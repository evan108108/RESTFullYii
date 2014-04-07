<?php
Yii::import('RestfullYii.actions.ERestBaseAction');
Yii::import('RestfullYii.actions.EActionRestPUT');
Yii::import('RestfullYii.events.*');


/**
 * Test For Base Class For Rest Actions
 *
 * Tests action methods for rest POST behavior
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/test
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class EActionRestPUTUnitTest extends ERestTestCase
{
	private $putAction;

	public function setUp()
	{
		parent::setUp();
		
		$controller = $this->getController()->Category;
		$controller->attachBehaviors(array(
			'class'=>'RestfullYii.behaviors.ERestBehavior'
		));
		$controller->injectEvents('req.put.my_custom_route.render', function($data, $param1='', $param2='') {
			echo "My Custom Route" . $param1 . $param2 . '_' . implode('_', $data);
		});
		$controller->injectEvents('req.data.read', function() {
			return [
				'id' => '1',
				'name' => 'cat-updated',
			];
		});

		$controller->ERestInit();
		$this->putAction = new EActionRestPUT($controller, 'REST.PUT');
	}

	/**
	 * run
	 *
	 * tests EActionRestPUT->run()
	 */ 
	public function testRunRESOURCES()
	{
		$result = $this->captureOB($this, function() {
			$this->putAction->run();
		});
		$this->assertInstanceOf('Exception', $result);
		$this->assertExceptionHasMessage('Method Not Allowed', $result);
	}

	/**
	 * run
	 *
	 * tests EActionRestPUT->run('my_custom_route')
	 */ 
	public function testRunCUSTOM()
	{
		$result = $this->captureOB($this, function() {
			$this->putAction->run('my_custom_route');
		});
		$this->assertEquals($result, 'My Custom Route_1_cat-updated');

		$result = $this->captureOB($this, function() {
			$this->putAction->run('my_custom_route', '_p1');
		});
		$this->assertEquals($result, 'My Custom Route_p1_1_cat-updated');

		$result = $this->captureOB($this, function() {
			$this->putAction->run('my_custom_route', '_p1', '_p2');
		});
		$this->assertEquals($result, 'My Custom Route_p1_p2_1_cat-updated');
	}

	/**
	 * run
	 *
	 * tests EActionRestPUT->run(1, 'posts')
	 */ 
	public function testRunSUBRESOURCES()
	{
		$result = $this->captureOB($this, function() {
			$this->putAction->run(1, 'posts');
		});
		$this->assertInstanceOf('Exception', $result);
		$this->assertExceptionHasMessage('Method Not Allowed', $result);
	}

	/**
	 * run
	 * 
	 * tests EActionRestPUT->run(1, 'posts', 1)
	 */ 
	public function testSubresource()
	{
		$result = $this->captureOB($this, function() {
			$this->putAction->run(1, 'posts', 2);
		});	
		$this->assertJSONFormat($result);
		$this->assertJsonStringEqualsJsonString(
			$result,
			'{"success":true,"message":"Subresource Added","data":{"totalCount":"1","category":{"id":"1","name":"cat1","posts":[{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1"},{"id":"2","title":"title2","content":"content2","create_time":"2013-08-07 10:09:42","author_id":"2"}]}}}'
		);
	}

	/**
	 * run
	 *
	 * tests EActionRestPUT->run(1)
	 */ 
	public function testRunRESOURCE()
	{
		$result = $this->captureOB($this, function() {
			$this->putAction->run(1);
		});
		$this->assertJSONFormat($result);
		$this->assertJsonStringEqualsJsonString(
			$result,
			'{"success":true,"message":"Record Updated","data":{"totalCount":"1","category":{"id":"1","name":"cat-updated","posts":[{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1"}]}}}'
		);
	}

	/**
	 * run
	 *
	 * tests EActionRestPUT->run('WHAT-THE-RESOURCE')
	 */ 
	public function testRunResourceNotFound()
	{
		$result = $this->captureOB($this, function() {
			$this->putAction->run('WHAT-THE-RESOURCE');
		});
		$this->assertInstanceOf('Exception', $result);
		$this->assertExceptionHasMessage('Resource Not Found', $result);
	}

	/**
	 * handlePut
	 *
	 *  test EActionRestPUT->handlePut()
	 */ 
	public function testHandlePut()
	{
		$new_model = $this->putAction->handlePut(1);
		$this->assertInstanceOf('Category', $new_model);
		$this->assertEquals($new_model->id, 1);
	}

	/**
	 * handlePutSubresource
	 *
	 *  test EActionRestPUT->handlePutSubresource(1, 'posts', 2)
	 */ 
	public function testHandlePutSubresource()
	{
		$new_model = $this->putAction->handlePutSubresource(1, 'posts', 2);
		$this->assertInstanceOf('Category', $new_model);
		$this->assertEquals($new_model->posts[1]->id, 2);
	}

}
