<?php
Yii::import('RestfullYii.actions.ERestBaseAction');
Yii::import('RestfullYii.actions.EActionRestGET');
Yii::import('RestfullYii.events.*');


/**
 * Test For Base Class For Rest Actions
 *
 * Tests action methods for rest GET behavior
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/test
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class EActionRestGETUnitTest extends ERestTestCase
{
	private $getAction;

	public function setUp()
	{
		parent::setUp();
		
		$controller = $this->getController()->Category;
		$controller->attachBehaviors(array(
			'class'=>'RestfullYii.behaviors.ERestBehavior'
		));
		$controller->injectEvents('req.get.my_custom_route.render', function($param1='', $param2='') {
			echo "My Custom Route" . $param1 . $param2;
		});
		$controller->ERestInit();
		$this->getAction = new EActionRestGET($controller, 'REST.GET');
	}


	/**
	 * run
	 *
	 * tests EActionRestGET->run()
	 */ 
	public function testRunRESOURCES()
	{
		$result = $this->captureOB($this, function() {
			$this->getAction->run();
		});
		$this->assertJSONFormat($result);	
		$this->assertJsonStringEqualsJsonString(
			$result,
			'{"success":true,"message":"Record(s) Found","data":{"totalCount":"6","category":[{"id":"1","name":"cat1","posts":[{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1"}]},{"id":"2","name":"cat2","posts":[{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1"},{"id":"2","title":"title2","content":"content2","create_time":"2013-08-07 10:09:42","author_id":"2"}]},{"id":"3","name":"cat3","posts":[{"id":"3","title":"title3","content":"content3","create_time":"2013-08-07 10:09:43","author_id":"3"}]},{"id":"4","name":"cat4","posts":[{"id":"4","title":"title4","content":"content4","create_time":"2013-08-07 10:09:44","author_id":"4"}]},{"id":"5","name":"cat5","posts":[{"id":"5","title":"title5","content":"content5","create_time":"2013-08-07 10:09:45","author_id":"5"}]},{"id":"6","name":"cat6","posts":[{"id":"6","title":"title6","content":"content6","create_time":"2013-08-07 10:09:46","author_id":"6"}]}]}}'
		);
	}

	/**
	 * run
	 *
	 * tests EActionRestGET->run('my_custom_route')
	 */ 
	public function testRunCUSTOM()
	{
		$result = $this->captureOB($this, function() {
			$this->getAction->run('my_custom_route');
		});
		$this->assertEquals($result, 'My Custom Route');

		$result = $this->captureOB($this, function() {
			$this->getAction->run('my_custom_route', '_p1');
		});
		$this->assertEquals($result, 'My Custom Route_p1');

		$result = $this->captureOB($this, function() {
			$this->getAction->run('my_custom_route', '_p1', '_p2');
		});
		$this->assertEquals($result, 'My Custom Route_p1_p2');
	}

	/**
	 * run
	 *
	 * tests EActionRestGET->run(1, 'posts')
	 */ 
	public function testRunSUBRESOURCES()
	{
		$result = $this->captureOB($this, function() {
			$this->getAction->run(1, 'posts');
		});
		$this->assertJSONFormat($result);	
		$this->assertJsonStringEqualsJsonString(
			$result,
			'{"success":true,"message":"Record(s) Found","data":{"totalCount":1,"post":[{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1"}]}}'
		);
	}

	/**
	 * run
	 *
	 * tests EActionRestGET->run(1, 'posts', 1)
	 */ 
	public function testRunSUBRESOURCE()
	{
		$result = $this->captureOB($this, function() {
			$this->getAction->run(1, 'posts', 1);
		});
		$this->assertJSONFormat($result);	
		
		$this->assertJsonStringEqualsJsonString(
			$result,
			'{"success":true,"message":"Record(s) Found","data":{"totalCount":1,"post":{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1"}}}'
		);
	}

	/**
	 * run
	 *
	 * tests EActionRestGET->run(1)
	 */ 
	public function testRunRESOURCE()
	{
		$result = $this->captureOB($this, function() {
			$this->getAction->run(1);
		});
		$this->assertJSONFormat($result);	
		$this->assertJsonStringEqualsJsonString(
			$result,
			'{"success":true,"message":"Record Found","data":{"totalCount":1,"category":{"id":"1","name":"cat1","posts":[{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1"}]}}}'
		);
	}

	/**
	 * run
	 *
	 * tests EActionRestGET->run('WHAT-THE-RESOURCE')
	 */ 
	public function testRunResourceNotFound()
	{
		$result = $this->captureOB($this, function() {
			$this->getAction->run('WHAT-THE-RESOURCE');
		});
		$this->assertInstanceOf('Exception', $result);
		$this->assertExceptionHasMessage('Resource Not Found', $result);
	}

}
