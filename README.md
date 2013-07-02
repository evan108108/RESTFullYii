# RESTFullYii

Adds RESTFul API to your Yii application.

Lets say you have a controller named 'PostController'. Your standard routes will look as they always do, ie /post/actionName .

RESTFullYii adds a new set of RESTFul routes to your standard routes, but prepends '/api' .

So if you apply RESTFullYii to the 'PostController' you will get the following new routes by default (You can override their behavior in your controller).

```
[GET] http://yoursite.com/api/post/ (returns all posts)
[GET] http://yoursite.com/api/post/1 (returns post with PK=1)
[POST] http://yoursite.com/api/post/ (create new post)
[PUT] http://yoursite.com/api/post/1 (update post with PK=1)
[DELETE] http://yoursite.com/api/post/1 (delete post with PK=1)
```

## Requirements

Yii 1.8 or above

## NEW
* Control which fields are returned in your GET requests with [scenarios](#scenarios).
* Now you can [Sub-Resource](#Sub-Resource) your 'many to many' Joins.
* Use with javascript (See validateAjaxUser in ERestController)
* Record count now included in JSON output 
* Query String  -> [sort](#sort), [limit](#limit), [offset](#offset), [scenario](#scenario) (NEW), [filter](#filter) (now with operators):

```shell
/api/post/?
limit=2&offset=1&sort=[{'property':'title','direction':'ASC'}]&filter=[{'property':'title', 'value':'some value'},{'property':'comment', 'value':'You need a REST'}]
```

* Save and display nested data sets:

```json
{
    "data": {
        "presentation": [
            {
            	"id": "41", 
                "author_email": "john.smith@somesite.com", 
                "author_name": "JSmith", 
                "description": "this is a great presentation", 
                "password": "12345", 
                "slides": [
                    {
                        "id": "17",
                        "content": "c4", 
                        "created": "1347972285", 
                        "description": "d3", 
                        "image_id": null, 
                        "options": null, 
                        "title": "t35795", 
                        "updated": "1347972285"
                    }, 
                    {
                    	"id": "18", 
                        "content": "c4", 
                        "created": "1347972285", 
                        "description": "d4", 
                        "image_id": null, 
                        "options": null, 
                        "title": "t45795", 
                        "updated": "1347972285"
                    }
                ], 
                "slug": "shoot123", 
                "title": "my present_test_2", 
                "updated": "1349289196"
            }
        ], 
        "totalCount": "2"
    }, 
    "message": "Records Retrieved Successfully", 
    "success": true
}
```

## Installation

Place RESTFullYii into your 'protected/extensions' directory.

Then, in your main.php config, add this code:

```php
'import' => array(
  'ext.restfullyii.components.*',
),
...
'params'=>array(
    'RESTusername' => 'admin@restuser',
    'RESTpassword' => 'admin@Access',
),
```

You will need to add the routes below to your main.php. They should be added to the beginning of the rules array.

```php
	return array(
		'api/<controller:\w+>'=>array('<controller>/restList', 'verb'=>'GET'),
		'api/<controller:\w+>/<id:\w*>'=>array('<controller>/restView', 'verb'=>'GET'),
		'api/<controller:\w+>/<id:\w*>/<var:\w*>'=>array('<controller>/restView', 'verb'=>'GET'),
		'api/<controller:\w+>/<id:\w*>/<var:\w*>/<var2:\w*>'=>array('<controller>/restView', 'verb'=>'GET'),
		
		array('<controller>/restUpdate', 'pattern'=>'api/<controller:\w+>/<id:\w*>', 'verb'=>'PUT'),
		array('<controller>/restUpdate', 'pattern'=>'api/<controller:\w+>/<id:\w*>/<var:\w*>', 'verb'=>'PUT'),
		array('<controller>/restUpdate', 'pattern'=>'api/<controller:\w*>/<id:\w*>/<var:\w*>/<var2:\w*>', 'verb'=>'PUT'),	
		
		array('<controller>/restDelete', 'pattern'=>'api/<controller:\w+>/<id:\w*>', 'verb'=>'DELETE'),
		array('<controller>/restDelete', 'pattern'=>'api/<controller:\w+>/<id:\w*>/<var:\w*>', 'verb'=>'DELETE'),
		array('<controller>/restDelete', 'pattern'=>'api/<controller:\w+>/<id:\w*>/<var:\w*>/<var2:\w*>', 'verb'=>'DELETE'),
		
		array('<controller>/restCreate', 'pattern'=>'api/<controller:\w+>', 'verb'=>'POST'),
		array('<controller>/restCreate', 'pattern'=>'api/<controller:\w+>/<id:\w+>', 'verb'=>'POST'),
		
		'<controller:\w+>/<id:\d+>'=>'<controller>/view',
		'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
		'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',  
	);
```

Alternatively you can choose to use the included routes.php. Then your main.php
config for 'urlManager' should look like this:

```php
'urlManager' => array(
  'urlFormat' => 'path',
  'rules' => require(dirname(__FILE__).'/../extensions/restfullyii/config/routes.php'),
),
```

Setting up the controller: (This applies to controllers for which you you would
like to add RESTFull routes) Change your controller class so that it extends
ERestController:

```php
class PostController extends ERestController {
  ...
}
```

You will need to merge your filters & accessRules methods with the parent methods here. To do
that you simply change the name of these methods by prepending an underscore
("\_"). So in your controller you will need to change the following:

```php
public function filters()
```
becomes

```php
public function _filters()
```

and

```php
public function accessRules()
```

becomes

```php
public function _accessRules()
```
## Security

The 'username' and 'password' are hardcoded as Const's in your 'Yii::app()->params'.

```php
'params'=>array(
    'RESTusername' => 'admin@restuser',
    'RESTpassword' => 'admin@Access',
),
```
At a minimum you will want change these values. To create a more secure Auth
system modify the 'filterRestAccessRules' method in 'ERestController'. This
should be straight forward.

To use with Javascript you simply need to override the 'validateAjaxUser' method in ERestController with custom logic.

## Usage

Sample Requests:

### GET

```shell
# Listing
$ curl -i -H "Accept: application/json" -H "X_REST_USERNAME: admin@restuser" -H "X_REST_PASSWORD: admin@Access" http://yii-tester.local/api/sample/

# Viewing
$ curl -l -H "Accept: application/json" -H "X_REST_USERNAME: admin@restuser" -H "X_REST_PASSWORD: admin@Access" http://yii-tester.local/api/sample/174
```
### PUT

```shell
# Update
$ curl -l -H "Accept: application/json" -H "X_REST_USERNAME: admin@restuser" -H "X_REST_PASSWORD: admin@Access" -H "X-HTTP-Method-Override: PUT" -X PUT -d '{"id":"174","name":"Five.1 Alive one ever Updated Again","desc":"It really is or should be at an honor","notes":"this is a note"}' http://yii-tester.local/api/sample/174
```

### POST

```shell
# Create
$ curl -l -H "Accept: application/json" -H "X_REST_USERNAME: admin@restuser" -H "X_REST_PASSWORD: admin@Access" -X POST -d '{"id":"175","name":"Six Alive one ever Updated Again","desc":"It really is or should be at an honor","notes":"this is a note"}' http://yii-tester.local/api/sample
$ curl -l -H "Accept: application/json" -H "X_REST_USERNAME: admin@restuser" -H "X_REST_PASSWORD: admin@Access" -X POST -d '[{"id":"175","name":"Six Alive one ever Updated Again","desc":"It really is or should be at an honor","notes":"this is a note"},{"id":"176","name":"First.3 one ever Updated Again","desc":"It really is or should be at an honor","notes":"this is a note"}]' http://yii-tester.local/api/sample
```

### Delete

```shell
$ curl -l -H "Accept: application/json" -H "X_REST_USERNAME: admin@restuser" -H "X_REST_PASSWORD: admin@Access" -H "X-HTTP-Method-Override: DELETE" -X DELETE http://yii-tester.local/api/sample/175
```

You may also optionally create custom REST methods in your controllers.

You must prefix your method with doCustomRest & the verb.

For GET request you use doCustomRestGet: EG public function doCustomRestGetOrder($var=null)

```shell
$ curl -l -H "Accept: application/json" -H "X_REST_USERNAME: admin@restuser" -H "X_REST_PASSWORD: admin@Access" http://yii-tester.local/api/sample/order
$ curl -l -H "Accept: application/json" -H "X_REST_USERNAME: admin@restuser" -H "X_REST_PASSWORD: admin@Access" http://yii-tester.local/api/sample/order/2
```

Similarly you can POST, PUT, DELETE' to a custom function. You must prefix your method withdoCustomRestPost(same is true for PUTdoCustomRestPutOrder($data)')

EG [POST] 'public function doCustomRestPostOrder($data)'  
EG [PUT]  'public function doCustomRestPutOrder($data, $vars=array())'  
EG [DELETE]  'public function doCustomRestDeleteOrder($data, $vars=array())'  

**POST**

```shell
$ curl -l -H "Accept: application/json" -H "X_REST_USERNAME: admin@restuser" -H "X_REST_PASSWORD: admin@Access" -X POST -d '{"id":"2","order":"French Fries"}' http://yii-tester.local/api/sample/order

```
**<a name="Sub-Resource"/>Sub-Resources</a>**
  
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

**Query String Operations**  
You can add filter, sort, limit and offset properties to your get request URL's. 

**<a name="filter">Filter</a>**
```
[GET] /api/post/?filter = [
  {"property": "id", "value" : 50, "operator": ">="}
, {"property": "user_id", "value" : [1, 5, 10, 14], "operator": "in"}
, {"property": "state", "value" : ["save", "deleted"], "operator": "not in"}
, {"property": "date", "value" : "2013-01-01", "operator": ">="}
, {"property": "date", "value" : "2013-01-31", "operator": "<="}
, {"property": "type", "value" : 2, "operator": "!="}
]
```

**<a name="sort">Sort</a>**
```
[GET] /api/post/?sort = [
[
   {'property':'title','direction':'ASC'}
 , {'property':'lud_dtm','direction':'DESC'}
]
```

**<a name="limit">Limit</a>**
```
[GET] /api/post/?limit=10
```

**<a name="offset">Offset</a>**
```
[GET] /api/post/?limit=10&offset=20  
```
  

**<a name="scenario">scenario</a>**
Set the Yii model scenario
```
[GET] /api/post/?scenario=search

[GET] /api/post/1?scenario=search
```

  
**Changing Default RestFullYii Behavior**  
To change behavior of default RESTFul actions you can simply override any of the following methods in your controller:

```php
 public function isPk($pk)
 
 public function validateAjaxUser($action)
 
 public function doRestList()
 
 public function doRestView($id)
 
 public function doRestViewSubResource($id, $subResource, $subResourceID=null)
  
 public function doRestUpdate($id, $data)
 
 public function doRestUpdateSubResource($id, $subResource, $subResourceID)
  
 public function doRestCreate($data)
  
 public function doRestDelete($id)

 public function doRestDeleteSubResource($id, $subResource, $subResourceID)
```

**<a name="scenarios">Scenarios</a>**

You can use a scenario to specify which fields you want to include during each request.
To do this, add a validation rule to your model and supply the name of the scenario (default is 'rest') 
```php
    class User extends CActiveRecord
    {
      public function rules() {
        return array(
          array('id, username, created', 'safe', 'on' => 'rest')
        );
      }
    }
```
You can also include virtual attributes. For more info about validation rules and scenarios, see the Yii documentation.


