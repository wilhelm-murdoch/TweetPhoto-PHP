<?php

class TweetPhoto_Request_Header_Block extends TweetPhoto_Request_Header_Parent implements Countable
{
	private $HeaderIterator;

	public function __construct()
	{
		parent::__construct();

		$this->HeaderIterator = new TweetPhoto_Iterator;
	}

	public function count()
	{
		return count($this->HeaderIterator);
	}

	public function addHeader(TweetPhoto_Request_Header $Header)
	{
		return $this->HeaderIterator->append($Header);
	}

	public function __toString()
	{
		$return = '';

		foreach($this->HeaderIterator as $Header)
		{
			$return .= ($this->HeaderIterator->key() != 0 ? self::EOL_HEADER : '') . $Header;
		}

		return $return . self::EOL_BLOCK;
	}
}