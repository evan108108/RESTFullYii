<?php
Yii::import('RestfullYii.tests.migrations.*');
Yii::import('RestfullYii.tests.MockObjs.models.*');
Yii::import('RestfullYii.tests.MockObjs.controllers.*');

class ERestTestCase extends CTestCase
{
	public $migration;

	/**
	 * migrate db up before each test
	 */
	public function setUp()
	{
		$this->migration = new ERestTestMigration();
		$this->migration->dbConnection = Yii::app()->db;
		$this->migration->down();
		$this->migration->up();
	}

	/**
	 * migrate down after tests
	 */
	/*public function tearDown()
	{
		$this->migration->down();
	}*/

	/**
	 * migrate db down after testcase has run
	 */ 
	/*function __destruct()
	{
		$this->migration->down();
	}*/

	public function getController()
	{
		return (object) [
			'Category'	=> new CategoryController('Category'),
			'Post'			=> new PostController('Post'),
			'Profile'		=> new ProfileController('Profile'),
			'User'			=> new UserController('User'),
		];
	}

	/**
	 * invokePrivateMethod
	 *
	 * Useses reflection to allow for the calling / testing of private methods
	 *
	 * @param (Object) (my_obj) the class that contains the private method
	 * @param (String) (method_name) name of the method to invoke
	 * @param (Array) (args) list of arguments to pass to the method on invocation
	 *
	 * @return (Mixed) result of the method
	 */ 
	public function invokePrivateMethod($my_obj, $method_name, $args=[])
	{
		array_unshift($args, $my_obj);
		$class = new ReflectionClass(get_class($my_obj));
		$method = $class->getMethod($method_name);
		$method->setAccessible(true);
		return call_user_func_array([$method, 'invoke'], $args);
	}

	/**
	 * getPrivateProperty
	 *
	 * Uses reflection to allow for the returning of private properties
	 *
	 * @param (Object) (my_obj) the class that contains the private property
	 * @param (String) (property_name) name of the property whose value to return
	 *
	 * @return (Mixed) value of the private property
	 */ 
	public function getPrivateProperty($my_obj, $property_name)
	{
		$reflectionClass = new ReflectionClass(get_class($my_obj));
		$reflectionProperty = $reflectionClass->getProperty($property_name);
		$reflectionProperty->setAccessible(true);
		return $reflectionProperty->getValue($my_obj);
	}

	/**
	 * assertArraysEqual
	 *
	 * Assert that two arrays are equal. This helper method will sort the two arrays before comparing them if
	 * necessary. This only works for one-dimensional arrays, if you need multi-dimension support, you will
	 * have to iterate through the dimensions yourself.
	 *
	 * @param array $expected the expected array
	 * @param array $actual the actual array
	 * @param bool $regard_order whether or not array elements may appear in any order, default is false
	 * @param bool $check_keys whether or not to check the keys in an associative array
	 */
	protected function assertArraysEqual(array $expected, array $actual, $regard_order = false, $check_keys = true)
	{
			// check length first
			$this->assertEquals(count($expected), count($actual), 'Failed to assert that two arrays have the same length.');

			// sort arrays if order is irrelevant
			if (!$regard_order) {
					if ($check_keys) {
							$this->assertTrue(ksort($expected), 'Failed to sort array.');
							$this->assertTrue(ksort($actual), 'Failed to sort array.');
					} else {
							$this->assertTrue(sort($expected), 'Failed to sort array.');
							$this->assertTrue(sort($actual), 'Failed to sort array.');
					}
			}

			$this->assertEquals($expected, $actual);
	}

	/**
	 * assertJSONFormat
	 * 
	 * asserts JSON string has a specific format
	 *
	 * @param (String) (json) a json string
	 * @param (Array) a list of properties that the JSON string must contain
	 */ 
	protected function assertJSONFormat($json, $format=['success', 'message', 'data', 'data'=>['totalCount']])
	{
		$this->assertTrue(CJSON::decode($json) !== false);
		$result = CJSON::decode($json);
		foreach($format as $key=>$value) {
			if(!is_array($value)) {
				$this->assertArrayHasKey($value, $result);
			} else {
				$this->assertJSONFormat(CJSON::encode($result[$key]), $value);
			}
		}
	}

	/**
	 * assertExceptionHasMessage
	 *
	 * asserts that an error objects contains a given message
	 *
	 * @param (String) (msg) the message that the error object must contain
	 * @param (Object) (e) The exception object
	 */ 
	protected function assertExceptionHasMessage($msg, Exception $e)
	{
		$this->assertContains($msg, CJSON::encode($e));
	}

	/**
	 * captureOB
	 *
	 * captures the output buffer of a given callback
	 *
	 * @param (Object) (bind_class) the class to bind the callback
	 * @param (Callable) (callback) the callback function
	 */ 
	protected function captureOB($bind_class, Callable $callback)
	{
		$callback = $callback->bindTo($bind_class);

		ob_start();
		try {
			call_user_func($callback);
			$output =  ob_get_contents();
		} catch (Exception $e) {
			$output = $e;
		}
		if (ob_get_length() > 0 ) { @ob_end_clean(); }
		
		return $output;
	}

	/**
	 * asUser()
	 *
	 * sets the Yii context to a logged in user
	 */
	protected function asUser($bind_class, Callable $callback)
	{
		$identity=new UserIdentity('admin', 'admin');
		$identity->authenticate();
		@Yii::app()->user->login($identity,0);

		$callback->bindTo($bind_class);
		call_user_func($callback);

		@Yii::app()->user->logout();
	}
}
