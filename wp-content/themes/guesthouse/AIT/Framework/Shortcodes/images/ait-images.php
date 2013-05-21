<?php
function theme_sc_image($atts, $content = null ) {
	extract(shortcode_atts(array(
		/*'width' => '',
		'height' => '',
		'alt' => '',
		'link' => '',
		'linktype' => '',
		'align' => ''*/
	), $atts));
	
	/*
	if($width != ""){
		$width = ' width="'.$width.'"';	
	}
	if($height != ""){
		$height = ' height="'.$height.'"';	
	}
	
	
	if($linktype == "site"){
		$result = '<div class="sc-image-wrap"><a class="sc-image-link zoom website-type" href="'.link_to($link).'"><img class="sc-image thumb" src="'.$content.'" alt="'.$alt.'" '.$width.$height.'/></a></div>';
	}
	if($linktype == "image"){
		$result = '<div class="sc-image-wrap"><a class="sc-image-link zoom image-type" href="'.link_to($link).'"><img class="sc-image thumb" src="'.$content.'" alt="'.$alt.'" '.$width.$height.'/></a></div>';
	} elseif($linktype == "video") {
		$result = '<div class="sc-image-wrap"><a class="sc-image-link zoom video-type" href="'.$link.'"><img class="sc-image thumb" src="'.$content.'" alt="'.$alt.'" '.$width.$height.'/></a></div>';
	} else {
		$result = '<div class="sc-image-wrap"><img class="sc-image thumb" src="'.$content.'" alt="'.$alt.'" '.$width.$height.'/></div>';
	}
	*/
	
	return str_replace('class="', 'class="thumb sc-image ', do_shortcode($content));
}
add_shortcode('image', 'theme_sc_image');