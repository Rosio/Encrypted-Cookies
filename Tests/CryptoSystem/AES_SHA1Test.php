<?php

namespace Tests\CryptoSystem;

use Rosio\EncryptedCookie\CryptoSystem\AES_SHA1;

class AES_SHA1Test extends \PHPUnit_Framework_TestCase
{
	protected $cryptoSystem;

	function setUp ($suffix = '')
	{
		$this->cryptoSystem = new AES_SHA1('symkey' . $suffix, 'hmackey' . $suffix);
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
}