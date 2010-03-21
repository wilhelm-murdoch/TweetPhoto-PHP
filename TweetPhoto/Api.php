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
		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . 'users/' . (is_null($username) ? $this->username : $username));

		return $Request->send();
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function getUserSettings($user_id)
	{
		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . "users/{$user_id}/settings");

		$Block = new TweetPhoto_Request_Header_Block;

		$Block->addHeader(new TweetPhoto_Request_Header('TPAPI', "{$this->username},{$this->password}"));

		$Request->addHeaderBlock($Block);

		return $Request->send();
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
		if(false == in_array(trim(strtolower($filter)), TweetPhoto_Config::$user_settings))
		{
			throw new TweetPhoto_Exception('Provide a valid user setting.');
		}

		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE .'users/' . (is_null($username) ? $this->username : $username) . "/settings/{$filter}", TweetPhoto_Request::HTTP_METHOD_PUT, $value);

		$Block = new TweetPhoto_Request_Header_Block;

		$Block->addHeader(new TweetPhoto_Request_Header('TPAPI', "{$this->username},{$this->password}"));

		$Request->addHeaderBlock($Block);

		return $Request->send();
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

		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . "users/{$user_id}/comments");

		$Request->buildQueryString(array('ind' => $start, 'ps' => $limit, 'sort' => $sort));

		return $Request->send();
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

		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . "users/{$user_id}/friends");

		$Request->buildQueryString(array('ind' => $start, 'ps' => $limit, 'nf' => $network));

		return $Request->send();
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

		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . "users/{$user_id}/favorites");

		$Request->buildQueryString(array('ind' => $start, 'ps' => $limit, 'sort' => $sort));

		return $Request->send();
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

		$data = json_encode(array
		(
			'Service'        => $service_id,
			'IdentitySecret' => $secret,
			'IdentityToken'  => $token
		));

		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . 'link', TweetPhoto_Request::HTTP_METHOD_POST, $data);

		$Block = new TweetPhoto_Request_Header_Block;

		$Block->addHeader(new TweetPhoto_Request_Header('TPAPIKEY', $this->api_key));

		$Request->addHeaderBlock($Block);

		return $Request->send();
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
		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . 'profiles');

		$Block = new TweetPhoto_Request_Header_Block;

		$Block->addHeader(new TweetPhoto_Request_Header('TPAPI', "{$this->username},{$this->password}"));

		$Request->addHeaderBlock($Block);

		return $Request->send();
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

		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . 'socialfeed');

		$Request->buildQueryString(array('ind' => $start, 'ps' => $limit, 'filter' => $filter, 'eventdate' => $date));

		return $Request->send();
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

		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . "users/{$user_id}/feed");

		$Request->buildQueryString(array('ind' => $start, 'ps' => $limit, 'filter' => $filter, 'eventdate' => $date));

		return $Request->send();
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

		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . 'profiles');

		$Block = new TweetPhoto_Request_Header_Block;

		$Block->addHeader(new TweetPhoto_Request_Header('TPAPI', "{$this->username},{$this->password}"));

		$Request->addHeaderBlock($Block);

		$Request->buildQueryString(array('ind' => $start, 'ps' => $limit, 'sort' => $sort, 'sf' => $filter));

		return $Request->send();
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

		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . 'querycount');

		$Request->buildQueryString(array('search' => $search, 'tags' => $tags));

		return $Request->send();
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

				$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . 'signin');

				$Block = new TweetPhoto_Request_Header_Block;

				$Block->addHeader(new TweetPhoto_Request_Header('TPAPI',     "{$this->username},{$this->password}"));
				$Block->addHeader(new TweetPhoto_Request_Header('TPSERVICE', 'TWITTER'));

				$Request->addHeaderBlock($Block);

				break;

			default:

				throw new TweetPhoto_Exception('You have specified an invalid authentication method.');
		}

		return $Request->send();
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

		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . "photos/{$photo_id}");

		return $Request->send();
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

		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . "photos/{$photo_id}/favorizers");

		return $Request->send();
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
		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . "photos/{$photo_id}/favorizers");

		$Request->buildQueryString(array('lat' => $lat, 'long' => $long, 'ind' => $start, 'ps' => $limit, 'getuser' => $get_user, 'dist' => $radius));

		return $Request->send();
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

		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . 'photos/byvenue');

		$Request->buildQueryString(array('vid' => $venue_id, 'ind' => $start, 'ps' => $limit));

		return $Request->send();
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

		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . "photos/{$photo_id}/tags");

		return $Request->send();
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

		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . "photos/{$photo_id}/viewers");

		$Request->buildQueryString(array('ind' => $start, 'ps' => $limit));

		return $Request->send();
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

		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . "users/{$user_id}/favorites/{$photo_id}");

		return $Request->send();
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

		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . "users/{$user_id}/votes/{$photo_id}");

		return $Request->send();
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

		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . "leaderboard/uploadedtoday/{$filter}");

		$Request->buildQueryString(array('ind' => $start, 'ps' => $limit));

		return $Request->send();
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

		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . "photos/{$photo_id}/comments");

		$Request->buildQueryString(array('ind' => $start, 'ps' => $limit));

		return $Request->send();
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

		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . "photos/{$photo_id}/thumbsup", TweetPhoto_Config::HTTP_METHOD_PUT);

		$Block = new TweetPhoto_Request_Header_Block;

		$Block->addHeader(new TweetPhoto_Request_Header('TPAPI',  "{$this->username},{$this->password}"));
		$Block->addHeader(new TweetPhoto_Request_Header('TPPOST', ($post_to_twitter ? 'TRUE' : 'FALSE')));

		$Request->addHeaderBlock($Block);

		return $Request->send();
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

		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . "photos/{$photo_id}/thumbsdown", TweetPhoto_Config::HTTP_METHOD_PUT);

		$Block = new TweetPhoto_Request_Header_Block;

		$Block->addHeader(new TweetPhoto_Request_Header('TPAPI',  "{$this->username},{$this->password}"));
		$Block->addHeader(new TweetPhoto_Request_Header('TPPOST', ($post_to_twitter ? 'TRUE' : 'FALSE')));

		$Request->addHeaderBlock($Block);

		return $Request->send();
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

		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . "users/{$user_id}/favorites/{$photo_id}", TweetPhoto_Config::HTTP_METHOD_POST);

		$Block = new TweetPhoto_Request_Header_Block;

		$Block->addHeader(new TweetPhoto_Request_Header('TPAPI',  "{$this->username},{$this->password}"));

		$Request->addHeaderBlock($Block);

		return $Request->send();
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

		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . "users/{$user_id}/favorites/{$photo_id}", TweetPhoto_Config::HTTP_METHOD_DELETE);

		$Block = new TweetPhoto_Request_Header_Block;

		$Block->addHeader(new TweetPhoto_Request_Header('TPAPI',  "{$this->username},{$this->password}"));

		$Request->addHeaderBlock($Block);

		return $Request->send();
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

		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . "users/{$user_id}/views/{$photo_id}", TweetPhoto_Config::HTTP_METHOD_POST);

		$Block = new TweetPhoto_Request_Header_Block;

		$Block->addHeader(new TweetPhoto_Request_Header('TPAPI',  "{$this->username},{$this->password}"));

		$Request->addHeaderBlock($Block);

		return $Request->send();
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

		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . "users/{$user_id}/comments/{$photo_id}", TweetPhoto_Config::HTTP_METHOD_POST, json_encode($comment));

		$Block = new TweetPhoto_Request_Header_Block;

		$Block->addHeader(new TweetPhoto_Request_Header('TPAPI',  "{$this->username},{$this->password}"));
		$Block->addHeader(new TweetPhoto_Request_Header('TPPOST', ($post_to_twitter ? 'TRUE' : 'FALSE')));

		$Request->addHeaderBlock($Block);

		return $Request->send();
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

		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . "users/{$user_id}/comments/{$photo_id}/{$comment_id}", TweetPhoto_Config::HTTP_METHOD_DELETE);

		$Block = new TweetPhoto_Request_Header_Block;

		$Block->addHeader(new TweetPhoto_Request_Header('TPAPI',  "{$this->username},{$this->password}"));

		$Request->addHeaderBlock($Block);

		return $Request->send();
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

		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . "photos/{$photo_id}", TweetPhoto_Config::HTTP_METHOD_DELETE);

		$Block = new TweetPhoto_Request_Header_Block;

		$Block->addHeader(new TweetPhoto_Request_Header('TPAPI',  "{$this->username},{$this->password}"));

		$Request->addHeaderBlock($Block);

		return $Request->send();
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

		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . "users/{$user_id}/broadcastmessage/{$broadcast_id}", TweetPhoto_Config::HTTP_METHOD_PUT, json_encode($message));

		$Block = new TweetPhoto_Request_Header_Block;

		$Block->addHeader(new TweetPhoto_Request_Header('TPAPI',  "{$this->username},{$this->password}"));

		$Request->addHeaderBlock($Block);

		return $Request->send();
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

		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . "photos/{$photo_id}/location", TweetPhoto_Config::HTTP_METHOD_PUT, json_encode("{$lat}, {$long}"));

		$Block = new TweetPhoto_Request_Header_Block;

		$Block->addHeader(new TweetPhoto_Request_Header('TPAPI',  "{$this->username},{$this->password}"));

		$Request->addHeaderBlock($Block);

		return $Request->send();
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

		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . "photos/{$photo_id}/location", TweetPhoto_Config::HTTP_METHOD_DELETE);

		$Block = new TweetPhoto_Request_Header_Block;

		$Block->addHeader(new TweetPhoto_Request_Header('TPAPI',  "{$this->username},{$this->password}"));

		$Request->addHeaderBlock($Block);

		return $Request->send();
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

		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . "photos/{$photo_id}/tags", TweetPhoto_Config::HTTP_METHOD_PUT, json_encode($tag));

		$Block = new TweetPhoto_Request_Header_Block;

		$Block->addHeader(new TweetPhoto_Request_Header('TPAPI',  "{$this->username},{$this->password}"));

		$Request->addHeaderBlock($Block);

		return $Request->send();
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

		$Request = new TweetPhoto_Request(TweetPhoto_Config::SERVICE . "photos/{$photo_id}/tags/{$tag_id}", TweetPhoto_Config::HTTP_METHOD_DELETE);

		$Block = new TweetPhoto_Request_Header_Block;

		$Block->addHeader(new TweetPhoto_Request_Header('TPAPI',  "{$this->username},{$this->password}"));

		$Request->addHeaderBlock($Block);

		return $Request->send();
	}


	// ! Executor Method

	/**
	 * Mounts a specified utility.
	 *
	 * @param Array $values Referenced array containg values ot evaluate.
	 * @access Static Private
	 * @return Boolean
	 */
	public function __get($property)
	{
		return $this->$property;
	}
}