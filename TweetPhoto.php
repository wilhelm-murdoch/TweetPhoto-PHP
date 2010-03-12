<?php

require_once 'config.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'TweetPhoto' . DIRECTORY_SEPARATOR . 'Autoload.php';

try
{

$Request = new TweetPhoto_Request('http://tweetphotoapi.com/api/tpapi.svc/json/socialfeed?omg=banana&blah');

$Request->buildQueryString(array('one' => 'blah', 'foo' => 'bar'));

$Request->addHeader(new TweetPhoto_Request_Header('Accept',       'text/plain'));
$Request->addHeader(new TweetPhoto_Request_Header('Content-Type', 'application/json'));


//	$Upload = new TweetPhoto_Upload;
//
//	$Chunked = new TweetPhoto_Upload_Chunked;
//
//	$Chunked->addChunk(new TweetPhoto_Upload_Chunk(file_get_contents('kitty.jpg-chunk-1.txt')));
//	$Chunked->addChunk(new TweetPhoto_Upload_Chunk(file_get_contents('kitty.jpg-chunk-2.txt')));
//	$Chunked->addChunk(new TweetPhoto_Upload_Chunk(file_get_contents('kitty.jpg-chunk-3.txt')));
//	$Chunked->addChunk(new TweetPhoto_Upload_Chunk(file_get_contents('kitty.jpg-chunk-4.txt')));
//
//	$Upload->addChunked($Chunked);
//
//	$Upload->upload(TweetPhoto_Config::UPLOAD_CHUNKED);
}
catch(TweetPhoto_Exception $Exception)
{
	echo $Exception;
}