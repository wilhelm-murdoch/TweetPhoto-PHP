<?php

class TweetPhoto_Exception extends Exception
{
	public function __construct($message, $code = null)
	{
		parent::__construct($message, $code);
	}
}