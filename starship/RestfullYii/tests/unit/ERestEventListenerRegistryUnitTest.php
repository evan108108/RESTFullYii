<?php
Yii::import('RestfullYii.events.ERestEventListenerRegistry');
Yii::import('RestfullYii.events.Eventor.*');
Yii::import('RestfullYii.events.*');
Yii::import('RestfullYii.behaviors.ERestBehavior');
Yii::import('RestfullYii.ARBehaviors.*');

/**
 * ERestEventListenerRegistryUnitTest
 *
 * Tests ERestEventListenerRegistry
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class ERestEventListenerRegistryUnitTest extends ERestTestCase
{
	protected $event;
	/**
	 * setup
	 */ 
	public function setUp()
	{
		parent::setUp();
		Yii::app()->params['RestfullYii'] = [];
		$controller = $this->getController()->Post;
		$controller->attachBehavior('ERestBehavior', new ERestBehavior());
		$erb = $controller->asa('ERestBehavior');
		$erb->ERestInit();
		$this->event = $this->getPrivateProperty($erb, 'event');
	}

	/**
	 * __construct
	 * 
	 * tests ERestEventListenerRegistry->__construct()
	 */
	public function testConstruct()
	{
		$eventor = new Eventor($this, true);
		$erelr = new ERestEventListenerRegistry([$eventor, 'on']);
		$this->assertInstanceOf('ERestEventListenerRegistry', $erelr);
		$this->assertArraysEqual([$eventor, 'on'], $this->getPrivateProperty($erelr, 'onRest'));
	}

	/**
	 * run
	 *
	 * tests ERestEventListenerRegistry->run()
	 */
	public function testRun()
	{
		$eventor = new Eventor($this, true);
		$erelr = new ERestEventListenerRegistry([$eventor, 'on']);
		$erelr->run();
		
		$this->assertTrue($eventor->eventExists(ERestEvent::CONFIG_DEV_FLAG), "Event " . ERestEvent::CONFIG_DEV_FLAG . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::CONFIG_APPLICATION_ID), "Event " . ERestEvent::CONFIG_APPLICATION_ID . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::REQ_EVENT_LOGGER), "Event " . ERestEvent::REQ_EVENT_LOGGER . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::REQ_DISABLE_CWEBLOGROUTE), "Event " . ERestEvent::REQ_DISABLE_CWEBLOGROUTE. " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::REQ_EXCEPTION), "Event " . ERestEvent::REQ_EXCEPTION . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::REQ_AUTH_TYPE), "Event " . ERestEvent::REQ_AUTH_TYPE . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::REQ_AUTH_CORS), "Event " . ERestEvent::REQ_AUTH_CORS . " is not registered");		
		$this->assertTrue($eventor->eventExists(ERestEvent::REQ_AUTH_CORS), "Event " . ERestEvent::REQ_AUTH_CORS . " is not registered");		
		$this->assertTrue($eventor->eventExists(ERestEvent::REQ_IS_SUBRESOURCE), "Event " . ERestEvent::REQ_IS_SUBRESOURCE . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::REQ_AUTH_URI), "Event " . ERestEvent::REQ_AUTH_URI . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::REQ_AUTH_HTTPS_ONLY), "Event " . ERestEvent::REQ_AUTH_HTTPS_ONLY . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::REQ_AUTH_USERNAME), "Event " . ERestEvent::REQ_AUTH_USERNAME . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::REQ_AUTH_PASSWORD), "Event " . ERestEvent::REQ_AUTH_PASSWORD . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::REQ_AUTH_USER), "Event " . ERestEvent::REQ_AUTH_USER . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::REQ_AFTER_ACTION), "Event " . ERestEvent::REQ_AFTER_ACTION . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::REQ_GET_RESOURCE_RENDER), "Event " . ERestEvent::REQ_GET_RESOURCE_RENDER . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::REQ_GET_RESOURCES_RENDER), "Event " . ERestEvent::REQ_GET_RESOURCES_RENDER . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::REQ_GET_SUBRESOURCES_RENDER), "Event " . ERestEvent::REQ_GET_SUBRESOURCES_RENDER . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::REQ_GET_SUBRESOURCE_RENDER), "Event " . ERestEvent::REQ_GET_SUBRESOURCE_RENDER . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::REQ_POST_RESOURCE_RENDER), "Event " . ERestEvent::REQ_POST_RESOURCE_RENDER . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::REQ_PUT_RESOURCE_RENDER), "Event " . ERestEvent::REQ_PUT_RESOURCE_RENDER . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::REQ_PUT_SUBRESOURCE_RENDER), "Event " . ERestEvent::REQ_PUT_SUBRESOURCE_RENDER . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::REQ_DELETE_RESOURCE_RENDER), "Event " . ERestEvent::REQ_DELETE_RESOURCE_RENDER . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::REQ_DELETE_SUBRESOURCE_RENDER), "Event " . ERestEvent::REQ_DELETE_SUBRESOURCE_RENDER . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::MODEL_SUBRESOURCE_COUNT), "Event " . ERestEvent::MODEL_SUBRESOURCE_COUNT . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::MODEL_SUBRESOURCE_FIND), "Event " . ERestEvent::MODEL_SUBRESOURCE_FIND . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::MODEL_SUBRESOURCES_FIND_ALL), "Event " . ERestEvent::MODEL_SUBRESOURCES_FIND_ALL . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::REQ_PARAM_IS_PK), "Event " . ERestEvent::REQ_PARAM_IS_PK . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::MODEL_INSTANCE), "Event " . ERestEvent::MODEL_INSTANCE . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::MODEL_ATTACH_BEHAVIORS), "Event " . ERestEvent::MODEL_ATTACH_BEHAVIORS . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::MODEL_LIMIT), "Event " . ERestEvent::MODEL_LIMIT . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::MODEL_OFFSET), "Event " . ERestEvent::MODEL_OFFSET . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::MODEL_SCENARIO), "Event " . ERestEvent::MODEL_SCENARIO . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::MODEL_FILTER), "Event " . ERestEvent::MODEL_FILTER . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::MODEL_SORT), "Event " . ERestEvent::MODEL_SORT . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::MODEL_WITH_RELATIONS), "Event " . ERestEvent::MODEL_WITH_RELATIONS . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::MODEL_LAZY_LOAD_RELATIONS), "Event " . ERestEvent::MODEL_LAZY_LOAD_RELATIONS . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::MODEL_FIND), "Event " . ERestEvent::MODEL_FIND . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::MODEL_FIND_ALL), "Event " . ERestEvent::MODEL_FIND_ALL . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::MODEL_COUNT), "Event " . ERestEvent::MODEL_COUNT . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::REQ_DATA_READ), "Event " . ERestEvent::REQ_DATA_READ . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::REQ_RENDER_JSON), "Event " . ERestEvent::REQ_RENDER_JSON . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::MODEL_APPLY_POST_DATA), "Event " . ERestEvent::MODEL_APPLY_POST_DATA . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::MODEL_APPLY_PUT_DATA), "Event " . ERestEvent::MODEL_APPLY_PUT_DATA . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::MODEL_RESTRICTED_PROPERTIES), "Event " . ERestEvent::MODEL_RESTRICTED_PROPERTIES . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::MODEL_SAVE), "Event " . ERestEvent::MODEL_SAVE . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::MODEL_SUBRESOURCE_SAVE), "Event " . ERestEvent::MODEL_SUBRESOURCE_SAVE . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::MODEL_DELETE), "Event " . ERestEvent::MODEL_DELETE . " is not registered");
		$this->assertTrue($eventor->eventExists(ERestEvent::MODEL_SUBRESOURCE_DELETE), "Event " . ERestEvent::MODEL_SUBRESOURCE_DELETE . " is not registered");
	}

	/**
	 * testEventConfigDevFlag
	 *
	 * tests event config.dev.flag event
	 */
	public function testEventConfigDevFlag()
	{
		$this->assertTrue($this->event->emit(ERestEvent::CONFIG_DEV_FLAG), "config.dev.flag should return true by default");
	}

	/**
	 * testEventConfigApplicationID
	 *
	 * tests event config.application.id
	 */
	public function testEventConfigApplicationID()
	{
		$this->assertEquals('REST', $this->event->emit(ERestEvent::CONFIG_APPLICATION_ID), "config.application.id should return 'REST' by default");
	}

	/**
	 * testReqEventLogger
	 *
	 * tests event req.event.logger
	 */
	public function testReqEventLogger()
	{
		$this->assertArraysEqual([
			ERestEvent::CONFIG_APPLICATION_ID, 'Application',[]],
			$this->event->emit(ERestEvent::REQ_EVENT_LOGGER, [ERestEvent::CONFIG_APPLICATION_ID, 'Application'])
		);
	}

	/**
	 * testReqDisableCWebLogRoute
	 *
	 * tests event req.disable.cweblogroute
	 */
	public function testReqDisableCWebLobRoute()
	{
		$this->assertTrue($this->event->emit(ERestEvent::REQ_DISABLE_CWEBLOGROUTE), "Disable CWebLogRoute should be true by default but we false");
	}

	/**
	 * testEventReqException
	 *
	 * tests event req.exception
	 */ 
	public function testEventReqException()
	{
		$exception_out = $this->event->emit(ERestEvent::REQ_EXCEPTION, [500, 'Internal Server Error']);
	
		$this->assertJsonStringEqualsJsonString($exception_out, '{"success":false,"message":"Internal Server Error","data":{"errorCode":500,"message":"Internal Server Error"}}');
		
		$exception_out_2 = $this->event->emit(ERestEvent::REQ_EXCEPTION, [200]);
		
		$this->assertJsonStringEqualsJsonString($exception_out_2, '{"success":false,"message":"OK","data":{"errorCode":200,"message":"OK"}}');
	}

	/**
	 * testHasConstREQTYPECORS
	 *
	 * test that the const ERestEventListenerRegistry::REQ_TYPE_CORS exists &
	 * has the correct value
	 */
	public function testHasConstREQTYPECORS()
	{
		$this->assertTrue(defined('ERestEventListenerRegistry::REQ_TYPE_CORS'));
		$this->assertEquals(1, ERestEventListenerRegistry::REQ_TYPE_CORS);
	}

	/**
	 * testHasConstREQTYPEUSERPASS
	 *
	 * test that the const ERestEventListenerRegistry::REQ_TYPE_USERPASS exists &
	 * has the correct value
	 */
	public function testHasConstREQTYPEUSERPASS()
	{
		$this->assertTrue(defined('ERestEventListenerRegistry::REQ_TYPE_USERPASS'));
		$this->assertEquals(2, ERestEventListenerRegistry::REQ_TYPE_USERPASS);
	}

	/**
	 * testHasConstREQTYPEAJAX
	 *
	 * test that the const ERestEventListenerRegistry::REQ_TYPE_AJAX exists &
	 * has the correct value
	 */
	public function testHasConstREQTYPEAJAX()
	{
		$this->assertTrue(defined('ERestEventListenerRegistry::REQ_TYPE_AJAX'));
		$this->assertEquals(3, ERestEventListenerRegistry::REQ_TYPE_AJAX);
	}

	/**
	 * testEventReqAuthType
	 * 
	 * tests that the correct request authentication types are returned
	 */
	public function testEventReqAuthType()
	{
		$this->assertEquals(ERestEventListenerRegistry::REQ_TYPE_AJAX, $this->event->emit(ERestEvent::REQ_AUTH_TYPE, 'REST'));

		$_SERVER['HTTP_X_REST_USERNAME'] = 'test_user_name';
		$_SERVER['HTTP_X_REST_PASSWORD'] = 'test_user_password';
		$this->assertEquals(ERestEventListenerRegistry::REQ_TYPE_USERPASS, $this->event->emit(ERestEvent::REQ_AUTH_TYPE, 'REST'));

		$_SERVER['HTTP_X_REST_CORS'] = 1;
		$this->assertEquals(ERestEventListenerRegistry::REQ_TYPE_CORS, $this->event->emit(ERestEvent::REQ_AUTH_TYPE, 'REST'));

		unset($_SERVER['HTTP_X_REST_CORS']);
		$_SERVER['REQUEST_METHOD'] = 'OPTIONS';
		$this->assertEquals(ERestEventListenerRegistry::REQ_TYPE_CORS, $this->event->emit(ERestEvent::REQ_AUTH_TYPE, 'REST'));

		unset($_SERVER['HTTP_X_REST_USERNAME'], $_SERVER['HTTP_X_REST_PASSWORD'], $_SERVER['HTTP_X_REST_CORS']);
	}

	/**
	 * testEventREQ_IS_SUBRESOURCE
	 *
	 * test that req.is.subresource event returns the correct response
	 */
	public function testEventREQ_IS_SUBRESOURCE()
	{
		$this->assertEquals(false, $this->event->emit(ERestEvent::REQ_IS_SUBRESOURCE, [new Post(), 'Author', 'GET']));
		$this->assertEquals(true, $this->event->emit(ERestEvent::REQ_IS_SUBRESOURCE, [new Post(), 'categories', 'GET']));
	}


	/**
	 * testEventReqCorsAccessControlAllowOrigin
	 *
	 * test that allow origin returns an empty array
	 */
	public function testEventReqCorsAccessControlAllowOrigin()
	{
		$this->assertEquals([], $this->event->emit(ERestEvent::REQ_CORS_ACCESS_CONTROL_ALLOW_ORIGIN));
	}

	/**
	 * testEventReqCorsAccessControlAllowMethods
	 *
	 * test that allow methods returns correct array
	 */
	public function testEventReqCorsAccessControlAllowMethods()
	{
		$this->assertEquals(['GET', 'POST'], $this->event->emit(ERestEvent::REQ_CORS_ACCESS_CONTROL_ALLOW_METHODS));
	}

	/**
	 * testEventReqCorsAccessControlAllowHeaders
	 *
	 * test that allow headers returns correct array
	 */
	public function testEventReqCorsAccessControlAllowHeaders()
	{
		$this->assertEquals(['X_REST_CORS'], $this->event->emit(ERestEvent::REQ_CORS_ACCESS_CONTROL_ALLOW_HEADERS, 'REST'));
	}

	/**
	 * testEventReqCorsAccessControlMaxAge
	 *
	 * test that max age returns 3628800
	 */
	public function testEventReqCorsAccessControlMaxAge()
	{
		$this->assertEquals(3628800, $this->event->emit(ERestEvent::REQ_CORS_ACCESS_CONTROL_MAX_AGE));
	}

	/**
	 * testEventReqAuthCors
	 *
	 * test that CORS authentication event works correclty
	 */
	public function testEventReqAuthCors()
	{
		$_SERVER['HTTP_ORIGIN'] = 'http://noallowed.test';
		$this->assertFalse($this->event->emit(ERestEvent::REQ_AUTH_CORS, [['http://1test.test', 'http://2test.test']]), "req.auth.cors should return false");

		$_SERVER['HTTP_ORIGIN'] = 'http://allowed.test';
		$this->assertTrue($this->event->emit(ERestEvent::REQ_AUTH_CORS, [['http://allowed.test']]), "req.auth.cors should return true");
	}

	/**
	 * testEventReqAuthAjaxUser
	 *
	 * tests event req.auth.ajax.user
	 */ 
	public function testEventReqAuthAjaxUser()
	{
		$this->assertTrue(!$this->event->emit(ERestEvent::REQ_AUTH_AJAX_USER), "req.auth.ajax.user should return false");

		$this->asUser($this, function() {
			$this->assertTrue($this->event->emit(ERestEvent::REQ_AUTH_AJAX_USER), "req.auth.ajax.user should return true");
		});
	}

	/**
	 * testEventReqAuthURI
	 *
	 * tests event req.auth.uri
	 */ 
	public function testEventReqAuthUri()
	{
		$this->assertTrue($this->event->emit(ERestEvent::REQ_AUTH_URI, ['/api/unit/testing', 'GET']), "req.auth.uri should return true");
	}

	/**
	 * testEventReqAuthHttpsOnly
	 *
	 * tests event req.auth.https.only
	 */
	public function testEventReqAuthHttpsOnly()
	{
		$this->assertTrue(!$this->event->emit(ERestEvent::REQ_AUTH_HTTPS_ONLY), "req.auth.https.only should return false by default");
	}

	/**
	 * testEventReqAuthUsername
	 *
	 * tests event req.auth.username
	 */
	public function testEventReqAuthUsername()
	{
		$this->assertEquals('admin@restuser', $this->event->emit(ERestEvent::REQ_AUTH_USERNAME), "req.auth.username should return 'admin@restuser' by default; instead returned: {$this->event->emit(ERestEvent::REQ_AUTH_USERNAME)}");
	}

	/**
	 * testEventReqAuthPassword
	 *
	 * tests event req.auth.password
	 */
	public function testEventReqAuthPassword()
	{
		$this->assertEquals('admin@Access', $this->event->emit(ERestEvent::REQ_AUTH_PASSWORD), "req.auth.password should return 'admin@Access' by default");
	}

	/**
	 * testReqAuthUser
	 *
	 * tests event req.auth.user
	 */
	public function testReqAuthUser()
	{
		$_SERVER['HTTP_X_REST_USERNAME'] = 'admin@restuser';
		$_SERVER['HTTP_X_REST_PASSWORD'] = 'admin@Access';
		
		$this->assertTrue($this->event->emit(
			ERestEvent::REQ_AUTH_USER, [
				'REST',
				'admin@restuser',
				'admin@Access'
			]
		));

		$_SERVER['HTTP_X_REST_USERNAME'] = 'guest@restuser';
		$_SERVER['HTTP_X_REST_PASSWORD'] = 'guest@Access';

		$this->assertTrue(!$this->event->emit(
			ERestEvent::REQ_AUTH_USER, [
				'REST',
				'admin@restuser',
				'admin@Access'
			]
		));
	}

	/**
	 * testAfterAction
	 *
	 * tests event req.after.action
	 */
	public function testAfterAction()
	{
		$this->assertEquals(null, $this->event->emit(ERestEvent::REQ_AFTER_ACTION, null));
	}

	public function testReqOptionsRender()
	{
		$expected_result = '{"Access-Control-Allow-Origin:":"http:\/\/restfullyii.test","Access-Control-Max-Age":3628800,"Access-Control-Allow-Methods":"GET, PUT","Access-Control-Allow-Headers: ":"X_REST_CORS"}';
		
		$event_result = $this->captureOB($this, function() {
			$_SERVER['HTTP_ORIGIN'] = 'http://restfullyii.test';
			$this->event->emit(ERestEvent::REQ_OPTIONS_RENDER, [
				['X_REST_CORS'],
				['GET', 'PUT'],
				3628800
			]);
			unset($_SERVER['HTTP_ORIGIN']);
		});

		$this->assertJsonStringEqualsJsonString($expected_result, $event_result);
	}

	/**
	 * testReqGetResourceRender()
	 *
	 * tests event req.get.resource.render
	 *
	 */
	public function testReqGetResourceRender()
	{
		$expected_result = '{"success":true,"message":"Record Found","data":{"totalCount":1,"post":{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1","categories":[{"id":"1","name":"cat1"},{"id":"2","name":"cat2"}],"author":{"id":"1","username":"username1","password":"password1","email":"email@email1.com"}}}}';
		
		$event_result = $this->event->emit(ERestEvent::REQ_GET_RESOURCE_RENDER, [
			Post::model()->findByPk(1),
			'Post',
			['categories', 'author'],
			1
		]);
		
		$this->assertJsonStringEqualsJsonString($expected_result, $event_result);


		//We need to test when no record(s) is found.
		$expected_result_2 = '{"success":false,"message":"No Record Found","data":{"totalCount":0,"post":[]}}';

		$event_result_2 =$this->event->emit(ERestEvent::REQ_GET_RESOURCE_RENDER, [
			null,
			'Post',
			['categories', 'author'],
			0
		]);
	
		$this->assertJsonStringEqualsJsonString($expected_result_2, $event_result_2);
	}

	/**
	 * testReqGetResourcesRender()
	 *
	 * tests event req.get.resources.render
	 *
	 */
	public function testReqGetResourcesRender()
	{
		$expected_result = '{"success":true,"message":"Record(s) Found","data":{"totalCount":"6","post":[{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1","categories":[{"id":"1","name":"cat1"},{"id":"2","name":"cat2"}],"author":{"id":"1","username":"username1","password":"password1","email":"email@email1.com"}},{"id":"2","title":"title2","content":"content2","create_time":"2013-08-07 10:09:42","author_id":"2","categories":[{"id":"2","name":"cat2"}],"author":{"id":"2","username":"username2","password":"password2","email":"email@email2.com"}},{"id":"3","title":"title3","content":"content3","create_time":"2013-08-07 10:09:43","author_id":"3","categories":[{"id":"3","name":"cat3"}],"author":{"id":"3","username":"username3","password":"password3","email":"email@email3.com"}},{"id":"4","title":"title4","content":"content4","create_time":"2013-08-07 10:09:44","author_id":"4","categories":[{"id":"4","name":"cat4"}],"author":{"id":"4","username":"username4","password":"password4","email":"email@email4.com"}},{"id":"5","title":"title5","content":"content5","create_time":"2013-08-07 10:09:45","author_id":"5","categories":[{"id":"5","name":"cat5"}],"author":{"id":"5","username":"username5","password":"password5","email":"email@email5.com"}},{"id":"6","title":"title6","content":"content6","create_time":"2013-08-07 10:09:46","author_id":"6","categories":[{"id":"6","name":"cat6"}],"author":{"id":"6","username":"username6","password":"password6","email":"email@email6.com"}}]}}';

		$event_result = $this->event->emit(ERestEvent::REQ_GET_RESOURCES_RENDER, [
				Post::model()->findAll(),
				'Post',
				['categories', 'author'],
				Post::model()->count()
			]);
		
		$this->assertJsonStringEqualsJsonString($expected_result, $event_result);

		//We need to test when no record(s) is found.
		$expected_result_2 = '{"success":false,"message":"No Record(s) Found","data":{"totalCount":0,"post":[]}}';

		$event_result_2 = $this->event->emit(ERestEvent::REQ_GET_RESOURCES_RENDER, [
			null,
			'Post',
			['categories', 'author'],
			0
		]);

		$this->assertJsonStringEqualsJsonString($expected_result_2, $event_result_2);
	}

	/**
	 * testReqGetSubresourcesRender
	 *
	 * tests event req.get.subresources.render
	 */ 
	public function testReqGetSubresourcesRender()
	{
		$expected_result = '{"success":true,"message":"Record(s) Found","data":{"totalCount":2,"categories":[{"id":"1","name":"cat1"},{"id":"2","name":"cat2"}]}}';

		$event_result = $this->event->emit(ERestEvent::REQ_GET_SUBRESOURCES_RENDER, [
				Post::model()->findByPk(1)->categories,
				'categories',
				count(Post::model()->findByPk(1)->categories)
			]);
		
		$this->assertJsonStringEqualsJsonString($expected_result, $event_result);

		//We need to test when no record(s) is found.
		$expected_result_2 = '{"success":false,"message":"No Record(s) Found","data":{"totalCount":0,"categories":[]}}';

		$event_result_2 = $this->event->emit(ERestEvent::REQ_GET_SUBRESOURCES_RENDER, [
				null,
				'categories',
				0
			]);

		$this->assertJsonStringEqualsJsonString($expected_result_2, $event_result_2);
	}

	/**
	 * testReqGetSubresourceRender
	 *
	 * tests event req.get.subresource.render
	 */ 
	public function testReqGetSubresourceRender()
	{
		$expected_result = '{"success":true,"message":"Record(s) Found","data":{"totalCount":1,"categories":{"id":"1","name":"cat1"}}}';

		$event_result = $this->event->emit(ERestEvent::REQ_GET_SUBRESOURCE_RENDER, [
			Post::model()->findByPk(1)->categories[0],
			'categories',
			1
		]);

		$this->assertJsonStringEqualsJsonString($expected_result, $event_result);

		//We need to test when no record(s) is found.
		$expected_result_2 = '{"success":true,"message":"No Record(s) Found","data":{"totalCount":0,"categories":[]}}';

		$event_result_2 = $this->event->emit(ERestEvent::REQ_GET_SUBRESOURCE_RENDER, [
			null,
			'categories',
			0
		]);

		$this->assertJsonStringEqualsJsonString($expected_result_2, $event_result_2);
	}

	/**
	 * testReqPostResourceRender
	 *
	 * tests event req.post.resource.render
	 */
	public function testReqPostResourceRender()
	{
		$expected_result = '{"success":true,"message":"Record Created","data":{"totalCount":1,"post":{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1","categories":[{"id":"1","name":"cat1"},{"id":"2","name":"cat2"}]}}}';
		
		$event_result = $this->event->emit(ERestEvent::REQ_POST_RESOURCE_RENDER, [
			Post::model()->findByPk(1),
			['categories']
		]);

		$this->assertJsonStringEqualsJsonString($expected_result, $event_result);
	}

	/**
	 * testReqPutResourceRender
	 *
	 * tests event req.put.resource.render
	 */
	public function testReqPutResourceRender()
	{
		$expected_result = '{"success":true,"message":"Record Updated","data":{"totalCount":1,"post":{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1","categories":[{"id":"1","name":"cat1"},{"id":"2","name":"cat2"}]}}}';
		
		$event_result = $this->event->emit(ERestEvent::REQ_PUT_RESOURCE_RENDER, [
			Post::model()->findByPk(1),
			['categories']
		]);

		$this->assertJsonStringEqualsJsonString($expected_result, $event_result);
	}

	/**
	 * testReqPutSubresourceRender
	 *
	 * tests event req.put.subresource.render
	 */
	public function testReqPutSubresourceRender()
	{
		$expected_result = '{"success":true,"message":"Subresource Added","data":{"totalCount":1,"post":{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1","categories":[{"id":"1","name":"cat1"},{"id":"2","name":"cat2"}]}}}';

		$event_result = $this->event->emit(ERestEvent::REQ_PUT_SUBRESOURCE_RENDER, [
			Post::model()->findByPk(1),
			'categories',
			1
		]);

		$this->assertJsonStringEqualsJsonString($expected_result, $event_result);
	}

	/**
	 * testReqDeleteResourceRender
	 *
	 * tests event req.delete.resource.render
	 */
	public function testReqDeleteResourceRender()
	{
		$expected_result = '{"success":true,"message":"Record Deleted","data":{"totalCount":1,"post":{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1"}}}';
		
		$event_result = $this->event->emit(ERestEvent::REQ_DELETE_RESOURCE_RENDER, [
			Post::model()->findByPk(1)
		]);

		$this->assertJsonStringEqualsJsonString($expected_result, $event_result);
	}

	/**
	 * testReqDeleteSubresourceRender
	 *
	 * tests event req.delete.subresource.render
	 */
	public function testReqDeleteSubresourceRender()
	{
		$expected_result = '{"success":true,"message":"Sub-Resource Deleted","data":{"totalCount":1,"post":{"id":"1","title":"title1","content":"content1","create_time":"2013-08-07 10:09:41","author_id":"1","categories":[{"id":"1","name":"cat1"},{"id":"2","name":"cat2"}]}}}';

		$event_result = $this->event->emit(ERestEvent::REQ_DELETE_SUBRESOURCE_RENDER, [
			Post::model()->findByPk(1),
			'categories',
			1
		]);

		$this->assertJsonStringEqualsJsonString($expected_result, $event_result);
	}

	/**
	 * testModelSubresourceFind
	 *
	 * tests event model.subresource.find
	 */
	public function testModelSubresourceFind()
	{
		$event_result = $this->event->emit(ERestEvent::MODEL_SUBRESOURCE_FIND, [
			Post::model()->findByPk(1),
			'categories',
			1
		]);
		$this->assertInstanceOf('Category', $event_result);
		$this->assertEquals(1, $event_result->id);

		//test no subresource is found
		$event_result_2 = $this->event->emit(ERestEvent::MODEL_SUBRESOURCE_FIND, [
			Post::model()->findByPk(1),
			'categories',
			10
		]);
		$this->assertEquals([], $event_result_2);
	}

	/**
	 * testModelSubresourcesFindAll
	 *
	 * tests event model.subresources.find.all
	 */
	public function testModelSubresourcesFindAll()
	{
		$expected_result = CJSON::encode(Post::model()->findByPk(1)->categories);

		$event_result = $this->event->emit(ERestEvent::MODEL_SUBRESOURCES_FIND_ALL, [
			Post::model()->findByPk(1),
			'categories'
		]);
		
		$this->assertJsonStringEqualsJsonString($expected_result, CJSON::encode($event_result));
	}

	/**
	 * testReqParamIsPk
	 *
	 * tests event req.param.is.pk
	 */
	public function testReqParamIsPk()
	{
		$this->assertTrue($this->event->emit(ERestEvent::REQ_PARAM_IS_PK, [1]));
		$this->assertTrue($this->event->emit(ERestEvent::REQ_PARAM_IS_PK, [999999789]));
		$this->assertTrue(!$this->event->emit(ERestEvent::REQ_PARAM_IS_PK, ['A']));
		$this->assertTrue(!$this->event->emit(ERestEvent::REQ_PARAM_IS_PK, ['I AM NOT A PK']));
	}

	/**
	 * testModelInstance
	 *
	 * tests event model.instance
	 */
	public function testtestModelInstance()
	{
		$this->assertInstanceOf('Post', $this->event->emit(ERestEvent::MODEL_INSTANCE));
	}

	/**
	 * testModelAttachBehaviors
	 *
	 * tests event model.attach.behaviors
	 */
	public function testModelAttachBehaviors()
	{
		$model = Post::model()->findByPk(1);
		$model = $this->event->emit(ERestEvent::MODEL_ATTACH_BEHAVIORS, $model);
		$this->assertInstanceOf('Post', $model);
		$this->assertInstanceOf('ERestActiveRecordRelationBehavior', $model->detachBehavior('ERestActiveRecordRelationBehavior'));
		$this->assertInstanceOf('ERestHelperScopes', $model->detachBehavior('ERestHelperScopes'));
	}

	/**
	 * testModelLimit
	 *
	 * test event model.limit
	 */
	public function testModelLimit()
	{
		$this->assertEquals(100, $this->event->emit(ERestEvent::MODEL_LIMIT));

		$_GET['limit'] = 30;
		$this->assertEquals(30, $this->event->emit(ERestEvent::MODEL_LIMIT));
	}

	/**
	 * testModelOffset
	 *
	 * tests event model.offset
	 */
	public function testModelOffset()
	{
		$this->assertEquals(0, $this->event->emit(ERestEvent::MODEL_OFFSET));

		$_GET['offset'] = 30;
		$this->assertEquals(30, $this->event->emit(ERestEvent::MODEL_OFFSET));
	}

	/**
	 * testModelScenario
	 *
	 * tests event model.scenario
	 */
	public function testModelScenario()
	{
		$this->assertEquals('restfullyii', $this->event->emit(ERestEvent::MODEL_SCENARIO));

		$_GET['scenario'] = 'update';
		$this->assertEquals('update', $this->event->emit(ERestEvent::MODEL_SCENARIO));
	}

	/**
	 * testModelFilter
	 *
	 * tests event model.filter
	 */
	public function testModelFilter()
	{
		$this->assertEquals(null, $this->event->emit(ERestEvent::MODEL_FILTER));

		$_GET['filter'] = '[{"property":"SOME_PROPERTY", "direction":"DESC"}]';
		$this->assertEquals('[{"property":"SOME_PROPERTY", "direction":"DESC"}]', $this->event->emit(ERestEvent::MODEL_FILTER));
	}

	/**
	 * testModelSort
	 *
	 * tests event model.sort
	 */
	public function testModelSort()
	{
		$this->assertEquals(null, $this->event->emit(ERestEvent::MODEL_SORT));

		$_GET['sort'] = '[{"property":"SOME_PROPERTY", "direction":"DESC"}]';
		$this->assertEquals('[{"property":"SOME_PROPERTY", "direction":"DESC"}]', $this->event->emit(ERestEvent::MODEL_SORT));
	}

	/**
	 * testModelWithRelations
	 *
	 * tests event model.with.relations
	 */
	public function testModelWithRelations()
	{	
		$expected_result = ['categories', 'author'];
		$result = $this->event->emit(ERestEvent::MODEL_WITH_RELATIONS, [new Post()]);
		$this->assertArraysEqual($expected_result, $result);
	}

	/**
	 * testModelLazyLoadRelations
	 *
	 * tests event model.lazy.load.relations
	 */
	public function testModelLazyLoadRelations()
	{	
		$result = $this->event->emit(ERestEvent::MODEL_LAZY_LOAD_RELATIONS);
		$this->assertTrue($result);
	}

	/**
	 * testModelFind
	 *
	 * tests event model.find
	 */
	public function testModelFind()
	{
		$expected_result = Post::model()->findByPk(1);
		$result = $this->event->emit(ERestEvent::MODEL_FIND, [
			new Post(),
			1
		]);
		$this->assertJsonStringEqualsJsonString(CJSON::encode($expected_result), CJSON::encode($result));

		$expected_result_2 = Post::model()->findByPk(2);
		$result_2 = $this->event->emit(ERestEvent::MODEL_FIND, [
			new Post(),
			2
		]);
		$this->assertJsonStringEqualsJsonString(CJSON::encode($expected_result_2), CJSON::encode($result_2));
	}

	/**
	 * testModelFindAll
	 *
	 * tests event model.find.all
	 */
	public function testModelFindAll()
	{
		$expected_result = Post::model()->findAll();
		$result = $this->event->emit(ERestEvent::MODEL_FIND_ALL, [new Post()]);
		$this->assertJsonStringEqualsJsonString(CJSON::encode($expected_result), CJSON::encode($result));		
	}

	/**
	 * testModelCount
	 *
	 * tests event model.count
	 */
	public function testModelCount()
	{
		$expected_result = Post::model()->count();
		$result = $this->event->emit(ERestEvent::MODEL_COUNT, [new Post()]);
		$this->assertEquals($expected_result, $result);
	}

	/**
	 * testReqDataRead
	 *
	 * tests event req.data.read
	 */
	public function testReqDataRead()
	{
		$expected_result = [
			"test" => "someval",
			"test2" => "secondval"
		];

		$stream = fopen("php://temp", 'wb');
		fputs($stream, CJSON::encode($expected_result));

		$result = $this->event->emit(ERestEvent::REQ_DATA_READ, [$stream]);
		$this->assertArraysEqual($expected_result, $result);
	}

	/**
	 * testReqRenderJson
	 *
	 * tests event req.render.json
	 */
	public function testReqRenderJson()
	{
		$data = [
			"test" => "someval",
			"test2" => "secondval"
		];
		
		$result = $this->event->emit(ERestEvent::REQ_RENDER_JSON, [$data]);
		
		$this->assertJsonStringEqualsJsonString(CJSON::encode($data), $result);
	}

	/**
	 * testModelApplyPostData
	 *
	 * tests event model.apply.post.data
	 */
	public function testModelApplyPostData()
	{
		$model = new Post();
		$data = [
			"title"=>"title1",
			"content"=>"content1",
			"create_time"=>"2013-08-07 10:09:41",
			"author_id"=>"1",
		];
		$result = $this->event->emit(ERestEvent::MODEL_APPLY_POST_DATA, [
			$model,
			$data,
			[]
		]);
		$this->assertInstanceOf('Post', $result);
		foreach($data as $attr=>$val)
		{
			$this->assertEquals($result->$attr, $val);
		}
		
		$result = $this->captureOB($this, function() use ($data, $model){
			$this->event->emit(ERestEvent::MODEL_APPLY_POST_DATA, [
				$model,
				$data,
				['title']
			]);
		});
		$this->assertInstanceOf('Exception', $result);
		$this->assertExceptionHasMessage('Parameter \'title\' is not allowed for model (Post)', $result);
	}

	/**
	 * testModelApplyPutData
	 *
	 * tests event model.apply.put.data
	 */
	public function testModelApplyPutData()
	{
		$model = Post::model()->findByPk(2);
		$data = [
			"title"=>"title1",
			"content"=>"content1",
			"create_time"=>"2013-08-07 10:09:41",
			"author_id"=>"1",
		];
		$result = $this->event->emit(ERestEvent::MODEL_APPLY_PUT_DATA, [
			$model,
			$data,
			[]
		]);
		$this->assertInstanceOf('Post', $result);
		foreach($data as $attr=>$val)
		{
			$this->assertEquals($result->$attr, $val);
		}
		
		$result = $this->captureOB($this, function() use ($data, $model){
			$this->event->emit(ERestEvent::MODEL_APPLY_PUT_DATA, [
				$model,
				$data,
				['title']
			]);
		});
		$this->assertInstanceOf('Exception', $result);
		$this->assertExceptionHasMessage('Parameter \'title\' is not allowed for model (Post)', $result);
	}

	/**
	 * testModelRestrictedProperties
	 *
	 * tests event model.restricted.properties
	 */
	public function testModelRestrictedProperties()
	{
		$this->assertEquals([], $this->event->emit(ERestEvent::MODEL_RESTRICTED_PROPERTIES));
	}

	/**
	 * testModelVisibleProperties
	 *
	 * tests event model.visible.properties
	 */
	public function testModelVisibleProperties()
	{
		$this->assertEquals([], $this->event->emit(ERestEvent::MODEL_VISIBLE_PROPERTIES));
	}

	/**
	 * testModelHiddenProperties
	 *
	 * tests event model.hidden.properties
	 */
	public function testModelHiddenProperties()
	{
		$this->assertEquals([], $this->event->emit(ERestEvent::MODEL_HIDDEN_PROPERTIES));
	}

	/**
	 * testModelSave
	 *
	 * tests event model.save
	 */
	public function testModelSave()
	{
		$model = Post::model()->findByPk(1);
		$model->title = 'evans updated';

		$result = $this->event->emit(ERestEvent::MODEL_SAVE, [$model]);
		$this->assertInstanceOf('Post', $result);
		$this->assertEquals('evans updated', $result->title);

		for($i=0; $i<257; $i++) {
			$model->title .= $i;
		}

		$result_2 = $this->captureOB($this, function() use ($model) {
			$this->event->emit(ERestEvent::MODEL_SAVE, [$model]);
		});
		$this->assertInstanceOf('Exception', $result_2);
		$this->assertExceptionHasMessage('Title is too long (maximum is 255 characters)', $result_2);
	}

	/**
	 * testModelSubresourceSave
	 *
	 * tests event model.subresources.save
	 */
	public function testModelSubresourceSave()
	{
		$result = $this->event->emit(ERestEvent::MODEL_SUBRESOURCE_SAVE, [
			Post::model()->findByPk(1),
			'categories',
			5
		]);
		$this->assertTrue($result);

		$found = false;
		foreach(Post::model()->findByPk(1)->categories as $cat) {
			if($cat->id == 5) {
				$found = true;
				break;
			}
		}
		$this->assertTrue($found);

		$result_2 = $this->captureOB($this, function() {
			$this->event->emit(ERestEvent::MODEL_SUBRESOURCE_SAVE, [
				Post::model()->findByPk(1),
				'categories',
				56
			]);
		});
		$this->assertInstanceOf('Exception', $result_2);
		$this->assertExceptionHasMessage('Cannot add or update a child row: a foreign key constraint fails', $result_2);
	}

	/**
	 * testModelDelete
	 *
	 * tests event model.delete
	 */
	public function testModelDelete()
	{
		$model = Post::model()->findByPk(1);
		$result = $this->event->emit(ERestEvent::MODEL_DELETE, [$model]);
		$this->assertInstanceOf('Post', $result);

		$model = Post::model()->findByPk(1);
		$this->assertEquals(null, $model);
	}

	/**
	 * testModelSubresourceDelete
	 *
	 * model.subresource.delete
	 */
	public function testModelSubresourceDelete()
	{
		$result = $this->event->emit(ERestEvent::MODEL_SUBRESOURCE_DELETE, [
			Post::model()->findByPk(1),
			'categories',
			1
		]);
		$this->assertInstanceOf('Post', $result);

		$not_found = true;
		foreach($result->categories as $cat) {
			if($cat->id == 1) {
				$not_found = false;
				break;
			}
		}
		$this->assertTrue($not_found);
	}

}
