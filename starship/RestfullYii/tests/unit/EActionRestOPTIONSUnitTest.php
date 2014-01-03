<?php
Yii::import('RestfullYii.actions.ERestBaseAction');
Yii::import('RestfullYii.actions.EActionRestOPTIONS');
Yii::import('RestfullYii.events.*');


/**
 * Test For Base Class For Rest Actions
 *
 * Tests action methods for rest options behavior
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/test
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class EActionRestOPTIONSUnitTest extends ERestTestCase
{
	private $optionsAction;

	public function setUp()
	{
		parent::setUp();
		
		$controller = $this->getController()->Category;
		$controller->attachBehaviors(array(
			'class'=>'RestfullYii.behaviors.ERestBehavior'
		));
		$controller->injectEvents('req.cors.access.control.allow.origin', function() {
			return ['http://cors-site.test'];
		});
		
		$_SERVER['HTTP_X_REST_CORS'] = 'ALLOW';
		$_SERVER['HTTP_ORIGIN'] = 'http://cors-site.test';

		$controller->ERestInit();
		$this->optionsAction = new EActionRestOPTIONS($controller, 'REST.OPTIONS');
	}

	/**
	 * run
	 *
	 * tests EActionRestOPTIONS->run()
	 */ 
	public function testRunRESOURCES()
	{
		$expected_result = '{"Access-Control-Allow-Origin:":"http:\/\/cors-site.test","Access-Control-Max-Age":3628800,"Access-Control-Allow-Methods":"GET, POST","Access-Control-Allow-Headers: ":"X_REST_CORS"}';
		
		$result = $this->captureOB($this, function() {
			$this->optionsAction->run();
		});

		$this->assertJsonStringEqualsJsonString($expected_result, $result);
	}

	/**
	 * tearDown
	 *
	 * clear server vars
	 */
	public function tearDown()
	{
		unset($_SERVER['HTTP_X_REST_CORS'], $_SERVER['HTTP_ORIGIN']);
		parent::tearDown();
	}

}

