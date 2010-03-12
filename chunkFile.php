<?php

chunkFile(realpath('kitty.jpg'), 4);

function chunkFile($file, $chunks = 2)
{
	$handle = fopen($file, 'rb');
	$count  = 1;

	while(false == feof($handle))
	{
		if($data = fread($handle, round(filesize($file) / $chunks)))
		{
			file_put_contents(basename($file) . "-chunk-{$count}.txt", $data);

			$count++;
		}
	}

	fclose($handle);

	return $count;
}