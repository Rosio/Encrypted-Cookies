<?php
namespace Rosio\EncryptedCookie\CryptoSystem;

use Rosio\EncryptedCookie\Exception\RNGUnavailableException;
use Rosio\EncryptedCookie\Exception\InputTamperedException;

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

		$hmac = $this->getHMAC($encData, $atime, $tid, $iv);

		return base64_encode($encData) . '|' . base64_encode($atime) . '|' . base64_encode($tid) . '|' . base64_encode($iv) . '|' . base64_encode($hmac);
	}

	public function decrypt ($data)
	{
		list($encData, $atime, $tid, $iv, $hmac) = array_map('base64_decode', explode('|', $data));

		if ($tid !== $this->getTID())
			throw new TIDMismatchException('The data TID no longer matches the crypto system TID.');

		$generatedHMAC = $this->getHMAC($encData, $atime, $tid, $iv);

		if ($hmac !== $generatedHMAC)
			throw new InputTamperedException('The data HMAC no longer matches.');

		$data = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->symmetricKey, $data, MCRYPT_MODE_CBC, $iv), chr(0));

		return $data;
	}

	protected function getRandom ($length)
	{
		$wasCryptoSecure = false;
		$random = openssl_random_pseudo_bytes($length, $wasCryptoSecure);

		if ($wasCryptoSecure !== true)
			throw new RNGUnavailableException('The RNG was unable to provide truely random numbers.');

		return $random;
	}

	protected function getHMAC ($encryptedData, $aTime, $tid, $iv)
	{
		return hash_hmac('sha1', base64_encode($encryptedData) . base64_encode($aTime) . base64_encode($tid) . base64_encode($iv), $this->HMACKey, true);
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