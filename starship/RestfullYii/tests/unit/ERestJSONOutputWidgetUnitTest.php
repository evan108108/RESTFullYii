<?php
Yii::import('RestfullYii.widgets.ERestJSONOutputWidget');

/**
 * ERestJSONOutputWidgetUnitTest
 *
 * Tests ERestJSONOutputWidget
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class ERestJSONOutputWidgetUnitTest extends ERestTestCase
{

	/**
	 * testRunRaw
	 *
	 * tests ERestJSONOutputWidget->run()
	 */
	public function testRunRaw()
	{
		$data = ['test' => 'val'];
		$result = $this->captureOB($this, function() use ($data){
			$widget = $this->getWidget([
				'type' => 'raw',
				'data' => $data,
			]);
			$widget->run();
		});
		$this->assertJsonStringEqualsJsonString(CJSON::encode($data), $result);
	}

	/**
	 * testRunError
	 *
	 * tests ERestJSONOutputWidget->run()
	 */
	public function testRunError()
	{
		$result = $this->captureOB($this, function() {
			$widget = $this->getWidget([
				'type' => 'error',
				'message' => 'TEST_ERROR',
				'errorCode' => 500
			]);
			$widget->run();
		});

		$expected = CJSON::encode([
			'success'	=> false,
			'message'	=> 'TEST_ERROR',
			'data'		=> [
				"errorCode"	=> 500,
				"message"		=> 'TEST_ERROR',
			]
			]);

		$this->assertJsonStringEqualsJsonString($expected, $result);
	}

	/**
	 * testRunREST
	 *
	 * tests ERestJSONOutputWidget->run()
	 */
	public function testRunREST()
	{
		$result = $this->captureOB($this, function() {
			$widget = $this->getWidget([
				'type' => 'rest',
				'success' => true,
				'message' => 'Record Returned',
				'totalCount' => 1,
				'modelName' => 'Post',
				'relations' => ['categories'],
				'data' => Post::model()->with('categories')->findByPk(1)
			]);
			$widget->run();
		});

		$my_model = Post::model()->findByPk(1);
		$my_model_array = $my_model->attributes;
		$cats = $my_model->categories;
		$my_model_array['categories'] = [];
		foreach($cats as $cat) {
			$my_model_array['categories'][] = $cat->attributes;
		}

		$expected = CJSON::encode([
			'success'	=> true,
			'message'	=> 'Record Returned',
			'data'		=> [
				"totalCount"	=> 1,
				"post"	=> $my_model_array,
			]
		]);

		$this->assertJsonStringEqualsJsonString($expected, $result);
	}

	/**
	 * testOutPutRaw
	 *
	 *  tests ERestJSONOutputWidget->outputRaw()
	 */
	public function testOutputRaw()
	{
		$data = ['test' => 'val'];
		$result = $this->captureOB($this, function() use ($data){
			$widget = $this->getWidget([
				'type' => 'raw',
				'data' => $data,
			]);
			$widget->outputRaw();
		});
		$this->assertJsonStringEqualsJsonString(CJSON::encode($data), $result);
	}

	/**
	 * testOutputError
	 *
	 * tests ERestJSONOutputWidget->outputError()
	 */
	public function testOutputError()
	{
		$result = $this->captureOB($this, function() {
			$widget = $this->getWidget([
				'type' => 'error',
				'message' => 'TEST_ERROR',
				'errorCode' => 500
			]);
			$widget->outputError();
		});

		$expected = CJSON::encode([
			'success'	=> false,
			'message'	=> 'TEST_ERROR',
			'data'		=> [
				"errorCode"	=> 500,
				"message"		=> 'TEST_ERROR',
			]
			]);

		$this->assertJsonStringEqualsJsonString($expected, $result);
	}


	/**
	 * testOutputRest
	 *
	 * tests ERestJSONOutputWidget->outputRest()
	 */
	public function testOutputRest()
	{
		$result = $this->captureOB($this, function() {
			$widget = $this->getWidget([
				'type' => 'rest',
				'success' => true,
				'message' => 'Record Returned',
				'totalCount' => 1,
				'modelName' => 'Post',
				'relations' => ['categories'],
				'data' => Post::model()->with('categories')->findByPk(1)
			]);
			$widget->outputRest();
		});

		$my_model = Post::model()->findByPk(1);
		$my_model_array = $my_model->attributes;
		$cats = $my_model->categories;
		$my_model_array['categories'] = [];
		foreach($cats as $cat) {
			$my_model_array['categories'][] = $cat->attributes;
		}

		$expected = CJSON::encode([
			'success'	=> true,
			'message'	=> 'Record Returned',
			'data'		=> [
				"totalCount"	=> 1,
				"post"	=> $my_model_array,
			]
		]);

		$this->assertJsonStringEqualsJsonString($expected, $result);
	}

	/**
	 * testModelsToArray
	 *
	 * tests ERestJSONOutputWidget->modelsToArray()
	 */
	public function testModelsToArray()
	{
		$model = Category::model()->with('posts')->findAll();
		$relations = [];
		$expected = CJSON::decode(CJSON::encode($model));
		$this->assertArraysEqual($expected, $this->getWidget()->modelsToArray($model, $relations));

		$relations = ['posts'];
		foreach($model as $key=>$cat)
		{
			$expected[$key]['posts'] = CJSON::decode(CJSON::encode($cat->posts));
		}
		$this->assertArraysEqual($expected, $this->getWidget()->modelsToArray($model, $relations));

		$model = Post::model()->findByPk(2);
		$relations = ['author'];
		$expected = CJSON::decode(CJSON::encode($model));
		$expected['author'] = CJSON::decode(CJSON::encode($model->author));
		$this->assertArraysEqual($expected, $this->getWidget()->modelsToArray($model, $relations));
    }

    /**
     * testpropertyIsVisibleVisibleProperties
     *
     * tests ERestJSONOutputWidget->propertyIsVisible()
     */
    public function testpropertyIsVisibleVisibleProperties()
    {
        $widget = $this->getWidget([
            'type' => 'rest',
            'success' => true,
            'message' => 'Record Returned',
            'visibleProperties'=>['id', 'title', '*.name', 'categories.id'],
            'totalCount' => 1,
            'modelName' => 'Post',
            'relations' => ['categories'],
            'data' => Post::model()->with('categories')->findByPk(1)
        ]);
    
        $this->assertTrue($widget->propertyIsVisible('id'));
        $this->assertTrue($widget->propertyIsVisible('title'));
        $this->assertFalse($widget->propertyIsVisible('create_time'));

        $this->assertTrue($widget->propertyIsVisible('name', 'categories'));
        $this->assertTrue($widget->propertyIsVisible('id', 'categories'));
        $this->assertFalse($widget->propertyIsVisible('title', 'categories'));
    }

    /**
     * testpropertyIsVisibleHiddenProperties
     *
     * tests ERestJSONOutputWidget->propertyIsVisible()
     */
    public function testpropertyIsVisibleHiddenProperties()
    {
        $widget = $this->getWidget([
            'type' => 'rest',
            'success' => true,
            'message' => 'Record Returned',
            'hiddenProperties'=>['id', 'title', '*.name', 'categories.id'],
            'totalCount' => 1,
            'modelName' => 'Post',
            'relations' => ['categories'],
            'data' => Post::model()->with('categories')->findByPk(1)
        ]);
    
        $this->assertFalse($widget->propertyIsVisible('id'));
        $this->assertFalse($widget->propertyIsVisible('title'));
        $this->assertTrue($widget->propertyIsVisible('create_time'));

        $this->assertFalse($widget->propertyIsVisible('name', 'categories'));
        $this->assertFalse($widget->propertyIsVisible('id', 'categories'));
        $this->assertTrue($widget->propertyIsVisible('title', 'categories'));
    }

    /**
     * testProcessAttributesMainModel
     *
     * tests ERestJSONOutputWidget->processAttributes()
     */
    public function testProcessAttributesMainModel()
    {
        $model = Post::model()->with('categories')->findByPk(1);

        $widget = $this->getWidget([
            'type' => 'rest',
            'success' => true,
            'message' => 'Record Returned',
            'totalCount' => 1,
            'modelName' => 'Post',
            'relations' => ['categories'],
            'data' => $model
        ]);

        $this->assertArraysEqual($model->attributes, $widget->processAttributes($model));
    }


    /**
     * testProcessAttributesRelatedModel
     *
     * tests ERestJSONOutputWidget->processAttributes()
     */
    public function testProcessAttributesRelatedModel()
    {
        $model = Post::model()->with('categories')->findByPk(1);

        $widget = $this->getWidget([
            'type' => 'rest',
            'success' => true,
            'message' => 'Record Returned',
            'totalCount' => 1,
            'modelName' => 'Post',
            'relations' => ['categories'],
            'data' => $model
        ]);

        $this->assertArraysEqual($model->categories[0]->attributes, $widget->processAttributes($model->categories[0], 'categories'));
    }

		/**
		 * testIsBinary
		 *
		 * tests isBinary
		 */
		public function testIsBinary()
		{
			$this->assertEquals(true, $this->getWidget()->isBinary('binary(16)', hex2bin('DE46C83E5A50CED70E6A525A7BE6D709')));
			$this->assertEquals(true, $this->getWidget()->isBinary('blob', hex2bin('AA46C83E5A50CED70E6A525A7BE6D709')));
			$this->assertEquals(false, $this->getWidget()->isBinary('binary(16)', 1));
			$this->assertEquals(false, $this->getWidget()->isBinary('blob', null));
		}


	/**
	 * getWidget
	 * 
	 * bootstraps an instance of ERestJSONOutputWidget
	 *
	 * @return (Object) an instance of ERestJSONOutputWidget
	 */
	public function getWidget($config=[])
	{
		$controller = new PostController('post');
		$controller->attachBehaviors([
			'ERestBehavior'=>'RestfullYii.behaviors.ERestBehavior'
		]);
		$controller->ERestInit();

		$widget = new ERestJSONOutputWidget($controller);
		$this->assertInstanceOf('ERestJSONOutputWidget', $widget);

		foreach($config as $prop=>$value)
		{
			$widget->$prop = $value;
		}

		return $widget;
	}
}
