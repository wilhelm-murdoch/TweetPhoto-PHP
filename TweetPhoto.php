<?php

echo '<pre>';
print_r(TweetPhoto::singleton(USERNAME, PASSWORD, API_KEY));
echo '</pre>';

class TweetPhotoException extends Exception{}

// return status messages specific to function
// add a few more layers of validation
// add linking functionality
// add user settings functionality
// propery status responses and error handling

class TweetPhoto
{
	private $username;
	private $password;
	private $api_key;

	static private $singleton = null;

	public function __construct($username, $password, $api_key)
	{
		$this->username = $username;
		$this->password = $password;
		$this->api_key  = $api_key;
	}

	static public function singleton($username = null, $password = null, $api_key = null)
	{
		if(false == self::$singleton instanceof self)
		{
			self::$singleton = new self($username, $password, $api_key);
		}

		return self::$singleton;
	}

	public function getUser($username = null)
	{
		return $this->sendRequest('http://tweetphotoapi.com/api/tpapi.svc/json/users/' . (is_null($username) ? $this->username : $username));
	}

	public function signIn($method = 'twitter')
	{
		switch($method)
		{
			default:
			case 'twitter':

				$headers = array
				(
					"TPAPI: {$this->username},{$this->password}",
					"TPAPIKEY: {$this->api_key}",
					'TPSERVICE: Twitter'
				);

				break;
		}
		return $this->sendRequest('http://tweetphotoapi.com/api/tpapi.svc/json/signin', $headers);
	}

	public function getPhotoDetails($photo_id)
	{
		return $this->sendRequest('http://tweetphotoapi.com/api/tpapi.svc/json/photos/' . (int) $photo_id);
	}

	public function voteThumbsUp($photo_id = null, $post_to_twitter = false)
	{
		$headers = array
		(
			"TPAPI: {$this->username},{$this->password}",
			"TPPOST: {$post_to_twitter}"
		);

		return $this->sendRequest("http://tweetphotoapi.com/api/tpapi.svc/json/photos/{$photo_id}/thumbsup", $headers, 'PUT');
	}

	public function voteThumbsDown($photo_id = null, $post_to_twitter = false)
	{
		$headers = array
		(
			"TPAPI: {$this->username},{$this->password}",
			'TPPOST: ' . ($post_to_twitter ? 'TRUE' : 'FALSE')
		);

		return $this->sendRequest("http://tweetphotoapi.com/api/tpapi.svc/json/photos/{$photo_id}/thumbsdown", $headers, 'PUT');
	}

	public function addFavorite($user_id, $photo_id)
	{
		return $this->sendRequest("http://tweetphotoapi.com/api/tpapi.svc/json/users/{$user_id}/favorites/{$photo_id}", array("TPAPI: {$this->username},{$this->password}"), 'POST');
	}

	public function deleteFavorite($user_id, $photo_id)
	{
		return $this->sendRequest("http://tweetphotoapi.com/api/tpapi.svc/json/users/{$user_id}/favorites/{$photo_id}", array("TPAPI: {$this->username},{$this->password}"), 'DELETE');
	}

	public function addView($user_id, $photo_id)
	{
		return $this->sendRequest("http://tweetphotoapi.com/api/tpapi.svc/json/users/{$user_id}/views/{$photo_id}", array("TPAPI: {$this->username},{$this->password}"), 'POST');
	}

	public function addComment($user_id, $photo_id, $comment, $post_to_twitter = false)
	{
		$headers = array
		(
			"TPAPI: {$this->username},{$this->password}",
			'TPPOST: ' . ($post_to_twitter ? 'TRUE' : 'FALSE')
		);

		return $this->sendRequest("http://tweetphotoapi.com/api/tpapi.svc/json/users/{$user_id}/comments/{$photo_id}", array("TPAPI: {$this->username},{$this->password}"), 'POST', $comment);
	}

	public function deleteComment($user_id, $photo_id, $comment_id)
	{
		return $this->sendRequest("http://tweetphotoapi.com/api/tpapi.svc/json/users/{$user_id}/comments/{$photo_id}/{$comment_id}", array("TPAPI: {$this->username},{$this->password}"), 'DELETE');
	}

	public function deletePhoto($photo_id)
	{
		return $this->sendRequest("http://tweetphotoapi.com/api/tpapi.svc/json/photos/{$photo_id}", array("TPAPI: {$this->username},{$this->password}"), 'DELETE');
	}

	private function sendRequest($url, array $headers = array(), $method = 'GET', $data = null)
	{
		$curl = curl_init($url);

		if($data)
		{
			$json = json_encode($data);
			$headers[] = 'Content-Length: ' . strlen($json);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
		}
		else
		{
			$headers[] = 'Content-Length: 0';
		}

		if($method == 'POST')
		{
			curl_setopt($curl, CURLOPT_PUT,  false);
			curl_setopt($curl, CURLOPT_POST, true);
		}
		else if($method == 'PUT')
		{
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
		}
		else if($method == 'DELETE')
		{
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
		}

		$options = array
		(
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER     => $headers
		);

		curl_setopt_array($curl, $options);

		$response = curl_exec($curl);

		switch($http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE))
		{
			case 404:
				throw new TweetPhotoException("{$http_code}: TweetPhoto record not found: {$url}");
				break;
			case 200:
				break;
			default:
				throw new TweetPhotoException($http_code . ': ' . curl_errno($curl) . ' ' . curl_error($curl));
				break;
		}

		curl_close($curl);

		return json_decode($response);
	}
}