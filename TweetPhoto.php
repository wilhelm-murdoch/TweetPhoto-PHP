<?php

// return status messages specific to function
// add a few more layers of validation
// add user settings functionality

require_once 'config.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'TweetPhoto/Autoload.php';

try
{
	$Upload = new TweetPhoto_Upload;

	$Upload->addFile(new TweetPhoto_Upload_File(realpath('kitty.jpg'), 'Huzzah!'));

	$Upload->upload();
}
catch(TweetPhoto_Exception $Exception)
{
	echo $Exception;
}