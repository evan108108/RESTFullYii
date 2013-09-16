<?php
/**
 * GETResourcesUnitTest
 *
 * Tests GET request for a list of resources
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/Tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class GETResourcesVisibleHiddenPropertiesUnitTest extends ERestTestCase
{
	/**
	 * testGETResourcesVisibleProperties
	 *
     * tests that a GET request for a list of 'User' resources
     * With limited selected visible fields only
	 * returns the correct response
	 */
	public function testGETResourcesVisibleProperties()
	{
        $request = new ERestTestRequestHelper();

        $request->addEvent('model.visible.properties', function() {
            return ['username', 'email'];
        });

        $request->addEvent('model.with.relations', function() {
            return [];
        });

		$request['config'] = [
			'url'			=> 'http://api/user',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

        $request_response = $request->send();
        $expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":"6","user":[{"username":"username1","email":"email@email1.com"},{"username":"username2","email":"email@email2.com"},{"username":"username3","email":"email@email3.com"},{"username":"username4","email":"email@email4.com"},{"username":"username5","email":"email@email5.com"},{"username":"username6","email":"email@email6.com"}]}}';
        $this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETResourcesVisibleProperties
	 *
     * tests that a GET request for a list of 'User' resources
     * With exluded visible fields only
	 * returns the correct response
	 */
	public function testGETResourcesHiddenProperties()
	{
        $request = new ERestTestRequestHelper();

        $request->addEvent('model.hidden.properties', function() {
            return ['password', 'id'];
        });

        $request->addEvent('model.with.relations', function() {
            return [];
        });

		$request['config'] = [
			'url'			=> 'http://api/user',
			'type'		=> 'GET',
			'data'		=> null,
			'headers' => [
				'X_REST_USERNAME' => 'admin@restuser',
				'X_REST_PASSWORD' => 'admin@Access',
			],
		];

        $request_response = $request->send();
        //echo $request_response; exit();
        $expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":"6","user":[{"username":"username1","email":"email@email1.com"},{"username":"username2","email":"email@email2.com"},{"username":"username3","email":"email@email3.com"},{"username":"username4","email":"email@email4.com"},{"username":"username5","email":"email@email5.com"},{"username":"username6","email":"email@email6.com"}]}}';
        $this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

}
