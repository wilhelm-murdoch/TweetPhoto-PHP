<?php

class TweetPhoto_Request_Header
{
	private $header;
	private $value;

	public function __construct($header, $value)
	{
		$this->header = $header;
		$this->value  = $value;
	}

	public function __get($property)
	{
		return $this->$property;
	}

	public function __toString()
	{
		return "{$this->header}: {$this->value}";
	}
}