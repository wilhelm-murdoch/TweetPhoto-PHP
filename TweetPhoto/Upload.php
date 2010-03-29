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

	private function getMimeType($media)
	{
		$mime_types = array
		(
			'png'  => 'image/png',
			'jpe'  => 'image/jpg',
			'jpeg' => 'image/jpg',
			'jpg'  => 'image/jpg',
			'gif'  => 'image/gif',
			'bmp'  => 'image/bmp',
			'ico'  => 'image/vnd.microsoft.icon',
			'tiff' => 'image/tiff',
			'tif'  => 'image/tiff',
			'svg'  => 'image/svg+xml',
			'svgz' => 'image/svg+xml'
		);

		$extension = strtolower(end(explode('.', $media)));

		if(array_key_exists($extension, $mime_types))
		{
			$mime_type = $mime_types[$extension];
		}

		if(function_exists('finfo_open'))
		{
			$finfo = finfo_open(FILEINFO_MIME);

			$mime_type = finfo_file($finfo, $filename);

			finfo_close($finfo);
		}

		if(false == in_array($mime_type, array('image/png', 'image/jpg')))
		{
			throw new TweetPhoto_Exception("{$mime_type} is not a valid mime type for {$media}.");
		}

		return $mime_type;
	}

	public function upload($type = TweetPhoto_Config::UPLOAD_BOTH)
	{
		$headers = array();

		if($type & (TweetPhoto_Config::UPLOAD_FILE | TweetPhoto_Config::UPLOAD_BOTH))
		{
			foreach($this->UploadIteratorFile as $File)
			{
				if(false == file_exists($File->media))
				{
					throw new TweetPhoto_Exception("Media file `{$Upload->media}` could not be located");
				}

				$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE .'upload', TweetPhoto_Request::HTTP_METHOD_POST, file_get_contents($File->media, FILE_BINARY));

				$Block = new TweetPhoto_Request_Header_Block;

				$Block->addHeader(new TweetPhoto_Request_Header('TPAPIKEY',     TweetPhoto_Config::ACCOUNT_KEY));
				$Block->addHeader(new TweetPhoto_Request_Header('TPAPI',        TweetPhoto_Config::ACCOUNT_USERNAME . ':' . TweetPhoto_Config::ACCOUNT_PASSWORD));
				$Block->addHeader(new TweetPhoto_Request_Header('TPUTF8',       'true'));
				$Block->addHeader(new TweetPhoto_Request_Header('TPMSG',        base64_encode($File->message)));
				$Block->addHeader(new TweetPhoto_Request_Header('TPTAGS',       base64_encode($File->tags)));
				$Block->addHeader(new TweetPhoto_Request_Header('TPMetadata',   base64_encode($File->metadata)));
				$Block->addHeader(new TweetPhoto_Request_Header('TPLAT',        $File->latitude));
				$Block->addHeader(new TweetPhoto_Request_Header('TPLONG',       $File->longitude));
				$Block->addHeader(new TweetPhoto_Request_Header('TPPOST',       $File->post_to_twitter));
				$Block->addHeader(new TweetPhoto_Request_Header('TPMIMETYPE',   $this->getMimeType($File->media)));
				$Block->addHeader(new TweetPhoto_Request_Header('Content-Type', 'application/x-www-form-urlencoded'));

				$Request->addHeaderBlock($Block);

				return $Request->send();
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
