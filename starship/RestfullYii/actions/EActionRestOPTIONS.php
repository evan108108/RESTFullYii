<?php
Yii::import('RestfullYii.actions.ERestBaseAction');

/**
 * Action For Rest Gets
 *
 * Provides the action for rest get behavior
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/actions
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class EActionRestOPTIONS extends ERestBaseAction
{
	/**
	 * run
	 *
	 * Called by Yii for GET verb
	 * 
	 * @param (Mixed/Int) (id) unique identifier of the resource
	 * @param (Mixed) (param1) first param sent in the request; Often subresource name
	 * @param (Mixed) (param2) Second param sent in the request: Often subresource ID
	 */
	public function run($id=null, $param1=null, $param2=null)
	{
		$application_id = $this->controller->emitRest(ERestEvent::CONFIG_APPLICATION_ID);
		$allowed_methods = $this->controller->emitRest(ERestEvent::REQ_CORS_ACCESS_CONTROL_ALLOW_METHODS);
		$allowed_headers = $this->controller->emitRest(ERestEvent::REQ_CORS_ACCESS_CONTROL_ALLOW_HEADERS, [$application_id]);
		$max_age = $this->controller->emitRest(ERestEvent::REQ_CORS_ACCESS_CONTROL_MAX_AGE);
		
		$this->controller->emitRest(ERestEvent::REQ_OPTIONS_RENDER, [
			$allowed_headers,
			$allowed_methods,
			$max_age,
		]);
	}
}
