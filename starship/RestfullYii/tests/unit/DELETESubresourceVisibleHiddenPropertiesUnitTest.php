<?php
/**
 * DELETESubresourcesUnitTest
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
class DELETESubResourceVisibleHiddenPropertiesUnitTest extends ERestTestCase
{
	/**
	 * testDeletesubresourceVisibleProperties
	 *
	 * tests that a DELETE request for a 'User' resource
	 * With limited selected visible fields only
	 * returns the correct response
	 */
	public function testDELETESubresourceVisibleProperties()
	{
		$request = new ERestTestRequestHelper();

		$request->addEvent('model.visible.properties', function() {
				return ['id', 'name'];
		});

		$request->addEvent('model.with.relations', function() {
				return [];
		});

		$request['config'] = [
			'url'			=> 'http://api/category/1/posts/1',
			'type'		=> 'DELETE',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Sub-Resource Deleted","data":{"totalCount":1,"category":{"id":"1","name":"cat1","posts":[]}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	
	/**
	 * testDELETESubresourceHiddenProperties
	 *
	 * tests that a DELETE request for a 'User' resource
	 * With exluded visible fields only
	 * returns the correct response
	 */
	public function testDELETESubresourceHiddenProperties()
	{
		$request = new ERestTestRequestHelper();

		$request->addEvent('model.hidden.properties', function() {
				return ['id'];
		});

		$request->addEvent('model.with.relations', function() {
				return [];
		});

		$request['config'] = [
			'url'			=> 'http://api/category/1/posts/1',
			'type'		=> 'DELETE',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Sub-Resource Deleted","data":{"totalCount":1,"category":{"name":"cat1","posts":[]}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

}

