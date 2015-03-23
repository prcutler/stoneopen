<?php

/**
 * AIT WordPress Framework
 *
 * Copyright (c) 2011, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */

global $aitDisableForum;
?>
<style type="text/css">
	.ait-themes						{ background: #f5f5f5; border: 1px solid #dfdfdf; border-radius: 3px; box-shadow: 0px 0px 0px 1px #ffffff inset; padding: 20px 20px 10px 20px; }
	.ait-themes .themes				{ list-style-type: none; margin: 0px 0px 0px 0px; padding: 0px; text-align: center; }
	.ait-themes .themes:after 		{ content: "."; display: block; height: 0; overflow:hidden; clear: both; visibility: hidden; }
	.ait-themes .themes li			{ display: inline-block; vertical-align: top; width: 70px; margin-left: 5px; margin-right: 5px; margin-bottom: 10px; position: relative; }
	.ait-themes .themes li:hover	{ z-index: 1000; }
	.ait-themes .themes a			{ display: block; }
	.ait-themes .themes .thumb		{ display: block; width: 60px; height: 60px; padding: 4px; border: 1px solid #DFDFDF; background: #FFFFFF; }
	.ait-themes .themes .preview			{ display: none; }
	.ait-themes .themes a:hover .preview	{ display: block; position: absolute; left: 50px; bottom: 50px; padding: 5px; border: 1px solid #DFDFDF; background: #FFFFFF; -moz-box-shadow: 0px 2px 5px rgba(0,0,0,0.3); -webkit-box-shadow: 0px 2px 5px rgba(0,0,0,0.3); box-shadow: 0px 2px 5px rgba(0,0,0,0.3);}

	h2#sorry-title		{text-align: center; margin:2em 0em 1em 0em; font-weight: normal;}
	p#sorry-subtitle	{text-align: center; margin:0em 0em 2em 0em;}
	.ait-themes .themes a:hover .preview	{position: absolute; left: 0px; top: 75px;}
</style>
<?php
if(isset($aitDisableForum) and $aitDisableForum){
?>
<h2 id="sorry-title"><strong>We're sorry,</strong> support is available for our <strong>Premium Wordpress Theme only.</strong></h2>
<p id="sorry-subtitle">Please have a look at our recent themes below.</p>
<?php

$url = 'http://www.ait-themes.com/json-export.php?ref=' . urlencode($_SERVER['SERVER_NAME']) . '&from=dashboard';

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

}else{
?>
<iframe src="http://support.ait-themes.com/" width="100%" height="80%" id="ait-support-forum"></iframe>
<?php
}