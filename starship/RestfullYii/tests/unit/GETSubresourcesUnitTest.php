<?php
Yii::import('RestfullYii.tests.ERestTestRequestHelper');

/**
 * GETSubresourcesUnitTest
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
class GETSubresourcesUnitTest extends ERestTestCase
{
	/**
	 * testGETSubresourceRequest
	 *
	 * tests that a get request for sub-resources
	 * returns the correct response
	 */
	public function testGETSubresourcesPostCategoryRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/post/1/categories',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":2,"category":[{"id":"1","name":"cat1"},{"id":"2","name":"cat2"}]}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETSubresourcesRequest
	 *
	 * tests that a get request for sub-resources
	 * returns the correct response
	 */
	public function testGETSubresourcesCategoryPostRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/category/1/posts',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":1,"post":[{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1"}]}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}


	/**
	 * testGETSubresourcesCategoryUserRequestForHasManyWithEventOverrideRequest
	 *
	 * tests that a get request for a non sub-resources (HAS_MANY) with event override
	 * returns the correct response
	 */
	public function testGETSubresourcesCategoryUserRequestForHasManyWithEventOverrideRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/user/1/posts',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request->addEvent('req.is.subresource', function($model, $subresource_name, $http_verb) {
			return true;
		});

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":1,"post":[{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1"}]}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

}

