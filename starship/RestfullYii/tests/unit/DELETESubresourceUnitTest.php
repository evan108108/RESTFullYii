<?php
Yii::import('RestfullYii.tests.ERestTestRequestHelper');

/**
 * DELETESubresourceUnitTest
 *
 * Tests DELETE Sub-Resource request
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/Tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class DELETESubresourceUnitTest extends ERestTestCase
{
	/**
	 * testDELETESubresourceCategoryPostsRequest
	 *
	 * tests that a DELETE request
	 * correctly deletes a Sub-Resource
	 */
	public function testDELETESubresourceCategoryPostsRequest()
	{
		$request = new ERestTestRequestHelper();

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
	 * testDELETESubresourcePostsCategoriesRequest
	 *
	 * tests that a DELETE request
	 * correctly deletes a Sub-Resource
	 */
	public function testDELETESubresourcePostsCategoriesRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/post/1/categories/1',
			'type'		=> 'DELETE',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];
		
		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Sub-Resource Deleted","data":{"totalCount":1,"post":{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1","categories":[{"id":"2","name":"cat2"}]}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

}

