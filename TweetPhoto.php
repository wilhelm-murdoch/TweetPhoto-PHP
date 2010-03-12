<?php

require_once 'config.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'TweetPhoto' . DIRECTORY_SEPARATOR . 'Autoload.php';

try
{
	$Upload = new TweetPhoto_Upload;

	//$Upload->addFile(new TweetPhoto_Upload_File(realpath('kitty.jpg'), 'Huzzah!'));

	$Upload->upload(TweetPhoto_Config::UPLOAD_CHUNK);
}
catch(TweetPhoto_Exception $Exception)
{
	echo $Exception;
}