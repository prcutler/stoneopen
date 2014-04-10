<?php

/**
 * AIT WordPress Framework
 *
 * Copyright (c) 2011, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */

/**
 * ThemeBox
 */
class ThemeBox
{
	/**
	 * @var string Absolut URL to ThemeBox folder
	 */
	public $url;

	/**
	 * @var string Additional ThemeBox template specific for current Theme
	 */
	public $themeTemplate;

	/**
	 * @var array Split ThemeBox options to basic sesctions
	 */
	public $sections;

	/**
	 * @var stdClass
	 */
	public $otherAitThemes;

	/**
	 * @var string Path to LESS file
	 */
	public $lessFile;

	/**
	 * @var stdClass Theme Options from DB
	 */
	public $themeOptions;

	/**
	 * @var array Theme Config from config file
	 */
	private $themeConfig;

	/**
	 * @var WpLatteSiteEntity
	 */
	private $site;



	/**
	 * @param array $config Theme Config from config file
	 * @param stdClass $options Theme Options from DB
	 * @param string $url Absolut URL to ThemeBox folder
	 * @param string $template Additional ThemeBox template specific for current Theme
	 */
	public function __construct($config, $options)
	{
		$this->themeOptions = $options;

		// make from tabs first level items
		foreach($config as $menuKey => $page){
			if(isset($page['tabs'])){
				foreach($page['tabs'] as $tabKey => $tabPage){
					unset($config[$menuKey]);
					$config[$tabKey] = $tabPage;
				}
			}
		}

		$this->themeConfig = $config;

	}



	/**
	 * @param WpLatteSiteEntity $site
	 */
	public function setSiteEntity($site)
	{
		$this->site = $site;
	}



	/**
	 * Gets all options for ThemeBox
	 * @return array
	 */
	public function getOptions($optionKey = null)
	{
		$return = array();

		foreach($this->themeConfig as $menuKey => $page){
			foreach($page['options'] as $option => $value){
				if(!isset($value['section-title'])){
					if(isset($value['themebox'])){
						$type = $value['type'];
						if(isset($value['themebox']['label'])){
							$return[$type][$option]['label'] = $value['themebox']['label'];
						}else{
							$return[$type][$option]['label'] = $value['label'];
						}

						$return[$type][$option]['section'] = $menuKey;
						$return[$type][$option]['default'] = isset($value['default']) ? $value['default'] : '';
						$return[$type][$option]['page'] = (array) $value['themebox']['page'];

						unset($value['themebox']['page']);
						unset($value['themebox']['label']);

						if($value['type'] == 'font'){
							$return[$type][$option]['value'] = str_replace(' ', '+', $this->themeOptions->$menuKey->$option->font);
						}else{
							if(!empty($value['themebox'])){
								$return[$type][$option]['value'] = $value['themebox'];
							}else{
								$return[$type][$option]['value'] = $this->themeOptions->$menuKey->$option;
							}
						}


						$return[$type][$option]['displayIt'] = false;

						if(((in_array('homepage', $return[$type][$option]['page']) and $this->site->isFrontPage) or
							(in_array('post', $return[$type][$option]['page']) and $this->site->isSingle) or
							(in_array('page', $return[$type][$option]['page']) and $this->site->isPage and !$this->site->isFrontPage) or
							(in_array('blog', $return[$type][$option]['page']) and $this->site->isBlog)) or
							in_array('all', $return[$type][$option]['page'])){
							$return[$type][$option]['displayIt'] = true;
						}

						if($optionKey !== null and isset($return[$type][$optionKey]) and $optionKey == $option){
							return $return[$type][$optionKey];
						}
					}
				}
			}
		}

		if($optionKey === null)
			return $return;
		else
			return array();
	}



	/**
	 * Gets all options for ThemeBox
	 * @return array
	 */
	public function getLessVariables()
	{
		$return = array();

		foreach($this->themeConfig as $menuKey => $page){
			foreach($page['options'] as $option => $value){
				if(!isset($value['section-title'])){
					if(isset($value['themebox']) and isset($this->sections[$value['type']])){
						$return[] = $option;
					}
				}
			}
		}
		return $return;
	}


	/**
	 * Gets CSS selectors for realtime appearance manipulations
	 * @param string $what What to return. 'string' for pregenerated JS array or 'raw' for PHP array
	 * @return array Array of CSS properties, LESS variables and selectors
	 */
	public function getSelectors($what = 'string')
	{
		aitIncludeLess();
		
		$less = new AitLess($this->lessFile);
		$styleLess = $less->parseTree();
		$return = array();
		$return['raw'] = array();

		foreach($styleLess as $key => $value){
			if(is_array($value)){
				foreach($value as $i => $k){
					if(is_array($k) and $k[0] == 'block'){
						foreach($k[1]->props as $property){

							// selectors {color: @variable;}
							if($property[1] == 'color'){
								if($property[2][0] == 'variable'){
									$return['raw'][$property[1]][trim($property[2][1], '@')][] = is_array($k[1]->tags) ? implode(', ', $k[1]->tags) : $k[1]->tags;
								}

							}elseif($property[1] == 'background'){

								// selectors {background: @variableColor @variableBg;} or
								// selectors {background: @variableBg @variableColor;}
								if($property[2][0] == 'list'){
									if(($property[2][2][0][0] == 'function' and $property[2][2][0][1] == 'url' and $property[2][2][0][2][0] == 'string') or
										($property[2][2][1][0] == 'function' and $property[2][2][1][1] == 'url' and $property[2][2][1][2][0] == 'string')){

										if(isset($property[2][2][0][2][1])) $option = trim($property[2][2][0][2][1], "\"'{}");
										if(isset($property[2][2][1][2][1])) $option = trim($property[2][2][1][2][1], "\"'{}");
										if($option[0] == '@'){
											$option = trim($option, '@');
											$return['raw']['background-image'][$option][] = is_array($k[1]->tags) ? implode(', ', $k[1]->tags) : $k[1]->tags;
										}
									}

									if($property[2][2][0][0] == 'variable' or $property[2][2][1][0] == 'variable'){

										if($property[2][2][0][1][0] == "@") $option = trim($property[2][2][0][1], "@");
										if($property[2][2][1][1][0] == "@") $option = trim($property[2][2][1][1], "@");

										$return['raw']['background-color'][$option][] = is_array($k[1]->tags) ? implode(', ', $k[1]->tags) : $k[1]->tags;
									}

								// selectors {background: @variableColor;}
								}elseif($property[2][0] == 'variable'){
									$option = trim($property[2][1], '@');
									$return['raw']['background-color'][$option][] = is_array($k[1]->tags) ? implode(', ', $k[1]->tags) : $k[1]->tags;

								}

							// selectors {background-color: @variableColor;}
							}elseif($property[1] == 'background-color'){
								if($property[2][0] == 'variable'){
									$return['raw'][$property[1]][trim($property[2][1], '@')][] = is_array($k[1]->tags) ? implode(', ', $k[1]->tags) : $k[1]->tags;
								}

							// selectors {background-image: @variableBg;}
							}elseif($property[1] == 'background-image'){
								if($property[2][0] == 'function'){
									$option = trim($property[2][2][1], "\"'{}");
									if($option[0] == '@'){
										$return['raw']['background-image'][$option][] = is_array($k[1]->tags) ? implode(', ', $k[1]->tags) : $k[1]->tags;
									}
								}

							}elseif(is_string($property[1]) and preg_match_all('/^border(?:-(?:top|bottom|left|right)(?:-color)?)?(?!radius)$/im', $property[1], $matches)){

								// selectors {border: 1px solid @variableColor;}
								// selectors {border-[top|right|bottom|left]: 1px solid @variableColor;}
								if(isset($property[2]) and isset($property[2][2]) and isset($property[2][2][2]) and $property[2][2][2][0] == 'variable'){
									if($property[2][2][2][1][0] == '@'){
										$option = trim($property[2][2][2][1], '@');
										$return['raw'][$property[1]][$option][] = is_array($k[1]->tags) ? implode(', ', $k[1]->tags) : $k[1]->tags;
									}
								}

								// selectors {border-[top|right|bottom|left]-color: @variableColor;}
								if(isset($property[2][0]) and $property[2][0] == 'variable'){
									if($property[2][1][0] == '@'){
										$option = trim($property[2][1], '@');
										$return['raw'][$property[1]][$option][] = is_array($k[1]->tags) ? implode(', ', $k[1]->tags) : $k[1]->tags;
									}
								}

							// selectors {font-family: @variableFont;}
							}elseif($property[1] == 'font-family'){
								if(isset($property[2][2]) and ($property[2][2][0][0] == 'variable' or $property[2][2][1][0] == 'variable')){

									if($property[2][2][0][1][0] == "@") $option = trim($property[2][2][0][1], "@");

									$return['raw']['font-family'][$option][] = is_array($k[1]->tags) ? implode(', ', $k[1]->tags) : $k[1]->tags;
								}
							}
						}
					}
				}
			}
		}

		$lessVars = $this->getLessVariables();

		$return['string'] = "var aitThemeBoxSelectors = [];\n";

		foreach($return['raw'] as $property => $options){

			$return['string'] .= "\n\taitThemeBoxSelectors['$property'] = [];\n";

			foreach($options as $option => $tags){
				if(in_array($option, $lessVars)){

					$return['raw'][$property][$option] = implode(', ', $tags);
					$return['string'] .= "\taitThemeBoxSelectors['$property']['$option'] = '" . implode(', ', $tags) . "';\n";

				}
			}

		}

		return $return[$what];
	}
}



$themeBox = new ThemeBox($GLOBALS['aitThemeConfig'], $GLOBALS['aitThemeOptions']);

$themeBox->setSiteEntity($site); // from $latteParams variable

$themeBox->url = AIT_FRAMEWORK_URL . '/ThemeBox';

$themeBox->themeTemplate = AIT_TEMPLATES_DIR . '/themebox.html';

$themeBox->lessFile =  THEME_DIR . '/style.less.css';

$requestUrl = 'http://www.ait-themes.com/json-export.php?ref=' . urlencode($_SERVER['SERVER_NAME']) . '&t=' . THEME_CODE_NAME . '&from=themebox';

$cacheTime = (defined('AIT_DEVELOPMENT') && AIT_DEVELOPMENT) ? 5: (2 * 24 * 60 * 60);

$themeBox->otherAitThemes = aitCachedRemoteRequest('themebox-themes', $requestUrl, $cacheTime);

$themeBox->thisTheme = isset($themeBox->otherAitThemes->{THEME_CODE_NAME}) ? $themeBox->otherAitThemes->{THEME_CODE_NAME} : null;

$themeBox->sections = array(
			'colorpicker'	=> __('Colors Settings', THEME_CODE_NAME),
			'image-url'		=> __('Background images', THEME_CODE_NAME),
			'font'			=> __('Font', THEME_CODE_NAME),
		);