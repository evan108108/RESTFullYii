<?php
/**
 * PUTSubresourcesUnitTest
 *
 * Tests PUT request has visible properties
 * And does not have hidden properties
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/Tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class PUTSubResourceVisibleHiddenPropertiesUnitTest extends ERestTestCase
{
	/**
	 * testDeletesubresourceVisibleProperties
	 *
	 * tests that a PUT request for a 'Post' sub-resource
	 * With limited selected visible fields only
	 * returns the correct response
	 */
	public function testPUTSubresourceVisibleProperties()
	{
		$request = new ERestTestRequestHelper();

		$request->addEvent('model.visible.properties', function() {
				return ['id', 'name', 'posts.title'];
		});

		$request->addEvent('model.with.relations', function() {
				return [];
		});

		$request['config'] = [
			'url'			=> 'http://api/category/1/posts/3',
			'type'		=> 'PUT',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Subresource Added","data":{"totalCount":1,"category":{"id":"1","name":"cat1","posts":[{"title":"title1"},{"title":"title3"}]}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	
	/**
	 * testPUTSubresourceHiddenProperties
	 *
	 * tests that a PUT request for a 'Post' sub-resource
	 * With exluded visible fields only
	 * returns the correct response
	 */
	public function testPUTSubresourceHiddenProperties()
	{
		$request = new ERestTestRequestHelper();

		$request->addEvent('model.hidden.properties', function() {
				return ['id', 'posts.title', 'posts.content', 'posts.create_time'];
		});

		$request->addEvent('model.with.relations', function() {
				return [];
		});

		$request['config'] = [
			'url'			=> 'http://api/category/1/posts/3',
			'type'		=> 'PUT',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Subresource Added","data":{"totalCount":1,"category":{"name":"cat1","posts":[{"id":"1","author_id":"1"},{"id":"3","author_id":"3"}]}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

}

