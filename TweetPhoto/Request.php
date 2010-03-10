<?php

class TweetPhoto_Request
{
	static public function build(array $parameters = array(), $separator = TweetPhoto_Config::PARAM_SEPARATOR)
	{
		foreach($parameters as $key => $value)
		{
			switch($key)
			{

			}
		}

		return http_build_query($parameters, null, $separator);
	}

	static public function send($url, $method = TweetPhoto_Config::HTTP_METHOD_GET, array $headers = array(), $data = null)
	{

	}
}