<?php

require_once 'config.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'TweetPhoto' . DIRECTORY_SEPARATOR . 'Autoload.php';

try
{
	$Upload = new TweetPhoto_Upload;

	$Upload->addFile(new TweetPhoto_Upload_File('kitty.jpg'));

	// This is a test merp merp

	$Response = $Upload->upload();

	echo '<h3>HTTP Response:</h3>';
	echo '<pre>';
	print_r($Response->status);
	echo '</pre>';

	echo '<h3>Headers:</h3>';
	echo '<pre>';
	echo $Response->Headers;
	echo '</pre>';

	echo '<h3>Body:</h3>';
	echo '<pre>';
	print_r(str_replace('<', '&lt;', json_decode($Response->body)));
	echo '</pre>';

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