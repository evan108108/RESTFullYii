<?php
Yii::import('RestfullYii.tests.ERestTestRequestHelper');

/**
 * PUTSubresourceUnitTest
 *
 * Tests PUT Sub-Resource request
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/Tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class PUTSubresourceUnitTest extends ERestTestCase
{
	/**
	 * testPUTSubresourceCategoryPostsRequest
	 *
	 * tests that a PUT request with belongsTo
	 * correctly updates a resource
	 */
	public function testPUTSubresourceCategoryPostsRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/category/4/posts/1',
			'type'		=> 'PUT',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];
		
		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Subresource Added","data":{"totalCount":1,"category":{"id":"4","name":"cat4","posts":[{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1"},{"id":"4","title":"title4","content":"content4","create_time":"2013-08-07 10:09:44","author_id":"4"}]}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testPUTSubresourcePostsCategoriesRequest
	 *
	 * tests that a PUT request with belongsTo
	 * correctly updates a resource
	 */
	public function testPUTSubresourcePostsCategoriesRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/post/1/categories/4',
			'type'		=> 'PUT',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];
		
		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Subresource Added","data":{"totalCount":1,"post":{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1","categories":[{"id":"1","name":"cat1"},{"id":"2","name":"cat2"},{"id":"4","name":"cat4"}]}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

}
