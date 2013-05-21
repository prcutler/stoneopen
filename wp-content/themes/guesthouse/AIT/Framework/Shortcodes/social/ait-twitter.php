<?php
/* **********************************************************
 * TWITTER
 * **********************************************************/
function theme_twitter( $params, $content = null) {
    extract( shortcode_atts( array(
    	'username' => '',
    	'tweets' => '10',
    	'width' => '600',
    	'height' => '600',
    	'interval' => '6000',
    	'titlecolor' => '#FFFFFF',
    	'headercolor' => '0',
    	'backgroundcolor' => '0',
    	'textcolor' => '#FFFFFF',
    	'linkscolor' => '0',
    	'border' => ''
    ), $params ) );
	
	if($username == "") { return "Username was not defined!"; }
	
	if($border == 'yes') {
		$borderStyle = ' border'; 
	} else {
		$borderStyle = ''; 
	}
	
return <<<HTML
<div class="sc-twitter{$borderStyle}"><div class="wrap">
<script src="http://widgets.twimg.com/j/2/widget.js"></script>
<script>
new TWTR.Widget({
  version: 2,
  type: 'profile',
  rpp: {$tweets},
  interval: {$interval},
  width: {$width},
  height: {$height},
  theme: {
    shell: {
    	background: '{$headercolor}',
      	color: '{$titlecolor}'
    },
    tweets: {
    	background: '{$backgroundcolor}',
    	color: '{$textcolor}',
    	links: '{$linkscolor}'
    }
  },
  features: {
    scrollbar: false,
    loop: false,
    live: false,
    hashtags: true,
    timestamp: true,
    avatars: false,
    behavior: 'all'
  }
}).render().setUser('{$username}').start();
</script>
</div></div>
HTML;

}
add_shortcode( 'twitter', 'theme_twitter' );