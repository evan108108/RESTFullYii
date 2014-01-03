<?php
Yii::import('RestfullYii.actions.ERestActionProvider');

/**
 * Test For ERestActionProvider
 *
 * Provides glue to bind action classes
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/test
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class ERestActionProviderUnitTest extends ERestTestCase
{
	public $actions = [
		'GET'=>'RestfullYii.actions.EActionRestGET',
		'PUT'=>'RestfullYii.actions.EActionRestPUT',
		'POST'=>'RestfullYii.actions.EActionRestPOST',
		'DELETE'=>'RestfullYii.actions.EActionRestDELETE',
		'OPTIONS'=>'RestfullYii.actions.EActionRestOPTIONS',
		];

	/**
	 * actions
	 *
	 * tests ERestActionProvider::actions()
	 */ 
	public function testActions()
	{
		$this->assertArraysEqual($this->actions, ERestActionProvider::actions());
	}

}
