<?php

/**
 * AIT WordPress Framework
 *
 * Copyright (c) 2011, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */


define('AIT_ADMIN_POSITION', 29); // position index of separator, 25 = Comments, 26 - 59 = free, 60 = second separator

function aitAdminStylesAndScripts()
{
	wp_enqueue_style('jquery-ui-datepicker', AIT_ADMIN_URL . '/gui/jquery-ui-datepicker.css');

	wp_enqueue_style('CSS_admin', AIT_ADMIN_URL . '/gui/ait-admin.css' );
	wp_enqueue_style('CSS_colorpicker', AIT_ADMIN_URL . '/gui/jquery.colorpicker.css' );
	wp_enqueue_style('CSS_colorbox', AIT_ADMIN_URL . '/gui/colorbox.css' );

	if(is_file(admin_url() . 'css/dashboard.css')){
		wp_admin_css('dashboard');
	}

	wp_enqueue_script('JS_admin', AIT_ADMIN_URL . '/gui/admin.js', array('jquery') );
	wp_enqueue_script('JS_colorpicker', AIT_ADMIN_URL . '/gui/jquery.colorpicker.js', array('jquery') );
	wp_enqueue_script('JS_colorbox', AIT_ADMIN_URL . '/gui/jquery.colorbox-min.js', array('jquery') );


	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_script('jquery-ui-datepicker');


	add_thickbox();
	wp_enqueue_script('media-upload');

	wp_enqueue_script('my-upload');

}
add_action('admin_enqueue_scripts', 'aitAdminStylesAndScripts');


// Activation of theme
add_action('load-themes.php', 'aitActivateTheme');

add_action("admin_init", "aitAdminInit");
add_action('admin_menu', '_maybeUpdateAitNews');
add_action('admin_menu', '_maybeUpdateThemeVersions');
add_action('admin_menu', 'aitSetupAdminMenu');
add_action('aitGenerateAdminMenu', 'aitGenerateAdminMenu', 10, 2);
add_action('aitAddAdminMenuSeparator', 'aitAddAdminMenuSeparator');
add_action('admin_notices', 'aitAdminNotices');


// ajax hooks
add_action('wp_ajax_markNewsAsRead', 'ajaxMarkNewsAsRead');
add_action('wp_ajax_markAllNewsAsRead', 'ajaxMarkAllNewsAsRead');
add_action('wp_ajax_disableThemeUpdates', 'ajaxDisableThemeUpdates');


$aitBrandingConfig = loadConfig(dirname(__FILE__) . '/conf/admin-branding.neon');


/**
 * AIT Admin initalisation, mostly logic needed before rendering html
 * @return void
 */
function aitAdminInit()
{
	global $aitThemeConfig, $aitBrandingConfig;

	if(isset($_POST['resetDocs']) and isset($_SERVER['HTTP_X_REQUESTED_WITH']) and $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest'){

		$transient = 'doc_' . $_POST['url'];
		delete_transient($transient);
	}

	if(isset($_POST['resetOptions']) and isset($_SERVER['HTTP_X_REQUESTED_WITH']) and $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest'){

		if(isset($_POST['key'])){
			$key = base64_decode($_POST['key']);
			if($key == AIT_OPTIONS_KEY){

				$c = aitGetThemeDefaultOptions($aitThemeConfig);
				update_option(AIT_OPTIONS_KEY, $c);

				delete_option('ait_current_skin_' . THEME_CODE_NAME);

				aitSaveCss(true);

			}elseif($key == AIT_BRANDING_OPTIONS_KEY){
				$c = aitGetThemeDefaultOptions($aitBrandingConfig);
				update_option(AIT_BRANDING_OPTIONS_KEY, $c);
			}
		}
		exit;
	}

	if(isset($_POST['resetOrder']) and isset($_SERVER['HTTP_X_REQUESTED_WITH']) and $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest'){

		if(isset($_POST['orderKey'])){

			// update globals ait options
			$dbOptions = get_option(AIT_OPTIONS_KEY);

			$defOptions = aitGetThemeDefaultOptions($aitThemeConfig);

			$newOptions = $dbOptions;

			foreach($newOptions as $key => $value){
				foreach($newOptions[$key] as $childKey => $childValue){
					if($childKey == $_POST['orderKey']){
						$newOptions[$key][$childKey] = $defOptions[$key][$childKey];
					}
				}
			}
			update_option(AIT_OPTIONS_KEY,$newOptions);
		}

		if(isset($_POST['metaKey'])){
			// remove all metadata with key orderKey from posts
			global $wpdb;
			$wpdb->query('DELETE FROM '.$wpdb->prefix.'postmeta WHERE meta_key LIKE "'.$_POST['metaKey'].'"');
		}

		exit;
	}

	if(isset($_GET['page']) and substr($_GET['page'], 0, 9) == 'ait-admin' and isset($_GET['settings-updated']) and $_GET['settings-updated'] == 'true'){

		aitSaveCss(true);
		exit;
	}

	aitBackup();

	aitDownloadSkin();

	aitActivateSkin();

	register_setting(AIT_OPTIONS_KEY, AIT_OPTIONS_KEY, 'aitValidateOptions');
	register_setting(AIT_BRANDING_OPTIONS_KEY, AIT_BRANDING_OPTIONS_KEY, 'aitValidateAdminBrandingOptions');

	add_filter('admin_footer_text', create_function('', 'global $aitBrandingOptions; echo @$aitBrandingOptions->branding->adminFooterText;'));
}



function aitSaveCss($errorMessages = false)
{
	$written = aitSaveLess2Css();
	if($errorMessages)
		aitCssErrorMessages($written);
}



/**
 * Displays error messages for saving LESS to CSS
 * @param bool $written
 * @return void
 */
function aitCssErrorMessages($written, $less = true)
{
	if($written){
		?>
		<div id="setting-error-settings_updated" class="updated settings-error">
			<p><strong><?php _e('Settings saved.', THEME_CODE_NAME); ?></strong></p>
		</div>
		<?php
		exit;
	}

	if(!$written and $less){
		?>
		<div id="setting-error-settings_updated" class="error settings-error">
			<p><strong><?php _e("Settings was saved to database but new <code>" . THEME_STYLESHEET_FILE . "</code> file with new settings was not generated because the file is not writeable. Please make it writeable (chmod 0777). <br>See the FAQ article <a href='" . get_admin_url(null, 'admin.php?page=ait-admin&tab=faq&qa=how-to-change-write-permissions') . "'>How to change write permissions</a>."); ?></strong></p>
		</div>
		<?php
		exit;
	}elseif(!$written and !$less){
		?>
		<div id="setting-error-settings_updated" class="error settings-error">
			<p><strong><?php _e("Settings was saved to database but CSS file with new settings was not generated because <code>ait-cache</code> directory is not writeable. Please make it writeable (chmod 0777). <br>See the FAQ article <a href='" . get_admin_url(null, 'admin.php?page=ait-admin&tab=faq&qa=how-to-change-write-permissions') . "'>How to change write permissions</a>."); ?></strong></p>
		</div>
		<?php
	}
}



/**
 * Displays error messages if cache dir and css file is not writeable
 * @return void
 */
function aitAdminNotices()
{
	$div = '<div class="%s"><p>%s</p></div>';
	$return = '';

	if(!is_dir(AIT_CACHE_DIR) or !is_writable(AIT_CACHE_DIR)){
		$path = dirname(AIT_CACHE_DIR);
		$dir = basename(AIT_CACHE_DIR);
		$return .= sprintf($div, 'error', "<strong>Cache directory <code>$path/<big>$dir</big></code> doesn't exists or isn't writeable. Create this directory if doesn't exists and make it writeable (chmod 0777).</strong>. <br>See the FAQ article <a href='" . get_admin_url(null, 'admin.php?page=ait-admin&tab=faq&qa=how-to-change-write-permissions') . "'>How to change write permissions</a>.");
	}

	if(!is_writable(THEME_STYLESHEET_FILE)){
		$return .= sprintf($div, 'error', "<strong>Stylesheet file  <code>" . THEME_STYLESHEET_FILE . "</code> is not writeable. Make it writeable please. (chmod 0777).</strong> <br>See the FAQ article <a href='" . get_admin_url(null, 'admin.php?page=ait-admin&tab=faq&qa=how-to-change-write-permissions') . "'>How to change write permissions</a>.");
	}

	echo $return;
}



/**
 * Helper action hook
 */
function aitSetupAdminMenu()
{
	global $aitThemeConfig, $aitBrandingOptions;

	do_action('aitGenerateAdminMenu', $aitThemeConfig, $aitBrandingOptions);
	do_action('aitAddAdminMenuSeparator');
}



/**
 * Generates AIT Admin menu
 * @param array $config Content of parsed config file
 */
function aitGenerateAdminMenu($config, $brandingOptions)
{
	global $aitDisableBranding, $showAdmin;

	$branding = @$brandingOptions->branding;
	$updateData = aitGetAitUpdatesData();

	$c = array_slice($config, 0, 1);
	$_keys = array_keys($c);
	$key = reset($_keys);
	$_values = array_values($c);
	$page = reset($_values);
	unset($c);
	$wkey = $key;

	if(current_user_can('manage_options') && @$showAdmin['dashboard'] != 'disabled'){
		$adminTitle = THEME_SHORT_NAME . ' ' . __('Options', THEME_CODE_NAME);
		$title = 'AIT Themes';
		$slug = 'ait-admin';
		$key = 'ait-admin';
	}else{
		if(@$showAdmin['backup'] != 'disabled'){
			$adminTitle = $branding->adminTitle;
			$title = $adminTitle;
			$slug = 'ait-admin-backup';
			$key = 'backup';
		}
		if(@$showAdmin['skins'] != 'disabled'){
			$adminTitle = $branding->adminTitle;
			$title = $adminTitle;
			$slug = 'ait-admin-skins';
			$key = 'skins';
		}
		if(!@$aitDisableBranding){
			if(@$showAdmin['branding'] != 'disabled'){
				$adminTitle = $branding->adminTitle;
				$title = $adminTitle;
				$slug = 'ait-admin-branding';
				$key = 'branding';
			}
		}
		if(@$showAdmin['website_settings'] != 'disabled'){
			$adminTitle = $branding->adminTitle;
			$title = $adminTitle;
			$slug = 'ait-admin-' . $wkey;
			$key = $wkey;
		}
	}

	add_filter('option_page_capability_' . AIT_OPTIONS_KEY , create_function('', "return 'unfiltered_html';"), 1);

	if ($slug) {
		add_menu_page(
			$title,
			sprintf( __('AIT Dashboard %s', THEME_CODE_NAME), "<span class='update-plugins count-{$updateData['counts']['total']}' title='{$updateData['title']}'><span class='update-count'>" . number_format_i18n($updateData['counts']['total']) . "</span></span>" ),
			'unfiltered_html',
			$slug,
			create_function('', 'aitAdmin("' . $key . '", "' . $adminTitle . '");'),
			THEME_URL . '/' . @$branding->adminMenuIcon,
			AIT_ADMIN_POSITION + 1
		);
	}

	if(@$showAdmin['dashboard'] != 'disabled'){
		if(current_user_can('manage_options') && @$showAdmin['dashboard'] != 'disabled'){
			add_submenu_page(
				'ait-admin',
				THEME_LONG_NAME . ' Dashboard',
				'AIT Dashboard',
				'manage_options',
				'ait-admin'
			);
		}else{
			add_submenu_page(
				$slug,
				THEME_LONG_NAME . ' ' . esc_html($page['title']),
				esc_html($page['menu-title']),
				'unfiltered_html',
				$slug
			);
			array_shift($config);
		}
	}

	if(isset($showAdmin['website_settings']) == false){
		$showAdmin['website_settings'] = "enabled";
	}

	if(@$showAdmin['website_settings'] != 'disabled'){

		foreach($config as $keyF => $page){
			if($keyF != $key){
				add_submenu_page(
					$slug,
					THEME_LONG_NAME . ' ' . esc_html($page['title']),
					esc_html($page['menu-title']),
					'unfiltered_html',
					'ait-admin-' . $keyF,
					create_function('', 'aitAdmin("' . $keyF . '", "' . $adminTitle . '");')
				);
			} else {
				add_submenu_page(
					$slug,
					THEME_LONG_NAME . ' ' . esc_html($page['title']),
					esc_html($page['menu-title']),
					'unfiltered_html',
					'ait-admin-' . $keyF
				);
			}
		}

	}

	if(isset($aitDisableBranding) == false){
		$aitDisableBranding = false;
	}

	if(!@$aitDisableBranding){
		if(isset($showAdmin['branding']) == false){
			$showAdmin['branding'] = "enabled";
		}

		if(@$showAdmin['branding'] != 'disabled'){
			if($slug != "ait-admin-branding"){
				add_submenu_page(
					$slug,
					THEME_LONG_NAME . ' ' . __('Admin Branding', THEME_CODE_NAME),
					__('Admin Branding', THEME_CODE_NAME),
					'manage_options',
					'ait-admin-branding',
					create_function('', 'aitAdmin("branding", "' . __('Admin Branding', THEME_CODE_NAME) . '");')
				);
			} else {
				add_submenu_page(
					$slug,
					THEME_LONG_NAME . ' ' . __('Admin Branding', THEME_CODE_NAME),
					__('Admin Branding', THEME_CODE_NAME),
					'manage_options',
					'ait-admin-branding'
				);
			}
		}
	}

	if(isset($showAdmin['skins']) == false){
		$showAdmin['skins'] = "enabled";
	}

	if(@$showAdmin['skins'] != 'disabled'){
		if($slug != "ait-admin-skins"){
			add_submenu_page(
				$slug,
				THEME_LONG_NAME . ' ' . __('Skins', THEME_CODE_NAME),
				__('Skins', THEME_CODE_NAME),
				'manage_options',
				'ait-admin-skins',
				create_function('', 'aitAdmin("skins", "' . sprintf(__('Skins for %s', THEME_CODE_NAME), THEME_SHORT_NAME) . '");')
			);
		} else {
			add_submenu_page(
				$slug,
				THEME_LONG_NAME . ' ' . __('Skins', THEME_CODE_NAME),
				__('Skins', THEME_CODE_NAME),
				'manage_options',
				'ait-admin-skins'
			);
		}
	}

	if(isset($showAdmin['backup']) == false){
		$showAdmin['backup'] = "enabled";
	}

	if(@$showAdmin['backup'] != 'disabled'){
		if($slug != "ait-admin-backup"){
			add_submenu_page(
				$slug,
				THEME_LONG_NAME . ' ' . __('Backup', THEME_CODE_NAME),
				__('Backup', THEME_CODE_NAME),
				'manage_options',
				'ait-admin-backup',
				create_function('', 'aitAdmin("backup", "' . __('Backup', THEME_CODE_NAME) . '");')
			);
		} else {
			add_submenu_page(
				$slug,
				THEME_LONG_NAME . ' ' . __('Backup', THEME_CODE_NAME),
				__('Backup', THEME_CODE_NAME),
				'manage_options',
				'ait-admin-backup'
			);
		}
	}
}



/**
 * Generates content of AIT Admin pages
 * @param string $key Key of section from config
 * @param string $adminTitle Title for current AIT Admin page
 */
function aitAdmin($key, $adminTitle)
{
	global $aitThemeConfig, $aitBrandingOptions, $aitBrandingConfig;

	$branding = @$aitBrandingOptions->branding;

	if($key == 'ait-admin'){
		require_once dirname(__FILE__) . '/dashboard/ait-dashboard-functions.php';
		require_once dirname(__FILE__) . '/dashboard/ait-dashboard.php';
	}else{
	?>

	<div class="wrap">
		<div id="icon-ait" class="icon32">
			<img src="<?php echo THEME_URL . '/' . @$branding->adminTitleIcon;?>" width="32" height="32">
		</div>
	<?php
	if(isset($aitThemeConfig[$key])):
		$page = $aitThemeConfig[$key];

		if(isset($page['tabs'])): ?>
			<h2 class="nav-tab-wrapper">
			<?php echo $adminTitle; ?>
			<?php echo aitAdminTabs($key, $page['tabs']); ?>
			</h2>
		<?php else: ?>
			<h2><?php echo $adminTitle; ?> <?php echo $page['title'] ?></h2>
		<?php endif; ?>

		<?php aitRenderThemeOptionsForm(AIT_OPTIONS_KEY, $key, $page); ?>
	<?php else:

		if($key == 'branding'){
			?><h2><?php echo $adminTitle; ?></h2><?php
			aitRenderThemeOptionsForm(AIT_BRANDING_OPTIONS_KEY, $key, $aitBrandingConfig[$key]);
		}

		if($key == 'backup'){
			?><h2><?php echo $adminTitle; ?></h2><?php
			aitRenderBackupPage();
		}

		if($key == 'skins'){ ?>
			<h2 class="nav-tab-wrapper">
			<?php echo aitAdminTabs($key, array(
				'skins' => array('tab-title' => $adminTitle),
				'upload-skin' => array('tab-title' => 'Upload a skin'),
				'create-new-skin' => array('tab-title' => 'Create new skin'),
			)); ?>
			</h2>
			<?php
			$tab = isset($_GET['tab']) ? $_GET['tab'] : '';
			aitRenderSkinsPage($key, $tab, $adminTitle);
		}
		?>
	<?php endif; ?>
	</div><!-- /.wrap -->
	<?php
	}
}



/**
 * Generates tabs in AIT Admin title
 * @param string $key Key of section from config
 * @param array $tabs Tabs from config
 * @return string HTML of tabs
 */
function aitAdminTabs($key, $tabs)
{

	if(!isset($_GET['tab'])) {
		$current = '';
	} else {
		// !!!!! HOSTING FIX
		if($_GET['tab'] == "ait-layout"){
			$current = "globals";
		} else {
			$current = $_GET['tab'];
		}
	}

	$links = '';
	$i = 0;

	foreach($tabs as $tabKey => $tab){

		if($i != 0){
			// !!!! HOSTING FIX
			if($tabKey == "globals"){
				$tab_slug = '&amp;tab=ait-layout';
			} else {
				$tab_slug = '&amp;tab=' . $tabKey . "";
			}
		} else {
			$tab_slug = '';
		}

		if($tabKey == $current){
			$active = ' nav-tab-active';
		}else{
			($current == '' and $i == 0) ? $active = ' nav-tab-active' : $active = ''; // activate first item
		}

		$links .= '<a class="nav-tab' . $active .'" href="' . admin_url('admin.php?page=ait-admin-') . $key . $tab_slug .'">' . esc_html($tab['tab-title']) . '</a>';

		$i++;
	}

	return $links;
}



/**
 * Helper function, adds separator before AIT Admin menu
 */
function aitAddAdminMenuSeparator()
{
	global $menu;

	if (is_admin()) {
		$index = 0;
		foreach($menu as $offset => $section) {
			if (substr($section[2], 0, 9) == 'separator')
				$index++;

			if ($offset >= AIT_ADMIN_POSITION) {
				$menu[AIT_ADMIN_POSITION] = array('', 'read', "separator{$index}", '', 'wp-menu-separator');
				break;
			}
		}
	}
}



/**
 * Theme activation hook function
 */
function aitActivateTheme()
{
	global $pagenow, $aitThemeConfig;

	if($pagenow == 'themes.php' && isset($_GET['activated'])){
		// try to change write permissions
		@chmod(AIT_CACHE_DIR, 0777);
		@chmod(THEME_STYLESHEET_FILE, 0777);
		@touch(THEME_STYLESHEET_FILE, time() - 30);

		do_action('aitThemeActivation');

		add_option(AIT_OPTIONS_KEY, aitGetThemeDefaultOptions($aitThemeConfig));

		aitSaveCss();

		$brandingConfig = dirname(__FILE__) . '/conf/admin-branding.neon';
		if(file_exists($brandingConfig)){
			add_option(AIT_BRANDING_OPTIONS_KEY, aitGetThemeDefaultOptions(loadConfig($brandingConfig)));
		}
	}
}



/**
 * Validates user input in theme options form
 * @todo
 * @param type $input
 * @return type
 */
function aitValidateOptions($input)
{
	return $input;
}



/**
 * Validates user input in theme options form
 * @todo
 * @param type $input
 * @return type
 */
function aitValidateAdminBrandingOptions($input)
{
	return $input;
}



/**
 * Renders Backup page
 */
function aitRenderBackupPage()
{
?>
	<?php
	if(isset($_GET['imported'])){
		echo '<div class="updated"><p>Options was successfully imported.</p></div>';
	}

	if(isset($_GET['import-error'])){
		echo '<div class="error"><p>There was a problem importing your options. Please Try again.</p></div>';
	}

	if(isset($_GET['invalid-theme'])){
		echo '<div class="error"><p>You can\'t import options from <code>' . esc_html($_GET['theme']) . '</code> theme to <code>' . THEME_CODE_NAME . '</code> theme.</p></div>';
	}

	if(isset($_GET['invalid-import'])){
		echo '<div class="error"><p>There was a problem importing your options. Content of your backup file is invalid.</p></div>';
	}

	echo '<div><p style="color:green;font-style:italic;">' . sprintf(__('Please note that this backup manager backs up only your Theme Options and Admin Branding Options and not your content. To backup your content, please use the %sWordPress Export Tool%s.', THEME_CODE_NAME), '<a href="' . admin_url( 'export.php' ) . '">', '</a>' ) . '</p></div>'; ?>

		<div id="dashboard-widgets-wrap">
		<div class="metabox-holder">
			<div class="postbox-container" style="width:48%;margin-right:15px;">
				<div  class="meta-box-sortables">
					<div id="ait-theme-doc-sidebar" class="postbox">
						<h3 style="cursor:default;"><span><?php _e('Backup Theme Options', THEME_CODE_NAME) ?></span></h3>
						<div class="inside">
							<h4><?php _e('Export Theme Options', THEME_CODE_NAME) ?></h4>
								<?php _e('<p>Downloaded will be all theme options.</p> <p>And in case you are using WPML plugin, then will be downloaded theme options for all languages you are using.</p>', THEME_CODE_NAME)?>

								<form method="post">
									<p>
										<input type="hidden" name="export-what" value="theme-options">
										<input type="submit" name="export" value="<?php _e('Download backup file', THEME_CODE_NAME) ?>" class="button-primary">
									</p>
								</form>

							<h4><?php _e('Import Theme Options', THEME_CODE_NAME) ?></h4>
								<?php _e('<p>Please be aware that this import will overwrite your current theme options.</p>', THEME_CODE_NAME)?>
								<form method="post" enctype="multipart/form-data">
									<p><label>Your backup file:<br><input type="file" name="import-file"></label></p>
									<p><input type="submit" name="import" value="<?php _e('Upload backup file and Import options', THEME_CODE_NAME) ?>" class="button-primary"></p>
								</form>

						</div>
					</div>
				</div>
			</div>
			<div class="postbox-container" style="width:49%;">
				<div  class="meta-box-sortables">
					<div id="ait-theme-doc-sidebar" class="postbox">
						<h3><span><?php _e('Backup Admin Branding Options', THEME_CODE_NAME) ?></span></h3>
						<div class="inside">
							<h4><?php _e('Export Admin Branding Options', THEME_CODE_NAME) ?></h4>
								<?php _e('<p>Downloaded will be all theme options.</p> <p>And in case you are using WPML plugin, then will be downloaded theme options for all languages you are using.</p>', THEME_CODE_NAME)?>
								<form method="post">
									<input type="hidden" name="export-what" value="branding-options">
									<p><input type="submit" name="export" value="<?php _e('Download backup file', THEME_CODE_NAME) ?>" class="button-primary"></p>
								</form>

							<h4><?php _e('Import Admin Branding Options', THEME_CODE_NAME) ?></h4>
								<?php _e('<p>Please be aware that this import will overwrite your current theme options.</p>', THEME_CODE_NAME)?>
								<form method="post" enctype="multipart/form-data">
									<p><label>Your backup file:<br><input type="file" name="import-file"></label></p>
									<p><input type="submit" name="import" value="<?php _e('Upload backup file and Import options', THEME_CODE_NAME) ?>" class="button-primary"></p>
								</form>

						</div>
					</div>
				</div>
			</div>
		</div> <!-- /#dashboard-widgets -->
		<div class="clear"></div>
	</div>
<?php
}



/**
 * Does backup of theme options and branding options
 */
function aitBackup()
{
	$key = '';
	$options = array();
	$langs = array('en');

	// Export
	if(isset($_POST['export'])){
		if(defined('ICL_LANGUAGE_CODE')){
			$langs = array_keys(icl_get_languages('KEY=code'));
		}
		foreach($langs as $lang){
			if(isset($_POST['export-what']) and $_POST['export-what'] == 'theme-options'){
				$filename = substr(AIT_DEFAULT_OPTIONS_KEY, 0, -2);
			}elseif(isset($_POST['export-what']) and $_POST['export-what'] == 'branding-options'){
				$filename = substr(AIT_BRANDING_OPTIONS_KEY, 0, -2);
			}

			$key = $filename . $lang;
			$data = get_option($key);
			if($data !== false){
				$options[$lang]['data'] = $data;
				$options[$lang]['key'] = $key;
			}
		}

		$options['theme'] = THEME_CODE_NAME;

	    $output = serialize($options);
	    header('Content-Description: File Transfer');
	    header('Cache-Control: public, must-revalidate');
	    header('Pragma: hack');
	    header('Content-Type: text/plain');
	    header('Content-Disposition: attachment; filename="' . $filename .'backup-' . date( 'Y-m-d-H.i.s' ) . '.ait"');
	    header('Content-Length: ' . strlen( $output ));
	    echo $output;
	    exit;
	}


	// Import
	if(isset($_POST['import'])){
		$content = @file_get_contents($_FILES['import-file']['tmp_name']);
		$theme = '';

		if($content !== false){
			$options = @unserialize($content);
			if($options === false){
				wp_redirect(admin_url('admin.php?page=' . $_GET['page'] . '&invalid-import=true'));exit;
			}

			$theme = $options['theme'];

			if($theme != THEME_CODE_NAME){
				wp_redirect(admin_url('admin.php?page=' . $_GET['page'] . '&invalid-theme=true&theme=' . $theme));exit;
			}

			unset($options['theme']);
		}else{
			wp_redirect(admin_url('admin.php?page=' . $_GET['page'] . '&import-error=true'));exit;
		}

		foreach($options as $lang => $data){
			update_option($data['key'], $data['data']);
		}

		wp_redirect(admin_url('admin.php?page=' . $_GET['page'] . '&imported=true'));exit;
	}
}



/**
 * Renders Skin page
 * @param string $page
 * @param string $tab
 * @param string $tabTitle
 * @return void
 */
function aitRenderSkinsPage($page, $tab, $tabTitle)
{
	aitDeleteSkin();

	if(empty($tab)){


		if(isset($_GET['wrong-theme-skin'])){
			echo '<div class="updated"><p>' . __('This skin is not for this theme.', THEME_CODE_NAME) . '</p></div>';
		}

		if(isset($_GET['activated-skin'])){
			echo '<div class="updated"><p>' . __('Skin was activated.', THEME_CODE_NAME) . '</p></div>';
		}

		if(isset($_GET['activate-skin-error'])){
			echo '<div class="error"><p>' . __('There was a problem with activating your skin. Maybe skin file .ait-skin is missing.', THEME_CODE_NAME) . '</p></div>';
		}

		if(isset($_GET['invalid-skin'])){
			echo '<div class="error"><p>' . __('Skin file is corrupted. Skin can\'t be activated.', THEME_CODE_NAME) . '</p></div>';
		}


		require_once AIT_ADMIN_DIR . '/libs/AitSkinsListTable.php';

		if(is_dir(THEME_SKINS_DIR)){
			$listSkins = new AitSkinsListTable(THEME_SKINS_DIR, THEME_SKINS_URL, $page);

			$listSkins->prepare_items();

			$currentSkin = get_option('ait_current_skin_' . THEME_CODE_NAME);

			if($currentSkin !== false):
				$listSkins->excludeCurrent($currentSkin['skin']);
				$screenshot = "/$currentSkin[skin]/$currentSkin[skin]-screenshot.png";
		?>

	<h3><?php _e('Current Skin', THEME_CODE_NAME); ?></h3>

	<div id="current-theme">
		<?php if(is_file(THEME_SKINS_DIR . $screenshot)): ?>
		<img src="<?php echo THEME_SKINS_URL . $screenshot; ?>" alt="<?php _e('Current skin preview', THEME_CODE_NAME); ?>">
		<?php endif; ?>
		<h4><?php echo $currentSkin['name'] ?> by <?php echo $currentSkin['author'] ?></h4>
		<p class="theme-description"><?php echo $currentSkin['desc'] ?></p>
	</div> <!-- /#current-theme -->
	<?php endif; ?>

	<br class="clear">

	<?php if($listSkins->has_items()){ ?>
	<h3><?php _e('Available skins in your WordPress', THEME_CODE_NAME); ?></h3>
	<?php
			$listSkins->display();
		}
	?>

	<?php if(!$listSkins->has_items() and $currentSkin === false): ?>
		<p style="padding:1em 0;"><em>
		<?php printf(__('You don\'t have any skins now. You can <a href="%s">create your own</a> or <a href="%s">upload</a> some skins.'), admin_url('admin.php?page=ait-admin-skins&tab=create-new-skin'), admin_url('admin.php?page=ait-admin-skins&tab=upload-skin')); ?>
		</em></p>
	<?php
		endif;
		}else{?>
		<div class="error"><p>
		<?php printf(__('Directory <code>%s</code> is missing. <br><strong>Please create this directory and make it writable</strong>. See the FAQ article <a href="' . get_admin_url(null, 'admin.php?page=ait-admin&tab=faq&qa=how-to-change-write-permissions') . '">How to make directory writable</a>.'), THEME_SKINS_DIR); ?>
		</p></div>
		<?php
		}

	}elseif($tab == 'create-new-skin'){

		$user = wp_get_current_user();
		$defaults = array(
			'name' => '',
			'folder' => '',
			'desc' => '',
			'screenshot' => '',
			'author' => $user->display_name,
		);

		$formFields = aitCreateNewSkin($page, $tab);

		if($formFields === true) return;

		$formFields = array_merge($defaults, (array) $formFields);
	?>
	<h3><?php _e('New Skin', THEME_CODE_NAME); ?></h3>

	<?php if(!isset($formFields['canDownload'])): ?>
	<p><?php _e('New skin will be created from your actual theme settings from AIT Admin. That means all colorpickers, background images and other visual options with their actual values will be exported. So if you want make new skin, just play around with ThemeBox and options in the AIT Admin.', THEME_CODE_NAME); ?></p>
	<?php endif; ?>

	<form method="post">
		<?php wp_nonce_field('ait-create-new-skin'); ?>
		<?php if(!isset($formFields['canDownload'])): ?>
		<table>
			<tr>
				<td><label for="ait-skin-name"><?php _e('Skin Name:', THEME_CODE_NAME); ?></label></td>
				<td><input type="text" name="skin-name" id="ait-skin-name" size="40" value="<?php echo esc_attr($formFields['name']) ?>"></td>
			</tr>
			<tr>
				<td><label for="ait-skin-folder-name"><?php _e('Skin Folder Name:', THEME_CODE_NAME); ?></label></td>
				<td><input type="text" name="skin-folder-name" id="ait-skin-folder-name" size="40" value="<?php echo esc_attr($formFields['folder']) ?>"></td>
			</tr>
			<tr>
				<td><label for="ait-skin-author"><?php _e('Skin Author:', THEME_CODE_NAME); ?></label></td>
				<td><input type="text" name="skin-author" id="ait-skin-author" size="40" value="<?php echo esc_attr($formFields['author']) ?>"></td>
			</tr>
			<tr>
				<td><label for="ait-skin-screenshot"><?php _e('Skin Screenshot:', THEME_CODE_NAME); ?></label></td>
				<td>
					<input type="text" name="skin-screenshot" id="ait-skin-screenshot" size="40" value="<?php echo esc_attr($formFields['screenshot']) ?>">
					<input type="button" name="skin-screenshot" id="ait-skin-screenshot_selectMedia" class="media-select" value="Select Image">
				</td>
			</tr>
			<tr>
				<td><label for="ait-skin-desc"><?php _e('Skin Description:', THEME_CODE_NAME); ?></label></td>
				<td><input type="text" name="skin-desc" id="ait-skin-desc" size="80" value="<?php echo esc_attr($formFields['desc']) ?>"></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="checkbox" name="skin-overwrite" id="ait-skin-overwrite" size="80" value="true"> <label for="ait-skin-overwrite"><?php _e('Overwrite existing skin', THEME_CODE_NAME); ?></label></td>
			</tr>
		</table>
		<p>
			<input type="submit" name="create-new-skin" value="<?php _e('Create New Skin', THEME_CODE_NAME) ?>" class="button-primary">
		<?php else: ?>
		<p>
			<input type="hidden" name="download-new-skin-name" value="<?php echo esc_attr($formFields['folder']); ?>">
			<input type="submit" name="download-new-skin" value="<?php _e('Download', THEME_CODE_NAME) ?>" class="button-primary"> or
			<a href="<?php echo admin_url('admin.php?page=ait-admin-skins'); ?>"><?php echo sprintf(__('Back to <em>%s</em>', THEME_CODE_NAME), $tabTitle); ?></a>
		<?php endif; ?>
		</p>
	</form>
	<?php


	}elseif($tab == 'upload-skin'){
		if(aitUploadSkin() === true) return;
	?>
		<h3><?php _e('Upload a skin in .zip format', THEME_CODE_NAME) ?></h3>
		<form method="post" enctype="multipart/form-data">
			<?php wp_nonce_field('ait-upload-skin-zip'); ?>
			<p><strong><em><?php _e('Existing skin with same name will be overwritten!', THEME_CODE_NAME) ?></em></strong></p>
			<p><input type="file" name="skin-file"></p>
			<p><input type="submit" name="upload-skin" value="<?php _e('Upload skin', THEME_CODE_NAME) ?>" class="button-primary"></p>
		</form>
	<?php
	}
}



/**
 * Creates new skin
 * @global WP_Filesystem $wp_filesystem
 * @param string $page
 * @param string $tab
 * @return boolean|string
 */
function aitCreateNewSkin($page, $tab)
{
	global $aitThemeConfig;

	if(empty($_POST))
		return false;

	check_admin_referer('ait-create-new-skin');

	$passFields = array('create-new-skin', 'skin-name', 'skin-folder-name', 'skin-screenshot', 'skin-desc', 'skin-author', 'skin-overwrite');

	if(isset($_POST['create-new-skin'])){

		$method = '';

		$url = wp_nonce_url(admin_url('admin.php?page=ait-admin-skins&tab=create-new-skin'), 'ait-create-new-skin');

		if(($creds = request_filesystem_credentials($url, $method, false, false, $passFields)) === false){
			return true;
		}

		if(!WP_Filesystem($creds)){
			request_filesystem_credentials($url, $method, true, false, $passFields);
			return true;
		}

		global $wp_filesystem;

		$name = strip_tags($_POST['skin-name']);
		$folderName = webalize($_POST['skin-folder-name']);
		$desc = strip_tags($_POST['skin-desc']);
		$author = strip_tags($_POST['skin-author']);
		$screenshot = strip_tags($_POST['skin-screenshot']);
		$overwrite = (isset($_POST['skin-overwrite']) and $_POST['skin-overwrite'] == 'true');

		$name = !empty($name) ? $name : THEME_SHORT_NAME . ' Skin ' . date('Y-m-d-H.i');
		$folder = !empty($folderName) ? $folderName : webalize($name);

		$skinDir = substr(THEME_SKINS_DIR, strlen(THEME_DIR) + 1) . "/$folder";
		$skinUrl = substr(THEME_SKINS_URL, strlen(THEME_URL) + 1) . "/$folder";

		$screenshotDest = '';
		$screenshotSrc = '';

		if(!empty($screenshot)){
			$rawSrc = THEME_DIR . "/" . $screenshot;
			$src = THEME_DIR . "/" . substr($screenshot, strlen(THEME_URL) + 1);
			$screenshotDest = THEME_DIR . "/$skinDir/$folder-screenshot.png";
			if(is_file($rawSrc)){
				$screenshotSrc = $rawSrc;
			}elseif(is_file($src)){ // it is from theme design dir
				$screenshotSrc = $src;
			}else{ // it is from upload dir
				$u = wp_upload_dir();
				$baseUrl = $u['baseurl'];
				$baseDir = $u['basedir'];
				$src = $baseDir . "/" . substr($screenshot, strlen($baseUrl) + 1);
				if(is_file($src)){
					$screenshotSrc = $src;
				}
			}
		}


		$return = array('name' => $name, 'folder' => $folder, 'desc' => $desc, 'author' => $author, 'screenshot' => $screenshot);

		$o = get_option(AIT_OPTIONS_KEY);
		$t = aitGetOptionsTypes($aitThemeConfig, true);

		$options = array();

		$redirectTo = admin_url('admin.php?page=' . $page . "&tab={$tab}");

		if(!$overwrite and is_dir(THEME_DIR . "/$skinDir")){
			echo '<div class="error"><p>' . sprintf(__('Skin with folder name <code>%s</code> already exists. Please choose other name.', THEME_CODE_NAME), $folder) . '</p></div>';
			$return['name'] = '';
			$return['author'] = '';
			return $return;
		}

		if(!is_dir(THEME_DIR . "/$skinDir")){
			if(!$wp_filesystem->mkdir(THEME_DIR . "/$skinDir", FS_CHMOD_DIR)){
				echo '<div class="error"><p>' . sprintf(__('Skin folder <code>%s</code> can not be created.', THEME_CODE_NAME), $skinDir) . '</p></div>';
				return $return;
			}
		}

		foreach($t as $section => $vars){
			foreach($vars as $key => $type){
				if(isset($o[$section]) and isset($o[$section][$key])){
					$value = $o[$section][$key];
					if($type == 'image-url' or $type == 'custom-css-vars'){
						if(!empty($value)){
							$v = array();
							if(!is_array($value)){
								$v[] = array('value' => $value); // make array, compatibility with custom-css-vars array of cloned items
							}elseif(is_array($value)){
								$v = $value;
							}

							foreach($v as $val){
								if(isset($val['value']) and !empty($val['value'])){
									if(is_file(THEME_DIR . "/" . ltrim($val['value'], '\\/'))){
										$src = THEME_DIR . "/" . ltrim($val['value'], '\\/');
									}else{
										$src = THEME_DIR . "/" . substr($val['value'], strlen(THEME_URL) + 1);
									}
									if(is_file($src)){ // it is from theme design dir
										$path = "$skinDir/" . basename($val['value']);
										if($type == 'custom-css-vars' and isset($val['variable'])){
											$val['value'] = $path;
											$options[$section][$key][] = $val;
										}else{
											$options[$section][$key] = $path;
										}
										$dest = THEME_DIR . "/$path";
										$wp_filesystem->copy($src, $dest, $overwrite);
									}else{ // it is from upload dir
										$u = wp_upload_dir();
										$baseUrl = $u['baseurl'];
										$baseDir = $u['basedir'];
										$src = $baseDir . "/" . substr($val['value'], strlen($baseUrl) + 1);
										if(is_file($src)){
											$path = "$skinDir/" . basename($val['value']);
											if($type == 'custom-css-vars' and isset($val['variable'])){
												$val['value'] = $path;
												$options[$section][$key][] = $val;
											}else{
												$options[$section][$key] = $path;
											}
											$dest = THEME_DIR . "/$path";
											$wp_filesystem->copy($src, $dest, $overwrite);
										}
									}
								}
							}
						}
					}else{
						$options[$section][$key] = $value;
					}
				}
			}
		}

		if(!empty($screenshotSrc) and !empty($screenshotDest)){
			$wp_filesystem->copy($screenshotSrc, $screenshotDest);
		}

		$neonContent = "name: $name\ndesc: $desc\nauthor: $author\ntheme: " . THEME_CODE_NAME;
		$neonFile = "$skinDir/$folder.neon";

		if(!$wp_filesystem->put_contents(THEME_DIR . "/$neonFile", $neonContent, FS_CHMOD_FILE)){
			echo '<div class="error"><p>' . sprintf(__('Skin config file "%s" can not be created.', THEME_CODE_NAME), $neonFile) . '</p></div>';
			return $return;
		}


		$options['@skinInfo']['theme'] = THEME_CODE_NAME;

		$dump = serialize($options);
		$dumpFile = "$skinDir/$folder.ait-skin";

		if(!$wp_filesystem->put_contents(THEME_DIR . "/$dumpFile", $dump, FS_CHMOD_FILE)){
			echo '<div class="error"><p>' . sprintf(__('Skin data file "%s" can not be created.', THEME_CODE_NAME), $dumpFile) . '</p></div>';
			return $return;
		}

		echo '<div class="updated"><p>' . sprintf(__('Your new skin "%s" has been successfully created. You can download it by clicking on Download button.', THEME_CODE_NAME), $name) . '</p></div>';
		$return['canDownload'] = true;
		return $return;
	}
}



/**
 * Makes zip file and sends it to browser for download
 */
function aitDownloadSkin()
{
	if((isset($_POST['download-new-skin']) and isset($_POST['download-new-skin-name'])) or (isset($_GET['action']) and $_GET['action'] == 'download-skin')){
		require_once(ABSPATH . 'wp-admin/includes/class-pclzip.php');

		$zipName = (isset($_POST['download-new-skin-name'])) ? strip_tags($_POST['download-new-skin-name']) : '';

		if(empty($zipName)){
			$s = urldecode($_GET['skin']);
			check_admin_referer("download-skin-$s"); // dies if not referred from admin page
			$zipName = $s;
		}


		$folder = THEME_SKINS_DIR;

		$zip = AIT_CACHE_DIR . "/$zipName.zip";

		$archive = new PclZip($zip);

		$result = $archive->create("$folder/$zipName", PCLZIP_OPT_REMOVE_PATH, $folder);

		unset($archive);

		$themeName = THEME_CODE_NAME;

		if($result !== 0) {
			if(file_exists($zip)){
				$content = file_get_contents($zip);
				header('Content-Description: File Transfer');
				header('Cache-Control: public, must-revalidate');
				header('Pragma: hack');
				header('Content-Type: application/zip');
				header("Content-Disposition: attachment; filename=\"ait-{$themeName}-skin-{$zipName}.zip\"");
				header('Content-Length: ' . strlen($content));
				echo $content;
			}else{
				echo "error"; exit;
			}
			@unlink($zip);
			exit;
		}else{
			echo sprintf('Creating ZIP <code>%s</code> file failed.', $zip);
			exit;
		}
	}
}



/**
 * Deletes folder with skin
 * @global WP_Filesystem $wp_filesystem
 */
function aitDeleteSkin()
{
	if(isset($_GET['action']) and $_GET['action'] == 'delete-skin'){

		$skin = urldecode($_GET['skin']);
		check_admin_referer("delete-skin-$skin"); // dies if not referred from admin page

		$method = '';
		$url = wp_nonce_url(admin_url('admin.php?page=ait-admin-skins'), 'ait-delete-skin');

		if(($creds = request_filesystem_credentials($url, $method, false, false, false)) === false){
			return;
		}

		if(!WP_Filesystem($creds)){
			request_filesystem_credentials($url, $method, true, false, false);
			return;
		}

		global $wp_filesystem;

		$dir = THEME_SKINS_DIR . "/$skin";

		if(is_dir($dir)){
			if(!$wp_filesystem->rmdir($dir, true)){ // true - recursive
				echo '<div class="error"><p>' . sprintf(__('Can\'t delete skin folder <code>%s</code>. Delete it by yourself.', THEME_CODE_NAME), $skin) . '</p></div>';
			}
		}else{
			echo '<div class="error"><p>' . sprintf(__('Skin folder <code>%s</code> doesn\'t exists.', THEME_CODE_NAME), $skin) . '</p></div>';
		}
	}
}



/**
 * Activates new skin
 */
function aitActivateSkin()
{
	if(isset($_GET['action']) and $_GET['action'] == 'activate-skin'){

		$skin = urldecode($_GET['skin']);
		check_admin_referer("use-skin-$skin"); // dies if not referred from admin page

		$folder = THEME_SKINS_DIR . "/$skin";

		if(is_dir($folder)){
			$data = @file_get_contents("$folder/$skin.ait-skin");
			$config = loadConfig("$folder/$skin.neon");
			$config['skin'] = $skin;

			if($data !== false){
				$data = @unserialize($data);
				if($data === false){
					wp_redirect(admin_url('admin.php?page=' . $_GET['page'] . '&invalid-skin=true'));exit;
				}

				if(isset($theme['@skinInfo'])){ // condition for backcompatibility with older skins
					if(THEME_CODE_NAME != $data['@skinInfo']['theme']){
						wp_redirect(admin_url('admin.php?page=' . $_GET['page'] . '&wrong-theme-skin=true'));exit;
					}
					unset($data['@skinInfo']);
				}

				$options = get_option(AIT_OPTIONS_KEY);
				$result = array_replace_recursive($options, $data);
				update_option(AIT_OPTIONS_KEY, $result); // for actual language

				update_option('ait_current_skin_' . THEME_CODE_NAME, $config);

				aitSaveCss();

			}else{
				wp_redirect(admin_url('admin.php?page=' . $_GET['page'] . '&activate-skin-error=true'));exit;
			}

			wp_redirect(admin_url('admin.php?page=' . $_GET['page'] . '&activated-skin=true'));exit;
		}
	}
}



/**
 * Uploads and unzip a skin
 * @return boolean
 */
function aitUploadSkin()
{
	if(empty($_POST))
		return false;

	check_admin_referer("ait-upload-skin-zip");

	$passFields = array('upload-skin', 'skin-file');

	if(isset($_POST['upload-skin']) and !empty($_FILES)){
		$method = '';

		$file = $_FILES['skin-file']['tmp_name'];
		$uploadError = $_FILES['skin-file']['error'];

		// function in functio, yeaah
		function errorMessage($code){
			switch ($code) {
				case UPLOAD_ERR_INI_SIZE:
					return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
				case UPLOAD_ERR_FORM_SIZE:
					return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
				case UPLOAD_ERR_PARTIAL:
					return 'The uploaded file was only partially uploaded';
				case UPLOAD_ERR_NO_FILE:
					return 'No file was uploaded';
				case UPLOAD_ERR_NO_TMP_DIR:
					return 'Missing a temporary folder';
				case UPLOAD_ERR_CANT_WRITE:
					return 'Failed to write file to disk';
				case UPLOAD_ERR_EXTENSION:
					return 'File upload stopped by extension';
				default:
					return 'Unknown upload error';
			}
		}

		if($uploadError !== UPLOAD_ERR_OK){
			echo '<div class="error"><p>' . errorMessage($uploadError) . '</p></div>';
			return false;
		}

		$url = wp_nonce_url(admin_url('admin.php?page=ait-admin-skins&tab=create-new-skin'), 'ait-create-new-skin');

		if(($creds = request_filesystem_credentials($url, $method, false, false, $passFields)) === false){
			return true;
		}

		if(!WP_Filesystem($creds)){
			request_filesystem_credentials($url, $method, true, false, $passFields);
			return true;
		}

		require_once(ABSPATH . 'wp-admin/includes/class-pclzip.php');

		$archive = new PclZip($file);

		$files = $archive->listContent();
		$ok = false;

		foreach($files as $f){
			if(isset($f['filename']) and substr(strrchr($f['filename'], '.'), 1) == 'neon'){
				$ok = true;
				break;
			}
		}

		if(!$ok){
			echo '<div class="error"><p>' . __('Uploaded skin is in wrong format. There are missing some important skin\'s files in ZIP file.', THEME_CODE_NAME) . '</p></div>';
			return;
		}

		$dounzip = unzip_file($file, THEME_SKINS_DIR);

		if(is_wp_error($dounzip)){
			$error = $dounzip->get_error_code();
			switch($error){
				case 'incompatible_archive':
					echo '<div class="error"><p>Failed: Incompatible archive</p></div>'; break;
				case 'empty_archive':
					echo '<div class="error"><p>Failed: Empty Archive</p></div>'; break;
				case 'mkdir_failed':
					echo '<div class="error"><p>Failed: mkdir Failure</p></div>'; break;
				case 'copy_failed':
					echo '<div class="error"><p>Failed: Copy Failed</p></div>'; break;
			}
		}else{
			echo '<div class="updated"><p>Skin was successfully uploaded.</p></div>';
		}
	}
}



/**
 * Ajax callback for marking news as read
 */
function ajaxMarkNewsAsRead()
{
	$id = intval($_POST['id']);

	$data = get_site_transient('ait_news_update');
	$unread = $data->news['unread'];

	if(!empty($unread)){
		unset($unread[array_search($id, $unread)]);
		$data->news['unread'] = $unread;
		$return = set_site_transient('ait_news_update', $data);
		echo $return ? 'changed' : 'unchanged';
		exit;
	}
	echo "unchanged";
	exit;
}



/**
 * Ajax callback for disabling theme update notification
 */
function ajaxMarkAllNewsAsRead()
{
	$data = get_site_transient('ait_news_update');
	$unread = $data->news['unread'];

	if(!empty($unread)){
		$data->news['unread'] = array();
		$return = set_site_transient('ait_news_update', $data);
		echo $return ? 'changed' : 'unchanged';
		exit;
	}
	echo "unchanged";
	exit;
}



/**
 * Ajax callback for disabling theme update notification
 */
function ajaxDisableThemeUpdates()
{
	$disabled = intval($_POST['disabled']);
	$return = update_option('disableAitThemeUpdates', $disabled);

	echo $return ? 'changed' : 'unchanged';
	exit;
}



/**
 * Check for new AIT News.
 *
 * @return mixed Returns null if update is unsupported. Returns false if check is too soon.
 */
function aitUpdateAitNews()
{
	$current = get_site_transient('ait_news_update');

	if (!is_object($current)){
		$current = new stdClass;
		$news = array();
		$unread = array();
	}else{
		$news = $current->news['all'];
		$unread = $current->news['unread'];
	}

	if(is_null($unread))
		$unread = array();

	$newOption = new stdClass;
	$newOption->lastChecked = time();

	// Check for update on a different schedule
	$timeout = (defined('AIT_DEVELOPMENT') and AIT_DEVELOPMENT) ? 5 : (12 * 60 * 60); // 12 hours

	$timeNotChanged = isset($current->lastChecked) && $timeout > (time() - $current->lastChecked);

	if($timeNotChanged){
		$newsChanged = false;
		if(isset($current->news) && isset($current->news['new']) && !empty($current->news['new'])){
			$newsChanged = true;
		}

		// Bail if we've checked recently and if nothing has changed
		if(!$newsChanged)
			return false;
	}

	// Update last_checked for current to prevent multiple blocking requests if request hangs
	$current->lastChecked = time();
	set_site_transient('ait_news_update', $current);

	$options = array(
		'timeout' => ((defined('DOING_CRON') && DOING_CRON) ? 30 : 3),
		'body' => array('aitNews' => true, 'lastDate' => empty($news) ? '' : $news[0]->date),
	);

	$rawResponse = wp_remote_post('http://ait-themes.com/notifications.php', $options);


	if(is_wp_error($rawResponse) || 200 != wp_remote_retrieve_response_code($rawResponse))
		return false;

	$response = maybe_unserialize(wp_remote_retrieve_body($rawResponse));

	if (is_array($response)){
		$newOption->news['unread'] = array_unique(array_merge($unread, $response['new']));
		$newOption->news['new'] = $response['new'];
		$newOption->news['all'] = $response['all'];
	}

	set_site_transient('ait_news_update', $newOption);
}



/**
 * Check plugin versions against the latest versions hosted on WordPress.org.
 *
 * @return mixed Returns null if update is unsupported. Returns false if check is too soon.
 */
function aitUpdateThemeVersions()
{
	$current = get_site_transient('ait_theme_versions_update');
    if (function_exists('wp_get_theme')) {
        $currentVersion = wp_get_theme()->version;
    } else {
        $theme = get_theme_data(get_current_theme());
        $currentVersion = isset($theme['Version']) ? $theme['Version'] : 1.0;
    }

	if (!is_object($current)){
		$current = new stdClass;
	}

	$newOption = new stdClass;
	$newOption->lastChecked = time();

	// Check for update on a different schedule
	$timeout = (defined('AIT_DEVELOPMENT') and AIT_DEVELOPMENT) ? 5 : (12 * 60 * 60); // 12 hours

	$timeNotChanged = isset($current->lastChecked) && $timeout > (time() - $current->lastChecked);

	if($timeNotChanged){
		$versionsChanged = false;
		if(isset($current->latest) && $current->latest != $currentVersion){
			$versionsChanged = true;
		}

		// Bail if we've checked recently and if nothing has changed
		if(!$versionsChanged)
			return false;
	}

	// Update last_checked for current to prevent multiple blocking requests if request hangs
	$current->lastChecked = time();
	set_site_transient('ait_theme_versions_update', $current);

	$options = array(
		'timeout' => ((defined('DOING_CRON') && DOING_CRON) ? 30 : 3),
		'body' => array('themeVersions' => true, 'theme' => THEME_CODE_NAME, 'version' => $currentVersion),
	);

	$rawResponse = wp_remote_post('http://ait-themes.com/notifications.php', $options);


	if(is_wp_error($rawResponse) || 200 != wp_remote_retrieve_response_code($rawResponse))
		return false;

	$response = maybe_unserialize(wp_remote_retrieve_body($rawResponse));


	if (is_object($response)){
		$newOption->updateAvailable = $response->updateAvailable;
		$newOption->latest = $response->latest;
		$newOption->versions = $response->versions;
	}

	set_site_transient('ait_theme_versions_update', $newOption);
}



/**
 * Check AIT Themes News only after a duration of time.
 *
 * @access private
 */
function _maybeUpdateAitNews()
{
	if(isset($aitDisableBranding) == false){
		$aitDisableBranding = false;
	}
	$isDisabled = @$aitDisableBranding == true; // @ - doesn't exist
	if($isDisabled)
		return;

	$current = get_site_transient('ait_news_update');
	$timeout = (defined('AIT_DEVELOPMENT') and AIT_DEVELOPMENT) ? 5 : (12 * 60 * 60);
	if(isset($current->lastChecked) && $timeout > (time() - $current->lastChecked))
		return;

	aitUpdateAitNews();
}



/**
 * Check theme versions only after a duration of time.
 *
 * @access private
 */
function _maybeUpdateThemeVersions()
{
	$current = get_site_transient('ait_theme_versions_update');
	$timeout = (defined('AIT_DEVELOPMENT') and AIT_DEVELOPMENT) ? 5 : (12 * 60 * 60);
	if(isset($current->lastChecked) && $timeout > (time() - $current->lastChecked))
		return;

	aitUpdateThemeVersions();
}