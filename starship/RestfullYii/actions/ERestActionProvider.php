<?php
Yii::import(((isset(Yii::app()->params['RestfullYiiBasePath']))?(Yii::app()->params['RestfullYiiBasePath']):('ext')).'.starship.RestfullYii.actions.ERestBaseAction');

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
			'GET'=>((isset(Yii::app()->params['RestfullYiiBasePath']))?(Yii::app()->params['RestfullYiiBasePath']):('ext')).'.starship.RestfullYii.actions.EActionRestGET',
			'PUT'=>((isset(Yii::app()->params['RestfullYiiBasePath']))?(Yii::app()->params['RestfullYiiBasePath']):('ext')).'.starship.RestfullYii.actions.EActionRestPUT',
			'POST'=>((isset(Yii::app()->params['RestfullYiiBasePath']))?(Yii::app()->params['RestfullYiiBasePath']):('ext')).'.starship.RestfullYii.actions.EActionRestPOST',
			'DELETE'=>((isset(Yii::app()->params['RestfullYiiBasePath']))?(Yii::app()->params['RestfullYiiBasePath']):('ext')).'.starship.RestfullYii.actions.EActionRestDELETE',
		];
	}
}
