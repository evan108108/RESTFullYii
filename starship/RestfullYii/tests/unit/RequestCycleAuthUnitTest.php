<?php
Yii::import('ext.starship.RestfullYii.tests.ERestTestRequestHelper');
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
}
