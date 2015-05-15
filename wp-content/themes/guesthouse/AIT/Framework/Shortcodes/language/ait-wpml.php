<?php
/*
 * This shortcode require WPML plugin
 */
function theme_wpml($atts, $content = null ) {
	extract(shortcode_atts(array(
		'code' => '',
	), $atts));

	if(defined('ICL_LANGUAGE_CODE') and ICL_LANGUAGE_CODE == $code){
		$result = do_shortcode($content);
	} else {
		$result = "";
	}

	return $result;
}
add_shortcode('lang', 'theme_wpml');




