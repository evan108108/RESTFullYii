<?php
Yii::import('RestfullYii.ARBehaviors.ERestHelperScopes');

/**
 * ERestHelperScopesUnitTest
 *
 * Tests ERestHelperScopes
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/Tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class ERestHelperScopesUnitTest extends ERestTestCase
{
	/**
	 * getERestHelperScopes
	 * 
	 * Gets the ERestHelperScopes Object
	 *
	 * @return (Object) ERestHelperScopes
	 */ 
	protected function getERestHelperScopes()
	{
		$model = new Category();
		$model->attachBehavior('ERestHelperScopes', new ERestHelperScopes());
		$erhs = $model->asa('ERestHelperScopes');
		$this->assertInstanceOf('ERestHelperScopes', $erhs);
		return $erhs;
	}

	/**
	 * limit
	 *
	 * tests ERestHelperScopes->limit()
	 */ 
	public function testLimit()
	{
		$erhs = $this->getERestHelperScopes();
		$result = $erhs->limit(10);
		$this->assertInstanceOf('Category', $result);
		$this->assertEquals(10, $result->getDbCriteria()->limit);
	}

	/**
	 * offset
	 *
	 * tests ERestHelperScopes->offset()
	 */
	public function testOffset()
	{
		$erhs = $this->getERestHelperScopes();
		$result = $erhs->offset(10);
		$this->assertInstanceOf('Category', $result);
		$this->assertEquals(10, $result->getDbCriteria()->offset);
	}

	/**
	 * orderBy
	 *
	 * tests ERestHelperScopes->orderBy()
	 */
	public function testOrderBy()
	{
		$erhs = $this->getERestHelperScopes();
		$result = $erhs->orderBy('name', 'DESC');
		$this->assertInstanceOf('Category', $result);
		$this->assertEquals('t.name DESC', $result->getDbCriteria()->order);
	}

	/**
	 * orderBy
	 *
	 * tests orderBy with JSON as param ERestHelperScopes->orderBy()
	 */
	public function testOrderByWithJSON()
	{
		$erhs = $this->getERestHelperScopes();
		$result = $erhs->orderBy('[{"property":"name", "direction":"ASC"}, {"property":"id", "direction":"DESC"}, {"property":"posts.title", "direction":"DESC"}]');
		$this->assertInstanceOf('Category', $result);
		$this->assertEquals('t.name ASC, t.id DESC', $result->getDbCriteria()->order);

		$expected_with_condition = [
			'posts'=>[
				'order' => 'posts.title DESC'
			]
		];
		
		$this->assertEquals($expected_with_condition, $result->getDbCriteria()->with);
	}

	/**
	 * filter
	 *
	 * tests ERestHelperScopes->filter('[{"property":"name", "value":"cat1"}]') default
	 */ 
	public function testFilter()
	{
		$erhs = $this->getERestHelperScopes();
		$result = $erhs->filter('[{"property":"name", "value":"cat1"}]');
		$this->assertInstanceOf('Category', $result);
		$this->assertEquals('(t.name LIKE :name0)', $result->getDbCriteria()->condition);
		$this->assertArraysEqual([":name0"=>"%cat1%"], $result->getDbCriteria()->params);
	}

	/**
	 * filter
	 *
	 * tests ERestHelperScopes->filter('[{"property":"id", "value":"[1,2,3], "operator": "in"}]')
	 */ 
	public function testFilterInOperator()
	{
		$erhs = $this->getERestHelperScopes();
		$result = $erhs->filter('[{"property":"id", "value":[1,2,3], "operator": "in"}]');
		$this->assertInstanceOf('Category', $result);
		$this->assertEquals('(t.id IN (:id0_0, :id0_1, :id0_2))', $result->getDbCriteria()->condition);
		$this->assertArraysEqual([":id0_0" => 1, ":id0_1" => 2, ":id0_2" => 3], $result->getDbCriteria()->params);
	}


	/**
	 * filter
	 *
	 * tests ERestHelperScopes->filter('[{"property": "id", "value" : 50, "operator": ">="}]')
	 */ 
	public function testFilterGTOrEqualToOperator()
	{
		$erhs = $this->getERestHelperScopes();
		$result = $erhs->filter('[{"property": "id", "value" : 50, "operator": ">="}]');
		$this->assertInstanceOf('Category', $result);
		$this->assertEquals('(t.id >= :id0)', $result->getDbCriteria()->condition);
		$this->assertArraysEqual([":id0" => 50], $result->getDbCriteria()->params);
	}

	/**
	 * filter
	 *
	 * tests ERestHelperScopes->filter('[{"property": "id", "value" : "50", "operator": "<="}]')
	 */ 
	public function testFilterLTOrEqualToOperator()
	{
		$erhs = $this->getERestHelperScopes();
		$result = $erhs->filter('[{"property": "id", "value" : 50, "operator": "<="}]');
		$this->assertInstanceOf('Category', $result);
		$this->assertEquals('(t.id <= :id0)', $result->getDbCriteria()->condition);
		$this->assertArraysEqual([":id0" => 50], $result->getDbCriteria()->params);
	}

	/**
	 * filter
	 *
	 * tests ERestHelperScopes->filter('[{"property":"id", "value":"[1,2,3], "operator": "not in"}]')
	 */ 
	public function testFilterNotInOperator()
	{
		$erhs = $this->getERestHelperScopes();
		$result = $erhs->filter('[{"property":"id", "value":[1,2,3], "operator": "not in"}]');
		$this->assertInstanceOf('Category', $result);
		$this->assertEquals('(t.id NOT IN (:id0_0, :id0_1, :id0_2))', $result->getDbCriteria()->condition);
		$this->assertArraysEqual([":id0_0" => 1, ":id0_1" => 2, ":id0_2" => 3], $result->getDbCriteria()->params);
	}

	/**
	 * filter
	 *
	 * tests ERestHelperScopes->filter('[{"property": "id", "value" : 2, "operator": "!="}]')
	 */ 
	public function testFilterNotEqualOperator()
	{
		$erhs = $this->getERestHelperScopes();
		$result = $erhs->filter('[{"property": "id", "value" : 2, "operator": "!="}]');
		$this->assertInstanceOf('Category', $result);
		$this->assertEquals('(t.id <> :id0)', $result->getDbCriteria()->condition);
		$this->assertArraysEqual([":id0" => 2], $result->getDbCriteria()->params);
	}

	/**
	 * filter
	 *
	 * tests ERestHelperScopes->filter('[{"property": "id", "value" : 2, "operator": "="}]')
	 */ 
	public function testFilterEqualOperator()
	{
		$erhs = $this->getERestHelperScopes();
		$result = $erhs->filter('[{"property": "id", "value" : 2, "operator": "="}]');
		$this->assertInstanceOf('Category', $result);
		$this->assertEquals('(t.id = :id0)', $result->getDbCriteria()->condition);
		$this->assertArraysEqual([":id0" => 2], $result->getDbCriteria()->params);
	}

	/**
	 * filter
	 *
	 * tests ERestHelperScopes->filter('[{"property": "id", "value" : NULL, "operator": "!="}]')
	 * tests ERestHelperScopes->filter('[{"property": "id", "value" : NULL, "operator": "="}]')
	 */ 
	public function testFilterNotEqualAndEqualOperatorWithNullValues()
	{
		$erhs = $this->getERestHelperScopes();
		$result = $erhs->filter('[{"property": "id", "value" : NULL, "operator": "!="}]');
		$this->assertInstanceOf('Category', $result);
		$this->assertEquals('(t.id IS NOT :id0)', $result->getDbCriteria()->condition);
		$this->assertArraysEqual([":id0" => null], $result->getDbCriteria()->params);

		$erhs = $this->getERestHelperScopes();
		$result = $erhs->filter('[{"property": "id", "value" : NULL, "operator": "="}]');
		$this->assertInstanceOf('Category', $result);
		$this->assertEquals('(t.id IS :id0)', $result->getDbCriteria()->condition);
		$this->assertArraysEqual([":id0" => null], $result->getDbCriteria()->params);
	}

	/**
	 * filter
	 *
	 * tests ERestHelperScopes->filter('[{"property": "id", "value" : 2, "operator": "!="}]')
	 */ 
	public function testFilterMultiFilter()
	{
		$erhs = $this->getERestHelperScopes();
		$result = $erhs->filter('[{"property": "id", "value" : 2, "operator": "!="}, {"property":"id", "value":[1,2,3], "operator": "not in"}]');
		$this->assertInstanceOf('Category', $result);
		$this->assertEquals('(t.id <> :id0 AND t.id NOT IN (:id1_0, :id1_1, :id1_2))', $result->getDbCriteria()->condition);
		$this->assertArraysEqual([":id0" => 2, ":id1_0" => 1, ":id1_1" => 2, ":id1_2" => 3], $result->getDbCriteria()->params);
	}

	/**
	 * getFilterCType
	 *
	 * tests ERestHelperScopes->getFilterCType()
	 */ 
	public function testGetFilterCType()
	{
		$result = $this->invokePrivateMethod($this->getERestHelperScopes(), 'getFilterCType', ['id']);
		$this->assertEquals('integer', $result);

		$result = $this->invokePrivateMethod($this->getERestHelperScopes(), 'getFilterCType', ['name']);
		$this->assertEquals('string', $result);

		$result = $this->invokePrivateMethod($this->getERestHelperScopes(), 'getFilterCType', ['THIS_PROP_DOES_NOT_EXIST']);
		$this->assertEquals('text', $result);
	}

	/**
	 * getFilterAlias
	 *
	 * tests ERestHelperScopes->getFilterAlias()
	 */ 
	public function testGetFilterAlias()
	{
		$result = $this->invokePrivateMethod($this->getERestHelperScopes(), 'getFilterAlias', ['id']);
		$this->assertEquals('t', $result);
	}

	/**
	 * constructFilter
	 *
	 * tests ERestHelperScopes->constructFilter()
	 */ 
	public function testConstructFilter()
	{
		$field = 'name';
		$prop = 'name0';
		$value = 'cat1';
		$cType = 'text';
		$filterItem = ['operation'=>'=', 'value'=>'cat1', 'property'=>'name'];
		$params = [];
		$result = $this->invokePrivateMethod($this->getERestHelperScopes(), 'constructFilter', [$field, $prop, $value, $cType, $filterItem, $params]);
		$expected_result = [
			' LIKE :name0',
			[
				':name0' => '%cat1%',
			],
		];

		$this->assertArraysEqual($expected_result, $result);
	}

	/**
	 * getSortSQL
	 *
	 * tests ERestHelperScopes->getSortSQL()
	 */ 
	public function testGetSortSQL()
	{
		$result = $this->invokePrivateMethod($this->getERestHelperScopes(), 'getSortSQL', ['id', 'DESC']);
		$this->assertEquals('t.id DESC', $result);
	}

}
