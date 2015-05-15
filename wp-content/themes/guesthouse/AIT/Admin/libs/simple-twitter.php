<?php


/**
 * Simple Twitter Class - library for receiving status updates.
 * Forked from Twitter for PHP - https://github.com/dg/twitter-php
 *
 * Uses WordPress Transient API for caching
 * 
 * @author     Cifro
 * @copyright  Copyright (c) 2011 Cifro
 * @copyright  Copyright (c) 2008 David Grudl (http://phpfashion.com/)
 * @license    New BSD License
 * @version    1.0
 */
class SimpleTwitter
{

	/**#@+ Timeline {@link Twitter::load()} */
	const ME = 1;
	const ME_AND_FRIENDS = 2;
	const REPLIES = 3;
	const ALL = 4;
	const RETWEETS = 128; // include retweets?
	/**#@-*/

	/**#@+ Output format {@link Twitter::load()} */
	const XML = 0;
	const JSON = 16;
	const RSS = 32;
	const ATOM = 48;
	/**#@-*/

	/** @var int */
	public static $cacheExpire = 1800; // 30 min

	/** @var string */
	public static $cacheDir;

	/** @var string */
	private $screenName;



	/**
	 * Creates object using consumer and access keys.
	 * @param  string  consumer key
	 * @param  string  app secret
	 * @param  string  optional access token
	 * @param  string  optinal access token secret
	 * @throws TwitterException when allow_url_fopen is not enabled
	 */
	public function __construct($screenName = NULL)
	{
		if (!ini_get('allow_url_fopen')) {
			throw new TwitterException('PHP directive allow_url_fopen is not enabled.');
		}

		$this->screenName = $screenName;
	}



	/**
	 * Returns the most recent statuses.
	 * @param  int    timeline (ME | ME_AND_FRIENDS | REPLIES | ALL) and optional (RETWEETS) or format (XML | JSON | RSS | ATOM)
	 * @param  int    number of statuses to retrieve
	 * @param  int    page of results to retrieve
	 * @return mixed
	 * @throws TwitterException
	 */
	public function load($flags = self::ME, $count = 5, $page = 1)
	{
		static $timelines = array(self::ME => 'user_timeline', self::ME_AND_FRIENDS => 'friends_timeline', self::REPLIES => 'mentions', self::ALL => 'public_timeline');

		if (!isset($timelines[$flags & 0x0F])) {
			throw new InvalidArgumentException;
		}

		return $this->cachedRequest('statuses/' . $timelines[$flags & 0x0F] . '.json', array(
			'screen_name' => $this->screenName,
			'count' => $count,
			'page' => $page,
			'include_rts' => $flags & self::RETWEETS ? 1 : 0,
		));
	}



	/**
	 * Process HTTP request.
	 * @param  string  URL or twitter command
	 * @param  array   data
	 * @param  string  HTTP method
	 * @return mixed
	 * @throws TwitterException
	 */
	public function request($request, $data = NULL, $method = 'POST')
	{
		if (!strpos($request, '://')) {
			if (!strpos($request, '.')) {
				$request .= '.json';
			}
			$request = 'http://api.twitter.com/1/' . $request;
		}

		$request = $request . '?' .  http_build_query($data);

		$options = array(
			'method' => 'GET',
			'timeout' => 3,
			'content' => NULL,
			'user_agent' => 'Simple Twitter',
		);

		$f = @fopen($request,
			'r', FALSE, stream_context_create(array('http' => $options)));
		if (!$f) {
			throw new TwitterException('Server error. Could not request the Twitter.');
		}

		$result = stream_get_contents($f);
		$payload = @simplexml_load_string($result); // intentionally @
		if (empty($payload)) {
			$payload = @json_decode($result); // intentionally @
			if (empty($payload)) {
				throw new TwitterException('Invalid server response');
			}
		}
		return $payload;
	}



	/**
	 * Cached HTTP request.
	 * @param  string  URL or twitter command
	 * @param  array
	 * @param  int
	 * @return mixed
	 */
	public function cachedRequest($request, $data = NULL, $cacheExpire = NULL)
	{

		$cacheTransient = 'twitter_' . md5($request . json_encode($data));
		$cache = get_transient($cacheTransient);

		if ($cacheExpire === NULL) {
			$cacheExpire = self::$cacheExpire;
		}

		if ($cache !== false){
			return strncmp($cache, '<', 1) ? @json_decode($cache) : @simplexml_load_string($cache);
		}else{
			try {
				$payload = $this->request($request, $data, 'GET');
				set_transient($cacheTransient, $payload instanceof SimpleXMLElement ? $payload->asXml() : json_encode($payload), $cacheExpire);
				return $payload;

			} catch (TwitterException $e) {
				if ($cache) {
					return $cache;
				}
				throw $e;
			}
		}
	}



	/**
	 * Makes twitter links, @usernames and #hashtags clickable.
	 * @param  string
	 * @return string
	 */
	public static function clickable($s)
	{
		return preg_replace_callback(
			'~(?<!\w)(https?://\S+\w|www\.\S+\w|@\w+|#\w+)|[<>&]~u',
			array(__CLASS__, 'clickableCallback'),
			html_entity_decode($s, ENT_QUOTES, 'UTF-8')
		);
	}



	private static function clickableCallback($m)
	{
		$m = htmlspecialchars($m[0]);
		if ($m[0] === '#') {
			$m = substr($m, 1);
			return "<a href='http://twitter.com/search?q=%23$m'>#$m</a>";
		} elseif ($m[0] === '@') {
			$m = substr($m, 1);
			return "@<a href='http://twitter.com/$m'>$m</a>";
		} elseif ($m[0] === 'w') {
			return "<a href='http://$m'>$m</a>";
		} elseif ($m[0] === 'h') {
			return "<a href='$m'>$m</a>";
		} else {
			return $m;
		}
	}
}



/**
 * An exception generated by Twitter.
 */
class TwitterException extends Exception
{
}
