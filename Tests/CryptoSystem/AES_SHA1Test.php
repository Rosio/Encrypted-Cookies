<?php

namespace Tests\CryptoSystem;

use Rosio\EncryptedCookie\CryptoSystem\AES_SHA1;

class AES_SHA1Test extends \PHPUnit_Framework_TestCase
{
	protected $cryptoSystem;

	function setUp ()
	{
		$this->cryptoSystem = new AES_SHA1('symkey', 'hmackey');
	}

	public function testEncryptDecrypt ()
	{
		$data = 'Some data. Special characters: ' . chr(1) . chr(2) . chr(3) . '.';

		$encData = $this->cryptoSystem->encrypt($data, 0);
		$decData = $this->cryptoSystem->decrypt($encData);

		$this->assertEquals($data, $decData);
	}
}