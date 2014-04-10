<?php

/**
 * AIT WordPress Framework
 *
 * Copyright (c) 2011, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */

/**
 * Libs
 */
require_once AIT_FRAMEWORK_DIR . '/WpLatte/WpLatte.php';

 if(file_exists(AIT_FRAMEWORK_DIR . '/Libs/WPAlchemy/MetaBox.inc'))
	require_once AIT_FRAMEWORK_DIR . '/Libs/WPAlchemy/MetaBox.inc';
else
	require_once AIT_FRAMEWORK_DIR . '/Libs/WPAlchemy/MetaBox.php';

@include AIT_FRAMEWORK_DIR . '/Libs/ChromePhp/ChromePhp.php';

require_once AIT_FRAMEWORK_DIR . '/Libs/AitImageResizer.inc';

/**
 * Widgets
 */

function aitLoadWidgets(){
	global $aitThemeWidgets;
	if(isset($aitThemeWidgets) and !empty($aitThemeWidgets)){
		foreach($aitThemeWidgets as $widget){
			require_once AIT_FRAMEWORK_DIR . "/Widgets/ait-{$widget}-widget.php";
		}
	}
}
add_action('widgets_init', 'aitLoadWidgets');

/**
 * Shortcodes
 */
require_once AIT_FRAMEWORK_DIR . "/Shortcodes/initEditorShortcodes.php";

/**
 * Custom Types default columns and config
 */
require_once  AIT_FRAMEWORK_DIR . '/CustomTypes/default/default-custom-type.php';

/**
 * Custom Types
 */

if(isset($aitThemeCustomTypes) and !empty($aitThemeCustomTypes)){
	foreach($aitThemeCustomTypes as $customType => $position){

		$cptName = (pathinfo($customType, PATHINFO_FILENAME));
		$customTypeVersion = (int) (pathinfo($customType, PATHINFO_EXTENSION));

		// backward compatibility
		unset($aitThemeCustomTypes[$customType]);
		$aitThemeCustomTypes[$cptName] = $position;

		require_once AIT_FRAMEWORK_DIR . "/CustomTypes/{$cptName}/{$cptName}.php";
	}
}

/*
 **** Helper functions ***
 */

function aitAddAdminBarMenu($wpAdminBar)
{
	global $showAdmin, $aitThemeConfig;

	$adminUrl = get_admin_url(0, 'admin.php?page=');
	$adminId = 'ait-admin';
	$rootId = '';
	$rootHref = '';

	if(isset($showAdmin['branding']) == false){
		$showAdmin['branding'] = "enabled";
	}

	if(@$showAdmin['backup'] != 'disabled'){ $rootId = 'ait-admin-backup'; }
	if(@$showAdmin['skins'] != 'disabled'){ $rootId = 'ait-admin-skins'; }
	if(@$showAdmin['branding'] != 'disabled'){ $rootId = 'ait-admin-branding'; }
	if(@$showAdmin['website_settings'] != 'disabled'){
		$c = array_slice($aitThemeConfig, 0, 1);
		$_keys = array_keys($c);
		$key = reset($_keys);
		$rootHref = 'ait-admin-' . $key;
		$rootId = $adminId;

	}

	$pages = array();

	if(@$showAdmin['branding'] != 'disabled' and isset($aitDisableBranding) and !@$aitDisableBranding){ $pages['branding'] = __('Admin Branding', 'ait'); }
	if(@$showAdmin['skins'] != 'disabled'){ $pages['skins'] = __('Skins', 'ait'); }
	if(@$showAdmin['backup'] != 'disabled'){ $pages['backup'] = __('Backup', 'ait'); }

	if ($rootId) {
		// root node
		if(@$showAdmin['dashboard'] != 'disabled'){
			$wpAdminBar->add_node(array(
				'id' => $rootId,
				'title' => __('AIT Themes Admin', 'ait'),
				'href' => $adminUrl . $rootHref
			));
		} else {
			$wpAdminBar->add_node(array(
				'id' => $rootId,
				'title' => __('Theme Admin', 'ait'),
				'href' => $adminUrl . $rootHref
			));
		}
	}


	// Dashboard pages
	if(@$showAdmin['dashboard'] != 'disabled'){
		$dashboardPages = array(
			//'dashboard' => 	__('Dashboard', 'ait'),
			'docs' => 		__('Documentation', 'ait'),
			'faq' => 		__('FAQ', 'ait'),
			//'videos' => 	__('Videos', 'ait'),
			'support' => 	__('Support Forum', 'ait'),
		);

		$wpAdminBar->add_node(array(
			'id' => $rootId . '-dashboard',
			'parent' => $rootId,
			'title' => __('AIT Dashboard', 'ait'),
			'href' => $adminUrl . $adminId
		));

		foreach($dashboardPages as $id => $title){
			if($id == 'support')
				$wpAdminBar->add_node(array('id' => "{$rootId}-{$id}", 'parent' => $rootId . '-dashboard', 'title' => $title, 'href' => 'http://support.ait-themes.com/categories/wp-' . THEME_CODE_NAME, 'meta' => array('target' => '_blank')));
			else
				$wpAdminBar->add_node(array('id' => "{$rootId}-{$id}", 'parent' => $rootId . '-dashboard', 'title' => $title, 'href' => $adminUrl . $adminId . "&tab={$id}"));
		}
	}


	//  Config pages
	if(@$showAdmin['website_settings'] != 'disabled'){
		foreach($aitThemeConfig as $key => $page){

			$wpAdminBar->add_node(array(
				'id' => $rootId . "-{$key}",
				'parent' => $rootId,
				'title' => esc_html($page['menu-title']),
				'href' => $adminUrl . $adminId . "-{$key}",
			));

			if(isset($page['tabs'])){
				foreach($page['tabs'] as $tabKey => $tab){
					$wpAdminBar->add_node(array(
						'id' => $rootId . "-{$tabKey}",
						'parent' => $rootId . "-{$key}",
						'title' => esc_html($tab['tab-title']),
						'href' => $adminUrl . $adminId . "-{$key}&amp;tab=" . $tabKey,
					));
				}
			}
		}
	}


	// Branding, Skins, Backup
	foreach($pages as $id => $title){
		$wpAdminBar->add_node(array(
			'id' => "{$rootId}-{$id}",
			'parent' => $rootId,
			'title' => $title,
			'href' => $adminUrl . $adminId . "-$id"
		));
	}


	// Updates
	$updates = aitGetAitUpdatesData();
	if($updates['counts']['total'] != 0){
		$title = '<span class="ab-icon"></span><span class="ab-label">' . number_format_i18n($updates['counts']['total']) . '</span>';
		$wpAdminBar->add_node(array(
			'id' => "{$rootId}-updates",
			'parent' => false,
			'title' => $title,
			'href' => $adminUrl . $adminId,
			'meta' => array(
				'title' => $updates['title'],
				'class' => 'ait-ab-updates',
			),
		));
	}
}



function aitAddDevBarMenu($wpAdminBar)
{
	$wpAdminBar->add_node(array(
		'id' => 'ait-dev-mode',
		'title'  => 'Ait Dev',
		'parent' => 'top-secondary',
		'href' => '#',
		'meta' => array('class' => 'ait-dev-mode'),
	));

	if(file_exists(ABSPATH . '/wp-content/debug.log')){

		$wpAdminBar->add_node(array(
			'id' => 'ait-debuglog',
			'title'  => 'View debug.log',
			'parent' => 'ait-dev-mode', // Off on the right side
			'href' => site_url() . '/wp-content/debug.log',
			'meta' => array(
				'target' => '_blank',
			),
		));
	}
}



function addAitToAdminBar($position = 31)
{
	add_action('admin_bar_menu', 'aitAddAdminBarMenu', $position);
	if(defined('AIT_DEVELOPMENT') and AIT_DEVELOPMENT)
		add_action('admin_bar_menu', 'aitAddDevBarMenu', 1986);
	add_action('admin_head', 'aitAdminBarCss');
	add_action('wp_head', 'aitAdminBarCss');
}



function aitAdminBarCss()
{
	global $aitBrandingOptions;

	$icon = (string) @$aitBrandingOptions->branding->adminMenuIcon;

	if(startsWith('design', $icon))
		$icon = THEME_URL . '/' . $aitBrandingOptions->branding->adminMenuIcon;

	$path = home_url('/');

	echo <<<out
	<style>
		#wpadminbar #wp-admin-bar-ait-admin >.ab-item { background-image: url('{$icon}');background-repeat: no-repeat;background-position: 0.85em 50%;padding-left: 32px;}
		#wpadminbar .ait-ab-updates .ab-icon{background-image: url({$path}wp-includes/images/admin-bar-sprite.png?d=20111130);background-position: -2px -159px;background-repeat: no-repeat;}
		#wpadminbar .ait-ab-updates .ab-label{background-color: #F03D25;border-radius: 2px;border-bottom: 1px solid #C0311E;border-left: 1px solid #D83722;border-right: 1px solid #D83722;border-top: 1px solid #E23923;box-shadow: rgba(0, 39, 121, 0.75) 0px 1px 0px 0px;color: white;font-weight: bold;padding:0 3px;}
	</style>
out;
}
