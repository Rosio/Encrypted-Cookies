<?php

namespace Rosio\EncryptedCookie\StorageSystem;

interface iStorageSystem
{
	public function get ($name);
	public function has ($name);
	public function set ($name, $data, $expiration, $domain, $path, $isSecure, $isHttpOnly);
}