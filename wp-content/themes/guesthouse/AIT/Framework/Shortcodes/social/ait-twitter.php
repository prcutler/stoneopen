<?php
/* **********************************************************
 * TWITTER
 * **********************************************************/
function theme_twitter( $params, $content = null) {
	//$customer_key = $customer_secret = $username = $border = $tweets = $width = $height = $interval = $titlecolor = "";
    extract( shortcode_atts( array(
	    'widgetid' => '',
	    'username' => '',
    	'tweets' => '',
	    'variant' => '',
    	'width' => '',
    	'height' => '',
	    'borders' => 'yes',
	    'borderscolor' => '',
	    'linkscolor' => '',
	    'transparent' => 'no',
    ), $params ) );

	if($widgetid == '') { return "Widget ID is not defined!"; } // only required argument for widget
	ob_start();
	?>
	<div class="sc-twitter">
		<div class="wrap">
			<a class="twitter-timeline" href="<?php echo $username != '' ? "https://twitter.com/$username" : "#" ?>"
			data-widget-id="<?php echo $widgetid ?>"
			data-screen-name="<?php echo $username ?>"
			data-tweet-limit="<?php echo $tweets ?>"
			data-theme="<?php echo $variant ?>"
			width=<?php echo $width ?>
			height=<?php echo $height ?>
			data-border-color="<?php echo $borderscolor ?>"
			data-link-color="<?php echo $linkscolor ?>"
			data-chrome="<?php echo $borders == 'no' ? "noborders " : ""; echo $transparent == 'yes' ? "transparent" : "" ?>"
				><?php echo isset($username) ? "Tweets by $username" : ""?>
			</a>
			<script type="text/javascript">!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		</div>
	</div>
<?php
return ob_get_clean();

}
add_shortcode( 'twitter', 'theme_twitter' );