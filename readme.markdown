# _Starship / RestfullYii_

Makes quickly adding a RESTFul API to your Yii project easy. RestfullYii provides full HTTP verb support (GET, PUT, POST, DELETE) for your resources, as well as the ability to offset, limit, sort, filter, etc… . You will also have the ability to read and manipulate related data with ease.

RestfullYii has been lovingly rebuilt from the metal and is now 100% test covered! The new event based architecture allows for clean and unlimited customization.

## How it works
RestfullYii adds a new set of RESTFul routes to your standard routes, but prepends '/api' .

So if you apply RestfullYii to the 'WorkController' you will get the following new routes by default.

```
[GET] http://yoursite.com/api/work (returns all works)
[GET] http://yoursite.com/api/work/1 (returns work with PK=1)
[POST] http://yoursite.com/api/work (create new work)
[PUT] http://yoursite.com/api/work/1 (update work with PK=1)
[DELETE] http://yoursite.com/api/work/1 (delete work with PK=1)
```


## Requirements

* PHP 5.4.0 (or later)*
* YiiFramework 1.1.14 (or later)
* PHPUnit 3.7 (or later) to run tests.



 _For older versions of PHP (< 5.4) checkout [v1.15](https://github.com/evan108108/RESTFullYii/tree/v1.15)_

## Installation

### Installing Manually 
0. Download and place the 'starship' directory in your Yii extension directory.

0. In config/main.php you will need to add the RestfullYii alias. This allows for flexability in where you place the extension.

```php
	'aliases' => array(
		.. .
        'RestfullYii' =>realpath(__DIR__ . '/../extensions/starship/RestfullYii'),
        .. .
	),
```

0. Include ext.starship.RestfullYii.config.routes in your main config (see below) or copy the routes and paste them in your components->urlManager->rules in same config.  

```php
	'components' => array(
		'urlManager' => array(
			'urlFormat' => 'path',
			'rules' => require(
				dirname(__FILE__).'/../extensions/starship/restfullyii/config/routes.php'
			),
		),
	)
```

### Installing With [Composer](http://getcomposer.org)

```JSON
{
    "require": {
        "starship/restfullyii": "dev-master"
    }
}
```

0. In config/main.php you will need to add the RestfullYii alias. This allows for flexability in where you place the extension.

```php
	'aliases' => array(
		.. .
		//Path to your Composer vendor dir plus starship/restfullyii path
		'RestfullYii' =>realpath(__DIR__ . '/../../../vendor/starship/restfullyii/starship/RestfullYii'),
        .. .
	),
```

0. Include ext.starship.RestfullYii.config.routes in your main config (see below) or copy the routes and paste them in your components->urlManager->rules in same config.  

```php
	'components' => array(
		'urlManager' => array(
			'urlFormat' => 'path',
			'rules' => require(
				dirname(__FILE__).'/../../../vendor/starship/restfullyii/starship/RestfullYii/config/routes.php
			),
		),
	)
```


##Controller Setup
Adding a set of RESTFul actions to a controller.

1. Add the ERestFilter to your controllers filter method.
```php
public function filters()
{
		return array(
			'accessControl', // perform access control for CRUD operations
			array(
				'RestfullYii.filters.ERestFilter + 
			 	REST.GET, REST.PUT, REST.POST, REST.DELETE'
			),
		);
}
```

2. Add the ERestActionProvider to your controllers actions method.
```php
public function actions()
{
		return array(
			'REST.'=>'RestfullYii.actions.ERestActionProvider',
		);
}	
```

3. If you are using the accessControl filter you need to make sure that access is allowed on all RESTFul routes.
```php
public function accessRules()
{
		return array(
			array('allow', 'actions'=>array('REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE'),
			'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
}
```

## Making Requests
To understand how to make RestfullYii API requests its best to look at a few examples. Code examples will be shown first in JavaScript* as an AJAX user* and then using CURL.  

_* JS examples use jQuery_

_* Default validation for an AJAX user is !Yii::app()->user->isGuest so the user must be logged in for this type of request._

###GET Requests

#### Getting a list or resources (WorkController)

JavaScript:
```javascript
 $.ajax({
    url:'/api/work',
    type:"GET",
    success:function(data) {
      console.log(data);
    },
    error:function (xhr, ajaxOptions, thrownError){
      console.log(xhr.responseText);
    } 
  }); 
```

CURL:
```shell
curl -i -H "Accept: application/json" -H "X_REST_USERNAME: admin@restuser" -H "X_REST_PASSWORD: admin@Access"\ 
http://my-site.com/api/work
```

Response:
```json
{
	"success":true,
	"message":"Record(s) Found",
	"data":{
		"totalCount":"30",
		"work":[
			{
				"id": "1",
                "title": "title1",
				"author_id": "1",
                "content": "content1",
                "create_time": "2013-08-07 10:09:41"
			},
			{
				"id": "2",
                "title": "title2",
				"author_id": "2",
                "content": "content2",
                "create_time": "2013-08-08 11:01:11"
			},
			. . .,
		]
	}
}	

```

#### Getting a single resource (WorkController)

JavaScript:
```javascript
 $.ajax({
    url:'/api/work/1',
    type:"GET",
    success:function(data) {
      console.log(data);
    },
    error:function (xhr, ajaxOptions, thrownError){
      console.log(xhr.responseText);
    } 
  }); 
```


CURL:
```shell
curl -i -H "Accept: application/json" -H "X_REST_USERNAME: admin@restuser" -H "X_REST_PASSWORD: admin@Access"\
http://my-site.com/api/work/1
```

Response:
```json
{
	"success":true,
	"message":"Record Found",
	"data":{
		"totalCount":"1",
		"work":[
			{
				"id": "1",
                "title": "title1",
				"author_id": "1",
                "content": "content1",
                "create_time": "2013-08-07 10:09:41"
			}
		]
	}
}	
```

### GET Request: Limit & Offset (WorkController)

You can limit and paginate through your results by adding the limit and offset variables to the request query string.

JavaScript:
```javascript
 $.ajax({
    url:'/api/work?limit=10&offset=30',
    type:"GET",
    success:function(data) {
      console.log(data);
    },
    error:function (xhr, ajaxOptions, thrownError){
      console.log(xhr.responseText);
    } 
  }); 
```

CURL:
```shell
curl -i -H "Accept: application/json" -H "X_REST_USERNAME: admin@restuser" -H "X_REST_PASSWORD: admin@Access"\
http://my-site.com/api/work?limit=10&offset=30
```

Response:
```json
{
	"success":true,
	"message":"Record(s) Found",
	"data":{
		"totalCount":"30",
		"work":[
			{
				"id": "11",
                "title": "title11",
				"author_id": "11",
                "content": "content11",
                "create_time": "2013-08-11 11:10:09"
			},
			{
				"id": "12",
                "title": "title12",
				"author_id": "12",
                "content": "content12",
                "create_time": "2013-08-08 12:11:10"
			},
			. . .,
		]
	}
}	

```

### GET Request: Sorting results (WorkController)
You can sort your results by any valid param or multiple params as well as provide a sort direction (ASC or DESC). sort=[{"property":"title", "direction":"DESC"}, {"property":"create_time", "direction":"ASC"}]


JavaScript:
```javascript
 $.ajax({
    url:'/api/work?sort=[{"property":"title", "direction":"DESC"}, {"property":"create_time", "direction":"ASC"}]',
    type:"GET",
    success:function(data) {
      console.log(data);
    },
    error:function (xhr, ajaxOptions, thrownError){
      console.log(xhr.responseText);
    } 
  }); 
```

CURL:
```shell
curl -i -H "Accept: application/json" -H "X_REST_USERNAME: admin@restuser" -H "X_REST_PASSWORD: admin@Access"\
http://my-site.com/api/work?sort=%5B%7B%22property%22%3A%22title%22%2C+%22direction%22%3A%22DESC%22%7D%2C+%7B%22property%22%3A%22create_time%22%2C+%22direction%22%3A%22ASC%22%7D%5D
```

Response:
```json
{
	"success":true,
	"message":"Record(s) Found",
	"data":{
		"totalCount":"30",
		"work":[
			{
				"id": "29",
                "title": "title30b",
				"author_id": "29",
                "content": "content30b",
                "create_time": "2013-08-07 14:05:01"
			},
			{
				"id": "30",
                "title": "title30",
				"author_id": "30",
                "content": "content30",
                "create_time": "2013-08-08 09:10:09"
			},
			{
				"id": "28",
                "title": "title28",
				"author_id": "28",
                "content": "content28",
                "create_time": "2013-08-09 14:05:01"
			},
			. . .,
		]
	}
}	

```

### GET Request: Filtering results (WorkController)
You can filter your results by any valid param or multiple params as well as an operator.

Available filter operators:
* in
* not in
* =
* !=
* >
* >=
* <
* <=
* No operator is "LIKE"

```
/api/post/?filter = [
  {"property": "id", "value" : 50, "operator": ">="}
, {"property": "user_id", "value" : [1, 5, 10, 14], "operator": "in"}
, {"property": "state", "value" : ["save", "deleted"], "operator": "not in"}
, {"property": "date", "value" : "2013-01-01", "operator": ">="}
, {"property": "date", "value" : "2013-01-31", "operator": "<="}
, {"property": "type", "value" : 2, "operator": "!="}
]
```

###POST Requests (Creating new resources)
With POST requests we must include the resource data as a JSON object in the request body.


JavaScript:
```javascript
var postData = {
	"title": "title31",
	"author_id": "31",
	"content": "content31",
	"create_time": "2013-08-20 09:23:14"
};

 $.ajax({
	url:'/api/work',
	data:JSON.stringify(postData)
	type:"POST",
	success:function(data) {
		console.log(data);
	},
	error:function (xhr, ajaxOptions, thrownError){
		console.log(xhr.responseText);
	} 
}); 
```

CURL:
```shell
curl -l -H "Accept: application/json" -H "X_REST_USERNAME: admin@restuser" -H "X_REST_PASSWORD: admin@Access"\
-X POST -d '{"title": "title31", "author_id": "31", "content": "content31", "create_time": "2013-08-20 09:23:14"}' http://my-site.com/api/work
```

Response:
```json
{
	"success":true,
	"message":"Record Created",
	"data":{
		"totalCount":"1",
		"work":[
			{
				"id": "31",
                "title": "title31",
				"author_id": "31",
                "content": "content31",
                "create_time": "2013-08-20 09:23:14"
			}
		]
	}
}	
```

###PUT Requests (Updating existing resources)
With PUT requests like POST requests we must include the resource data as a JSON object in the request body.

JavaScript:
```javascript
var postData = {
	"id": "31",
	"title": "title31",
	"author_id": "31",
	"content": "content31",
	"create_time": "2013-08-20 09:23:14"
};

 $.ajax({
	url:'/api/work/31',
	data:JSON.stringify(postData)
	type:"PUT",
	success:function(data) {
		console.log(data);
	},
	error:function (xhr, ajaxOptions, thrownError){
		console.log(xhr.responseText);
	} 
}); 
```

CURL:
```shell
curl -l -H "Accept: application/json" -H "X_REST_USERNAME: admin@restuser" -H "X_REST_PASSWORD: admin@Access"\
-X PUT -d '{"id": "31", "title": "title31", "author_id": "31", "content": "content31", "create_time": "2013-08-20 09:23:14"}' http://my-site.com/api/work/31
```

Response:
```json
{
	"success":true,
	"message":"Record Updated",
	"data":{
		"totalCount":"1",
		"work":[
			{
				"id": "31",
                "title": "title31",
				"author_id": "31",
                "content": "content31",
                "create_time": "2013-08-20 09:23:14"
			}
		]
	}
}	
```

### DELETE Requests (Delete a resource)

JavaScript:
```javascript
 $.ajax({
    url:'/api/work/1',
    type:"DELETE",
    success:function(data) {
      console.log(data);
    },
    error:function (xhr, ajaxOptions, thrownError){
      console.log(xhr.responseText);
    } 
  }); 
```


CURL:
```shell
curl -l -H "Accept: application/json" -H "X_REST_USERNAME: admin@restuser" -H "X_REST_PASSWORD: admin@Access"\
"X-HTTP-Method-Override: DELETE" -X DELETE http://my-site.com/api/work/1

```

Response:
```json
{
	"success":true,
	"message":"Record Deleted",
	"data":{
		"totalCount":"1",
		"work":[
			{
				"id": "1",
                "title": "title1",
				"author_id": "1",
                "content": "content1",
                "create_time": "2013-08-07 10:09:41"
			}
		]
	}
}	
```

### Sub-Resources
  
When working with 'many to many' relations you now have the ability to treat them as sub-resources.  

Consider:  
```
URL Format: http://mysite.com/api/<controller>/<id>/<many_many_relation>/<many_many_relation_id>

Getting player 3 who is on team 1  
or simply checking whether player 3 is on that team (200 vs. 404)  
GET /api/team/1/players/3  

getting player 3 who is also on team 3  
GET /api/team/3/players/3  

Adding player 3 also to team 2  
PUT /api/team/2/players/3  

Getting all teams of player 3  
GET /api/player/3/teams  

Remove player 3 from team 1 (Injury)
DELETE /api/team/1/players/3  

Team 1 found a replacement, who is not registered in league yet  
POST /api/player  

From payload you get back the id, now place it officially to team 1  
PUT /api/team/1/players/44  
```

## Customization & Configuration
RestfullYii's default behaviors can be easily customized though the built-in event system. Almost all aspects of RestFullYii's request / response handling trigger events. Changing RestfullYii's behaviors is as simple as registering the appropriate event handlers. Event handlers can be registered both globally (in the main config) and locally (at the controller level).

To understand how to do this, lets create a scenario that requires some customization and see how we might accomplish it.

Lets say we have two controllers in our API, WorkController and CategoryController. We would like our API to function in the following ways:

1. The API should be accessible to JavaScript via AJAX.
2. The API should not be accessible to any external client.
3. Only registered users should be allowed to view Work and Category resources.
4. Only users with the permission REST-UPDATE should be allowed to update works.
5. Only users with the permission REST-CREATE should be allowed to create works.
6. Only users with the permission REST-DELETE should be allowed to delete works.
7. Create, update and delete on categories should be disallowed for all API users.

Now that we know how we would like our API to function, lets take a look at the list above and determine which features can be implemented globally. Since 1, 2 and 3 effect both of our controllers, we can effect these globally by registering a few callbacks in our config/main.php.

To accomplish 1 & 3 we don't have to do anything as this is RestfullYii's default behavior, so that leaves 2. By default RestfullYii does allow access to external clients which is not what we want. Lets change it!

In the /protected/config/main.php params section we will add the following:
```php
$config = array(
	..,
	'params'=>[
		'RestfullYii' => [
			'req.auth.user'=>function($application_id, $username, $password) {
				return false;
			},
		]
	]
);
```

This tells RestfullYii that when the event 'req.auth.user' is handled it should always return false. Returning false will deny access (true grants it). Similarly validating an AJAX user has it's own event 'req.auth.ajax.user' which (as mentioned earlier) allows access to registered users by default.

That takes care of our global config and we can now focus on features 4-7 while taking care not to break feature 3. Since features 4-6 involve the work controller and user permissions, we can accomplish all of those at the same time. Remember RestfullYii's default behavior is to allow all registered users complete API access and again this is not what we want. Lets change it!

We will now register an event handler locally in the WorkController; To do this we will need to add a special public method in the WorkController called 'restEvents'. Then once we have the 'restEvents' method we can use one other special method 'onRest' to register our event handler. We can call 'onRest' using '$this->onRest()'. 'onRest' takes two params the first is the event name and the second is a Callable that will actually handle the event. This Callable is bound to the controller so the $this context inside the Callable always refers to the current controller. This is true for event handlers registered both globally as well as locally.

Now we are ready modify the output of event handler 'req.auth.ajax.user', but this time instead of overriding the default behavior, we will use the post-filter feature to add our additional user validation. The event name of a post-filter event is always the main event name prefixed with 'post.filter.', thus in our case the event name is 'post.filter.req.auth.ajax.user'. The param(s) passed into a post-filter handler are always the value(s) returned from the main event. Take a look:

```php
class WorkController extends Controller
{
	.. .
	
	public function restEvents()
	{
		$this->onRest('post.filter.req.auth.ajax.user', function($validation) {
			if(!$validation) {
				return false;
			}
			switch ($this->getAction()->getId()) {
				case 'REST.POST':
					return Yii::app()->user->checkAccess('REST-CREATE');
					break;
				case 'REST.POST':
					return Yii::app()->user->checkAccess('REST-UPDATE');
					break;
				case 'REST.DELETE':
					return Yii::app()->user->checkAccess('REST-DELETE');
					break;
				default:
					return false;
					break;
			}
		});
	}
	
	.. .
}
```

Cool! That just leaves feature 7, disallowing create, update, delete on category. Again we will add this change locally, but this time to the CategoryController. Take a look:

```php
class CategoryController extends Controller
{
	.. .
	
	public function restEvents()
	{
		$this->onRest('post.filter.req.auth.ajax.user', function($validation) {
			if(!$validation) {
				return false;
			}
			return ($this->getAction()->getId() == 'REST.GET');
		});
	}
	
	.. .
}

```

We now have all features implemented! 

## Defining Custom Routes
Custom routes are very simple to define as all you really need to do is create an event handler for your route and http verb combination (event name = 'req.\<verb>.\<route_name>.render').  Lets take a look at some examples.

Here is the list of routes we would like to add to our api:

1. [GET] /api/category/active 

2. [GET] /api/work/special/\<param1>

3. [PUT] /api/work/special/\<param1>/\<param2>

4. [POST] /api/work/special/\<param1>

5. [DELETE] /api/work/special/\<param1>/\<param2>


### Custom Route 1
As you tell from the route the request will be handled by the Category controller. So that is where we will add our event handler.

```php
class CategoryController extends Controller
{
	.. .
	public function restEvents()
    {
    	$this->onRest('req.get.active.render', function() {
    		//Custom logic for this route.
    		//Should output results.
    		$this->emitRest('req.render.json', [
    			[
    				'type'=>'raw',
    				'data'=>['active'=>true]
    			]
    		])
		});
	}
}
```

### Custom Routes 2-5
These routes all involve the Work controller. So that is where we will add our event handlers.

```php
class WorkController extends Controller
{
	.. .
	
	public function restEvents()
	{
		$this->onRest('req.get.special.render', function($param1) {
			echo CJSON::encode(['param1'=>$param1]);
		});
		
		$this->onRest('req.put.special.render', function($data, $param1, $param2) {
			//$data is the data sent in the PUT
			echo CJSON::encode(['data'=>$data, $param1, $param2]);
		});
		
		$this->onRest('req.post.special.render', function($data, $param1) {
			//$data is the data sent in the POST
			echo CJSON::encode(['data'=>$data, 'param1'=>$param1, 'param2'=>$param2]);
		});
		
		$this->onRest('req.delete.special.render', function($param1, $param2) {
			echo CJSON::encode(['param1'=>$param1, 'param2'=>$param2]);
		});
	}

```

##CORS Requests (Cross-Origin Resource Sharing)

Making cross origin requests from Javascript is now possible with RESTFullYii! RESTFullYii has several CORS specific events that help making CORS requests easy.

Lets suppose you have the following setup; An API server with the domain http://rest-api-server.back and a client application at the domain http://my-js-client-app.front. Obviously you would like to make requests from your front end client application (my-js-client-app.front) to your backend API server (rest-api-server.back). 

#####1) The first thing you will need to do is let RESTFullYii know that my-js-client-app.front is allowed to make requests to a given resource or resources. 
Lets say we want to allow our app to make requests to the 'Artist' resource only. So lets add an event hook in our ArtistController

```php
class ArtistController extends Controller
{
	[...]
	
	public function restEvents()
	{
		$this->onRest('req.cors.access.control.allow.origin', function() {
			return ['http://my-js-client-app.front']; //List of sites allowed to make CORS requests 
		});
	}
}

```

If you would like to allow my-js-client-app.front to access other resource you simply repeat this process in the appropriate controller(s) or apply it globally in your main config params (see above examples).

#####2) We need to determine is the types of requests we would like to allow. By default RESTFullYii will allow GET & POST but for our application we would like PUT & DELETE as well.

```php
class ArtistController extends Controller
{
	[...]
	
	public function restEvents()
	{
		$this->onRest('req.cors.access.control.allow.origin', function() {
			return ['http://my-js-client-app.front']; //List of sites allowed to make CORS requests 
		});
		
		$this->onRest('req.cors.access.control.allow.methods', function() {
			return ['GET', 'POST', 'PUT', 'DELETE']; //List of allowed http methods (verbs) 
		});
	}
}
```

This is the minimum configuration but there are more available; See ([req.cors.access.control.allow.headers](#req.cors.access.control.allow.headers), [req.cors.access.control.max.age](#req.cors.access.control.max.age), [req.auth.cors](#req.auth.cors))

#####3) Now that our server is set up to allow CORS requests we are ready to send requests from our client @http://my-js-client-app.front

```javascript
$.ajax({
	type: "GET",
	url: "http://rest-api-server.back/api/artist",
	headers: {
		'X_REST_CORS': 'Yes',
	},
	success: function( response ){
		console.log(response);
	},
	error: function( error ){
		console.log( "ERROR:", error );
	}
});
```

######*Notice the headers object in the jQuery request above. It is required that you send `X_REST_CORS: 'Yes'`

####That's it! You are making sweet CORS!



## Events
List of all events and their default event handlers.

| Event  | Pre-Filter  | Post-Filter | Description|
|---------------|----------------|----------------|----------------|
| Configuration Events |
| [config.application.id](#config.application.id)  |   [Yes](#pre.filter.config.application.id)   | [Yes](#post.filter.config.application.id) | Returns the app id that is applied to header vars (username, password) |
| [config.dev.flag](#config.dev.flag)    |   [Yes](#pre.filter.config.dev.flag)  | [Yes](#post.filter.config.dev.flag) | Return true to set develop mode; False to turn of develop mode |
| Request Events |
| [req.event.logger](#req.event.logger)   |   No  | No | Logs events |
| [req.disable.cweblogroute](#req.disable.cweblogroute)   |   [Yes](#pre.filter.req.disable.cweblogroute)  | [Yes](#post.filter.req.disable.cweblogroute) | Disable CWebLogRoute (True by default) |
| [req.auth.https.only](#req.auth.https.only)   |   [Yes](#pre.filter.req.auth.https.only)  | [Yes](#post.filter.req.auth.https.only) | Return true to restrict to https; false to allow http or https |
| [req.auth.username](#req.auth.username)   |   [Yes](#pre.filter.req.auth.username)   | [Yes](#post.filter.req.auth.username)  | This is the username used to grant access to non-ajax users. At a minimum you should change this value |
| [req.auth.password](#req.auth.password)   |   [Yes](#pre.filter.req.auth.password)  | [Yes](#post.filter.req.auth.password) |  This is the password use to grant access to non-ajax users. At a minimum you should change this value |
| [req.auth.user](#req.auth.user)   |   [Yes](#pre.filter.req.auth.user)  | [Yes](#post.filter.req.auth.user) |  Used to validate a non-ajax user; return true to allow; false to deny |
| [req.auth.ajax.user](#req.auth.ajax.user)   |   [Yes](#pre.filter.req.auth.ajax.user)  | [Yes](#post.filter.req.auth.ajax.user) |  Used to validate a an ajax user; return true to allow; false to deny |
| [req.auth.type](#req.auth.type)   |   [Yes](#pre.filter.req.auth.type)  | [Yes](#post.filter.req.auth.type) | returns the authorization type (1=CORS, 2=USER_PASS, 3=AJAX) |
| [req.cors.access.control.allow.origin](#req.cors.access.control.allow.origin)   |   [Yes](#pre.filter.req.cors.access.control.allow.origin)  | [Yes](#post.filter.req.cors.access.control.allow.origin) | returns the allowed remote origins durring a CORS request |
| [req.cors.access.control.allow.methods](#req.cors.access.control.allow.methods)   |   [Yes](#pre.filter.req.cors.access.control.allow.methods)  | [Yes](#post.filter.req.cors.access.control.allow.methods) | returns the allowed http methods/verbs for a CORS request |
| [req.cors.access.control.allow.headers](#req.cors.access.control.allow.headers)   |   [Yes](#pre.filter.req.cors.access.control.allow.headers)  | [Yes](#post.filter.req.cors.access.control.allow.headers) | returns the allowed headers for a CORS request |
| [req.cors.access.control.max.age](#req.cors.access.control.max.age)   |   [Yes](#pre.filter.req.cors.access.control.max.age)  | [Yes](#post.filter.req.cors.access.control.max.age) | Used in a CORS request to indicate how long the response can be cached (seconds) |
| [req.auth.cors](#req.auth.cors)   |   [Yes](#pre.filter.req.auth.cors)  | [Yes](#post.filter.req.auth.cors) | returns the authorization true if the CORS request is authorized and false if not |
| [req.auth.uri](#req.auth.uri)   |   [Yes](#pre.filter.req.auth.uri)  | [Yes](#post.filter.req.auth.uri) |  grant / deny access based on the URI and or HTTP verb |
| [req.after.action](#req.after.action)   |   [Yes](#pre.filter.req.after.action)  | [Yes](#post.filter.req.after.action) |  Called after the request has been fulfilled. By default it has no behavior |
| [req.param.is.pk](#req.param.is.pk)   |   [Yes](#pre.filter.req.param.is.pk)  | [Yes](#post.filter.req.param.is.pk) |  Called when attempting to validate a resources primary key. The default is an integer. Return true to confirm Primary Key; False to deny primary key. |
| [req.data.read](#req.data.read)   |   [Yes](#pre.filter.req.data.read)  | [Yes](#post.filter.req.data.read) |  Called when reading data on POST & PUT requests |
| [req.get.resource.render](#req.get.resource.render)   |   [Yes](#pre.filter.req.get.resource.render)  | No |  Called when a GET request for a single resource is to be rendered |
| [req.get.resources.render](#req.get.resources.render)   |   [Yes](#pre.filter.req.get.resources.render)  | No |  Called when a GET request for when a list resources is to be rendered |
| [req.put.resource.render](#req.put.resource.render)   |   [Yes](#pre.filter.req.put.resource.render)  | No |  Called when a PUT request for a single resource is to be rendered |
| [req.post.resource.render](#req.post.resource.render)   |   [Yes](#pre.filter.req.post.resource.render)  | No |  Called when a POST request is to be rendered |
| [req.delete.resource.render](#req.delete.resource.render)   |   [Yes](#pre.filter.req.delete.resource.render)  | No |  Called when a DELETE request is to be rendered |
| [req.get.subresource.render](#req.get.subresource.render)   |   [Yes](#pre.filter.req.get.subresource.render)  | No |  Called when a GET request for a single sub-resource is to be rendered |
| [req.get.subresources.render](#req.get.subresources.render)   |   [Yes](#pre.filter.req.get.subresources.render)  | No |  Called when a GET request for a list of sub-resources is to be rendered |
| [req.put.subresource.render](#req.put.subresource.render)   |   [Yes](#pre.filter.req.put.subresource.render)  | No |  Called when a PUT request for a single sub-resource is to be rendered |
| [req.delete.subresource.render](#req.delete.subresource.render)   |   [Yes](#pre.filter.req.delete.subresource.render)  | No |  Called when a DELETE request on a sub-resource is to be rendered |
| [req.render.json](#req.render.json)   |   [Yes](#pre.filter.req.render.json)  | No |   NOT CALLED INTERNALLY. The event exists to allow users the ability to easily render arbitrary JSON.|
| [req.exception](#req.exception)   |   [Yes](#pre.filter.req.exception)  | No |  Error handler called when an Exception is thrown |
| Model Events |
| [model.instance](#model.instance)   |   [Yes](#pre.filter.model.instance)  | [Yes](#post.filter.model.instance) |   Called when an instance of the model representing the resource(s) is requested. By default this is your controller class name minus the 'Controller' |
| [model.attach.behaviors](#model.attach.behaviors)   |   [Yes](#pre.filter.model.attach.behaviors)  | [Yes](#post.filter.model.attach.behaviors) |   Attaches helper behaviors to model. Called on all requests (Other then custom) to add some magic to your models. |
| [model.with.relations](#model.with.relations)   |   [Yes](#pre.filter.model.with.relations)  | [Yes](#post.filter.model.with.relations) |    Called when trying to determine which relations to include in a requests render. The default is all relations not starting with an underscore. You should most likely customize this per resource controller as some resources may have relations that return large number of records |
| [model.lazy.load.relations](#model.lazy.load.relations)   |   [Yes](#pre.filter.model.lazy.load.relations)  | [Yes](#post.filter.model.lazy.load.relations) |    Called when determining if relations should be lazy or eager loaded. The default is to lazy load. In most cases this is sufficient. Under certain conditions eager loading my be more appropriate |
| [model.limit](#model.limit)   |   [Yes](#pre.filter.model.limit)  | [Yes](#post.filter.model.limit) |     Called when applying a limit to the resources returned in a GET request. The default is 100 or the value of the _GET param 'limit' |
| [model.offset](#model.offset)   |   [Yes](#pre.filter.model.offset)  | [Yes](#post.filter.model.offset) |     Called when applying an offset to the records returned in a GET request. The default is 0 or the value of the _GET param 'offset'|
| [model.scenario](#model.scenario)   |   [Yes](#pre.filter.model.scenario)  | [Yes](#post.filter.model.scenario) |     Called before a resource(s) is found. This is the scenario to apply to a resource pre-find. The default is 'restfullyii' or the value of the _GET param 'scenario'. At this point setting the scenario does very little, but is included here so that you may create custom functionality with little modification.|
| [model.filter](#model.filter)   |   [Yes](#pre.filter.model.filter)  | [Yes](#post.filter.model.filter) |     Called when attempting to apply a filter to apply to the records in a GET request. The default is 'NULL' or the value of the _GET param 'filter'. The format is JSON: '[{"property":"SOME_PROPERTY", "value":"SOME_VALUE", "operator": =""}]'
| [model.sort](#model.sort)   |   [Yes](#pre.filter.model.sort)  | [Yes](#post.filter.model.sort) |     Called when attempting to sort records returned in a GET request. The default is 'NULL' or the value of the _GET param 'sort'. Rhe format is JSON:[{"property":"SOME_PROPERTY", "direction":"DESC"}]
| [model.find](#model.find)   |   [Yes](#pre.filter.model.find)  | [Yes](#post.filter.model.find) |     Called when attempting to find a single model
| [model.find.all](#model.find.all)   |   [Yes](#pre.filter.model.find.all)  | [Yes](#post.filter.model.find.all) |      Called when attempting to find a list of models
| [model.count](#model.count)   |   [Yes](#pre.filter.model.count)  | [Yes](#post.filter.model.count) |      Called when the count of model(s) is needed
| [model.subresource.find](#model.subresource.find)   |   [Yes](#pre.filter.model.subresource.find)  | [Yes](#post.filter.model.subresource.find) |      Called when attempting to find a subresource
| [model.subresource.find.all](#model.subresource.find.all)   |   [Yes](#pre.filter.model.subresource.find.all)  | [Yes](#post.filter.model.subresource.find.all) |      Called when attempting to find all subresources of a resource
| [model.subresource.count](#model.subresource.count)   |   [Yes](#pre.filter.model.subresource.count)  | [Yes](#post.filter.model.subresource.count) |       Called when the count of sub-resources is needed
| [model.apply.post.data](#model.apply.post.data)   |   [Yes](#pre.filter.model.apply.post.data)  | [Yes](#post.filter.model.apply.post.data) |       Called on POST requests when attempting to apply posted data
| [model.apply.put.data](#model.apply.put.data)   |   [Yes](#pre.filter.model.apply.put.data)  | [Yes](#post.filter.model.apply.put.data) |       Called on PUT requests when attempting to apply PUT data
| [model.save](#model.save)   |   [Yes](#pre.filter.model.save)  | [Yes](#post.filter.model.save) |       Called whenever a model resource is saved
| [model.subresources.save](#model.subresources.save)   |   [Yes](#pre.filter.model.subresources.save)  | [Yes](#post.filter.model.subresources.save) |       Called whenever a sub-resource is saved
| [model.delete](#model.delete)   |   [Yes](#pre.filter.model.delete)  | [Yes](#post.filter.model.delete) |       Called whenever a model resource needs deleting
| [model.subresource.delete](#model.subresource.delete)   |   [Yes](#pre.filter.model.subresource.delete)  | [Yes](#post.filter.model.subresource.delete) |       Called whenever a subresource needs deleting
| [model.restricted.properties](#model.restricted.properties)   |   [Yes](#pre.filter.model.restricted.properties)  |  [Yes](#post.filter.model.restricted.properties) |       Called when determining which properties if any should be considered restricted. The default is \[] (no restricted properties)
| [model.visible.properties](#model.visible.properties)   |   [Yes](#pre.filter.model.visible.properties)  |  [Yes](#post.filter.model.visible.properties) |       Called when determining which properties if any should be visible. The default is \[] (no hidden properties)
[model.hidden.properties](#model.hidden.properties)   |   [Yes](#pre.filter.model.hidden.properties)  |  [Yes](#post.filter.model.hidden.properties) |       Called when determining which properties if any should be hidden. The default is \[] (no hidden properties)

###<a name="config.application.id"/>config.application.id</a>
```php
/**
 * config.application.id
 *
 * returns the app id that is applied to header vars (username, password)
 *
 * @return (String) default is 'REST'
 */
$this->onRest('config.application.id', function() {
	return 'REST';
});
```

####<a name="pre.filter.config.application.id"/>pre.filter.config.application.id</a>
```php
$this->onRest('pre.filter.config.application.id', function() {
	//no return
});
```

####<a name="post.filter.config.application.id"/>post.filter.config.application.id</a>
```php
$this->onRest('post.filter.config.application.id', function($app_id) {
	return $app_id; //String
});
```






###<a name="config.dev.flag"/>config.dev.flag</a>
```php
/**
 * config.dev.flag
 *
 * return true to set develop mode; false to turn of develop mode
 *
 * @return (bool) true by default
 */
$this->onRest('config.dev.flag', function() {
	return true;
});
```

####<a name="pre.filter.config.dev.flag"/>pre.filter.config.dev.flag</a>
```php
$this->onRest('pre.filter.config.dev.flag', function() {
	//no return
});
```

####<a name="post.filter.config.dev.flag"/>post.filter.config.dev.flag</a>
```php
$this->onRest('post.filter.config.dev.flag', function($flag) {
	return $flag; //Bool
});
```





###<a name="req.event.logger"/>req.event.logger</a>
```php
/**
* req.event.logger
*
* @param (String) (event) the event to log
* @param (String) (category) the log category
* @param (Array) (ignore) Events to ignore logging
*
* @return (Array) the params sent into the event
*/
$this->onRest('req.event.logger', function($event, $category='application',$ignore=[]) {
	if(!isset($ignore[$event])) {
		Yii::trace($event, $category);
	}
	return [$event, $category, $ignore];
});
```





###<a name="req.disable.cweblogroute"/>req.disable.cweblogroute</a>
```php
/**
 * req.disable.cweblogroute
 *
 * this is only relevant if you have enabled CWebLogRoute in your main config
 *
 * @return (Bool) true (default) to disable CWebLogRoute, false to allow
 */ 
$this->onRest('req.auth.username', function(){
	return true;
});
```

####<a name="pre.filter.req.disable.cweblogroute"/>pre.filter.req.disable.cweblogroute</a>
```php
$this->onRest('pre.filter.req.disable.cweblogroute', function() {
	//no return
});
```

####<a name="post.filter.req.disable.cweblogroute"/>post.filter.req.disable.cweblogroute</a>
```php
$this->onRest('post.filter.req.disable.cweblogroute', function($disable) {
	return $disable; //Bool
});
```






###<a name="req.auth.https.only"/>req.auth.https.only</a>
```php
/**
 * req.auth.https.only
 *
 * return true to restrict to https;
 * false to allow http or https
 *
 * @return (bool) default is false
 */ 
$this->onRest('req.auth.https.only', function() {
	return false;
});
```

####<a name="pre.filter.req.auth.https.only"/>pre.filter.req.auth.https.only</a>
```php
$this->onRest('pre.filter.req.auth.https.only', function() {
	//no return
});
```

####<a name="post.filter.req.auth.https.only"/>post.filter.req.auth.https.only</a>
```php
$this->onRest('post.filter.req.auth.https.only', function($https_only) {
	return $https_only; //Bool
});
```




###<a name="req.auth.username"/>req.auth.username</a>
```php
/**
 * req.auth.username
 *
 * This is the username used to grant access to non-ajax users
 * At a minimum you should change this value
 *
 * @return (String) the username
 */ 
$this->onRest('req.auth.username', function(){
	return 'admin@restuser';
});
```

####<a name="pre.filter.req.auth.username"/>pre.filter.req.auth.username</a>
```php
$this->onRest('pre.filter.req.auth.username', function() {
	//no return
});
```

####<a name="post.filter.req.auth.username"/>post.filter.req.auth.username</a>
```php
$this->onRest('post.filter.req.auth.username', function($username) {
	return $username; //String
});
```




###<a name="req.auth.password"/>req.auth.password</a>
```php
/**
 * req.auth.password
 *
 * This is the password use to grant access to non-ajax users
 * At a minimum you should change this value
 *
 * @return (String) the password
 */ 
$this->onRest('req.auth.password', function(){
	return 'admin@Access';
});
```

####<a name="pre.filter.req.auth.password"/>pre.filter.req.auth.password</a>
```php
$this->onRest('pre.filter.req.auth.password', function() {
	//no return
});
```

####<a name="post.filter.req.auth.password"/>post.filter.req.auth.password</a>
```php
$this->onRest('post.filter.req.auth.password', function($password) {
	return $password; //String
});
```




###<a name="req.auth.user"/>req.auth.user</a>
```php
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
$this->onRest('req.auth.user', function($application_id, $username, $password) {
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
```

####<a name="pre.filter.req.auth.user"/>pre.filter.req.auth.user</a>
```php
$this->onRest('pre.filter.req.auth.user', function($application_id, $username, $password) {
	return [$application_id, $username, $password]; //Array [String, String, String]
});
```

####<a name="post.filter.req.auth.user"/>post.filter.req.auth.user</a>
```php
$this->onRest('post.filter.req.auth.user', function($validation) {
	return $validation; //Bool
});
```




###<a name="req.auth.ajax.user"/>req.auth.ajax.user</a>
```php
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
$this->onRest('req.auth.ajax.user', function() {
	if(Yii::app()->user->isGuest) {
		return false;
	}
	return true;
});
```


####<a name="pre.filter.req.auth.ajax.user"/>pre.filter.req.auth.ajax.user</a>
```php
$this->onRest('pre.filter.req.auth.ajax.user', function() {
	//no return
});
```

####<a name="post.filter.req.auth.ajax.user"/>post.filter.req.auth.ajax.user</a>
```php
$this->onRest('post.filter.req.auth.ajax.user', function($validation) {
	return $validation; //Bool
});
```





###<a name="req.auth.type"/>req.auth.type</a>
```php
/**
 * req.auth.type
 *
 * @return (Int) The request authentication type which may be 'USERPASS' (2), 'AJAX' (3) or 'CORS' (1)
 */
$this->onRest('req.auth.type', function($application_id) {
	if(isset($_SERVER['HTTP_X_'.$application_id.'_CORS']) || (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS')) {
		return ERestEventListenerRegistry::REQ_TYPE_CORS;
	} else if(isset($_SERVER['HTTP_X_'.$application_id.'_USERNAME']) && isset($_SERVER['HTTP_X_'.$application_id.'_PASSWORD'])) {
		return ERestEventListenerRegistry::REQ_TYPE_USERPASS;
	} else {
		return ERestEventListenerRegistry::REQ_TYPE_AJAX;
	}
});
```

####<a name="pre.filter.req.auth.type"/>pre.filter.req.auth.type</a>
```php
$this->onRest('pre.filter.req.auth.type', function($application_id) {
	return $application_id; //String
});
```

####<a name="post.filter.req.auth.type"/>post.filter.req.auth.type</a>
```php
$this->onRest('post.filter.config.application.id', function($auth_type) {
	return $auth_type; //Int
});
```




###<a name="req.cors.access.control.allow.origin"/>req.cors.access.control.allow.origin</a>
```php
/**
 * req.cors.access.control.allow.origin
 *
 * Used to validate a CORS request
 *
 * @return (Array) return a list of domains (origin) allowed access
 */
$this->onRest('req.cors.access.control.allow.origin', function() {
	return []; //Array
});
```

####<a name="pre.filter.req.cors.access.control.allow.origin"/>pre.filter.req.cors.access.control.allow.origin</a>
```php
$this->onRest('pre.filter.req.cors.access.control.allow.origin', function() {
	//No Return
});
```

####<a name="post.filter.req.cors.access.control.allow.origin"/>post.filter.req.cors.access.control.allow.origin</a>
```php
$this->onRest('post.filter.req.cors.access.control.allow.origin', function($allowed_origins) {
	return $allowed_origins; //Array
});
```





###<a name="req.cors.access.control.allow.methods"/>req.cors.access.control.allow.methods</a>
```php
/**
 * req.cors.access.control.allow.methods
 *
 * Used by CORS request to indicate the http methods (verbs) 
 * that can be used in the actual request
 *
 * @return (Array) List of http methods allowed via CORS
 */
$this->onRest('req.cors.access.control.allow.methods', function() {
	return ['GET', 'POST'];
});
```

####<a name="pre.filter.req.cors.access.control.allow.methods"/>pre.filter.req.cors.access.control.allow.methods</a>
```php
$this->onRest('pre.filter.req.cors.access.control.allow.methods', function() {
	//No Return
});
```

####<a name="post.filter.req.cors.access.control.allow.methods"/>post.filter.req.cors.access.control.allow.methods</a>
```php
$this->onRest('post.filter.req.cors.access.control.allow.methods', function($allowed_methods) {
	return $allowed_methods; //Array
});
```





###<a name="req.cors.access.control.allow.headers"/>req.cors.access.control.allow.headers</a>
```php
/**
 * req.cors.access.control.allow.headers
 *
 * Used by CORS request to indicate which custom headers are allowed in a request
 *
 * @return (Array) List of allowed headers
 */ 
$this->onRest('req.cors.access.control.allow.headers', function($application_id) {
	return ["X_{$application_id}_CORS"];
});
```

####<a name="pre.filter.req.cors.access.control.allow.headers"/>pre.filter.req.cors.access.control.allow.headers</a>
```php
$this->onRest('pre.filter.req.cors.access.control.allow.headers', function() {
	//No Return
});
```

####<a name="post.filter.req.cors.access.control.allow.headers"/>post.filter.req.cors.access.control.allow.headers</a>
```php
$this->onRest('post.filter.req.cors.access.control.allow.headers', function($allowed_headers) {
	return $allowed_headers; //Array
});
```





###<a name="req.cors.access.control.max.age"/>req.cors.access.control.max.age</a>
```php
/**
 * req.cors.access.control.max.age
 *
 * Used in a CORS request to indicate how long the response can be cached, 
 * so that for subsequent requests, within the specified time, no preflight request has to be made
 *
 * @return (Int) time in seconds
 */
$this->onRest('req.cors.access.control.max.age', function() {
	return 3628800; //Int
});
```

####<a name="pre.filter.req.cors.access.control.max.age"/>pre.filter.req.cors.access.control.max.age</a>
```php
$this->onRest('pre.filter.req.cors.access.control.max.age', function() {
	//No Return
});
```

####<a name="post.filter.req.cors.access.control.allow.headers"/>post.filter.req.cors.access.control.max.age</a>
```php
$this->onRest('post.filter.req.cors.access.control.max.age', function($max_age_in_seconds) {
	return $max_age_in_seconds; //int
});
```




###<a name="req.auth.cors"/>req.auth.cors</a>
```php
/**
 * req.auth.cors
 *
 * Used to authorize a given CORS request
 *
 * @param (Array) (allowed_origins) list of allowed remote origins
 *
 * @return (Bool) true to allow access and false to deny access
 */
$this->onRest('req.auth.cors', function ($allowed_origins) {
	if((isset($_SERVER['HTTP_ORIGIN'])) && (( array_search($_SERVER['HTTP_ORIGIN'], $allowed_origins)) !== false )) {
		return true;
	}
	return false;	
});
```

####<a name="pre.filter.req.auth.cors"/>pre.filter.req.auth.cors</a>
```php
$this->onRest('pre.filter.req.auth.cors', function($allowed_origins) {
	return $allowed_origins; //Array
});
```

####<a name="post.filter.req.auth.cors"/>post.filter.req.auth.cors</a>
```php
$this->onRest('post.filter.config.application.id', function($auth_cors) {
	return $auth_cors; //Bool
});
```






###<a name="req.auth.uri"/>req.auth.uri</a>
```php
/**
 * req.auth.uri
 *
 * return true to allow access to a given uri / http verb;
 * false to deny access to a given uri / http verb;
 *
 * @return (bool) default is true
 */ 
$this->onRest(req.auth.uri, function($uri, $verb) {
	return true;
});
```

####<a name="pre.filter.req.auth.uri"/>pre.filter.req.auth.uri</a>
```php
$this->onRest('pre.filter.req.auth.uri', function($uri, $verb) {
	return [$uri, $verb]; //array[string, string]
});
```

####<a name="post.filter.req.auth.uri"/>post.filter.req.auth.uri</a>
```php
$this->onRest('post.filter.req.auth.uri, function($validation) {
	return $validation; //bool
});
```



####<a name="req.after.action"/>req.after.action</a>
```php
/**
 * req.after.action
 *
 * Called after the request has been fulfilled
 * By default it has no behavior
 */ 
$this->onRest('req.after.action', function($filterChain) {
	//Logic being applied after the action is executed
});
```

####<a name="pre.filter.req.after.action"/>pre.filter.req.after.action</a>
```php
$this->onRest('pre.filter.req.after.action', function($filterChain) {
	return $filterChain; //Object
});
```

####<a name="post.filter.req.after.action"/>post.filter.req.after.action</a>
```php
$this->onRest('post.filter.after.action', function($result=null) {
	return $result; //Mixed
});
```


###<a name="req.param.is.pk"/>req.param.is.pk</a>
```php
/**
 * req.param.is.pk
 *
 * Called when attempting to validate a resources primary key
 * The default is an integer
 *
 * @return (bool) true to confirm primary key; false to deny
 */
$this->onRest('req.param.is.pk', function($pk) {
	return $pk === '0' || preg_match('/^-?[1-9][0-9]*$/', $pk) === 1;
});
```

####<a name="pre.filter.req.param.is.pk"/>pre.filter.req.param.is.pk</a>
```php
$this->onRest('pre.filter.req.param.is.pk', function($pk) {
	return $pk; //Mixed
});
```

####<a name="post.filter.req.param.is.pk"/>post.filter.req.param.is.pk</a>
```php
$this->onRest('post.filter.req.param.is.pk', function($isPk) {
	return $isPk; //Bool
});
```




###<a name="req.data.read"/>req.data.read</a>
```php
/**
 * req.data.read
 *
 * Called when reading data on POST & PUT requests
 *
 * @param (String) this can be either a stream wrapper of a file path
 *
 * @return (Array) the JSON decoded array of data
 */ 
$this->onRest('req.data.read', function($stream='php://input') {
	$reader = new ERestRequestReader($stream);
	return CJSON::decode($reader->getContents());
});
```

####<a name="pre.filter.req.data.read"/>pre.filter.req.data.read</a>
```php
$this->onRest('pre.filter.req.data.read, function($stream='php://input') {
	return $stream; //Mixed
});
```

####<a name="post.filter.req.data.read"/>post.filter.req.data.read</a>
```php
$this->onRest('post.filter.req.data.read', function($data) {
	return [$data]; //Array [Array]
});
```



###<a name="req.get.resource.render"/>req.get.resource.render</a>
```php
/**
 * req.get.resource.render
 *
 * Called when a GET request for a single resource is to be rendered
 * @param (Object) (data) this is the resources model
 * @param (String) (model_name) the name of the resources model
 * @param (Array) (relations) the list of relations to include with the data
 * @param (Int) (count) the count of records to return (will be either 1 or 0)
 */
$this->onRest('req.get.resource.render', function($data, $model_name, $relations, $count) {
	//Handler for GET (single resource) request
	$this->setHttpStatus((($count > 0)? 200: 204));
	$this->renderJSON([
		'type'				=> 'rest',
		'success'			=> (($count > 0)? true: false),
		'message'			=> (($count > 0)? "Record Found": "No Record Found"),
		'totalCount'		=> $count,
		'modelName'			=> $model_name,
		'relations'			=> $relations,
		'data'				=> $data,
	]);
});
```

####<a name="pre.filter.req.get.resource.render"/>pre.filter.req.get.resource.render</a>
```php
$this->onRest('pre.filter.req.get.resource.render, function($data, $model_name, $relations, $count) {
	return [$data, $model_name, $relations, $count]; //Array [Object, String, Array, Int]
});
```




####<a name="req.get.resources.render"/>req.get.resources.render</a>
```php
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
$this->onRest('req.get.resources.render', function($data, $model_name, $relations, $count) {
	//Handler for GET (list resources) request
	$this->setHttpStatus((($count > 0)? 200: 204));
	$this->renderJSON([
		'type'				=> 'rest',
		'success'			=> (($count > 0)? true: false),
		'message'			=> (($count > 0)? "Record(s) Found": "No Record(s) Found"),
		'totalCount'		=> $count,
		'modelName'			=> $model_name,
		'relations'			=> $relations,
		'data'				=> $data,
	]);
});
```

####<a name="pre.filter.req.get.resources.render"/>pre.filter.req.get.resources.render</a>
```php
$this->onRest('pre.filter.req.get.resources.render, function($data, $model_name, $relations, $count) {
	return [$data, $model_name, $relations, $count]; //Array [Array [Object], String, Array, Int]
});
```





###<a name="req.put.resource.render"/>req.put.resource.render</a>
```php
/**
 * req.put.resource.render
 * 
 * Called when a PUT request (update) is to be rendered
 *
 * @param (Object) (model) the updated model
 * @param (Array) (relations) list of relations to render with model
 */
$this->onRest('req.put.resource.render' function($model, $relations) {
	$this->renderJSON([
		'type'				=> 'rest',
		'success'			=> 'true',
		'message'			=> "Record Updated",
		'totalCount'	=> "1",
		'modelName'		=> get_class($model),
		'relations'		=> $relations,
		'data'				=> $model,
	]);
});
```

####<a name="pre.filter.req.put.resource.render"/>pre.filter.req.put.resource.render</a>
```php
$this->onRest('pre.filter.req.req.put.resource.render, function($model, $relations) {
	return [$model, relations]; //Array [Object, Array]
});
```




###<a name="req.post.resource.render"/>req.post.resource.render</a>
```php
/**
 * req.post.resource.render
 * 
 * Called when a POST request (create) is to be rendered
 *
 * @param (Object) (model) the newly created model
 * @param (Array) (relations) list of relations to render with model
 */
$this->onRest('req.post.resource.render', function($model, $relations=[]) {
	$this->renderJSON([
		'type'				=> 'rest',
		'success'			=> 'true',
		'message'			=> "Record Created",
		'totalCount'	=> "1",
		'modelName'		=> get_class($model),
		'relations'		=> $relations,
		'data'				=> $model,
	]);
});
```

####<a name="pre.filter.req.post.resource.render"/>pre.filter.req.post.resource.render</a>
```php
$this->onRest('pre.filter.req.post.resource.render', function($model, $relations) {
	return [$model, relations]; //Array [Object, Array]
});
```





###<a name="req.delete.resource.render"/>req.delete.resource.render</a>
```php
/**
 * req.delete.resource.render
 *
 * Called when DELETE request is to be rendered
 *
 * @param (Object) (model) this is the deleted model object for the resource
 */
$this->onRest('req.delete.resource.render', function($model) {
	$this->renderJSON([
		'type'				=> 'rest',
		'success'			=> 'true',
		'message'			=> "Record Deleted",
		'totalCount'		=> "1",
		'modelName'			=> get_class($model),
		'relations'			=> [],
		'data'				=> $model,
	]);
});
```

####<a name="pre.filter.req.delete.resource.render"/>req.delete.resource.render</a>
```php
$this->onRest('pre.filter.req.delete.resource.render', function($model) {
	return $model; //Object
});
```





###<a name="req.get.subresource.render">req.get.subresource.render</a>
```php
/**
 * req.get.subresource.render
 *
 * Called when a GET request for a sub-resource is to be rendered
 *
 * @param (Object) (model) the model representing the sub-resource
 * @param (String) (subresource_name) the name of the sub-resource to render
 * @param (Int) (count) the count of sub-resources to render (will be either 1 or 0)
 */
$this->onRest('req.get.subresource.render', function($model, $subresource_name, $count) {
	$this->setHttpStatus((($count > 0)? 200: 204));

	$this->renderJSON([
		'type'				=> 'rest',
		'success'			=> true,
		'message'			=> (($count > 0)? "Record(s) Found": "No Record(s) Found"),
		'totalCount'		=> $count,
		'modelName'			=> $subresource_name,
		'data'				=> $model,
	]);
});
```

####<a name="pre.filter.req.get.subresource.render">pre.filter.req.get.subresource.render</a>
```php
$this->onRest('pre.filter.req.get.subresource.render', function($model, $subresource_name, $count) {
	return [$model, $subresource_name, $count]; //Array [Object, String, Int]
});
```






###<a name="req.get.subresources.render">req.get.subresources.render</a>
```php
/**
 * req.get.subresources.render
 *
 * Called when a GET request for a list of sub-resources is to be rendered
 *
 * @param (Array) (models) list of sub-resource models
 * @param (String) (subresource_name) the name of the sub-resources to render
 * @param (Int) (count) the count of sub-resources to render
 */
$this->onRest('req.get.subresources.render', function($models, $subresource_name, $count) {
	$this->setHttpStatus((($count > 0)? 200: 204));
	
	$this->renderJSON([
		'type'				=> 'rest',
		'success'			=> (($count > 0)? true: false),
		'message'			=> (($count > 0)? "Record(s) Found": "No Record(s) Found"),
		'totalCount'		=> $count,
		'modelName'			=> $subresource_name,
		'data'				=> $models,
	]);
});
```

####<a name="pre.filter.req.get.subresources.render">pre.filter.req.get.subresources.render</a>
```php
$this->onRest('pre.filter.req.get.subresources.render', function($models, $subresource_name, $count) {
	return [$models, $subresource_name, $count]; //Array [Array[Object], String, Int]
});
```





###<a name="req.put.subresource.render">req.put.subresource.render</a>
```php
/**
 * req.put.subresource.render
 *
 * Called when a PUT request to a sub-resource (add a sub-resource) is rendered
 *
 * @param (Object) (model) the model of the resource that owns the subresource
 * @param (String) (subresource_name) the name of the sub-resource
 * @param (Mixed/Int) (subresource_id) the primary key of the sub-resource
 */
$this->onRest('req.put.subresource.render', function($model, $subresource_name, $subresource_id) {
	$this->renderJSON([
		'type'				=> 'rest',
		'success'			=> 'true',
		'message'			=> "Subresource Added",
		'totalCount'		=> "1",
		'modelName'			=> get_class($model),
		'relations'			=> [$subresource_name],
		'data'				=> $model,
	]);
});
```

####<a name="pre.filter.req.put.subresource.render">pre.filter.req.put.subresource.render</a>
```php
$this->onRest('pre.filter.req.put.subresource.render', function($model, $subresource_name, $subresource_id) {
	return [$model, $subresource_name, $subresource_id]; //Array [Object, String, Int]
});
```



###<a name="req.delete.subresource.render">req.delete.subresource.render</a>
```php
/**
 * req.delete.subresource.render
 *
 * Called when DELETE request on a sub-resource is to be made
 *
 * @param (Object) (model) this is the model object that owns the deleted sub-resource
 * @param (String) (subresource_name) the name of the deleted sub-resource
 * @param (Mixed/Int) (subresource_id) the primary key of the deleted sub-resource
 */
$this->onRest('req.delete.subresource.render', function($model, $subresource_name, $subresource_id) {
	$this->renderJSON([
		'type'				=> 'rest',
		'success'			=> 'true',
		'message'			=> "Sub-Resource Deleted",
		'totalCount'		=> "1",
		'modelName'			=> get_class($model),
		'relations'			=> [$subresource_name],
		'data'				=> $model,
	]);
});
```

####<a name="pre.filter.req.delete.subresource.render">pre.filter.req.delete.subresource.render</a>
```php
$this->onRest('pre.filter.req.delete.subresource.render', function($model, $subresource_name, $subresource_id) {
	return [$model, $subresource_name, $subresource_id]; //Array [Object, String, Int]
});
```






###<a name="req.render.json">req.render.json</a>
```php
/**
 * req.render.json
 * NOT CALLED internally
 * The handler exists to allow users the ability to easily render arbitrary JSON
 * To do so you must 'emitRest' this event
 *
 * @param (Array) (data) data to be rendered
 */
$this->onRest('req.render.json', function($data) {
	$this->renderJSON([
		'type' => 'raw',
		'data' => $data,
	]);
});
```

####<a name="pre.filter.req.render.json">pre.filter.req.render.json</a>
```php
$this->onRest('pre.filter.req.render.json', function($data) {
	return [$data]; //Array[Array]
});
```






###<a name="req.exception">req.exception</a>
```php
/**
 * req.exception
 *
 * Error handler called when an Exception is thrown
 * Used to render response to the client
 *
 * @param (Int) (errorCode) the http status code
 * @param (String) the error message
 */
$this->onRest('req.exception', function($errorCode, $message=null) {
	$this->renderJSON([
		'type'			=> 'error',
		'success'		=> false,
		'message'		=> (is_null($message)? $this->getHttpStatus()->message: $message),
		'errorCode' => $errorCode,
	]);
});
```

####<a name="pre.filter.req.exception">pre.filter.req.exception</a>
```php
$this->onRest('pre.filter.req.exception', function($errorCode, $message=null) {
	return [$errorCode, $message=null]; //Array [Int, String]
});
```





###<a name="model.instance">model.instance</a>
```php
/**
 * model.instance
 *
 * Called when an instance of the model representing the resource(s) is requested
 * By default this is your controller class name minus the 'Controller'
 *
 * @return (Object) an empty instance of a resources Active Record model
 */
$this->onRest('model.instance', function() {
	$modelName = str_replace('Controller', '', get_class($this));
	return new $modelName();
});
```

####<a name="pre.filter.model.instance">pre.filter.model.instance</a>
```php
$this->onRest('pre.filter.model.instance', function() {
	//No return
});
```

####<a name="post.filter.model.instance">post.filter.model.instance</a>
```php
$this->onRest('post.filter.model.instance', function($result)) {
	return $result; //
});
```





###<a name="model.attach.behaviors">model.attach.behaviors</a>
```php
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
$this->onRest('model.attach.behaviors', function($model) {
	//Attach this behavior to help saving nested models
	if(!array_key_exists('ERestActiveRecordRelationBehavior', $model->behaviors())) {
		$model->attachBehavior('ERestActiveRecordRelationBehavior', new ERestActiveRecordRelationBehavior());
	}
	
	if(!array_key_exists('ERestHelperScopes', $model->behaviors())) {
		$model->attachBehavior('ERestHelperScopes', new ERestHelperScopes());
	}
	return $model;
});
```	

####<a name="pre.filter.model.attach.behaviors">pre.filter.model.attach.behaviors</a>
```php
$this->onRest('pre.filter.model.attach.behaviors', function($model) {
	return $model //Object
});
```

####<a name="post.filter.model.attach.behaviors">post.filter.model.attach.behaviors</a>
```php
$this->onRest('post.filter.model.attach.behaviors', function($model)) {
	return $model; //Object
});
```







###<a name="model.with.relations">model.with.relations</a>
```php
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
$this->onRest('model.with.relations', function($model) {
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
```

####<a name="pre.filter.model.with.relations">pre.filter.model.with.relations</a>
```php
$this->onRest('pre.filter.model.with.relations', function($model) {
	return $model; //Object
});
```

####<a name="post.filter.model.with.relations">post.filter.model.with.relations</a>
```php
$this->onRest('post.filter.model.with.relations', function($result)) {
	return $result; //Array[String]
});
```





###<a name="model.lazy.load.relations">model.lazy.load.relations</a>
```php
/**
 * model.lazy.load.relations
 *
 * Called when determining if relations should be lazy or eager loaded
 * The default is to lazy load. In most cases this is sufficient
 * Under certain conditions eager loading my be more appropriate
 *
 * @return (Bool) true to lazy load relations; false to eager load
 */
$this->onRest('model.lazy.load.relations', function() {
	return true;
});
```

####<a name="pre.filter.model.lazy.load.relations">pre.filter.model.lazy.load.relations</a>
```php
$this->onRest('pre.filter.model.lazy.load.relations', function() {
	//No return
});
```

####<a name="post.filter.model.lazy.load.relations">post.filter.model.lazy.load.relations</a>
```php
$this->onRest('post.filter.model.lazy.load.relations', function($result)) {
	return $result; //Bool
});
```






###<a name="model.limit">model.limit</a>
```php
/**
 * model.limit
 *
 * Called when applying a limit to the resources returned in a GET request
 * The default is 100 or the value of the _GET param 'limit'
 *
 * @return (Int) the number of results to return
 */
$this->onRest('model.limit', function() {
	return isset($_GET['limit'])? $_GET['limit']: 100;
});
```

####<a name="pre.filter.model.limit">pre.filter.model.limit</a>
```php
$this->onRest('pre.filter.model.limit', function() {
	//No return
});
```

####<a name="post.filter.model.limit">post.filter.model.limit</a>
```php
$this->onRest('post.filter.model.limit', function($result)) {
	return $result; //Int
});
```







###<a name="model.offset">model.offset</a>
```php
/**
 * model.offset
 *
 * Called when applying an offset to the records returned in a GET request
 * The default is 0 or the value of the _GET param 'offset'
 *
 * @return (Int) the offset of results to return
 */
$this->onRest('model.offset', function() {
	return isset($_GET['offset'])? $_GET['offset']: 0;
});
```

####<a name="pre.filter.model.offset">pre.filter.model.offset</a>
```php
$this->onRest('pre.filter.model.offset', function() {
	//No return
});
```

####<a name="post.filter.model.offset">post.filter.model.offset</a>
```php
$this->onRest('post.filter.model.offset', function($result)) {
	return $result; //Int
});
```





###<a name="model.scenario">model.scenario</a>
```php
/**
 * model.scenario
 *
 * Called before a resource is found
 * This is the scenario to apply to a resource pre-find
 * The default is 'restfullyii' or the value of the _GET param 'scenario'
 *
 * @return (String) the scenario name
 */
$this->onRest('model.scenario', function() {
	return isset($_GET['scenario'])? $_GET['scenario']: 'restfullyii';
});
```

####<a name="pre.filter.model.scenario">pre.filter.model.scenario</a>
```php
$this->onRest('pre.filter.model.scenario', function() {
	//No return
});
```

####<a name="post.filter.model.scenario">post.filter.model.scenario</a>
```php
$this->onRest('post.filter.model.scenario', function($result)) {
	return $result; //String
});
```





###<a name="model.filter">model.filter</a>
```php
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
$this->onRest('model.filter', function() {
	return isset($_GET['filter'])? $_GET['filter']: null;
});
```

####<a name="pre.filter.model.filter">pre.filter.model.filter</a>
```php
$this->onRest('pre.filter.model.filter', function() {
	//No return
});
```

####<a name="post.filter.model.filter">post.filter.model.filter</a>
```php
$this->onRest('post.filter.model.filter', function($result)) {
	return $result; //Array[Object]
});
```






###<a name="model.sort">model.sort</a>
```php
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
$this->onRest('model.sort', function() {
	return isset($_GET['sort'])? $_GET['sort']: null;
});
```

####<a name="pre.filter.model.sort">pre.filter.model.sort</a>
```php
$this->onRest('pre.filter.model.sort', function() {
	//No return
});
```

####<a name="post.filter.model.sort">post.filter.model.sort</a>
```php
$this->onRest('post.filter.model.sort', function($result)) {
	return $result; //Array[Object]
});
```






###<a name="model.find">model.find</a>
```php
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
$this->onRest('model.find', function($model, $id) {
	return $model->findByPk($id);
});
```

####<a name="pre.filter.model.find">pre.filter.model.find</a>
```php
$this->onRest('pre.filter.model.find', function($model, $id) {
	return [$model, $id]; //Array [Object, Int]
});
```

####<a name="post.filter.model.find">post.filter.model.find</a>
```php
$this->onRest('post.filter.model.find', function($result)) {
	return $result; //Object
});
```


###<a name="model.find.all">model.find.all</a>
```php
/**
 * model.find.all
 *
 * Called when attempting to find a list of models
 *
 * @param (Object) (model) an instance of the resources model
 *
 * @return (Array) list of found models
 */
$this->onRest('model.find.all', function($model) {
	return $model->findAll();
});
```

####<a name="pre.filter.model.find.all">pre.filter.model.find.all</a>
```php
$this->onRest('pre.filter.model.find.all', function($model) {
	return $model; //Object
});
```

####<a name="post.filter.model.find.all">post.filter.model.find.all</a>
```php
$this->onRest('post.filter.model.find.all', function($result)) {
	return $result; //Array[Object]
});
```





###<a name="model.count">model.count</a>
```php
/**
 * model.count
 *
 * Called when the count of model(s) is needed
 *
 * @param (Object) (model) the model to apply the count to
 *
 * @return (Int) the count of models
 */
$this->onRest('model.count', function($model) {
	return $model->count();
});
```

####<a name="pre.filter.model.count">pre.filter.model.count</a>
```php
$this->onRest('pre.filter.model.count', function($model) {
	return $model; //Object
});
```

####<a name="post.filter.model.count">post.filter.model.count</a>
```php
$this->onRest('post.filter.model.count', function($result)) {
	return $result; //Int
});
```





###<a name="model.subresource.find">model.subresource.find</a>
```php
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
$this->onRest('model.subresource.find', function($model, $subresource_name, $subresource_id) {
	$subresource = @$this->getSubresourceHelper()->getSubresource($model, $subresource_name, $subresource_id);
	if(count($subresource) > 0) {
		return $subresource[0];
	}
	return $subresource; //Object
});
```

####<a name="pre.filter.model.subresource.find">pre.filter.model.subresource.find</a>
```php
$this->onRest('pre.filter.model.subresource.find', function($model, $subresource_name, $subresource_id) {
	return [$model, $subresource_name, $subresource_id]; //Array [Object, String, Int]
});
```

####<a name="post.filter.model.subresource.find">post.filter.model.subresource.find</a>
```php
$this->onRest('post.filter.model.subresource.find', function($result)) {
	return $result; //Object
});
```







###<a name="model.subresource.find.all">model.subresource.find.all</a>
```php
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
$this->onRest(ERestEvent::MODEL_SUBRESOURCES_FIND_ALL, function($model, $subresource_name) {
	return $this->getSubresourceHelper()->getSubresource($model, $subresource_name);
});
```

####<a name="pre.filter.model.subresource.find.all">pre.filter.model.subresource.find.all</a>
```php
$this->onRest('pre.filter.model.subresource.find.all', function($model, $subresource_name) {
	return [$model, $subresource_name]; //Array [Object, String]
});
```

####<a name="post.filter.model.subresource.find.all">post.filter.model.subresource.find.all</a>
```php
$this->onRest('post.filter.model.subresource.find.all', function($result)) {
	return $result; //Array[Object]
});
```






###<a name="model.subresource.count">model.subresource.count</a>
```php
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
$this->onRest('model.subresource.count', function($model, $subresource_name, $subresource_id=null) {
	return $this->getSubresourceHelper()->getSubresourceCount($model, $subresource_name, $subresource_id);
});
```

####<a name="pre.filter.model.subresource.count">pre.filter.model.subresource.count</a>
```php
$this->onRest('pre.filter.model.subresource.count', function($model, $subresource_name, $subresource_id=null) {
	return [$model, $subresource_name, $subresource_id=null]; //Array [Object, String, Int]
});
```

####<a name="post.filter.model.subresource.count">post.filter.model.subresource.count</a>
```php
$this->onRest('post.filter.model.subresource.count', function($result)) {
	return $result; //Int
});
```






###<a name="model.apply.post.data">model.apply.post.data</a>
```php
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
$this->onRest('model.apply.post.data', function($model, $data, $restricted_properties) {
	return $this->getResourceHelper()->setModelAttributes($model, $data, $restricted_properties);
});
```

####<a name="pre.filter.model.apply.post.data">pre.filter.model.apply.post.data</a>
```php
$this->onRest('pre.filter.model.apply.post.data', function($model, $data, $restricted_properties) {
	return [$model, $data, $restricted_properties]; //Array []
});
```

####<a name="post.filter.model.apply.post.data">post.filter.model.apply.post.data</a>
```php
$this->onRest('post.filter.model.apply.post.data', function($result)) {
	return $result; //
});
```





###<a name="model.apply.put.data">model.apply.put.data</a>
```php
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
$this->onRest('model.apply.put.data', function($model, $data, $restricted_properties) {
	return $this->getResourceHelper()->setModelAttributes($model, $data, $restricted_properties);
});
```

####<a name="pre.filter.model.apply.put.data">pre.filter.model.apply.put.data</a>
```php
$this->onRest('pre.filter.model.apply.put.data', function($model, $data, $restricted_properties) {
	return [$model, $data, $restricted_properties]; //Array [Object, Array, Array]
});
```

####<a name="post.filter.model.apply.put.data">post.filter.model.apply.put.data</a>
```php
$this->onRest('post.filter.model.apply.put.data', function($result)) {
	return $result; //Object
});
```



###<a name="model.save">model.save</a>
```php
/**
 * model.save
 *
 * Called whenever a model resource is saved
 *
 * @param (Object) the resource model to save
 *
 * @return (Object) the saved resource
 */
$this->onRest('model.save', function($model) {
	if(!$model->save()) {
		throw new CHttpException('400', CJSON::encode($model->errors));
	}
	$model->refresh();
	return $model;
});
```

####<a name="pre.filter.model.save">pre.filter.model.save</a>
```php
$this->onRest('pre.filter.model.save', function($model) {
	return $model; //Object
});
```

####<a name="post.filter.model.save">post.filter.model.save</a>
```php
$this->onRest('post.filter.model.save', function($result)) {
	return $result; //Object
});
```




###<a name="model.subresources.save">model.subresources.save</a>
```php
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
$this->onRest(ERestEvent::MODEL_SUBRESOURCE_SAVE, function($model, $subresource_name, $subresource_id) {
	if(!$this->getSubresourceHelper()->putSubresourceHelper($model, $subresource_name, $subresource_id)) {
		throw new CHttpException('500', 'Could not save Sub-Resource');
	}
	$model->refresh();
	return true;
});
```

####<a name="pre.filter.model.subresources.save">pre.filter.model.subresources.save</a>
```php
$this->onRest('pre.filter.model.subresources.save', function($model, $subresource_name, $subresource_id) {
	return [$model, $subresource_name, $subresource_id]; //Array [Object, String, Int]
});
```

####<a name="post.filter.model.subresources.save">post.filter.model.subresources.save</a>
```php
$this->onRest('post.filter.model.subresources.save', function($result)) {
	return $result; //Object
});
```






###<a name="model.delete">model.delete</a>
```php
/**
 * model.delete
 *
 * Called whenever a model resource needs deleting
 *
 * @param (Object) (model) the model resource to be deleted
 */
$this->onRest('model.delete', function($model) {
	if(!$model->delete()) {
		throw new CHttpException(500, 'Could not delete model');
	}
	return $model;
});
```

####<a name="pre.filter.model.delete">pre.filter.model.delete</a>
```php
$this->onRest('pre.filter.model.delete', function($model) {
	return $model; //Object
});
```

####<a name="post.filter.model.delete">post.filter.model.delete</a>
```php
$this->onRest('post.filter.model.delete', function($result)) {
	return $result; //Object
});
```




###<a name="model.subresource.delete">model.subresource.delete</a>
```php
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
$this->onRest('model.subresource.delete', function($model, $subresource_name, $subresource_id) {
	if(!$this->getSubresourceHelper()->deleteSubResource($model, $subresource_name, $subresource_id)) {
		throw new CHttpException(500, 'Could not delete Sub-Resource');
	}
	$model->refresh();
	return $model;
});
```

####<a name="pre.filter.model.subresource.delete">pre.filter.model.subresource.delete</a>
```php
$this->onRest('pre.filter.model.subresource.delete', function($model, $subresource_name, $subresource_id) {
	return [$model, $subresource_name, $subresource_id]; //Array [Object, String, Int]
});
```

####<a name="post.filter.model.subresource.delete">post.filter.model.subresource.delete</a>
```php
$this->onRest('post.filter.model.subresource.delete', function($result)) {
	return $result; //Object
});
```






###<a name="model.restricted.properties">model.restricted.properties</a>
```php
/**
 * model.restricted.properties
 *
 * Called when determining which properties if any should be considered restricted
 * The default is [] (no restricted properties)
 *
 * @return (Array) list of restricted properties
 */
$this->onRest('model.restricted.properties', function() {
	return [];
});
```

####<a name="pre.filter.model.restricted.properties">pre.filter.model.restricted.properties</a>
```php
$this->onRest('pre.filter.model.restricted.properties', function() {
	//No return
});
```

####<a name="post.filter.model.restricted.properties">post.filter.model.restricted.properties</a>
```php
$this->onRest('post.filter.model.restricted.properties', function($result)) {
	return $result; //Array
});
```






###<a name="model.visible.properties">model.visible.properties</a>
```php
/**
 * model.visible.properties
 *
 * Called when determining which properties if any should be considered visible
 * The default is [] (no hidden properties)
 *
 * @return (Array) list of visible properties
 */
$this->onRest('model.visible.properties', function() {
	return [];
});
```

####<a name="pre.filter.model.visible.properties">pre.filter.model.visible.properties</a>
```php
$this->onRest('pre.filter.model.visible.properties', function() {
	//No return
});
```

####<a name="post.filter.model.visible.properties">post.filter.model.visible.properties</a>
```php
$this->onRest('post.filter.model.visible.properties', function($result)) {
	return $result; //Array
});
```






###<a name="model.hidden.properties">model.hidden.properties</a>
```php
/**
 * model.hidden.properties
 *
 * Called when determining which properties if any should be considered hidden
 * The default is [] (no hidden properties)
 *
 * @return (Array) list of hidden properties
 */
$this->onRest('model.hidden.properties', function() {
	return [];
});
```

####<a name="pre.filter.model.hidden.properties">pre.filter.model.hidden.properties</a>
```php
$this->onRest('pre.filter.model.hidden.properties', function() {
	//No return
});
```

####<a name="post.filter.model.hidden.properties">post.filter.model.hidden.properties</a>
```php
$this->onRest('post.filter.model.hidden.properties', function($result)) {
	return $result; //Array
});
```






## Testing
Running the project's automated tests.

### Unit Tests

1. Make sure you that you have the correct database and database user in the test config 
(/WEB_ROOT/protected/extensions/starship/RestfullYii/tests/testConfig.php).
2. % cd /WEB_ROOT/protected/extensions/starship/RestfullYii/tests
3. % phpunit unit/

## Contributors
* [evan108108](https://github.com/evan108108)
* [goliatone](https://github.com/goliatone)
* [ubin](https://github.com/ubin)
* [jlsalvador](https://github.com/jlsalvador)
* [ericmcgill](https://github.com/ericmcgill)
* [stianlik](https://github.com/stianlik)
* [kachar](https://github.com/kachar)
* [drLev](https://github.com/drLev)
* [sheershoff](https://github.com/sheershoff)
* [Arne-S](https://github.com/Arne-S)
* [amesmoey](https://github.com/amesmoey)
* [eligundry](https://github.com/eligundry)
* [rominawnc](https://github.com/rominawnc)

## License
Starship / RestfullYii is released under the WTFPL license - http://sam.zoy.org/wtfpl/. This means that you can literally do whatever you want with this extension.
