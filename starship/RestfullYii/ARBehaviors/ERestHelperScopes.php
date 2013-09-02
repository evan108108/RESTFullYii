<?php
/**
 * ERestHelperScopes
 *
 * Helper scopes for active record models
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/ARBehaviors
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class ERestHelperScopes extends CActiveRecordBehavior 
{
	/**
	 * limit
	 *
	 * sets the number of records to return
	 * 
	 * @param (Int) (limit) the limit
	 *
	 * @return (Object) returns the model for chaining
	 */ 
	public function limit($limit) 
	{
		$this->Owner->getDbCriteria()->mergeWith([
			'limit' => $limit
		]);
		return $this->Owner;
	}

	/**
	 * offset
	 *
	 * sets the offset of results
	 *
	 * @param (Int) (offset) the offset
	 *
	 * @return (Object) returns the model for chaining
	 */ 
	public function offset($offset) 
	{
		$this->Owner->getDbCriteria()->mergeWith([
			'offset' => $offset
		]);
		return $this->Owner;
	}

	/**
	 * orderBy
	 *
	 * tells active record how to order results
	 *
	 * @param (String) field or json string
	 * @param (String) direction to order
	 *
	 * @return (Object) returns the model for chaining
	 */
	public function orderBy($field, $dir = 'ASC') 
	{
		if (empty($field)) {
			return $this->Owner;
		}

		if (!is_array($orderListItems = CJSON::decode($field))) {
			$this->Owner->getDbCriteria()->mergeWith([
				'order' => $this->getSortSQL($field, $dir)
			]);
			return $this->Owner;
		} else {
			$orderByStr = "";
			foreach ($orderListItems as $orderListItem)
				$orderByStr .= ((!empty($orderByStr)) ? ", " : "") .
				$this->getSortSQL($orderListItem['property'], $orderListItem['direction']);

			$this->Owner->getDbCriteria()->mergeWith([
				'order' => $orderByStr
			]);
			return $this->Owner;
		}
	}

	/**
	 * filter
	 *
	 * filter result set based on criteria
	 *
	 * @param (String) JSON string to filter by
	 *
	 * @return (Object) returns the model for chaining
	 */
	public function filter($filter) 
	{
		if (empty($filter)) {
			return $this->Owner;
		}

		$props = [];

		if (!is_array($filter)) {
			$filterItems = CJSON::decode($filter);
		}
		else {
			$filterItems = $filter;
		}

		$query = "";
		$params = [];
		foreach ($filterItems as $filterItem) {
			if (!is_null($filterItem['property'])) {
				$c = 0;
				$prop = $filterItem['property'] . $c;
				while (in_array($prop, $props)) {
					$c++;
					$prop = $filterItem['property'] . $c;
				}
				$props[] = $prop;
				$value = $filterItem['value'];
				$field = $filterItem['property'];
				$cType = $this->getFilterCType($field);

				if (array_key_exists('operator', $filterItem) || is_array($value)) {
					if (!array_key_exists('operator', $filterItem)) {
						$operator = 'in';
					} else {
						$operator = strtolower($filterItem['operator']);
					}
					switch ($operator) {
						case 'not in':
						case 'in':
							$paramsStr = '';
							foreach ((array) $value as $index => $item) {
								$paramsStr.= (empty($paramsStr)) ? '' : ', ';
								$params[(":" . $prop . '_' . $index)] = $item;
								$paramsStr.= (":" . $prop . '_' . $index);
							}

							$compare = " " . strtoupper($operator) . " ({$paramsStr})";
							break;
						case 'like':
							$compare = " LIKE :" . $prop;
							$params[(":" . $prop)] = '%' . $value . '%';
							break;
						case '=' :
						case '<' :
						case '<=':
						case '>' :
						case '>=':
							$compare = " $operator :" . $prop;
							$params[(":" . $prop)] = $value;
							break;
						case '!=':
						case '<>':
							$compare = " <> :" . $prop;
							$params[(":" . $prop)] = $value;
							break;
						default :
							$compare = " = :" . $prop;
							$params[(":" . $prop)] = $value;
							break;
					}
				} else {
					if ($cType == 'text' || $cType == 'string') {
						$compare = " LIKE :" . $prop;
						$params[(":" . $prop)] = '%' . $value . '%';
					} else {
						$compare = " = :" . $prop;
						$params[(":" . $prop)] = $value;
					}
				}
				$query .= (empty($query) ? "(" : " AND ") . $this->getFilterAlias($field) . '.' . $field . $compare;
			}
		}
		if (empty($query)) {
			return $this->Owner;
		}

		$query .= ")";

		$this->Owner->getDbCriteria()->mergeWith([
			'condition' => $query, 'params' => $params
		]);
		return $this->Owner;
	}

	/**
	 * getFilterCType
	 *
	 * returns the type of the property being filtered
	 *
	 * @param (String) (property) the filter to get type of
	 *
	 * @return (String) the filter type
	 */ 
	private function getFilterCType($property) 
	{
		if ($this->Owner->hasAttribute($property)) {
			return $this->Owner->metaData->columns[$property]->type;
		}
		return 'text';
	}

	/**
	 * getFilterAlias
	 *
	 * returns the alias of the table in query
	 *
	 * @param (String) (property) takes property
	 * @todo remove property param as it is not used
	 *
	 * @return (String) the table alias
	 */ 
	private function getFilterAlias($property=null) 
	{
		return $this->Owner->getTableAlias(false, false);
	}

	/**
	 * getSortSQL
	 *
	 * returns the sql for sorting
	 *
	 * @param (String) (field) the field name to sort by
	 * #param (String) (dir) the direction to sor in
	 *
	 * @return (String) the sort SQL
	 */ 
	private function getSortSQL($field, $dir = 'ASC') 
	{
		return $this->Owner->getTableAlias(false, false) . ".$field $dir";
	}

}
