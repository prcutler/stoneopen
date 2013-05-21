<?php

/*
Hook into WordPress
*/
add_action('init', 'aitAddShortcodesButtons');



/*
Create Our Initialization Function
*/
function aitAddShortcodesButtons()
{
	global $showAdmin, $aitThemeShortcodes, $aitEditorShortcodes;

	if(isset($aitThemeShortcodes) and !empty($aitThemeShortcodes)){
		foreach($aitThemeShortcodes as $shortcode => $ver){
			require_once AIT_FRAMEWORK_DIR . "/Shortcodes/{$shortcode}/load.php";
		}
	}

	if(!current_user_can('edit_posts') && !current_user_can('edit_pages')) return;

   	if(isset($showAdmin['wysiwyg']) == false){
   		$showAdmin['wysiwyg'] = "enabled";
   	}

   	if (get_user_option('rich_editing') == 'true' && $showAdmin['wysiwyg'] != 'disabled' and is_admin()) {
     	add_filter('mce_external_plugins', 'aitMceExternaPlugins');
     	add_filter('mce_buttons_3', 'aitMceButtons');
   	}

}



/*
Register Buttons
*/
function aitMceButtons( $buttons ) {
	global $aitEditorShortcodes;

	if(isset($aitEditorShortcodes) and !empty($aitEditorShortcodes)){
		foreach($aitEditorShortcodes as $shortcode){
			$buttons[] = "ait_shortcodes_" . $shortcode;
		}
	}
	return $buttons;
}



/*
Register TinyMCE Plugins
*/
function aitMceExternaPlugins($plugins) {
	global $aitEditorShortcodes;

	$from = $_SERVER['REQUEST_URI'];
	if(isset($aitEditorShortcodes) and !empty($aitEditorShortcodes)){
		foreach($aitEditorShortcodes as $shortcode){
			$plugins["ait_shortcodes_" . $shortcode] =  AIT_FRAMEWORK_URL . "/Shortcodes/pluginScript.php?plugin={$shortcode}&from={$from}";
		}
	}
   	return $plugins;
}

/*
Shortcode empty Paragraph fix

add_filter('the_content', 'sc_empty_p_fix');
function sc_empty_p_fix($content)
{
    $array = array (
        '<p>[' => '[',
        ']</p>' => ']',
        ']<br />' => ']'
    );

    $content = strtr($content, $array);

	return $content;
}
*/