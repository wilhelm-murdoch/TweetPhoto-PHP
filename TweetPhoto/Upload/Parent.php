<?

abstract class TweetPhoto_Upload_Parent
{
	protected $media;
	protected $message;
	protected $tags;
	protected $metadata;
	protected $latitude;
	protected $longitude;
	protected $post_to_twitter;
	protected $isoauth;
	protected $vid;
	protected $venue;
	protected $response_format;
	protected $tpservice;

	public function __construct($media, $message = null, $tags = null, $metadata = null, $latitude = null, $longitude = null, $post_to_twitter = false, $isoauth = false, $vid = null, $venue = '', $tpservice = 'twitter')
	{
		$this->media           = $media;
		$this->message         = $message;
		$this->tags            = $tags;
		$this->metadata        = $metadata;
		$this->latitude        = $latitude;
		$this->longitude       = $longitude;
		$this->post_to_twitter = $post_to_twitter ? 'true' : 'false';
		$this->isoauth         = $isoauth ? 'true' : 'false';
		$this->vid             = $vid;
		$this->venue           = $venue;
		$this->response_format = 'JSON';
		$this->tpservice       = $tpservice;
	}
}