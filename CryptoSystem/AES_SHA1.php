<?php
namespace Rosio\EncryptedCookie\CryptoSystem;

class AES_SHA1
{
	private $symmetricKey;
	private $HMACKey;

	public function __construct($symmetricKey, $HMACKey)
	{
		$this->symmetricKey = $symmetricKey;
		$this->HMACKey      = $HMACKey;
	}

	public function encrypt ($data)
	{
		return $data;
	}

	public function decrypt ($data)
	{
		return $data;
	}

	protected function getRandom ($length)
	{
		return openssl_random_pseudo_bytes($length, true);
	}
}