<?php
/**
 * ERestFilter
 * 
 * Access filter for REST requests
 * Initializes and Attaches the ERestBehavior.
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/filters
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class ERestFilter extends CFilter
{
	/**
	 * preFilter
	 *
	 * logic being applied before the action is executed
	 * false if the action should not be executed
	 *
	 * @param (Object) (filterChain) the filterChain object
	 *
	 * @return (Mixed) false to deny access; $filterChain->run() to allow
	 */
	protected function preFilter($filterChain)
	{
		$controller = $filterChain->controller;

		$controller->attachBehaviors([
			'ERestBehavior'=>'ext.starship.RestfullYii.behaviors.ERestBehavior'
		]);
		$controller->ERestInit();	

		if( $controller->emitRest(ERestEvent::REQ_AUTH_HTTPS_ONLY) ) {
			if( !$this->validateHttpsOnly() ) {
				throw new CHttpException(401, "You must use a secure connection");
			}
		}

		if( !$controller->emitRest(ERestEvent::REQ_AUTH_AJAX_USER) ) {
			if(!$controller->emitRest(ERestEvent::REQ_AUTH_USER, [
				$controller->emitRest(ERestEvent::CONFIG_APPLICATION_ID),
				$controller->emitRest(ERestEvent::REQ_AUTH_USERNAME),
				$controller->emitRest(ERestEvent::REQ_AUTH_PASSWORD),
			])) {
				throw new CHttpException(401, "Unauthorized");
			}
		}

		return $filterChain->run();
	}

	/**
	 * postFilter
	 *
	 * logic being applied after the action is executed
	 *
	 * @param (Object) (filterChain) the filterChain object
	 */
	protected function postFilter($filterChain)
	{
		$filterChain->controller->emitRest(ERestEvent::REQ_AFTER_ACTION, $filterChain);
	}

	/**
	 * validateHttpsOnly
	 *
	 * checks if request is https
	 *
	 * @return (bool) returns true if the request protocol is https; false if not
	 */ 
	public final function validateHttpsOnly()
	{
		if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS']!='on'){
			return false;
		}
		return true;
	}
}

