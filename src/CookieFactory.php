<?php

namespace Rosio\EncryptedCookie;

use Rosio\EncryptedCookie\CryptoSystem\iCryptoSystem;

class CookieFactory
{
	private $cryptoSystem;

	function __construct (iCryptoSystem $cryptoSystem)
	{
		$this->cryptoSystem = $cryptoSystem;
	}

	function create ($name)
	{
		$cookie = new EncryptedCookie ($name);
		$cookie->setCryptoSystem($this->cryptoSystem);

		return $cookie;
	}

	function get ($name)
	{
		$cookie = new EncryptedCookie ($name);
		$cookie->setCryptoSystem($this->cryptoSystem)->load();

		return $cookie;
	}
}