<?php
Yii::import('RestfullYii.actions.ERestBaseAction');
Yii::import('RestfullYii.actions.EActionRestDELETE');
Yii::import('RestfullYii.events.*');


/**
 * Test For Base Class For Rest Actions
 *
 * Tests action methods for rest delete behavior
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/test
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class EActionRestDELETEUnitTest extends ERestTestCase
{
	private $deleteAction;

	public function setUp()
	{
		parent::setUp();
		
		$controller = $this->getController()->Category;
		$controller->attachBehaviors(array(
			'class'=>'RestfullYii.behaviors.ERestBehavior'
		));
		$controller->injectEvents('req.delete.my_custom_route.render', function($param1='', $param2='') {
			echo "My Custom Route" . $param1 . $param2;
		});
		$controller->ERestInit();
		$this->deleteAction = new EActionRestDELETE($controller, 'REST.DELETE');
	}

	/**
	 * run
	 *
	 * tests EActionRestDELETE->run()
	 */ 
	public function testRunRESOURCES()
	{
		$result = $this->captureOB($this, function() {
			$this->deleteAction->run();
		});
		$this->assertInstanceOf('Exception', $result);
		$this->assertExceptionHasMessage('Method Not Allowed', $result);
	}


	/**
	 * run
	 *
	 * tests EActionRestDELETE->run(1, 'posts')
	 */ 
	public function testRunSUBRESOURCES()
	{
		$result = $this->captureOB($this, function() {
			$this->deleteAction->run(1, 'posts');
		});
		$this->assertInstanceOf('Exception', $result);
		$this->assertExceptionHasMessage('Method Not Allowed', $result);
	}


	/**
	 * run
	 *
	 * tests EActionRestDELETE->run(1, 'posts', 1)
	 */ 
	public function testRunSUBRESOURCE()
	{
		$result = $this->captureOB($this, function() {
			$this->deleteAction->run(1, 'posts', 1);
		});
		
		$this->assertJSONFormat($result);	
		$result = CJSON::decode($result);
		$this->assertEquals($result['success'], true);
		$this->assertEquals($result['message'], 'Sub-Resource Deleted');
		$this->assertEquals($result['data']['totalCount'], '1');
		$this->assertArrayHasKey('category', $result['data']);
		$this->assertArrayHasKey('id', $result['data']['category']);
		$this->assertEquals($result['data']['category']['id'], 1);
		$this->assertArrayHasKey('posts', $result['data']['category']);
		$this->assertEquals( $result['data']['category']['posts'], []);
	}


	/**
	 * run
	 *
	 * tests EActionRestDELETE->run('my_custom_route')
	 */ 
	public function testRunCUSTOM()
	{
		$result = $this->captureOB($this, function() {
			$this->deleteAction->run('my_custom_route');
		});
		$this->assertEquals($result, 'My Custom Route');

		$result = $this->captureOB($this, function() {
			$this->deleteAction->run('my_custom_route', '_p1');
		});
		$this->assertEquals($result, 'My Custom Route_p1');

		$result = $this->captureOB($this, function() {
			$this->deleteAction->run('my_custom_route', '_p1', '_p2');
		});
		$this->assertEquals($result, 'My Custom Route_p1_p2');
	}

	/**
	 * run
	 *
	 * tests EActionRestDELETE->run(1)
	 */ 
	public function testRunRESOURCE()
	{
		$result = $this->captureOB($this, function() {
			$this->deleteAction->run(1);
		});
		$this->assertJSONFormat($result);
		$result = CJSON::decode($result);
		$this->assertEquals($result['success'], true);
		$this->assertEquals($result['message'], 'Record Deleted');
		$this->assertEquals($result['data']['totalCount'], '1');
		$this->assertArrayHasKey('category', $result['data']);
		$this->assertArrayHasKey('id', $result['data']['category']);
		$this->assertEquals($result['data']['category']['id'], 1);
	}

	/**
	 * run
	 *
	 * tests EActionRestDELETE->run('WHAT-THE-RESOURCE')
	 */ 
	public function testRunResourceNotFound()
	{
		$result = $this->captureOB($this, function() {
			$this->deleteAction->run('WHAT-THE-RESOURCE');
		});
		$this->assertInstanceOf('Exception', $result);
		$this->assertExceptionHasMessage('Resource Not Found', $result);
	}

	/**
	 * handleDelete
	 *
	 * Tests EActionRestDELETE->handleDelete(1)
	 */  
	public function testHandleDelete()
	{
		$model = $this->deleteAction->handleDelete(1);
		$this->assertInstanceOf('Category', $model);
		$deleted_model = Category::model()->findByPk(1);
		$this->assertEquals($deleted_model, null);
	}

	/**
	 * handleSubresourceDelete
	 *
	 * Tests EActionRestDELETE->handleSubresourceDelete(1, 'posts', 1)
	 */  
	public function testHandleSubresourceDelete()
	{
		$model = $this->deleteAction->handleSubresourceDelete(1, 'posts', 1);
		$this->assertInstanceOf('Category', $model);
		$deleted_model = Category::model()->findByPk(1);
		$this->assertEquals($deleted_model->posts, []);
	}

	

}
