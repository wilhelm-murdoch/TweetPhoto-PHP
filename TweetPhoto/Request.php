<?php

class TweetPhoto_Request
{
	const ARG_SEPARATOR = '&';

	const HTTP_METHOD_GET    = 1;
	const HTTP_METHOD_POST   = 2;
	const HTTP_METHOD_PUT    = 4;
	const HTTP_METHOD_DELETE = 8;

	const REQUEST_METHOD_CURL  = 1;
	const REQUEST_METHOD_SOCKS = 2;

	private $HeaderBlock;

	private $url;
	private $method;

	public function __construct($url, $method = self::HTTP_METHOD_GET, $data = null)
	{
		$this->url    = $url;
		$this->method = $method;

		$this->HeaderBlock = null;
	}

	public function buildQueryString(array $arguments, $append_to_url = true)
	{
		if($append_to_url)
		{
			if(strstr($this->url, '?'))
			{
				$existing_arguments = array();

				foreach(explode(self::ARG_SEPARATOR, end(explode('?', $this->url))) as $argument)
				{
					if(strstr($argument, '='))
					{
						list($key, $value) = explode('=', $argument, 2);

						$existing_arguments[$key] = $value;
					}
					else
					{
						$existing_arguments[$argument] = '';
					}
				}

				$arguments = array_merge($existing_arguments, $arguments);
			}

			$query_string = http_build_query($arguments, null, self::ARG_SEPARATOR);

			$this->url = array_shift(explode('?', $this->url)) . "?{$query_string}";

			return $query_string;
		}

		$query_string = http_build_query($arguments, null, self::ARG_SEPARATOR);

		return $query_string;
	}

	public function addHeaderBlock(TweetPhoto_Request_Header_Block &$HeaderBlock)
	{
		if(false == is_null($this->HeaderBlock))
		{
			throw new TweetPhoto_Exception('There may only be one instance of class TweetPhoto_Request_Header_Block per class TweetPhoto_Request.');
		}

		$this->HeaderBlock = &$HeaderBlock;

		return true;
	}

	public function send($method = self::HTTP_METHOD_CURL)
	{
		if($method & self::HTTP_METHOD_CURL)
		{
			if(false == function_exists('curl_exec'))
			{

			}
		}
		else
		{

		}
	}
}