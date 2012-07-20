<?php
class ERestControllerTest extends CDbTestCase {
	public function testShould_be_able_to_create_new_user() {

		// Mock input stream

		$mock = $this->getMock('EInputStream');
		$mock->expects($this->once())
			->method('getContents')
			->will($this->returnValue(
				'{ "username": "stianl@gil.com", "password": "mypass" }'
			));

		// Initiate controller

		$controller = new ERestController('User');
		$controller->inputStream = $mock;

		// Make request

		ob_start();

		$controller->actionRestCreate();
		$result = json_decode(ob_get_contents());
		$this->assertEquals($result->success, true);

		ob_clean();
	}
}
