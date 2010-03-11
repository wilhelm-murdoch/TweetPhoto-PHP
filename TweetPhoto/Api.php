<?php

class TweetPhoto_Api
{
	private $username;
	private $password;
	private $api_key;

	static private $singleton = null;


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function __construct($username = TweetPhoto_Config::ACCOUNT_USERNAME, $password = TweetPhoto_Config::ACCOUNT_PASSWORD, $api_key = TweetPhoto_Config::ACCOUNT_KEY)
	{
		$this->username = $username;
		$this->password = $password;
		$this->api_key  = $api_key;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	static public function singleton($username = TweetPhoto_Config::ACCOUNT_USERNAME, $password = TweetPhoto_Config::ACCOUNT_PASSWORD, $api_key = TweetPhoto_Config::ACCOUNT_KEY)
	{
		if(false == self::$singleton instanceof self)
		{
			if(is_null($username) || is_null($password) || is_null($api_key))
			{
				throw new TweetPhoto_Exception('Username, password and api key are all required to instantiate this class.');
			}

			self::$singleton = new self($username, $password, $api_key);
		}

		return self::$singleton;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function getUser($username = null)
	{
		if(is_null($username))
		{
			throw new TweetPhoto_Exception('Provide a valid user name or ID.');
		}

		$result = $this->sendRequest('/users/' . (is_null($username) ? $this->username : $username));

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function getUserSettings($username = null)
	{
		if(is_null($username))
		{
			throw new TweetPhoto_Exception('Provide a valid user name or ID.');
		}

		$result = $this->sendRequest('/users/' . (is_null($username) ? $this->username : $username) . '/settings');

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function setUserSettings($username = null, $filter, $value)
	{
		if(is_null($username))
		{
			throw new TweetPhoto_Exception('Provide a valid user name or ID.');
		}

		if(false == in_array(trim(strtolower($filter)), TweetPhoto_Config::$user_settings))
		{
			throw new TweetPhoto_Exception('Provide a valid user setting.');
		}

		$result = $this->sendRequest('/users/' . (is_null($username) ? $this->username : $username) . "/settings/{$filter}", TweetPhoto_Config::HTTP_METHOD_PUT, array(), $value);

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function getUserComments($user_id, $start = 0, $limit = 25, $sort = 'desc')
	{
		if(false == is_int($user_id))
		{
			throw new TweetPhoto_Exception('Provide a valid user ID.');
		}

		if(false == in_array(trim(strtolower($sort)), TweetPhoto_Config::$sorts))
		{
			throw new TweetPhoto_Exception('Provide a valid sort option.');
		}

		$result = $this->sendRequest("/users/{$user_id}/comments?" . http_build_query(array('ind' => $start, 'ps' => $limit, 'sort' => $sort), null, '&'));

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function getUserFriends($user_id, $start = 0, $limit = 25, $network = 'all')
	{
		if(false == is_int($user_id))
		{
			throw new TweetPhoto_Exception('Provide a valid user ID.');
		}

		if(false == in_array(trim(strtolower($network)), TweetPhoto_Config::$networks))
		{
			throw new TweetPhoto_Exception('Provide a valid network filter option.');
		}

		$result = $this->sendRequest("/users/{$user_id}/friends?" . http_build_query(array('ind' => $start, 'ps' => $limit, 'nf' => $network), null, '&'));

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function getUserFavorites($user_id, $start = 0, $limit = 25, $sort = 'desc')
	{
		if(false == is_int($user_id))
		{
			throw new TweetPhoto_Exception('Provide a valid user ID.');
		}

		if(false == in_array(trim(strtolower($sort)), TweetPhoto_Config::$sorts))
		{
			throw new TweetPhoto_Exception('Provide a valid sort option.');
		}

		$result = $this->sendRequest("/users/{$user_id}/favorites?" . http_build_query(array('ind' => $start, 'ps' => $limit, 'sort' => $sort), null, '&'));

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function addLinkToService($service_id, $secret, $token)
	{
		if(false == in_array(trim(strtolower($service_id)), TweetPhoto_Config::$service_ids))
		{
			throw new TweetPhoto_Exception('Provide a valid service ID.');
		}

		$headers = array
		(
			"TPAPIKEY: {$this->api_key}"
		);

		$data = array
		(
			'Service'        => $service_id,
			'IdentitySecret' => $secret,
			'IdentityToken'  => $token
		);

		$result = $this->sendRequest('/link', TweetPhoto_Config::HTTP_METHOD_POST, $headers, $data);

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function getLinkedServices()
	{
		$result = $this->sendRequest('/profiles');

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function getPublicFeed($start = 0, $limit = 25, $filter = 'voted', $date = null)
	{
		if(false == in_array(trim(strtolower($filter)), TweetPhoto_Config::$social_feed_filters))
		{
			throw new TweetPhoto_Exception('Provide a valid filter option.');
		}

		$result = $this->sendRequest('/socialfeed?' . http_build_query(array('ind' => $start, 'ps' => $limit, 'filter' => $filter, 'eventdate' => $date), null, '&'));

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function getUserFeed($user_id, $start = 0, $limit = 25, $filter = 'voted', $date = null)
	{
		if(false == is_int($user_id))
		{
			throw new TweetPhoto_Exception('Provide a valid user ID.');
		}

		if(false == in_array(trim(strtolower($filter)), TweetPhoto_Config::$social_feed_filters))
		{
			throw new TweetPhoto_Exception('Provide a valid filter option.');
		}

		$result = $this->sendRequest("/users/{$user_id}/feed?" . http_build_query(array('ind' => $start, 'ps' => $limit, 'filter' => $filter, 'eventdate' => $date), null, '&'));

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function getUserPhotos($user_id, $start = 0, $limit = 25, $sort = 'desc', $filter = 'date')
	{
		if(false == is_int($user_id))
		{
			throw new TweetPhoto_Exception('Provide a valid user ID.');
		}

		if(false == in_array(trim(strtolower($sort)), TweetPhoto_Config::$sorts))
		{
			throw new TweetPhoto_Exception('Provide a valid sort option.');
		}

		if(false == in_array(trim(strtolower($filter)), TweetPhoto_Config::$photo_filters))
		{
			throw new TweetPhoto_Exception('Provide a valid filtering option.');
		}

		$result = $this->sendRequest("/users/{$user_id}/photos?" . http_build_query(array('ind' => $start, 'ps' => $limit, 'sort' => $sort, 'sf' => $filter), null, '&'));

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function getQueryCount($search = null, $tags = null)
	{
		if(is_null($search) && is_null($tags))
		{
			throw new TweetPhoto_Exception('Provide a valid search term or tag list.');
		}

		$result = $this->sendRequest('/querycount?' . http_build_query(array('search' => $search, 'tags' => $tags), null, '&'));

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function signIn($method = TweetPhoto_Config::SIGNIN_METHOD_TWITTER)
	{
		$result = null;

		switch($method)
		{
			case TweetPhoto_Config::SIGNIN_METHOD_FACEBOOK:
			case TweetPhoto_Config::SIGNIN_METHOD_FOURSQUARE:

				throw new TweetPhoto_Exception('You have specified an unsupported authentication method.');

				break;

			case TweetPhoto_Config::SIGNIN_METHOD_TWITTER:

				$headers = array
				(
					"TPAPIKEY: {$this->api_key}",
					'TPSERVICE: Twitter'
				);

				$result = $this->sendRequest('/signin', $headers);

				break;

			default:

				throw new TweetPhoto_Exception('You have specified an invalid authentication method.');
		}

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function getPhotoDetails($photo_id)
	{
		if(false == is_int($photo_id))
		{
			throw new TweetPhoto_Exception('Provide a valid photo ID.');
		}

		$result = $this->sendRequest("/photos/{$photo_id}");

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function getPhotoFavorited($photo_id)
	{
		if(false == is_int($photo_id))
		{
			throw new TweetPhoto_Exception('Provide a valid photo ID.');
		}

		$result = $this->sendRequest("/photos/{$photo_id}/favorizers");

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function getPhotosByGeoLocation($lat, $long, $start = 0, $limit = 25, $radius = 20000, $get_user = false)
	{
		$result = $this->sendRequest('/photos/bylocation?' . http_build_query(array('lat' => $lat, 'long' => $long, 'ind' => $start, 'ps' => $limit, 'getuser' => $get_user, 'dist' => $radius), null, '&'));

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function getPhotoByVenue($venue_id, $start = 0, $limit = 25)
	{
		if(false == is_int($venue_id))
		{
			throw new TweetPhoto_Exception('Provide a valid FourSquare venue ID.');
		}

		$result = $this->sendRequest('/photos/byvenue?' . http_build_query(array('vid' => $venue_id, 'ind' => $start, 'ps' => $limit), null, '&'));

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function getPhotoTags($photo_id)
	{
		if(false == is_int($photo_id))
		{
			throw new TweetPhoto_Exception('Provide a valid photo ID.');
		}

		$result = $this->sendRequest("/photos/{$photo_id}/tags");

		return $result;
	}

	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function getPhotoUsersViewed($photo_id, $start = 0, $limit = 25)
	{
		if(false == is_int($venue_id))
		{
			throw new TweetPhoto_Exception('Provide a valid FourSquare venue ID.');
		}

		$result = $this->sendRequest("/photos/{$photo_id}/viewers?" . http_build_query(array('ind' => $start, 'ps' => $limit), null, '&'));

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function checkUserPhotoFavorite($user_id, $photo_id)
	{
		if(false == is_int($photo_id) || false == is_int($user_id))
		{
			throw new TweetPhoto_Exception('Provide valid photo and user IDs.');
		}

		$result = $this->sendRequest("/users/{$user_id}/favorites/{$photo_id}");

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function checkUserPhotoVote($user_id, $photo_id)
	{
		if(false == is_int($photo_id) || false == is_int($user_id))
		{
			throw new TweetPhoto_Exception('Provide valid photo and user IDs.');
		}

		$result = $this->sendRequest("/users/{$user_id}/votes/{$photo_id}");

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function getLeaderBoard($filter = 'views')
	{
		if(false == in_array(trim(strtolower($filter)), TweetPhoto_Config::$leaderboard_filters))
		{
			throw new TweetPhoto_Exception('Provide a valid filtering option.');
		}

		$result = $this->sendRequest("/leaderboard/uploadedtoday/{$filter}?" . http_build_query(array('ind' => $start, 'ps' => $limit), null, '&'));

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function getPhotoComments($photo_id, $start = 0, $limit = 25)
	{
		if(false == is_int($photo_id))
		{
			throw new TweetPhoto_Exception('Provide a valid photo ID.');
		}

		$result = $this->sendRequest("/photos/{$photo_id}/comments?" . http_build_query(array('ind' => $start, 'ps' => $limit), null, '&'));

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function voteThumbsUp($photo_id = null, $post_to_twitter = false)
	{
		if(false == is_int($photo_id))
		{
			throw new TweetPhoto_Exception('Provide a valid photo ID.');
		}

		$result = $this->sendRequest("/photos/{$photo_id}/thumbsup", TweetPhoto_Config::HTTP_METHOD_PUT, array('TPPOST: ' . ($post_to_twitter ? 'TRUE' : 'FALSE')));

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function voteThumbsDown($photo_id = null, $post_to_twitter = false)
	{
		if(false == is_int($photo_id))
		{
			throw new TweetPhoto_Exception('Provide a valid photo ID.');
		}

		$result = $this->sendRequest("/photos/{$photo_id}/thumbsdown", TweetPhoto_Config::HTTP_METHOD_PUT, array('TPPOST: ' . ($post_to_twitter ? 'TRUE' : 'FALSE')));

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function addFavorite($user_id, $photo_id)
	{
		if(false == is_int($photo_id) || false == is_int($user_id))
		{
			throw new TweetPhoto_Exception('Provide valid photo and user IDs.');
		}

		$result = $this->sendRequest("/users/{$user_id}/favorites/{$photo_id}", TweetPhoto_Config::HTTP_METHOD_POST);

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function deleteFavorite($user_id, $photo_id)
	{
		if(false == is_int($photo_id) || false == is_int($user_id))
		{
			throw new TweetPhoto_Exception('Provide valid photo and user IDs.');
		}

		$result = $this->sendRequest("/users/{$user_id}/favorites/{$photo_id}", TweetPhoto_Config::HTTP_METHOD_DELETE);

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function addView($user_id, $photo_id)
	{
		if(false == is_int($photo_id) || false == is_int($user_id))
		{
			throw new TweetPhoto_Exception('Provide valid photo and user IDs.');
		}

		$result = $this->sendRequest("/users/{$user_id}/views/{$photo_id}", TweetPhoto_Config::HTTP_METHOD_POST);

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function addComment($user_id, $photo_id, $comment, $post_to_twitter = false)
	{
		if(false == is_int($photo_id) || false == is_int($user_id))
		{
			throw new TweetPhoto_Exception('Provide valid photo and user IDs.');
		}

		if(false == $comment)
		{
			throw new TweetPhoto_Exception('Provide valid comment.');
		}

		$result = $this->sendRequest("/users/{$user_id}/comments/{$photo_id}", TweetPhoto_Config::HTTP_METHOD_POST, array('TPPOST: ' . ($post_to_twitter ? 'TRUE' : 'FALSE')), $comment);

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function deleteComment($user_id, $photo_id, $comment_id)
	{
		if(false == is_int($photo_id) || false == is_int($user_id) || false == is_int($comment_id))
		{
			throw new TweetPhoto_Exception('Provide valid photo, comment and user IDs.');
		}

		$result = $this->sendRequest("/users/{$user_id}/comments/{$photo_id}/{$comment_id}", TweetPhoto_Config::HTTP_METHOD_DELETE);

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function deletePhoto($photo_id)
	{
		if(false == is_int($photo_id))
		{
			throw new TweetPhoto_Exception('Provide a valid photo ID.');
		}

		$response = $this->sendRequest("/photos/{$photo_id}", TweetPhoto_Config::HTTP_METHOD_DELETE);

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function setBroadcastMessage($user_id, $broadcast_id, $message)
	{
		if(false == is_int($user_id))
		{
			throw new TweetPhoto_Exception('Provide valid user ID.');
		}

		if(false == in_array(trim(strtolower($broadcast_id)), TweetPhoto_Config::$broadcast_ids))
		{
			throw new TweetPhoto_Exception('Provide a valid broadcast ID');
		}

		if(false == $message)
		{
			throw new TweetPhoto_Exception('Provide valid broadcast message.');
		}

		$result = $this->sendRequest("/users/{$user_id}/broadcastmessage/{$broadcast_id}", TweetPhoto_Config::HTTP_METHOD_PUT, array(), $message);

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function setPhotoLocation($photo_id, $lat, $long)
	{
		if(false == is_int($photo_id))
		{
			throw new TweetPhoto_Exception('Provide a valid photo ID.');
		}

		$result = $this->sendRequest("/photos/{$photo_id}/location", TweetPhoto_Config::HTTP_METHOD_PUT, array(), "{$lat}, {$long}");

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function deletePhotoLocation($photo_id)
	{
		if(false == is_int($photo_id))
		{
			throw new TweetPhoto_Exception('Provide a valid photo ID.');
		}

		$result = $this->sendRequest("/photos/{$photo_id}/location", TweetPhoto_Config::HTTP_METHOD_DELETE);

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function addPhotoTag($photo_id, $tag)
	{
		if(false == is_int($photo_id))
		{
			throw new TweetPhoto_Exception('Provide a valid photo ID.');
		}

		$result = $this->sendRequest("/photos/{$photo_id}/tags", TweetPhoto_Config::HTTP_METHOD_PUT, array(), $tag);

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function deletePhotoTag($photo_id, $tag_id)
	{
		if(false == is_int($photo_id) || false == is_int($tag_id))
		{
			throw new TweetPhoto_Exception('Provide valid photo and tag IDs.');
		}

		$result = $this->sendRequest("/photos/{$photo_id}/tags/{$tag_id}", TweetPhoto_Config::HTTP_METHOD_DELETE);

		return $result;
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function sendRequest($url, $method = TweetPhoto_Config::HTTP_METHOD_GET, array $headers = array(), $data = null, $json_encode = true)
	{
		$headers[] = "TPAPI: {$this->username},{$this->password}";

		$curl = curl_init(TweetPhoto_Config::SERVICE . $url);

		if($data)
		{
			if($json_encode)
			{
				$data = json_encode($data);
			}

			$headers[] = 'Content-Length: ' . strlen($data);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		else
		{
			$headers[] = 'Content-Length: 0';
		}

		if($method & TweetPhoto_Config::HTTP_METHOD_POST)
		{
			curl_setopt($curl, CURLOPT_PUT,  false);
			curl_setopt($curl, CURLOPT_POST, true);
		}
		else if($method & TweetPhoto_Config::HTTP_METHOD_PUT)
		{
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
		}
		else if($method & TweetPhoto_Config::HTTP_METHOD_DELETE)
		{
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
		}

		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER,     $headers);

		$response  = curl_exec($curl);
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);

		return array
		(
			'code'    => $http_code,
			'results' => json_decode($response)
		);
	}

	public function __get($property)
	{
		return $this->$property;
	}
}