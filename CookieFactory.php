<?php

namespace Rosio\EncryptedCookie;

class CookieFactory
{
	static function createCookie ($name)
	{
		return new EncryptedCookie ($name);
	}

	static function getCookie ($name)
	{
		return new EncryptedCookie($name)->load();
	}
}