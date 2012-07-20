<?php
class ERestControllerTest extends CDbTestCase {

	public function testShould_be_able_to_create_new_user() {

		// Request

		$requestJson = json_encode(array(
			'username' => 'support@foogile.com',
			'password' => 'hardpassword'
		));

		$requestReaderMock = $this->getMock('ERequestReader', array('getContents'));
		$requestReaderMock->expects($this->once())
			->method('getContents')
			->will($this->returnValue($requestJson)); 

		// Model

		$modelCallback = new HasAttributeCallack(array('username', 'password'));
		$modelMock = $this->getMock('stdClass', array('save', 'hasAttribute'));
		$modelMock->expects($this->once())->method('save')->will($this->returnValue(true));
		$modelMock->expects($this->any())->method('hasAttribute')->will($this->returnCallback(array($modelCallback, 'hasAttribute')));
		$modelMock->id = 2;

		// Controller

		$controller = new ERestController('User');
		$controller->requestReader = $requestReaderMock;
		$controller->model = $modelMock;

		// Make request

		ob_start();

		$controller->actionRestCreate();
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
