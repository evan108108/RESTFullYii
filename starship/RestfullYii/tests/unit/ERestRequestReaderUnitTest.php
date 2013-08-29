<?php
Yii::import('ext.starship.RestfullYii.components.ERestRequestReader');

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
	 * __constuct
	 *
	 * tests ERestRequestReader constructor
	 */
	public function testConstruct()
	{
		$ERRR = new ERestRequestReader();
		$this->assertEquals($this->getPrivateProperty($ERRR, 'filename'), 'php://input');

		$ERRR = new ERestRequestReader('/tmp/myfile.txt');
		$this->assertEquals($this->getPrivateProperty($ERRR, 'filename'), '/tmp/myfile.txt');
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
		$ERRR = new ERestRequestReader($tmp_file);
		$this->assertEquals($ERRR->getContents(), 'TESTING DATA!');
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
		$ERRR = new ERestRequestReader($stream);
		$this->assertEquals(CJSON::encode($data), $ERRR->getContents());
	}
}
