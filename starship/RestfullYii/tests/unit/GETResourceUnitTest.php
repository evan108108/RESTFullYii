<?php
Yii::import('RestfullYii.tests.ERestTestRequestHelper');

/**
 * GETResourceUnitTest
 *
 * Tests GET request for single resource
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/Tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class GETResourceUnitTest extends ERestTestCase
{
	/**
	 * testGETResourceRequestWithNoRelations
	 *
	 * tests that a get request for a single resource
	 * returns the correct response
	 */
	public function testGETResourceRequestWithNoRelations()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/post/6',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request->addEvent(ERestEvent::POST_FILTER_MODEL_WITH_RELATIONS, function() {
			return [];
		});

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Found","data":{"totalCount":1,"post":{"id":"6","title":"title6","content":"content6","create_time":"2013-08-07 10:09:46","author_id":"6"}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETResourceRequestWithRelations
	 *
	 * tests that a get request for a single resource
	 * returns the correct response
	 */
	public function testGETResourceRequestWithRelations()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/post/6',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request->addEvent(ERestEvent::POST_FILTER_MODEL_WITH_RELATIONS, function() {
			return ['author', 'categories'];
		});

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Found","data":{"totalCount":1,"post":{"id":"6","title":"title6","content":"content6","create_time":"2013-08-07 10:09:46","author_id":"6","author":{"id":"6","username":"username6","password":"password6","email":"email@email6.com"},"categories":[{"id":"6","name":"cat6"}]}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETCategory
	 *
	 * tests that a get request for a single resource (category)
	 * returns the correct response
	 */
	public function testGETCategory()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/category/1',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Found","data":{"totalCount":1,"category":{"id":"1","name":"cat1","posts":[{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1"}]}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETProfile
	 *
	 * tests that a get request for a single resource (category)
	 * returns the correct response
	 */
	public function testGETProfile()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/profile/2',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Found","data":{"totalCount":1,"profile":{"id":"2","user_id":"2","photo":"0","website":"mysite2.com","owner":{"id":"2","username":"username2","password":"password2","email":"email@email2.com"}}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

}
