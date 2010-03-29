<?php

class TweetPhoto_Config
{
	const SERVICE = 'http://tweetphotoapi.com/api/tpapi.svc/json/';

	const ACCOUNT_USERNAME = USERNAME;
	const ACCOUNT_PASSWORD = PASSWORD;
	const ACCOUNT_KEY      = API_KEY;

	const CHARACTER_LIMIT = 200;

	const SIGNIN_METHOD_TWITTER    = 1;
	const SIGNIN_METHOD_FOURSQUARE = 2;
	const SIGNIN_METHOD_FACEBOOK   = 4;

	const UPLOAD_CHUNKED = 1;
	const UPLOAD_FILE    = 2;
	const UPLOAD_BOTH    = 4;

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
}