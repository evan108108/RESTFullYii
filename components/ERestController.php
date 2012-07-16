<?php
/**
 * ERestController Created by Evan Frohlich evan.frohlich@controlgroup.com
 * Controller is the customized base controller class.
 * All controller classes that require RESTFull services should extend from this base class.
 *
 *
 * Installation:
 *   Place restfullyii into your protected/modules directory
 *
 *   You will need to add the routes below to your main.php
 *   They should be added to the begining of the rules array.
 *       'api/<controller:\w+>'=>array('<controller>/restList', 'verb'=>'GET'),
 *       'api/<controller:\w+>/<id:\w+>'=>array('<controller>/restView', 'verb'=>'GET'),
 *       'api/<controller:\w+>/<id:\w+>/<var:\w+>'=>array('<controller>/restView', 'verb'=>'GET'),
 *
 *       array('<controller>/restUpdate', 'pattern'=>'api/<controller:\w+>/<id:\d+>', 'verb'=>'PUT'),
 *       array('<controller>/restDelete', 'pattern'=>'api/<controller:\w+>/<id:\d+>', 'verb'=>'DELETE'),
 *       array('<controller>/restCreate', 'pattern'=>'api/<controller:\w+>', 'verb'=>'POST'),
 *       array('<controller>/restCreate', 'pattern'=>'api/<controller:\w+>/<id:\w+>', 'verb'=>'POST'),
 *
 *			'<controller:\w+>/<id:\d+>'=>'<controller>/view',
 *			'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
 *      '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
 *
 *   You may also choose to use the included routes.php. 
 *   Then your main.php config for `urlManager' should look like this:
 *     'urlManager'=>array(
 *         'urlFormat'=>'path',
 *         'rules'=>require(dirname(__FILE__).'/../modules/restfullyii/config/routes.php'),
 *      ), 
 *
 *
 *   Setting up the controller:
 *      (This applies to controllers for which you you would like to add RESTFull routes)
 *      Change your controller class so that it extends ERestController:
 *       class PostController extends ERestController{..} 
 *      You will need to merge your `fileters` & `accessRules` methods with the parent methods here.
 *      To do that you simply change the name of these methods by preppeding an underscore ("_")
 *      So in your controller you will need to change the following:
 *        `public function filters()' becomes `public function _filters()'
 *        `public function accessRules()' becomes `public function _accessRules()'
 *      
 *
 * Sample Requests:
 *    Verbs (GET, PUT, POST, DELETE)
 *    GET
 *      List
 *        curl -i -H "Accept: application/json" -H "X_REST_USERNAME: admin@restuser" -H "X_REST_PASSWORD: admin@Access" http://yii-tester.local/api/sample/
 *        curl -i -H "Accept: application/json" -H "X_REST_USERNAME: admin@restuser" -H "X_REST_PASSWORD: admin@Access" http://yii-tester.local/api/sample/limit/1
 *        curl -i -H "Accept: application/json" -H "X_REST_USERNAME: admin@restuser" -H "X_REST_PASSWORD: admin@Access" http://yii-tester.local/api/sample/limit/10/5 (limit/offeset)
 *      View
 *        curl -l -H "Accept: application/json" -H "X_REST_USERNAME: admin@restuser" -H "X_REST_PASSWORD: admin@Access" http://yii-tester.local/api/sample/174
 *    PUT
 *      Update
 *        curl -l -H "Accept: application/json" -H "X_REST_USERNAME: admin@restuser" -H "X_REST_PASSWORD: admin@Access" -H "X-HTTP-Method-Override: PUT" -X PUT -d '{"id":"174","name":"Five.1 Alive one ever Updated Again","desc":"It really is or should be at an honor","notes":"this is a note"}' http://yii-tester.local/api/sample/174
 *    POST
 *      Create
 *        curl -l -H "Accept: application/json" -H "X_REST_USERNAME: admin@restuser" -H "X_REST_PASSWORD: admin@Access" -X POST -d '{"id":"175","name":"Six Alive one ever Updated Again","desc":"It really is or should be at an honor","notes":"this is a note"}' http://yii-tester.local/api/sample
 *        curl -l -H "Accept: application/json" -H "X_REST_USERNAME: admin@restuser" -H "X_REST_PASSWORD: admin@Access" -X POST -d '[{"id":"175","name":"Six Alive one ever Updated Again","desc":"It really is or should be at an honor","notes":"this is a note"},{"id":"176","name":"First.3 one ever Updated Again","desc":"It really is or should be at an honor","notes":"this is a note"}]' http://yii-tester.local/api/sample
 *    Delete
 *        curl -l -H "Accept: application/json" -H "X_REST_USERNAME: admin@restuser" -H "X_REST_PASSWORD: admin@Access" -H "X-HTTP-Method-Override: DELETE" -X DELETE http://yii-tester.local/api/sample/175       
 *
 *  You may also optionaly create custom methods
 *  You must prefix your method with `doCustomRestGet`
 *  EG `public function doCustomRestGetOrder($var=null)`
 *    GET
 *     curl -l -H "Accept: application/json" -H "X_REST_USERNAME: admin@restuser" -H "X_REST_PASSWORD: admin@Access" http://yii-tester.local/api/sample/order
 *     curl -l -H "Accept: application/json" -H "X_REST_USERNAME: admin@restuser" -H "X_REST_PASSWORD: admin@Access" http://yii-tester.local/api/sample/order/2
 *  
 *  Similarly you can post to a custom function
 *  You must prefix your method with `doCustomRestPost` (same is true for PUT `doCustomRestPutOrder($data)')
 *  EG `public function doCustomRestPostOrder($data)`
 *    POST
 *      curl -l -H "Accept: application/json" -H "X_REST_USERNAME: admin@restuser" -H "X_REST_PASSWORD: admin@Access" -X POST -d '{"id":"2","order":"French Fries"}' http://yii-tester.local/api/sample/order     
 *
 */


class ERestController extends Controller
{
  Const APPLICATION_ID = 'REST';
  Const C404NOTFOUND = 'HTTP/1.1 404 Not Found';
  Const C401UNAUTHORIZED = 'HTTP/1.1 401 Unauthorized';
  Const C406NOTACCEPTABLE = 'HTTP/1.1 406 Not Acceptable';
  Const C201CREATED = 'HTTP/1.1 201 Created';
  Const C200OK = 'HTTP/1.1 200 OK';
  Const C500INTERNALSERVERERROR = 'HTTP/1.1 500 Internal Server Error';
  Const USERNAME = 'admin@restuser';
  Const PASSWORD = 'admin@Access';

  public $HTTPStatus = 'HTTP/1.1 200 OK';
  

  public function filters()
  {
    $restFilters = array('restAccessRules+ restList restView restCreate restUpdate restDelete');
    if(method_exists($this, '_filters'))
      return CMap::mergeArray($restFilters, $this->_filters());
    else
      return $restFilters;
  } 

  public function accessRules()
  {
    $restAccessRules = array(
      array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('restList', 'restView', 'restCreate', 'restUpdate', 'restDelete', 'error'),
				'users'=>array('*'),
      ));

    if(method_exists($this, '_accessRules'))
      return CMap::mergeArray($restAccessRules, $this->_accessRules());
    else
      return $restAccessRules;
  }  

  /**
   * Controls access to restfull requests
   */ 
  public function filterRestAccessRules( $c )
  {
    Yii::app()->errorHandler->errorAction = '/' . $this->uniqueid . '/error';

    if(!(isset($_SERVER['HTTP_X_'.self::APPLICATION_ID.'_USERNAME']) and isset($_SERVER['HTTP_X_'.self::APPLICATION_ID.'_PASSWORD']))) {
      // Error: Unauthorized
      throw new CHttpException(401, 'You are not authorized to proform this action.');
    }
    $username = $_SERVER['HTTP_X_'.self::APPLICATION_ID.'_USERNAME'];
    $password = $_SERVER['HTTP_X_'.self::APPLICATION_ID.'_PASSWORD'];
    // Find the user
    if($username != self::USERNAME)
    {
      // Error: Unauthorized
      throw new CHttpException(401, 'Error: User Name is invalid');
    } 
    else if($password != self::PASSWORD) 
    {
      // Error: Unauthorized
      throw new CHttpException(401, 'Error: User Password is invalid');
    } 
    // This tells the filter chain $c to keep processing. 
    $c->run(); 
  }   

  /**
   * Custom error handler for restfull Errors
   */ 
  public function actionError()
	{
    if($error=Yii::app()->errorHandler->error)
    {
      if(Yii::app()->request->isAjaxRequest)
        echo $error['message'];
      else
      {
        $this->HTTPStatus = $this->getHttpStatus($error['code'], 'C500INTERNALSERVERERROR');
        $this->renderJson(array('success' => false, 'message' => $error['message'], 'data' => array('errorCode'=>$error['code'])));
      }
    }
  }

  /**
   * Get HTTP Status Headers From code
   */ 
  public function getHttpStatus($statusCode, $default='C200OK')
  {
    switch ($statusCode) {
      case '200':
        return self::C200OK;
        break;
      case '201':
        return self::C201CREATED;
        break;
      case '401':
        return self::C401UNAUTHORIZED;
        break;
      case '404':
        return self::C404NOTFOUND;
        break;
      case '406':
        return self::C406NOTACCEPTABLE;
        break;
      case '500':
        return self::C500INTERNALSERVERERROR;
        break;
      default:
        return self::$default;
    }
  }

 /**
    ****************************************************************************************** 
    ******************************************************************************************
    * Actions that are tiggered by RESTFull requests
    * To change their default behavoir 
    * you should overide "doRest..." Methods in the controller 
    * and leave these actions as is
    ******************************************************************************************
    ******************************************************************************************
   */

  /**
   * Renders list of data assosiated with controller as json
   */
  public function actionRestList()
  {
    $this->doRestList();
  }
  
  /**
   * Renders View of record as json
   * Or Custom method
   */ 
  public function actionRestView($id, $var=null, $var2=null)
  {
    //If the value is numeric we can assume we have a record ID
    if($this->isPk($id))
      $this->doRestView($id);
    else
    {
      //if the $id is not numeric 
      //we are assume that the client is attempting to call a custom method
      //There may optionaly be a second param `$var` passed in the url
      if(isset($var) && isset($var2))
        $var = array($var, $var2);
      $id = 'doCustomRestGet' . ucfirst($id);
      if(method_exists($this, $id))
        $this->$id($var);
      else
        throw new CHttpException(500, 'Method does not exist.');
    }
  }

  /**
   * Updated record
   */ 
  public function actionRestUpdate($id, $var=false)
  {
    $this->HTTPStatus = $this->getHttpStatus('201');

    if(!$var)
      $this->doRestUpdate($id, $this->data());
    else
    {
      $var = 'doCustomRestPut' . ucfirst($var);
      if(method_exists($this, $var))
        $this->$var($id, $this->data());
      else if($this->isPk($var))
        $this->doRestUpdate($id, $this->data());
      else
        throw new CHttpException(500, 'Method does not exist.');
    }
  }

  /**
   * Creates new record
   */ 
  public function actionRestCreate($id=null)
  {
    $this->HTTPStatus = $this->getHttpStatus('201');

    if(!$id)
      $this->doRestCreate($this->data());
    else
    {
      //we can assume if $id is set the user is trying to call a custom method
      $id = 'doCustomRestPost' . ucfirst($id);
      if(method_exists($this, $id))
        $this->$id($this->data());
      else if($this->isPk($var))
        $this->doRestCreate($this->data());
      else
        throw new CHttpException(500, 'Method does not exist.');
    }
  }

  /**
   * Deletes record
   */ 
  public function actionRestDelete($id)
  {
    $this->doRestDelete($id);
  }

   /**
    ****************************************************************************************** 
    ******************************************************************************************
    * Helper functions for processing Rest data 
    ******************************************************************************************
    ******************************************************************************************
   */
  
  /**
   * Takes array and renders Json String
   */ 
  protected function renderJson($data)
  {
    $this->layout = 'ext.restfullyii.views.layouts.json';
    $this->render('ext.restfullyii.views.api.output', array('data'=>$data));
  }


  /**
   * Get data submited by the client
   */ 
  public function data()
  {
    return json_decode(file_get_contents("php://input"), true);
  }

  /**
   * Returns the model assosiated with this controller.
   * The assumption is that the model name matches your controller name
   * If this is not the case you should override this method in your controller
   */ 
  public function getModel()
  {
    $modelName = ucfirst($this->uniqueid);
    return new $modelName;
  }

  /**
   * Sets the model attributes and checks for errors
   */ 
  private function setModelAttributes($model, $data)
  {
    foreach($data as $var=>$value) {
      if($model->hasAttribute($var)) {
        $model->$var = $value;
      }
      else
        throw new CHttpException(406, 'Parameter is not allowed for model');
     }

    return $model;
  }
  
  /**
   * Helper for saving single/mutliple models 
   */ 
  private function saveModel($model, $data)
  {
    if(!isset($data[0]))
      $models[] = $this->setModelAttributes($model, $data);
    else
    {
      for($i=0; $i<count($data); $i++)
      {
        $models[$i] = $this->setModelAttributes($this->getModel(), $data[$i]);
        if(!$models[$i]->validate())
          throw new CHttpException(406, 'Model could not be saved as vildation failed.');
      }
    }
    
    for($cnt=0;$cnt<count($models);$cnt++)
    {
      if(!$models[$cnt]->save())
        throw new CHttpException(406, 'Model could not be saved');
      else
        $ids[] = $models[$cnt]->id;
    }
    return $ids;
  } 


  /**
    ****************************************************************************************** 
    ******************************************************************************************
    * OVERIDE THE METHODS BELOW IN YOUR CONTROLLER TO REMOVE/ALTER DEFAULT FUNCTIONALITY
    ******************************************************************************************
    ******************************************************************************************
   */
  
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

}

