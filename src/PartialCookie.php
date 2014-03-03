<?php
namespace Rosio\EncryptedCookie;

class PartialCookie
{
	protected $name;
	protected $data;

	public function __construct ($name, $data)
	{
		if (!is_string($name))
			throw new \InvalidArgumentException('name must be a string.');

		$this->name = $name;
		$this->data = $data;
	}

	public function getName ()
	{
		return $this->name;
	}

	public function getData ()
	{
		return $this->data;
	}
}