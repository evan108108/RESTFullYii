<?php
/**
 * MorrayBehavior (Model to Array Behavior)
 *
 * ver 1.0 beta
 *
 * Converts an AR model to an array, retaining associated relations.
 *
 **/

class MorrayBehavior extends CBehavior
{

  public function toArray($options=array())
  {
      //option 'scenario', use a scenario to only display safe attributes
      //default: empty (no scenario)
      if (!isset($options['scenario'])) {
          $options['scenario'] = "";
      }

      //option 'relname', key name of the array holding the relations
      //       use empty string to use no new array for relations
      //default: "_rel"
      if (!isset($options['relname'])) {
          $options['relname'] = "_rel";
      }

      //get the owner class
      $owner = $this->getOwner();

      //check if the owner class is an AR class
      if (is_subclass_of($owner, 'CActiveRecord')) {

          //this function recursively loops all attributes and relations of a supplied object 

          //return the result by calling the recursive function
          return $this->loop($owner, $options);
      }

      return false;
  }

  public function loop($obj, $options = array(), $arr = array())
  {
      //store current object attribute values
      $attributes = $obj->attributes;

      //check if user supplied a scenario, and if this object has any rules
      if ((!empty($options['scenario'])) && (is_array($obj->rules()))) {

          //loop the rules, search for a matching scenario
          foreach ($obj->rules() as $rule) {

              //check if this scenario name matches the supplied one
              if ((isset($rule['on']) && ($rule['on'] == $options['scenario']))) {

                  //check if this is the 'safe' option
                  if ((isset($rule[1])) && ($rule[1] == "safe")) {

                      //put the safe attributes in an array
                      $safe_attributes = explode(",", $rule[0]);

                      //new array for putting only the values of the safe attributes
                      $attributes_values = array();

                      //loop the safe attributes
                      foreach ($safe_attributes as $safe_attribute) {

                          //trim in case there are any spaces
                          $safe_attribute = trim($safe_attribute);

                          //if this safe attribute contains a value, save it in the new attribute array
                          if (isset($attributes[$safe_attribute])) {
                              $attributes_values[$safe_attribute] = $attributes[$safe_attribute];
                          }
                      }
                      //assign the safe attributes over the the array containing all the attributes
                      $attributes = $attributes_values;
                  }
              }
          }
      }

      // assign the attributes of current relation to array
      $arr = $attributes;

      //get relations from current relation
      $relations = $obj->relations();

      //put the keys (relation names) in an array
      if ($relation_ids = array_keys($relations)) {

          //loop relations
          foreach ($relation_ids as $relation_id) {

              //check if sub relation is loaded (using scopes or 'with')
              if ($obj->hasRelated($relation_id)) {
                  $relation = $obj->{$relation_id};

                  //if sub relation is an object, there is 1 relation being returned
                  if (is_object($relation)) {

                      //if relname is supplied, use that to store the relation in
                      if (!empty($options['relname'])) {
                          $arr[$options['relname']][$relation_id] = $this->loop($relation, $options, $arr);
                      }
                      else {
                          //if no relname is supplied, store directly in current array
                          $arr[$relation_id] = $this->loop($relation, $options, $arr);
                      }
                  }

                  //if sub relation is an array, there are multiple relations being returned
                  if (is_array($relation)) {

                      //do a foreach on each sub relation
                      foreach ($relation as $sub_relation) {

                          //if relname is supplied, use that to store the relation in
                          if (!empty($options['relname'])) {
                              $arr[$options['relname']][$relation_id][] = $this->loop($sub_relation, $options, $arr);
                          }
                          else {
                              //if no relname is supplied, store directly in current array
                              $arr[$relation_id][] = $this->loop($sub_relation, $options, $arr);
                          }
                      }
                  }
              }
          }
      }
      return $arr;
  }

}

