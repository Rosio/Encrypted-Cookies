<?php

namespace Rosio\EncryptedCookie\CryptoSystem;

interface iCryptoSystem
{
	public function encrypt ($data);
	public function decrypt ($data);
}