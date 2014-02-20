<?php

namespace Rosio\EncryptedCookie;

class CookieFactory
{
	private $cryptoSystem;

	function __construct (iCryptoSystem $cryptoSystem)
	{
		$this->cryptoSystem = $cryptoSystem;
	}

	function createCookie ($name)
	{
		return new EncryptedCookie ($name)->setCryptoSystem($this->cryptoSystem);
	}

	function getCookie ($name)
	{
		return new EncryptedCookie($name)->setCryptoSystem($this->cryptoSystem)->load();
	}
}