<?php
Yii::import('RestfullYii.components.ERestRequestReader');
Yii::import('RestfullYii.behaviors.ERestBehavior');

/**
 * ERestSubresourceHelperUnitTest
 * 
 * tests ERestSubresourceHelperUnitTest
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class ERestSubresourceHelperUnitTest extends ERestTestCase
{
	/**
	 * __construct
	 *
	 * tests ERestSubresourceHelper constructor
	 */
	public function testConstruct()
	{
		$controller = $this->getController()->Category;
		$controller->attachBehavior('ERestBehavior', new ERestBehavior());
		$erb = $controller->asa('ERestBehavior');
		$erb->ERestInit();

		$esrh = new ERestSubresourceHelper([$erb, 'emitRest']);
		$this->assertEquals($this->getPrivateProperty($esrh, 'emitter'), [$erb, 'emitRest']);		
	}

	/**
	 * setEmitter
	 *
	 * tests ERestSubresourceHelper->setEmitter()
	 */
	public function testSetEmitter()
	{
		$esrh = $this->getERestSubresourceHelper();
		$esrh->setEmitter([$this, 'emitterForTesting']);
		$this->assertEquals($this->getPrivateProperty($esrh, 'emitter'), [$this, 'emitterForTesting']);		
	}

	/**
	 * getEmitter
	 *
	 * tests ERestSubresourceHelper->getEmitter()
	 */
	public function testGetEmitter()
	{
		$esrh = $this->getERestSubresourceHelper();
		$this->assertEquals($this->getPrivateProperty($esrh, 'emitter'), $esrh->getEmitter());
	}

	/**
	 * isSubresource
	 *
	 * tests ERestSubresourceHelper->isSubresource()
	 */
	public function testIsSubresource()
	{
		$model = $this->getModel('Post');
		$model = $model->findByPk(1);
		$esrh = $this->getERestSubresourceHelper();
		$this->assertTrue($esrh->isSubresource($model, 'categories', 'GET'));
		$this->assertTrue(!$esrh->isSubresource($model, 'author', 'POST'));
		$this->assertTrue(!$esrh->isSubresource($model, 'author', 'PUT'));
		$this->assertTrue(!$esrh->isSubresource($model, 'author', 'DELETE'));
	}

	/**
	 * getSubresourceClassName
	 *
	 * tests ERestSubresourceHelper->getSubresourceClassName()
	 */
	public function testGetSubresourceClassName()
	{
		$model = $this->getModel('Post');
		$esrh = $this->getERestSubresourceHelper();
		$this->assertEquals('Category', $esrh->getSubresourceClassName($model, 'categories'));
		$this->assertEquals('User', $esrh->getSubresourceClassName($model, 'author'));
	}

	/**
	 * getSubresourceCount
	 *
	 * tests ERestSubresourceHelper->getSubresourceCount()
	 */ 
	public function testGetSubresourceCount()
	{
		$model = $this->getModel('Post');
		$model = $model->findByPk(1);
		$esrh = $this->getERestSubresourceHelper();
		$this->assertEquals(2, $esrh->getSubresourceCount($model, 'categories'));
		$this->assertEquals(1, $esrh->getSubresourceCount($model, 'categories', 1));
	}

	/**
	 * getSubresourceAR
	 *
	 * tests ERestSubresourceHelper->getSubresourceAR()
	 */
	public function testGetSubresourceAR()
	{
		$model = $this->getModel('Post');
		$model = $model->findByPk(1);
		$esrh = $this->getERestSubresourceHelper();
		$sar = $this->invokePrivateMethod($esrh, 'getSubresourceAR', [$model, 'categories']);
		$this->assertEquals($model->getActiveRelation('categories'), $sar->active_relation);
		$this->assertEquals('Category', get_class($sar->model));
	}

	/**
	 * getSubresource
	 *
	 * tests ERestSubresourceHelper->getSubresource()
	 */ 
	public function testGetSubresource()
	{
		$model = $this->getModel('Post');
		$model = $model->findByPk(1);
		$esrh = $this->getERestSubresourceHelper();
		$this->assertJsonStringEqualsJsonString(CJSON::encode($model->categories), CJSON::encode($esrh->getSubresource($model, 'categories')));
		$cat = Category::model()->findByPk(1);
		$this->assertJsonStringEqualsJsonString(CJSON::encode($cat), CJSON::encode($esrh->getSubresource($model, 'categories', 1)[0]));
	}

	/**
	 * getSubresourceMeta
	 *
	 * tests ERestSubresourceHelper->getSubresourceMeta()
	 */ 
	public function testGetSubresourceMeta()
	{
		$model = $this->getModel('Post');
		$esrh = $this->getERestSubresourceHelper();
		$tblSchema = $esrh->getSubresourceMeta($model, 'categories');
		$this->assertArraysEqual(['post_id', 'category_id'], $tblSchema[0]->primaryKey);
		$this->assertEquals('tbl_post_category', $tblSchema[0]->name);
	}

	/**
	 * putSubresourceHelper
	 *
	 * tests ERestSubresourceHelper->putSubresourceHelper()
	 */ 
	public function testPutSubresourceHelper()
	{
		$esrh = $this->getERestSubresourceHelper();

		$model = $this->getModel('Post');
		$model = $model->findByPk(1);

		$tblSchema = $esrh->getSubresourceMeta($model, 'categories');
		$fks = $tblSchema[0]->primaryKey;
		$relation_table = $tblSchema[0]->name;

		$this->assertTrue($esrh->putSubresourceHelper($model, 'categories', 4));
		$model = $this->getModel('Post');
		$model = $model->with('categories')->findByPk(1, ['condition'=>'categories.id=4']);
		$this->assertEquals(1, count($model->categories));
		$this->assertEquals(4, $model->categories[0]->id);
	}

	/**
	 * saveSubresource
	 *
	 * test ERestSubresourceHelper->saveSubresource
	 */ 
	public function testSaveSubresource()
	{
		$esrh = $this->getERestSubresourceHelper();

		$model = $this->getModel('Post');
		$model = $model->findByPk(1);

		$this->assertTrue($esrh->deleteSubResource($model, 'categories', 1));
		$results = $model->with('categories')->findByPk(1, ['condition'=>'categories.id=1']);
		$this->assertEquals(0, count($results));
	}

	/**
	 * getSubRecourcesPKAttribute
	 *
	 * test ERestSubresourceHelper->getSubRecourcesPKAttribute
	 */
	public function testGetSubRecourcesPKAttribute()
	{
		$esrh = $this->getERestSubresourceHelper();
		$model = $this->getModel('Post');
		$model = $model->findByPk(1);
		$srPK = $esrh->getSubRecourcesPKAttribute($this->invokePrivateMethod($esrh, 'getSubresourceAR', [$model, 'categories']));
		$this->assertEquals(Category::model()->tableSchema->primaryKey, $srPK);
	}

	/**
	 * getERestResourceHelper
	 *
	 * returns instance of ERestResourceHelper
	 *
	 * @return (Object) (ERestResourceHelper)
	 */
	public function getERestSubresourceHelper()
	{
		$controller = $this->getController()->Category;
		$controller->attachBehavior('ERestBehavior', new ERestBehavior());
		$erb = $controller->asa('ERestBehavior');
		$erb->ERestInit();
		$esrh = $erb->getSubresourceHelper();


		$this->assertInstanceOf('ERestSubresourceHelper', $esrh);
		return $esrh;
	}

	/**
	 * getModel
	 */
	public function getModel($model_name)
	{
		$model = new $model_name();
		$esrh = $this->getERestSubresourceHelper();
		$emitRest = $esrh->getEmitter();
		return $emitRest(ERestEvent::MODEL_ATTACH_BEHAVIORS, $model);
	}

	/**
	 * emitterForTesting
	 *
	 * this is used internally for testing
	 */ 
	public function emitterForTesting()
	{
		return true;
	}

	/**
	 * attachBehaviorsForTesting
	 *
	 * this is used internally for testing
	 */ 
	public function attachBehaviorsForTesting()
	{
		return true;
	}
}
