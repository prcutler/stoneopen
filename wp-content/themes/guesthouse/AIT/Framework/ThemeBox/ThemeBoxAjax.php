<?php

/**
 * AIT WordPress Framework
 *
 * Copyright (c) 2011, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */

$wpLoad = realpath(dirname(__FILE__) . '/../../../../../../wp-load.php');

require_once $wpLoad;

$opt = substr(AIT_DEFAULT_OPTIONS_KEY, 0, -2);

$payload = new stdClass;


if(is_user_logged_in() and isset($_SERVER['HTTP_X_REQUESTED_WITH']) and $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest'){
	$formValues = $_POST['ait-themebox'];
	$lang = $formValues['lang'];
	unset($formValues['lang']);

	$opt .= $lang;

	$options = get_option($opt);

	if(empty($options)){
		$options = aitGetThemeDefaultOptions($GLOBALS['aitThemeConfig']);
	}

	$result = array_replace_recursive($options, $formValues);


	$updated = update_option($opt, $result);

	if($updated){
		if(defined(AIT_FW_VERSION) and version_compare(AIT_FW_VERSION, "1.1", ">=")){
			aitGenerateCss();
		}else{
			aitSaveLess2Css();
		}

		$payload->msg = '<p class="ait-themebox-success">' . __('Settings succesfully saved. Wheee ^_^', THEME_CODE_NAME) . '</p>';

	}else{
		$payload->msg = '<p class="ait-themebox-error">' . __('I could not save the settings. Change some values or please try again.', THEME_CODE_NAME) . '</p>';
	}

	exit(json_encode($payload));

}else{

	exit('Nothing to see here. Move along...');
}