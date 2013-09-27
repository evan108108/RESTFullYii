<?php
Yii::import('RestfullYii.events.Eventor.*');

class EventorUnitTest extends CTestCase
{
	public function testBoundObj()
	{
		$myObj = new myClass();
		$Eventor = new Eventor($myObj);
		$this->assertTrue(get_class($Eventor->getBoundObj()) == get_class($myObj));

		$myObjTwo = new myClassTwo();
		$Eventor->setBoundObj($myObjTwo);
		$this->assertTrue(get_class($Eventor->getBoundObj()) == get_class($myObjTwo));
	}

	public function testExclusiveEventEmit()
	{
		$myObj = new myClass();
		$Eventor = new Eventor($myObj);

		$Eventor->setExclusiveEventEmit(true);
		$this->assertEquals($Eventor->getExclusiveEventEmit(), true);

		$Eventor->setExclusiveEventEmit(false);
		$this->assertEquals($Eventor->getExclusiveEventEmit(), false);
	}

	public function testEventRegister()
	{
		$myObj = new myClass();
		$Eventor = new Eventor($myObj);

		$event_register = [ 'test'=>['someval'] ];
		$Eventor->setEventRegister($event_register);
		$this->assertEquals($Eventor->getEventRegister(), $event_register);
	}

	public function testClearEventRegister()
	{
		$myObj = new myClass();
		$Eventor = new Eventor($myObj);

		$event_register = [ 'test'=>['someval'] ];
		$Eventor->setEventRegister($event_register);
		$this->assertEquals($Eventor->getEventRegister(), $event_register);

		$Eventor->clearEventRegister();
		$this->assertEquals($Eventor->getEventRegister(), []);
	}

	public function testOn()
	{
		$myObj = new myClass();
		$Eventor = new Eventor($myObj);

		$event = 'testEvent';
		$listener = function() { echo 'test'; };

		$listener_signature = $Eventor->on($event, $listener);
		$this->assertTrue(is_string($listener_signature));

		$this->assertTrue(isset($Eventor->getEventRegister()[$event]));
		$this->assertTrue(isset($Eventor->getEventRegister()[$event][0]));
		$this->assertTrue(isset($Eventor->getEventRegister()[$event][0]['signature']));
		$this->assertEquals($Eventor->getEventRegister()[$event][0]['signature'], $listener_signature);
	}

	public function testEmit()
	{
		$myObj = new myClass();
		$Eventor = new Eventor($myObj);

		$event_1 = 'testEvent_1';
		$listener_1 = function() { echo 'test:' . $this->somevar; };
		$Eventor->on($event_1, $listener_1);

		$event_2 = 'testEvent_2';
		$listener_2 = function($var) { echo "test:{$var}:{$this->somevar}"; };
		$Eventor->on($event_2, $listener_2);

		$event_3 = 'testEvent_3';
		$listener_3 = function($var, $var2) { echo "test:{$var}:{$var2}:{$this->somevar}"; };
		$Eventor->on($event_3, $listener_3);
		
		ob_start();
			$Eventor->emit('testEvent_1');
			$output =  ob_get_contents();
    if (ob_get_length() > 0 ) { @ob_end_clean(); }

		$this->assertEquals($output, 'test:my class');

		ob_start();
			$Eventor->emit('testEvent_2', 't2');
			$output_2 =  ob_get_contents();
    if (ob_get_length() > 0 ) { @ob_end_clean(); }

		$this->assertEquals($output_2, 'test:t2:my class');
		
		ob_start();
			$Eventor->emit('testEvent_3', ['t3a', 't3b'] );
			$output_3 =  ob_get_contents();
    if (ob_get_length() > 0 ) { @ob_end_clean(); }

		$this->assertEquals($output_3, 'test:t3a:t3b:my class');
	
		$listener_4 = function() { echo "\ntest:b:" . $this->somevar; };
		$Eventor->on($event_1, $listener_4);

		ob_start();
			$Eventor->emit('testEvent_1');
			$output_4 =  ob_get_contents();
    if (ob_get_length() > 0 ) { @ob_end_clean(); }

		$this->assertEquals($output_4, "test:my class\ntest:b:my class");

		$myObj = new myClass();
		$Eventor = new Eventor($myObj, true);
		
		$Eventor->on($event_1, $listener_1);
		$Eventor->on($event_1, $listener_4);

		ob_start();
			$Eventor->emit('testEvent_1');
			$output_5 =  ob_get_contents();
    if (ob_get_length() > 0 ) { @ob_end_clean(); }
		
		$this->assertEquals($output_5, "\ntest:b:my class");

		$myObj = new myClass();
		$Eventor = new Eventor($myObj, false);

		$event_5 = 'testEvent_5';
		$listener_5 = function($var, $var2) { return "test:{$var}:{$var2}:{$this->somevar}"; };
		$Eventor->on($event_5, $listener_5);

		$listener_6 = function($var, $var2) { return "test:{$var}:{$var2}:{$this->somevar}:2"; };
		$Eventor->on($event_5, $listener_6);

		$event_results = $Eventor->emit('testEvent_5', ['v1', 'v2']);

		$this->assertEquals($event_results, [ 
			'test:v1:v2:my class',
			'test:v1:v2:my class:2',
		]);

		$myObj = new myClass();
		$Eventor = new Eventor($myObj, true);

		$event_6 = 'testEvent_6';
		$listener_7 = function($var, $var2) { return "test:{$var}:{$var2}:{$this->somevar}"; };
		$Eventor->on($event_6, $listener_7);

		$listener_8 = function($var, $var2) { return "test:{$var}:{$var2}:{$this->somevar}:2"; };
		$Eventor->on($event_6, $listener_8);

		$event_results = $Eventor->emit('testEvent_6', ['v1', 'v2']);

		$this->assertEquals($event_results, 'test:v1:v2:my class:2');
	}

	public function testRemoveListener()
	{
		$myObj = new myClass();
		$Eventor = new Eventor($myObj);

		$event_1 = 'testEvent_1';
		$listener_1 = function() { echo 'test:' . $this->somevar; };
		$listener_signature = $Eventor->on($event_1, $listener_1);
		$Eventor->removeListener($listener_signature);

		$this->assertTrue(!isset($Eventor->getEventRegister()[$event_1][0]));

		ob_start();
			$Eventor->emit('testEvent_1');
			$output =  ob_get_contents();
    if (ob_get_length() > 0 ) { @ob_end_clean(); }

		$this->assertEquals($output, '');
	}

	public function testRemoveEvent()
	{
		$myObj = new myClass();
		$Eventor = new Eventor($myObj);

		$event_1 = 'testEvent_1';
		$listener_1 = function() { echo 'test:' . $this->somevar; };
		$listener_signature = $Eventor->on($event_1, $listener_1);
		$Eventor->removeEvent($event_1);

		$this->assertTrue(!isset($Eventor->getEventRegister()[$event_1]));

		ob_start();
			$Eventor->emit('testEvent_1');
			$output =  ob_get_contents();
    if (ob_get_length() > 0 ) { @ob_end_clean(); }

		$this->assertEquals($output, '');
	}

	public function testEventExists()
	{
		$myObj = new myClass();
		$Eventor = new Eventor($myObj);

		$event_1 = 'my_event';
		$listener_1 = function() { echo 'test:' . $this->somevar; };
		$listener_signature = $Eventor->on($event_1, $listener_1);

		$this->assertEquals($Eventor->eventExists($event_1), true);
		$this->assertEquals($Eventor->eventExists('Event.That.Should.Not.Exist'), false);
	}
}

class myClass
{
	public $somevar = 'my class';
}

class myClassTwo
{
	public $somevar = 'my class 2';
}


