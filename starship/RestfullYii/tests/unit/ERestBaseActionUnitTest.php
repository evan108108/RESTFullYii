<?php
Yii::import('RestfullYii.actions.ERestBaseAction');
Yii::import('RestfullYii.events.*');


/**
 * Test For Base Class For Rest Actions
 *
 * Provides helper methods for rest actions
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/test
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class ERestBaseActionUnitTest extends ERestTestCase
{
	private $baseAction;

	public function setUp()
	{
		parent::setUp();
		$controller = $this->getController()->Category;
		$controller->attachBehaviors(array(
			'class'=>'RestfullYii.behaviors.ERestBehavior'
		));
		$controller->injectEvents('req.get.my_custom_route.render', function($param1=null, $param2=null) {
			echo "My Custom Route";
		});
		$controller->ERestInit();
		$this->baseAction = new ERestBaseAction($controller, 'REST.GET');
	}

	/**
	 * testRequestType
	 *
	 * Test for ERestBaseAction->requestType()
	 */ 
	public function testRequestType()
	{
		$this->assertEquals($this->baseAction->getRequestActionType(null, null, null, 'get'), 'RESOURCES');
		$this->assertEquals($this->baseAction->getRequestActionType('my_custom_route', null, null, 'get'), 'CUSTOM');
		$this->assertEquals($this->baseAction->getRequestActionType('my_custom_route', 1, null, 'get'), 'CUSTOM');
		$this->assertEquals($this->baseAction->getRequestActionType('my_custom_route', 1, 2, 'get'), 'CUSTOM');
		$this->assertEquals($this->baseAction->getRequestActionType(1, 'posts', null, 'get'), 'SUBRESOURCES');
		$this->assertEquals($this->baseAction->getRequestActionType(1, 'posts', 1, 'get'), 'SUBRESOURCE');
		$this->assertEquals($this->baseAction->getRequestActionType(1, null, null, 'get'), 'RESOURCE');
	}

	/**
	 * testFinalRender
	 *
	 * Test for ERestBaseAction->finalRender()
	 */
	public function testFinalRender()
	{
		$data_json = CJSON::encode(['one'=>1, 'two'=>2]);

		$result = $this->captureOB($this->baseAction, function() use ($data_json) {
			$this->finalRender(function($v, $h) use($data_json) {
				return $data_json;
			});
		});

		$this->assertJsonStringEqualsJsonString($data_json, $result);
	}

	/**
	 * testGetModel
	 *
	 * Test for ERestBaseAction->getModel()
	 */ 
	public function testGetModel()
	{
		$this->assertInstanceOf('Category', $this->baseAction->getModel(null, true));
		$this->assertInstanceOf('Category', $this->baseAction->getModel(1, false));
		$this->assertEquals(1, $this->baseAction->getModel(1, false)->id);
		$this->assertNotEquals(1, $this->baseAction->getModel(1, true)->id);
	}

	/**
	 * testGetModelCount
	 *
	 * Test for ERestBaseAction->getModelCount()
	 */ 
	public function testGetModelCount()
	{
		$category = new Category();
		$count = $category->count();
		$this->assertEquals($count, $this->baseAction->getModelCount(null));
		$this->assertEquals(1, $this->baseAction->getModelCount(1));
	}

	/**
	 * testGetRelations
	 *
	 * Test for ERestBaseAction->getRelations()
	 */ 
	public function testGetRelations()
	{
		$relations = $this->baseAction->getRelations();

		$model = new Category();
		$nestedRelations = [];
		foreach($model->metadata->relations as $rel=>$val)
		{
			$className = $val->className;
			$rel_model = call_user_func([$className, 'model']);
			if(!is_array($rel_model->tableSchema->primaryKey) && substr($rel, 0, 1) != '_') {
				$this->assertContains($rel, $relations);
				$nestedRelations[] = $rel;
			}
		}
		$this->assertArraysEqual($nestedRelations, $relations);
	}

	/**
	 * testGetSubresourceCount
	 *
	 * Test for ERestBaseAction->getSubresourceCount()
	 */ 
	public function testGetSubresourceCount()
	{
		foreach(Category::model()->findAll() as $cat)
		{
			$count = count($cat->posts);
			$this->assertEquals($count, $this->baseAction->getSubresourceCount($cat->id, 'posts'));
		}
		$this->assertEquals(1, $this->baseAction->getSubresourceCount(1, 'posts', 1));
	}

	/**
	 * testGetSubresourceClassName
	 *
	 * Test for ERestBaseAction->getSubresourceClassName()
	 */ 
	public function testGetSubresourceClassName()
	{
		$this->assertEquals('Post', $this->baseAction->getSubresourceClassName('posts'));
	}

	/**
	 * testGetSubresources
	 *
	 * Test for ERestBaseAction->getSubresources()
	 */ 
	public function testGetSubresources()
	{
		foreach(Category::model()->findAll() as $cat) {
			$this->assertArraysEqual($cat->posts, $this->baseAction->getSubresources($cat->id, 'posts'));
		}
	}

	/**
	 * testGetSubresource
	 *
	 * Test for ERestBaseAction->getSubresources()
	 */ 
	public function testGetSubresource()
	{
		foreach(Category::model()->findAll() as $cat) {
			foreach($cat->posts as $post) {
				$sub = $this->baseAction->getSubresource($cat->id, 'posts', $post->id);
				$this->assertEquals($post->id, $this->baseAction->getSubresource($cat->id, 'posts', $post->id)->id);
			}
		}
	}

}
