<?php
namespace Rosio\EncryptedCookie;

use InvalidArgumentException;

class Cookie
{
	protected $name;

	protected $data;
	protected $domain;
	protected $path;
	protected $expiration;
	protected $isSecure;
	protected $isHttpOnly;

	public function __construct ($name, $options = array())
	{
		$this->name = $name;

		$defaults = array (
			'data' => '',
			'domain' => '',
			'path' => '/',
			'expiration' => 0,
			'isSecure' => false,
			'isHttpOnly' => true,
		);

		// Set options/defaults
		foreach ($defaults as $name => $default)
			$this->$name = isset($options[$name]) ? $options[$name] : $default;
	}

	public static function create ($name, $data = '', $expiration = 0)
	{
		return new static($name, array(
			'data' => $data,
			'expiration' => $expiration
		));
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
		$this->data = $data;

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
			throw new InvalidArgumentException('Expiration must be a unix timestamp.');

		$this->expiration = $expiration;

		return $this;
	}

	/**
	 * The domain the cookie is valid for.
	 * @param string $domain
	 *
	 * @throws InvalidArgumentException If the domain is not a string.
	 */
	function setDomain ($domain)
	{
		if (!is_string($domain))
			throw new InvalidArgumentException('Domain must be a string.');

		$this->domain = $domain;

		return $this;
	}

	/**
	 * The path the cookie is valid for.
	 * @param string $path
	 *
	 * @throws InvalidArgumentException If the path is not a string.
	 */
	function setPath ($path)
	{
		if (!is_string($path))
			throw new InvalidArgumentException('Path must be a string.');

		$this->path = $path;

		return $this;
	}

	/**
	 * Should this cookie only be sent to the server if there is a secure connection?
	 * @param boolean $isSecure
	 *
	 * @throws InvalidArgumentException If isSecure is not a boolean.
	 */
	function setSecure ($isSecure)
	{
		if (!is_bool($isSecure))
			throw new InvalidArgumentException('isSecure must be a boolean.');

		$this->isSecure = $isSecure;

		return $this;
	}

	/**
	 * Should this cookie only be available to the server (and not client-side scripts)?
	 * @param boolean $isHttpOnly
	 *
	 * @throws InvalidArgumentException If isHttpOnly is not a boolean.
	 */
	function setHttpOnly ($isHttpOnly)
	{
		if (!is_bool($isHttpOnly))
			throw new InvalidArgumentException('isHttpOnly must be a boolean.');

		$this->isHttpOnly = $isHttpOnly;

		return $this;
	}


	/* =============================================================================
	   Getters
	   ========================================================================== */
	
	function getName ()
	{
		return $this->name;
	}

	function getData ()
	{
		return $this->data;
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