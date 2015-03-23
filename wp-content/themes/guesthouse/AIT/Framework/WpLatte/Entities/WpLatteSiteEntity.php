<?php

/**
 * AIT WordPress Framework
 *
 * Copyright (c) 2011, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */



/**
 * The Site Entity
 *
 * Contains properties and (helper) methods used in all WpLatte templates.
 * In templates this entity is available under variable `$site`.
 */
class WpLatteSiteEntity extends WpLatteBaseEntity
{

	/**
	 * Returns the 'Site Title' set in Settings > General.
	 * @var string
	 */
	protected $name;

	/**
	 * Returns the 'Site address (URL)' set in Settings > General.
	 * @var string
	 */
	protected $url;

	/**
	 * Date format. Alias for get_option('date_format')
	 * @var string
	 */
	protected $dateFormat;

	/**
	 * Time format. Alias for get_option('time_format');
	 * @var string
	 */
	protected $timeFormat;

	/**
	 * Display the language attributes for the <html> tag.
	 * @var string HTML
	 */
	protected $langAttributes;

	/**
	 * Returns the language of WordPress.
	 * @var string
	 */
	protected $language;

	/**
	 * Returns the 'Encoding for pages and feeds' set in Settings > Reading.
	 * @var type
	 */
	protected $charset;

	/**
	 * Returns the primary CSS (usually style.css) file URL of the active theme.
	 * @var string
	 */
	protected $stylesheetUrl;

	/**
	 * Returns the Pingback XML-RPC file URL (xmlrpc.php).
	 * @var string
	 */
	protected $pingbackUrl;

	/**
	 * Returns the 'Tagline' set in Settings > General.
	 * @var string
	 */
	protected $description;

	/**
	 * Gets searched phrase. Alias for get_search_query()
	 * @var type
	 */
	protected $searchQuery;

	/** @var bool */
	protected $isHome;

	/** @var bool */
	protected $isSingle;

	/** @var bool */
	protected $isPage;

	/** @var bool */
	protected $isSearch;

	/**
	 * Wheter displayed page is archive.
	 * It differs from $wp_query->is_archive in that only returns true if it is archive.php page.
	 * @var bool
	 */
	protected $isArchive;

	/** @var bool */
	protected $isCategory;

	/** @var bool */
	protected $isAuthor;

	/** @var bool */
	protected $isTag;

	/** @var bool */
	protected $is404;

	/**
	 * True if $wp_query->is_home and $wp_query->is_posts_page are true
	 * @var bool
	 */
	protected $isBlog;

	/**
	 * Alias for is_front_page()
	 * @var bool
	 */
	protected $isFrontPage;

	/**
	 * Alias for $isFrontPage
	 * @var bool
	 */
	protected $isHomepage;

	/**
	 * Alias for is_user_logged_in()
	 * @var bool
	 */
	protected $isUserLoggedIn;

	/**
	 * Returns the WordPress Version you use.
	 * @var string
	 */
	protected $version;

	/**
	 * Is client an iPad?
	 * @var bool
	 */
	protected $isIpad;

	/**
	 * Is client an iPhone?
	 * @var bool
	 */
	protected $isIphone;

	/**
	 * Is client mobile device
	 * @var bool
	 */
	protected $isMobile;

	/**
	 * Is paged?
	 * @var bool
	 */
	protected $isPaged;

	/**
	 * @internal
	 * @var WpLatteSiteEntity
	 */
	private static $instance;



	public function __get($key)
	{

		try{
			$result = parent::__get($key);
			if(!is_null($result)){
				$this->$key = $result;
				return $result;
			}else{
				if(property_exists(__CLASS__, $key)){
					if(is_null($this->$key)){
						$this->$key = get_bloginfo(WpLatteFunctions::camel2underscore($key));
						return $this->$key;
					}else{
						return $this->$key;
					}
				}else{
					return '';
				}
			}
		}catch(WpLatteEntityException $e){
			if(property_exists(__CLASS__, $key)){
				if(is_null($this->$key)){
					$this->$key = get_bloginfo(WpLatteFunctions::camel2underscore($key));
					return $this->$key;
				}else{
					return $this->$key;
				}
			}else{
				throw $e;
			}
		}
	}



	private function __construct()
	{
	}



	/**
	 * Singleton "constructor"
	 * @return self
	 */
	public static function getInstance()
	{
		if(self::$instance === NULL){
			self::$instance = new self;
		}

		global $wp_query;

		self::$instance->isHome = $wp_query->is_home;
		self::$instance->isSingle = $wp_query->is_single;
		self::$instance->isPage = $wp_query->is_page;
		self::$instance->isSearch = $wp_query->is_search;
		self::$instance->isArchive = ($wp_query->is_archive && !$wp_query->is_category && !$wp_query->is_tag && !$wp_query->is_author);
		self::$instance->isCategory = $wp_query->is_category;
		self::$instance->isAuthor = $wp_query->is_author;
		self::$instance->isTag = $wp_query->is_tag;
		self::$instance->is404 = $wp_query->is_404;
		self::$instance->isBlog = $wp_query->is_home and $wp_query->is_posts_page;
		self::$instance->isFrontPage = is_front_page();
		self::$instance->isHomepage = is_front_page(); // alias
		self::$instance->isPaged = is_paged(); // alias

		$ua = $_SERVER['HTTP_USER_AGENT'];
		self::$instance->isIpad = strpos($ua, 'iPad') !== false;
		self::$instance->isIphone = (strpos($ua,'iPhone') !== false || strpos($ua,'iPod') !== false);
		self::$instance->isMobile = (stripos($ua, 'safari') !== false && stripos($ua, 'mobile') !== false) || (stripos($ua,'android') !== false && stripos($ua,'mobile') !== false);


		return self::$instance;
	}



	/**
	 * Gets HTML lang attributes
	 * @return string
	 */
	protected function getLangAttributes()
	{
		ob_start();
		language_attributes();
		return ob_get_clean();
	}



	/**
	 * Gets date format
	 * @return string
	 */
	protected function getDateFormat()
	{
		return get_option('date_format');
	}



	/**
	 * Gets time format
	 * @return string
	 */
	protected function getTimeFormat()
	{
		return get_option('time_format');
	}



	/**
	 * Gets searched phrase
	 * @return string
	 */
	protected function getSearchQuery()
	{
		return get_search_query();
	}



	/**
	 * Is user logged in?
	 * @return bool
	 */
	protected function getIsUserLoggedIn()
	{
		return is_user_logged_in();
	}



	/**
	 * Helper method for better manipulation with widgets in templates.
	 *
	 * Register sidebars for widgets in functions.php like this ("prefix is sidebar-"):
	 * * First widget: 'id' => 'sidebar-widgets-1',
	 * * Second widget: 'id' => 'sidebar-widgets-2',
	 * * Etc...
	 *
	 * And in the template you can get all this widgets in array via this method like this:
	 *
	 * $site->widgets('sidebar')
	 *
	 * @param string
	 * @return array Array with widgets which ID starts with $sidebarPrefix
	 */
	public function widgets($sidebarPrefix)
	{
		$widgets = array();
		foreach(wp_get_sidebars_widgets() as $key => $val){
			if(strncmp($key, $sidebarPrefix, strlen($sidebarPrefix)) === 0) // Nette\Utils\Strings::startsWith()
				$widgets[] = $key;
		}
		return $widgets;
	}



	public function isActiveWidgetArea($areaId)
	{
		return is_active_sidebar($areaId);
	}



	/**
	 * Creates Entities from Custom Types
	 *
	 * Example:
	 * $site->create('slider', 'homepage', 5)
	 *
	 * @param string $what Name of custom type without "ait-" prefix
	 * @param string $category Selecting custom type by specific category
	 * @param int $limit Limit of returned items
	 * @return array|WpLattePostEntity
	 */
	public function create($what, $category = 0, $limit = null, $order = 'menu_order')
	{
		return self::createCustomPostEntity($what, $category, $limit, $order);
	}



	/**
	 * Creates Entities from Custom Types (static Alias)
	 * @param string $what Name of custom type without "ait-" prefix
	 * @param string $category Selecting custom type by specific category
	 * @param int $limit Limit of returned items
	 * @return array|WpLattePostEntity
	 */
	public static function createCustomPostEntity($what, $category = 0, $limit = null, $order = 'menu_order', $suppress_filters = false)
	{
		if(empty($limit) or $limit === false)
			$limit = -1; // default, no value entered in admin or missing parameter

		$tax = array();
		if($category !== 0 && $category !== "0" && $category !== null){
			$tax = array(
				array(
					'taxonomy' => 'ait-' . $what . '-category',
					'field' => 'slug',
					'terms' => $category,
				),
			);
		}

		$options = WpLatteFunctions::dash2camel($what) . 'Options'; // convention over configuration

		return WpLatte::createPostEntity(new WP_Query(array(
				'tax_query' => $tax,
				'post_type' => 'ait-' . $what,
				'post_status' => 'publish',
				'orderby' => $order,
				'order' => 'ASC',
				'posts_per_page' => $limit,
				'suppress_filters' => $suppress_filters,
			)),
			array(
				'meta' => @$GLOBALS[$options], // fffuuuuu global variable
				'isCustomType' => true
			)
		);
	}
}
