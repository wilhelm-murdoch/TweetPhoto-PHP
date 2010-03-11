<?php

class TweetPhoto_Upload_File extends TweetPhoto_Upload_Parent
{
	public function __construct($media, $message = null, $tags = null, $metadata = null, $latitude = null, $longitude = null, $post_to_twitter = false, $isoauth = false, $vid = null, $venue = '', $tpservice = 'twitter')
	{
		parent::__construct($media, $message, $tags, $metadata, $latitude, $longitude, $post_to_twitter, $isoauth, $vid, $venue, $tpservice);
	}

	public function __get($property)
	{
		return $this->$property;
	}
}