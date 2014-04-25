<?php
/**
 * BinaryOutputUnitTest
 *
 * Tests GET request has correct output for binary field types
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/Tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class BinaryOutputUnitTest extends ERestTestCase
{
	/**
	 * testGetWithBinaryData
	 *
	 * tests get request that contains binary data
	 */
	public function testGetWithBinaryData()
	{
		$request = new ERestTestRequestHelper();
		$request['config'] = [
			'url'			=> 'http://api/binary/1',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

		$request_response = $request->send();
		$expected_response = '{"success":true,"message":"Record Found","data":{"totalCount":1,"binary":{"id":"1","name":"de46c83e5a50ced70e6a525a7be6d709"}}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}
}


