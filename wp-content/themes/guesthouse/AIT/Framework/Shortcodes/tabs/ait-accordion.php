<?php
/* **********************************************************
 * jQuery UI Accordion (toggles)
 * **********************************************************/
function theme_toggles( $params, $content = null) {
    extract( shortcode_atts( array(
    	'id' => rand(100,1000),
        'ver' => $GLOBALS['aitThemeShortcodes']['tabs']
    ), $params ) );
		
	$scontent = do_shortcode($content);
	if(trim($scontent) != ""){
		$output = '<div id="ait-accordion-'.$id.'" class="sc-accordion">'.$scontent;
		$output .= '</div>';
		$output .= '<script type="text/javascript">
		jQuery(function() {
			jQuery( "#ait-accordion-'.$id.' > br" ).remove();
			jQuery( "#ait-accordion-'.$id.' > p" ).remove();
			
			jQuery( "#ait-accordion-'.$id.'" ).accordion();
		});
		</script>';
		
		return $output;
	} else {
		return "";
	}
}
add_shortcode( 'toggles', 'theme_toggles' );

function theme_toggle( $params, $content = null) {
    extract( shortcode_atts( array(
        'title' => 'title'
    ), $params ) );

	return str_replace('<p></p>','','<div class="ac-title"><a href="#">'.$title.'</a></div><div>'.do_shortcode($content).'</div>');
	
}
add_shortcode( 'toggle', 'theme_toggle' );