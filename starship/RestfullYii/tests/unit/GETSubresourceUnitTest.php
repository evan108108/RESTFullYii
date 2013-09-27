<?php
Yii::import('RestfullYii.tests.ERestTestRequestHelper');

/**
 * GETSubresourceUnitTest
 *
 * Tests GET request for single sub-resource
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/Tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class GETSubresourceUnitTest extends ERestTestCase
{
	/**
	 * testGETSubresourceRequest
	 *
	 * tests that a get request for a single sub-resource
	 * returns the correct response
	 */
	public function testGETSubresourcePostCategoryRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/post/1/categories/1',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":1,"category":{"id":"1","name":"cat1"}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETSubresourceRequest
	 *
	 * tests that a get request for a single sub-resource
	 * returns the correct response
	 */
	public function testGETSubresourceCategoryPostRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/category/1/posts/1',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":1,"post":{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1"}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

}
