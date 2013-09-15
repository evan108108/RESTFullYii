<?php
/**
 * ERestJSONOutputWidget
 *
 * Helps in formatting output for rendering JSON on RESTFul Requests=
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/widgets
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 *
 * @property string		$type
 * @property bool			$success
 * @property string		$message
 * @property integer	$totalCount
 * @property string		$modelName
 * @property array		$relations
 * @property array		$data
 * @property integer	$errorCode
 * @property array		$visibleProperties
 * @property array		$hiddenProperties
 */
class ERestJSONOutputWidget extends CWidget {
	public $type = 'raw';
	public $success = true;
	public $message = "";
	public $totalCount;
	public $modelName;
	public $relations = [];
	public $data;
	public $errorCode = 500;
	public $visibleProperties = [];
	public $hiddenProperties = [];

	/**
	 * run
	 *
	 * called when widget is to be run
	 * will trigger different output based on $type
	 */
	public function run()
	{
		switch($this->type) {
			case 'error':
				$this->outputError();
				break;
			case 'rest':
				$this->outputRest();
				break;
			default:
				$this->outputRaw();
		}
	}

	/**
	 * outputRaw
	 *
	 * when type is 'raw' this method will simply output $data as JSON
	 */
	public function outputRaw()
	{
		echo CJSON::encode($this->data);
	}

	/**
	 * outputError
	 *
	 * when the output $type is 'error' $data JSON output will be formatted
	 * with a specific error template
	 */ 
	public function outputError()
	{
		echo CJSON::encode([
			'success'	=> false,
			'message'	=> $this->message,
			'data'		=> [
				"errorCode"	=> $this->errorCode,
				"message"		=> $this->message,
			]
		]);
	}

	/**
	 * outputRest
	 *
	 * when $type is 'REST' $data JSON output will be formatted
	 * with a specific rest template
	 */ 
	public function outputRest()
	{
		echo CJSON::encode([
			'success'	=> $this->success,
			'message'	=> $this->message,
			'data'		=> [
				"totalCount" => $this->totalCount,
				lcfirst($this->modelName) => $this->modelsToArray($this->data, $this->relations),
			]
		]);
	}

	/**
	 * modelsToArray
	 *
	 * helps format a model or models data as an array with relations as needed
	 *
	 * @param (Object) (model) the model or models to be formatted as an array
	 * @param (Array) (relations) the models relations to include in the data
	 * @param (Array) (model_as_array) the model(s) data represented as an array
	 *
	 * @return (Array) the model(s) data represented as an array
	 */
	public function modelsToArray($model, $relations, $model_as_array = [])
	{
		if(is_null($model)) {
			return [];
		}

		$listOfModels = !is_array($model)? $listOfModels = [$model]: $listOfModels = $model;

		$process_relations = function($models) {
			if(is_null($models)){
				return null;
			}
			if( !is_array($models) ) {
				return $models->attributes;
			}
			$list = [];
			foreach($models as $model) {
				$list[] = $model->attributes;
			}
			return $list;
		};

		array_walk($listOfModels, function($ar_model, $index) use($relations, &$model_as_array, $process_relations) {
			foreach ($ar_model->attributes as $property => $value) {
				if (!empty($this->visibleProperties) && !in_array($property, $this->visibleProperties)
					|| !empty($this->hiddenProperties) && in_array($property, $this->hiddenProperties))
					continue;
				$model_as_array[$index][$property] = $value;
			}
			foreach($relations as $relation) {
				$model_as_array[$index][$relation] = $process_relations($ar_model->$relation);
			}
		});

		return is_array($model)? $model_as_array: $model_as_array[0];
	}	

}

