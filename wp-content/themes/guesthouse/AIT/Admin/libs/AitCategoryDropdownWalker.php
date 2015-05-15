<?php

/**
 * AIT WordPress Framework
 *
 * Copyright (c) 2011, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */


/**
 * Create modified dropdown list of Categories for AIT Admin
 * @uses Walker_CategoryDropdown
 */
class AitCategoryDropdownWalker extends Walker_CategoryDropdown
{
	/**
	 * @see Walker::start_el()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $category Category data object.
	 * @param int $depth Depth of category. Used for padding.
	 * @param array $args Uses 'selected', 'show_count', and 'show_last_update' keys, if they exist.
	 */
	function start_el(&$output, $category, $depth = 0, $args = array(), $id = 0)
	{
		$pad = str_repeat('&nbsp;', $depth * 3);

		if(is_object($category)){
			$cat_name = apply_filters('list_cats', $category->name, $category);
			$output .= "\t<option class=\"level-$depth\" value=\"".$category->slug."\"";
			if ( $category->slug == $args['selected'] )
				$output .= ' selected="selected"';
			$output .= '>';
			$output .= $pad.$cat_name;
			if ( $args['show_count'] )
				$output .= '&nbsp;&nbsp;('. $category->count .')';
			if ( @$args['show_last_update'] ) {
				$format = 'Y-m-d';
				$output .= '&nbsp;&nbsp;' . gmdate($format, $category->last_update_timestamp);
			}
			$output .= "</option>\n";
		}
	}
}