<?php

class TweetPhoto_Config
{
	const ACCOUNT_USERNAME = USERNAME;
	const ACCOUNT_PASSWORD = PASSWORD;
	const ACCOUNT_KEY      = API_KEY;

	const HTTP_METHOD_GET    = 1;
	const HTTP_METHOD_POST   = 2;
	const HTTP_METHOD_PUT    = 4;
	const HTTP_METHOD_DELETE = 8;

	const PARAM_SEPARATOR = '&';
	const CHARACTER_LIMIT = 200;

	const SIGNIN_METHOD_TWITTER    = 1;
	const SIGNIN_METHOD_FOURSQUARE = 2;
	const SIGNIN_METHOD_FACEBOOK   = 4;

	static public $sorts = array
	(
		'desc',
		'asc'
	);

	static public $networks = array
	(
		'all',
		'facebook',
		'twitter'
	);

	static public $photo_filters = array
	(
		'date',
		'comments',
		'views'
	);

	static public $leaderboard_filters = array
	(
		'viewed',
		'commented',
		'voted'
	);

	static public $social_feed_filters = array
	(
		'voted',
		'commented',
		'favorited',
		'uploadedphoto'
	);

	static public $broadcast_ids = array
	(
		'thumbsup',
		'thumbsdown',
		'favorite'
	);

	static public $service_ids = array
	(
		'twitter',
		'foursquare',
		'myspace',
		'facebook'
	);

	static public $user_settings = array
	(
		'hideviewingpatterns',
		'donottweetfavoritephoto',
		'hidevotes',
		'maptype',
		'shortenurl',
		'pin'
	);

	static private $messages = array
	(
		'VOTES' => array
		(
			200 => 'Vote worked and counted',
			401 => 'Invalid Credentials',
			409 => 'User has already voted for that photo',
			404 => 'Photo not found',
			500 => 'Internal Error'
		),
		'COMMENTS' => array
		(
			404 => 'Comment not found',
			200 => 'Comment sucessfully removed'
		)
	);

	static public function getResponse($type, $code)
	{
		return isset(self::$messages[$type][$code]) ? self::$messages[$type][$code] : null;
	}
}