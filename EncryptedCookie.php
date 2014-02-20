<?php
namespace Rosio\EncryptedCookie;

use EncryptedCookie\Exception\InputTooLargeException;

class EncryptedCookie
{
	protected $name;

	function __construct ($name)
	{
		$this->name = $name;
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

	function setExpiration ($expiration)
	{
		$this->expiration = $expiration;

		return $this;
	}

	function setPath ($path)
	{
		$this->path = $path;

		return $this;
	}

	/* =============================================================================
	   Getters
	   ========================================================================== */
	function getData ()
	{

	}
}