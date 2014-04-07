<?php
Yii::import('RestfullYii.tests.ERestTestRequestHelper');

/**
 * GETResourcesLimitOffsetUnitTest
 *
 * Tests GET request for a list of resources with limit and or offset
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/Tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class GETResourcesLimitOffsetUnitTest extends ERestTestCase
{
	/**
	 * testGETResourcesLimitCategoriesRequest
	 *
	 * tests that a GET request for a list of 'Category' resources
	 * with limit returns the correct response
	 */
	public function testGETResourcesLimitCategoriesRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/category?limit=2',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$this->assertEquals(2, count(CJSON::decode($request_response)['data']['category']));
		$expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":6,"category":[{"id":"1","name":"cat1","posts":[{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1"}]},{"id":"2","name":"cat2","posts":[{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1"},{"id":"2","title":"title2","content":"content2","create_time":"2013-08-07 10:09:42","author_id":"2"}]}]}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETResourcesOffsetCategoriesRequest
	 *
	 * tests that a GET request for a list of 'Category' resources
	 * with offset returns the correct response
	 */
	public function testGETResourcesOffsetCategoriesRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/category?offset=2',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$this->assertEquals(4, count(CJSON::decode($request_response)['data']['category']));
		$expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":6,"category":[{"id":"3","name":"cat3","posts":[{"id":"3","title":"title3","content":"content3","create_time":"2013-08-07 10:09:43","author_id":"3"}]},{"id":"4","name":"cat4","posts":[{"id":"4","title":"title4","content":"content4","create_time":"2013-08-07 10:09:44","author_id":"4"}]},{"id":"5","name":"cat5","posts":[{"id":"5","title":"title5","content":"content5","create_time":"2013-08-07 10:09:45","author_id":"5"}]},{"id":"6","name":"cat6","posts":[{"id":"6","title":"title6","content":"content6","create_time":"2013-08-07 10:09:46","author_id":"6"}]}]}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETResourcesLimitOffsetCategoriesRequest
	 *
	 * tests that a GET request for a list of 'Category' resources
	 * with limit & offset returns the correct response
	 */
	public function testGETResourcesLimitOffsetCategoriesRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/category?limit=2&offset=2',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$this->assertEquals(2, count(CJSON::decode($request_response)['data']['category']));
		$expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":6,"category":[{"id":"3","name":"cat3","posts":[{"id":"3","title":"title3","content":"content3","create_time":"2013-08-07 10:09:43","author_id":"3"}]},{"id":"4","name":"cat4","posts":[{"id":"4","title":"title4","content":"content4","create_time":"2013-08-07 10:09:44","author_id":"4"}]}]}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETResourcesLimitPostsRequest
	 *
	 * tests that a GET request for a list of 'Posts' resources
	 * with limit returns the correct response
	 */
	public function testGETResourcesLimitPostsRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/post?limit=1',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$this->assertEquals(1, count(CJSON::decode($request_response)['data']['post']));
		$expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":6,"post":[{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1","categories":[{"id":"1","name":"cat1"},{"id":"2","name":"cat2"}],"author":{"id":"1","username":"username1","password":"password1","email":"email@email1.com"}}]}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETResourcesOffsetPostsRequest
	 *
	 * tests that a GET request for a list of 'Post' resources
	 * with offset returns the correct response
	 */
	public function testGETResourcesOffsetPostsRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/post?offset=5',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$this->assertEquals(1, count(CJSON::decode($request_response)['data']['post']));
		$expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":6,"post":[{"id":"6","title":"title6","content":"content6","create_time":"2013-08-07 10:09:46","author_id":"6","categories":[{"id":"6","name":"cat6"}],"author":{"id":"6","username":"username6","password":"password6","email":"email@email6.com"}}]}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETResourcesLimitOffsetPostsRequest
	 *
	 * tests that a GET request for a list of 'Post' resources
	 * with limit & offset returns the correct response
	 */
	public function testGETResourcesLimitOffsetPostsRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/post?limit=1&offset=2',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$this->assertEquals(1, count(CJSON::decode($request_response)['data']['post']));
		$expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":6,"post":[{"id":"3","title":"title3","content":"content3","create_time":"2013-08-07 10:09:43","author_id":"3","categories":[{"id":"3","name":"cat3"}],"author":{"id":"3","username":"username3","password":"password3","email":"email@email3.com"}}]}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}
}
