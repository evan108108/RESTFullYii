<?php
Yii::import('RestfullYii.tests.ERestTestRequestHelper');

/**
 * DELETECustomRouteUnitTest
 *
 * Tests DELETE custom route request
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/Tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class DELETECustomRouteUnitTest extends ERestTestCase
{
	/**
	 * testDELETECustomRouteNoParamsRequest
	 *
	 * tests that a DELETE request with custom route and no params
	 * returns the correct response
	 */
	public function testDELETECustomRouteNoParamsRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/category/testing',
			'type'		=> 'DELETE',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];
		
		$request->addEvent('req.delete.testing.render', function() {
			echo CJSON::encode(['test'=>'data']);
		});

		$request_response = $request->send();
		$expected_response = '{"test":"data"}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	
	/**
	 * testDELETECustomRouteOneParamRequest
	 *
	 * tests that a DELETE request with custom route and one param
	 * returns the correct response
	 */
	public function testDELETECustomRouteOneParamRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/post/testing/1',
			'type'		=> 'DELETE',
			'data'		=> '{"test":"data"}',
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];
		
		$request->addEvent('req.delete.testing.render', function($param1) {
			echo '{"test":"' . $param1 . '"}';
		});

		$request_response = $request->send();
		$expected_response = '{"test":"1"}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testDELETECustomRouteTowParamRequest
	 *
	 * tests that a DELETE request with custom route and two params
	 * returns the correct response
	 */
	public function testDELETECustomRouteTwoParamRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/user/testing/a/b',
			'type'		=> 'DELETE',
			'data'		=> '{"test":"data"}',
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];
		
		$request->addEvent('req.delete.testing.render', function($param1, $param2) {
			echo '{"test":"data", "param1":"' . $param1 . '", "param2":"' . $param2 . '"}';
		});

		$request_response = $request->send();
		$expected_response = '{"test":"data", "param1":"a", "param2":"b"}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testDELETECustomRouteTowParamPreFilterRequest
	 *
	 * tests that a DELETE request with custom route and two params and pre-filter
	 * returns the correct response
	 */
	public function testDELETECustomRouteTwoParamPreFilterRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/user/testing/1/2',
			'type'		=> 'DELETE',
			'data'		=> '{"test":"data"}',
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];
		
		$request->addEvent('req.delete.testing.render', function($param1, $param2) {
			echo '{"test":"data", "param1":"' . $param1 . '", "param2":"' . $param2 . '"}';
		});
		
		$request->addEvent('pre.filter.req.delete.testing.render', function($param1, $param2) {
			return [$param1+1, $param2+1];
		});

		$request_response = $request->send();
		$expected_response = '{"test":"data", "param1":"2", "param2":"3"}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

}



