<?php
namespace Rosio\EncryptedCookie\CryptoSystem;

use Rosio\EncryptedCookie\Exception\RNGUnavailableException;
use Rosio\EncryptedCookie\Exception\InputTamperedException;
use Rosio\EncryptedCookie\Exception\InputExpiredException;
use Rosio\EncryptedCookie\Exception\TIDMismatchException;

class AES_SHA implements iCryptoSystem
{
	const IV_SIZE = 16;

	protected $symmetricKey;
	protected $HMACKey;

	public function __construct($symmetricKey, $HMACKey)
	{
		if (strlen($symmetricKey) != 32)
			throw new \InvalidArgumentException('Symmetric key must be 32 bytes/characters long.');

		$this->symmetricKey = $symmetricKey;
		$this->HMACKey      = $HMACKey;
	}

	public function encrypt ($data, $expiration)
	{
		$iv = $this->getRandom(self::IV_SIZE);
		$atime = time();
		$tid = $this->getTID();

		$encData = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->symmetricKey, $data, MCRYPT_MODE_CBC, $iv);

		$hmac = $this->getHMAC($encData, $atime, $expiration, $tid, $iv);

		return base64_encode($encData) . '|' . base64_encode($atime) . '|' . base64_encode($expiration) . '|' . base64_encode($tid) . '|' . base64_encode($iv) . '|' . base64_encode($hmac);
	}

	public function decrypt ($data)
	{
		list($encData, $atime, $expiration, $tid, $iv, $hmac) = array_map('base64_decode', explode('|', $data));

		if (!self::ctComp($tid, $this->getTID()))
			throw new TIDMismatchException('The data TID no longer matches the crypto system TID.');

		$generatedHMAC = $this->getHMAC($encData, $atime, $expiration, $tid, $iv);

		if (!self::ctComp($hmac, $generatedHMAC))
			throw new InputTamperedException('The data HMAC no longer matches.');

		if ($expiration > 0 && $atime + $expiration < time())
			throw new InputExpiredException('The expiration time on the data has been reached.');

		$data = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->symmetricKey, $encData, MCRYPT_MODE_CBC, $iv), chr(0));

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

	static function ctComp ($value1, $value2)
	{
		$differences = 0;
		for ($i = 0; $i < max(strlen($value1), strlen($value2)); $i++)
		{
			$v1 = isset($value1[$i]) ? ord($value1[$i]) : -1;
			$v2 = isset($value2[$i]) ? ord($value2[$i]) : -1;

		    $differences |= $v1 ^ $v2;
		}
		return $differences === 0;
	}

	protected function getHMAC ($encryptedData, $aTime, $expiration, $tid, $iv)
	{
		return hash_hmac('sha256', base64_encode($encryptedData) . base64_encode($aTime) . base64_encode($expiration) . base64_encode($tid) . base64_encode($iv), $this->HMACKey, true);
	}

	/**
	 * Get a string which uniquely represents the algorithms and keys used to encrypt the data.
	 * @return string
	 */
	function getTID ()
	{
		return substr(md5(md5($this->symmetricKey) . 'AES_SHA' . md5($this->HMACKey)), 0, 8);
	}
}