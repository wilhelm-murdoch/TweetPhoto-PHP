<?php

class TweetPhoto_Upload_Chunked extends TweetPhoto_Upload_Parent
{
	private $IteratorChunk;

	public function __construct($message = null, $tags = null, $metadata = null, $latitude = null, $longitude = null, $post_to_twitter = false, $isoauth = false, $vid = null, $venue = '', $tpservice = 'twitter')
	{
		parent::__construct(null, $message, $tags, $metadata, $latitude, $longitude, $post_to_twitter, $isoauth, $vid, $venue, $tpservice);

		$this->IteratorChunk = new TweetPhoto_Iterator;
	}

	public function getChunks()
	{
		return $this->IteratorChunk;
	}

	public function addChunk(TweetPhoto_Upload_Chunk $Chunk)
	{
		return $this->IteratorChunk->append($Chunk);
	}
}