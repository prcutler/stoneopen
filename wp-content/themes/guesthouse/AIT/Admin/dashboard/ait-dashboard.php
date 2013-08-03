<?php

/**
 * AIT WordPress Framework
 *
 * Copyright (c) 2011, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */
?>



<?php
$dWidgets = array(
	array('about-us', __('About us', THEME_CODE_NAME), 'aitAboutUsWidget'),
);

if(@$GLOBALS['aitDisableBranding'] == true){
	array_push($dWidgets, array('latest-theme', __('Latest / Featured WordPress theme from AitThemes.com', THEME_CODE_NAME), 'aitLatestThemeWidget'));
	array_push($dWidgets, array('themeforest', __('Awesome WordPress themes from AitThemes.com', THEME_CODE_NAME), 'aitThemesWidget'));
	array_push($dWidgets, array('ait-news', __('AitThemes.com News', THEME_CODE_NAME), 'aitNews'));
}

$dWidgets[] = array('ait-theme-updates', __(THEME_SHORT_NAME . ' Theme Updates', THEME_CODE_NAME), 'aitThemeUpdates');

aitAddDashboardWidgets($dWidgets);
?>



<?php function aitAboutUsWidget(){ ?>
	<div class="ait-about">
		<div class="ait-box">

			<div class="ait-logo">
				<div class="ait-wrap">
					<a class="ait" href="http://www.ait-themes.com" target="_blank">ait-themes.com</a>
					<p>tools for your<br /><strong>professional theme</strong><br />administration</p>
				</div>
			</div>

			<div class="ait-links">
				<div class="ait-wrap">
					<a class="ait-button themeforest" href="http://themeforest.net/user/ait/follow" target="_blank">
						<span class="ait-butwrap">
							<span class="title">Themeforest</span>
						</span>
					</a>
					<a class="ait-button facebook" href="http://www.facebook.com/AitThemes" target="_blank">
						<span class="ait-butwrap">
							<span class="title">Facebook</span>
						</span>
					</a>
					<a class="ait-button twitter" href="http://twitter.com/AitThemes" target="_blank">
						<span class="ait-butwrap">
							<span class="title">Twitter</span>
						</span>
					</a>
					<a class="ait-button youtube" href="http://www.youtube.com/user/AitThemes" target="_blank">
						<span class="ait-butwrap">
							<span class="title">YouTube</span>
						</span>
					</a>
					<a class="ait-button google" href="https://plus.google.com/106741986791543596667/posts" target="_blank">
						<span class="ait-butwrap">
							<span class="title">Google Plus</span>
						</span>
					</a>
					<a class="ait-button rss" href="http://feeds.feedburner.com/AitThemes" target="_blank">
						<span class="ait-butwrap">
							<span class="title">RSS</span>
						</span>
					</a>
				</div>
				<p>socialize with us</p>
			</div>

		</div>
	</div>
<?php } ?>



<?php function aitThemesWidget(){
	$url = 'http://www.ait-themes.com/json-export.php?ref=' . urlencode($_SERVER['SERVER_NAME']) . '&t=' . THEME_CODE_NAME . '&from=dashboard';

	$cacheTime = (defined('AIT_DEVELOPMENT') && AIT_DEVELOPMENT) ? 5: (1 * 24 * 60 * 60);

	$themes = aitCachedRemoteRequest('ait-themes', $url, $cacheTime);

	if($themes !== false):
	?>
	<div class="ait-themes">
	<ul class="themes">
	<?php foreach($themes as $theme): ?>
		<?php if($theme->inThemeBox): ?>
		<li>
			<a href="<?php echo $theme->url ?>" target="_blank">
				<img src="<?php echo $theme->thumbnail ?>" class="thumb">
				<img src="<?php echo $theme->preview ?>" class="preview">
			</a>
		</li>
		<?php endif; ?>
	<?php endforeach; ?>
	</ul>
	</div>
	<?php
	endif;
	?>
	<p>You can buy these themes on <a href="http://themeforest.net/user/ait/portfolio">themeforest.net</a>.</p>
<?php } ?>



<?php function aitLatestThemeWidget(){
	$url = 'http://www.ait-themes.com/json-export.php?ref=' . urlencode($_SERVER['SERVER_NAME']) . '&t=' . THEME_CODE_NAME . '&from=dashboard';

	$cacheTime = (defined('AIT_DEVELOPMENT') && AIT_DEVELOPMENT) ? 5: (1 * 24 * 60 * 60);

	$themes = aitCachedRemoteRequest('ait-themes', $url, $cacheTime);

	if($themes !== false and !empty($themes)):
		$latest = reset($themes);
		if($latest->codeName == THEME_CODE_NAME or !$latest->inThemeBox){
			$latest = next($themes);
		}
		if($latest->inThemeBox): ?>
		<div style="text-align:center;">
			<a href="<?php echo $latest->url ?>" target="_blank">
				<img src="<?php echo $latest->preview ?>" style="max-width:100%">
			</a>
		</div>
		<?php endif; ?>

	<?php
	endif;
} ?>



<?php function aitNews() {
	$data = get_site_transient('ait_news_update');
	$news = $unread = array();

	if($data !== false and !empty($data)){
		$news = $data->news['all'];
		$unread = $data->news['unread'];
	}
	?>
	<div class="ait-info">
		<div class="ait-box">
			<div class="ait-wrap">
	<?php
	if(!empty($news)):
		foreach($news as $item):
			$dd = mysql2date('d', $item->date);
			$mm = mysql2date('M', $item->date);
			$yyyy = mysql2date('Y', $item->date);
			$unreadClass = '';
			if(in_array($item->id, $unread) && @$GLOBALS['showAdmin']['ait_news_notifications'] != 'disabled')
				$unreadClass = 'ait-new-news';
			?>
				<div class="ait-button ait-news <?php echo $unreadClass; ?>" id="ait-news-<?php echo esc_attr($item->id); ?>">
					<a href="<?php echo $item->url; ?>" class="ait-butwrap ait-news-permalink" data-ait-news-id="<?php echo esc_attr($item->id); ?>" target="_blank">
						<span class="ait-day"><?php echo $dd; ?></span>
						<span class="ait-month"><?php echo strtoupper($mm); ?></span>
						<span class="ait-year"><?php echo $yyyy; ?></span>
					</a>
				</div>
				<h3 class="ait-news-title"><a href="<?php echo $item->url; ?>" target="_blank" data-ait-news-id="<?php echo esc_attr($item->id); ?>" class="ait-news-permalink"><?php echo $item->title; ?></a></h3>
				<div class="ait-news-content"><?php echo htmlspecialchars_decode($item->content); ?></div>
				<div class="separator"></div>
		<?php
		endforeach;
		?>
		<?php if(!empty($unread)): ?><p class="ait-mark-all-as-read"><a href="#"><?php _e('Mark all news as read', THEME_CODE_NAME)?></a></p><?php endif; ?>
	<?php else:	?>
	<p class="ait-no-updates"><?php _e('There are no AitThemes.com News available.', THEME_CODE_NAME)?></p>
	<?php endif; ?>
			</div>
		</div>
	</div>
 <?php } ?>



<?php function aitThemeUpdates() {

	$opt = get_option('disableAitThemeUpdates');

    if (function_exists('wp_get_theme')) {
        $currentVersion = wp_get_theme()->version;
    } else {
        $theme = get_theme_data(get_current_theme());
        $currentVersion = isset($theme['Version']) ? $theme['Version'] : 1.0;
    }

	$disableUpdatesNotifying = ($opt !== false and $opt);

	$data = get_site_transient('ait_theme_versions_update');

	$versions = array();
	$latest = '';
	$isUpdateAvailable = 0;

	if($data !== false and !empty($data)){
		$versions = $data->versions;
		$latest = $data->latest;
		$isUpdateAvailable = (int) $data->updateAvailable;
	}
	?>
	<div class="ait-info ait-update">
		<div class="ait-box">
			<div class="ait-wrap">

				<p class="ait-current-version-msg"><?php echo sprintf(__('Your current version of <strong>%s</strong> is <strong class="ait-your-theme-version">%s</strong>.</p>', THEME_CODE_NAME), THEME_LONG_NAME, $currentVersion) ?>
	<?php
	if(!empty($versions)):
		$i = 0;
		?><div class="ait-versions-list"><?php
		foreach($versions as $version):
			$i++;
			$dd = mysql2date('d', $version->date);
			$mm = mysql2date('M', $version->date);
			$yyyy = mysql2date('Y', $version->date);
			$class = '';
			$isNew = false;
			$isCurrent = false;
			if(version_compare($version->version, $currentVersion, '==')){
				$class = 'ait-current-version';
				$isCurrent = true;
			}
			if(!$disableUpdatesNotifying && version_compare($version->version, $latest, '==') && version_compare($version->version, $currentVersion, '!=')){
				$class = 'ait-new-version';
				$isNew = true;
			}
			?>
				<div class="ait-button ait-version <?php echo $class; ?>" id="ait-version-<?php echo esc_attr($version->id); ?>">
					<?php if($isNew): ?><a href="<?php echo $version->url;?>" target="_blank"><?php endif; ?>
					<span class="ait-butwrap">
						<span class="ait-day"><?php echo $dd; ?></span>
						<span class="ait-month"><?php echo strtoupper($mm); ?></span>
						<span class="ait-year"><?php echo $yyyy; ?></span>
					</span>
					<?php if($isNew): ?></a><?php endif; ?>
				</div>
				<h3 class="ait-news-title">
					<?php if($isNew): ?><a href="<?php echo $version->url;?>"><?php endif; ?>
					<?php echo THEME_SHORT_NAME; ?> <?php echo $version->title; ?>
					<?php if($isNew): ?></a><?php endif; ?>
				</h3>
				<div class="ait-news-content"><?php echo htmlspecialchars_decode($version->content); ?></div>
				<div class="separator"></div>
		<?php
		endforeach;
		?>
		</div>
		<p><input type="checkbox" <?php echo $disableUpdatesNotifying ? 'checked' : ''; ?> data-ait-is-update-available="<?php echo $isUpdateAvailable; ?>" id="disableUpdatesNotifications" name="disableUpdatesNotifications"> <label for="disableUpdatesNotifications"><?php _e("Disable theme updates notifications.", THEME_CODE_NAME); ?></label></p>
	<?php else:	?>
	<p class="ait-no-updates"><?php _e('There are no updates available.', THEME_CODE_NAME); ?></p>
	<?php endif; ?>
			</div>
		</div>
	</div>
 <?php } ?>




 <?php
// =======================================================
// Render the page
// -------------------------------------------------------
 ?>
<div class="wrap">
	<div id="icon-ait" class="icon32"><img src="<?php echo AIT_ADMIN_URL?>/gui/img/ait-logo.png" width="32" height="32"></div>

	<h2 class="nav-tab-wrapper"><a href="http://ait-themes.com" target="_blank" style="text-decoration: none;">AitThemes.com</a>
		<?php echo aitDashboardTabs(); ?>
	</h2>

	<?php if(aitIsDashboardHome()): ?>

	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">

			<?php aitDashboard() ; ?>

		</div> <!-- /#dashboard-widgets -->
		<div class="clear"></div>
	</div> <!-- /#dashboard-widgets-wrap -->

	<?php else: ?>

	<div id="ait-dashboard-page">
		<?php aitDashboardPages(); ?>
	</div>

	<?php endif; ?>
</div> <!-- /.wrap -->

