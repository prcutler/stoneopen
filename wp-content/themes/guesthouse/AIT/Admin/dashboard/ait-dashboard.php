<?php

/**
 * AIT WordPress Framework
 *
 * Copyright (c) 2011, Affinity Information Technology, s.r.o. (http://ait-themes.club)
 */
?>



<?php
$dWidgets = array(
	array('about-us', __('About us', THEME_CODE_NAME), 'aitAboutUsWidget'),
	array('ait-theme-updates', __(THEME_SHORT_NAME . ' Theme Updates', THEME_CODE_NAME), 'aitThemeUpdates'),
);
aitAddDashboardWidgets($dWidgets);
?>



<?php function aitAboutUsWidget(){ ?>
	<div class="ait-about">
		<div class="ait-box">

			<div class="ait-logo">
				<div class="ait-wrap">
					<a class="ait" href="http://www.ait-themes.club" target="_blank">AitThemes.club</a>
					<p>tools for your<br /><strong>professional theme</strong><br />administration</p>
				</div>
			</div>

			<div class="ait-links">
				<div class="ait-wrap">
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

	<h2 class="nav-tab-wrapper"><a href="http://www.ait-themes.club" target="_blank" style="text-decoration: none;">AitThemes.club</a>
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

