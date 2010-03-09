<?php

// return status messages specific to function
// add a few more layers of validation
// add linking functionality
// add user settings functionality
// propery status responses and error handling

require_once 'config.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'TweetPhoto/Autoload.php';


try
{
	echo '<pre>';
	print_r(TweetPhoto_Api::singleton()->getQueryCount('cute'));
	echo '</pre>';
}
catch(TweetPhoto_Exception $Exception)
{
	echo $Exception;
}