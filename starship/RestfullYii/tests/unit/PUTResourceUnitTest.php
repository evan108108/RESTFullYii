<?php
Yii::import('RestfullYii.tests.ERestTestRequestHelper');

/**
 * PUTResourceUnitTest
 *
 * Tests PUT resource request
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/Tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class PUTResourceUnitTest extends ERestTestCase
{
	/**
	 * testPUTResourceCategoryRequest
	 *
	 * tests that a PUT request
	 * correctly updates a resource
	 */
	public function testPUTResourceCategoryRequest()
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

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Updated","data":{"totalCount":1,"category":' . $data . '}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testPUTResourceUserRequest
	 *
	 * tests that a PUT request
	 * correctly updates a resource
	 */
	public function testPUTResourceUserRequest()
	{
		$request = new ERestTestRequestHelper();

		$data = '{"id":"2","username":"UPDATEDUSERNAME","password":"UPDATEDPASSWORD","email":"UPDATED@email2.com"}';

		$request['config'] = [
			'url'			=> 'http://api/user/2',
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

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Updated","data":{"totalCount":1,"user":' . $data . '}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

}
