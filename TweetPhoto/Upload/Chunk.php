<?php

class TweetPhoto_Upload_Chunk
{
	private $transaction_id;
	private $chunk;

	public function __construct($chunk, $transaction_id = null)
	{
		$this->transaction_id = $transaction_id;
		$this->chunk          = $chunk;
	}
}