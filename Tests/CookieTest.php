<?php

namespace Tests;

use \Mockery as m;
use Rosio\EncryptedCookie\Cookie;
use Rosio\EncryptedCookie\CookieStorage;
use Rosio\EncryptedCookie\CryptoSystem\MOCK_MOCK;

class CookieTest extends \PHPUnit_Framework_TestCase
{
	private $cookie;

	public function setUp ()
	{
		$this->createCookie();
	}

	public function tearDown ()
	{
		m::close();
	}

	protected function createCookie ($name = 'test')
	{
		$this->cookie = new Cookie($name);
	}

	public function testGettersSetters ()
	{
		$this->assertEquals($this->cookie->getName(), 'test');

		$this->cookie->setData('test');
		$this->assertEquals($this->cookie->getData(), 'test');

		$time = time() * 60 * 60 * 24; // 24 hours
		$this->cookie->setExpiration($time);
		$this->assertEquals($this->cookie->getExpiration(), $time);

		$this->cookie->setDomain('google.com');
		$this->assertEquals($this->cookie->getDomain(), 'google.com');

		$this->cookie->setPath('/test/');
		$this->assertEquals($this->cookie->getPath(), '/test/');

		$this->cookie->setSecure(true);
		$this->assertTrue($this->cookie->isSecure());

		$this->cookie->setHttpOnly(true);
		$this->assertTrue($this->cookie->isHttpOnly());
	}
}