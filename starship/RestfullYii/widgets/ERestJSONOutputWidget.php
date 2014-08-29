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
		 * init
		 *
		 * called when widget is initialized
		 * will set the initial properties
		 */ 
		public function init()
		{
				if(is_null($this->visibleProperties)) {
						$this->visibleProperties = [];
				}
				if(is_null($this->hiddenProperties)) {
						$this->hiddenProperties = [];
				}

				parent::init();
		}
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
				"totalCount" => (Int) $this->totalCount,
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

		$listOfModels = !is_array($model)? [$model]: $model;

		$process_relations = function($relationName, $models) {
			if(is_null($models)){
				return null;
			}
			if( !is_array($models) ) {
				return $this->processAttributes($models, $relationName);
			}
			$list = [];
			foreach($models as $index => $model) {
				$list[$index] = $this->processAttributes($model, $relationName);
			}
			return $list;
		};

		array_walk($listOfModels, function($ar_model, $index) use($relations, &$model_as_array, $process_relations) {
			$model_as_array[$index] = $this->processAttributes($ar_model);
			foreach($relations as $relation) {
				$model_as_array[$index][$relation] = 
					(
						($ar_model->relations()[$relation][0] != CActiveRecord::STAT)?
						//(is_object($ar_model->$relation) || is_array($ar_model->$relation))?
							$process_relations($relation, $ar_model->$relation):
							$ar_model->$relation
					);
			}
		});

		return is_array($model)? $model_as_array: $model_as_array[0];
	}

		/**
	 * propertyIsVisible
	 *
	 * Decides if a property is visable.
	 *
	 * @param (String) (property) the name of the property
	 * @param (String) (relation) the name of the relation if any
	 *
	 * @return (Bool) True if the property should be visable false if it should not
	 */
		public function propertyIsVisible($property, $relation=null)
		{
				$main_model_visible_properties = [];
				$related_model_visible_properties = []; 

				foreach($this->visibleProperties as $vp) {
						if(strpos($vp, '.') === false) {
								$main_model_visible_properties[] = $vp;
						} else if(!is_null($relation)) {
								$vp = str_replace('*.', "$relation.", $vp); 
								list($relation_name, $property_name) = explode('.', $vp); 
								if(!isset($related_model_visible_properties[$relation_name])) {
										$related_model_visible_properties[$relation_name] = [];
								}
								$related_model_visible_properties[$relation_name][] = $vp;
						}
				}

				if(is_null($relation)) {
						if (!empty($main_model_visible_properties) && !in_array($property, $main_model_visible_properties) || !empty($this->hiddenProperties) && in_array($property, $this->hiddenProperties)) {
								return false;
						}
						return true;
				}

				if ( (isset($related_model_visible_properties[$relation]) && !in_array("$relation.$property", $related_model_visible_properties[$relation]) ) || (!empty($this->hiddenProperties) && (in_array("$relation.$property", $this->hiddenProperties)) ||  in_array("*.$property", $this->hiddenProperties))) {
						return false;
				}

				return true;
		}

	/**
	 * processAttributes
	 *
	 * Converts a models attributes to an array of same
	 *
	 * @param (Object) (model) the model to process
	 * @param (String) (relation) the name of the relation if any
	 *
	 * @return (Array) Array of the models attributes 
	 */
		public function processAttributes($model, $relation=null)
		{
			$schema = $model->getTableSchema();
			$model_as_array = [];

			//Provides the ability to override a models AR attributes
			$model_attributes = call_user_func_array(function($model, $event){ 
				if($this->controller->eventExists($event)) {
					return $this->controller->emitRest($event, $model);
				}
				return $model->attributes;
			}, [$model, 'model.' . strtolower(get_class($model)) . '.override.attributes']);

			foreach($model_attributes as $property => $value) {
				if (!$this->propertyIsVisible($property, $relation)) {
						continue;
				}
				if(array_key_exists($property, $schema->columns) && $this->isBinary($schema->columns[$property]->dbType, $value)) {
					$value =  bin2hex($value);
				}
				$model_as_array[$property] = $value;
			}
			return $model_as_array;
		}

		/**
		 * isBinary
		 *
		 * Helper to convert binary to hex when binary/blob field types are larger then 1 digit
		 *
		 * @param (String) (property_type) the data type of the given property
		 * @param (Mixed) (value) the value of the given property
		 *
		 * @return (Bool) returns true if the property is a binary that should be converted to hex false if not
		 */
		public function isBinary($property_type, $value)
		{
			try {
				if(@strlen($value) < 2) { // binarys with a length of 1 do not need to be converted to hex to render properly
					return false;
				}
				if(strpos($property_type, "binary") !== false) { //if we have a binary
					return true;
				}
				if(strpos($property_type, "blob") !== false) { //if we have a binary blob
					return true;
				}
			} catch(Exception $e) {
				return false;
			}
			return false;
		}
	
}
