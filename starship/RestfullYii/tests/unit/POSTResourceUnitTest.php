<?php
Yii::import('RestfullYii.tests.ERestTestRequestHelper');

/**
 * POSTResourceUnitTest
 *
 * Tests POST Resource request
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/Tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class POSTResourceUnitTest extends ERestTestCase
{
	/**
	 * testPOSTResourceCategoryRequest
	 *
	 * tests that a POST request
	 * correctly creates a resource
	 */
	public function testPOSTResourceCategoryRequest()
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
		
		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Created","data":{"totalCount":1,"category":{"id":"7","name":"new_cat_name","posts":[]}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testPOSTResourceProfileRequest
	 *
	 * tests that a POST request
	 * correctly creates a resource
	 */
	public function testPOSTResourceProfileRequest()
	{
		$request = new ERestTestRequestHelper();

		$data = '{"user_id":"4","photo":"0","website":"mysite4.com"}';

		$request['config'] = [
			'url'			=> 'http://api/profile',
			'type'		=> 'POST',
			'data'		=> $data,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request->addEvent(ERestEvent::MODEL_WITH_RELATIONS, function() {
			return [];
		});

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Created","data":{"totalCount":1,"profile":{"id":"7","user_id":"4","photo":"0","website":"mysite4.com"}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

}
