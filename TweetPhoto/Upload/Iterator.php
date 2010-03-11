<?php

class TweetPhoto_Upload_Iterator extends TweetPhoto_Iterator
{
	public function __construct()
	{
		parent::__construct();
	}

	public function appendChunk(TweetPhoto_Upload_Chunk $Chunk, $rewind = true)
	{
		return parent::append($Chunk, $rewind);
	}

	public function appendFile(TweetPhoto_Upload_File $File, $rewind = true)
	{
		return parent::append($File, $rewind);
	}
}