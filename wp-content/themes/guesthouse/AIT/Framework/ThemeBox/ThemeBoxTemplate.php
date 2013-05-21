{**
 * AIT WordPress Framework
 *
 * Copyright (c) 2011, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 *}

<?php require_once AIT_FRAMEWORK_DIR . '/ThemeBox/ThemeBox.php'; ?>
<!-- ThemeBox -->
<link rel="stylesheet" type="text/css" media="all" href="{$themeBox->url}/gui/themebox.css">
<!--[if lte IE 8]><script src="{$themeBox->url}/gui/json2.min.js"></script><![endif]-->
<script src="{$themeBox->url}/gui/plugins.js"></script>
<script>
	var aitThemeCodeName = {=THEME_CODE_NAME};
	var aitCufonFontsUrl = {=THEME_FONTS_URL};
	var aitThemeUrl = <?php echo NTemplateHelpers::escapeJs(preg_replace('|https?://[^/]+|i', '', $site->url . '/')); ?>;

	Cookies.cookieName = 'aitThemeBox-' + aitThemeCodeName;
	Cookies.cookiePlugin = jQuery.cookie;
	Cookies.jquery = jQuery;
	Cookies.path = aitThemeUrl;

	{!$themeBox->getSelectors()}
</script>

<div id="ait-themebox">
	<h1 class="visuallyhidden">ThemeBox</h1>
	<div id="ait-themebox-social">
		<a href="http://ait-themes.com" target="_blank"><img src="{$themeBox->url}/gui/themebox/ico_ait.png" width="32" height="32" alt="AIT-Themes icon" title="Visit our site"></a>
		<a href="http://themeforest.net/user/ait/follow" target="_blank"><img src="{$themeBox->url}/gui/themebox/ico_tf.png" width="32" height="32" alt="Themeforest icon" title="Follow us on Themeforest"></a>
		<a href="http://twitter.com/AitThemes" target="_blank"><img src="{$themeBox->url}/gui/themebox/ico_tw.png" width="32" height="32" alt="Twitter icon" title="Follow us on Twitter"></a>
		<a href="http://www.facebook.com/AitThemes" target="_blank"><img src="{$themeBox->url}/gui/themebox/ico_fb.png" width="32" height="32" alt="Facebook icon" title="Like us on Facebook"></a>
		<a href="http://www.youtube.com/user/AitThemes" target="_blank"><img src="{$themeBox->url}/gui/themebox/ico_yt.png" width="32" height="32" alt="YouTube icon" title="Watch us on YouTube"></a>
	</div>

	<div id="ait-themebox-purchase">
	{if strpos($_SERVER['SERVER_NAME'], 'ait-themes.com') !== false and !$site->isUserLoggedIn}
		{if $themeBox->thisTheme === null}
		<a href="http://themeforest.net/user/ait/portfolio" title="Our portfolio" target="_blank">Our Portfolio</a>
		{else}
		<a href="{$themeBox->thisTheme->url}?ref=ait" title="Only ${$themeBox->thisTheme->price}" target="_blank">Purchase NOW</a>
		{/if}
	{else}
		<a href="http://themeforest.net/user/ait/portfolio" title="Our portfolio" target="_blank">Our Portfolio</a>
	{/if}
	</div>
	{if $site->isUserLoggedIn}
	<form id="ait-themebox-form" action="{$themeBox->url}/ThemeBoxAjax.php">
		{if defined('ICL_LANGUAGE_CODE')}
		<input type="hidden" name="ait-themebox[lang]" value="{=ICL_LANGUAGE_CODE}">
		{else}
		<input type="hidden" name="ait-themebox[lang]" value="en">
		{/if}
	{/if}

		<span id="ait-themebox-toggler" class="closed">open/close</span>

		<div id="ait-themebox-options">
		{foreach $themeBox->getOptions() as $type => $options}
			{ifset $themeBox->sections[$type]}
			<div class="ait-themebox-options-header">{$themeBox->sections[$type]}</div>
			<div id="ait-themebox-{$type}" class="ait-themebox-options-content">

			{foreach $options as $option => $value}
				{if $value[displayIt]}
					{if $type == 'colorpicker'}

						<label for="ait-themebox-{$value[section]}-{$option}" class="ait-themebox-label">{$value[label]}</label>
						<input type="text" class="ait-themebox-colorpicker option-{$option}" id="ait-themebox-{$value[section]}-{$option}" data-ait-themebox-option='{ "section": "{$value[section]}", "option": "{$option}" }' name="ait-themebox[{$value[section]}][{$option}]" value="{$value[value]}">

					{elseif $type == 'image-url'}

						<div class="ait-themebox-label">{$value[label]}</div>
						<ul class="ait-themebox-link-list" data-ait-themebox-option='{ "section": "{$value[section]}", "option": "{$option}" }'>
						<?php
							if(isset($themeBox->themeOptions->{$value['section']}->{"{$option}Color"})){
								$__color = $themeBox->themeOptions->{$value['section']}->{"{$option}Color"};
							}else{
								$__color = '';
								}
							?>
						{foreach $value[value] as $i => $bg}
							<li>
								<strong n:tag-if="$themeBox->themeOptions->{$value['section']}->$option == $themeUrl . '/' . $bg['img']">
									<a href="#" data-ait-themebox-img-data='{"img": "{$themeUrl}/{$bg[img]}", "color": "{if isset($bg[color])}{$bg[color]}{else}{$__color}{/if}", "repeat": "{ifset $bg[repeat]}{$bg[repeat]}{else}repeat{/ifset}", "x": "{ifset $bg[x]}{$bg[x]}{else}0{/ifset}", "y": "{ifset $bg[y]}{$bg[y]}{else}0{/ifset}", "attach": "{ifset $bg[attach]}{$bg[attach]}{else}scroll{/ifset}"}'>{$bg[title]}</a>
								</strong>
							</li>
						{/foreach}

							<li>
								<strong n:tag-if="$themeBox->themeOptions->{$value['section']}->$option == $themeUrl . '/' . $value['default']">
									<a href="#" data-ait-themebox-img-data='{"img": "{$themeUrl}/{$value[default]}", "color": "{$__color}", "repeat": "repeat", "x": "0", "y": "0", "attach": "scroll"}'>{_'Default'}</a>
								</strong>


						{if !empty($value[default])}
								<input type="hidden" id="ait-themebox-{$value[section]}-{$option}-img" name="ait-themebox[{$value[section]}][{$option}]" value="{$themeUrl}/{$value[default]}">
						{else}

								<input type="hidden" id="ait-themebox-{$value[section]}-{$option}-img" name="ait-themebox[{$value[section]}][{$option}]"  value="">
						{/if}
								<input type="hidden" id="ait-themebox-{$value[section]}-{$option}-repeat" name="ait-themebox[{$value[section]}][{$option}Repeat]" value="">
								<input type="hidden" id="ait-themebox-{$value[section]}-{$option}-x" name="ait-themebox[{$value[section]}][{$option}X]" value="">
								<input type="hidden" id="ait-themebox-{$value[section]}-{$option}-y" name="ait-themebox[{$value[section]}][{$option}Y]" value="">
								<input type="hidden" id="ait-themebox-{$value[section]}-{$option}-attach" name="ait-themebox[{$value[section]}][{$option}Attach]" value="">
								</li>
						</ul>

					{elseif $type == 'font'}
						<label for="ait-themebox-fonts-{$value[section]}-{$option}" class="ait-themebox-label">{$value[label]}</label>
						{!=aitFontsDropdown(
							"ait-themebox[{$value['section']}][{$option}]",
							'ait-themebox-fonts-' . $value['section'] . '-' . $option,
							$value['value'],
							'all',
							'data-ait-themebox-option=\'{ "section": "' . $value['section'] . '", "option": "' . $option . '"}\''
							)
						}

					{/if}
				{/if}
			{/foreach}
			</div>
			{/ifset}
		{/foreach}


		{* load additional ThemeBox options for this theme *}

		{if file_exists($themeBox->themeTemplate)}
			{include $themeBox->themeTemplate, themeBox => $themeBox}
		{/if}

		{if $site->isUserLoggedIn}
			<div id="ait-themebox-save-button">
				<input type="submit" id="ait-themebox-save" value="Save settings">
			</div>
		{/if}

		<div id="ait-themebox-reset-options">
			<a href="#" id="ait-themebox-reset" title="This will reset only these temporary settings stored in cookies">Reset All These Settings</a>
		</div>

		</div> <!-- /#ait-themebox-options -->

	{if $site->isUserLoggedIn}
	</form>
	{/if}



	<script src="{$themeBox->url}/gui/themebox.js"></script>

	<div id="ait-themebox-ait-themes">
		<a href="#" id="ait-themebox-themes-toggler">{_"Our WordPress Themes"}</a>
		<div id="ait-themebox-themes">
			<ul id="ait-themebox-themes-list">
			{foreach $themeBox->otherAitThemes as $theme}
				{if $theme->inThemeBox}
				<li>
					<a href="{$theme->url}" target="_blank">
						<img src="{$theme->thumbnail}" class="thumb" width="32" height="32" alt="thumbnail">
						<img src="{$theme->preview}" class="preview" alt="preview image">
						<strong>{$theme->shortName}</strong>
					</a>
				</li>
				{/if}
			{/foreach}
			</ul>
		</div>
	</div>
</div><!-- /#themebox  -->
<!-- /ThemeBox -->