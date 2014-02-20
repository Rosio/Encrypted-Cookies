<?php
namespace Rosio\EncryptedCookie;

use EncryptedCookie\Exception\InputTooLargeException;

class EncryptedCookie
{
	protected $name;

	protected $domain;
	protected $path;
	protected $expiration;
	protected $isSecure;
	protected $isHttpOnly;

	function __construct ($name)
	{
		$this->name = $name;
	}

	protected static function tryUnserialize ($data)
	{
		// Catch edge-case where the unserialized data is false.
		if ($data === serialize(false))
			return false;

		return ($udata = unserialize($data)) !== false ? $udata : $data;
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
		$this->data = !is_string($data) ? serialize($data) : $data;

		// Strlen isn't multibyte, so it will give us the number of bytes in the data string
		if (strlen($data) > 2842)
			throw new InputTooLargeException('Data must be less than 2.842kb, or the encrypted cookie will be truncated by the browser.');

		return $this;
	}

	/**
	 * Set the expiration of the cookie.  This also corresponds to the date the cookie's data is considered invalid.
	 * @param int $expiration A Unix timestamp.
	 */
	function setExpiration ($expiration)
	{
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
		return self::tryUnserialize($this->data);
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