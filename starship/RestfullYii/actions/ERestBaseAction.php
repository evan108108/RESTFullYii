<?php
/**
 * Base Class For Rest Actions
 *
 * Provides helper methods for rest actions
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/actions
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class ERestBaseAction extends CAction
{
	/**
	 * getRequestActionType
	 *
	 * Helps determine the action type
	 *
	 * @param (Mixed/Int) (id) unique identifier of the resource
	 * @param (Mixed) (param1) first param sent in the request; Often subresource name
	 * @param (Mixed) (param2) Second param sent in the request: Often subresource ID
	 *
	 * @return (String) the action type
	 */
	public function getRequestActionType($id=null, $param1=null, $param2=null, $verb='get')
	{
		$id_is_null = is_null($id);
		$id_is_pk = $this->controller->emitRest(ERestEvent::REQ_PARAM_IS_PK, $id);
		$is_subresource = $id_is_pk && $this->controller->getSubresourceHelper()->isSubresource($this->controller->emitRest(ERestEvent::MODEL_INSTANCE), $param1);
		$is_custom_route = $this->controller->eventExists("req.$verb.$id.render");

		if($id_is_null) {
			return 'RESOURCES';
		} else if($is_custom_route) {
			return 'CUSTOM';
		} else if($is_subresource && is_null($param2)) {
			return 'SUBRESOURCES';
		} else if($is_subresource && !is_null($param2)) {
			return 'SUBRESOURCE';
		} else if($id_is_pk && is_null($param1)) {
			return 'RESOURCE';
		} else {
			return false;
		}
	}

	/**
	 * getModel
	 *
	 * Helper to retrieve the model of the current resource
	 *
	 * @param (Mixed/Int) (id) unique identifier of the resource
	 * @param (Bool) (empty) if true will return only an empty model;
	 *
	 * @return (Object) (Model) the model representing the current resource
	 */
	public function getModel($id=null, $empty=false)
	{
		if($empty) {
			return $this->controller->emitRest(ERestEvent::MODEL_INSTANCE);
		}
		return $this->controller->getResourceHelper()->prepareRestModel($id);
	}

	/**
	 * getModelCount
	 *
	 * Helper that returns the count of models representing the requested resource
	 *
	 * @param (Mixed/Int) (id) unique identifier of the resource
	 *
	 * @return (Int) Count of found models
	 */
	public function getModelCount($id=null)
	{
		return $this->controller->getResourceHelper()->prepareRestModel($id, true);
	}

	/**
	 * getModelName
	 *
	 * Helper that returns the name of the model associated with the requested resource
	 *
	 * @return (String) name of the model
	 */
	public function getModelName()
	{
		return get_class($this->controller->emitRest(ERestEvent::MODEL_INSTANCE));
	}

	/**
	 * getRelations
	 *
	 * Helper that returns the relations to include when the resource is rendered
	 *
	 * @return (Array) list of relations to include in output
	 */
	public function getRelations()
	{
		return $this->controller->emitRest(ERestEvent::MODEL_WITH_RELATIONS,
			$this->controller->emitRest(ERestEvent::MODEL_INSTANCE)
		);
	}

	/**
	 * getSubresourceCount
	 *
	 * Helper that will return the count of subresources of the requested resource
	 *
	 * @param (Mixed/Int) (id) unique identifier of the resource
	 * @param (Mixed) (param1) Subresource name
	 * @param (Mixed) (param2) Subresource ID
	 *
	 * @return (Int) Count of subresources
	 */
	public function getSubresourceCount($id, $param1, $param2=null)
	{
		return $this->controller->emitRest(ERestEvent::MODEL_SUBRESOURCE_COUNT, [
			$this->getModel($id),
				$param1,
				$param2
			]
		);
	}

	/**
	 * getSubresourceClassName
	 *
	 * Helper that will return the class name that will be used to represent the requested subresource
	 *
	 * @param (String) Name of subresource
	 *
	 * @return (String) Name of subresource class
	 */
	public function getSubresourceClassName($param1)
	{
		return $this->controller->getSubresourceHelper()->getSubresourceClassName(
			$this->controller->emitRest(ERestEvent::MODEL_INSTANCE),
			$param1
		);
	}

	/**
	 * getSubresources
	 *
	 * Helper that returns a list of subresource object models
	 *
	 * @param (Mixed/Int) (id) the ID of the requested resource
	 * @param (String) (param1) the name of the subresource
	 *
	 * @return (Array) Array of subresource object models
	 */
	public function getSubresources($id, $param1)
	{
		return $this->controller->emitRest(ERestEvent::MODEL_SUBRESOURCES_FIND_ALL, [$this->getModel($id), $param1]);
	}

	/**
	 * getSubresources
	 *
	 * Helper that returns a single subresource
	 *
	 * @param (Mixed/Int) (id) unique identifier of the resource
	 * @param (Mixed) (param1) Subresource name
	 * @param (Mixed) (param2) Subresource ID
	 *
	 * @return (Object) the sub resource model object
	 */
	public function getSubresource($id, $param1, $param2)
	{
		return $this->controller->emitRest(ERestEvent::MODEL_SUBRESOURCE_FIND, [$this->getModel($id), $param1, $param2]);
	}
}
