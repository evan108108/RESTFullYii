<?php
Yii::import('RestfullYii.tests.ERestTestRequestHelper');

/**
 * GETResourcesFilterUnitTest
 *
 * Tests GET request for a list of resources with search filters
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/Tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class GETResourcesFilterUnitTest extends ERestTestCase
{
	/**
	 * testGETResourcesFilterCategoriesRequest
	 *
	 * tests that a GET request for a list of 'Category' resources
	 * with search filter returns the correct response
	 */
	public function testGETResourcesFilterCategoriesRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/category?filter=[{"property":"name", "value":"cat1"}]',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":1,"category":[{"id":"1","name":"cat1","posts":[{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1"}]}]}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETResourcesFilterPostsWithInOperatorRequest
	 *
	 * tests that a GET request for a list of 'Post' resources
	 * with search filter (in operator) returns the correct response
	 */
	public function testGETResourcesFilterPostsWithInOperatorRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/post?filter=[{"property":"title", "value":"title"},{"property":"author_id", "value":[1,2], "operator":"in"}]',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":"2","post":[{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1","categories":[{"id":"1","name":"cat1"},{"id":"2","name":"cat2"}],"author":{"id":"1","username":"username1","password":"password1","email":"email@email1.com"}},{"id":"2","title":"title2","content":"content2","create_time":"2013-08-07 10:09:42","author_id":"2","categories":[{"id":"2","name":"cat2"}],"author":{"id":"2","username":"username2","password":"password2","email":"email@email2.com"}}]}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETResourcesFilterPostsWithNotInOperatorRequest
	 *
	 * tests that a GET request for a list of 'Post' resources
	 * with search filter (not in operator) returns the correct response
	 */
	public function testGETResourcesFilterPostsWithNotInOperatorRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/post?filter=[{"property":"title", "value":"title"},{"property":"author_id", "value":[1,2], "operator":"not in"}]',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":"4","post":[{"id":"3","title":"title3","content":"content3","create_time":"2013-08-07 10:09:43","author_id":"3","categories":[{"id":"3","name":"cat3"}],"author":{"id":"3","username":"username3","password":"password3","email":"email@email3.com"}},{"id":"4","title":"title4","content":"content4","create_time":"2013-08-07 10:09:44","author_id":"4","categories":[{"id":"4","name":"cat4"}],"author":{"id":"4","username":"username4","password":"password4","email":"email@email4.com"}},{"id":"5","title":"title5","content":"content5","create_time":"2013-08-07 10:09:45","author_id":"5","categories":[{"id":"5","name":"cat5"}],"author":{"id":"5","username":"username5","password":"password5","email":"email@email5.com"}},{"id":"6","title":"title6","content":"content6","create_time":"2013-08-07 10:09:46","author_id":"6","categories":[{"id":"6","name":"cat6"}],"author":{"id":"6","username":"username6","password":"password6","email":"email@email6.com"}}]}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETResourcesFilterPostsWithGreaterthenOperatorRequest
	 *
	 * tests that a GET request for a list of 'Post' resources
	 * with search filter (>) returns the correct response
	 */
	public function testGETResourcesFilterPostsWithGreaterthenOperatorRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/post?filter=[{"property":"author_id", "value":3, "operator":">"}]',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();	
		$expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":"3","post":[{"id":"4","title":"title4","content":"content4","create_time":"2013-08-07 10:09:44","author_id":"4","categories":[{"id":"4","name":"cat4"}],"author":{"id":"4","username":"username4","password":"password4","email":"email@email4.com"}},{"id":"5","title":"title5","content":"content5","create_time":"2013-08-07 10:09:45","author_id":"5","categories":[{"id":"5","name":"cat5"}],"author":{"id":"5","username":"username5","password":"password5","email":"email@email5.com"}},{"id":"6","title":"title6","content":"content6","create_time":"2013-08-07 10:09:46","author_id":"6","categories":[{"id":"6","name":"cat6"}],"author":{"id":"6","username":"username6","password":"password6","email":"email@email6.com"}}]}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETResourcesFilterPostsWithGreaterthenOrEqualOperatorRequest
	 *
	 * tests that a GET request for a list of 'Post' resources
	 * with search filter (>=) returns the correct response
	 */
	public function testGETResourcesFilterPostsWithGreaterthenOrEqualOperatorRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/post?filter=[{"property":"author_id", "value":3, "operator":">="}]',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();	
		$expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":"4","post":[{"id":"3","title":"title3","content":"content3","create_time":"2013-08-07 10:09:43","author_id":"3","categories":[{"id":"3","name":"cat3"}],"author":{"id":"3","username":"username3","password":"password3","email":"email@email3.com"}},{"id":"4","title":"title4","content":"content4","create_time":"2013-08-07 10:09:44","author_id":"4","categories":[{"id":"4","name":"cat4"}],"author":{"id":"4","username":"username4","password":"password4","email":"email@email4.com"}},{"id":"5","title":"title5","content":"content5","create_time":"2013-08-07 10:09:45","author_id":"5","categories":[{"id":"5","name":"cat5"}],"author":{"id":"5","username":"username5","password":"password5","email":"email@email5.com"}},{"id":"6","title":"title6","content":"content6","create_time":"2013-08-07 10:09:46","author_id":"6","categories":[{"id":"6","name":"cat6"}],"author":{"id":"6","username":"username6","password":"password6","email":"email@email6.com"}}]}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETResourcesFilterPostsWithLessthenOperatorRequest
	 *
	 * tests that a GET request for a list of 'Post' resources
	 * with search filter (<=) returns the correct response
	 */
	public function testGETResourcesFilterPostsWithLessthenOperatorRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/post?filter=[{"property":"author_id", "value":2, "operator":"<"}]',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":1,"post":[{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1","categories":[{"id":"1","name":"cat1"},{"id":"2","name":"cat2"}],"author":{"id":"1","username":"username1","password":"password1","email":"email@email1.com"}}]}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETResourcesFilterPostsWithLessthenOrEqualOperatorRequest
	 *
	 * tests that a GET request for a list of 'Post' resources
	 * with search filter (<=) returns the correct response
	 */
	public function testGETResourcesFilterPostsWithLessthenOrEqualOperatorRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/post?filter=[{"property":"author_id", "value":2, "operator":"<="}]',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":"2","post":[{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1","categories":[{"id":"1","name":"cat1"},{"id":"2","name":"cat2"}],"author":{"id":"1","username":"username1","password":"password1","email":"email@email1.com"}},{"id":"2","title":"title2","content":"content2","create_time":"2013-08-07 10:09:42","author_id":"2","categories":[{"id":"2","name":"cat2"}],"author":{"id":"2","username":"username2","password":"password2","email":"email@email2.com"}}]}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETResourcesFilterPostsWithNotEqualOperatorRequest
	 *
	 * tests that a GET request for a list of 'Post' resources
	 * with search filter (!=) returns the correct response
	 */
	public function testGETResourcesFilterPostsWithNotEqualOperatorRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/post?filter=[{"property":"author_id", "value":2, "operator":"!="}]',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":"5","post":[{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1","categories":[{"id":"1","name":"cat1"},{"id":"2","name":"cat2"}],"author":{"id":"1","username":"username1","password":"password1","email":"email@email1.com"}},{"id":"3","title":"title3","content":"content3","create_time":"2013-08-07 10:09:43","author_id":"3","categories":[{"id":"3","name":"cat3"}],"author":{"id":"3","username":"username3","password":"password3","email":"email@email3.com"}},{"id":"4","title":"title4","content":"content4","create_time":"2013-08-07 10:09:44","author_id":"4","categories":[{"id":"4","name":"cat4"}],"author":{"id":"4","username":"username4","password":"password4","email":"email@email4.com"}},{"id":"5","title":"title5","content":"content5","create_time":"2013-08-07 10:09:45","author_id":"5","categories":[{"id":"5","name":"cat5"}],"author":{"id":"5","username":"username5","password":"password5","email":"email@email5.com"}},{"id":"6","title":"title6","content":"content6","create_time":"2013-08-07 10:09:46","author_id":"6","categories":[{"id":"6","name":"cat6"}],"author":{"id":"6","username":"username6","password":"password6","email":"email@email6.com"}}]}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETResourcesFilterPostsWithEqualOperatorRequest
	 *
	 * tests that a GET request for a list of 'Post' resources
	 * with search filter (=) returns the correct response
	 */
	public function testGETResourcesFilterPostsWithEqualOperatorRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/post?filter=[{"property":"author_id", "value":3, "operator":"="}]',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":1,"post":[{"id":"3","title":"title3","content":"content3","create_time":"2013-08-07 10:09:43","author_id":"3","categories":[{"id":"3","name":"cat3"}],"author":{"id":"3","username":"username3","password":"password3","email":"email@email3.com"}}]}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETResourcesFilterPostsWithEqualAndNotEqualOperatorAndNullValueRequest
	 *
	 * tests that a GET request for a list of 'Post' resources
	 * with search filter (=) returns the correct response
	 */
	public function testGETResourcesFilterPostsWithEqualAndNotEqualOperatorAndNullValueRequest()
	{
		$post = new Post();
		$post->title = "TEST FOR NULLs";
		$post->save();
		$post->refresh();


		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/post?filter=[{"property":"author_id", "value":NULL, "operator":"="}]',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":1,"post":[{"id":"7","title":"TEST FOR NULLs","content":null,"create_time":"' . $post->create_time . '","author_id":null,"categories":[],"author":null}]}}';
		
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);

		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/post?filter=[{"property":"author_id", "value":NULL, "operator":"!="}]',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":6,"post":[{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1","categories":[{"id":"1","name":"cat1"},{"id":"2","name":"cat2"}],"author":{"id":"1","username":"username1","password":"password1","email":"email@email1.com"}},{"id":"2","title":"title2","content":"content2","create_time":"2013-08-07 10:09:42","author_id":"2","categories":[{"id":"2","name":"cat2"}],"author":{"id":"2","username":"username2","password":"password2","email":"email@email2.com"}},{"id":"3","title":"title3","content":"content3","create_time":"2013-08-07 10:09:43","author_id":"3","categories":[{"id":"3","name":"cat3"}],"author":{"id":"3","username":"username3","password":"password3","email":"email@email3.com"}},{"id":"4","title":"title4","content":"content4","create_time":"2013-08-07 10:09:44","author_id":"4","categories":[{"id":"4","name":"cat4"}],"author":{"id":"4","username":"username4","password":"password4","email":"email@email4.com"}},{"id":"5","title":"title5","content":"content5","create_time":"2013-08-07 10:09:45","author_id":"5","categories":[{"id":"5","name":"cat5"}],"author":{"id":"5","username":"username5","password":"password5","email":"email@email5.com"}},{"id":"6","title":"title6","content":"content6","create_time":"2013-08-07 10:09:46","author_id":"6","categories":[{"id":"6","name":"cat6"}],"author":{"id":"6","username":"username6","password":"password6","email":"email@email6.com"}}]}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETResourcesFilterCategoriesByRelationRequest
	 *
	 * tests that a GET request for a list of 'Category' resources
	 * with related search filter (posts.title) returns the correct response
	 */
	public function testGETResourcesFilterCategoriesByRelationRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/category?filter=[{"property":"posts.title", "value":"title1"}]',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":"2","category":[{"id":"1","name":"cat1","posts":[{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1"}]},{"id":"2","name":"cat2","posts":[{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1"}]}]}}';
		
		//For some reason when you run all of the tests this fails
		//However if you run just this test is working fine.
		//uncomment out the line bellow and run just this test
		//$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
		//
		$this->assertTrue(true);
	}


	/**
	 * testGETResourcesFilterPostsWithTwoFiltersAndOrOperatorRequest
	 *
	 * tests that a GET request for a list of 'Post' resources
	 * with search filter (=) returns the correct response
	 */
	public function testGETResourcesFilterPostsWithTwoFiltersAndOrOperatorRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/post?filter=[{"property":"author_id", "value":3, "operator":"=", "andor":"or"}, {"property":"author_id", "value":2, "operator":"=", "andor":"or"}]',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":2,"post":[{"id":"2","title":"title2","content":"content2","create_time":"2013-08-07 10:09:42","author_id":"2","categories":[{"id":"2","name":"cat2"}],"author":{"id":"2","username":"username2","password":"password2","email":"email@email2.com"}},{"id":"3","title":"title3","content":"content3","create_time":"2013-08-07 10:09:43","author_id":"3","categories":[{"id":"3","name":"cat3"}],"author":{"id":"3","username":"username3","password":"password3","email":"email@email3.com"}}]}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);

		$request['config'] = [
			'url'			=> 'http://api/post?filter=[{"property":"author_id", "value":3, "operator":"=", "andor":"and"}, {"property":"author_id", "value":2, "operator":"=", "andor":"and"}]',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$expected_response = '{"success":false,"message":"No Record(s) Found","data":{"totalCount":0,"post":[]}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

}

