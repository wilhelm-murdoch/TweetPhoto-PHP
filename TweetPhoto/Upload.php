<?php

class TweetPhoto_Upload
{
	private $UploadIteratorChunk;
	private $UploadIteratorFile;

	public function __construct()
	{
		$this->UploadIteratorChunk = new TweetPhoto_Upload_Iterator;
		$this->UploadIteratorFile  = new TweetPhoto_Upload_Iterator;
	}

	public function addChunk(TweetPhoto_Upload_Chunk $Chunk)
	{
		return $this->UploadIteratorChunk->appendChunk($Chunk);
	}

	public function addFile(TweetPhoto_Upload_File $File)
	{
		return $this->UploadIteratorFile->appendFile($File);
	}

	public function upload($type = TweetPhoto_Config::UPLOAD_BOTH)
	{
		if($type & (TweetPhoto_Config::UPLOAD_FILE | TweetPhoto_Config::UPLOAD_BOTH))
		{
			foreach($this->UploadIteratorFile as $File)
			{
				if(false == file_exists($Upload->media))
				{
					throw new TweetPhoto_Exception("Media file `{$Upload->media}` could not be located");
				}

				$headers = array
				(
					'TPAPIKEY: ' . TweetPhoto_Config::ACCOUNT_KEY,
					'TPUTF8: true',
					'TPMSG: ' . base64_encode($Upload->message),
					'TPTAGS: ' . base64_encode($Upload->tags),
					'TPMetadata: ' . base64_encode($Upload->metadata),
					"TPLAT: {$Upload->latitude}",
					"TPLONG: {$Upload->longitude}",
					"TPPOST: {$Upload->post_to_twitter}",
					"TPMIMETYPE: image/jpg",
					'Content-Type: application/x-www-form-urlencoded'
				);

				$response = TweetPhoto_Api::singleton()->sendRequest('/upload2', TweetPhoto_Config::HTTP_METHOD_POST, $headers, file_get_contents($Upload->media), false);
			}
		}

		if($type & (TweetPhoto_Config::UPLOAD_CHUNK | TweetPhoto_Config::UPLOAD_BOTH))
		{

		}

		return true;
	}
}