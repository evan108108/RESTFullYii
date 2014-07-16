<?php
Yii::import('RestfullYii.tests.ERestTestRequestHelper');

/**
 * OverrideAttributesEventUnitTest
 *
 * Tests model.{YOUR-MODEL-NAME-HERE}.override.attributes event
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/Tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class OverrideAttributesEventUnitTest extends ERestTestCase
{
	/**
	 * testGETResourceRequestPostOverrideAttributes
	 *
	 * tests that a get request for a single resource
	 * with new property model added durring model.post.override.attributes
	 * returns the correct response
	 */
	public function testGETResourceRequestPostOverrideAttributes()
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

		$request->addEvent('model.post.override.attributes', function($model) {
			return array_merge($model->attributes, ['next_id'=>($model->id + 1)]);
		});

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Found","data":{"totalCount":1,"post":{"id":"6","title":"title6","content":"content6","create_time":"2013-08-07 10:09:46","author_id":"6","next_id":7}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETResourceRequestPostOverrideAttributesWithArrayAttribute
	 *
	 * tests that a get request for a single resource
	 * with new property model added durring model.post.override.attributes
	 * returns the correct response
	 */
	public function testGETResourceRequestPostOverrideAttributesWithArrayAttribute()
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

		$request->addEvent('model.post.override.attributes', function($model) {
			return array_merge($model->attributes, ['title'=>[1,2,3,4],'another_prop'=>[5,6,7,8]]);
		});

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Found","data":{"totalCount":1,"post":{"id":"6","title":[1,2,3,4],"content":"content6","create_time":"2013-08-07 10:09:46","author_id":"6","another_prop":[5,6,7,8]}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETResourceRequestPostsOverrideAttributes
	 *
	 * tests that a get request for a single resource
	 * with new property model added durring model.post.override.attributes
	 * returns the correct response
	 */
	public function testGETResourceRequestPostsOverrideAttributes()
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

		$request->addEvent('model.post.override.attributes', function($model) {
			return array_merge($model->attributes, ['next_id'=>($model->id + 1)]);
		});

		$request->addEvent('model.category.override.attributes', function($model) {
			return array_merge($model->attributes, ['next_cat_id'=>($model->id + 1)]);
		});

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":6,"post":[{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1","next_id":2,"categories":[{"id":"1","name":"cat1","next_cat_id":2},{"id":"2","name":"cat2","next_cat_id":3}],"author":{"id":"1","username":"username1","password":"password1","email":"email@email1.com"}},{"id":"2","title":"title2","content":"content2","create_time":"2013-08-07 10:09:42","author_id":"2","next_id":3,"categories":[{"id":"2","name":"cat2","next_cat_id":3}],"author":{"id":"2","username":"username2","password":"password2","email":"email@email2.com"}},{"id":"3","title":"title3","content":"content3","create_time":"2013-08-07 10:09:43","author_id":"3","next_id":4,"categories":[{"id":"3","name":"cat3","next_cat_id":4}],"author":{"id":"3","username":"username3","password":"password3","email":"email@email3.com"}},{"id":"4","title":"title4","content":"content4","create_time":"2013-08-07 10:09:44","author_id":"4","next_id":5,"categories":[{"id":"4","name":"cat4","next_cat_id":5}],"author":{"id":"4","username":"username4","password":"password4","email":"email@email4.com"}},{"id":"5","title":"title5","content":"content5","create_time":"2013-08-07 10:09:45","author_id":"5","next_id":6,"categories":[{"id":"5","name":"cat5","next_cat_id":6}],"author":{"id":"5","username":"username5","password":"password5","email":"email@email5.com"}},{"id":"6","title":"title6","content":"content6","create_time":"2013-08-07 10:09:46","author_id":"6","next_id":7,"categories":[{"id":"6","name":"cat6","next_cat_id":7}],"author":{"id":"6","username":"username6","password":"password6","email":"email@email6.com"}}]}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testPUTResourceCategoryOverrideAttributes
	 *
	 * tests that a PUT request
	 * correctly updates a resource
	 * with new property model added durring model.category.override.attributes
	 */
	public function testPUTResourceCategoryOverrideAttributes()
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

		$request->addEvent('model.category.override.attributes', function($model) {
			return array_merge($model->attributes, ['next_id'=>($model->id + 1)]);
		});

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Updated","data":{"totalCount":1,"category":{"id":"1","name":"update_cat_name","next_id":2}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testPOSTResourceCategoryOverrideAttributes
	 *
	 * tests that a POST request
	 * correctly creates a resource
	 * with new property model added durring model.category.override.attributes
	 */
	public function testPOSTResourceCategoryOverrideAttributes()
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

		$request->addEvent('model.category.override.attributes', function($model) {
			return array_merge($model->attributes, ['next_id'=>($model->id + 1)]);
		});
		
		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Created","data":{"totalCount":1,"category":{"id":"7","name":"new_cat_name","next_id":8,"posts":[]}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testDELETEResourceCategoryOverrideAttributes
	 *
	 * tests that a DELETE request
	 * correctly deletes a resource
	 * with new property model added durring model.category.override.attributes
	 */
	public function testDELETEResourceCategoryOverrideAttributes()
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

		$request->addEvent('model.category.override.attributes', function($model) {
			return array_merge($model->attributes, ['next_id'=>($model->id + 1)]);
		});
		
		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Deleted","data":{"totalCount":1,"category":{"id":"4","name":"cat4","next_id":5}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETSubresourcePostCategoryOverrideAttributes
	 *
	 * tests that a get request for a single sub-resource
	 * returns the correct response
	 * with new property model added durring model.category.override.attributes
	 */
	public function testGETSubresourcePostCategoryOverrideAttributes()
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

		$request->addEvent('model.category.override.attributes', function($model) {
			return array_merge($model->attributes, ['next_id'=>($model->id + 1)]);
		});

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":1,"category":{"id":"1","name":"cat1","next_id":2}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETSubresourcesPostCategoryOverrideAttributes
	 *
	 * tests that a get request for a single sub-resource
	 * with new property model added durring model.category.override.attributes
	 * returns the correct response
	 */
	public function testGETSubresourcesPostCategoryOverrideAttributes()
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

		$request->addEvent('model.category.override.attributes', function($model) {
			return array_merge($model->attributes, ['next_id'=>($model->id + 1)]);
		});

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":2,"category":[{"id":"1","name":"cat1","next_id":2},{"id":"2","name":"cat2","next_id":3}]}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testPUTSubresourceCategoryPostsOverrideAttributes
	 *
	 * tests that a PUT request with belongsTo
	 * correctly updates a resource
	 * with new property model added durring model.post.override.attributes
	 */
	public function testPUTSubresourceCategoryPostsOverrideAttributes()
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

		$request->addEvent('model.post.override.attributes', function($model) {
			return array_merge($model->attributes, ['next_id'=>($model->id + 1)]);
		});
		
		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Subresource Added","data":{"totalCount":1,"category":{"id":"4","name":"cat4","posts":[{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1","next_id":2},{"id":"4","title":"title4","content":"content4","create_time":"2013-08-07 10:09:44","author_id":"4","next_id":5}]}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testDELETESubresourceCategoryPostsOverrideAttributes
	 *
	 * tests that a DELETE request
	 * correctly deletes a Sub-Resource
	 * with new property model added durring model.post.override.attributes
	 */
	public function testDELETESubresourceCategoryPostsOverrideAttributes()
	{
		$request = new ERestTestRequestHelper();

		
		$request['config'] = [
			'url'			=> 'http://api/category/1/posts/2',
			'type'		=> 'PUT',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request->send();

		$request['config'] = [
			'url'			=> 'http://api/category/1/posts/1',
			'type'		=> 'DELETE',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request->addEvent('model.post.override.attributes', function($model) {
			return array_merge($model->attributes, ['next_id'=>($model->id + 1)]);
		});
		
		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Sub-Resource Deleted","data":{"totalCount":1,"category":{"id":"1","name":"cat1","posts":[{"id":"2","title":"title2","content":"content2","create_time":"2013-08-07 10:09:42","author_id":"2","next_id":3}]}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}
}
