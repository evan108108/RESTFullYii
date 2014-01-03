<?php
Yii::import('RestfullYii.tests.ERestTestRequestHelper');

/**
 * RouteUnitTest
 *
 * Tests that the config/routes.php routes request as expected
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/Tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class RouteUnitTest extends ERestTestCase
{
	/**
	 * testRouteGETResource
	 *
	 * tests that a GET for a single resource
	 * is correctly routed
	 */
	public function testRouteGETResource()
	{
		$this->assertRouteResolvesToControllerAndAction('GET::api/category/1', 'Category', 'REST.GET');
		$this->assertRouteResolvesToControllerAndAction('GET::api/post/2', 'Post', 'REST.GET');
		$this->assertRouteResolvesToControllerAndAction('GET::api/user/3', 'User', 'REST.GET');
		$this->assertRouteResolvesToControllerAndAction('GET::api/profile/4', 'Profile', 'REST.GET');
	}

	/**
	 * testRouteGETResources
	 *
	 * tests that a GET for resources
	 * are correctly routed
	 */
	public function testRouteGetResources()
	{
		$this->assertRouteResolvesToControllerAndAction('GET::api/category', 'Category', 'REST.GET');
		$this->assertRouteResolvesToControllerAndAction('GET::api/post', 'Post', 'REST.GET');
		$this->assertRouteResolvesToControllerAndAction('GET::api/user', 'User', 'REST.GET');
		$this->assertRouteResolvesToControllerAndAction('GET::api/profile', 'Profile', 'REST.GET');
	}

	/**
	 * testRouteGETSubresource
	 *
	 * tests that a GET for a single subresource
	 * is correctly routed
	 */
	public function testRouteGETSubresource()
	{
		$this->assertRouteResolvesToControllerAndAction('GET::api/category/1/posts/1', 'Category', 'REST.GET');
		$this->assertRouteResolvesToControllerAndAction('GET::api/post/1/categories/1', 'Post', 'REST.GET');
	}

	/**
	 * testRouteGETSubresources
	 *
	 * tests that a GET for subresources
	 * is correctly routed
	 */
	public function testRouteGETSubresources()
	{
		$this->assertRouteResolvesToControllerAndAction('GET::api/category/1/posts', 'Category', 'REST.GET');
		$this->assertRouteResolvesToControllerAndAction('GET::api/post/1/categories', 'Post', 'REST.GET');
	}

	/**
	 * testRouteGETCustomNoParams
	 *
	 * tests that a custom GET with no params
	 * is routed correctly
	 */
	public function testRouteGETCustomNoParams()
	{
		$this->assertRouteResolvesToControllerAndAction('GET::api/category/testing1', 'Category', 'REST.GET');
		$this->assertRouteResolvesToControllerAndAction('GET::api/post/testing2', 'Post', 'REST.GET');
		$this->assertRouteResolvesToControllerAndAction('GET::api/user/testing3', 'User', 'REST.GET');
		$this->assertRouteResolvesToControllerAndAction('GET::api/profile/testing4', 'Profile', 'REST.GET');
	}

	/**
	 * testRouteGETCustomOneParam
	 *
	 * tests that a custom GET with one param
	 * is routed correctly
	 */
	public function testRouteGETCustomOneParam()
	{
		$this->assertRouteResolvesToControllerAndAction('GET::api/category/testing1/1', 'Category', 'REST.GET');
		$this->assertRouteResolvesToControllerAndAction('GET::api/post/testing2/2', 'Post', 'REST.GET');
		$this->assertRouteResolvesToControllerAndAction('GET::api/user/testing3/3', 'User', 'REST.GET');
		$this->assertRouteResolvesToControllerAndAction('GET::api/profile/testing4/4', 'Profile', 'REST.GET');
	}

	/**
	 * testRouteGETCustomTwoParams
	 *
	 * tests that a custom GET with Two params
	 * is routed correctly
	 */
	public function testRouteGETCustomTwoParams()
	{
		$this->assertRouteResolvesToControllerAndAction('GET::api/category/testing1/1/a', 'Category', 'REST.GET');
		$this->assertRouteResolvesToControllerAndAction('GET::api/post/testing2/2/b', 'Post', 'REST.GET');
		$this->assertRouteResolvesToControllerAndAction('GET::api/user/testing3/3/c', 'User', 'REST.GET');
		$this->assertRouteResolvesToControllerAndAction('GET::api/profile/testing4/4/d', 'Profile', 'REST.GET');
	}

/**
	 * testRoutePUTResource
	 *
	 * tests that a PUT for a single resource
	 * is correctly routed
	 */
	public function testRoutePUTResource()
	{
		$this->assertRouteResolvesToControllerAndAction('PUT::api/category/1', 'Category', 'REST.PUT');
		$this->assertRouteResolvesToControllerAndAction('PUT::api/post/2', 'Post', 'REST.PUT');
		$this->assertRouteResolvesToControllerAndAction('PUT::api/user/3', 'User', 'REST.PUT');
		$this->assertRouteResolvesToControllerAndAction('PUT::api/profile/4', 'Profile', 'REST.PUT');
	}


	/**
	 * testRoutePUTSubresource
	 *
	 * tests that a PUT for a single subresource
	 * is correctly routed
	 */
	public function testRoutePUTSubresource()
	{
		$this->assertRouteResolvesToControllerAndAction('PUT::api/category/1/posts/1', 'Category', 'REST.PUT');
		$this->assertRouteResolvesToControllerAndAction('PUT::api/post/1/categories/1', 'Post', 'REST.PUT');
	}

	/**
	 * testRoutePUTCustomNoParams
	 *
	 * tests that a custom PUT with no params
	 * is routed correctly
	 */
	public function testRoutePUTCustomNoParams()
	{
		$this->assertRouteResolvesToControllerAndAction('PUT::api/category/testing1', 'Category', 'REST.PUT');
		$this->assertRouteResolvesToControllerAndAction('PUT::api/post/testing2', 'Post', 'REST.PUT');
		$this->assertRouteResolvesToControllerAndAction('PUT::api/user/testing3', 'User', 'REST.PUT');
		$this->assertRouteResolvesToControllerAndAction('PUT::api/profile/testing4', 'Profile', 'REST.PUT');
	}

	/**
	 * testRoutePUTCustomOneParam
	 *
	 * tests that a custom PUT with one param
	 * is routed correctly
	 */
	public function testRoutePUTCustomOneParam()
	{
		$this->assertRouteResolvesToControllerAndAction('PUT::api/category/testing1/1', 'Category', 'REST.PUT');
		$this->assertRouteResolvesToControllerAndAction('PUT::api/post/testing2/2', 'Post', 'REST.PUT');
		$this->assertRouteResolvesToControllerAndAction('PUT::api/user/testing3/3', 'User', 'REST.PUT');
		$this->assertRouteResolvesToControllerAndAction('PUT::api/profile/testing4/4', 'Profile', 'REST.PUT');
	}

	/**
	 * testRoutePUTCustomTwoParams
	 *
	 * tests that a custom PUT with Two params
	 * is routed correctly
	 */
	public function testRoutePUTCustomTwoParams()
	{
		$this->assertRouteResolvesToControllerAndAction('PUT::api/category/testing1/1/a', 'Category', 'REST.PUT');
		$this->assertRouteResolvesToControllerAndAction('PUT::api/post/testing2/2/b', 'Post', 'REST.PUT');
		$this->assertRouteResolvesToControllerAndAction('PUT::api/user/testing3/3/c', 'User', 'REST.PUT');
		$this->assertRouteResolvesToControllerAndAction('PUT::api/profile/testing4/4/d', 'Profile', 'REST.PUT');
	}
	
	/**
	 * testRoutePOSTResource
	 *
	 * tests that a POST for a single resource
	 * is correctly routed
	 */
	public function testRoutePOSTResource()
	{
		$this->assertRouteResolvesToControllerAndAction('POST::api/category', 'Category', 'REST.POST');
		$this->assertRouteResolvesToControllerAndAction('POST::api/post', 'Post', 'REST.POST');
		$this->assertRouteResolvesToControllerAndAction('POST::api/user', 'User', 'REST.POST');
		$this->assertRouteResolvesToControllerAndAction('POST::api/profile', 'Profile', 'REST.POST');
	}


	/**
	 * testRoutePOSTCustomNoParams
	 *
	 * tests that a custom POST with no params
	 * is routed correctly
	 */
	public function testRoutePOSTCustomNoParams()
	{
		$this->assertRouteResolvesToControllerAndAction('POST::api/category/testing1', 'Category', 'REST.POST');
		$this->assertRouteResolvesToControllerAndAction('POST::api/post/testing2', 'Post', 'REST.POST');
		$this->assertRouteResolvesToControllerAndAction('POST::api/user/testing3', 'User', 'REST.POST');
		$this->assertRouteResolvesToControllerAndAction('POST::api/profile/testing4', 'Profile', 'REST.POST');
	}

	/**
	 * testRoutePOSTCustomOneParam
	 *
	 * tests that a custom POST with one param
	 * is routed correctly
	 */
	public function testRoutePOSTCustomOneParam()
	{
		$this->assertRouteResolvesToControllerAndAction('POST::api/category/testing1/1', 'Category', 'REST.POST');
		$this->assertRouteResolvesToControllerAndAction('POST::api/post/testing2/2', 'Post', 'REST.POST');
		$this->assertRouteResolvesToControllerAndAction('POST::api/user/testing3/3', 'User', 'REST.POST');
		$this->assertRouteResolvesToControllerAndAction('POST::api/profile/testing4/4', 'Profile', 'REST.POST');
	}

	/**
	 * testRoutePOSTCustomTwoParams
	 *
	 * tests that a custom POST with Two params
	 * is routed correctly
	 */
	public function testRoutePOSTCustomTwoParams()
	{
		$this->assertRouteResolvesToControllerAndAction('POST::api/category/testing1/1/a', 'Category', 'REST.POST');
		$this->assertRouteResolvesToControllerAndAction('POST::api/post/testing2/2/b', 'Post', 'REST.POST');
		$this->assertRouteResolvesToControllerAndAction('POST::api/user/testing3/3/c', 'User', 'REST.POST');
		$this->assertRouteResolvesToControllerAndAction('POST::api/profile/testing4/4/d', 'Profile', 'REST.POST');
	}

		/**
	 * testRouteDELETEResource
	 *
	 * tests that a DELETE for a single resource
	 * is correctly routed
	 */
	public function testRouteDELETEResource()
	{
		$this->assertRouteResolvesToControllerAndAction('DELETE::api/category/1', 'Category', 'REST.DELETE');
		$this->assertRouteResolvesToControllerAndAction('DELETE::api/post/2', 'Post', 'REST.DELETE');
		$this->assertRouteResolvesToControllerAndAction('DELETE::api/user/3', 'User', 'REST.DELETE');
		$this->assertRouteResolvesToControllerAndAction('DELETE::api/profile/4', 'Profile', 'REST.DELETE');
	}


	/**
	 * testRouteDELETESubresource
	 *
	 * tests that a DELETE for a single subresource
	 * is correctly routed
	 */
	public function testRouteDELETESubresource()
	{
		$this->assertRouteResolvesToControllerAndAction('DELETE::api/category/1/posts/1', 'Category', 'REST.DELETE');
		$this->assertRouteResolvesToControllerAndAction('DELETE::api/post/1/categories/1', 'Post', 'REST.DELETE');
	}


	/**
	 * testRouteDELETECustomNoParams
	 *
	 * tests that a custom DELETE with no params
	 * is routed correctly
	 */
	public function testRouteDELETECustomNoParams()
	{
		$this->assertRouteResolvesToControllerAndAction('DELETE::api/category/testing1', 'Category', 'REST.DELETE');
		$this->assertRouteResolvesToControllerAndAction('DELETE::api/post/testing2', 'Post', 'REST.DELETE');
		$this->assertRouteResolvesToControllerAndAction('DELETE::api/user/testing3', 'User', 'REST.DELETE');
		$this->assertRouteResolvesToControllerAndAction('DELETE::api/profile/testing4', 'Profile', 'REST.DELETE');
	}

	/**
	 * testRouteDELETECustomOneParam
	 *
	 * tests that a custom DELETE with one param
	 * is routed correctly
	 */
	public function testRouteDELETECustomOneParam()
	{
		$this->assertRouteResolvesToControllerAndAction('DELETE::api/category/testing1/1', 'Category', 'REST.DELETE');
		$this->assertRouteResolvesToControllerAndAction('DELETE::api/post/testing2/2', 'Post', 'REST.DELETE');
		$this->assertRouteResolvesToControllerAndAction('DELETE::api/user/testing3/3', 'User', 'REST.DELETE');
		$this->assertRouteResolvesToControllerAndAction('DELETE::api/profile/testing4/4', 'Profile', 'REST.DELETE');
	}

	/**
	 * testRouteDELETECustomTwoParams
	 *
	 * tests that a custom DELETE with Two params
	 * is routed correctly
	 */
	public function testRouteDELETECustomTwoParams()
	{
		$this->assertRouteResolvesToControllerAndAction('DELETE::api/category/testing1/1/a', 'Category', 'REST.DELETE');
		$this->assertRouteResolvesToControllerAndAction('DELETE::api/post/testing2/2/b', 'Post', 'REST.DELETE');
		$this->assertRouteResolvesToControllerAndAction('DELETE::api/user/testing3/3/c', 'User', 'REST.DELETE');
		$this->assertRouteResolvesToControllerAndAction('DELETE::api/profile/testing4/4/d', 'Profile', 'REST.DELETE');
	}

	/**
	 * testRouteOPTIONSResource
	 *
	 * tests that a OPTIONS for a single resource
	 * is correctly routed
	 */
	public function testRouteOptionsResource()
	{
		$this->assertRouteResolvesToControllerAndAction('OPTIONS::api/category', 'Category', 'REST.OPTIONS');
		$this->assertRouteResolvesToControllerAndAction('OPTIONS::api/post', 'Post', 'REST.OPTIONS');
		$this->assertRouteResolvesToControllerAndAction('OPTIONS::api/user', 'User', 'REST.OPTIONS');
		$this->assertRouteResolvesToControllerAndAction('OPTIONS::api/profile', 'Profile', 'REST.OPTIONS');

		$this->assertRouteResolvesToControllerAndAction('OPTIONS::api/category/1', 'Category', 'REST.OPTIONS');
		$this->assertRouteResolvesToControllerAndAction('OPTIONS::api/post/2', 'Post', 'REST.OPTIONS');
		$this->assertRouteResolvesToControllerAndAction('OPTIONS::api/user/3', 'User', 'REST.OPTIONS');
		$this->assertRouteResolvesToControllerAndAction('OPTIONS::api/profile/4', 'Profile', 'REST.OPTIONS');

		$this->assertRouteResolvesToControllerAndAction('OPTIONS::api/category/1/1', 'Category', 'REST.OPTIONS');
		$this->assertRouteResolvesToControllerAndAction('OPTIONS::api/post/2/2', 'Post', 'REST.OPTIONS');
		$this->assertRouteResolvesToControllerAndAction('OPTIONS::api/user/3/3', 'User', 'REST.OPTIONS');
		$this->assertRouteResolvesToControllerAndAction('OPTIONS::api/profile/4/4', 'Profile', 'REST.OPTIONS');
	}


	public function assertRouteResolvesToControllerAndAction($uri, $controller_name, $action_name)
	{
    $_SERVER['SCRIPT_FILENAME'] = '/bootstrap.php';
		$_SERVER['SCRIPT_NAME'] =  '/bootstrap.php';
		$_SERVER['DOCUMENT_ROOT'] = '/';

		$uri = explode('::', $uri);

    $_SERVER['REQUEST_URI'] = $uri[1];
		$_SERVER['REQUEST_METHOD'] = $uri[0];

		$route = Yii::app()->getUrlManager()->parseUrl(new CHttpRequest());
	
    list($controller, $action) = Yii::app()->createController($route);
 
    $this->assertInstanceOf("{$controller_name}Controller", $controller);
		$this->assertEquals($action_name, $action);
	}


}
