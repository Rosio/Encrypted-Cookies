<?php

namespace Rosio\EncryptedCookie\StorageSystem;

class MockStorageSystem implements iStorageSystem
{
	private $cookies = array();

	public function get ($name)
	{
		return $this->cookies[$name];
	}

	public function has ($name)
	{
		return isset($this->cookies[$name]);
	}

	public function set ($name, $data, $expiration, $domain, $path, $isSecure, $isHttpOnly)
	{
		$this->cookies[$name] = $data;
	}
}