<?php
Yii::import('RestfullYii.components.iERestRequestReader');

/**
 * ERestSubresourceHelper
 * 
 * Helper methods for manipulating subresource models used in request
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
class ERestSubresourceHelper implements iERestResourceHelper
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
	 * isSubresource
	 *
	 * Checks if resource is in fact a subresource
	 *
	 * @param (Object) (model) AR model
	 * @param (String) (subresource_name) name of the subresource to check
	 *
	 * @return (Bool) true if this is a subresource; false if not
	 */ 
	public function isSubresource($model, $subresource_name, $verb)
	{	
		$emitRest = $this->getEmitter();

		return $emitRest(ERestEvent::REQ_IS_SUBRESOURCE, [$model, $subresource_name, $verb]);
	}

	/**
	 * getSubresourceClassName
	 *
	 * gets the class name of the subresource
	 *
	 * @param (Object) (model) AR model
	 * @param (String) (subresource_name) name of the subresource
	 *
	 * @return (String) subresource model name
	 */ 
	public function getSubresourceClassName($model, $subresource_name)
	{
		return $model->getActiveRelation($subresource_name)->className;
	}

	/**
	 * getSubresourceCount
	 *
	 * gets the count of subresource items
	 *
	 * @param (Object) (model) AR model
	 * @param (String) (subresource_name) name of the subresource
	 * @param (Mixed/Int) (subresource_pk) the primary key of the subresource
	 *
	 * @return (Int) the count of subresource items
	 */ 
	public function getSubresourceCount($model, $subresource_name, $subresource_pk=null)
	{
		$pk = $model->getPrimaryKey();;
		$subresourceAR = $this->getSubresourceAR($model, $subresource_name);
		if( is_null($subresource_pk) ) {
			$model_name = get_class($model);
			$new_relation_name = "_" . $subresourceAR->active_relation->className . "Count";
			$model->metaData->addRelation($new_relation_name, [
				constant($model_name.'::STAT'),
				$subresourceAR->active_relation->className,
				$subresourceAR->active_relation->foreignKey
			]);
			$model = $model->with($new_relation_name)->findByPk($pk);	
			return $model->$new_relation_name;
		} 
		return !is_null( 
			$model->with($subresource_name)->findByPk($pk, array('condition'=>"$subresource_name.{$this->getSubRecourcesPKAttribute($subresourceAR)}=$subresource_pk")) 
		)? 1: 0;
	}

	/**
	 * getSubresourceAR
	 *
	 * gets the active record info about a given subresource
	 *
	 * @param (Object) (model) AR model
	 * @param (String) (subresource_name) name of the subresource
	 *
	 * @return (Object) subresource relation info
	 */ 
	protected function getSubresourceAR($model, $subresource_name)
	{	
		$emitRest = $this->getEmitter();

		$subresource_relation = $model->getActiveRelation($subresource_name);
		$sub_resource_model = $emitRest(ERestEvent::MODEL_ATTACH_BEHAVIORS, new $subresource_relation->className);

		return (object) [
			'active_relation'	=> $subresource_relation,
			'model'						=> $sub_resource_model,
		];
	}

	/**
	 * getSubresource
	 *
	 * @param (Object) (model) AR model
	 * @param (String) (subresource_name) name of the subresource
	 * @param (Mixed/Int) (subresource_pk) the primary key of the subresource
	 *
	 * @return (Array) an array containing the subresource AR models
	 */ 
	public function getSubresource($model, $subresource_name, $subresource_pk=null)
	{
		$pk = $model->getPrimaryKey();	
		$emitRest = $this->getEmitter();

		if( is_null($subresource_pk) ) {
			$model = $emitRest(ERestEvent::MODEL_ATTACH_BEHAVIORS, $model);
			$results = $model
				->with($subresource_name)
				->limit($emitRest(ERestEvent::MODEL_LIMIT))
				->offset($emitRest(ERestEvent::MODEL_OFFSET))
				->findByPk($pk, ['together'=>true]);
			return !is_null($results->$subresource_name)? $results->$subresource_name: [];
		}
		$results = $model
			->with($subresource_name)
			->findByPk($pk, ['condition'=>"$subresource_name.{$this->getSubRecourcesPKAttribute($this->getSubresourceAR($model, $subresource_name))}=:subresource_pk", 'params'=>[':subresource_pk' => $subresource_pk]]);
		return !is_null($results->$subresource_name)? $results->$subresource_name: [];
	}

	/**
	 * getSubresourceMeta
	 *
	 * @param (Object) (model) AR model
	 * @param (String) (subresource_name) name of the subresource
	 *
	 * @return (Array) returns an array containing an instance of the Table Schema Object 
	 * for the ManyMany Join Table
	 */
	public function getSubresourceMeta($model, $subresource_name)
	{
		$emitRest = $this->getEmitter();
		$model = $emitRest(ERestEvent::MODEL_ATTACH_BEHAVIORS, $model);
		return $model->parseManyManyFk($subresource_name, $model->relations()[$subresource_name]);
	}

	/**
	 * putSubresourceHelper
	 *
	 * puts a subresource to an existing AR model
	 *
	 * @param (Object) (model) AR model
	 * @param (String) (subresource_name) name of the subresource
	 * @param (Mixed/Int) (subresource_pk) the primary key of the subresource
	 *
	 * @return (Bool) returns true if saved; false if save fails
	 */
	public function putSubresourceHelper($model, $subresource_name, $subresource_id)
	{
		list($relation_table, $fks) = $this->getSubresourceMeta($model, $subresource_name);

		if($this->saveSubresource($model, $subresource_id, $relation_table, $fks) > 0) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * saveSubresource
	 *
	 * saves a given subresource to a given model
	 *
	 * @param (Object) (model) AR model
	 * @param (Mixed/Int) (subresource_pk) the primary key of the subresource
	 * @param (String) (relation_table) the name of the relation table
	 * @param (Array) (fks) the names of the fields that comprise the foreign key
	 *
	 * return (Bool) true if save success; false if save unsuccessful
	 */
	public function saveSubresource($model, $subresource_id, $relation_table, $fks)
	{
		return $model->dbConnection->commandBuilder->createInsertCommand($relation_table, array(
			$fks[0] => $model->getPrimaryKey(),
			$fks[1] => $subresource_id,
		))->execute();
	}

	/**
	 * deleteSubResource
	 *
	 * deletes a subresource from a given model
	 *
	 * @param (Object) (model) AR model
	 * @param (Object) (subresource) AR model of the subresource
	 * @param (Mixed/Int) (subresource_pk) the primary key of the subresource
	 *
	 * @return (Bool) returns true if subresource is deleted; false if it can not be deleted
	 */
	public function deleteSubResource($model, $subresource, $subresource_id)
	{
		list($relation_table, $fks) = $this->getSubresourceMeta($model, $subresource);
		$criteria=new CDbCriteria();
		$criteria->addColumnCondition(array(
			$fks[0]=>$model->getPrimaryKey(),
			$fks[1]=>$subresource_id
		));
		if($model->dbConnection->commandBuilder->createDeleteCommand($relation_table, $criteria)->execute()) {
			return true;
		}
		return false;
	}

	/**
	 * getSubRecourcesPKAttribute
	 *
	 * returns the Primary Key Attribute of the subresource
	 *
	 * @param (Object) (Active relation) AR model
	 *
	 * @return (String) Primary Key Attribute Name of the subresource model
	 */
	public function getSubRecourcesPKAttribute($subresourceAR)
	{
		return call_user_func(function($srPK){
			return $srPK::model()->tableSchema->primaryKey; 
		}, $subresourceAR->active_relation->className);
	}
}

