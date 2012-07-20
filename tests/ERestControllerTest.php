<?php
class ERestControllerTest extends CDbTestCase {

	/**
	 * @expectedException CHttpException
	 */
	public function testShould_fail_if_model_does_not_validate() {
		$modelMock = $this->mockModel();
		$modelMock->expects($this->once())->method('save')->will($this->returnValue(false));
		$result = $this->request($this->mockRequest(), $modelMock, 'create');
	}

	public function testShould_be_able_to_create_new_entry() {
		$postData = array(
			'username' => 'support@foogile.com',
			'password' => 'hardpassword'
		);
		$requestMock = $this->mockRequest($postData);

		$modelMock = $this->mockModel(array('username', 'password'), array('setAttributes'));
		$modelMock->expects($this->once())->method('setAttributes')->with($postData);
		$modelMock->expects($this->once())->method('save')->will($this->returnValue(true));

		$result = $this->request($requestMock, $modelMock, 'create');
		$this->assertEquals($result->success, true);
	}

	/*
	 * Helper methods
	 */

	protected function request($postData, $model, $action, $params = array()) {
		ob_start();
		$controller = $this->getController($postData, $model);
		$methodName = 'actionRest' . ucfirst($action);
		call_user_func_array(array($controller, $methodName), $params);
		return json_decode(ob_get_clean());
	}

	protected function mockRequest($request = array()) {
		$requestJson = json_encode($request);
		$mock = $this->getMock('ERequestReader', array('getContents'));
		$mock->expects($this->once())
			->method('getContents')
			->will($this->returnValue($requestJson)); 
		return $mock;
	}

	protected function mockModel($attributes = array(), $extraMethods = array()) {
		$callback = new HasAttributeCallack($attributes);
		$methods = array('attributeNames', 'save', 'delete', 'findByPk', 'getId');
		$mock = $this->getMock('CModel', array_merge($methods, $extraMethods));
		$mock->expects($this->any())->method('findByPk')->will($this->returnValue($mock));
		return $mock;
	}

	protected function getController($request, $model) {
		$controller = new ERestController('User');
		$controller->requestReader = $request;
		$controller->model = $model;
		return $controller;
	}



}

/*
 * Helper classes
 */

class HasAttributeCallack {
	protected $attributes = null;

	public function __construct($attributes) {
		$this->attributes = $attributes;
	}

	public function hasAttribute($key) {
		return array_search($key, $this->attributes) !== false;
	}

	public function attributeNames() {
		return $attributes;
	}
}
