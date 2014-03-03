<?php

namespace Tests;

use Mockery as m;

use Rosio\EncryptedCookie;
use Rosio\EncryptedCookie\CookieFactory;
use Rosio\EncryptedCookie\CryptoSystem\AES_SHA1;

class CookieFactoryTest extends \PHPUnit_Framework_TestCase
{
	public function tearDown ()
	{
		m::close();
	}

	public function testEncryptDecrypt ()
	{
		$storage = m::mock('\Rosio\EncryptedCookie\CookieStorage');
		$storage->shouldReceive('set')->once()->with(m::type('\Rosio\EncryptedCookie\EncryptedCookie'));
		$storage->shouldReceive('has')->once()->andReturn(true);
		$storage->shouldReceive('get')->once()->andReturn('blah');

		$factory = new CookieFactory(new AES_SHA1('asdfdasdfdasdfdasdfdasdfdasdfdsd', 'hmactest'), $storage);

		$data = 'blah';

		$cookie = $factory->create('testCookie')->setData($data)->save();

		$newCookie = $factory->get('testCookie');

		// Returned data wasn't the correctly encrypted data, so it should fail to decrypt it
		$this->assertFalse($newCookie->getData());
	}
}