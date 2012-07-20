<?php
class ERestControllerTest extends CDbTestCase {

	protected function mockRequest($request) {
		$requestJson = json_encode($request);
		$mock = $this->getMock('ERequestReader', array('getContents'));
		$mock->expects($this->once())
			->method('getContents')
			->will($this->returnValue($requestJson)); 
		return $mock;
	}

	public function mockModel($attributes) {
		$callback = new HasAttributeCallack($attributes);
		$mock = $this->getMock('stdClass', array('save', 'hasAttribute'));
		$mock->expects($this->any())->method('hasAttribute')
			->will($this->returnCallback(array($callback, 'hasAttribute')));
		return $mock;
	}

	public function getController($request, $model) {
		$controller = new ERestController('User');
		$controller->requestReader = $request;
		$controller->model = $model;
		return $controller;
	}

	public function testShould_be_able_to_create_new_entry() {

		// Request

		$requestMock = $this->mockRequest(array(
			'username' => 'support@foogile.com',
			'password' => 'hardpassword'
		));

		// Model

		$modelMock = $this->mockModel(array('username', 'password'));
		$modelMock->expects($this->once())->method('save')->will($this->returnValue(true));
		$modelMock->id = 2;

		// Make request

		ob_start();

		$this->getController($requestMock, $modelMock)->actionRestCreate();
		$result = json_decode(ob_get_contents());
		$this->assertEquals($result->success, true);

		ob_clean();
	}
}

class HasAttributeCallack {
	protected $attributes = null;

	public function __construct($attributes) {
		$this->attributes = $attributes;
	}

	public function hasAttribute($key) {
		return array_search($key, $this->attributes) !== false;
	}
}
