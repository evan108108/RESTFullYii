<?php
/**
 * DELETEResourcesUnitTest
 *
 * Tests DELETE request has visible properties
 * And does not have hidden properties
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/Tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class DELETEResourcesVisibleHiddenPropertiesUnitTest extends ERestTestCase
{
	/**
	 * testDeleteResourceVisibleProperties
	 *
	 * tests that a DELETE request for a 'User' resource
	 * With limited selected visible fields only
	 * returns the correct response
	 */
	public function testDELETEResourceVisibleProperties()
	{
		$request = new ERestTestRequestHelper();

		$request->addEvent('model.visible.properties', function() {
				return ['username', 'email'];
		});

		$request->addEvent('model.with.relations', function() {
				return [];
		});

		$request['config'] = [
			'url'			=> 'http://api/user/1',
			'type'		=> 'DELETE',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Deleted","data":{"totalCount":1,"user":{"username":"username1","email":"email@email1.com"}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	
	/**
	 * testDELETEResourceHiddenProperties
	 *
	 * tests that a DELETE request for a 'User' resource
	 * With exluded visible fields only
	 * returns the correct response
	 */
	public function testDELETEResourceHiddenProperties()
	{
		$request = new ERestTestRequestHelper();

		$request->addEvent('model.hidden.properties', function() {
				return ['password', 'id'];
		});

		$request->addEvent('model.with.relations', function() {
				return [];
		});

		$request['config'] = [
			'url'			=> 'http://api/user/1',
			'type'		=> 'DELETE',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Deleted","data":{"totalCount":1,"user":{"username":"username1","email":"email@email1.com"}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

}

