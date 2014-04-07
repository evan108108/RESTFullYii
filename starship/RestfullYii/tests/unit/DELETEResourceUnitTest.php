<?php
Yii::import('RestfullYii.tests.ERestTestRequestHelper');

/**
 * DELETEResourceUnitTest
 *
 * Tests DELETE Resource request
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/Tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class DELETEResourceUnitTest extends ERestTestCase
{
	/**
	 * testDELETEResourceCategoryRequest
	 *
	 * tests that a DELETE request
	 * correctly deletes a resource
	 */
	public function testDELETEResourceCategoryRequest()
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
		
		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Deleted","data":{"totalCount":1,"category":{"id":"4","name":"cat4"}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testDELETEResourcePostRequest
	 *
	 * tests that a DELETE request
	 * correctly deletes a resource
	 */
	public function testDELETEResourcePostRequest()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/post/2',
			'type'		=> 'DELETE',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];
		
		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Deleted","data":{"totalCount":1,"post":{"id":"2","title":"title2","content":"content2","create_time":"2013-08-07 10:09:42","author_id":"2"}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

}
