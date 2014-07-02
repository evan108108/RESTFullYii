<?php
Yii::import('RestfullYii.actions.ERestBaseAction');

/**
 * Action For Rest Posts
 *
 * Provides the action for rest post behavior
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/actions
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class EActionRestPOST extends ERestBaseAction
{
	/**
	 * run
	 *
	 * Called by Yii for DELETE verb
	 * 
	 * @param (Mixed/Int) (id) unique identifier of the resource
	 * @param (Mixed) (param1) first param sent in the request; Often subresource name
	 * @param (Mixed) (param2) Second param sent in the request: Often subresource ID
	 */
	public function run($id=null, $param1=null, $param2=null) 
	{
		$this->finalRender(
			function($visibleProperties, $hiddenProperties) use($id, $param1, $param2) {	
				switch ($this->getRequestActionType($id, $param1, $param2, 'post')) {
					case 'RESOURCES':
						return $this->controller->emitRest(ERestEvent::REQ_POST_RESOURCE_RENDER, [$this->handlePost(), $this->getRelations(), $visibleProperties, $hiddenProperties]);
						break;
					case 'CUSTOM':
						return $this->controller->emitRest("req.post.$id.render", [$this->controller->emitRest(ERestEvent::REQ_DATA_READ), $param1, $param2]);
						break;
					case 'SUBRESOURCES':
						throw new CHttpException('405', 'Method Not Allowed');
						break;
					case 'SUBRESOURCE':
						throw new CHttpException('405', 'Method Not Allowed');
						break;
					case 'RESOURCE':
						throw new CHttpException('405', 'Method Not Allowed');
						break;
					default:
						throw new CHttpException(404, "Resource Not Found");
				}
			}
		);
	}

	/**
	 * handlePost
	 *
	 * Helper method for post actions
	 *
	 * @return (Object) Returns the model of the new resource
	 */ 
	public function handlePost()
	{
		$model = $this->controller->emitRest(
			ERestEvent::MODEL_ATTACH_BEHAVIORS,
			$this->controller->emitRest(ERestEvent::MODEL_INSTANCE)
		);
		$data = $this->controller->emitRest(ERestEvent::REQ_DATA_READ);	
		$restricted_properties = $this->controller->emitRest(ERestEvent::MODEL_RESTRICTED_PROPERTIES);
		$model = $this->controller->emitRest(ERestEvent::MODEL_APPLY_POST_DATA, [$model, $data, $restricted_properties]);
		return $this->controller->emitRest(ERestEvent::MODEL_SAVE, [$model]);
	}
}
