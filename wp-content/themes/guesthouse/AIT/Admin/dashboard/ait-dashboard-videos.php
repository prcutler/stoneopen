<?php

/**
 * AIT WordPress Framework
 *
 * Copyright (c) 2011, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */

?>

<style type="text/css">
	.ait-yt-video{
		display:inline-block;
		margin:0 20px 20px 0;
		width:310px;
	}
	.wrap .ait-yt-video-title{
		font-size:18px;
	}
</style>

<h1>Videos</h1>

<?php

$url = 'http://gdata.youtube.com/feeds/base/users/AitThemes/uploads';

$cacheTime = (defined('AIT_DEVELOPMENT') && AIT_DEVELOPMENT) ? 5: (1 * 24 * 60 * 60);

$videos = aitCachedRemoteRequest('ait-videos', $url, $cacheTime);

if($videos !== false){
	foreach($videos->entry as $video){
		$id = substr($video->id, -11);
		?>
<div class="ait-yt-video">
	<h2 class="ait-yt-video-title"><a href="http://youtu.be/<?php echo $id?>?hd=1" target="_blank" title="Open video on YouTube"><?php echo $video->title ?></a></h2>
	<iframe width="310" height="240" src="http://www.youtube.com/embed/<?php echo $id ?>?hd=1" frameborder="0" allowfullscreen></iframe>
</div>
		<?php
	}
}else{
	?>
<div class="error">
	<p>Can not load videos from our channel <a href="http://www.youtube.com/AitThemes" target="_blank">YouTube.com/AitThemes</a></p>
</div>
	<?php
}

