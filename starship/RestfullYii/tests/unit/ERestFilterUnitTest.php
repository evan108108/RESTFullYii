<?php
Yii::import('RestfullYii.filters.ERestFilter');
Yii::import('RestfullYii.actions.*');
Yii::import('RestfullYii.event.*');

/**
 * ERestFilter
 * 
 * Tests ERestFilter
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class ERestFilterUnitTest extends ERestTestCase
{
	protected $filter;
	protected $filterChain;

	/**
	 * setUp
	 *
	 * pre-test setup
	 */ 
	public function setUp()
	{
		parent::setUp();
		$this->loadFilter();
	}

	/**
	 * testPreFilterAttachesBehaviorAndInit
	 *
	 * tests ERestFilter->preFilter()
	 */ 
	public function testPreFilterAttachesBehaviorAndInit()
	{
		$this->asUser($this, function() {
			$this->captureOB($this, function() {
				$this->invokePrivateMethod($this->filter, 'preFilter', [$this->filterChain]);
			});
			$erb = $this->filterChain->controller->asa('ERestBehavior');

			//Test that ERestBehavior has been initialized
			$this->assertInstanceOf('ERestBehavior', $erb);
			$this->assertInstanceOf('Eventor', $this->getPrivateProperty($erb, 'event'));
			$this->assertInstanceOf('EHttpStatus', $this->getPrivateProperty($erb, 'http_status'));
			$this->assertInstanceOf('ERestSubresourceHelper', $this->getPrivateProperty($erb, 'subresource_helper'));
			$this->assertInstanceOf('ERestResourceHelper', $this->getPrivateProperty($erb, 'resource_helper'));
			$this->assertInstanceOf('ERestEventListenerRegistry', $this->getPrivateProperty($erb, 'listeners'));
		});
	}

	/**
	 * testPreFilterAuthHttpsOnly
	 *
	 * tests ERestFilter->preFilter()
	 */
	public function testPreFilterAuthHttpsOnly()
	{
		$this->loadFilter(
			[[
			'event' => ERestEvent::REQ_AUTH_HTTPS_ONLY,
			'handler' => function() {
					return true;
				}
			]]
		);
		$this->asUser($this, function() {
			$result = $this->captureOB($this, function() {
				$this->invokePrivateMethod($this->filter, 'preFilter', [$this->filterChain]);
			});
			$this->assertInstanceOf('Exception', $result);
			$this->assertExceptionHasMessage('You must use a secure connection', $result);

			$_SERVER['HTTPS'] = 'on';
			$result = $this->captureOB($this, function() {
				$this->invokePrivateMethod($this->filter, 'preFilter', [$this->filterChain]);
			});
			$this->assertTrue(is_array(CJSON::decode($result)));
		});
	}

	/**
	 * testPreFilterAuthAjaxUser
	 *
	 * tests ERestFilter->preFilter()
	 */
	public function testPreFilterAuthAjaxUser()
	{
		$this->loadFilter(
			[[
			'event' => ERestEvent::REQ_AUTH_AJAX_USER,
			'handler' => function() {
					return true;
				}
			]]
		);
		$result = $this->captureOB($this, function() {
			$this->invokePrivateMethod($this->filter, 'preFilter', [$this->filterChain]);
		});
		$this->assertTrue(is_array(CJSON::decode($result)));

		$this->loadFilter(
			[[
			'event' => ERestEvent::REQ_AUTH_AJAX_USER,
			'handler' => function() {
					return false;
				}
			]]
		);
		$result = $this->captureOB($this, function() {
			$this->invokePrivateMethod($this->filter, 'preFilter', [$this->filterChain]);
		});
		$this->assertInstanceOf('Exception', $result);
		$this->assertExceptionHasMessage('Unauthorized', $result);
	}

	/**
	 * testPreFilterAuthUser
	 *
	 * tests ERestFilter->preFilter()
	 */
	public function testPreFilterAuthUser()
	{
		$result = $this->captureOB($this, function() {
			$this->invokePrivateMethod($this->filter, 'preFilter', [$this->filterChain]);
		});
		$this->assertInstanceOf('Exception', $result);
		$this->assertExceptionHasMessage('Unauthorized', $result);

		$this->loadFilter(
			[
				[
					'event' => ERestEvent::REQ_AUTH_USER,
					'handler' => function($app_id, $username, $password) {
						return true;
					}
				], [
					'event' => ERestEvent::REQ_AUTH_TYPE,
					'handler' => function($application_id) {
						return ERestEventListenerRegistry::REQ_TYPE_USERPASS;
					}
				]
			]
		);
		$result = $this->captureOB($this, function() {
			$this->invokePrivateMethod($this->filter, 'preFilter', [$this->filterChain]);
		});
		$this->assertTrue(is_array(CJSON::decode($result)), $result);
	}

	/**
	 * testPreFilterAuthCors
	 *
	 * tests ERestFilter->preFilter()
	 */
	public function testPreFilterAuthCors()
	{
		$result = $this->captureOB($this, function() {
			$this->invokePrivateMethod($this->filter, 'preFilter', [$this->filterChain]);
		});
		$this->assertInstanceOf('Exception', $result);
		$this->assertExceptionHasMessage('Unauthorized', $result);

		$this->loadFilter(
			[
				[
					'event' => ERestEvent::REQ_AUTH_CORS,
					'handler' => function() {
						return true;
					}
				], [
					'event' => ERestEvent::REQ_AUTH_TYPE,
					'handler' => function($application_id) {
						return ERestEventListenerRegistry::REQ_TYPE_CORS;
					}
				]
			]
		);
		$result = $this->captureOB($this, function() {
			$this->invokePrivateMethod($this->filter, 'preFilter', [$this->filterChain]);
		});
		$this->assertTrue(is_array(CJSON::decode($result)), $result);
	}

	/**
	 * testCWebLogRouteDisabled
	 *
	 * tests that the disable CWebLogRoute logic is working
	 */ 
	public function testCWebLogRouteDisabled()
	{
		$this->captureOB($this, function() {
			$this->invokePrivateMethod($this->filter, 'preFilter', [$this->filterChain]);
		});
		$route_disabled = true;
		foreach (Yii::app()->log->routes as $route) {
			if ( $route instanceof CWebLogRoute ) {
				if($route->enabled) {
					$route_disabled = false;
				}
			}
		}
		$this->assertTrue($route_disabled, "CWebLogRoute should be disabled but was enabled");
	}

	/**
	 * testPreFilterUri
	 *
	 * tests ERestFilter->preFilter()
	 */
	public function testPreFilterURI()
	{
		$this->loadFilter(
			[
				[
					'event' => ERestEvent::REQ_AUTH_USER,
					'handler' => function($app_id, $username, $password) {
						return true;
					}
				], [
					'event' => ERestEvent::REQ_AUTH_URI,
					'handler' => function($uri, $verb) {
							return false;
					}
				], [
					'event' => ERestEvent::REQ_AUTH_TYPE,
					'handler' => function($application_id) {
						return ERestEventListenerRegistry::REQ_TYPE_USERPASS;
					}
				]
			]
		);

		$result = $this->captureOB($this, function() {
			$this->invokePrivateMethod($this->filter, 'preFilter', [$this->filterChain]);
		});
		$this->assertInstanceOf('Exception', $result);
    $this->assertExceptionHasMessage('Unauthorized', $result);


		$this->loadFilter(
			[
				[
					'event' => ERestEvent::REQ_AUTH_USER,
					'handler' => function($app_id, $username, $password) {
						return true;
					}
				], [
					'event' => ERestEvent::REQ_AUTH_URI,
					'handler' => function($uri, $verb) {
						return true;
					}
				], [
					'event' => ERestEvent::REQ_AUTH_TYPE,
					'handler' => function($application_id) {
						return ERestEventListenerRegistry::REQ_TYPE_USERPASS;
					}
				]
			]
		);

		$result = $this->captureOB($this, function() {
			$this->invokePrivateMethod($this->filter, 'preFilter', [$this->filterChain]);
		});
		$this->assertTrue(is_array(CJSON::decode($result)));
	}

	/**
	 * testPreFilterPostFilter
	 *
	 * Not sure how to test as AfterAction is fired on __destruct
	 *
	 *  tests ERestFilter->postFilter()
	 */
	public function testPreFilterPostFilter()	
	{
		$this->assertTrue(true);
	}

	/**
	 * testValidateHttpsOnly
	 *
	 * ERestFilter->validateHttpsOnly()
	 */
	public function testValidateHttpsOnly()
	{
		$this->assertTrue(!$this->filter->validateHttpsOnly());

		$_SERVER['HTTPS'] = 'on';
		$this->assertTrue($this->filter->validateHttpsOnly());
	}

	/**
	 * loadFilter
	 *
	 * set the filter var with an instance of ERestFilter
	 * set the filterChain var with an instance of CFilterChain
	 */ 
	public function loadFilter($injectEvents=[])
	{
		$controller = $this->getController()->Post;
		foreach($injectEvents as $event) {
			$controller->injectEvents($event['event'], $event['handler']);
		}
		$action = new EActionRestGET($controller, 'REST.GET');
		$this->filterChain = new CFilterChain($controller, $action);
		$this->filter = new ERestFilter();
	}

}
