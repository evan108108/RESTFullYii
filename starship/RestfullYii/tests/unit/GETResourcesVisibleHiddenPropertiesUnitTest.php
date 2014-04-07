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
        $expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":6,"user":[{"username":"username1","email":"email@email1.com"},{"username":"username2","email":"email@email2.com"},{"username":"username3","email":"email@email3.com"},{"username":"username4","email":"email@email4.com"},{"username":"username5","email":"email@email5.com"},{"username":"username6","email":"email@email6.com"}]}}';
        $this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}

	/**
	 * testGETResourcesHiddenProperties
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
        $expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":6,"user":[{"username":"username1","email":"email@email1.com"},{"username":"username2","email":"email@email2.com"},{"username":"username3","email":"email@email3.com"},{"username":"username4","email":"email@email4.com"},{"username":"username5","email":"email@email5.com"},{"username":"username6","email":"email@email6.com"}]}}';
        $this->assertJsonStringEqualsJsonString($request_response, $expected_response);
    }


    /**
	 * testGETResourcesHiddenGlobalRelatedProperties
	 *
     * tests that a GET request for a list of 'User' resources
     * With exluded visible fields only
	 * returns the correct response
	 */
	public function testGETResourcesHiddenGlobalRelatedProperties()
	{
        $request = new ERestTestRequestHelper();

        $request->addEvent('model.hidden.properties', function() {
            return ['*.photo'];
        });

        $request->addEvent('model.with.relations', function() {
            return ['profile'];
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
        
        $expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":6,"user":[{"id":"1","username":"username1","password":"password1","email":"email@email1.com","profile":{"id":"1","user_id":"1","website":"mysite1.com"}},{"id":"2","username":"username2","password":"password2","email":"email@email2.com","profile":{"id":"2","user_id":"2","website":"mysite2.com"}},{"id":"3","username":"username3","password":"password3","email":"email@email3.com","profile":{"id":"3","user_id":"3","website":"mysite3.com"}},{"id":"4","username":"username4","password":"password4","email":"email@email4.com","profile":{"id":"4","user_id":"4","website":"mysite4.com"}},{"id":"5","username":"username5","password":"password5","email":"email@email5.com","profile":{"id":"5","user_id":"5","website":"mysite5.com"}},{"id":"6","username":"username6","password":"password6","email":"email@email6.com","profile":{"id":"6","user_id":"6","website":"mysite6.com"}}]}}';
        $this->assertJsonStringEqualsJsonString($request_response, $expected_response);
    }


    /**
	 * testGETResourcesHiddenSpecificRelatedProperties
	 *
     * tests that a GET request for a list of 'User' resources
     * With exluded visible fields only
	 * returns the correct response
	 */
	public function testGETResourcesHiddenSpecificRelatedProperties()
	{
        $request = new ERestTestRequestHelper();

        $request->addEvent('model.hidden.properties', function() {
            return ['profile.photo', 'profile.website'];
        });

        $request->addEvent('model.with.relations', function() {
            return ['profile'];
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
        $expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":6,"user":[{"id":"1","username":"username1","password":"password1","email":"email@email1.com","profile":{"id":"1","user_id":"1"}},{"id":"2","username":"username2","password":"password2","email":"email@email2.com","profile":{"id":"2","user_id":"2"}},{"id":"3","username":"username3","password":"password3","email":"email@email3.com","profile":{"id":"3","user_id":"3"}},{"id":"4","username":"username4","password":"password4","email":"email@email4.com","profile":{"id":"4","user_id":"4"}},{"id":"5","username":"username5","password":"password5","email":"email@email5.com","profile":{"id":"5","user_id":"5"}},{"id":"6","username":"username6","password":"password6","email":"email@email6.com","profile":{"id":"6","user_id":"6"}}]}}';
        $this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}


    /**
	 * testGETResourcesVissibleGlobalRelatedProperties
	 *
     * tests that a GET request for a list of 'User' resources
     * With exluded visible fields only
	 * returns the correct response
	 */
	public function testGETResourcesVissibleGlobalRelatedProperties()
	{
		$request = new ERestTestRequestHelper();

		$request->addEvent('model.visible.properties', function() {
				return ['id', '*.website'];
		});

		$request->addEvent('model.with.relations', function() {
				return ['profile'];
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
		$expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":6,"user":[{"id":"1","profile":{"website":"mysite1.com"}},{"id":"2","profile":{"website":"mysite2.com"}},{"id":"3","profile":{"website":"mysite3.com"}},{"id":"4","profile":{"website":"mysite4.com"}},{"id":"5","profile":{"website":"mysite5.com"}},{"id":"6","profile":{"website":"mysite6.com"}}]}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}


    /**
	 * testGETResourcesVisibleSpecificRelatedProperties
	 *
     * tests that a GET request for a list of 'User' resources
     * With exluded visible fields only
	 * returns the correct response
	 */
	public function testGETResourcesVisibleSpecificRelatedProperties()
	{
		$request = new ERestTestRequestHelper();

		$request->addEvent('model.visible.properties', function() {
				return ['profile.photo', 'profile.website'];
		});

		$request->addEvent('model.with.relations', function() {
				return ['profile'];
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
		$expected_response = '{"success":true,"message":"Record(s) Found","data":{"totalCount":6,"user":[{"id":"1","username":"username1","password":"password1","email":"email@email1.com","profile":{"photo":"1","website":"mysite1.com"}},{"id":"2","username":"username2","password":"password2","email":"email@email2.com","profile":{"photo":"0","website":"mysite2.com"}},{"id":"3","username":"username3","password":"password3","email":"email@email3.com","profile":{"photo":"1","website":"mysite3.com"}},{"id":"4","username":"username4","password":"password4","email":"email@email4.com","profile":{"photo":"0","website":"mysite4.com"}},{"id":"5","username":"username5","password":"password5","email":"email@email5.com","profile":{"photo":"1","website":"mysite5.com"}},{"id":"6","username":"username6","password":"password6","email":"email@email6.com","profile":{"photo":"0","website":"mysite6.com"}}]}}';
		$this->assertJsonStringEqualsJsonString($request_response, $expected_response);
	}
}
