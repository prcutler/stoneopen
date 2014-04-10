<?php

/**
 * AIT WordPress Framework
 *
 * Copyright (c) 2011, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */



// ASCII Art :)))
require_once dirname(__FILE__) . '/WpLatteFunctions.php';
require_once dirname(__FILE__) . '/WpLatteMacros.php';
require_once dirname(__FILE__) . '/WpLatteCommentWalker.php';
require_once dirname(__FILE__) . '/Entities/WpLatteBaseEntity.php';
require_once dirname(__FILE__) . '/Entities/WpLatteSiteEntity.php';
require_once dirname(__FILE__) . '/Entities/WpLatteCategoryEntity.php';
require_once dirname(__FILE__) . '/Entities/WpLatteTagEntity.php';
require_once dirname(__FILE__) . '/Entities/WpLatteArchiveEntity.php';
require_once dirname(__FILE__) . '/Entities/WpLattePostAuthorEntity.php';
require_once dirname(__FILE__) . '/Entities/WpLatteCommentAuthorEntity.php';
require_once dirname(__FILE__) . '/Entities/WpLatteCommentEntity.php';
require_once dirname(__FILE__) . '/Entities/WpLattePostEntity.php';


/**
 * Version of WpLatte Cache
 * Allways change this, when changes in WpLatte,
 * mainly macros affects WpLatte cache.
 */
define('WPLATTE_CACHE_VERSION', 4);


/**
 * The WpLatte
 *
 * Creates Latte templates and renders them.
 */
class WpLatte extends NFileTemplate
{

	/**
	 * Absolut path to cache directory
	 * @var string
	 */
	public static $cacheDir;

	/**
	 * Absolut path to templates directory
	 * @var string
	 */
	public static $templatesDir;



	/**
	 * @param string $file Absolut path to template file
	 * @param array $params Variables for template
	 */
	public function __construct($file, $params, $absolutePath = false)
	{

		if (!$absolutePath) {
			$prefix = "main-";

			$file = basename($file, '.php');

			$file = self::$templatesDir . "/%s{$file}.php";

			if(is_file(sprintf($file, $prefix))){
				$file = sprintf($file, $prefix);
			}else{
				$prefix = "";
				$file = sprintf($file, $prefix);
			}
		}

		parent::__construct($file);

		$params['site'] = WpLatteSiteEntity::getInstance();

		if(file_exists(self::$templatesDir . '/main-layout.php'))
			$params['layout'] = 'main-layout.php';
		elseif(file_exists(self::$templatesDir . '/layout.php'))
			$params['layout'] = 'layout.php';
		else
			$params['layout'] = '@layout.php';

		$params['headerNotCalled'] = did_action('get_header') == 0;
		$params['footerNotCalled'] = did_action('get_footer') == 0;
		$params['sidebarNotCalled'] = did_action('get_sidebar') == 0;

		$this->setParams($params);

		$this->registerHelperLoader('NTemplateHelpers::loader');
		$this->registerHelper("printf", "sprintf");

		$this->setCacheStorage(new NPhpFileStorage(realpath(self::$cacheDir)));

		$this->onPrepareFilters[] = create_function('$template',
			'
			$engine = new NLatteFilter();
			WpLatteMacros::install($engine->parser);
			$template->registerFilter($engine);
			'
		);
	}


	function render()
	{
		if ($this->getFile() == NULL) {
			throw new InvalidStateException("Template file name was not specified.");
		}

		$cache = new NCache($storage = $this->getCacheStorage(), 'wplatte');
		if ($storage instanceof NPhpFileStorage) {
			$storage->hint = str_replace(dirname(dirname($this->getFile())), '', $this->getFile());
		}
		$cached = $compiled = $cache->load($this->getFile());

		if ($compiled === NULL) {
			try {
				$compiled = "<?php\n\n// source file: {$this->getFile()}\n\n?>" . $this->compile();

			} catch (NTemplateException $e) {
				$e->setSourceFile($this->getFile());
				throw $e;
			}

			$cache->save($this->getFile(), $compiled, array(
				NCache::FILES => $this->getFile(),
				NCache::CONSTS => array('NFramework::REVISION', 'WPLATTE_CACHE_VERSION'),
			));
			$cache->release();
			$cached = $cache->load($this->getFile());
		}

		if ($cached !== NULL && $storage instanceof NPhpFileStorage) {
			NLimitedScope::load($cached['file'], $this->getParams());
		} else {
			NLimitedScope::evaluate($compiled, $this->getParams());
		}
	}


	/**
	 * Creates new Latte template
	 * @param string $file Path to .latte template file
	 * @param array $params Params for template
	 * @return WpLatte
	 */
	public static function createTemplate($file, $params, $absolutePath = false)
	{
		return new self($file, $params, $absolutePath);
	}



	/**
	 * Factory for WpLattePostEntity
	 *
	 * @param WP_Query|stdClass|array $input
	 * @param array $meta Array of additional data
	 * @return array|WpLattePostEntity Array of WpLattePost entities or single WpLattePostEntity
	 */
	public static function createPostEntity($input, $meta = array())
	{

		if($input instanceof WP_Query)
			$posts = $input->posts;
		elseif($input instanceof stdClass or $input instanceof WP_Post)
			return new WpLattePostEntity($input, $meta);
		else
			$posts = $input;

		$return = array();

		foreach($posts as $post){
			$return[] = new WpLattePostEntity($post, $meta);
		}



		return $return;
	}


	/**
	 * Alias for WpLatteSiteEntity::createCusomPostEntity()
	 *
	 * @param string $what Name of custom type without "ait-" prefix
	 * @param string $category Selecting custom type by specific category
	 * @param int $limit Limit of returned items
	 * @return array|WpLattePostEntity
	 */
	public static function createCustomPostEntity($what, $category = 0, $limit = null, $order = 'menu_order', $suppress_filters = false)
	{
		return WpLatteSiteEntity::createCustomPostEntity($what, $category, $limit, $order, $suppress_filters);
	}

}
