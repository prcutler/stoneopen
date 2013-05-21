<?php
/* **********************************************************
 * VIDEO
 * **********************************************************/
function theme_video( $params, $content = null) {
    extract( shortcode_atts( array(
        'type' => '',
        'link' => '',
        'width' => '',
        'height' => '',
        'border' => ''
    ), $params ) );
	
	if($link == "") { return ""; }
	
	if($width == "") { $width = 600; }
	
	if($height == "") { $height = 370; }
	
	if($border != ""){
		$borderStyle = ' border';
	} else {
		$borderStyle = "";
	}
	
  	if($type == "youtube"){
  		
		$vidparser = parse_url($link);
		parse_str($vidparser['query'], $query);
		$clip_id = ($query['v']);
		
  		return '<div class="sc-video'.$borderStyle.'"><div class="wrap"><iframe width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$clip_id.'?wmode=opaque"></iframe></div></div>';
		
  	} elseif ($type == "vimeo"){ 
  		
		$clip_id = str_replace("http","",$link);
		$clip_id = str_replace(":","",$clip_id);
		$clip_id = str_replace("/","",$clip_id);
		$clip_id = trim(str_replace("vimeo.com","",$clip_id));
  		
  		return '<div class="sc-video'.$borderStyle.'"><div class="wrap"><iframe src="http://player.vimeo.com/video/'.$clip_id.'?title=0&amp;byline=0&amp;portrait=0" width="'.$width.'" height="'.$height.'"></iframe></div></div>';
  	}
}
add_shortcode( 'video', 'theme_video' );