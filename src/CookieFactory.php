<?php

namespace Rosio\EncryptedCookie;

use Rosio\EncryptedCookie\CryptoSystem\iCryptoSystem;
use Rosio\EncryptedCookie\CookieStorage;

class CookieFactory
{
	private $cryptoSystem;
	private $cookieStorage;

	function __construct (iCryptoSystem $cryptoSystem, CookieStorage $cookieStorage = null)
	{
		$this->cryptoSystem = $cryptoSystem;

		if ($cookieStorage === null)
			$this->cookieStorage = new CookieStorage;
		else
			$this->cookieStorage = $cookieStorage;
	}

	function create ($name)
	{
		$cookie = new EncryptedCookie ($name);
		$cookie->setCryptoSystem($this->cryptoSystem);
		$cookie->setCookieStorage($this->cookieStorage);

		return $cookie;
	}

	function get ($name)
	{
		$cookie = new EncryptedCookie ($name);
		$cookie->setCryptoSystem($this->cryptoSystem);
		$cookie->setCookieStorage($this->cookieStorage);

		$cookie->load();

		return $cookie;
	}
}