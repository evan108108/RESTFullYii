<?php
class ERestControllerTest extends CDbTestCase {

	public function testShould_be_able_to_create_new_entry() {
		$requestMock = $this->mockRequest(array(
			'username' => 'support@foogile.com',
			'password' => 'hardpassword'
		));

		$modelMock = $this->mockModel(array('username', 'password'));
		$modelMock->expects($this->once())->method('save')->will($this->returnValue(true));
		$modelMock->id = 2;

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

	protected function mockRequest($request) {
		$requestJson = json_encode($request);
		$mock = $this->getMock('ERequestReader', array('getContents'));
		$mock->expects($this->once())
			->method('getContents')
			->will($this->returnValue($requestJson)); 
		return $mock;
	}

	protected function mockModel($attributes) {
		$callback = new HasAttributeCallack($attributes);
		$mock = $this->getMock('stdClass', array('save', 'hasAttribute', 'findByPk', 'delete'));
		$mock->expects($this->any())->method('hasAttribute')
			->will($this->returnCallback(array($callback, 'hasAttribute')));
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
}
