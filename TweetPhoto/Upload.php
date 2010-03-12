<?php

class TweetPhoto_Upload
{
	private $UploadIteratorChunked;
	private $UploadIteratorFile;

	public function __construct()
	{
		$this->UploadIteratorChunked = new TweetPhoto_Upload_Iterator;
		$this->UploadIteratorFile    = new TweetPhoto_Upload_Iterator;
	}

	public function addChunked(TweetPhoto_Upload_Chunked $Chunked)
	{
		return $this->UploadIteratorChunked->appendChunked($Chunked);
	}

	public function addFile(TweetPhoto_Upload_File $File)
	{
		return $this->UploadIteratorFile->appendFile($File);
	}

	public function upload($type = TweetPhoto_Config::UPLOAD_BOTH)
	{
		$headers = array();

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

		if($type & (TweetPhoto_Config::UPLOAD_CHUNKED | TweetPhoto_Config::UPLOAD_BOTH))
		{
			foreach($this->UploadIteratorChunked as $Chunked)
			{
				$IteratorChunks = $Chunked->getChunks();

				foreach($IteratorChunks as $Chunk)
				{
					$headers = array();

					$position = $IteratorChunks->key();

					if($position != 0)
					{
						$headers[] = "TPTXID: {$Chunk->transaction_id}";
					}

					$response = TweetPhoto_Api::singleton()->sendRequest('/uploadchunk', TweetPhoto_Config::HTTP_METHOD_POST, $headers, $Chunk->chunk, false);

					$IteratorChunks->next();

					if($IteratorChunks->valid())
					{
						$IteratorChunks->current()->transaction_id = $response['results']->TransactionId;
					}

					$IteratorChunks->seek($position);

					echo '<pre>';
					echo "code[{$response['code']}] {$position}: {$Chunk->transaction_id}\n";
					echo "Headers:\n";
					print_r($headers);
					echo '</pre>';
				}


				$headers = array
				(
					'TPAPIKEY: ' . TweetPhoto_Config::ACCOUNT_KEY,
					'TPUTF8: true',
					'TPMSG: ' . base64_encode('Chunk test'),
					'TPTAGS: ' . base64_encode(''),
					'TPMetadata: ' . base64_encode(''),
					"TPLAT: 50",
					"TPLONG: 50",
					"TPPOST: false",
					"TPMIMETYPE: image/jpg",
					"TPTXID: {$Chunk->transaction_id}",
					'Content-Type: application/x-www-form-urlencoded'
				);

				$response = TweetPhoto_Api::singleton()->sendRequest('/uploadchunkcomplete', TweetPhoto_Config::HTTP_METHOD_POST, $headers);

				echo '<pre>';
				echo "Response:\n";
				print_r($response);
				echo "Headers:\n";
				print_r($headers);
				echo '</pre>';
			}
		}

		return true;
	}
}
