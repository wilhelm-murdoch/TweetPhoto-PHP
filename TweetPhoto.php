<?php

require_once 'config.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'TweetPhoto' . DIRECTORY_SEPARATOR . 'Autoload.php';

try
{

$Request = new TweetPhoto_Request('http://tweetphotoapi.com/api/tpapi.svc/json/socialfeed?omg=banana&blah=d=d=d=d');

echo $Request->buildQueryString(array('one' => 'blah', 'foo' => 'bar'));

$Block = new TweetPhoto_Request_Header_Block;

$Block->addHeader(new TweetPhoto_Request_Header('content-type', 'application/json'));
$Block->addHeader(new TweetPhoto_Request_Header('pragma', 'no-cache'));
$Block->addHeader(new TweetPhoto_Request_Header('language', 'en'));

$Request = new TweetPhoto_Request('http://tweetphotoapi.com/api/tpapi.svc/json/socialfeed', TweetPhoto_Request::HTTP_METHOD_POST, json_encode(array('foo', 'bar')));

$Request->addHeaderBlock($Block);


//$Request->dispatch();

//$Request->dispatch();


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