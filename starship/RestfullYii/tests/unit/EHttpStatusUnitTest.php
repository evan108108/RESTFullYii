<?php
Yii::import('RestfullYii.components.EHttpStatus');

/**
 * EHttpStatusUnitTest
 *
 * Tests EHttpStatus
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class EHttpStatusUnitTest extends ERestTestCase
{
	/**
	 * testConstsHaveMessages
	 *
	 * tests consts have messages
	 */
	public function testConstsHaveMessages()
	{
		$msg = EHttpStatus::$messages;
		foreach($this->getConsts() as $code)
		{
			$this->assertTrue(isset($msg[$code]));
		}
	}

	/**
	 * __construct
	 *
	 * tests EHttpStatus constructor
	 */ 
	public function testConstruct()
	{
		$ehs = new EHttpStatus();
		$this->assertEquals(200, $ehs->code);
		$this->assertEquals('OK', $ehs->message);

		foreach($this->getConsts() as $code)
		{
			$ehs = new EHttpStatus($code);
			$this->assertEquals($code, $ehs->code);
			$this->assertEquals(EHttpStatus::$messages[$code], $ehs->message);
		}

		$ehs = new EHttpStatus(406, 'You are not acceptable!');
		$this->assertEquals(406, $ehs->code);
		$this->assertEquals('You are not acceptable!', $ehs->message);
	}

	/**
	 * set
	 *
	 * tests EHttpStatus->set()
	 */
	public function testSet()
	{
		$ehs = new EHttpStatus();
		$ehs->set(505, 'Not Supported');
		$this->assertEquals(505, $ehs->code);
		$this->assertEquals('Not Supported', $ehs->message);
	}

	/**
	 * __toString
	 *
	 * tests EHttpStatus __toString magic method
	 */ 
	public function testToString()
	{
		foreach($this->getConsts() as $code)
		{
			$ehs = new EHttpStatus($code);
			$this->assertEquals('HTTP/1.1 '.$code.' '.$ehs->message, (string) $ehs);
		}
	}

	/**
	 * getConsts
	 *
	 * returns the list of constants in the EHttpStatus class
	 *
	 * @return (Array) list of EHttpStatus constants
	 */ 
	public function getConsts()
	{
		$refl = new ReflectionClass('EHttpStatus');
		return $refl->getConstants();
	}	
}
