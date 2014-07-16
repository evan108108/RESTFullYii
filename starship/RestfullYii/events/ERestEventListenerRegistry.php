<?php
/**
 * ERestEventListenerRegistry
 * 
 * Initializes all event handlers / listeners
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/events
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 *
 * @property Callable $onRest
 */
class ERestEventListenerRegistry
{
	private $onRest;

	CONST REQ_TYPE_CORS = 1;
	CONST REQ_TYPE_USERPASS = 2;
	CONST REQ_TYPE_AJAX = 3;

	/**
	 * __construct
	 *
	 * takes the onRest callable used to register event handlers / listeners
	 *
	 * @params (Callable) (onRest) callable for handler registration
	 */
	function __construct(Callable $onRest)
	{
		$this->onRest = $onRest;
	}

	/**
	 * run
	 *
	 * registers all event handlers
	 */
	public final function run()
	{
		$onRest = $this->onRest;

		/**
		 * config.dev.flag
		 *
		 * return true to set develop mode; false to turn of develop mode
		 *
		 * @return (bool) true by default
		 */
		$onRest(ERestEvent::CONFIG_DEV_FLAG, function() {
			return true;
		});

		/**
		 * config.application.id
		 *
		 * returns the app id that is applied to header vars (username, password)
		 *
		 * @return (String) default is 'REST'
		 */
		$onRest(ERestEvent::CONFIG_APPLICATION_ID, function() {
			return 'REST';
		});

		/**
		 * req.event.logger
		 *
		 * @param (String) (event) the event to log
		 * @param (String) (category) the log category
		 * @param (Array) (ignore) Events to ignore logging
		 *
		 * @return (Array) the params sent into the event logger
		 * 
		 */
		$onRest(ERestEvent::REQ_EVENT_LOGGER, function($event, $category='application', $ignore=[]) {
			if(!isset($ignore[$event])) {
				Yii::trace($event, $category);
			}
			return [$event, $category, $ignore];
		});

		/**
		 *
		 * req.disable.cweblogroute
		 *
		 * this is only relivent if you have enabled CWebLogRoute in your main config
		 *
		 * @return (Bool) true (default) to disable CWebLogRoute, false to allow
		 */
		$onRest(ERestEvent::REQ_DISABLE_CWEBLOGROUTE, function() {
			return true;
		});

		/**
		 * req.exception
		 *
		 * Error handler called when an Exception is thrown
		 * Used to render response to the client
		 *
		 * @param (Int) (errorCode) the http status code
		 * @param (String) the error message
		 */
		$onRest(ERestEvent::REQ_EXCEPTION, function($errorCode, $message=null) {
			return $this->renderJSON([
				'type'			=> 'error',
				'success'		=> false,
				'message'		=> (is_null($message)? $this->getHttpStatus()->message: $message),
				'errorCode' => $errorCode,
			]);
		});

		/**
		 * req.auth.type
		 *
		 * @return (Int) The request authentication type which may be 'USERPASS' (2), 'AJAX' (3) or 'CORS' (1)
		 */
		$onRest(ERestEvent::REQ_AUTH_TYPE, function($application_id) {
			if(isset($_SERVER['HTTP_X_'.$application_id.'_CORS']) || (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS')) {
				return ERestEventListenerRegistry::REQ_TYPE_CORS;
			} else if(isset($_SERVER['HTTP_X_'.$application_id.'_USERNAME']) && isset($_SERVER['HTTP_X_'.$application_id.'_PASSWORD'])) {
				return ERestEventListenerRegistry::REQ_TYPE_USERPASS;
			} else {
				return ERestEventListenerRegistry::REQ_TYPE_AJAX;
			}
		});

		/**
		 * req.auth.ajax.user
		 *
		 * Used to validate an ajax user
		 * That is requests originating from JavaScript
		 * By default all logged-in users will be granted access
		 * everyone else will be denied
		 * You should most likely over ride this
		 *
		 * @return (Bool) return true to grant access; false to deny access
		 */ 
		$onRest(ERestEvent::REQ_AUTH_AJAX_USER, function() {
			if(Yii::app()->user->isGuest) {
				return false;
			}
			return true;
		});

		/**
		 * req.cors.access.control.allow.origin
		 *
		 * Used to validate a CORS request
		 *
		 * @return (Array) return a list of domains (origin) allowed access
		 */
		$onRest(ERestEvent::REQ_CORS_ACCESS_CONTROL_ALLOW_ORIGIN, function() {
			return [];
		});

		/**
		 * req.cors.access.control.allow.methods
		 *
		 * Used by CORS request to indicate the http methods (verbs) 
		 * that can be used in the actual request
		 *
		 * @return (Array) List of http methods allowed via CORS
		 */
		$onRest(ERestEvent::REQ_CORS_ACCESS_CONTROL_ALLOW_METHODS, function() {
			return ['GET', 'POST'];
		});

		/**
		 * req.cors.access.control.allow.headers
		 *
		 * Used by CORS request to indicate which custom headers are allowed in a request
		 *
		 * @return (Array) List of allowed headers
		 */ 
		$onRest(ERestEvent::REQ_CORS_ACCESS_CONTROL_ALLOW_HEADERS, function($application_id) {
			return ["X_{$application_id}_CORS"];
		});

		/**
		 * req.cors.access.control.max.age
		 *
		 * Used in a CORS request to indicate how long the response can be cached, 
		 * so that for subsequent requests, within the specified time, no preflight request has to be made
		 *
		 * @return (Int) time in seconds
		 */
		$onRest(ERestEvent::REQ_CORS_ACCESS_CONTROL_MAX_AGE, function() {
			return 3628800;
		});

		/**
		 * req.auth.cors
		 *
		 * Used to authorize a given CORS request
		 *
		 * @param (Array) (allowed_origins) list of allowed remote origins
		 *
		 * @return (Bool) true to allow access and false to deny access
		 */
		$onRest(ERestEvent::REQ_AUTH_CORS, function ($allowed_origins) {
			if((isset($_SERVER['HTTP_ORIGIN'])) && (( array_search($_SERVER['HTTP_ORIGIN'], $allowed_origins)) !== false )) {
				return true;
			}
			return false;	
		});

		/**
		 * req.auth.https.only
		 *
		 * return true to restrict to https;
		 * false to allow http or https
		 *
		 * @return (Bool) default is false
		 */ 
		$onRest(ERestEvent::REQ_AUTH_HTTPS_ONLY, function() {
			return false;
		});

		/**
		 * req.auth.uri
		 *
		 * return true to allow access to a given uri;
		 * false to deny access to a given uri;
		 *
		 * @return (bool) default is true
		 */ 
        $onRest(ERestEvent::REQ_AUTH_URI, function($uri, $verb) {
            return true;
        });

		/**
		 * req.auth.username
		 *
		 * This is the username used to grant access to non-ajax users
		 * At a minimum you should change this value
		 *
		 * @return (String) the username
		 */ 
		$onRest(ERestEvent::REQ_AUTH_USERNAME, function(){
			return 'admin@restuser';
		});

		/**
		 * req.auth.password
		 *
		 * This is the password use to grant access to non-ajax users
		 * At a minimum you should change this value
		 *
		 * @return (String) the password
		 */ 
		$onRest(ERestEvent::REQ_AUTH_PASSWORD, function(){
			return 'admin@Access';
		});

		/**
		 * req.auth.user
		 *
		 * Used to validate a non-ajax user
		 *
		 * @param (String) (application_id) the application_id defined in config.application.id
		 * @param (String) (username) the username defined in req.auth.username
		 * @param (String) (password) the password defined in req.auth.password
		 *
		 * @return (Bool) true to grant access; false to deny access
		 */ 
		$onRest(ERestEvent::REQ_AUTH_USER, function($application_id, $username, $password) {
			if(!isset($_SERVER['HTTP_X_'.$application_id.'_USERNAME']) || !isset($_SERVER['HTTP_X_'.$application_id.'_PASSWORD'])) {
				return false;
			}
			if( $username != $_SERVER['HTTP_X_'.$application_id.'_USERNAME'] ) {
				return false;
			}
			if( $password != $_SERVER['HTTP_X_'.$application_id.'_PASSWORD'] ) {
				return false;
			}
			return true;
		});

		/**
		 * req.after.action
		 *
		 * Called after the request has been fulfilled
		 * By default it has no behavior
		 */ 
		$onRest(ERestEvent::REQ_AFTER_ACTION, function($filterChain) {
			//Logic being applied after the action is executed
		});

		/**
		 * req.is.subresource
		 * 
		 * Called when trying to determain if the request is for a subresource
		 * WARNING!!!: ONLY CHANGE THIS EVENTS BEHAVIOR IF YOU REALLY KNOW WHAT YOUR DOING!!!
		 * WARNING!!!: CHANGING THIS MAY LEAD TO INCONSISTENT AND OR INCORRECT BEHAVIOR
		 *
		 * @param (Object) (model) model instance to evaluate
		 * @param (String) (subresource_name) potentially the name of the subresource
		 * @param (String) (http_verb) the http verb used to make the request
		 *
		 * @return (Bool) True if this is a subresouce request and false if not
		 */ 
		$onRest(ERestEvent::REQ_IS_SUBRESOURCE, function($model, $subresource_name, $http_verb) {
			if(!array_key_exists($subresource_name, $model->relations())) {
				return false;
			}
			if($model->relations()[$subresource_name][0] != CActiveRecord::MANY_MANY) {
				return false;
			}
			return true;
		});

		/**
		 * req.options.render
		 *
		 * Called when an options request is made from a CORS request
		 * @param (Array) (allowed_headers) list of allowed headers
		 * @param (Array) (allowed_methods) list of allowed http methods (verbs: ie PUT, POST, ect...)
		 * @param (Int) (max_age) Maximum age to cache options client side
		 */
		$onRest(ERestEvent::REQ_OPTIONS_RENDER, function($allowed_headers, $allowed_methods, $max_age) {
			$this->layout = 'RestfullYii.views.layouts.json';
			$this->render('RestfullYii.views.api.options', [
				'allowed_headers'		=>$allowed_headers,
				'allowed_methods'		=>$allowed_methods,
				'max_age'						=>$max_age,
				'origin'						=>$_SERVER['HTTP_ORIGIN'],
			]);
		});

		/**
		 * req.get.resource.render
		 *
		 * Called when a GET request for a single resource is to be rendered
		 * @param (Object) (data) this is the resources model
		 * @param (String) (model_name) the name of the resources model
		 * @param (Array) (relations) the list of relations to include with the data
		 * @param (Int) (count) the count of records to return (will be either 1 or 0)
		 */
		$onRest(ERestEvent::REQ_GET_RESOURCE_RENDER, function($data, $model_name, $relations, $count, $visibleProperties=[], $hiddenProperties=[]) {
			//Handler for GET (single resource) request
			$this->setHttpStatus(200);
			return $this->renderJSON([
				'type'							=> 'rest',
				'success'						=> (($count > 0)? true: false),
				'message'						=> (($count > 0)? "Record Found": "No Record Found"),
				'totalCount'				=> $count,
				'modelName'					=> $model_name,
				'relations'					=> $relations,
				'visibleProperties'	=> $visibleProperties,
				'hiddenProperties'	=> $hiddenProperties,
				'data'							=> $data,
			]);
		});

		/**
		 * req.get.resources.render
		 *
		 * Called when a GET request for when a list resources is to be rendered
		 *
		 * @param (Array) (data) this is an array of models representing the resources
		 * @param (String) (model_name) the name of the resources model
		 * @param (Array) (relations) the list of relations to include with the data
		 * @param (Int) (count) the count of records to return
		 */
		$onRest(ERestEvent::REQ_GET_RESOURCES_RENDER, function($data, $model_name, $relations, $count, $visibleProperties=[], $hiddenProperties=[]) {
			//Handler for GET (list resources) request
			$this->setHttpStatus(200);
			return $this->renderJSON([
				'type'							=> 'rest',
				'success'						=> (($count > 0)? true: false),
				'message'						=> (($count > 0)? "Record(s) Found": "No Record(s) Found"),
				'totalCount'				=> $count,
				'modelName'					=> $model_name,
				'relations'					=> $relations,
				'visibleProperties'	=> $visibleProperties,
				'hiddenProperties'	=> $hiddenProperties,
				'data'							=> $data,
			]);
		});
		
		/**
		 * req.get.subresources.render
		 *
		 * Called when a GET request for a list of sub-resources is to be rendered
		 *
		 * @param (Array) (models) list of sub-resource models
		 * @param (String) (subresource_name) the name of the sub-resources to render
		 * @param (Int) (count) the count of sub-resources to render
		 */
		$onRest(ERestEvent::REQ_GET_SUBRESOURCES_RENDER, function($models, $subresource_name, $count, $visibleProperties=[], $hiddenProperties=[]) {
			$this->setHttpStatus(200);
			
			return $this->renderJSON([
				'type'							=> 'rest',
				'success'						=> (($count > 0)? true: false),
				'message'						=> (($count > 0)? "Record(s) Found": "No Record(s) Found"),
				'totalCount'				=> $count,
				'modelName'					=> $subresource_name,
				'visibleProperties'	=> $visibleProperties,
				'hiddenProperties'	=> $hiddenProperties,
				'data'							=> $models,
			]);
		});

		/**
		 * req.get.subresource.render
		 *
		 * Called when a GET request for a sub-resource is to be rendered
		 *
		 * @param (Object) (model) the model representing the sub-resource
		 * @param (String) (subresource_name) the name of the sub-resource to render
		 * @param (Int) (count) the count of sub-resources to render (will be either 1 or 0)
		 */
		$onRest(ERestEvent::REQ_GET_SUBRESOURCE_RENDER, function($model, $subresource_name, $count, $visibleProperties=[], $hiddenProperties=[]) {
			$this->setHttpStatus(200);

			return $this->renderJSON([
				'type'				=> 'rest',
				'success'			=> true,
				'message'			=> (($count > 0)? "Record(s) Found": "No Record(s) Found"),
				'totalCount'	=> $count,
				'modelName'		=> $subresource_name,
				'visibleProperties' => $visibleProperties,
				'hiddenProperties' => $hiddenProperties,
				'data'				=> $model,
			]);
		});

		/**
		 * req.post.resource.render
		 * 
		 * Called when a POST request (create) is to be rendered
		 *
		 * @param (Object) (model) the newly created model
		 * @param (Array) (relations) list of relations to render with model
		 */
		$onRest(ERestEvent::REQ_POST_RESOURCE_RENDER, function($model, $relations=[], $visibleProperties=[], $hiddenProperties=[]) {
			$this->setHttpStatus(201);

			return $this->renderJSON([
				'type'							=> 'rest',
				'success'						=> true,
				'message'						=> "Record Created",
				'totalCount'				=> 1,
				'modelName'					=> get_class($model),
				'relations'					=> $relations,
				'visibleProperties'	=> $visibleProperties,
				'hiddenProperties'	=> $hiddenProperties,
				'data'							=> $model,
			]);
		});

		/**
		 * req.put.resource.render
		 * 
		 * Called when a PUT request (update) is to be rendered
		 *
		 * @param (Object) (model) the updated model
		 * @param (Array) (relations) list of relations to render with model
		 */
		$onRest(ERestEvent::REQ_PUT_RESOURCE_RENDER, function($model, $relations, $visibleProperties=[], $hiddenProperties=[]) {
			return $this->renderJSON([
				'type'							=> 'rest',
				'success'						=> true,
				'message'						=> "Record Updated",
				'totalCount'				=> 1,
				'modelName'					=> get_class($model),
				'relations'					=> $relations,
				'visibleProperties'	=> $visibleProperties,
				'hiddenProperties'	=> $hiddenProperties,
				'data'							=> $model,
			]);
		});

		/**
		 * req.put.subresource.render
		 *
		 * Called when a PUT request to a sub-resource (add a sub-resource) is rendered
		 *
		 * @param (Object) (model) the model of the resource that owns the subresource
		 * @param (String) (subresource_name) the name of the sub-resource
		 * @param (Mixed/Int) (subresource_id) the primary key of the sub-resource
		 */
		$onRest(ERestEvent::REQ_PUT_SUBRESOURCE_RENDER, function($model, $subresource_name, $subresource_id, $visibleProperties=[], $hiddenProperties=[]) {
			return $this->renderJSON([
				'type'							=> 'rest',
				'success'						=> true,
				'message'						=> "Subresource Added",
				'totalCount'				=> 1,
				'modelName'					=> get_class($model),
				'relations'					=> [$subresource_name],
				'visibleProperties' => $visibleProperties,
				'hiddenProperties'	=> $hiddenProperties,
				'data'							=> $model,
			]);
		});

		/**
		 * req.delete.resource.render
		 *
		 * Called when DELETE request is to be rendered
		 *
		 * @param (Object) (model) this is the deleted model object for the resource
		 */
		$onRest(ERestEvent::REQ_DELETE_RESOURCE_RENDER, function($model, $visibleProperties=[], $hiddenProperties=[]) {
			return $this->renderJSON([
				'type'							=> 'rest',
				'success'						=> true,
				'message'						=> "Record Deleted",
				'totalCount'				=> 1,
				'modelName'					=> get_class($model),
				'relations'					=> [],
				'visibleProperties'	=> $visibleProperties,
				'hiddenProperties'	=> $hiddenProperties,
				'data'							=> $model,
			]);
		});

		/**
		 * req.delete.subresource.render
		 *
		 * Called when DELETE request on a sub-resource is to be made
		 *
		 * @param (Object) (model) this is the model object that owns the deleted sub-resource
		 * @param (String) (subresource_name) the name of the deleted sub-resource
		 * @param (Mixed/Int) (subresource_id) the primary key of the deleted sub-resource
		 */
		$onRest(ERestEvent::REQ_DELETE_SUBRESOURCE_RENDER, function($model, $subresource_name, $subresource_id, $visibleProperties=[], $hiddenProperties=[]) {
			return $this->renderJSON([
				'type'							=> 'rest',
				'success'						=> true,
				'message'						=> "Sub-Resource Deleted",
				'totalCount'				=> 1,
				'modelName'					=> get_class($model),
				'relations'					=> [$subresource_name],
				'visibleProperties'	=> $visibleProperties,
				'hiddenProperties'	=> $hiddenProperties,
				'data'							=> $model,
			]);
		});

		/**
		 * model.subresource.count
		 *
		 * Called when the count of sub-resources is needed
		 *
		 * @param (Object) (model) the model that represents the owner of the sub-resource
		 * @param (String) (subresource_name) the name of the sub-resource
		 * @param (Mixed/Int) (subresource_id) the primary key of the sub-resource
		 *
		 * @return (Int) count of the subresources
		 */
		$onRest(ERestEvent::MODEL_SUBRESOURCE_COUNT, function($model, $subresource_name, $subresource_id=null) {
			return $this->getSubresourceHelper()->getSubresourceCount($model, $subresource_name, $subresource_id);
		});

		/**
		 * model.subresource.find
		 *
		 * Called when attempting to find a subresource
		 *
		 * @param (Object) (model) the model that represents the owner of the sub-resource
		 * @param (String) (subresource_name) the name of the sub-resource
		 * @param (Mixed/Int) (subresource_id) the primary key of the sub-resource
		 *
		 * @return (Object) the sub-resource model
		 */
		$onRest(ERestEvent::MODEL_SUBRESOURCE_FIND, function($model, $subresource_name, $subresource_id) {
			$subresource = @$this->getSubresourceHelper()->getSubresource($model, $subresource_name, $subresource_id);
			if(count($subresource) > 0) {
				return $subresource[0];
			}
			return $subresource;
		});

		/**
		 * model.subresource.find.all
		 *
		 * Called when attempting to find all subresources of a resource
		 *
		 * @param (Object) (model) the model that represents the owner of the sub-resources
		 * @param (String) (subresource_name) the name of the sub-resource
		 *
		 * @return (Array) list of sub-resource models
		 */
		$onRest(ERestEvent::MODEL_SUBRESOURCES_FIND_ALL, function($model, $subresource_name) {
			return $this->getSubresourceHelper()->getSubresource($model, $subresource_name);
		});

		/**
		 * req.param.is.pk
		 *
		 * Called when attempting to validate a resources primary key
		 * The default is an integer
		 *
		 * @return (bool) true to confirm primary key; false to deny
		 */
		$onRest(ERestEvent::REQ_PARAM_IS_PK, function($pk) {
			return $pk === '0' || preg_match('/^-?[1-9][0-9]*$/', $pk) === 1;
		});

		/**
		 * model.instance
		 *
		 * Called when an instance of the model representing the resource(s) is requested
		 * By default this is your controller class name minus the 'Controller'
		 *
		 * @return (Object) an empty instance of a resources Active Record model
		 */
		$onRest(ERestEvent::MODEL_INSTANCE, function() {
			$modelName = str_replace('Controller', '', get_class($this));
			return new $modelName();
		});

		/**
		 * model.attach.behaviors
		 *
		 * Called on all requests (Other then custom) to add some magic to your models
		 * Attach helper behaviors to model
		 *
		 * @param (Object) (model) an instance of an AR Model
		 *
		 * @return (Object) an instance of an Active Record model
		 */
		$onRest(ERestEvent::MODEL_ATTACH_BEHAVIORS, function($model) {
			//Attach this behavior to help saving nested models
			if(!array_key_exists('ERestActiveRecordRelationBehavior', $model->behaviors())) {
				$model->attachBehavior('ERestActiveRecordRelationBehavior', new ERestActiveRecordRelationBehavior());
			}
			
			if(!array_key_exists('ERestHelperScopes', $model->behaviors())) {
				$model->attachBehavior('ERestHelperScopes', new ERestHelperScopes());
			}
			return $model;
		});

		/**
		 * model.limit
		 *
		 * Called when applying a limit to the resources returned in a GET request
		 * The default is 100 or the value of the _GET param 'limit'
		 *
		 * @return (Int) the number of results to return
		 */
		$onRest(ERestEvent::MODEL_LIMIT, function() {
			return isset($_GET['limit'])? $_GET['limit']: 100;
		});

		/**
		 * model.offset
		 *
		 * Called when applying an offset to the records returned in a GET request
		 * The default is 0 or the value of the _GET param 'offset'
		 *
		 * @return (Int) the offset of results to return
		 */
		$onRest(ERestEvent::MODEL_OFFSET, function() {
			return isset($_GET['offset'])? $_GET['offset']: 0;
		});

		/**
		 * model.scenario
		 *
		 * Called before a resource is found
		 * This is the scenario to apply to a resource pre-find
		 * The default is 'search' or the value of the _GET param 'scenario'
		 *
		 * @return (String) the scenario name
		 */
		$onRest(ERestEvent::MODEL_SCENARIO, function() {
			return isset($_GET['scenario'])? $_GET['scenario']: 'restfullyii';
		});

		/**
		 * model.filter
		 *
		 * Called when attempting to apply a filter to apply to the records in a GET request
		 * The default is 'NULL' or the value of the _GET param 'filter'
		 * The format is JSON: 
		 * '[{"property":"SOME_PROPERTY", "value":"SOME_VALUE"}]'
		 * See documentation for additional options
		 *
		 * @return (JSON) the filter to apply
		 */
		$onRest(ERestEvent::MODEL_FILTER, function() {
			return isset($_GET['filter'])? $_GET['filter']: null;
		});

		/**
		 * model.sort
		 *
		 * Called when attempting to sort records returned in a GET request
		 * The default is 'NULL' or the value of the _GET param 'sort'
		 * The format is JSON:
		 * [{"property":"SOME_PROPERTY", "direction":"DESC"}]
		 *
		 * @return (JSON) the sort to apply
		 */ 
		$onRest(ERestEvent::MODEL_SORT, function() {
			return isset($_GET['sort'])? $_GET['sort']: null;
		});

		/**
		 * model.with.relations
		 *
		 * Called when trying to determine which relations to include in a requests render
		 * The default is all relations not starting with an underscore
		 * You should most likely customize this per resource
		 * as some resources may have relations that return large number of records
		 *
		 * @return (Array) list of relations (Strings) to attach to resources output
		 */
		$onRest(ERestEvent::MODEL_WITH_RELATIONS, function($model) {
			$nestedRelations = [];
			foreach($model->metadata->relations as $rel=>$val)
			{
				$className = $val->className;
				$rel_model = call_user_func([$className, 'model']);
				if(!is_array($rel_model->tableSchema->primaryKey) && substr($rel, 0, 1) != '_') {
					$nestedRelations[] = $rel;
				}
			}
			return $nestedRelations;
		});

		/**
		 * model.lazy.load.relations
		 *
		 * Called when determining if relations should be lazy or eager loaded
		 * The default is to lazy load. In most cases this is sufficient
		 * Under certain conditions eager loading my be more appropriate
		 *
		 * @return (Bool) true to lazy load relations; false to eager load
		 */
		$onRest(ERestEvent::MODEL_LAZY_LOAD_RELATIONS, function() {
			return true;
		});

		/**
		 * model.find
		 *
		 * Called when attempting to find a single model
		 *
		 * @param (Object) (model) an instance of the resources model
		 * @param (Mixed/Int) (id) the resources primary key
		 *
		 * @return (Object) the found model
		 */
		$onRest(ERestEvent::MODEL_FIND, function($model, $id) {
			return $model->findByPk($id);
		});

		/**
		 * model.find.all
		 *
		 * Called when attempting to find a list of models
		 *
		 * @param (Object) (model) an instance of the resources model
		 *
		 * @return (Array) list of found models
		 */
		$onRest(ERestEvent::MODEL_FIND_ALL, function($model) {
			return $model->findAll();
		});

		/**
		 * model.count
		 *
		 * Called when the count of model(s) is needed
		 *
		 * @param (Object) (model) the model to apply the count to
		 *
		 * @return (Int) the count of models
		 */
		$onRest(ERestEvent::MODEL_COUNT, function($model) {
			return $model->count();
		});

		/**
		 * req.data.read
		 *
		 * Called when reading data on POST & PUT requests
		 *
		 * @param (String) this can be either a stream wrapper of a file path
		 *
		 * @return (Array) the JSON decoded array of data
		 */ 
		$onRest(ERestEvent::REQ_DATA_READ, function($stream='php://input') {
			$reader = new ERestRequestReader($stream);
			return CJSON::decode($reader->getContents());
		});

		/**
		 * req.render.json
		 * NOT CALLED internally
		 * The handler exists to allow users the ability to easily render arbitrary JSON
		 * To do so you must 'emitRest' this event
		 *
		 * @param (Array) (data) data to be rendered
		 */
		$onRest(ERestEvent::REQ_RENDER_JSON, function($data) {
			return $this->renderJSON([
				'type' => 'raw',
				'data' => $data,
			]);
		});

		/**
		 * model.apply.post.data
		 *
		 * Called on POST requests when attempting to apply posted data
		 *
		 * @param (Object) (model) the resource model to save data to
		 * @param (Array) (data) the data to save to the model
		 * @param (Array) (restricted_properties) list of restricted properties
		 *
		 * @return (Object) the model with posted data applied
		 */
		$onRest(ERestEvent::MODEL_APPLY_POST_DATA, function($model, $data, $restricted_properties) {
			return $this->getResourceHelper()->setModelAttributes($model, $data, $restricted_properties);
		});

		/**
		 * model.apply.put.data
		 *
		 * Called on PUT requests when attempting to apply PUT data
		 *
		 * @param (Object) (model) the resource model to save data to
		 * @param (Array) (data) the data to save to the model
		 * @param (Array) (restricted_properties) list of restricted properties
		 *
		 * @return (Object) the model with PUT data applied
		 */
		$onRest(ERestEvent::MODEL_APPLY_PUT_DATA, function($model, $data, $restricted_properties) {
			return $this->getResourceHelper()->setModelAttributes($model, $data, $restricted_properties);
		});

		/**
		 * model.restricted.properties
		 *
		 * Called when determining which properties if any should be considered restricted
		 * The default is [] (no restricted properties)
		 *
		 * @return (Array) list of restricted properties
		 */
		$onRest(ERestEvent::MODEL_RESTRICTED_PROPERTIES, function() {
			return [];
		});

		/**
		 * model.visible.properties
		 *
		 * Called when determining which properties if any should be considered visible
		 * The default is [] (no hidden properties)
		 *
		 * @return (Array) list of visible properties
		 */
		$onRest(ERestEvent::MODEL_VISIBLE_PROPERTIES, function() {
			return [];
		});

		/**
		 * model.hidden.properties
		 *
		 * Called when determining which properties if any should be considered hidden
		 * The default is [] (no hidden properties)
		 *
		 * @return (Array) list of hidden properties
		 */
		$onRest(ERestEvent::MODEL_HIDDEN_PROPERTIES, function() {
			return [];
		});

		/**
		 * model.save
		 *
		 * Called whenever a model resource is saved
		 *
		 * @param (Object) the resource model to save
		 *
		 * @return (Object) the saved resource
		 */
		$onRest(ERestEvent::MODEL_SAVE, function($model) {
			if(!$model->save()) {
				throw new CHttpException('400', CJSON::encode($model->errors));
			}
			$model->refresh();
			return $model;
		});

		/**
		 * model.subresources.save
		 *
		 * Called whenever a sub-resource is saved
		 *
		 * @param (Object) (model) the owner of the sub-resource
		 * @param (String) (subresource_name) the name of the subresource
		 * @param (Mixed/Int) (subresource_id) the primary key of the subresource
		 *
		 * @return (Object) the updated model representing the owner of the sub-resource
		 */
		$onRest(ERestEvent::MODEL_SUBRESOURCE_SAVE, function($model, $subresource_name, $subresource_id) {
			if(!$this->getSubresourceHelper()->putSubresourceHelper($model, $subresource_name, $subresource_id)) {
				throw new CHttpException('500', 'Could not save Sub-Resource');
			}
			$model->refresh();
			return true;
		});

		/**
		 * model.delete
		 *
		 * Called whenever a model resource needs deleting
		 *
		 * @param (Object) (model) the model resource to be deleted
		 */
		$onRest(ERestEvent::MODEL_DELETE, function($model) {
			if(!$model->delete()) {
				throw new CHttpException(500, 'Could not delete model');
			}
			return $model;
		});

		/**
		 * model.subresource.delete
		 *
		 * Called whenever a subresource needs deleting
		 *
		 * @param (Object) (model) the owner of the sub-resource
		 * @param (String) (subresource_name) the name of the subresource
		 * @param (Mixed/Int) (subresource_id) the primary key of the subresource
		 *
		 * @return (Object) the updated model representing the owner of the sub-resource
		 */
		$onRest(ERestEvent::MODEL_SUBRESOURCE_DELETE, function($model, $subresource_name, $subresource_id) {
			if(!$this->getSubresourceHelper()->deleteSubResource($model, $subresource_name, $subresource_id)) {
				throw new CHttpException(500, 'Could not delete Sub-Resource');
			}
			$model->refresh();
			return $model;
		});
	}
}
