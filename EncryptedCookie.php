<?php
namespace Rosio\EncryptedCookie;

use EncryptedCookie\Exception\InputTooLargeException;
use EncryptedCookie\Exception\InputTamperedException;

class EncryptedCookie
{
	protected $name;

	protected $data;
	protected $domain;
	protected $path;
	protected $expiration;
	protected $isSecure;
	protected $isHttpOnly;

	function __construct ($name)
	{
		$this->name = $name;

		$this->domain = '';
		$this->path = '/';
		$this->expiration = 0;
		$this->isSecure = false;
		$this->isHttpOnly = true;

		// If the cookie exists, get it's information
		$this->load();
	}

	/**
	 * Load a cookie's data if available.
	 *
	 * @throws InputTamperedException If The returned cookie was modified locally.
	 */
	function load ()
	{
		if (!isset($_COOKIE[$this->name]))
			return false;

		$data = $_COOKIE[$this->name];

		$data = $this->decryptData($data);

		$this->data = $data;

		return $this;
	}

	/**
	 * Save a cookie's data.
	 *
	 * @throws InputTooLargeException If the encrypted cookie is larger than 4kb (the max size a cookie can be).
	 */
	function save ()
	{
		$edata = $this->encryptData($this->data);
		
		if (strlen($edata) >= 4096)
			throw new InputTooLargeException('Total encrypted data must be less than 4kb, or it will be truncated on the client.');

		setcookie($this->name, $edata, $this->expiration, $this->path, $this->domain, $this->isSecure, $this->isHttpOnly);

		return $this;
	}

	function decryptData ($data)
	{
		return $data;
	}

	function encryptData ($data)
	{
		return $data;
	}

	/* =============================================================================
	   Setters
	   ========================================================================== */
	
	/**
	 * Set the data to be stored in the cookie.
	 * Max is about 2.8kb (encrypting adds size).
	 * @param mixed $data Data to be stored in the cookie.  If it isn't a string it will be serialized.
	 */
	function setData ($data)
	{
		$this->data = serialize($data);

		// Strlen isn't multibyte, so it will give us the number of bytes in the data string
		if (strlen($data) > 2842)
			throw new InputTooLargeException('Data must be less than 2.842kb, or the encrypted cookie will be truncated by the browser.');

		return $this;
	}

	/**
	 * Set the expiration of the cookie.  This also corresponds to the date the cookie's data is considered invalid.
	 * @param int $expiration A Unix timestamp.
	 *
	 * @throws InvalidArgumentException If the given expiration isn't a valid unix timestamp.
	 */
	function setExpiration ($expiration)
	{
		if (!is_numeric($expiration) || $expiration < 0)
			throw new \InvalidArgumentException('Expiration must be a unix timestamp.');

		$this->expiration = $expiration;

		return $this;
	}

	/**
	 * The domain the cookie is valid for.
	 * @param string $domain
	 */
	function setDomain ($domain)
	{
		$this->domain = $domain;

		return $this;
	}

	/**
	 * The path the cookie is valid for.
	 * @param string $path
	 */
	function setPath ($path)
	{
		$this->path = $path;

		return $this;
	}

	/**
	 * Should this cookie only be sent to the server if there is a secure connection?
	 * @param boolean $isSecure
	 */
	function setSecure ($isSecure)
	{
		$this->isSecure = $isSecure;

		return $this;
	}

	/**
	 * Should this cookie only be available to the server (and not client-side scripts)?
	 * @param boolean $isHttpOnly
	 */
	function setHttpOnly ($isHttpOnly)
	{
		$this->isHttpOnly = $isHttpOnly;

		return $this;
	}

	/* =============================================================================
	   Getters
	   ========================================================================== */
	function getData ()
	{
		return unserialize($this->data);
	}

	function getExpiration ()
	{
		return $this->expiration;
	}

	function getPath ()
	{
		return $this->path;
	}

	function getDomain ()
	{
		return $this->domain;
	}

	function isSecure ()
	{
		return $this->isSecure;
	}

	function isHttpOnly ()
	{
		return $this->isHttpOnly;
	}
}