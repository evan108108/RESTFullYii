<?php
Yii::import('RestfullYii.actions.ERestBaseAction');

/**
 * Action For Rest Puts
 *
 * Provides the action for rest put behavior
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/actions
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class EActionRestPUT extends ERestBaseAction
{
	/**
	 * run
	 *
	 * Called by Yii for PUT verb
	 * 
	 * @param (Mixed/Int) (id) unique identifier of the resource
	 * @param (Mixed) (param1) first param sent in the request; Often subresource name
	 * @param (Mixed) (param2) Second param sent in the request: Often subresource ID
	 */
	public function run($id=null, $param1=null, $param2=null) 
	{
		$this->finalRender(function($visibleProperties, $hiddenProperties) use($id, $param1, $param2) {
				switch ($this->getRequestActionType($id, $param1, $param2, 'put')) {
					case 'RESOURCES':
						throw new CHttpException('405', 'Method Not Allowed');
						break;
					case 'CUSTOM':
						return $this->controller->emitRest("req.put.$id.render", [$this->controller->emitRest(ERestEvent::REQ_DATA_READ), $param1, $param2]);
						break;
					case 'SUBRESOURCES':
						throw new CHttpException('405', 'Method Not Allowed');
						break;
					case 'SUBRESOURCE':
						return $this->controller->emitRest(ERestEvent::REQ_PUT_SUBRESOURCE_RENDER, [
							$this->handlePutSubresource($id, $param1, $param2),
							$param1,
							$param2,
							$visibleProperties,
							$hiddenProperties,
						]);
						break;
					case 'RESOURCE':
						return $this->controller->emitRest(ERestEvent::REQ_PUT_RESOURCE_RENDER, [$this->handlePut($id), $this->getRelations(), $visibleProperties, $hiddenProperties]);
						break;
					default:
						throw new CHttpException(404, "Resource Not Found");
				}
			}
		);
	}

	/**
	 * handlePut
	 *
	 * Helper method for PUT actions
	 *
	 * @param (Mixed/Int) (id) unique identifier of the resource to put
	 *
	 * @return (Object) Returns the model of the updated resource
	 */ 
	public function handlePut($id)
	{
		$model = $this->controller->emitRest(
			ERestEvent::MODEL_ATTACH_BEHAVIORS,
			$this->getModel($id)
		);
		$data = $this->controller->emitRest(ERestEvent::REQ_DATA_READ);	
		$restricted_properties = $this->controller->emitRest(ERestEvent::MODEL_RESTRICTED_PROPERTIES);
		$model = $this->controller->emitRest(ERestEvent::MODEL_APPLY_PUT_DATA, [$model, $data, $restricted_properties]);
		return $this->controller->emitRest(ERestEvent::MODEL_SAVE, [$model]);
	}

	/**
	 * handlePutSubresource
	 *
	 * Helper method for PUT subresource actions
	 *
	 * @param (Mixed/Int) (id) unique identifier of the resource
	 * @param (String) (subresource_name) name of the subresource
	 * @param (Mixed/Int) (subresource_id) unique identifier of the subresource to put
	 *
	 * @return (Object) Returns the model containing the updated subresource
	 */ 
	public function handlePutSubresource($id, $subresource_name, $subresource_id)
	{
		$model = $this->controller->emitRest(
			ERestEvent::MODEL_ATTACH_BEHAVIORS,
			$this->getModel($id)
		);
		$this->controller->emitRest(ERestEvent::MODEL_SUBRESOURCE_SAVE, [$model, $subresource_name, $subresource_id]);
		return $model;
	}
}
