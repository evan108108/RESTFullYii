<?php
Yii::import('RestfullYii.tests.ERestTestRequestHelper');

/**
 * GETCustomRouteUnitTest
 *
 * Tests GET custom route request
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/Tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class GETCustomRouteUnitTest extends ERestTestCase
{
	/**
	 * testGETCustomRouteNoParamsRequest
	 *
	 * tests that a GET request with custom route and no params
	 * returns the correct response
	 */
	public function testGETCustomRouteNoParamsRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/category/testing',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];
		
		$request->addEvent('req.get.testing.render', function() {
			echo CJSON::encode(['myParam' => 'myValue']);
		});

		$request_response = $request->send();
		$expected_response = '{"myParam":"myValue"}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETCustomRouteOneParamRequest
	 *
	 * tests that a GET request with custom route and one param
	 * returns the correct response
	 */
	public function testGETCustomRouteOneParamRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/post/testing/1',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];
		
		$request->addEvent('req.get.testing.render', function($param1) {
			echo CJSON::encode(['myParam' => "$param1"]);
		});

		$request_response = $request->send();
		$expected_response = '{"myParam":"1"}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETCustomRouteTowParamRequest
	 *
	 * tests that a GET request with custom route and two params
	 * returns the correct response
	 */
	public function testGETCustomRouteTwoParamRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/user/testing/a/b',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];
		
		$request->addEvent('req.get.testing.render', function($param1, $param2) {
			echo CJSON::encode([$param1 => $param2]);
		});

		$request_response = $request->send();
		$expected_response = '{"a":"b"}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETCustomRouteTowParamPreFilterRequest
	 *
	 * tests that a GET request with custom route and two params and pre-filter
	 * returns the correct response
	 */
	public function testGETCustomRouteTwoParamPreFilterRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/user/testing/a/b',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];
		
		$request->addEvent('req.get.testing.render', function($param1, $param2) {
			echo CJSON::encode([$param1 => $param2]);
		});
		
		$request->addEvent('pre.filter.req.get.testing.render', function($param1, $param2) {
			return ["pre_1_$param1", "pre_2_$param2"];
		});

		$request_response = $request->send();
		$expected_response = '{"pre_1_a":"pre_2_b"}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

}
