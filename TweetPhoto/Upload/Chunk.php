<?php

class TweetPhoto_Upload_Chunk
{
	private $chunk;
	private $transaction_id;

	public function __construct($chunk, $transaction_id = null)
	{
		$this->chunk          = $chunk;
		$this->transaction_id = $transaction_id;
	}

	public function __get($property)
	{
		return $this->$property;
	}

	public function __set($property, $value)
	{
		return $this->$property = $value;
	}
}