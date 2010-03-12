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

	const HEADER_REQUEST  = 1;
	const HEADER_RESPONSE = 2;

	const EOL_HEADER = "\r\n";
	const EOL_BLOCK  = "\r\n\r\n";

	private $RequestHeaderIterator;
	private $ResponseHeaderIterator;

	private $url;
	private $method;
	private $status;

	public function __construct($url, $http_method = self::HTTP_METHOD_GET)
	{
		$this->url    = $url;
		$this->method = $http_method;
		$this->status = null;

		$this->RequestHeaderIterator  = new TweetPhoto_Iterator;
		$this->ResponseHeaderIterator = new TweetPhoto_Iterator;
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
						list($key, $value) = explode('=', $argument);

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

	public function addHeader(TweetPhoto_Request_Header $Header)
	{
		return $this->RequestHeaderIterator->append($Header);
	}

	public function buildHeaderBlock($type = TweetPhoto_Request::HEADER_REQUEST)
	{
		$return = '';

		if($type & TweetPhoto_Request::HEADER_REQUEST)
		{
			foreach($this->RequestHeaderIterator as $Header)
			{
				$return .= ($this->RequestHeaderIterator->key() != 0 ? self::EOL_HEADER : '') . $Header;
			}
		}

		if($type & TweetPhoto_Request::HEADER_RESPONSE)
		{
			foreach($this->ResponseHeaderIterator as $Header)
			{
				$return .= ($this->ResponseHeaderIterator->key() != 0 ? self::EOL_HEADER : '') . $Header;
			}
		}

		return $return;
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