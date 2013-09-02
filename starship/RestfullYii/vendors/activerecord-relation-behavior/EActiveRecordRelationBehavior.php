<?php
/**
 * EActiveRecordRelationBehavior class file.
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @link http://yiiext.github.com/extensions/activerecord-relation-behavior/index.html
 * @copyright Copyright &copy; 2013 Carsten Brandt
 * @license https://github.com/yiiext/activerecord-relation-behavior/blob/master/LICENSE#L1
 */

/**
 * EActiveRecordRelationBehavior adds the possibility to handle activerecord relations more intuitively
 *
 * This extension is inspired by and puts together the awesomeness of all the yii extensions
 * that aim to improve saving of related records.
 * It allows you to assign related records especially for MANY_MANY relations more easily.
 *
 * For details on how to use it, please refer to the README.* files that ship with it.
 *
 * Limitations:
 * - currently does not support composite primary keys
 * - currently handles all existing relations, will add support for limitation shortly
 * - relations defined with 'through' are not supported yet (http://www.yiiframework.com/doc/guide/1.1/en/database.arr#relational-query-with-through)
 *
 * @property CActiveRecord $owner The owner AR that this behavior is attached to.
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @package yiiext.behaviors.activeRecordRelation
 */
class EActiveRecordRelationBehavior extends CActiveRecordBehavior
{
	/**
	 * @var bool set this to false if your dbms does not support transactions.
	 * This behavior will use transactions to save MANY_MANY tables to ensure consistent data.
	 * If you start a transaction yourself you can use it without configuring anything. This behavior will
	 * run inside your transaction without touching it.
	 */
	public $useTransaction=true;
	/** @var CDbTransaction */
	private $_transaction;

	/**
	 * Declares events and the corresponding event handler methods.
	 * @return array events (array keys) and the corresponding event handler methods (array values).
	 * @see CBehavior::events
	 */
	public function events()
	{
		return array(
			'onBeforeValidate'=>'beforeValidate',
			'onBeforeSave'=>'beforeSave',
			'onAfterSave'=>'afterSave',
//			'onBeforeDelete'=>'beforeDelete',
//			'onAfterDelete'=>'afterDelete',
		);
	}

	public function getTransaction()
	{
		return $_transaction;
	}
	
	public function setTransaction($transaction)
	{
		$this->_transaction = $transaction;
	}

	/**
	 * Responds to {@link CModel::onBeforeValidate} event.
	 * @throws CDbException
	 * @param CModelEvent $event event parameter
	 */
	public function beforeValidate($event)
	{
		foreach($this->owner->relations() as $name => $relation)
		{
			switch($relation[0]) // relation type such as BELONGS_TO, HAS_ONE, HAS_MANY, MANY_MANY
			{
				// BELONGS_TO: if the relationship between table A and B is one-to-many, then B belongs to A
				//             (e.g. Post belongs to User);
				// attribute of $this->owner has to be changed
				case CActiveRecord::BELONGS_TO:

					// when relation attribute will not be validated,
					// we do not need to populate it at this point, will do it in beforeSave()
					if (count($this->owner->getValidators($relation[2])) == 0)
						break;
					if (!$this->owner->hasRelated($name) || !$this->isRelationSupported($relation))
						break;

					$this->populateBelongsToAttribute($name, $relation);

				break;
			}
		}
	}

	/**
	 * Responds to {@link CActiveRecord::onBeforeSave} event.
	 * @param CModelEvent $event event parameter
	 */
	public function beforeSave($event)
	{
		// ensure transactions
		if ($this->useTransaction && $this->owner->dbConnection->currentTransaction===null)
			$this->_transaction=$this->owner->dbConnection->beginTransaction();

		foreach($this->owner->relations() as $name => $relation)
		{
			switch($relation[0]) // relation type such as BELONGS_TO, HAS_ONE, HAS_MANY, MANY_MANY
			{
				// BELONGS_TO: if the relationship between table A and B is one-to-many, then B belongs to A
				//             (e.g. Post belongs to User);
				// attribute of $this->owner has to be changed
				case CActiveRecord::BELONGS_TO:

					if (!$this->owner->hasRelated($name) || !$this->isRelationSupported($relation))
						break;

					$this->populateBelongsToAttribute($name, $relation);

				break;
			}
		}
	}

	/**
	 * Responds to {@link CActiveRecord::onAfterSave} event.
	 * @throws CDbException
	 * @param CModelEvent $event event parameter
	 */
	public function afterSave($event)
	{
		try {
			/** @var CDbCommandBuilder $commandBuilder */
			$commandBuilder=$this->owner->dbConnection->commandBuilder;

			foreach($this->owner->relations() as $name => $relation)
			{
				switch($relation[0]) // relation type such as BELONGS_TO, HAS_ONE, HAS_MANY, MANY_MANY
				{
					/* MANY_MANY: this corresponds to the many-to-many relationship in database.
					 *            An associative table is needed to break a many-to-many relationship into one-to-many
					 *            relationships, as most DBMS do not support many-to-many relationship directly.
					 */
					case CActiveRecord::MANY_MANY:

						if (!$this->owner->hasRelated($name) || !$this->isRelationSupported($relation))
							break;
						
						Yii::trace('updating MANY_MANY table for relation '.get_class($this->owner).'.'.$name,'system.db.ar.CActiveRecord');

						// get table and fk information
						list($relationTable, $fks)=$this->parseManyManyFk($name, $relation);

						// get pks of the currently related records
						$newPKs=$this->getNewManyManyPks($name);


						// 1. delete relation table entries for records that have been removed from relation
						// @todo add support for composite primary keys
						$criteria=new CDbCriteria();
						$criteria->addNotInCondition($fks[1], $newPKs)
								 ->addColumnCondition(array($fks[0]=>$this->owner->getPrimaryKey()));
						$commandBuilder->createDeleteCommand($relationTable, $criteria)->execute();


						// 2. add new entries to relation table
						// @todo add support for composite primary keys
						$oldPKs=$this->getOldManyManyPks($name);
						foreach($newPKs as $fk) {
							if (!in_array($fk, $oldPKs)) {
								$commandBuilder->createInsertCommand($relationTable, array(
									$fks[0] => $this->owner->getPrimaryKey(),
									$fks[1] => $fk,
								))->execute();
							}
						}

						// refresh relation data
						//$this->owner->getRelated($name, true); // will come back with github issue #4

					break;
					// HAS_MANY: if the relationship between table A and B is one-to-many, then A has many B
					//           (e.g. User has many Post);
					// HAS_ONE: this is special case of HAS_MANY where A has at most one B
					//          (e.g. User has at most one Profile);
					// need to change the foreign ARs attributes
					case CActiveRecord::HAS_MANY:
					case CActiveRecord::HAS_ONE:

						if (!$this->owner->hasRelated($name) || !$this->isRelationSupported($relation))
							break;

						Yii::trace(
							'updating '.(($relation[0]==CActiveRecord::HAS_ONE)?'HAS_ONE':'HAS_MANY').
							' foreign-key field for relation '.get_class($this->owner).'.'.$name,
							'system.db.ar.CActiveRecord'
						);

						$newRelatedRecords=$this->owner->getRelated($name, false);

						if ($relation[0]==CActiveRecord::HAS_MANY && !is_array($newRelatedRecords))
							throw new CDbException('A HAS_MANY relation needs to be an array of records or primary keys!');

						// HAS_ONE is special case of HAS_MANY, so we have array with one or no element
						if ($relation[0]==CActiveRecord::HAS_ONE) {
							if ($newRelatedRecords===null)
								$newRelatedRecords=array();
							else
								$newRelatedRecords=array($newRelatedRecords);
						}

						// get related records as objects and primary keys
						$newRelatedRecords=$this->primaryKeysToObjects($newRelatedRecords, $relation[1]);
						$newPKs=$this->objectsToPrimaryKeys($newRelatedRecords);

						// update all not anymore related records
						$criteria=new ECompositeDbCriteria();
						$criteria->addNotInCondition(CActiveRecord::model($relation[1])->tableSchema->primaryKey, $newPKs);
						// @todo add support for composite primary keys
						$criteria->addColumnCondition(array($relation[2]=>$this->owner->getPrimaryKey()));
						if (CActiveRecord::model($relation[1])->tableSchema->getColumn($relation[2])->allowNull) {
							CActiveRecord::model($relation[1])->updateAll(array($relation[2]=>null), $criteria);
						} else {
							CActiveRecord::model($relation[1])->deleteAll($criteria);
						}

						/** @var CActiveRecord $record */
						foreach($newRelatedRecords as $record) {
							// only save if relation did not exist
							// @todo add support for composite primary keys
							if ($record->{$relation[2]}===null || $record->{$relation[2]} !=  $this->owner->getPrimaryKey()) {
								$record->saveAttributes(array($relation[2] => $this->owner->getPrimaryKey()));
							}
						}

					break;
				}
			}
			// commit internal transaction if one exists
			if ($this->_transaction!==null)
				$this->_transaction->commit();

		} catch(Exception $e) {
			// roll back internal transaction if one exists
			if ($this->_transaction!==null)
				$this->_transaction->rollback();
			// re-throw exception
			throw $e;
		}
	}

	/**
	 * Populates the BELONGS_TO relations attribute with the pk of the related model.
	 * @param string $name the relation name
	 * @param array $relation the relation config array
	 */
	protected function populateBelongsToAttribute($name, $relation)
	{
		$pk=null;
		if (($related=$this->owner->getRelated($name, false))!==null) {
			if (is_object($related)) {
				/** @var CActiveRecord $related */
				//if ($related->isNewRecord)
					//throw new CDbException('You can not save a record that has new related records!');
				$pk=$related->getPrimaryKey();
			} else {
				$pk=$related;
			}
		}

		// @todo add support for composite primary keys
		if (!is_array($pk)) {
			$this->owner->setAttribute($relation[2], $pk);
		}
	}

	/**
	 * do not do anything with relations defined with 'through' or have limiting 'condition'/'scopes' defined
	 *
	 * @param array $relation
	 * @return bool
	 */
	protected function isRelationSupported($relation)
	{
		// @todo not sure about 'together', also check for joinType
		return !isset($relation['on']) &&
			   !isset($relation['through']) &&
			   !isset($relation['condition']) &&
			   !isset($relation['group']) &&
			   !isset($relation['join']) &&
			   !isset($relation['having']) &&
			   !isset($relation['limit']) && // @todo not sure what to do if limit/offset is set
			   !isset($relation['offset']) &&
			   !isset($relation['scopes']);
	}

	/**
	 * converts an array of AR objects or primary keys to only primary keys
	 *
	 * @throws CDbException
	 * @param CActiveRecord[] $records
	 * @return array
	 */
	protected function objectsToPrimaryKeys($records)
	{
		$pks=array();
		foreach($records as $record) {
			//if (is_object($record) && $record->isNewRecord)
			//throw new CDbException('You can not save a record that has new related records!');
			

			$pks[]=is_object($record) ? $record->getPrimaryKey() : $record;
		}
		return $pks;
	}

	/**
	 * converts an array of AR objects or primary keys to only AR objects
	 *
	 * @throws CDbException
	 * @param CActiveRecord[] $pks
	 * @param string $className classname of the ARs to instantiate
	 * @return array
	 */
	protected function primaryKeysToObjects($pks, $className)
	{
		// @todo increase performance by running one query with findAllByPk()
		$records=array();
		foreach($pks as $pk) {
			$record=$pk;
			if (is_object($record) && $record->isNewRecord)
				throw new CDbException('You can not save a record that has new related records!');
			if (!is_object($record))
				$record=CActiveRecord::model($className)->findByPk($pk);
			if ($record===null)
				throw new CDbException('Related record with primary key "'.print_r($pk,true).'" does not exist!');

			$records[]=$record;
		}
		return $records;
	}

	/**
	 * returns all primary keys of the currently assigned records
	 *
	 * @throws CDbException
	 * @param string $relationName name of the relation
	 * @return array
	 */
	protected function getNewManyManyPks($relationName)
	{
		$newRelatedRecords=$this->owner->getRelated($relationName, false);
		if (!is_array($newRelatedRecords)) {
			throw new CDbException('A MANY_MANY relation needs to be an array of records or primary keys!');
		}
		// get new related records primary keys
		return $this->objectsToPrimaryKeys($newRelatedRecords);
	}

	/**
	 * returns all primary keys of the old assigned records(in database)
	 *
	 * @param string $relationName name of the relation
	 * @return array
	 */
	protected function getOldManyManyPks($relationName)
	{
		// @todo improve performance by doing simple select query instead of using AR
		$tmpAr=CActiveRecord::model(get_class($this->owner))->findByPk($this->owner->getPrimaryKey());
		return $this->objectsToPrimaryKeys($tmpAr->getRelated($relationName, true));
	}

	/**
	 * parses the foreign key definition of a MANY_MANY relation
	 *
	 * the first 7 lines are copied from CActiveFinder:561-568
	 * https://github.com/yiisoft/yii/blob/2353e0adf98c8a912f0faf29cc2558c0ccd6fec7/framework/db/ar/CActiveFinder.php#L561
	 *
	 * @todo this method should be removed and using code should implement solution of https://github.com/yiisoft/yii/issues/508 when it is fixed
	 *
	 * @throws CDbException
	 * @param string $name name of the relation
	 * @param array $relation relation definition
	 * @return array ($joinTable, $fks)
	 *               joinTable is the many-many-relation-table
	 *               fks are primary key of that table defining the relation
	 */
	protected function parseManyManyFk($name, $relation)
	{
		if(!preg_match('/^\s*(.*?)\((.*)\)\s*$/',$relation[2],$matches))
			throw new CDbException(Yii::t('yii','The relation "{relation}" in active record class "{class}" is specified with an invalid foreign key. The format of the foreign key must be "joinTable(fk1,fk2,...)".',
				array('{class}'=>get_class($this->owner),'{relation}'=>$name)));
		if(($joinTable=$this->owner->dbConnection->schema->getTable($matches[1]))===null)
			throw new CDbException(Yii::t('yii','The relation "{relation}" in active record class "{class}" is not specified correctly: the join table "{joinTable}" given in the foreign key cannot be found in the database.',
				array('{class}'=>get_class($this->owner), '{relation}'=>$name, '{joinTable}'=>$matches[1])));
		$fks=preg_split('/\s*,\s*/',$matches[2],-1,PREG_SPLIT_NO_EMPTY);

		return array($joinTable, $fks);
	}
}

/**
 * Extension of CDbCriteria that adds support for composite pks to conditions
 *
 * @todo CDbCommandBuilder::createInCondition() supports composite pk, check if we can use it
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @package yiiext.behaviors.activeRecordRelation
 */
class ECompositeDbCriteria extends CDbCriteria
{
	/**
	 * Adds support for composite keys if first param is array
	 *
	 * for further details {@see CDbCriteria::addInCondition}
	 *
	 * @param $column
	 * @param $values
	 * @param string $operator
	 * @return CDbCriteria
	 */
	public function addInCondition($column,$values,$operator='AND')
	{
		if (is_array($column)) {
			return $this->addCondition(
				$this->createCompositeInCondition($column, $values),
				$operator
			);
		}
		return parent::addInCondition($column,$values,$operator);
	}

	/**
	 * Adds support for composite keys if first param is array
	 *
	 * for further details {@see CDbCriteria::addNotInCondition}
	 *
	 * @param $column
	 * @param $values
	 * @param string $operator
	 * @return CDbCriteria
	 */
	public function addNotInCondition($column,$values,$operator='AND')
	{
		if (is_array($column)) {
			return $this->addCondition(
				'NOT '.$this->createCompositeInCondition($column, $values),
				$operator
			);
		}
		return parent::addNotInCondition($column,$values,$operator);
	}

	private function createCompositeInCondition($columns,$values)
	{
		if(count($values)<1)
			return '0=1'; // 0=1 is used because in MSSQL value alone can't be used in WHERE

		$sql1 = array();
		foreach($values as $value) {
			$sql2 = array();
			foreach($columns as $column) {
				$sql2[] = $column.'='.self::PARAM_PREFIX.self::$paramCount;
				$this->params[self::PARAM_PREFIX.self::$paramCount++]=$value[$column];
			}
			$sql1[] = implode(' AND ',$sql2);
		}

		return '(('.implode(') OR (',$sql1).'))';
	}
}
