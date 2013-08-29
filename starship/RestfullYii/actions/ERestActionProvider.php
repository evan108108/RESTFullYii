<?php
Yii::import('ext.starship.RestfullYii.actions.ERestBaseAction');

/**
 * Action Provider Widget
 *
 * Provides the actions for RestfullYii events
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/actions
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class ERestActionProvider extends CWidget
{
	/**
	 * actions
	 *
	 * @return (Array) List of actions and their ID's
	 */ 
	public static function actions() 
	{
		return [
			'GET'=>'ext.starship.RestfullYii.actions.EActionRestGET',
			'PUT'=>'ext.starship.RestfullYii.actions.EActionRestPUT',
			'POST'=>'ext.starship.RestfullYii.actions.EActionRestPOST',
			'DELETE'=>'ext.starship.RestfullYii.actions.EActionRestDELETE',
		];
	}
}
