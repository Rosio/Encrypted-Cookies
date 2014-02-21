<?php

namespace Tests;

use Rosio\EncryptedCookie;
use Rosio\EncryptedCookie\CookieFactory;
use Rosio\EncryptedCookie\CryptoSystem\AES_SHA1;

class CookieFactoryTest extends \PHPUnit_Framework_TestCase
{
	public function testEncryptDecrypt ()
	{
		$this->markTestIncomplete('Need to find workaround so as to not try and actually set a cookie.');

		$factory = new CookieFactory(new AES_SHA1('symtest', 'hmactest'));

		$data = 'blah';

		$cookie = $factory->create('testCookie')->setData($data)->save();

		$newCookie = $factory->get('testCookie');

		$this->assertEquals($data, $newCookie->getData());
	}
}