<?php
function theme_shortcode_list($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		'style' => '',
		'line' => '',
	), $atts));
	if($line == "yes"){
		$line = " line";
	} else {
		$line = " ";
	}
	if($style){
		$style = " ".$style;
	}

	return str_replace('<ul', '<ul class="sc-list'.$style.$line.'"', do_shortcode($content));
}
add_shortcode('list', 'theme_shortcode_list');