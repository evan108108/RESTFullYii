<?php

class ERestHelperScopes extends CActiveRecordBehavior {

    public function limit($limit) {
        $this->Owner->getDbCriteria()->mergeWith(array(
            'limit' => $limit
        ));
        return $this->Owner;
    }

    public function offset($offset) {
        $this->Owner->getDbCriteria()->mergeWith(array(
            'offset' => $offset
        ));
        return $this->Owner;
    }

    public function orderBy($field, $dir = 'ASC') {
        if (empty($field))
            return $this->Owner;

        if (!is_array($orderListItems = CJSON::decode($field))) {
            $this->Owner->getDbCriteria()->mergeWith(array(
                'order' => $this->getSortSQL($field, $dir)
            ));
            return $this->Owner;
        } else {
            $orderByStr = "";
            foreach ($orderListItems as $orderListItem)
                $orderByStr .= ((!empty($orderByStr)) ? ", " : "") . $this->getSortSQL($orderListItem['property'], $orderListItem['direction']);

            $this->Owner->getDbCriteria()->mergeWith(array(
                'order' => $orderByStr
            ));
            return $this->Owner;
        }
    }

    public function filter($filter) {
        if (empty($filter))
            return $this->Owner;

        $props = array();

        if (!is_array($filter))
            $filterItems = CJSON::decode($filter);
        else
            $filterItems = $filter;

        $query = "";
        $params = array();
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
        if (empty($query))
            return $this->Owner;

        $query .= ")";

        $this->Owner->getDbCriteria()->mergeWith(array(
            'condition' => $query, 'params' => $params
        ));
        return $this->Owner;
    }

    private function getFilterCType($property) {
        if ($this->Owner->hasAttribute($property))
            return $this->Owner->metaData->columns[$property]->type;

        return 'text';
    }

    private function getFilterAlias($property) {
        return $this->Owner->getTableAlias(false, false);
    }

    private function getSortSQL($field, $dir = 'ASC') {
        return $this->Owner->getTableAlias(false, false) . ".$field $dir";
    }

}
