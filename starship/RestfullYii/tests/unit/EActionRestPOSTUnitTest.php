<?php
Yii::import('RestfullYii.actions.ERestBaseAction');
Yii::import('RestfullYii.actions.EActionRestPOST');
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
class EActionRestPOSTUnitTest extends ERestTestCase
{
	private $postAction;

	public function setUp()
	{
		parent::setUp();
		
		$controller = $this->getController()->Category;
		$controller->attachBehaviors(array(
			'class'=>'RestfullYii.behaviors.ERestBehavior'
		));
		$controller->injectEvents('req.post.my_custom_route.render', function($data, $param1='', $param2='') {
			echo "My Custom Route" . $param1 . $param2 . '_' . implode('_', $data);
		});
		$controller->injectEvents('req.data.read', function() {
			return [
				'name' => 'cat-new',
			];
		});

		$controller->ERestInit();
		$this->postAction = new EActionRestPOST($controller, 'REST.POST');
	}

	/**
	 * run
	 *
	 * tests EActionRestPOST->run()
	 */ 
	public function testRunRESOURCES()
	{
		$result = $this->captureOB($this, function() {
			$this->postAction->run();
		});
		$this->assertJSONFormat($result);	
		$this->assertJsonStringEqualsJsonString(
			$result,
			'{"success":true,"message":"Record Created","data":{"totalCount":1,"category":{"id":"7","name":"cat-new","posts":[]}}}'
		);
	}

	/**
	 * run
	 *
	 * tests EActionRestPOST->run('my_custom_route')
	 */ 
	public function testRunCUSTOM()
	{
		$result = $this->captureOB($this, function() {
			$this->postAction->run('my_custom_route');
		});
		$this->assertEquals($result, 'My Custom Route_cat-new');

		$result = $this->captureOB($this, function() {
			$this->postAction->run('my_custom_route', '_p1');
		});
		$this->assertEquals($result, 'My Custom Route_p1_cat-new');

		$result = $this->captureOB($this, function() {
			$this->postAction->run('my_custom_route', '_p1', '_p2');
		});
		$this->assertEquals($result, 'My Custom Route_p1_p2_cat-new');
	}

	/**
	 * run
	 * 
	 * tests EActionRestPOST->run(1, 'posts')
	 */ 
	public function testSubresources()
	{
		$result = $this->captureOB($this, function() {
			$this->postAction->run(1, 'posts');
		});
		$this->assertInstanceOf('Exception', $result);
		$this->assertExceptionHasMessage('Method Not Allowed', $result);
	}

	/**
	 * run
	 * 
	 * tests EActionRestPOST->run(1, 'posts', 1)
	 */ 
	public function testSubresource()
	{
		$result = $this->captureOB($this, function() {
			$this->postAction->run(1, 'posts', 1);
		});
		$this->assertInstanceOf('Exception', $result);
		$this->assertExceptionHasMessage('Method Not Allowed', $result);
	}

	/**
	 * run
	 * 
	 * tests EActionRestPOST->run(1)
	 */ 
	public function testResource()
	{
		$result = $this->captureOB($this, function() {
			$this->postAction->run(1);
		});
		$this->assertInstanceOf('Exception', $result);
		$this->assertExceptionHasMessage('Method Not Allowed', $result);
	}

	/**
	 * run
	 *
	 * tests EActionRestPOST->run('WHAT-THE-RESOURCE')
	 */ 
	public function testRunResourceNotFound()
	{
		$result = $this->captureOB($this, function() {
			$this->postAction->run('WHAT-THE-RESOURCE');
		});
		$this->assertInstanceOf('Exception', $result);
		$this->assertExceptionHasMessage('Resource Not Found', $result);
	}

	/**
	 * handlePost
	 *
	 *  test EActionRestPOST->handlePost()
	 */ 
	public function testHandlePost()
	{
		$new_model = $this->postAction->handlePost();
		$this->assertInstanceOf('Category', $new_model);
		$this->assertEquals($new_model->id, 7);
	}

}
