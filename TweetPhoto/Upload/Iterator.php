<?php

class TweetPhoto_Upload_Iterator extends TweetPhoto_Iterator
{
	public function __construct()
	{
		parent::__construct();
	}

	public function appendChunked(TweetPhoto_Upload_Chunked $Chunked, $rewind = true)
	{
		return parent::append($Chunked, $rewind);
	}

	public function appendFile(TweetPhoto_Upload_File $File, $rewind = true)
	{
		return parent::append($File, $rewind);
	}
}