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
			'ERestBehavior'=>'RestfullYii.behaviors.ERestBehavior'
		]);
		$controller->ERestInit();

		/**
		 * Since we are going to be outputting JSON
		 * we disable CWebLogRoute unless otherwise indicated
		 */ 
		if( $controller->emitRest(ERestEvent::REQ_DISABLE_CWEBLOGROUTE ) ){
			foreach (Yii::app()->log->routes as $route) {
				if ( $route instanceof CWebLogRoute ) {
						$route->enabled = false;
				}
			}
		}


		if( $controller->emitRest(ERestEvent::REQ_AUTH_HTTPS_ONLY) ) {
			if( !$this->validateHttpsOnly() ) {
				throw new CHttpException(401, "You must use a secure connection");
			}
		}


		$application_id = $controller->emitRest(ERestEvent::CONFIG_APPLICATION_ID);

		switch ($controller->emitRest(ERestEvent::REQ_AUTH_TYPE, $application_id)) {
			case ERestEventListenerRegistry::REQ_TYPE_CORS:
				$authorized = $controller->emitRest(
					ERestEvent::REQ_AUTH_CORS,
					[$controller->emitRest(ERestEvent::REQ_CORS_ACCESS_CONTROL_ALLOW_ORIGIN)]
				);
				break;
			case ERestEventListenerRegistry::REQ_TYPE_USERPASS:	
				$authorized = ($controller->emitRest(ERestEvent::REQ_AUTH_USER, [
					$application_id,
					$controller->emitRest(ERestEvent::REQ_AUTH_USERNAME),
					$controller->emitRest(ERestEvent::REQ_AUTH_PASSWORD),
				]));
				break;
			case ERestEventListenerRegistry::REQ_TYPE_AJAX:
				$authorized = ($controller->emitRest(ERestEvent::REQ_AUTH_AJAX_USER));
				break;
			default:
				$authorized = false;
				break;
		}

		if(!$authorized) {
			throw new CHttpException(401, "Unauthorized");
		}
	
		if(!$controller->emitRest(ERestEvent::REQ_AUTH_URI, $controller->getURIAndHTTPVerb())) {
			throw new CHttpException(401, "Unauthorized");
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

