<?php
namespace Rosio\EncryptedCookie\CryptoSystem;

class MOCK_MOCK implements iCryptoSystem
{
	public function encrypt ($data, $expiration)
	{
		return $data;
	}

	public function decrypt ($data)
	{
		return $data;
	}
}