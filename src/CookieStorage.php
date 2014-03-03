<?php
namespace Rosio\EncryptedCookie;

use Rosio\EncryptedCookie\EncryptedCookie;
use Rosio\EncryptedCookie\StorageSystem\iStorageSystem;
use Rosio\EncryptedCookie\CryptoSystem\iCryptoSystem;

class CookieStorage
{
	protected $group;

	protected $cryptoSystem;
	protected $storageSystem;

	public function __construct (iCryptoSystem $cryptoSystem, iStorageSystem $storageSystem = null, $group = null)
	{
		$this->cryptoSystem = $cryptoSystem;
		$this->storageSystem = $storageSystem;
		$this->group = $group;
	}

	/**
	 * Load a cookie from the browser.
	 * @param  string        $cookieName Name of the cookie to load
	 * @return PartialCookie             A partial cookie containing the name and data of the original cookie.
	 *
	 * @throws InputExpiredException If the cookie has gone past expiration.
	 * @throws InputTamperedExceptino If the cookie's value has been tampered with.
	 * @throws TIDMismatchException If the decryption algorithm is different from the encryption algorithm, most likely because the keys had changed.
	 */
	public function load ($cookieName)
	{
		if (!$this->getStorageSystem()->has($this->getCookieName($cookieName)))
			return null;

		$data = $this->getStorageSystem()->get($this->getCookieName($cookieName));

		$decryptedData = unserialize($this->getCryptoSystem()->decrypt($data));

		return new PartialCookie($cookieName, $decryptedData);
	}

	/**
	 * Save a cookie to the browser.
	 * @param  Cookie $cookie
	 * @return void
	 */
	public function save (Cookie $cookie)
	{
		$encryptedData = $this->getCryptoSystem()->encrypt(serialize($cookie->getData()), $cookie->getExpiration());

		$this->getStorageSystem()->set(
			$this->getCookieName($cookie->getName()),
			$encryptedData,
			$cookie->getExpiration(),
			$cookie->getDomain(),
			$cookie->getPath(),
			$cookie->isSecure(),
			$cookie->isHttpOnly()
		);
	}

	protected function getCookieName ($name)
	{
		return $this->group === null ? $name : $this->group . '_' . $name;
	}

	/* =============================================================================
	   Setters
	   ========================================================================== */
	
	public function setCryptoSystem (iCryptoSystem $cryptoSystem)
	{
		$this->cryptoSystem = $cryptoSystem;
	}

	public function setStorageSystem (iStorageSystem $storageSystem)
	{
		$this->storageSystem = $storageSystem;
	}

	/* =============================================================================
	   Getters
	   ========================================================================== */
	
	protected function getStorageSystem ()
	{
		if ($this->storageSystem === null)
			$this->storageSystem = new StorageSystem\NativeStorageSystem;

		return $this->storageSystem;
	}

	protected function getCryptoSystem ()
	{
		if ($this->cryptoSystem === null)
			throw new \BadMethodCallException('Crypto System must be set before trying to handle cookies.');

		return $this->cryptoSystem;
	}
}