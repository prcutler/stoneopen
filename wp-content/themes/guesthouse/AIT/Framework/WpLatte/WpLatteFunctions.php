<?php

/**
 * AIT WordPress Framework
 *
 * Copyright (c) 2011, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/**
 * Helper functions used in WpLatte and WpLatte macros
 */
class WpLatteFunctions
{

	/**
	 * Gets content for title in <title> element
	 * @return string Title for <title> element
	 */
	public static function getTitle()
	{
		global $page, $paged;

		$return = '';

		// If Wordpress SEO is activated (Yoast Wordpress SEO)
		if(is_plugin_active('wordpress-seo/wp-seo.php')){
			$return .= wp_title('');
		} else {

			$return .= wp_title('|', false, 'right');
			$return .= get_bloginfo('name');
			$site_description = get_bloginfo('description', 'display');
			if($site_description && (is_home() || is_front_page()))
				$return .= " | $site_description";

			// Add a page number if necessary:
			if($paged >= 2 || $page >= 2)
				$return .= ' | ' . sprintf(__('Page %s', 'ait' ), max($paged, $page));
		}

		return $return;
	}



	/**
	 * Alias simply isn't enough for edit_comment_link()
	 * @param string $link
	 * @param type $id
	 * @param string $before
	 * @param string $after
	 * @return string HTML link
	 */
	public static function editCommentLink($link = null, $id, $before = '', $after = '')
	{

		if(!current_user_can('edit_comment', $id))
			return;

		if(is_null($link))
			$link = __('Edit This', 'ait');

		$link = '<a class="comment-edit-link" href="' . get_edit_comment_link($id) . '" title="' . esc_attr__( 'Edit comment' ) . '">' . $link . '</a>';
		echo $before . apply_filters( 'edit_comment_link', $link, $id ) . $after;
	}



	/**
	 * Function called by macro {less ...}
	 * @param string $file URL via: get_bloginfo('stylesheet_url')
	 * @return string URL to CSS file with timestamp mark
	 */
	public static function lessify()
	{
		$importDir = THEME_CSS_DIR;

		$input = THEME_DIR . '/style.less.css';
		$output = THEME_STYLESHEET_FILE;

		if(!file_exists($input)){
			wp_die("File '$input' doesn't exists.", "File '$input' doesn't exists.", array('response' => 500, 'back_link' => true));
		}

		$timeIn  = filemtime($input);
		$timeOut = intval(@filemtime($output)); // @ - file not exists, intval(false) -> 0

		$a = array();
		$a[-1] = $timeIn;
		$fs =  array_filter(array_merge((array) @glob("{$importDir}/*.css"), (array) @glob("{$importDir}/*.less")));
		foreach ($fs as $f) {
			$a[] = filemtime($f);
		}

		$max = max($a);

		// parse only if there is no style.css or LESS file is newer then CSS file
		if(!file_exists($output) || ((file_exists($output) &&  $max > $timeOut) || (file_exists($output) &&  $max == $timeOut))){
			self::saveLess2Css();
		}

		return THEME_STYLESHEET_URL . "?" . $max;
	}



	/**
	 * Converts and saves LESS to CSS file
	 * @param string $input Path to LESS file
	 * @param string $output Path to CSS file
	 * @param stdClass $options Object with settings from DB
	 * @return void
	 */
	public static function saveLess2Css($input = null, $output = null, $options = null)
	{
		aitSaveLess2Css($input, $output, $options);
	}



	/**
	 *
	 *
	 */
	public static function generatedCss()
	{
		$importDir = THEME_CSS_DIR;

		$input = THEME_DIR . '/style.less.css';
		$outputName = "style." . (defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : 'en') . ".css";
		$output = AIT_CACHE_URL . "/$outputName";

		if(!file_exists($input)){
			wp_die("File '$input' doesn't exists.", "File '$input' doesn't exists.", array('response' => 500, 'back_link' => true));
		}

		$timeIn  = filemtime($input);
		$timeOut = intval(@filemtime($output)); // @ - file not exists, intval(false) -> 0

		$a = array();
		$a[-1] = $timeIn;
		$fs =  array_filter(array_merge((array) @glob("{$importDir}/*.css"), (array) @glob("{$importDir}/*.less")));
		foreach ($fs as $f) {
			$a[] = @filemtime($f);
		}

		$max = max($a);

		// parse only if there is no style.css or LESS file is newer then CSS file
		if(!file_exists($output) || ((file_exists($output) &&  $max > $timeOut) || (file_exists($output) &&  $max == $timeOut))){
			aitGenerateCss();
		}

		return $output . "?" . $max;
	}




	/**
	 * Gets CSS selectors for realtime appearance manipulations
	 * @param string $what What to return. 'string' for pregenerated JS array or 'raw' for PHP array
	 * @return array Array of CSS properties, LESS variables and selectors
	 */
	public static function getCssFontSelectors($optionKey = null)
	{
		aitIncludeLess();
		$less = new AitLess(THEME_DIR . '/style.less.css');
		$variables = aitPrepareVariablesForLess();
		$styleLess = $less->parseTree();
		$selectors = array();

		$isLessVar = false;
		$i = -1;

		foreach($styleLess as $key => $value){
			if(is_array($value)){
				foreach($value as $i => $k){
					if(is_array($k) and $k[0] == 'block'){
						foreach($k[1]->props as $property){
							if($property[1] == 'font-family'){
								$isLessVar = false;

								if((isset($property[2][2][0][0]) and $property[2][2][0][0] == 'variable') or (isset($property[2][2][1][0]) and $property[2][2][1][0] == 'variable')){

									if($property[2][2][0][1][0] == "@"){
										$option = trim($property[2][2][0][1], "@");
									}
									$isLessVar = true;
								}

								if(isset($property[2][0]) and $property[2][0] == 'variable'){

									if($property[2][1][0] == "@"){
										$option = trim($property[2][1], "@");
									}
									$isLessVar = true;
								}

								if($isLessVar){
									$i++;

									$tags = is_array($k[1]->tags) ? implode(', ', $k[1]->tags) : $k[1]->tags;
									preg_match_all('/:hover/i', $tags, $hovers);

									if(!empty($hovers) and isset($hovers[0][0])){

										foreach($k[1]->props as $p){
											list(, $pname, $pvalue) = $p;
											$line = $less->reduce($pvalue, $variables);

											if($p[1] == 'text-shadow'){
												$selectors[$option]['hover'][$i]['text-shadow'] = $less->compileValue($line);
											}

											if($p[1] == 'color'){
												$selectors[$option]['hover'][$i]['color'] = $less->compileValue($line);
											}
										}

										$tags = preg_replace('/:hover/i', '', $tags);
									}

									$selectors[$option]['selectors'][$i] = $tags;


									foreach($k[1]->props as $p){
										if($p[1] == 'text-shadow'){
											list(, $pname, $pvalue) = $p;
											$selectors[$option]['text-shadow'][$i] = $less->compileValue($less->reduce($pvalue, $variables));
										}
									}
								}
							}
						}
					}
				}
			}
		}

		$i = 0;
		$s = array();
		foreach($selectors as $key => $values){

			$i = 0;
			foreach($values as $group => $value){
				foreach($value as $index => $v){
					$tags = $selectors[$key]['selectors'][$index];
					$s[$key][$tags]['selectors'] = $tags;

					if(isset($selectors[$key]['text-shadow']) and isset($selectors[$key]['text-shadow'][$index])){
						$s[$key][$tags]['text-shadow'] = $selectors[$key]['text-shadow'][$index];
					}
					if(isset($selectors[$key]['hover']) and isset($selectors[$key]['hover'][$index])){
						$s[$key][$tags]['hover'] = $selectors[$key]['hover'][$index];
					}

				}
			}

		}


		if(empty($optionKey)){
			return $s;
		}else{
			if(isset($s[$optionKey])){
				return $s[$optionKey];
			}else{
				return 'bad option key';
			}
		}

		return $return;
	}



	/**
	 * Helper function, use in future... maybe...
	 */
	public static function getConfigOptions($key)
	{
		$config = array();

		if(isset($GLOBALS['aitThemeConfig'][$key])){
			$config = $GLOBALS['aitThemeConfig'][$key]['options'];
		}else{
			foreach($GLOBALS['aitThemeConfig'] as $k => $v){
				if(isset($v['tabs'])){
					if(isset($GLOBALS['aitThemeConfig'][$k]['tabs'][$key])){
						$config = $GLOBALS['aitThemeConfig'][$k]['tabs'][$key]['options'];
						break;
					}
				}
			}
		}

		if(empty($config))
			throw new Exception("There is no key like '$key' in config file.");

		return $config;
	}



	/**
	 * Gets the breadcrumbs
	 * @param string $delimiter
	 * @param array array('home' => 'Home', 'delimiter' => '&raquo;')
	 * @return string Breadcrumbs HTML
	 */
	public static function breadcrumbs($args = array())
	{

		if(!isset($args['delimiter']))
			$delimiter = '&raquo;';
		else
			$delimiter = $args['delimiter'];

		if(!isset($args['home']))
			$home = __('Home', 'ait');
		else
			$home = $args['home'];

		$return = '';

		$before = '<span class="current">'; // tag before the current crumb
		$after = '</span>'; // tag after the current crumb

		if(!is_home() && !is_front_page() || is_paged()){

			$return .= '<span class="crumbs">';

			global $post;
			//$homeLink = get_bloginfo('url');
			$homeLink = home_url();
			$return .= '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';

			if(is_category()){
				$cat_obj = $GLOBALS['wp_query']->queried_object;
				$thisCat = $cat_obj->term_id;
				$thisCat = get_category($thisCat);
				$parentCat = get_category($thisCat->parent);

				if ($thisCat->parent != 0)
					$return .= get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' ');

				$return .= $before . sprintf(__('Category: "%s"', 'ait'), single_cat_title('', false)) . $after;

			}elseif(is_day()){
				$return .= '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
				$return .= '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
				$return .= $before . get_the_time('d') . $after;

			}elseif(is_month()){
				$return .= '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
				$return .= $before . get_the_time('F') . $after;

			}elseif(is_year()){
				$return .= $before . get_the_time('Y') . $after;

			}elseif(is_single() && !is_attachment()){
				if (get_post_type() != 'post'){
					$post_type = get_post_type_object(get_post_type());
					$slug = $post_type->rewrite;
					if(THEME_CODE_NAME == 'guesthouse'){
          $return .= $post_type->labels->singular_name . ' ' . $delimiter . ' ';
          } else {
          $return .= '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
          }

					$return .= $before . get_the_title() . $after;
				}else{
					$cat = get_the_category();
					if(!empty($cat)){
						$cat = $cat[0];
						$return .= get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
						$return .= $before . get_the_title() . $after;
					}
				}

			}elseif(!is_single() && !is_search() && !is_page() && get_post_type() != 'post' && !is_404()){
				$post_type = get_post_type_object(get_post_type());
				if(is_null($post_type)) return '';
				$return .= $before . $post_type->labels->singular_name . $after;

			}elseif(is_attachment()){
				$parent = get_post($post->post_parent);
				$cat = get_the_category($parent->ID);
				if(is_array($cat) and isset($cat[0])){
					$cat = $cat[0];
					$return .= get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
					$return .= '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
					$return .= $before . get_the_title() . $after;
				}

			}elseif(is_page() && !$post->post_parent){
				$return .= $before . get_the_title() . $after;

			}elseif(is_page() && $post->post_parent){
				$parent_id  = $post->post_parent;
				$breadcrumbs = array();

				while($parent_id){
					$page = get_page($parent_id);
					$breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
					$parent_id  = $page->post_parent;
				}

				$breadcrumbs = array_reverse($breadcrumbs);

				$return .= trim(join($breadcrumbs, ' ' . $delimiter . ' '));

				$return .= ' ' . $delimiter . ' ' . $before . get_the_title() . $after;

			}elseif(is_tag()){
				$return .= $before . sprintf(__('Posts tagged &quot;%s&quot;', 'ait'), single_tag_title('', false)) . $after;

			}elseif(is_author()){
				global $author;
				$userdata = get_userdata($author);
				$return .= $before . sprintf(__('Author: &quot;%s&quot;', 'ait'), $userdata->display_name) . $after;

			}elseif(is_404()){
				$return .= $before . __('Error 404', 'ait') . $after;

			}elseif(is_search()){
				$return .= $before . sprintf(__('Search results for &quot;%s&quot;', 'ait'), get_search_query()) . $after;
			}

			if(get_query_var('paged')){
				if( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author())
					$return .= ' (';

				$return .= __('Page', 'ait') . ' ' . get_query_var('paged');

				if(is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author())
					$return .= ')';
			}

			$return .= '</span>';

		}elseif($GLOBALS['wp_query']->is_posts_page){

			$return .= '<span class="crumbs">';
			//$return .= '<a href="' . get_bloginfo('url') . '">' . $home . '</a> ' . $delimiter . ' ';
      $return .= '<a href="' . home_url() . '">' . $home . '</a> ' . $delimiter . ' ';
			$return .= $GLOBALS['wp_query']->queried_object->post_title;
			$return .= '</span>';

		}elseif(is_home() || is_front_page()){
      $return .= '<span class="crumbs">';
			//$return .= '<a href="' . get_bloginfo('url') . '">' . $home . '</a>';
      $return .= '<a href="' . home_url() . '">' . $home . '</a>';
			$return .= '</span>';
    }

		return $return;
	}



	/**
	 * Process better link
	 */
	public static function linkTo($link = null)
	{
	    if($link == null) return;

	    if(strpos($link, 'http://') !== false){
	        return $link;

		}elseif(strpos($link, '/') === 0){
	        return get_option('siteurl') . $link;

		}else{
	        return get_option('siteurl') . '/' . $link;
	    }
	}



	/**
	 * Get language code from WPML plugin
	 */
	public static function getLanguageCode()
	{
		if(defined('ICL_LANGUAGE_CODE')){
			return ICL_LANGUAGE_CODE;
		}else{
			return "";
		}
	}



	/**
	 * Get day link
	 * @param string $date Date in format accepted by strtotime().
	 */
	public static function getDayLink($date = "")
	{
		$dateArray = date_parse($date);
		return get_day_link($dateArray['year'], $dateArray['month'], $dateArray['day']);
	}



	/**
	 * Get paginate links
	 */
	public static function paginateLinks($new = false)
	{
		global $wp_query;

		$return = "";

		if($new){

			$range = 1;
			$centerItems = ($range*2)+1;

			$total = intval($wp_query->max_num_pages);
			$current = max(1, get_query_var('paged')); // if 0 then 1

			if($total > 1) {

				$return = '<div class="page_nav">';

				// Prev
				if($current > 1){
					$return .= '<a class="page-numbers prev" href="'.get_pagenum_link($current - 1).'">'.__('Prev', 'ait').'</a>';
				}
				// First
				if($current == 1){
					$return .= '<span class="page-numbers current">1</span>';
				} else {
					$return .= '<a class="page-numbers" href="'.get_pagenum_link(1).'">1</a>';
				}

				$j = 0;
				for($i = $current - $range; $i <= $current + $range; $i++){
					if($i > 1 && $i < $total){
						$j++;
					}
				}
				if($j > 0) $return .= '<span class="dots">...</span>';

				for($i = $current - $range; $i <= $current + $range; $i++){
					if($i > 1 && $i < $total){
						if($i == $current){
							// Current
							$return .= '<span class="page-numbers current">'.$current.'</span>';
						} else {
							$return .= '<a class="page-numbers" href="'.get_pagenum_link($i).'">'.$i.'</a>';
						}
					}
				}

				if($j > 0) $return .= '<span class="dots">...</span>';

				// Last
				if($current == $total){
					$return .= '<span class="page-numbers current">'.$total.'</span>';
				} else {
					$return .= '<a class="page-numbers" href="'.get_pagenum_link($total).'">'.$total.'</a>';
				}
				// Next
				if($current < $total){
					$return .= '<a class="page-numbers next" href="'.get_pagenum_link($current + 1).'">'.__('Next', 'ait').'</a>';
				}

				$return .= '</div>';

			}

		} else {

			$total_pages = $wp_query->max_num_pages;

			if ($total_pages > 1){

			  $current_page = max(1, get_query_var('paged'));

			  $return = '<div class="page_nav">';

			  $return .= paginate_links(array(
			      'base' => get_pagenum_link(1) . '%_%',
			      'format' => 'page/%#%',
			      'current' => $current_page,
			      'total' => $total_pages,
			      'prev_text' => __('Prev', 'ait'),
			      'next_text' => __('Next', 'ait'),
			    ));

			  $return .= '</div>';

			}


		}

		return $return;

	}



	/**
	 * dash-separated -> camelCase.
	 * @param  string
	 * @return string
	 */
	public static function dash2camel($s)
	{
		$s = strtolower($s);
		$s = preg_replace('#([.-])(?=[a-z])#', '$1 ', $s);
		$s = ucwords($s);
		$s = strtolower($s[0]) . substr($s, 1);
		$s = str_replace('- ', '', $s);
		return $s;
	}



	/**
	 * camelCase -> underscore_separated.
	 * @param  string
	 * @return string
	 */
	public static function camel2underscore($s)
	{
		$s = preg_replace('#(.)(?=[A-Z])#', '$1_', $s);
		$s = strtolower($s);
		return $s;
	}



	/**
	 * Alias for strip_tags() PHP function
	 * @param  string $inputText Input string with tags
	 * @return string Stripped string
	 */
	public static function stripTag($inputText = "")
	{
	    $stripped = strip_tags($inputText);
	    return $stripped;
  	}

}

