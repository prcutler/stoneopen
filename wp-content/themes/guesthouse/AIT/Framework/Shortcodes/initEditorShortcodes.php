<?php

/*
Hook into WordPress
*/
add_action('init', 'aitAddShortcodesButtons');
add_action('admin_footer', 'aitAddShortcodesButtonsCss');



/*
Create Our Initialization Function
*/
function aitAddShortcodesButtons()
{
	global $aitThemeShortcodes, $aitEditorShortcodes;

	if(isset($aitThemeShortcodes) and !empty($aitThemeShortcodes)){
		foreach($aitThemeShortcodes as $shortcode => $ver){
			require_once AIT_FRAMEWORK_DIR . "/Shortcodes/{$shortcode}/load.php";
		}
	}

	if(!current_user_can('edit_posts') && !current_user_can('edit_pages')) return;


	if (get_user_option('rich_editing') == 'true' && aitShowAdminFeature('wysiwyg') and is_admin()) {
		add_filter('mce_external_plugins', 'aitMceExternaPlugins');
		add_filter('mce_buttons_3', 'aitMceButtons');
	}
}



/*
 * Add tinymce shortcode buttons css to admin footer
 * This is needed for displaying buttons' icons
 */
function aitAddShortcodesButtonsCss() {
	global $aitThemeShortcodes;

	echo '<style type="text/css">';
	foreach($aitThemeShortcodes as $shortcode => $ver){
		if (file_exists(AIT_FRAMEWORK_DIR . "/Shortcodes/{$shortcode}/icon.png")) {
			$shortcodeButtonIconPath =  get_template_directory_uri() . "/AIT/Framework/Shortcodes/{$shortcode}/icon.png";
			echo ".mce-i-ait_shortcodes_{$shortcode} { background-image: url('{$shortcodeButtonIconPath}'); }";
		}
	}
	echo "</style>";
}


/*
Register Buttons
*/
function aitMceButtons( $buttons ) {
	global $aitEditorShortcodes;

	if(isset($aitEditorShortcodes) and !empty($aitEditorShortcodes)){
		foreach($aitEditorShortcodes as $shortcode){
			if($shortcode === 'language' and !function_exists('icl_get_languages')) continue;
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

	$oldWpVersion = get_bloginfo('version') < 3.9 ? "-old" : "";

	$from = $_SERVER['REQUEST_URI'];
	if(isset($aitEditorShortcodes) and !empty($aitEditorShortcodes)){
		foreach($aitEditorShortcodes as $shortcode){
			$plugins["ait_shortcodes_" . $shortcode] =  AIT_FRAMEWORK_URL . "/Shortcodes/pluginScript{$oldWpVersion}.php?plugin={$shortcode}&from={$from}";
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