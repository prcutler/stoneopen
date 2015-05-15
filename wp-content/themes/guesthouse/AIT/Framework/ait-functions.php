<?php

/**
 * AIT WordPress Framework
 *
 * Copyright (c) 2011, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */

/**
 * Load own jQuery from Google APIs (deregister from Wordpress)
 * @param string $version Version from jQuery Google API, eg. 1.6.2
 */
function aitLoadJQuery($version)
{
    // deregister the original version of jQuery
    //wp_deregister_script('jquery');
    // discover the correct protocol to use
    /*$protocol = 'http:';

    if(isset($_SERVER['HTTPS'])){
       $protocol = 'https:';
    }*/
    // register the Google CDN version
    //wp_register_script('jquery', $protocol.'//ajax.googleapis.com/ajax/libs/jquery/'.$version.'/jquery.min.js', false, $version);
    // add it back into the queue
    wp_enqueue_script('jquery');
}


function aitIncludeLess()
{
	$less = AIT_FRAMEWORK_DIR . '/Libs/lessphp/lessc';
	if(file_exists($less.'.inc'))
		require_once $less.'.inc';
	else
		require_once $less.'.inc.php';

}

/**
 *
 * @param string $url Requested URL
 * @param int $cacheExpire Expire time in seconds
 * @param string $cacheKey Name of cache file
 * @return stdClass|SimpleXMLElement JSON object or XML object
 */
function aitFileCachedRemoteRequest($url, $cacheExpire, $cacheKey)
{
	$cacheFile = AIT_CACHE_DIR . '/' . $cacheKey . md5($url);
	$cache = @file_get_contents($cacheFile); // intentionally @
	$cache = strncmp($cache, '<', 1) ? @json_decode($cache) : @simplexml_load_string($cache); // intentionally @

	if($cache && @filemtime($cacheFile) + $cacheExpire > time()){ // intentionally @
		return $cache;

	}else{
		$request = wp_remote_get($url);

		if($request['response']['code'] == 200){
			$payload = $request['body'];

			if(strncmp($payload, '<', 1)){
				@file_put_contents($cacheFile, $payload);
				$payload = json_decode($payload);
			}else{
				$payload = simplexml_load_string($payload);
				@file_put_contents($cacheFile, $payload->asXml());
			}

			return $payload;

		}else{
			return false;
		}
	}
}



/**
 *
 * @param string prefix Prefix for transient key
 * @param string $url Requested URL
 * @param int $cacheExpire Expire time in seconds
 * @param string $id Id of HTML element to be selected via DOMDocument::getElementById()
 * @return stdClass|SimpleXMLElement JSON object or XML object
 */
function aitCachedRemoteRequest($prefix, $url, $cacheExpire, $id = null)
{
	$cacheTransient = substr($prefix, 0, 11) . '_' . md5($url);
	$cache = get_transient($cacheTransient);

	if($cache !== false){
		// xml
		if(strncasecmp(trim($cache), '<?xml', 5) === 0){
			return @simplexml_load_string($cache); // intentionally @

		// html
		}elseif(strncasecmp(trim($cache), '<', 1) === 0){
			return $cache;

		// json
		}elseif(strncasecmp(trim($cache), '{', 1) === 0 || strncasecmp(trim($cache), '[', 1) === 0){
			return @json_decode($cache);
		}
	}else{

		$request = wp_remote_get($url);

		if(!is_wp_error($request)){

			if($request['response']['code'] == 200){
				$payload = $request['body'];

				// xml
				if(strncasecmp(trim($payload), '<?xml', 5) === 0){
					$payload = simplexml_load_string($payload);
					set_transient($cacheTransient, $payload->asXml(), $cacheExpire);

				// html
				}elseif(strncasecmp(trim($payload), '<', 1) === 0){
					$payload = str_replace("\r", '', $payload);
					$dom = new DOMDocument;
					@$dom->loadHTML($payload);
					$div = $dom->getElementById($id);
					$payload = $dom->saveXML($div);
					set_transient($cacheTransient, $payload, $cacheExpire);

				// json
				}elseif(strncasecmp(trim($payload), '{', 1) === 0 || strncasecmp(trim($payload), '[', 1) === 0){
					set_transient($cacheTransient, $payload, $cacheExpire);
					$payload = json_decode($payload);

				}

				return $payload;
			}else{
				return false;
			}
		}else{
			return false;
		}

	}
}



/**
 * Gets all available fonts for theme
 * @return array Fonts
 */
function aitGetFonts($type = 'all')
{
	$fonts = array();
	$cufonFonts = array();
	$googleFonts = array();

	if($type == 'cufon' or $type == 'all'){
		$jsfonts = glob(THEME_FONTS_DIR . '/*.js');
		if($jsfonts === false) $jsfonts = array();
		foreach($jsfonts as $i => $font){
			$content = file_get_contents($font);
			preg_match_all('/"font-family":"([a-zA-Z0-9 ]+)/m', $content, $matches);
			if(isset($matches[1])){
				$cufonFonts[$i]['font'] = str_replace(' ', '+', $matches[1][0]);
				$cufonFonts[$i]['file'] = basename($font);
			}
		}
	}

	if($type == 'google' or $type == 'all'){
		$file = AIT_FRAMEWORK_DIR . "/Libs/GoogleFonts.txt";
		foreach(file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $font){
			$googleFonts[]['font'] = $font;
		}
	}

	if(!empty($cufonFonts)){
		$fonts['cufon'] = array(
			'title' => __('Cufon Fonts', THEME_CODE_NAME),
			'fonts' => $cufonFonts,
		);
	}

	if(!empty($googleFonts)){
		$fonts['google'] = array(
			'title' => __('Google Fonts', THEME_CODE_NAME),
			'fonts' => $googleFonts,
		);
	}

	if($type != 'all'){
		unset($fonts[$type]);
	}

	return $fonts;
}



/**
 * Returns HTML <select> dropdown element for Fonts
 * @param string $name Value of attr. name
 * @param string $id Value of attr. id
 * @param string $type Cufon, Google Fonts or both?
 * @return string HTML <select> element
 */
function aitFontsDropdown($name, $id = 'ait-fonts-dropdown', $selected = '', $type = 'all', $dataAttr = '')
{
	$fonts = aitGetFonts($type);

	if(substr($name, -6, 6) == '[font]')
		$name = substr($name, 0, -6);

	$return  = "<select name=\"{$name}[font]\" id=\"$id\" {$dataAttr}>";
	$return .= "<option>" . __('- select font -', THEME_CODE_NAME) . "</option>";

	$selectedType = '';
	$selectedFile = '';

	foreach($fonts as $type => $group){
		$return .= "<optgroup label=\"{$group['title']}\" class=\"$type\">";
		foreach($group['fonts'] as $font){

			$s = '';
			$class = '';
			$f = '';
			if(isset($font['file'])){
				$class = "class=\"{$font['file']}\"";
				$f = $font['file'];
			}

			if($selected == $font['font']){
				$s = 'selected';
				$selectedType = $type;
				$selectedFile = $f;
			}

			$return .= "<option value=\"{$font['font']}\" $class $s>" . str_replace('+', ' ', $font['font']) . "</option>";
		}
		$return .= "</optgroup>";
	}
	$return .= "</select>";

	$return .= "<input type=\"hidden\" id=\"$id-type\" name=\"{$name}[type]\" value=\"$selectedType\">";
	$return .= "<input type=\"hidden\" id=\"$id-file\" name=\"{$name}[file]\" value=\"$selectedFile\">";

	$return .= "<script>
		jQuery(function(){
			var aitFontsDropdowns = [], aitFontsDropdownTypes = [], aitFontsDropdownFiles = [];
			aitFontsDropdowns['{$id}'] = jQuery('#{$id}');
			aitFontsDropdownTypes['{$id}'] = jQuery('#{$id}-type');
			aitFontsDropdownFiles['{$id}'] = jQuery('#{$id}-file');
			aitFontsDropdowns['{$id}'].change(function(){
				var \$selected = aitFontsDropdowns['{$id}'].find('option:selected');
				var type = \$selected.parent().attr('class');
				var file = \$selected.attr('class') != undefined ? \$selected.attr('class') : '';
				aitFontsDropdownTypes['{$id}'].val(type);
				aitFontsDropdownFiles['{$id}'].val(file);
			});
		});
		</script>
	";

	return $return;
}



/**
 * Generates CSS from LESS
 * @param string $input Absolut path to LESS file
 * @param string $output Absolut path to CSS file
 * @param array $options
 */
function aitSaveLess2Css($input = null, $output = null, $options = null)
{
	aitIncludeLess();

	if($input === null and $output === null and $options === null){
		$options = get_option(AIT_OPTIONS_KEY);
		$input = THEME_DIR . "/style.less.css";
		$output = THEME_STYLESHEET_FILE;
	}

	if($options === false){ // for theme preview
		$options = aitGetThemeDefaultOptions($GLOBALS['aitThemeConfig']);
	}

	$less = new AitLess();
	$less->importDir = THEME_DIR . '/';

	$content = file_get_contents($input);

	$onlyDesignVars = true;

	$configTypes = aitGetOptionsTypes($GLOBALS['aitThemeConfig'], $onlyDesignVars);

	$customCss = aitGetOptionsByType(array('custom-css', 'custom-css-vars'), $configTypes, $options);

	if(isset($customCss['custom-css'])){
		foreach($customCss['custom-css'] as $css){
			$content .= $css['value'];
			unset($options[$css['section']][$css['key']]);
		}
	}

	$variables = aitPrepareVariablesForLess($options, $configTypes);
	try{
		$css = $less->parse($content, $variables);
	}catch(Exception $e){
		wp_die($e->getMessage());
	}

	// save also comment header
	preg_match("/\/\*.*?\*\//s", $content, $match);
	$header = $match[0];

	unset($content); // clean up

	$header .= "\n\n/* *************************************\n *    !!! Do not edit this file !!!    *\n * Please edit style.less.css instead. *\n * *********************************** */\n\n";

	if(!defined('AIT_DEVELOPMENT') or AIT_DEVELOPMENT != true)
		$css = preg_replace('~\\s*([:;{},])\\s*~', '\\1', preg_replace('~/\\*.*\\*/~sU', '', $css));


	@chmod($output, 0777);
	$written = @file_put_contents($output, $header . "\n" . $css);
	@chmod($output, 0755);

	if($written === false)
		return false;
	else
		return true;
}



/**
 * Converts structured config array to simple key => value array
 * @param array $options Config array
 * @return array
 */
function aitPrepareVariablesForLess($options = array(), $configTypes = null)
{
	if(empty($options)){
		$options = get_option(AIT_OPTIONS_KEY);
		if($options === false) $options = array();
	}

	if($configTypes === null){
		$onlyDesignVars = true;
		$configTypes = aitGetOptionsTypes($GLOBALS['aitThemeConfig'], $onlyDesignVars);
	}

	$variables = array();
	foreach($options as $section => $values){
		foreach($values as $option => $value){
			if(isset($configTypes[$section][$option])){
				if($configTypes[$section][$option] == 'custom-css'){
					continue;
				}
				if(is_string($value)){
					if(empty($value)){
						$variables[$option] = '';
					}else{
						$variables[$option] = $value;
					}
					// url to images must be in quotes
					if(preg_match('/\.(jpg|png|gif)/', $variables[$option]) !== 0)
						$variables[$option] = "\"$variables[$option]\"";

				}elseif(is_array($value) and isset($value['font']) and !empty($value['type'])){
					$font = str_replace('+', ' ', $value['font']);
					$pos = strpos($font, ':');

					if($pos !== false)
						$font = substr($font, 0, $pos);

					$variables[$option] = "'" . $font . "'";

				}elseif($configTypes[$section][$option] == 'transparent' and is_array($value) and isset($value['color'])){
					if(startsWith('#', $value['color']) and $value['opacity'] == 1){
						$variables[$option] = $value['color'];
					}else{
						$rgba = "rgba(%s, %s, %s, %s);";

						$rgb = hex2rgb($value['color']);

						$rgba = sprintf($rgba, $rgb[0], $rgb[1], $rgb[2], $value['opacity']);
						$ieFilter = "progid:DXImageTransform.Microsoft.gradient(startColorstr='#".base_convert(floor($value['opacity']*255),10,16).str_replace('#','',$value['color'])."',endColorstr='#".base_convert(floor($value['opacity']*255),10,16).str_replace('#','',$value['color'])."',GradientType=0)";
            $variables[$option] = $rgba;
            $variables[$option.'-ie'] = $ieFilter;
					}

				}elseif($configTypes[$section][$option] == 'custom-css-vars' and is_array($value)){
					foreach($value as $var){
						if(isset($var['variable']) and isset($var['value']) and !empty($var['variable']) and !empty($var['value'])){
							if(preg_match('/\.(jpg|png|gif)/', $var['value']) !== 0)
								$var['value'] = "\"$var[value]\"";
							$variables[$var['variable']] = $var['value'];
						}
					}
				}
			}
		}
	}

	return $variables;
}

/**
 * Gets default values from Neon theme config file
 * @param type $config
 * @return type
 */
function aitGetThemeDefaultOptions($config)
{
	$settings = $config;
	$defaults = array();

	foreach($config as $menu_key => $page){

		if(isset($page['tabs'])){
			foreach($page['tabs'] as $tab_key => $tab_page){
				unset($settings[$menu_key]);
				$settings[$tab_key] = $tab_page;
			}
		}
	}

	// Niagara waterfalls. Yeaah
	foreach($settings as $key => $value){
		foreach($value['options'] as $k => $v){
			if(isset($k['sortable']) and is_string($v) and substr($v, 0, 7) == 'section' and isset($v[7]) and $v[7] == ' ' and isset($v[8])){
				$defaults[$key]['sectionsOrder'][] = trim(strstr($v, ' '));
			}elseif(!is_string($v)){
				if(!isset($v['default']))
					$v['default'] = '';

				if(is_array($v['default']) and $v['type'] != 'clone' and $v['type'] != 'custom-css-vars'  and $v['type'] != 'order'){
					foreach($v['default'] as $name => $val){
						if(isset($val['checked'])){
							$defaults[$key][$k][$name] = $name;
							if($v['type'] != 'checkbox'){ // check box could have multiple options checked
								$defaults[$key][$k] = $name;
							}
						}
					}

				}elseif((is_array($v['default']) and $v['type'] == 'order')){
					$defaults[$key][$k] = array_keys($v['default']);

				//cloning
				}elseif((is_array($v['default']) and $v['type'] == 'clone') or (is_array($v['default']) and $v['type'] == 'custom-css-vars')){
					foreach($v['default'] as $name => $val){
						foreach($val as $clone_key => $clone_val){
							if(!isset($clone_val['default']))
								$clone_val['default'] = '';
							if(isset($clone_val['default']) and !is_array($clone_val['default'])){
								if(!empty($clone_val['default']) and $clone_val['type'] == 'image-url'){
									$clone_val['default'] = THEME_URL . '/' . $clone_val['default'];
								}
								$defaults[$key][$k][$name][$clone_key] = $clone_val['default'];
							}else{
								foreach($clone_val['default'] as $x => $y){
									if(isset($y['checked'])){
										$defaults[$key][$k][$name][$clone_key][$x] = $x;
										if($clone_val['type'] != 'checkbox'){
											$defaults[$key][$k][$name][$clone_key] = $x;
										}
									}else{
										if($clone_val['type'] == 'checkbox'){
											$defaults[$key][$k][$name][$clone_key][$x] = array();
										}
									}
								}
							}
						}
					}
				}else{
					if(!empty($v['default']) and isset($v['type']) and $v['type'] == 'image-url'){
						$v['default'] = THEME_URL . '/' . $v['default'];
					}

					if(!empty($v['default']) and isset($v['type']) and $v['type'] == 'transparent'){
						$v['default'] = array('color' => $v['default'], 'opacity' => isset($v['opacity']) ? $v['opacity'] : '1');
					}

					if(isset($v['type']) and $v['type'] == 'dropdown-categories')
						$v['default'] = 0;

					if(isset($v['type']) and $v['type'] == 'dropdown-categories-posts')
						$v['default'] = 0;

					if(isset($v['type']) and $v['type'] == 'transparent'){
						$v['default'] = array();
						$opacity =  isset($v['opacity']) ? floatval($v['opacity']) : 1;
						if($opacity > 1)
							$opacity /= 100;

						if(startsWith('#', $v['color']) and $opacity == 1){
							$v['default']['color'] = $v['color'];
							$v['default']['opacity'] = 1;
						}else{
							$v['default']['color'] = $v['color'];
							$v['default']['opacity'] = $opacity;
						}
					}


					if(!empty($v['default']) and isset($v['type']) and $v['type'] == 'font'){
						$v['default'] = array(
							'font' => str_replace(' ', '+', $v['default']),
							'type' => '',
							'file' => '',
						);

						foreach(aitGetFonts() as $type => $fonts){
							foreach($fonts['fonts'] as $font){
								if($v['default']['font'] == $font['font']){
									$v['default']['type'] = $type;
									if($type == 'cufon')
										$v['default']['file'] = $font['file'];

									break;
								}
							}
						}
					}
					$defaults[$key][$k] = $v['default'];
				}
			}
		}
	}
	return $defaults;
}



/**
 * Gets default values from Neon theme config file
 * @param type $config
 * @return type
 */
function aitGetOptionsTypes($config, $onlySkinable = false)
{
	$settings = $config;
	$types = array();
	// $types[<section>][<key>] = <type>

	foreach($config as $menuKey => $page){

		if(isset($page['tabs'])){
			foreach($page['tabs'] as $tabKey => $tabPage){
				unset($settings[$menuKey]);
				$settings[$tabKey] = $tabPage;
			}
		}
	}

	$designTypes = array('transparent', 'colorpicker', 'image-url', 'font', 'select', 'radio', 'custom-css', 'custom-css-vars');

	foreach($settings as $section => $options){
		foreach($options['options'] as $key => $value){
			if(is_string($value) and startsWith('section', $value)){
				continue;
			}

			if($onlySkinable){

				if(in_array($value['type'], $designTypes) and (!isset($value['skinable']) or (isset($value['skinable']) and $value['skinable'] != false))){
					if(($value['type'] == 'select' or $value['type'] == 'radio') and (endsWith('X', $key) or endsWith('Y', $key) or endsWith('Repeat', $key) or endsWith('Attach', $key))){
						$types[$section][$key] = $value['type'];
					}else{
						$types[$section][$key] = $value['type'];
					}
				}

				if(isset($value['skinable']) and $value['skinable'] == true){
					$types[$section][$key] = $value['type'];
				}

			}else{
				$types[$section][$key] = $value['type'];
			}
		}
	}
	return $types;
}



function aitGetOptionsByType($optionType, $optionsTypes, $options)
{
	$return = array();

	foreach($optionsTypes as $section => $opts){
		foreach($opts as $key => $value){
			if(is_string($optionType) and $optionType == $value){
				$return[] = array('section' => $section, 'key' => $key, 'value' => $options[$section][$key]);
			}elseif(is_array($optionType)){
				foreach($optionType as $type){
					if($type == $value and isset($options[$section][$key])){
						$return[$type][] = array('section' => $section, 'key' => $key, 'value' => $options[$section][$key]);
					}
				}
			}
		}
	}

	return $return;
}




/**
 * Loads and parses Neon theme config files
 * @param string $filename Absoluth path to config file
 * @return array
 */
function loadConfig($filename)
{
	$file = realpath($filename);
	if($file === false)
		return false;
	$options = NNeon::decode(file_get_contents($file));
    return $options;
}



/**
 * Converts raw array to object
 * @param array $array
 * @return stdClass|boolean
 */
function arrayToObject($array)
{
	$temp = array();
	$object = new stdClass;
	if (is_array($array) and count($array) > 0){
		foreach($array as $name => $value){
			foreach($value as $k => $v){
				if(is_array($v) and $k != 'sectionsOrder'){
					foreach($v as $i => $j){
						if(is_numeric($i)){ // cloned items
							$temp[$i] = (object) $j;
							@$object->$name->$k = $temp;
						}else{
							@$object->$name->$k->$i = $j; // checkbox
						}
					}
				}else{
					@$object->$name->$k = $v; // @ - PHP 5.4 compatibility
				}
			}
		}
		return $object;
	}else{
		return false;
	}
}



if (!function_exists('array_replace_recursive')){
	/**
	 * Replaces elements from passed arrays into the first array recursively
	 * (PHP 5 >= 5.3.0)
	 * @return array|null
	 */
	function array_replace_recursive()
	{
		$arrays = func_get_args();
		$original = array_shift($arrays);

		foreach($arrays as $array){
			foreach($array as $key => $value){
				if(is_array($value)){
					$original[$key] = array_replace_recursive($original[$key], $array[$key]);
				}else{
					$original[$key] = $value;
				}
			}
		}
		return $original;
	}
}



/**
 * Starts the $haystack string with the prefix $needle?
 * @param  string
 * @param  string
 * @return bool
 */
function startsWith($needle, $haystack)
{
	return strncmp($haystack, $needle, strlen($needle)) === 0;
}



/**
 * Ends the $haystack string with the suffix $needle?
 * @param  string
 * @param  string
 * @return bool
 */
function endsWith($needle, $haystack)
{
	return strlen($needle) === 0 || substr($haystack, -strlen($needle)) === $needle;
}



/**
 * Converts to web safe characters [a-z0-9-] text.
* @param string String in UTF-8 encoding
* @return string
*/
function webalize($text)
{
    $url = $text;
    $url = preg_replace('~[^\\pL0-9_]+~u', '-', $url);
    $url = trim($url, "-");
    $url = @iconv("utf-8", "us-ascii//TRANSLIT", $url);
    $url = strtolower($url);
    $url = preg_replace('~[^-a-z0-9_]+~', '', $url);
    return $url;
}



/**
 * Fix for non-object reference, when calling $themeOptions['post']->options('slider') method
 **/
function aitGetOptions($object, $what)
{
	try
	{
		if(is_object($object)){
			return $object->options($what);
		} else {
			return null;
		}
	} catch (Exception $e) {
		return null;
	}
}



/**
 * Collect counts and UI strings for available AIT News and theme updates
 * @return array
 */
function aitGetAitUpdatesData()
{
	$counts = array(
		'news' => 0,
		'themeUpdate' => 0,
		'total' => 0,
	);
	$updateTitle = '';

	if(isset($GLOBALS['showAdmin']['dashboard']) == false){
		$GLOBALS['showAdmin']['dashboard'] = "enabled";
	}
	if(isset($GLOBALS['showAdmin']['ait_news_notifications']) == false){
		$GLOBALS['showAdmin']['ait_news_notifications'] = "enabled";
	}
	if(isset($GLOBALS['aitDisableBranding']) == false){
		$GLOBALS['aitDisableBranding'] = false;
	}

	$enabledDashboard = @$GLOBALS['showAdmin']['dashboard'] != 'disabled'; // @ - doesn't exist
	$enabledNotify = @$GLOBALS['showAdmin']['ait_news_notifications'] != 'disabled'; // @ - doesn't exist
	$isNewsDisabled = @$GLOBALS['aitDisableBranding'] == true; // @ - doesn't exist

	if($enabledDashboard and $enabledNotify){

		if(current_user_can('manage_options')){
			if($isNewsDisabled){
				$news = get_site_transient('ait_news_update');
				if($news !== false){
					$counts['news'] = count($news->news['unread']);
					$all = count($news->news['all']);
					if($all == 0){
						$counts['news'] = 0;
					}
				}
			}

			$themeVersion = get_site_transient('ait_theme_versions_update');
			if($themeVersion !== false && $themeVersion->updateAvailable && !get_option('disableAitThemeUpdates'))
				$counts['themeUpdate'] = 1;
		}

		$counts['total'] = $counts['news'] + $counts['themeUpdate'];
		$updateTitle = array();

		if($counts['news'])
			$updateTitle[] = sprintf(_n('%d Unread News', '%d Unread News', $counts['news']), $counts['news']);
		if ( $counts['themeUpdate'] )
			$updateTitle[] = sprintf(__('%d ' . THEME_SHORT_NAME . ' Theme Update', THEME_CODE_NAME), $counts['themeUpdate']);

		$updateTitle = ! empty($updateTitle ) ? esc_attr(implode(', ', $updateTitle )) : '';
	}

	return array(
		'counts' => $counts,
		'title' => $updateTitle,
	);
}



/**
 * Trim text with HTML tags
 * @param string Text to be trimmed
 * @param int Length of returned texts in characters
 * @return string Trimmed text with right HTML endings
 * @copyright Jakub Vrána, http://php.vrana.cz/
 */
function trim_html_content($s, $limit)
{
	$length = 0;
	$tags = array(); // dosud neuzavřené značky
	for ($i=0; $i < strlen($s) && $length < $limit; $i++){
		switch ($s[$i]) {
			case '<':
				// načtení značky
				$start = $i+1;
				while ($i < strlen($s) && $s[$i] != '>' && !ctype_space($s[$i])) {
					$i++;
				}
				$tag = substr($s, $start, $i - $start);
				// přeskočení případných atributů
				$in_quote = '';
				while ($i < strlen($s) && ($in_quote || $s[$i] != '>')) {
					if (($s[$i] == '"' || $s[$i] == "'") && !$in_quote) {
						$in_quote = $s[$i];
					} elseif ($in_quote == $s[$i]) {
						$in_quote = '';
					}
					$i++;
				}
				if ($s[$start] == '/') { // uzavírací značka
					array_shift($tags); // v XHTML dokumentu musí být vždy uzavřena poslední neuzavřená značka
				} elseif ($s[$i-1] != '/') { // otevírací značka
					array_unshift($tags, $tag);
				}
				break;

			case '&':
				$length++;
				while ($i < strlen($s) && $s[$i] != ';') {
					$i++;
				}
				break;

			default:
				$length++;

				while ($i+1 < strlen($s) && ord($s[$i+1]) > 127 && ord($s[$i+1]) < 192) {
					$i++;
				}
		}
	}
	$s = substr($s, 0, $i);
	(strlen($s) > $limit) ? $s .= " [...]" : '';
	if ($tags) {
		$s .= "</" . implode("></", $tags) . ">";
	}
	return $s;
}



function hex2rgb($color)
{
    if ($color[0] == '#')
        $color = substr($color, 1);

    if (strlen($color) == 6)
        list($r, $g, $b) = array($color[0].$color[1],
                                 $color[2].$color[3],
                                 $color[4].$color[5]);
    elseif (strlen($color) == 3)
        list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
    else
        return false;

    $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

    return array($r, $g, $b);
}

/**
 * Get embed video (youtube, vimeo) code
**/
function getVideoCode($link, $width, $height){
	// youtube video
	if (strpos($link,'youtube') !== false) {

		$vidparser = parse_url($link);
		parse_str($vidparser['query'], $query);
		$clip_id = ($query['v']);

  		return '<iframe width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$clip_id.'?wmode=opaque" frameborder="0" allowfullscreen></iframe>';

	// vimeo
	} elseif (strpos($link,'vimeo') !== false) {

		$clip_id = str_replace("http","",$link);
		$clip_id = str_replace(":","",$clip_id);
		$clip_id = str_replace("/","",$clip_id);
		$clip_id = trim(str_replace("vimeo.com","",$clip_id));

  		return '<iframe src="http://player.vimeo.com/video/'.$clip_id.'?title=0&amp;byline=0&amp;portrait=0" width="'.$width.'" height="'.$height.'" frameborder="0"></iframe>';

	}
}



/**
 * Callback for home_template filter.
 * It allows to set up diferent template for blog page
 */
function aitIndexTemplate($template)
{
	$id = get_queried_object_id();

	if(!$id)
		return $template;

	$template = get_page_template_slug($id);
	$pagename = get_query_var('pagename');

	if(!$pagename && $id){
		$post = get_queried_object();
		$pagename = $post->post_name;
	}

	$templates = array();
	if($template && 0 === validate_file($template))
		$templates[] = $template;
	if($pagename)
		$templates[] = "index-$pagename.php";
	if($id)
		$templates[] = "index-$id.php";
	$templates[] = 'index.php';

	return locate_template($templates);
}



/**
 * Better API for registering sidebars - widget areas
 * @param  array $areas Array of widget areas to register
 * @return void
 */
function aitRegisterWidgetAreas($areas, $defaultParams = array())
{
	if(empty($defaultParams)){
		$defaultParams = array(
			'before_widget' => '<div id="%1$s" class="box widget-container %2$s"><div class="box-wrapper">',
			'after_widget' => "</div></div>",
			'before_title' => '<h2 class="widget-title">',
			'after_title' => '</h2>',
		);
	}

	foreach($areas as $id => $area){
		$params = array_merge($defaultParams, $area, array('id' => $id));
		register_sidebar($params);
	}
}



/**
 * Better API for registering and enqueueing styles
 * @param  array $styles
 */
function aitAddStyles($styles)
{
	foreach($styles as $handler => $style){
		if(is_bool($style) and $style === true){
			wp_enqueue_style($handler);
		}elseif(is_array($style)){

			wp_register_style($handler, $style['file'],  isset($style['deps']) ? $style['deps'] : array(), isset($style['ver']) ? $style['ver'] : false, isset($style['media']) ? $style['media'] : 'all');

			if(!isset($style['enqueue']) or (isset($style['enqueue']) and $style['enqueue'] == true))
				wp_enqueue_style($handler);
		}
	}
}



/**
 * Better API for registering and enqueueing scripts
 * @param  array $styles
 */
function aitAddScripts($scripts)
{
	foreach($scripts as $handler => $script){
		if(is_bool($script) and $script === true){
			wp_enqueue_script($handler);
		}elseif(is_array($script)){
			wp_register_script($handler, $script['file'],  isset($script['deps']) ? $script['deps'] : array(), isset($script['ver']) ? $script['ver'] : false, isset($script['inFooter']) ? $script['inFooter'] : false);

			if(!isset($script['enqueue']) or (isset($script['enqueue']) and $script['enqueue'] == true))
				wp_enqueue_script($handler);

			if(isset($script['localize']) and $script['localize'])
				wp_localize_script($handler, 'Ait', $script['localize']);
		}
	}
}



/**
 * Better API for registering plugins which theme depends on
 * @param  array $plugins
 */
function aitRegisterPlugins()
{
	global $aitRequiredPlugins;

	if(!empty($aitRequiredPlugins)){
		$config = array(
			'domain'           => 'ait',
			'parent_menu_slug' => 'plugins.php',
			'parent_url_slug'  => 'plugins.php',
			'menu'             => 'install-required-plugins',
			'is_automatic'     => true,
			'strings'          => array(
				'menu_title'   => __('Install Required Plugins', 'ait'),
			),
		);

		tgmpa($aitRequiredPlugins, $config);
	}
}



/**
 * Helper method to preserve same AIP as aitAddStyles and aitAddScripts
 * @param  plugins $plugins Same structure of array as in http://goo.gl/ObDrA
 */
function aitAddPlugins($plugins)
{
	global $aitRequiredPlugins;

	if(is_admin())
		$aitRequiredPlugins = $plugins;
}



/**
 * Get image size by url
 */
function aitGetImageSize($imageUrl)
{
	$imageSize = array();
	if(!empty($imageUrl)){
		if ($imageSize = @getimagesize($imageUrl)) {
		} else {
			$src = THEME_DIR . "/" . substr($imageUrl, strlen(THEME_URL) + 1);
			if(is_file($src)){
				$imageSize = @getimagesize($src);
			} else {
				$u = wp_upload_dir();
				$baseUrl = $u['baseurl'];
				$baseDir = $u['basedir'];
				$src = $baseDir . "/" . substr($imageUrl, strlen($baseUrl) + 1);
				$imageSize = @getimagesize($src);
			}
		}
	}
	return $imageSize;
}



function getRealThumbnailUrl($multisiteUrl)
{
	global $blog_id;
	if(is_multisite()){
		if(strstr($multisiteUrl, '/files/') !== false){
			if(isset($blog_id) && $blog_id > 0){
				$a = explode('/files/', $multisiteUrl);
				if(isset($a[1])){
					$u = parse_url($a[0]);
					$a0 = (!is_subdomain_install() and !is_main_site() and isset($u['path'])) ? dirname($a[0]) : $a[0];
					$multisiteUrl = $a0 . '/wp-content/blogs.dir/' . $blog_id . '/files/' . $a[1];
				}
			}
		}else{
			$a = explode('/wp-content/', $multisiteUrl);
			//removes site address part from url in multisite install
			$u = parse_url($a[0]);
			$a0 = (!is_subdomain_install() and !is_main_site() and isset($u['path'])) ? dirname($a[0]) : $a[0];
			$multisiteUrl = $a0 . '/wp-content/' . $a[1];
		}
	}
	return $multisiteUrl;
}