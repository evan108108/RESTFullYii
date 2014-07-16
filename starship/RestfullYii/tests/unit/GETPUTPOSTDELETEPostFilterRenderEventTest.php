<?php
Yii::import('RestfullYii.tests.ERestTestRequestHelper');

/**
 * GETPUTPOSTDELETEPostFilterRenderEventTest
 *
 * Tests req.post.filter.{get,put,post,delete}.{resouce,resources,subresource,subresources}.render events
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/Tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class GETPUTPOSTDELETEPostFilterRenderEventTest extends ERestTestCase
{
	/**
	 * testGETResourceRequestPostFilterRequest
	 *
	 * tests that a get request for a single resource
	 * with new property added durring post filter
	 * returns the correct response
	 */
	public function testGETResourceRequestPostFilterRequest()
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

		$request->addEvent('post.filter.req.get.resource.render', function($json) {
			$j = CJSON::decode($json);
			$j['new_key'] = 'NEW VALUE';
			return $j;
		});

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Found","new_key":"NEW VALUE","data":{"totalCount":1,"post":{"id":"6","title":"title6","content":"content6","create_time":"2013-08-07 10:09:46","author_id":"6"}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETResourcesCategoriesPostFilterRequest
	 *
	 * tests that a GET request for a list of 'Category' resources
	 * with new property added durring post filter
	 * returns the correct response
	 */
	public function testGETResourcesCategoriesPostFilterRequest()
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

		$request->addEvent('post.filter.req.get.resources.render', function($json) {
			$j = CJSON::decode($json);
			$j['new_key'] = 'NEW VALUE';
			return $j;
		});

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record(s) Found", "new_key":"NEW VALUE", "data":{"totalCount":6,"category":[{"id":"1","name":"cat1","posts":[{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1"}]},{"id":"2","name":"cat2","posts":[{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1"},{"id":"2","title":"title2","content":"content2","create_time":"2013-08-07 10:09:42","author_id":"2"}]},{"id":"3","name":"cat3","posts":[{"id":"3","title":"title3","content":"content3","create_time":"2013-08-07 10:09:43","author_id":"3"}]},{"id":"4","name":"cat4","posts":[{"id":"4","title":"title4","content":"content4","create_time":"2013-08-07 10:09:44","author_id":"4"}]},{"id":"5","name":"cat5","posts":[{"id":"5","title":"title5","content":"content5","create_time":"2013-08-07 10:09:45","author_id":"5"}]},{"id":"6","name":"cat6","posts":[{"id":"6","title":"title6","content":"content6","create_time":"2013-08-07 10:09:46","author_id":"6"}]}]}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testPUTResourceCategoryPostFilterRequest
	 *
	 * tests that a PUT request
	 * correctly updates a resource
	 * with new property added durring post filter
	 */
	public function testPUTResourceCategoryPostFilterRequest()
	{
		$request = new ERestTestRequestHelper();

		$data = '{"id":"1","name":"update_cat_name"}';

		$request['config'] = [
			'url'			=> 'http://api/category/1',
			'type'		=> 'PUT',
			'data'		=> $data,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];
		
		$request->addEvent(ERestEvent::MODEL_WITH_RELATIONS, function() {
			return [];
		});

		$request->addEvent('post.filter.req.put.resource.render', function($json) {
			$j = CJSON::decode($json);
			$j['new_key'] = 'NEW VALUE';
			return $j;
		});

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Updated","new_key":"NEW VALUE","data":{"totalCount":1,"category":' . $data . '}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testPOSTResourceCategoryPostFilterRequest
	 *
	 * tests that a POST request
	 * correctly creates a resource
	 * with new property added durring post filter
	 */
	public function testPOSTResourceCategoryPostFilterRequest()
	{
		$request = new ERestTestRequestHelper();

		$data = '{"name":"new_cat_name"}';

		$request['config'] = [
			'url'			=> 'http://api/category',
			'type'		=> 'POST',
			'data'		=> $data,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request->addEvent('post.filter.req.post.resource.render', function($json) {
			$j = CJSON::decode($json);
			$j['new_key'] = 'NEW VALUE';
			return $j;
		});
		
		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Created","new_key":"NEW VALUE","data":{"totalCount":1,"category":{"id":"7","name":"new_cat_name","posts":[]}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testDELETEResourceCategoryPostFilterRequest
	 *
	 * tests that a DELETE request
	 * correctly deletes a resource
	 * with new property added durring post filter
	 */
	public function testDELETEResourceCategoryPostFilterRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/category/4',
			'type'		=> 'DELETE',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request->addEvent('post.filter.req.delete.resource.render', function($json) {
			$j = CJSON::decode($json);
			$j['new_key'] = 'NEW VALUE';
			return $j;
		});
		
		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Deleted","new_key":"NEW VALUE","data":{"totalCount":1,"category":{"id":"4","name":"cat4"}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETSubresourcePostCategoryPostFilterRequest
	 *
	 * tests that a get request for a single sub-resource
	 * with new property added durring post filter
	 * returns the correct response
	 */
	public function testGETSubresourcePostCategoryPostFilterRequest()
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

		$request->addEvent('post.filter.req.get.subresource.render', function($json) {
			$j = CJSON::decode($json);
			$j['new_key'] = 'NEW VALUE';
			return $j;
		});

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record(s) Found","new_key":"NEW VALUE","data":{"totalCount":1,"category":{"id":"1","name":"cat1"}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETSubresourcesPostCategoryPostFilterRequest
	 *
	 * tests that a get request for a single sub-resource
	 * with new property added durring post filter
	 * returns the correct response
	 */
	public function testGETSubresourcesPostCategoryPostFilterRequest()
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

		$request->addEvent('post.filter.req.get.subresources.render', function($json) {
			$j = CJSON::decode($json);
			$j['new_key'] = 'NEW VALUE';
			return $j;
		});

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record(s) Found","new_key":"NEW VALUE","data":{"totalCount":2,"category":[{"id":"1","name":"cat1"},{"id":"2","name":"cat2"}]}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testPUTSubresourceCategoryPostsPostFilterRequest
	 *
	 * tests that a PUT request with belongsTo
	 * correctly updates a resource
	 * with new property added durring post filter
	 */
	public function testPUTSubresourceCategoryPostsPostFilterRequest()
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

		$request->addEvent('post.filter.req.put.subresource.render', function($json) {
			$j = CJSON::decode($json);
			$j['new_key'] = 'NEW VALUE';
			return $j;
		});
		
		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Subresource Added","new_key":"NEW VALUE","data":{"totalCount":1,"category":{"id":"4","name":"cat4","posts":[{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1"},{"id":"4","title":"title4","content":"content4","create_time":"2013-08-07 10:09:44","author_id":"4"}]}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testDELETESubresourceCategoryPostsPostFilterRequest
	 *
	 * tests that a DELETE request
	 * correctly deletes a Sub-Resource
	 * with new property added durring post filter
	 */
	public function testDELETESubresourceCategoryPostsPostFilterRequest()
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

		$request->addEvent('post.filter.req.delete.subresource.render', function($json) {
			$j = CJSON::decode($json);
			$j['new_key'] = 'NEW VALUE';
			return $j;
		});
		
		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Sub-Resource Deleted","new_key":"NEW VALUE","data":{"totalCount":1,"category":{"id":"1","name":"cat1","posts":[]}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}
}
