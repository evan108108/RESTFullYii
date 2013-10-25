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
     * testPropertyIsVisableVisibleProperties
     *
     * tests ERestJSONOutputWidget->propertyIsVisable()
     */
    public function testPropertyIsVisableVisibleProperties()
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
    
        $this->assertTrue($widget->propertyIsVisable('id'));
        $this->assertTrue($widget->propertyIsVisable('title'));
        $this->assertFalse($widget->propertyIsVisable('create_time'));

        $this->assertTrue($widget->propertyIsVisable('name', 'categories'));
        $this->assertTrue($widget->propertyIsVisable('id', 'categories'));
        $this->assertFalse($widget->propertyIsVisable('title', 'categories'));
    }

    /**
     * testPropertyIsVisableHiddenProperties
     *
     * tests ERestJSONOutputWidget->propertyIsVisable()
     */
    public function testPropertyIsVisableHiddenProperties()
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
    
        $this->assertFalse($widget->propertyIsVisable('id'));
        $this->assertFalse($widget->propertyIsVisable('title'));
        $this->assertTrue($widget->propertyIsVisable('create_time'));

        $this->assertFalse($widget->propertyIsVisable('name', 'categories'));
        $this->assertFalse($widget->propertyIsVisable('id', 'categories'));
        $this->assertTrue($widget->propertyIsVisable('title', 'categories'));
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
	 * getWidget
	 * 
	 * bootstraps an instance of ERestJSONOutputWidget
	 *
	 * @return (Object) an instance of ERestJSONOutputWidget
	 */
	public function getWidget($config=[])
	{
		$widget = new ERestJSONOutputWidget();
		$this->assertInstanceOf('ERestJSONOutputWidget', $widget);

		foreach($config as $prop=>$value)
		{
			$widget->$prop = $value;
		}

		return $widget;
	}
}
