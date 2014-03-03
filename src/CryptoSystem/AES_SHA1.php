<?php
namespace Rosio\EncryptedCookie\CryptoSystem;

use Rosio\EncryptedCookie\Exception\RNGUnavailableException;
use Rosio\EncryptedCookie\Exception\InputTamperedException;
use Rosio\EncryptedCookie\Exception\InputExpiredException;
use Rosio\EncryptedCookie\Exception\TIDMismatchException;

class AES_SHA1 extends AES_SHA implements iCryptoSystem
{
	protected function getHMAC ($encryptedData, $aTime, $expiration, $tid, $iv)
	{
		return hash_hmac('sha1', base64_encode($encryptedData) . base64_encode($aTime) . base64_encode($expiration) . base64_encode($tid) . base64_encode($iv), $this->HMACKey, true);
	}
}