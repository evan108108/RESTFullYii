<?php
Yii::import('RestfullYii.tests.ERestTestRequestHelper');
/**
 * RequestCycleAuthUnitTest
 *
 * Tests request authorization
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/Tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class RequestCycleAuthUnitTest extends ERestTestCase
{
	/**
	 * testInvalidUsername
	 *
	 * tests that a request with an invalid username is denied
	 */
	public function testInvalidUsername()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/post/1',
			'type'		=> 'GET',
			'data'		=> NULL,
			'headers' => [
				'X_REST_USERNAME' => 'INVALID_USER_NAME',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$this->assertInstanceOf('Exception', $request_response);
		$this->assertExceptionHasMessage('Unauthorized', $request_response);
	}

	/**
	 * testInvalidPassword
	 *
	 * tests that a request with an invalid password is denied
	 */
	public function testInvalidPassword()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/post/1',
			'type'		=> 'GET',
			'data'		=> NULL,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'INVALID_PASSWORD',
			],
		];

		$request_response = $request->send();
		$this->assertInstanceOf('Exception', $request_response);
		$this->assertExceptionHasMessage('Unauthorized', $request_response);
	}

	/**
	 * testNoCredentials
	 *
	 * tests that a request with no credentials is denied
	 */
	public function testNoCredentials()
	{
		$request = new ERestTestRequestHelper();

		$request['config'] = [
			'url'			=> 'http://api/post/1',
			'type'		=> 'GET',
			'data'		=> NULL,
			'headers' => [],
		];

		$request_response = $request->send();
		$this->assertInstanceOf('Exception', $request_response);
		$this->assertExceptionHasMessage('Unauthorized', $request_response);
	}

	/**
	 * tests that when https_only is true and the request is over http
	 * that the request is denied
	 */ 
	public function testHttpsOnlyDeniedWhenHttp()
	{
		$request = new ERestTestRequestHelper();
		
		$request->addEvent(ERestEvent::POST_FILTER_REQ_AUTH_HTTPS_ONLY, function() {
			return true;
		});

		$request['config'] = [
			'url'			=> 'http://api/post?limit=1&sort=[{"property":"title", "direction":"desc"}]',
			'type'		=> 'GET',
			'data'		=> NULL,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];
		$request_response = $request->send();
		$this->assertInstanceOf('Exception', $request_response);
		$this->assertExceptionHasMessage('You must use a secure connection', $request_response);
	}

	/**
	 * tests that specific URI's may be accepted or denied 
	 */ 
	public function testRequestAuthUri()
	{
		$request = new ERestTestRequestHelper();
		
		$request->addEvent(ERestEvent::REQ_AUTH_URI, function($uri, $verb) {
			if($uri == '/api/post' && $verb == 'GET') {
					return false;
			}
			return true;
		});

		$request['config'] = [
			'url'			=> 'http://api/post',
			'type'		=> 'GET',
			'data'		=> NULL,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];
		$request_response = $request->send();
		$this->assertInstanceOf('Exception', $request_response);
		$this->assertExceptionHasMessage('Unauthorized', $request_response);

		$request['config'] = [
			'url'			=> 'http://api/post/1',
			'type'		=> 'GET',
			'data'		=> NULL,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];
		$request_response = $request->send();
		$this->assertJSONFormat($request_response);
	}

	/**
	 * Test that cors auth round trip works
	 */
	public function testRequestCORSAuth()
	{
		$request = new ERestTestRequestHelper();

		$request->addEvent(ERestEvent::REQ_CORS_ACCESS_CONTROL_ALLOW_ORIGIN, function() {
			return ['http://rest.test'];
		});

		$request['config'] = [
			'url'			=> 'http://api/post/1',
			'type'		=> 'OPTIONS',
			'data'		=> NULL,
			'headers' => [
				'ORIGIN' 			=> 'http://rest.test',
				'X_REST_CORS' => 'ALLOW',
			],
		];

		$request_response = $request->send();
		$expected_response = '{"Access-Control-Allow-Origin:":"http:\/\/rest.test","Access-Control-Max-Age":3628800,"Access-Control-Allow-Methods":"GET, POST","Access-Control-Allow-Headers: ":"X_REST_CORS"}';
		$this->assertJsonStringEqualsJsonString($expected_response, $request_response);

		$request['config'] = [
			'url'			=> 'http://api/post/1',
			'type'		=> 'GET',
			'data'		=> NULL,
			'headers' => [
				'ORIGIN' 			=> 'http://rest.test',
				'X_REST_CORS' => 'ALLOW',
			],
		];

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Found","data":{"totalCount":1,"post":{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1","categories":[{"id":"1","name":"cat1"},{"id":"2","name":"cat2"}],"author":{"id":"1","username":"username1","password":"password1","email":"email@email1.com"}}}}';
		$this->assertJsonStringEqualsJsonString($expected_response, $request_response);

	}



}
