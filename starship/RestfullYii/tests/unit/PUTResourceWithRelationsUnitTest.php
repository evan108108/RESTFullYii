<?php
Yii::import('RestfullYii.tests.ERestTestRequestHelper');

/**
 * PUTResourceWithRelationsUnitTest
 *
 * Tests PUT resource request with relations
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/Tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class PUTResourceWithRelationsUnitTest extends ERestTestCase
{

	
	/**
	 * testPUTResourceWithBelongsToRequest
	 *
	 * tests that a PUT request with belongsTo
	 * correctly updates a resource
	 */
	public function testPUTResourceWithBelongsToRequest()
	{
		$request = new ERestTestRequestHelper();

		$data = '{
			"id": "2",
			"title": "title2",
			"author": {
					"email": "new_email@email3.com",
					"id": "3",
					"password": "password3",
					"username": "username3"
			},
			"content": "content2",
			"create_time": "2013-08-07 10:09:42",
		}';

		$request['config'] = [
			'url'			=> 'http://api/post/2',
			'type'		=> 'PUT',
			'data'		=> $data,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];
		
		$request->addEvent(ERestEvent::MODEL_WITH_RELATIONS, function() {
			return ['author'];
		});

		$expected_data = CJSON::decode($data);
		$expected_data['author_id'] = 3;

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Updated","data":{"totalCount":1,"post":' . CJSON::encode($expected_data) . '}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testPUTResourceWithNewBelongsToRequest
	 *
	 * tests that a PUT request with new belongs to
	 * correctly updates a resource
	 */
	public function testPUTResourceWithNewBelongsToRequest()
	{
		$request = new ERestTestRequestHelper();

		$data = '{
			"id": "2",
			"title": "title2",
			"author": {
					"email": "new_email@email77.com",
					"password": "password77",
					"username": "username77"
			},
			"content": "content2",
			"create_time": "2013-08-07 10:09:42",
		}';

		$request['config'] = [
			'url'			=> 'http://api/post/2',
			'type'		=> 'PUT',
			'data'		=> $data,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];
		
		$request->addEvent(ERestEvent::MODEL_WITH_RELATIONS, function() {
			return ['author'];
		});

		$expected_data = CJSON::decode($data);
		$expected_data['author_id'] = 7;
		$expected_data['author']['id'] = 7;

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Updated","data":{"totalCount":1,"post":' . CJSON::encode($expected_data) . '}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testPUTResourceWithHasManyRequest
	 *
	 * tests that a PUT request with new Has Many
	 * correctly updates a resource
	 */
	public function testPUTResourceWithHasManyRequest()
	{
		$request = new ERestTestRequestHelper();

		$data = '{
				"email": "email@email1.com",
				"id": "1",
				"password": "password1",
				"posts": [
						{
							"author_id": "1",
							"content": "NEW_content1",
							"create_time": "2013-08-07 10:09:41",
							"id": "1",
							"title": "title1"
						}, {
							"content": "content2",
							"create_time": "2013-08-07 10:09:42",
							"title": "title1"
						}
				],
				"username": "username1"
		}';

		$request['config'] = [
			'url'			=> 'http://api/user/1',
			'type'		=> 'PUT',
			'data'		=> $data,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];
		
		$request->addEvent(ERestEvent::MODEL_WITH_RELATIONS, function() {
			return ['posts'];
		});

		$expected_data = CJSON::decode($data);
		$expected_data['posts'][1]['id'] = 7;
		$expected_data['posts'][1]['author_id'] = 1;

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Updated","data":{"totalCount":1,"user":' . CJSON::encode($expected_data) . '}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	
		//Test that a PUT can remove a Many To Many
		unset($expected_data['posts'][1]);
		$request = new ERestTestRequestHelper();
		$request['config'] = [
			'url'			=> 'http://api/user/1',
			'type'		=> 'PUT',
			'data'		=> CJSON::encode($expected_data),
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];
		
		$request->addEvent(ERestEvent::MODEL_WITH_RELATIONS, function() {
			return ['posts'];
		});

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Updated","data":{"totalCount":1,"user":' . CJSON::encode($expected_data) . '}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testPUTResourceWithHasOneRequest
	 *
	 * tests that a PUT request with belongsTo
	 * correctly updates a resource
	 */
	public function testPUTResourceWithHasOneRequest()
	{
		$request = new ERestTestRequestHelper();

		$data = '{
					"email": "email@email2.com",
					"id": "2",
					"password": "password2",
					"profile": {
							"photo": "1",
							"website": "mysite7.com"
					},
					"username": "username2"
			}
    }';

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
			return ['profile'];
		});

		$expected_data = CJSON::decode($data);
		$expected_data['profile']['id'] = 7;
		$expected_data['profile']['user_id'] = 2;

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Updated","data":{"totalCount":1,"user":' . CJSON::encode($expected_data) . '}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testPUTResourceWithManyManyRequest
	 *
	 * tests that a PUT request with belongsTo
	 * correctly updates a resource
	 */
	public function testPUTResourceWithManyManyRequest()
	{
		$request = new ERestTestRequestHelper();

		$data = '{
			"id": "1",
			"name": "cat1",
			"posts": [
				{
					"author_id": "1",
					"content": "content1_UPDATED",
					"create_time": "2013-08-07 10:09:41",
					"id": "1",
					"title": "title1"
				}, {
					"author_id": "1",
					"content": "content7",
					"create_time": "2013-08-07 10:09:47",
					"title": "title1"
				}
			]
		}';

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
			return ['posts'];
		});

		$expected_data = CJSON::decode($data);
		$expected_data['posts'][1]['id'] = 7;

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Updated","data":{"totalCount":1,"category":' . CJSON::encode($expected_data) . '}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}
	
	/**
	 * testPUTResourceWithHasManyRequestRemoveAll
	 *
	 * tests that a PUT request with new Has Many And Empty Array
	 * correctly updates a resource
	 */
	public function testPUTResourceWithHasManyRequestRemoveAll()
	{
		$request = new ERestTestRequestHelper();

		$data = '{
				"email": "email@email1.com",
				"id": "1",
				"password": "password1",
				"posts": [],
				"username": "username1"
		}';

		$request['config'] = [
			'url'			=> 'http://api/user/1',
			'type'		=> 'PUT',
			'data'		=> $data,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];
		
		$request->addEvent(ERestEvent::MODEL_WITH_RELATIONS, function() {
			return ['posts'];
		});

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Updated","data":{"totalCount":1,"user":{"id":"1","username":"username1","password":"password1","email":"email@email1.com","posts":[]}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

}
