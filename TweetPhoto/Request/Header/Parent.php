<?php

abstract class TweetPhoto_Request_Header_Parent
{
	const EOL_HEADER = "\r\n";
	const EOL_BLOCK  = "\r\n\r\n";

	private $status;
	private $body;

	public function __construct()
	{
		$this->status = array();
		$this->body   = null;
	}

	private function extractHttpStatus($string)
	{
		if(preg_match_all('#^http\/[0-9]+\.[0-9]+ [0-9]{3}#i', $string, $match))
		{
			$this->status[] = array
			(
				'original' => $match[0][0],
				'code'     => end(explode(' ', trim($match[0][0])))
			);

			return $match[0][0];
		}

		return null;
	}

	public function parse($response)
	{
		foreach(explode(self::EOL_BLOCK, $response) as $chunk)
		{
			if(is_null($this->extractHttpStatus($chunk)))
			{
				$this->body = $chunk;
			}
			else
			{
				if(strstr($chunk, self::EOL_HEADER))
				{
					$header_block = $chunk;
				}
			}
		}

		$HeaderBlock = new TweetPhoto_Request_Header_Block;

		foreach(explode(self::EOL_HEADER, $header_block) as $entry)
		{
			if(strstr($entry, ':'))
			{
				list($header, $value) = explode(':', $entry);

				$HeaderBlock->addHeader(new TweetPhoto_Request_Header(trim($header), trim($value)));
			}
		}

		return $this->buildResponse($response, $HeaderBlock);
	}

	private function buildResponse(&$response, TweetPhoto_Request_Header_Block &$HeaderBlock)
	{
		$Response = new stdClass;

		$Response->status  = $this->status;
		$Response->Headers = $HeaderBlock;
		$Response->body    = $this->body;

		return $Response;
	}
}