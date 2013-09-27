<?php
Yii::import('RestfullYii.components.ERestRequestReader');

/**
 * ERestRequestReaderUnitTest
 *
 * Tests ERestRequestReader
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class ERestRequestReaderUnitTest extends ERestTestCase
{
	/**
	 * __construct
	 *
	 * tests ERestRequestReader constructor
	 */
	public function testConstruct()
	{
		$errr = new ERestRequestReader();
		$this->assertEquals($this->getPrivateProperty($errr, 'filename'), 'php://input');

		$errr = new ERestRequestReader('/tmp/myfile.txt');
		$this->assertEquals($this->getPrivateProperty($errr, 'filename'), '/tmp/myfile.txt');
	}

	/**
	 * getContents
	 * 
	 * tests ERestRequestReader->getContents
	 */ 
	public function testGetContents()
	{
		$tmp_file = sys_get_temp_dir() . '/my_req_file.txt';
		file_put_contents($tmp_file, 'TESTING DATA!');
		$errr = new ERestRequestReader($tmp_file);
		$this->assertEquals($errr->getContents(), 'TESTING DATA!');
	}

	/**
	 * getContents
	 * 
	 * tests ERestRequestReader->getContents with a resource stream
	 */ 
	public function testGetContentsWithStream()
	{
		$data = [
			"test11" => "someval",
			"test12" => "secondval"
			];
		$stream = fopen("php://temp", 'wb');
		fputs($stream, CJSON::encode($data));
		$errr = new ERestRequestReader($stream);
		$this->assertEquals(CJSON::encode($data), $errr->getContents());
	}
}
