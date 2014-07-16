<?php
Yii::import('RestfullYii.behaviors.ERestBehavior');

/**
 * ERestBehaviorUnitTest
 *
 * Tests ERestBehavior
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class ERestBehaviorUnitTest extends ERestTestCase
{
	/**
	 * ERestInit
	 *
	 * tests ERestBehavior->ERestInit()
	 */
	public function testERestInit()
	{
		$erb = $this->getNewERestBehavior();
		$erb->ERestInit();
		$this->assertInstanceOf('Eventor', $this->getPrivateProperty($erb, 'event'));
		$this->assertInstanceOf('ERestEventListenerRegistry', $this->getPrivateProperty($erb, 'listeners'));
		$this->assertInstanceOf('EHttpStatus', $this->getPrivateProperty($erb, 'http_status'));
		$this->assertInstanceOf('ERestResourceHelper', $this->getPrivateProperty($erb, 'resource_helper'));
	}

	/**
	 * registerEventListeners
	 *
	 * tests ERestBehavior->registerEventListeners()
	 */
	public function testRegisterEventListeners()
	{
		$erb = $this->getNewERestBehavior();
		$erb->ERestInit();
		$this->invokePrivateMethod($erb, 'registerEventListeners', []);
		$eventor = $this->getPrivateProperty($erb, 'event');
		foreach($this->getEventList() as $event_name) {
			if(strpos($event_name, 'pre.filter.') === false && strpos($event_name, 'post.filter.') === false) {
				$this->assertTrue($eventor->eventExists($event_name), "Event ($event_name) is missing");
			}
		}
		
		$erb->owner->injectEvents(ERestEvent::CONFIG_APPLICATION_ID, function() {
			return 'TEST_APPLICATION_ID';
		});
		$this->invokePrivateMethod($erb, 'registerEventListeners', []);
		$eventor = $this->getPrivateProperty($erb, 'event');
		$this->assertEquals('TEST_APPLICATION_ID', $eventor->emit(ERestEvent::CONFIG_APPLICATION_ID));
		
		Yii::app()->params['RestfullYii'] = [
			ERestEvent::REQ_AUTH_USERNAME => function() {
				return 'TEST_GLOBAL_USERNAME';
			}
		];
		$this->invokePrivateMethod($erb, 'registerEventListeners', []);
		$eventor = $this->getPrivateProperty($erb, 'event');
		$this->assertEquals('TEST_GLOBAL_USERNAME', $eventor->emit(ERestEvent::REQ_AUTH_USERNAME));
	}

	/**
	 * onRest
	 *
	 * tests ERestBehavior->onRest()
	 */
	public function testOnRest()
	{
		$erb = $this->getNewERestBehavior();
		$erb->ERestInit();

		$erb->onRest('my.test.event', function($testing) {
			return $testing;
		});
		$eventor = $this->getPrivateProperty($erb, 'event');
		$this->assertTrue($eventor->eventExists('my.test.event'));
		$this->assertEquals('test.val', $eventor->emit('my.test.event', 'test.val'));
	}

	/**
	 * emitRest
	 *
	 * tests ERestBehavior->emitRest()
	 */
	public function testEmitRest()
	{
		$erb = $this->getNewERestBehavior();
		$erb->ERestInit();

		$erb->onRest('my.test.event', function($testing) {
			return $testing;
		});
		$eventor = $this->getPrivateProperty($erb, 'event');
		$this->assertTrue($eventor->eventExists('my.test.event'));
		$this->assertEquals('test.val', $erb->emitRest('my.test.event', 'test.val'));
	}

	/**
	 * emitRest
	 *
	 * tests ERestBehavior->emitRest()
	 */
	public function testEmitRestPrePostFilter()
	{
		$erb = $this->getNewERestBehavior();
		$erb->ERestInit();

		$erb->onRest('pre.filter.my.test.event', function($testing) {
			return 'pre.filter.' . $testing;
		});

		$erb->onRest('my.test.event', function($testing) {
			return $testing;
		});

		$erb->onRest('post.filter.my.test.event', function($result) {
			return $result . ".post.filter";
		});

		$eventor = $this->getPrivateProperty($erb, 'event');
		$this->assertTrue($eventor->eventExists('my.test.event'));
		$this->assertEquals('pre.filter.test.val.post.filter', $erb->emitRest('my.test.event', 'test.val'));
	}

	/**
	 * emitRest
	 *
	 * tests ERestBehavior->emitRest()
	 */
	public function testEmitRestPrePostFilterMultiParam()
	{
		$erb = $this->getNewERestBehavior();
		$erb->ERestInit();

		$erb->onRest('pre.filter.my.test.event', function($first_param, $second_param, $third_param ) {
			return ["pre.filter.$first_param", ($second_param + 1), ($third_param - 1)];
		});

		$erb->onRest('my.test.event', function($first_param, $second_param, $third_param) {
			return $first_param . '.' . $second_param . '.' . $third_param;
		});

		$erb->onRest('post.filter.my.test.event', function($result) {
			return $result . ".post.filter";
		});

		$eventor = $this->getPrivateProperty($erb, 'event');
		$this->assertTrue($eventor->eventExists('my.test.event'));
		$this->assertEquals('pre.filter.test.val.2.4.post.filter', $erb->emitRest('my.test.event', ['test.val', 1, 5]));
	}

	/**
	 * emitRest
	 *
	 * tests ERestBehavior->emitRest()
	 */
	public function testEmitRestPrePostFilterMultiParamAndArrayResult()
	{
		$erb = $this->getNewERestBehavior();
		$erb->ERestInit();

		$erb->onRest('pre.filter.my.test.event', function($first_param, $second_param, $third_param ) {
			return ["pre.filter.$first_param", ($second_param + 1), ($third_param - 1)];
		});

		$erb->onRest('my.test.event', function($first_param, $second_param, $third_param) {
			return [$first_param, $second_param, $third_param];
		});

		$erb->onRest('post.filter.my.test.event', function($result) {
			$result[0] .= '.post.filter';
			$result[1]++;
			$result[2] += 10;
			return $result;
		});

		$eventor = $this->getPrivateProperty($erb, 'event');
		$this->assertTrue($eventor->eventExists('my.test.event'));
		$expected = ['pre.filter.test.val.post.filter', 3, 14];
		$this->assertArraysEqual($expected, $erb->emitRest('my.test.event', ['test.val', 1, 5]));
	}
	
	/**
	 * emitRest
	 *
	 * tests ERestBehavior->emitRest()
	 */
	public function testEmitRestPreNoParam()
	{
		$erb = $this->getNewERestBehavior();
		$erb->ERestInit();

		$erb->onRest('pre.filter.my.test.event', function() {
			//This pre filter return should not have any effect on the event result
			return false;
		});

		$erb->onRest('my.test.event', function() {
			return true;
		});

		$this->assertTrue($erb->emitRest('my.test.event'));
	}

	/**
	 * emitRest
	 *
	 * tests ERestBehavior->emitRest()
	 */
	public function testEmitRestPostNoReturnResult()
	{
		$erb = $this->getNewERestBehavior();
		$erb->ERestInit();

		$erb->onRest('my.test.event', function() {
			//Do something with no return
		});

		$erb->onRest('post.filter.my.test.event', function($result) {
			//This post filter should execute with the $result equal to Null
			return $result;
		});

		$this->assertTrue(is_null($erb->emitRest('my.test.event')));
	}

	/**
	 * eventExists
	 *
	 * tests ERestBehavior->eventExists()
	 */
	public function testEventExists()
	{
		$erb = $this->getNewERestBehavior();
		$erb->ERestInit();

		$this->assertTrue(!$erb->eventExists('my.test.event'));

		$erb->onRest('my.test.event', function($testing) {
			return $testing;
		});
		$this->assertTrue($erb->eventExists('my.test.event'));
	}

	/**
	 * getHttpStatus
	 *
	 * tests ERestBehavior->getHttpStatus()
	 */ 
	public function testGetHttpStatus()
	{
		$erb = $this->getNewERestBehavior();
		$erb->ERestInit();

		$this->assertInstanceOf('EHttpStatus', $erb->getHttpStatus());
	}

	/**
	 * setHttpStatus
	 *
	 * tests ERestBehavior->setHttpStatus()
	 *
	 */ 
	public function testSetHttpStatus()
	{
		$erb = $this->getNewERestBehavior();
		$erb->ERestInit();

		$http_status = $erb->setHttpStatus(500, 'TEST ERROR MESSAGE');
		$this->assertInstanceOf('EHttpStatus', $http_status);

		$this->assertEquals('HTTP/1.1 500 TEST ERROR MESSAGE', (string) $http_status);
	}

	/**
	 * getResourceHelper
	 *
	 * tests ERestBehavior->getResourceHelper()
	 */ 
	public function testGetResourceHelper()
	{
		$erb = $this->getNewERestBehavior();
		$erb->ERestInit();

		$this->assertInstanceOf('ERestResourceHelper', $erb->getResourceHelper());
	}

	/**
	 * getSubresourceHelper
	 *
	 * tests ERestBehavior->getSubresourceHelper()
	 */ 
	public function testGetSubresourceHelper()
	{
		$erb = $this->getNewERestBehavior();
		$erb->ERestInit();

		$this->assertInstanceOf('ERestSubresourceHelper', $erb->getSubresourceHelper());
	}


	/**
	 * onException
	 *
	 * tests ERestBehavior->onException()
	 */
	public function testOnException()
	{
		$erb = $this->getNewERestBehavior();
		$erb->ERestInit();

		$except = $this->captureOB($erb, function() {
			$this->onException(new CExceptionEvent($this, New CHttpException(500, 'TEST ERROR MESSAGE')));
		});

		$this->assertJsonStringEqualsJsonString($except, '{"success":false,"message":"TEST ERROR MESSAGE","data":{"errorCode":500,"message":"TEST ERROR MESSAGE"}}');
	}

	/**
	 * renderJSON
	 *
	 * tests ERestBehavior->renderJSON()
	 */ 
	public function testRenderJSON()
	{
		$erb = $this->getNewERestBehavior();
		$erb->ERestInit();

		$render_raw = $erb->renderJSON(['type'=>'raw', 'data'=>['test_param'=>'test_value']]);
		
		$this->assertJsonStringEqualsJsonString($render_raw, '{"test_param":"test_value"}');

		$render_rest = $erb->renderJSON([
			'type'				=> 'rest',
			'success'			=> true,
			'message'			=> "Record Found",
			'totalCount'	=> 1,
			'modelName'		=> 'Category',
			'relations'		=> [],
			'data'				=> Category::model()->findByPk(1),
		]);
		$this->assertJsonStringEqualsJsonString($render_rest, '{"success":true,"message":"Record Found","data":{"totalCount":1,"category":{"id":"1","name":"cat1"}}}');
		
		$render_error = $erb->renderJSON([
			'type'			=> 'error',
			'success'		=> false,
			'message'		=> 'TEST ERROR',
			'errorCode' => 500,
		]);
		$this->assertJsonStringEqualsJsonString($render_error, '{"success":false,"message":"TEST ERROR","data":{"errorCode":500,"message":"TEST ERROR"}}');
	}

	/**
	 * testFinalRender
	 *
	 * test ERestBehavior->finalRender()
	 */
	public function testFinalRender()
	{
		$erb = $this->getNewERestBehavior();
		$erb->ERestInit();

		$data_array = ['one'=>1, 'two'=>2];

		$render_array_rest = $this->captureOB($erb, function() use ($data_array) {
			$this->finalRender($data_array);
		});

		$this->assertJsonStringEqualsJsonString($render_array_rest, CJSON::encode($data_array));


		$data_json = CJSON::encode($data_array);

		$render_json_rest = $this->captureOB($erb, function() use ($data_json) {
			$this->finalRender($data_json);
		});

		$this->assertJsonStringEqualsJsonString($render_json_rest, $data_json);
	}

	/**
	 * testGetUriAndHttpVerb
	 *
	 * tests ERestBehavior->getURIAndHTTPVerb()
	 * note: This is an incomplete test as it can only test the CLI implimentation
	 * Additionally since we are not entering via an action we have no HTTP VERB
	 */
		public function testGetUriAndHttpVerb()
		{
				$erb = $this->getNewERestBehavior();
				$erb->ERestInit();

				$_GET['id'] = 1;
				$_GET['param1'] = 'p1';
				$_GET['param2'] = 'p2';

				$this->assertArraysEqual(['/api/category/1/p1/p2', 'UNKOWN'], $erb->getURIAndHTTPVerb());
		}

		/**
		 * getController
		 *
		 * tests ERestBehavior->getController()
		 */
		public function testGetController()
		{
				$erb = $this->getNewERestBehavior();
				$erb->ERestInit();

				$controller = $this->invokePrivateMethod($erb, 'getController', []);
				$this->assertInstanceOf('CategoryController', $controller);
		}

	/**
	 * getNewERestBehavior
	 *
	 * returns the ERestBehavior;
	 */ 
	public function getNewERestBehavior()
	{
		$controller = $this->getController()->Category;
		$controller->attachBehavior('ERestBehavior', new ERestBehavior());
		$erb = $controller->asa('ERestBehavior');
		$this->assertInstanceOf('ERestBehavior', $erb);
		return $erb;
	} 

	/**
	 * getEventList
	 *
	 * @return returns a list of all event names (CONST's) defined in ERestEvent
	 */ 
	public function getEventList()
	{
		$refl = new ReflectionClass('ERestEvent');
		return $refl->getConstants();
	}
}
