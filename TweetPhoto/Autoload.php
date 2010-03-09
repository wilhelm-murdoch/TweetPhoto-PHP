<?php

if(false == function_exists('autoLoadTweetPhoto'))
{
	function autoLoadTweetPhoto($class)
	{
		if(strpos($class, 'TweetPhoto_') === 0)
		{
			$path = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';

			if(file_exists($path))
			{
				require_once $path;
			}
		}

		return true;
	}

	spl_autoload_register('autoLoadTweetPhoto');
}