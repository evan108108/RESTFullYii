<?php
/**
 * MorrayBehavior (Model to Array Behavior)
 *
 * ver 1.0 | https://github.com/Arne-S/Morray
 *
 * Converts an AR model to an array, retaining associated relations.
 *
 **/

class MorrayBehavior extends CBehavior
{

  public function toArray($opts=array())
  {
      // option 'scenario', use a scenario to only display safe attributes
      // default: empty (no scenario)
      if (!isset($opts['scenario'])) $opts['scenario'] = "";

      // option 'relname', key name of the array holding the relations
      // use empty string to use no new array for relations
      if (!isset($opts['relname'])) $opts['relname'] = "";

      // get the owner class
      $owner = $this->getOwner();

      // check if the owner class is an AR class
      if (is_subclass_of($owner, 'CActiveRecord')) {

          // this function recursively loops all attributes and relations of a supplied object 
          // return the result by calling the recursive function
          return $this->loop($owner, $opts);
      }

      return false;
  }

  public function loop($obj, $opts = array(), $arr = array())
  {
      // store current object attribute values
      $attributes = $obj->attributes;

      // check if user supplied a scenario, and if this object has any rules
      if ((!empty($opts['scenario'])) && (is_array($obj->rules()))) {

          // loop the rules, search for a matching scenario
          foreach ($obj->rules() as $rule) {

              // check if this scenario name matches the supplied one
              if (isset($rule['on']) && $rule['on'] == $opts['scenario']) {

                  // check if this is the 'safe' option
                  if (isset($rule[1]) && $rule[1] == "safe") {

                      // put the safe attributes in an array
                      $safe_attributes = explode(",", $rule[0]);

                      // new array for putting only the values of the safe attributes
                      $attributes_values = array();

                      // loop the safe attributes
                      foreach ($safe_attributes as $safe_attribute) {

                          // trim in case there are any spaces
                          $safe_attribute = trim($safe_attribute);

                          // get attribute from object
                          $attributes_values[$safe_attribute] = $obj->{$safe_attribute};
                      }
                      // assign the safe attributes over the the array containing all the attributes
                      $attributes = $attributes_values;
                  }
              }
          }
      }

      //  assign the attributes of current rel to array
      $arr = $attributes;

      // get relations from current rel
      $relations = $obj->relations();

      // put the keys (rel names) in an array
      if ($rel_ids = array_keys($relations)) {

          // loop relations
          foreach ($rel_ids as $rel_id) {

              // check if sub rel is loaded (using scopes or 'with')
              if ($obj->hasRelated($rel_id)) {
                  $rel = $obj->{$rel_id};

                  // if sub rel is an object, there is 1 rel being returned
                  if (is_object($rel)) {

                      // if relname is supplied, use that to store the rel in
                      if (!empty($opts['relname'])) {
                          $arr[$opts['relname']][$rel_id] = $this->loop($rel, $opts, $arr);
                      }
                      else {
                          // if no relname is supplied, store directly in current array
                          $arr[$rel_id] = $this->loop($rel, $opts, $arr);
                      }
                  }

                  // if sub rel is an array, there are multiple relations being returned
                  if (is_array($rel)) {

                      // do a foreach on each sub rel
                      foreach ($rel as $sub_rel) {

                          // if relname is supplied, use that to store the rel in
                          if (!empty($opts['relname'])) {
                              $arr[$opts['relname']][$rel_id][] = $this->loop($sub_rel, $opts, $arr);
                          }
                          else {
                              // if no relname is supplied, store directly in current array
                              $arr[$rel_id][] = $this->loop($sub_rel, $opts, $arr);
                          }
                      }
                  }
              }
          }
      }
      return $arr;
  }
}