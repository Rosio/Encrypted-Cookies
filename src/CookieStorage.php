<?php
namespace Rosio\EncryptedCookie;

use Rosio\EncryptedCookie\EncryptedCookie;

class CookieStorage
{
	protected $group;

	public function __construct ($group = null)
	{
		$this->group = $group;
	}

	public function has ($name)
	{
		return isset($_COOKIE[$this->getCookieName($name)]);
	}

	public function get ($name)
	{
		return $_COOKIE[$this->getCookieName($name)];
	}

	public function set (EncryptedCookie $cookie)
	{
		setcookie($cookie->getName(), $cookie->getEncryptedData(), $cookie->getExpiration(), $cookie->getPath(), $cookie->getDomain(), $cookie->isSecure(), $cookie->isHttpOnly());
	}

	protected function getCookieName ($name)
	{
		return $this->group === null ? $name : $this->group . '_' . $name;
	}
}