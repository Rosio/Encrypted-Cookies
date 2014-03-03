<?php

namespace Tests\CryptoSystem;

use Rosio\EncryptedCookie\CryptoSystem\AES_SHA1;

class AES_SHA1Test extends \PHPUnit_Framework_TestCase
{
	protected $cryptoSystem;

	function setUp ($suffix = '1')
	{
		$this->cryptoSystem = new AES_SHA1('asdfdasdfdasdfdasdfdasdfdasdfds' . $suffix, 'hmackey' . $suffix);
	}

	public function testEncryptDecrypt ()
	{
		$data = 'Some data. Special characters: ' . chr(1) . chr(2) . chr(3) . '.';

		$encData = $this->cryptoSystem->encrypt($data, 0);
		$decData = $this->cryptoSystem->decrypt($encData);

		$this->assertEquals($data, $decData);
	}

	/**
	 * @expectedException \Rosio\EncryptedCookie\Exception\InputExpiredException
	 */
	public function testExpiration ()
	{
		$data = 'Some data.';

		$encData = $this->cryptoSystem->encrypt($data, 1);
		sleep(2);
		$decData = $this->cryptoSystem->decrypt($encData);
	}

	public function testOkayExpiration ()
	{
		$data = 'Some data.';

		$encData = $this->cryptoSystem->encrypt($data, 2);
		$decData = $this->cryptoSystem->decrypt($encData);

		$this->assertEquals($data, $decData);
	}

	/**
	 * @expectedException \Rosio\EncryptedCookie\Exception\TIDMismatchException
	 */
	public function testTIDMismatch ()
	{
		$data = 'Some data.';

		$encData = $this->cryptoSystem->encrypt($data, 2);
		$this->setUp('2'); // Force new keys
		$decData = $this->cryptoSystem->decrypt($encData);
	}

	/**
	 * @expectedException \Rosio\EncryptedCookie\Exception\InputTamperedException
	 */
	public function testInputTampered ()
	{
		$data = 'Some data.';

		$encData = $this->cryptoSystem->encrypt($data, 2);

		$badEncData = substr($encData, 0, -4); // Do 4 to make sure we don't get base64's padding
		$this->assertNotEquals($encData, $badEncData);
		
		$decData = $this->cryptoSystem->decrypt($badEncData);
	}
}