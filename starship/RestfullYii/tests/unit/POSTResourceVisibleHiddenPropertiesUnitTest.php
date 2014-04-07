<?php
/**
 * POSTResourcesUnitTest
 *
 * Tests POST request has visible properties
 * And does not have hidden properties
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/Tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class POSTResourcesVisibleHiddenPropertiesUnitTest extends ERestTestCase
{
	/**
	 * testDeleteResourceVisibleProperties
	 *
	 * tests that a POST request for a 'Profile' resource
	 * With limited selected visible fields only
	 * returns the correct response
	 */
	public function testPOSTResourceVisibleProperties()
	{
		$request = new ERestTestRequestHelper();

		$request->addEvent('model.visible.properties', function() {
				return ['user_id'];
		});

		$request->addEvent('model.with.relations', function() {
				return [];
		});

		$data = '{"user_id":"4","photo":"0","website":"mysite4.com"}';

		$request['config'] = [
			'url'			=> 'http://api/profile',
			'type'		=> 'POST',
			'data'		=> $data,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Created","data":{"totalCount":1,"profile":{"user_id":"4"}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	
	/**
	 * testPOSTResourceHiddenProperties
	 *
	 * tests that a POST request for a 'Profile' resource
	 * With exluded visible fields only
	 * returns the correct response
	 */
	public function testPOSTResourceHiddenProperties()
	{
		$request = new ERestTestRequestHelper();

		$request->addEvent('model.hidden.properties', function() {
				return ['website', 'photo', 'id'];
		});

		$request->addEvent('model.with.relations', function() {
				return [];
		});

		$data = '{"user_id":"4","photo":"0","website":"mysite4.com"}';

		$request['config'] = [
			'url'			=> 'http://api/profile',
			'type'		=> 'POST',
			'data'		=> $data,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Created","data":{"totalCount":1,"profile":{"user_id":"4"}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
		 
	}

}

