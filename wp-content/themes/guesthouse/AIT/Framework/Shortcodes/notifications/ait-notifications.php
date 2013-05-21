<?php
function theme_notification($atts, $content = null ) {
	extract(shortcode_atts(array(
		'type' => '',
	), $atts));
	
	if($type){
		$type = " note-".$type;	
	}
	
	$result = '<div class="sc-notification'.$type.'">';
	$result .= '	<a href="#" class="close" title="Close notification">close</a>';
	$result .= '	<p>'.do_shortcode($content).'</p>';
	$result .= '</div>';
	
	return $result;
}
add_shortcode('notification', 'theme_notification');


				
				
			