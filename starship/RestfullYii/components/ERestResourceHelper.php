<?php
Yii::import('RestfullYii.components.iERestRequestReader');

/**
 * ERestResourceHelper
 * 
 * Helper methods for manipulating models used in request
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/components
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 * 
 * @property (Callable)		$emitter
 */
class ERestResourceHelper implements iERestResourceHelper
{	
	private $emitter;

	/**
	 * __construct
	 *
	 * Takes a callable dependency (emitter)
	 *
	 * @param (callable) (emitter) Callback used to emit events
	 */ 
	public function __construct(Callable $emitter)
	{
		$this->setEmitter($emitter);
	}

	/**
	 * setEmitter
	 *
	 * sets the emitter property
	 *
	 * @param (Callable) (emitter) the emitter callback
	 */
	public function setEmitter(Callable $emitter)
	{
		$this->emitter = $emitter;
	}

	/**
	 * getEmitter
	 *
	 * gets the emitter callback
	 *
	 * @return (Callable) (emitter) the emitter callback
	 */ 
	public function getEmitter()
	{
		return $this->emitter;
	}

	/**
	 * prepareRestModel
	 *
	 * gets the model associated with the REST request ready for output
	 *
	 * @param (Mixed/Int) (id) the id of the model
	 * @param (Bool) (count) if true then the count is returned; if false then the model is returned
	 *
	 * @return (Mixed) prepared model or count of models
	 */ 
	public function prepareRestModel($id=null, $count=false)
	{
		$emitRest = $this->getEmitter();

		$model = $emitRest(ERestEvent::MODEL_INSTANCE);
		$model = $emitRest(ERestEvent::MODEL_ATTACH_BEHAVIORS, $model);

		$scenario = $emitRest(ERestEvent::MODEL_SCENARIO);
		if(!is_null($scenario) && $scenario !== false) {
			$model->scenario = $scenario;
		}
		
		if($emitRest(ERestEvent::MODEL_LAZY_LOAD_RELATIONS) === false) {
			$model = $this->applyScope(ERestEvent::MODEL_WITH_RELATIONS, $model, 'with', true);
		}

		if(!empty($id)) {
			$model_found = $emitRest(ERestEvent::MODEL_FIND, [$model, $id]);
			if(is_null($model_found)) {
				throw new CHttpException(404, "RESOURCE '$id' NOT FOUND");
			}
			return ($count)? 1: $model_found;
		}

		$model = $this->applyScope(ERestEvent::MODEL_FILTER, $model, 'filter', false);
		$model = $this->applyScope(ERestEvent::MODEL_SORT, $model, 'orderBy', false);

		if($count) {
			return $emitRest(ERestEvent::MODEL_COUNT, $model);
		}

		$model = $this->applyScope(ERestEvent::MODEL_LIMIT, $model, 'limit', false);
		$model = $this->applyScope(ERestEvent::MODEL_OFFSET, $model, 'offset', false);

		return $emitRest(ERestEvent::MODEL_FIND_ALL, $model);
	}

	/**
	 * applyScope
	 *
	 * helper fo prepareRestModel
	 * sets model scopes
	 *
	 * @param (String) (event) the name of the event
	 * @param (Object) (model) an AR model object
	 * @param (String) (scope_name) the name of the scope to apply
	 * @param (Bool) (pass_model) true to pass model into the event
	 *
	 * @return (Object) (model) returns the AR model object with scope applied
	 */
	private function applyScope($event, $model, $scope_name, $pass_model=false)
	{
		$emitRest = $this->getEmitter();

		if(!$pass_model) {
			$scope_params = $emitRest($event);
		} else {
			$scope_params = $emitRest($event, $model);
		}
		if(!is_null($scope_params) && $scope_params !== false) {
			return $model->$scope_name($scope_params);
		}
		return $model;
	}


	/**
	 * setModelAttributes
	 *
	 * sets an AR models properties to the data in the request
	 *
	 * @param (Object) (model) AR model
	 * @param (Array) (data) data to be applied to the AR model
	 * @param (Array) (restricted_properties) list of properties not to allow setting of
	 *
	 * @return (Object) AR model
	 */
	public function setModelAttributes($model, $data, $restricted_properties)
	{
		foreach($data as $var=>$value) {
			if(($model->hasAttribute($var) || isset($model->metadata->relations[$var])) && !in_array($var, $restricted_properties)) {	
				$model->$var = $value;	
			}
			else {
				throw new CHttpException(406, 'Parameter \'' . $var . '\' is not allowed for model (' . get_class($model) . ')');
			}
		}
		return $model;
	}
}
