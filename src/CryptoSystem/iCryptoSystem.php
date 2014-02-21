<?php

namespace Rosio\EncryptedCookie\CryptoSystem;

interface iCryptoSystem
{
	public function encrypt ($data, $expiration);
	public function decrypt ($data);
}