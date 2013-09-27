<?php
Yii::import('RestfullYii.tests.ERestTestRequestHelper');

/**
 * GETResourcesUnitTest
 *
 * Tests GET request for a list of resources
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/Tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class GETResourcesUnitTest extends ERestTestCase
{
	/**
	 * testGETResourcesCategoriesRequest
	 *
	 * tests that a GET request for a list of 'Category' resources
	 * returns the correct response
	 */
	public function testGETResourcesCategoriesRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/category',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":"6","category":[{"id":"1","name":"cat1","posts":[{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1"}]},{"id":"2","name":"cat2","posts":[{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1"},{"id":"2","title":"title2","content":"content2","create_time":"2013-08-07 10:09:42","author_id":"2"}]},{"id":"3","name":"cat3","posts":[{"id":"3","title":"title3","content":"content3","create_time":"2013-08-07 10:09:43","author_id":"3"}]},{"id":"4","name":"cat4","posts":[{"id":"4","title":"title4","content":"content4","create_time":"2013-08-07 10:09:44","author_id":"4"}]},{"id":"5","name":"cat5","posts":[{"id":"5","title":"title5","content":"content5","create_time":"2013-08-07 10:09:45","author_id":"5"}]},{"id":"6","name":"cat6","posts":[{"id":"6","title":"title6","content":"content6","create_time":"2013-08-07 10:09:46","author_id":"6"}]}]}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETResourcesPostsRequest
	 *
	 * tests that a GET request for a list of 'Post' resources
	 * returns the correct response
	 */
	public function testGETResourcesPostsRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/post',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":"6","post":[{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1","categories":[{"id":"1","name":"cat1"},{"id":"2","name":"cat2"}],"author":{"id":"1","username":"username1","password":"password1","email":"email@email1.com"}},{"id":"2","title":"title2","content":"content2","create_time":"2013-08-07 10:09:42","author_id":"2","categories":[{"id":"2","name":"cat2"}],"author":{"id":"2","username":"username2","password":"password2","email":"email@email2.com"}},{"id":"3","title":"title3","content":"content3","create_time":"2013-08-07 10:09:43","author_id":"3","categories":[{"id":"3","name":"cat3"}],"author":{"id":"3","username":"username3","password":"password3","email":"email@email3.com"}},{"id":"4","title":"title4","content":"content4","create_time":"2013-08-07 10:09:44","author_id":"4","categories":[{"id":"4","name":"cat4"}],"author":{"id":"4","username":"username4","password":"password4","email":"email@email4.com"}},{"id":"5","title":"title5","content":"content5","create_time":"2013-08-07 10:09:45","author_id":"5","categories":[{"id":"5","name":"cat5"}],"author":{"id":"5","username":"username5","password":"password5","email":"email@email5.com"}},{"id":"6","title":"title6","content":"content6","create_time":"2013-08-07 10:09:46","author_id":"6","categories":[{"id":"6","name":"cat6"}],"author":{"id":"6","username":"username6","password":"password6","email":"email@email6.com"}}]}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

}
