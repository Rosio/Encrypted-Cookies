<?php

namespace Tests;

use Mockery as m;

use Rosio\EncryptedCookie;
use Rosio\EncryptedCookie\CookieStorage;
use Rosio\EncryptedCookie\Cookie;
use Rosio\EncryptedCookie\CryptoSystem;
use Rosio\EncryptedCookie\StorageSystem;

class CookieStorageTest extends \PHPUnit_Framework_TestCase
{
	public function tearDown ()
	{
		m::close();
	}

	public function testEncryptDecrypt ()
	{
		$storage = new CookieStorage;

		$storage->setCryptoSystem(new CryptoSystem\AES_SHA('asdfqwerasasdfqwerasasdfqwerassd', 'hmactest'));
		$storage->setStorageSystem(new StorageSystem\MockStorageSystem);

		$data = 'blah';

		$cookie = Cookie::create('testCookie', $data);

		$storage->save($cookie);

		$newCookie = $storage->load('testCookie');

		// Returned data wasn't the correctly encrypted data, so it should fail to decrypt it
		$this->assertEquals($data, $newCookie->getData());
	}
}