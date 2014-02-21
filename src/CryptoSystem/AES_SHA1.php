<?php
namespace Rosio\EncryptedCookie\CryptoSystem;

use Rosio\EncryptedCookie\Exception\RGPUnavailable;

class AES_SHA1
{
	const IV_SIZE = 16;

	private $symmetricKey;
	private $HMACKey;

	public function __construct($symmetricKey, $HMACKey)
	{
		$this->symmetricKey = $symmetricKey;
		$this->HMACKey      = $HMACKey;
	}

	public function encrypt ($data)
	{
		$iv = $this->getRandom(self::IV_SIZE);
		$atime = time();
		$tid = $this->getTID();

		$encData = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->symmetricKey, $data, MCRYPT_MODE_CBC, $iv);

		$mac = hash_hmac('sha1', base64_encode($encData) . base64_encode($atime) . base64_encode($tid) . base64_encode($iv), $this->HMACKey, true);

		return base64_encode($encData) . '|' . base64_encode($atime) . '|' . base64_encode($tid) . '|' . base64_encode($iv) . '|' . base64_encode($mac);
	}

	public function decrypt ($data)
	{
		return $data;
	}

	protected function getRandom ($length)
	{
		$wasCryptoSecure = false;
		$random = openssl_random_pseudo_bytes($length, $wasCryptoSecure);

		if ($wasCryptoSecure !== true)
			throw new RGPUnavailable();

		return $random;
	}

	function setIVSize ($size)
	{
		$this->IVSize = $size;
	}

	/**
	 * Get a string which uniquely represents the algorithms and keys used to encrypt the data.
	 * @return string
	 */
	function getTID ()
	{
		return substr(md5(md5($this->symmetricKey) . 'AES_SHA1' . md5($this->HMACKey)), 0, 8);
	}
}