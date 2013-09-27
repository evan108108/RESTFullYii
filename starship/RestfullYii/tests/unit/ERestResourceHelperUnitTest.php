<?php
Yii::import('RestfullYii.components.ERestRequestReader');
Yii::import('RestfullYii.behaviors.ERestBehavior');

/**
 * ERestResourceHelperUnitTest
 * 
 * tests ERestResourceHelperUnitTest
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class ERestResourceHelperUnitTest extends ERestTestCase
{
	/**
	 * __construct
	 *
	 * tests ERestResourceHelper constructor
	 */
	public function testConstruct()
	{
		$controller = $this->getController()->Category;
		$controller->attachBehavior('ERestBehavior', new ERestBehavior());
		$erb = $controller->asa('ERestBehavior');
		$erb->ERestInit();

		$errh = new ERestResourceHelper([$erb, 'emitRest']);
		$this->assertEquals($this->getPrivateProperty($errh, 'emitter'), [$erb, 'emitRest']);		
	}

	/**
	 * setEmitter
	 *
	 * tests ERestResourceHelper->setEmitter()
	 */
	public function testSetEmitter()
	{
		$errh = $this->getERestResourceHelper();
		$errh->setEmitter([$this, 'emitterForTesting']);
		$this->assertEquals($this->getPrivateProperty($errh, 'emitter'), [$this, 'emitterForTesting']);		
	}

	/**
	 * getEmitter
	 *
	 * tests ERestResourceHelper->getEmitter()
	 */
	public function testGetEmitter()
	{
		$errh = $this->getERestResourceHelper();
		$this->assertEquals($this->getPrivateProperty($errh, 'emitter'), $errh->getEmitter());
	}
	
	/**
	 * prepareRestModel
	 *
	 * tests ERestResourceHelper->prepareRestModel(1)
	 */ 
	public function testPrepareRestModelWithIDAndCountFalse()
	{
		$errh = $this->getERestResourceHelper();
		$model = $errh->prepareRestModel(1);
		$this->assertInstanceOf('Category', $model);
		$compare_model = Category::model()->findByPk(1);
		$this->assertJsonStringEqualsJsonString(CJSON::encode($model), CJSON::encode($compare_model));
	}

	/**
	 * prepareRestModel
	 *
	 * tests ERestResourceHelper->prepareRestModel(1, true)
	 */ 
	public function testPrepareRestModelWithIDAndCountTrue()
	{
		$errh = $this->getERestResourceHelper();
		$count = $errh->prepareRestModel(1, true);
		$this->assertEquals(1, $count);
	}

	/**
	 * prepareRestModel
	 *
	 * tests ERestResourceHelper->prepareRestModel()
	 */ 
	public function testPrepareRestModelWithIDNullAndCountFalse()
	{
		$errh = $this->getERestResourceHelper();
		$model = $errh->prepareRestModel();
		$compare_model = Category::model()->findAll();
		$this->assertJsonStringEqualsJsonString(CJSON::encode($model), CJSON::encode($compare_model));
	}

	/**
	 * prepareRestModel
	 *
	 * tests ERestResourceHelper->prepareRestModel(null, 1)
	 */ 
	public function testPrepareRestModelWithIDNullAndCountTrue()
	{
		$errh = $this->getERestResourceHelper();
		$count = $errh->prepareRestModel(null, 1);
		$compare_count = Category::model()->count();
		$this->assertEquals($count, $compare_count);
	}

	/**
	 * prepareRestModel
	 *
	 * tests ERestResourceHelper->prepareRestModel(1) can turn off lazyLoadRelations
	 */ 
	public function testPrepareRestModelWithLazyLoadRelationsOff()
	{
		Yii::app()->params['RestfullYii'] = [
			ERestEvent::MODEL_LAZY_LOAD_RELATIONS => function() {
				return false;
			}
		];
		$errh = $this->getERestResourceHelper();
		$model = $errh->prepareRestModel(1);
		$this->assertTrue($model->hasRelated('posts'));
	}

	/**
	 * prepareRestModel
	 *
	 * tests ERestResourceHelper->prepareRestModel(1) can turn on lazyLoadRelations
	 */ 
	public function testPrepareRestModelWithLazyLoadRelationsOn()
	{
		Yii::app()->params['RestfullYii'] = [
			ERestEvent::MODEL_LAZY_LOAD_RELATIONS => function() {
				return true;
			}
		];
		$errh = $this->getERestResourceHelper();
		$model = $errh->prepareRestModel(1);
		$this->assertTrue(!$model->hasRelated('posts'));
	}

	/**
	 * prepareRestModel
	 *
	 * tests ERestResourceHelper->prepareRestModel() can set sort
	 */ 
	public function testPrepareRestModelWithSort()
	{
		Yii::app()->params['RestfullYii'] = [
			ERestEvent::MODEL_SORT => function() {
				return '[{"property":"name", "direction":"desc"}]';
			}
		];
		$errh = $this->getERestResourceHelper();
		$model = $errh->prepareRestModel();
		$compare_model = Category::model()->findAll(array('order'=>'name desc'));
		$this->assertJsonStringEqualsJsonString(CJSON::encode($model), CJSON::encode($compare_model));

		Yii::app()->params['RestfullYii'] = [
			ERestEvent::MODEL_SORT => function() {
				return '[{"property":"name", "direction":"Asc"}]';
			}
		];
		$errh = $this->getERestResourceHelper();
		$model = $errh->prepareRestModel();
		$compare_model = Category::model()->findAll(array('order'=>'name'));
		$this->assertJsonStringEqualsJsonString(CJSON::encode($model), CJSON::encode($compare_model));
	}

	/**
	 * prepareRestModel
	 *
	 * tests ERestResourceHelper->prepareRestModel() can filter
	 */ 
	public function testPrepareRestModelWithFilter()
	{
		Yii::app()->params['RestfullYii'] = [
			ERestEvent::MODEL_FILTER => function() {
				return '[{"property":"name", "value":"cat2"}]';
			}
		];
		$errh = $this->getERestResourceHelper();
		$model = $errh->prepareRestModel();
		$compare_model = Category::model()->findAllByAttributes(array('name'=>'cat2'));
		$this->assertJsonStringEqualsJsonString(CJSON::encode($model), CJSON::encode($compare_model));
	}

	/**
	 * prepareRestModel
	 *
	 * tests ERestResourceHelper->prepareRestModel() can limit
	 */
	public function testPrepareRestModelWithLimit()
	{
		Yii::app()->params['RestfullYii'] = [
			ERestEvent::MODEL_LIMIT => function() {
				return 2;
			}
		];
		$errh = $this->getERestResourceHelper();
		$models = $errh->prepareRestModel();
		$this->assertEquals(2, count($models));
	}

	/**
	 * prepareRestModel
	 *
	 * tests ERestResourceHelper->prepareRestModel() can offset
	 */
	public function testPrepareRestModelWithOffset()
	{
		Yii::app()->params['RestfullYii'] = [
			ERestEvent::MODEL_OFFSET => function() {
				return 2;
			}
		];
		$errh = $this->getERestResourceHelper();
		$models = $errh->prepareRestModel();
		$this->assertEquals(4, count($models));
	}

	/**
	 * applyScope
	 *
	 * tests ERestResourceHelper->applyScope()
	 */
	public function testApplyScope()
	{
		$model = new Category();
		Yii::app()->params['RestfullYii'] = [
			ERestEvent::MODEL_LAZY_LOAD_RELATIONS => function() {
				return false;
			}
		];
		$errh = $this->getERestResourceHelper();
		$model = $this->invokePrivateMethod($errh, 'applyScope', [
			ERestEvent::MODEL_WITH_RELATIONS,
			$model,
			'with',
			true,
		])->findByPk(1);
		
		$this->assertTrue($model->hasRelated('posts'));
	}

	/**
	 * getERestResourceHelper
	 *
	 * returns instance of ERestResourceHelper
	 *
	 * @return (Object) (ERestResourceHelper)
	 */
	public function getERestResourceHelper()
	{
		$controller = $this->getController()->Category;
		$controller->attachBehavior('ERestBehavior', new ERestBehavior());
		$erb = $controller->asa('ERestBehavior');
		$erb->ERestInit();
		$errh = $erb->getResourceHelper();
		$this->assertInstanceOf('ERestResourceHelper', $errh);
		return $errh;
	}

	/**
	 * setModelAttributes
	 *
	 * tests ERestResourceHelper->setModelAttributes()
	 */
	public function testSetModelAttributes()
	{
		$errh = $this->getERestResourceHelper();
		$model = $errh->prepareRestModel(1);
		$data = [
			"name" => "CAT_UPDATE",
		];
		$model = $errh->setModelAttributes($model, $data, []);
		$this->assertEquals('CAT_UPDATE', $model->name);

		$data = [
			"id" => 10,
			"name" => "CAT_UPDATE",
		];

		$result = $this->captureOB($this, function() use ($errh, $model, $data) {
			$errh->setModelAttributes($model, $data, ['id']);
		});

		$this->assertInstanceOf('Exception', $result);
		$this->assertExceptionHasMessage('Parameter \'id\' is not allowed for model (Category)', $result);
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
