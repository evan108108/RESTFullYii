<?php
Yii::import('RestfullYii.tests.ERestTestRequestHelper');

/**
 * PUTCustomRouteUnitTest
 *
 * Tests PUT custom route request
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/Tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class PUTCustomRouteUnitTest extends ERestTestCase
{
	/**
	 * testPUTCustomRouteNoParamsRequest
	 *
	 * tests that a PUT request with custom route and no params
	 * returns the correct response
	 */
	public function testPUTCustomRouteNoParamsRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/category/testing',
			'type'		=> 'PUT',
			'data'		=> '{"test":"data"}',
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];
		
		$request->addEvent('req.put.testing.render', function($data) {
			echo CJSON::encode($data);
		});

		$request_response = $request->send();
		$expected_response = '{"test":"data"}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	
	/**
	 * testPUTCustomRouteOneParamRequest
	 *
	 * tests that a PUT request with custom route and one param
	 * returns the correct response
	 */
	public function testPUTCustomRouteOneParamRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/post/testing/1',
			'type'		=> 'PUT',
			'data'		=> '{"test":"data"}',
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];
		
		$request->addEvent('req.put.testing.render', function($data, $param1) {
			$data['test'] = $param1;
			echo CJSON::encode($data);
		});

		$request_response = $request->send();
		$expected_response = '{"test":"1"}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testPUTCustomRouteTowParamRequest
	 *
	 * tests that a PUT request with custom route and two params
	 * returns the correct response
	 */
	public function testPUTCustomRouteTwoParamRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/user/testing/a/b',
			'type'		=> 'PUT',
			'data'		=> '{"test":"data"}',
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];
		
		$request->addEvent('req.put.testing.render', function($data, $param1, $param2) {
			$data['param1'] = $param1;
			$data['param2'] = $param2;
			echo CJSON::encode($data);
		});

		$request_response = $request->send();
		$expected_response = '{"test":"data", "param1":"a", "param2":"b"}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testPUTCustomRouteTowParamPreFilterRequest
	 *
	 * tests that a PUT request with custom route and two params and pre-filter
	 * returns the correct response
	 */
	public function testPUTCustomRouteTwoParamPreFilterRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/user/testing/1/2',
			'type'		=> 'PUT',
			'data'		=> '{"test":"data"}',
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];
		
		$request->addEvent('req.put.testing.render', function($data, $param1, $param2) {
			$data['param1'] = $param1;
			$data['param2'] = $param2;
			echo CJSON::encode($data);
		});
		
		$request->addEvent('pre.filter.req.put.testing.render', function($data, $param1, $param2) {
			return [$data, $param1+1, $param2+1];
		});

		$request_response = $request->send();
		$expected_response = '{"test":"data", "param1":"2", "param2":"3"}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

}

