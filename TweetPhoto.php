<?php

// return status messages specific to function
// add a few more layers of validation
// add user settings functionality

require_once 'config.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'TweetPhoto/Autoload.php';

try
{
	echo '<pre>';
	print_r(TweetPhoto_Api::singleton()->addLinkToService('Twitter', 'test', 'merp'));
	echo '</pre>';
}
catch(TweetPhoto_Exception $Exception)
{
	echo $Exception;
}