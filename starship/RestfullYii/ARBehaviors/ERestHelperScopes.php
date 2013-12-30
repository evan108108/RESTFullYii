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
			foreach ($orderListItems as $orderListItem) {
				$alias = $this->Owner->getTableAlias(false, false);
				$property = $orderListItem['property'];
				$with = [];
				if(strpos($property, '.')) {
					$prop_parts = explode('.', $property);
					$alias = $prop_parts[0];
					$property = $prop_parts[1];
					if(!isset($with[$alias])) {
						$with[$alias] = [];
						$with[$alias]['order'] = '';
					}
					$with[$alias]['order'] = (!empty($with[$alias]['order'])? ', ': '') . $this->getSortSQL($property, $orderListItem['direction'], $alias);
				} else {
					$orderByStr .= ((!empty($orderByStr)) ? ", " : "") . $this->getSortSQL($property, $orderListItem['direction'], $alias);
				}
			}
			$this->Owner->getDbCriteria()->mergeWith([
				'order' => $orderByStr,
				'with' => $with
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
		$related_params = [];
		$related_query = [];
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

				if(strpos($field, '.')===false) {
					list($compare, $params) = $this->constructFilter($field, $prop, $value, $cType, $filterItem, $params);
					$query .= (empty($query) ? "(" : " AND ") . $this->getFilterAlias($field) . '.' . $field . $compare;
				} else {
					list($relation, $property) = explode('.', $field);
					if(!isset($related_params[$relation])) {
						$related_params[$relation] = [];
					}
					$prop = str_replace('.', '_', $prop);
					list($compare, $related_params[$relation]) = $this->constructFilter($field, $prop, $value, $cType, $filterItem, $related_params[$relation]);
					if(!isset($related_query[$relation])) {
						$related_query[$relation] = '';
					}
					$related_query[$relation] .= (empty($related_query[$relation]) ? "(" : " AND ") . $field . $compare;
				}
			}
		}
		if (empty($query) && empty($related_query)) {
			return $this->Owner;
		}

		if(!empty($query)) {
			$query .= ")";
		}

		$with = [];

		foreach($related_query as $relation=>$val) {
			$with[$relation] = [
				'condition' => $related_query[$relation] . ')',
				'params' => $related_params[$relation],
				'joinType' => 'INNER JOIN',
				'together'=>true,
			];
		}

		if(!empty($query)) {
			$this->Owner->getDbCriteria()->mergeWith([
				'condition' => $query, 'params' => $params
			]);
		}

		if(count($with) > 0) {
			$this->Owner->getDbCriteria()->mergeWith([
				'with' => $with
			]);
		}

		return $this->Owner;
	}

	/**
	 * constructFilter
	 *
	 * Helper method that builds conditions and params for a given filter object
	 *
	 * @param (String) (field) name of the field to apply filter to
	 * @param (String) (prop) special unique name of the property to be used as a replacement param key
	 * @param (String) (value) the value to use for filtering results
	 * @param (String) (cType) the type of the filed (text, int, ect)
	 * @param (Array) (filterItem) the filter object
	 * @param (Array) (params) Array of bind params for the query
	 *
	 * @return (Array) returns both the compare statement and the params array
	 */ 
	private function constructFilter($field, $prop, $value, $cType, $filterItem, $params)
	{
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
		return [$compare, $params];
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
	private function getSortSQL($field, $dir = 'ASC', $alias=false) 
	{
		return (($alias)? $alias: $this->Owner->getTableAlias(false, false)) . ".$field $dir";
	}

}
