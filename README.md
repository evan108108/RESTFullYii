# RESTFullYii

Adds RESTFul API to your Yii application.

Lets say you have a controller named 'PostController'. Your standard routes will look as they always do, ie /post/actionName .

RESTFullYii adds a new set of RESTFul routes to your standard routes, but prepends '/api' .

So if you apply RESTFullYii to the 'PostController' you will get the following new routes by default (You can override their behavior in your controller).

```
[GET] http://yoursite.com/api/post/ (returns all posts)
[GET] http://yoursite.com/api/post/1 (returns post with PK=1)
[GET] http://yoursite.com/api/post/count (returns total count of posts)
[GET] http://yoursite.com/api/post/limit/x (returns x number of posts)
[GET] http://yoursite.com/api/post/limit/x/y (returns x number of posts with offset y)
[POST] http://yoursite.com/api/post/ (create new post)
[POST] http://yoursite.com/api/post/search (returns posts matching post attributes)
[PUT] http://yoursite.com/api/post/1 (update post with PK=1)
[DELETE] http://yoursite.com/api/post/1 (delete post with PK=1)
```

## Requirements

Yii 1.8 or above

## Installation

Place RESTFullYii into your 'protected/extensions' directory.

Then, in your main.php config, add this code:

```php
'import' => array(
  'ext.restfullyii.components.*',
),
```

You will need to add the routes below to your main.php. They should be added to the beginning of the rules array.

```php
'urlManager' => array(
  'urlFormat'=>'path',
  'rules' => array(
    'api/<controller:\w+>' => array(
      '<controller>/restList',
      'verb' => 'GET',
    ),
    'api/<controller:\w+>/<id:\w+>' => array(
      '<controller>/restView',
      'verb' => 'GET',
    ),
    'api/<controller:\w+>/<id:\w+>/<var:\w+>' => array(
      '<controller>/restView',
      'verb' => 'GET',
    ),
    array(
      '<controller>/restUpdate',
      'pattern' => 'api/<controller:\w+>/<id:\d+>',
      'verb' => 'PUT',
    ),
    array(
      '<controller>/restDelete',
      'pattern' => 'api/<controller:\w+>/<id:\d+>',
      'verb' => 'DELETE',
    ),
    array(
      '<controller>/restCreate',
      'pattern' => 'api/<controller:\w+>',
      'verb' => 'POST',
    ),
    array(
      '<controller>/restCreate',
      'pattern' => 'api/<controller:\w+>/<id:\w+>',
      'verb'=>'POST',
    ),
    '<controller:\w+>/<id:\d+>' => '<controller>/view',
    '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
    '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
  ),
),
```

Alternatively you can choose to use the included routes.php. Then your main.php
config for 'urlManager' should look like this:

```php
'urlManager' => array(
  'urlFormat' => 'path',
  'rules' => require(dirname(__FILE__).'/../extensions/restfullyii/config/routes.php'),
),
```

Another alternative is to use the custom rule class.  To use this method you
will need to set the 'restControllers' parameter to the array of controllers you
would like to use with RestFullYii. Your urlManager in main.php would look
something like this:

```php
'urlManager' => array(
  'urlFormat' => 'path',
  'rules' => array(
    array(
      'class' => 'application.extensions.restfullyii.components.RestUrlRule',
      'restControllers' => array('YourController1', 'YourController2', '...'),
    ),
    '<controller:\w+>/<id:\d+>' => '<controller>/view',
    '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
    '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
  ),
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

The 'username' and 'password' are currently hardcoded as Const's in 'ERestController'.

```php
Const USERNAME = 'admin@restuser';
Const PASSWORD = 'admin@Access'
```
At a minimum you will want change these values. To create a more secure Auth
system modify the 'filterRestAccessRules' method in 'ERestController'. This
should be straight forward.

## Usage

Sample Requests:

### GET

```shell
# Listing
$ curl -i -H "Accept: application/json" -H "X_REST_USERNAME: admin@restuser" -H "X_REST_PASSWORD: admin@Access" http://yii-tester.local/api/sample/
$ curl -i -H "Accept: application/json" -H "X_REST_USERNAME: admin@restuser" -H "X_REST_PASSWORD: admin@Access" http://yii-tester.local/api/sample/limit/1
$ curl -i -H "Accept: application/json" -H "X_REST_USERNAME: admin@restuser" -H "X_REST_PASSWORD: admin@Access" http://yii-tester.local/api/sample/limit/10/5 (limit/offeset)

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

Similarly you can POST' to a custom function. You must prefix your method withdoCustomRestPost(same is true for PUTdoCustomRestPutOrder($data)')

EG 'public function doCustomRestPostOrder($data)'

**POST**

```shell
$ curl -l -H "Accept: application/json" -H "X_REST_USERNAME: admin@restuser" -H "X_REST_PASSWORD: admin@Access" -X POST -d '{"id":"2","order":"French Fries"}' http://yii-tester.local/api/sample/order
```

To change behavior of default RESTFul actions you can simply override any of the following methods in your controller:

```php
/**
  * Over ride this function if your model uses a non Numeric PK.
  */
  public function isPk($pk)
  {
    if(is_numeric($pk))
      return true;
    else
      return false;
  }

  /**
   * This is broken out as a sperate method from actionRestList
   * To allow for easy overriding in the controller
   * and to allow for easy unit testing
   */
  public function doRestList()
  {
    $this->renderJson(array('success'=>true, 'message'=>'Records Retrieved Successfully', 'data'=>$this->getModel()->findAll()));
  }

   /**
   * This is broken out as a sperate method from actionRestView
   * To allow for easy overriding in the controller
   * adn to allow for easy unit testing
   */
  public function doRestView($id)
  {
    $this->renderJson(array('success'=>true, 'message'=>'Record Retrieved Successfully', 'data'=>$this->loadModel($id)));
  }

  /**
   * This is broken out as a sperate method from actionResUpdate
   * To allow for easy overriding in the controller
   * and to allow for easy unit testing
   */
  public function doRestUpdate($id, $data)
  {
    $model = $this->saveModel($this->loadModel($id), $data);
    $this->renderJson(array('success'=>true, 'message'=>'Record Updated', 'data'=>array('id'=>$id)));
  }

  /**
   * This is broken out as a sperate method from actionRestCreate
   * To allow for easy overriding in the controller
   * and to alow for easy unit testing
   */
  public function doRestCreate($data)
  {
    $model = $this->getModel();

    $ids = $this->saveModel($model, $data);

    $this->renderJson(array('success'=>true, 'message'=>'Record(s) Created', 'data'=>array('id'=>$ids)));
  }

  /**
   * This is broken out as a sperate method from actionRestDelete
   * To allow for easy overridding in the controller
   * and to alow for easy unit testing
   */
  public function doRestDelete($id)
  {
    $model = $this->loadModel($id);
    if($model->delete())
      $data = array('success'=>true, 'message'=>'Record Deleted', 'data'=>array('id'=>$id));
    else
      throw new CHttpException(406, 'Could not delete model with ID: ' . $id);

    $this->renderJson($data);
  }


  /**
   * Provides the ability to Limit and offset results
   * http://example.local/api/sample/limit/1/2
   * The above example would limit results to 1
   * and offest them by 2
   */
  public function doCustomRestGetLimit($var)
  {
    $criteria = new CDbCriteria();

    if(isset($var[1]))
    {
      $criteria->limit = $var[0];// . ", " . $var[1];
      $criteria->offset = $var[1];
    }
    else
      $criteria->limit = $var;

    $this->renderJson(array('success'=>true, 'message'=>'Records Retrieved Successfully', 'data'=>$this->getModel()->findAll($criteria)));
  }

  /**
   * Returns the current record count
   * http://example.local/api/sample/count
   */
  public function doCustomRestGetCount($var=null, $remote=true)
  {
    $this->renderJson(array('success'=>true, 'message'=>'Record Count Retrieved Successfully', 'data'=>array('count'=>count($this->getModel()->findAll()))));
  }

  /**
   * Search by attribute
   * Simply post a list of attributes and values you wish to search by
   * http://example.local/api/sample/search
   * POST = {'id':'6', 'name':'evan'}
   */
  public function doCustomRestPostSearch($data)
  {
    $this->renderJson(array('success'=>true, 'message'=>'Records Retrieved Successfully', 'data'=>$this->getModel()->findAllByAttributes($data)));
  }
```
