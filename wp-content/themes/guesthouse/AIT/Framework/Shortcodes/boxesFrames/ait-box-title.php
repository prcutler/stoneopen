<?php
/* **********************************************************
 * BOX-TITLE
 * **********************************************************/
function theme_box_title( $params, $content = null) {
    extract( shortcode_atts( array(
    	'align' => ''
    ), $params ) );

	$alignStyle = '';
	if($align != ""){
		$alignStyle = ' style="text-align: '.$align.';"';
	}
	
	$result = '<div class="sc-box-title"'.$alignStyle.'><div class="wrap">';
	$result .= do_shortcode( $content );
	$result .= '</div></div>';
  	return $result;
}
add_shortcode( 'box-title', 'theme_box_title' );