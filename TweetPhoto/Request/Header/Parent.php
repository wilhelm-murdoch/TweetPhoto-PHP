<?php

abstract class TweetPhoto_Request_Header_Parent
{
	const EOL_HEADER = "\r\n";
	const EOL_BLOCK  = "\r\n\r\n";

	private $status;
	private $body;

	public function __construct()
	{
		$this->status = null;
		$this->body   = null;
	}

	private function extractHttpStatus($string)
	{
		if(preg_match_all("#^http\/[0-9]+\.[0-9]+ ([0-9]{3})#i", $string, $match))
		{
			return $this->status = $match[1][0];
		}

		return null;
	}

	public function parse($response)
	{
		list($block, $this->body) = explode(self::EOL_BLOCK, $response, 2);

		$HeaderBlock = new TweetPhoto_Request_Header_Block;

		foreach(explode(self::EOL_HEADER, $block) as $entry)
		{
			if(strstr($entry, ':'))
			{
				list($header, $value) = explode(':', $entry);

				$HeaderBlock->addHeader(new TweetPhoto_Request_Header($header, $value));
			}
		}

		return array
		(
			'status' => $this->extractHttpStatus($response),
			'header' => $HeaderBlock,
			'body'   => $this->body
		);
	}
}