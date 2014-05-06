<?php
Yii::import('RestfullYii.vendors.activerecord-relation-behavior.EActiveRecordRelationBehavior');

/**
 * ERestActiveRecordRelationBehavior
 * Extends EActiveRecordRelationBehavior
 *
 * Adds additional ability to save/update related models
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/ARBehaviors
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class ERestActiveRecordRelationBehavior extends EActiveRecordRelationBehavior
{
	/**
	 * events
	 *
	 * the Active record events to hook
	 */ 
	public function events()
	{
		return [
			'onBeforeSave'=>'beforeSave',
			'onAfterSave'=>'afterSave',
		];
	}

	/**
	 * beforeSave
	 *
	 * Sets up the transaction and triggers relation parsing
	 *
	 * @param (Object) (event) the active record event
	 *
	 * @return (Bool) the result of calling the parent beforeSave($event)
	 */ 
	public function beforeSave($event)
	{
		// ensure transactions
		if ($this->useTransaction && $this->owner->dbConnection->currentTransaction===null) {
			$this->setTransaction($this->owner->dbConnection->beginTransaction());
		}
		
		$this->preSaveHelper($this->owner);
		
		return parent::beforeSave($event);
	}

	/**
	 * preSaveHelper
	 *
	 * Cleans Model attributes
	 * Gets relations ready for save
	 *
	 * @param (Object) (model) takes an active record model
	 *
	 * @return (Object) returns the cleaned model
	 */
	public function preSaveHelper($model)
	{
		$attribute_cleaner = function($attributes) {
			array_walk($attributes, function(&$val, $attr) {
				if(is_bool($val) === true) {
					$val = (int) $val;
				} else if( $attr == 'id' && $val === 0) {
					$val = NULL;
				}
			});
			return $attributes;
		};

		$relation_helper = function($relation_name, $attributes) use ($attribute_cleaner, $model) {
			$relation_class_name = $model->metaData->relations[$relation_name]->className;

			$relation_model = new $relation_class_name();
			$relation_model->attributes = $attribute_cleaner($attributes);
			$table = $relation_model->getMetaData()->tableSchema;
			if(is_string($table->primaryKey)) {
				if(isset($attribute_cleaner($attributes)[($table->primaryKey)])) {
					$relation_model->setPrimaryKey($attribute_cleaner($attributes)[($table->primaryKey)]);
				}
			}	

			$relation_pk = $relation_model->getPrimaryKey();
			
			if( !empty($relation_pk) ) {
				//$relation_model = $relation_model::model()->findByPk($relation_pk);
				$relation_model->setIsNewRecord(false);
			}

			if($this->getRelationType($model, $relation_name) == 'CHasManyRelation' || $this->getRelationType($model, $relation_name) == 'CHasOneRelation') {
				$relation_model->{$model->metaData->relations[$relation_name]->foreignKey} = $model->getPrimaryKey();
			}

			if(!$relation_model->save()) {
				throw new CHttpException(500, 'Could not save Model: ' . get_class($relation_model) . ' : ' . CJSON::encode($relation_model->errors));
				$relation_model->refresh();
			}

			if($this->getRelationType($model, $relation_name) == 'CBelongsToRelation') {
				$belongs_to_fk = $model->metaData->relations[$relation_name]->foreignKey;
				if(empty($model->$belongs_to_fk)) {
					$model->$belongs_to_fk = $relation_model->getPrimaryKey();
				}
			}

			return $relation_model;
		};

		$model->attributes = $attribute_cleaner($model->attributes);

		foreach($model->metadata->relations as $key=>$value)
		{
			if( $model->hasRelated($key) ) {
				if( array_key_exists(0, $model->{$key}) ) {
					$relation_data = [];
					foreach($model->{$key} as $index=>$attributes) {
						if(!is_object($attributes)) {
							$relation_data[$index] = $relation_helper($key, $attributes);
						} else {
							$relation_data[$index] = $attributes;
						}
					}
					$model->{$key} = $relation_data;
				} else if(is_array($model->{$key})){
					if($model->$key != []) {
						$model->$key = $relation_helper($key, $model->$key);
					}
				}
			}
		}
		return $model;
	}

	/**
	 * getRelationType
	 *
	 * Gets the type of a relation or returns false
	 *
	 * @param (Object) (model) active record model
	 * @param (String) (key) the relation name
	 *
	 * @return (Mixed) returns the relation type (String) or false (Bool)
	 */ 
	private function getRelationType($model, $key)
	{
		$relations = $model->relations();
		if(isset($relations[$key])) {
			return $relations[$key][0];
		} 
		return false;
	}
			
}
