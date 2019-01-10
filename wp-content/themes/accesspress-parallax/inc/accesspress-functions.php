<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package accesspress_parallax
 */


/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function accesspress_parallax_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}
add_filter( 'body_class', 'accesspress_parallax_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function accesspress_parallax_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'accesspress_parallax_pingback_header' );

//bxSlider Callback for do action
function accesspress_parallax_bxslidercb(){
		global $post;
		$accesspress_parallax = of_get_option('parallax_section');
		if(!empty($accesspress_parallax)) :
			$accesspress_parallax_first_page_array = array_slice($accesspress_parallax, 0, 1);
			$accesspress_parallax_first_page = $accesspress_parallax_first_page_array[0]['page'];
		endif;
		$accesspress_slider_category = of_get_option('slider_category');
		$accesspress_slider_full_window = of_get_option('slider_full_window') ;
		$accesspress_show_slider = of_get_option('show_slider') ;
		$accesspress_show_caption = of_get_option('show_caption') ;
		$accesspress_enable_parallax = of_get_option('enable_parallax');
		?>

		<?php if( $accesspress_show_slider == "yes" || empty($accesspress_show_slider)) : ?>
		<section id="main-slider" class="full-screen-<?php echo esc_attr($accesspress_slider_full_window); ?>">

		<?php if(!empty($accesspress_parallax_first_page) && $accesspress_enable_parallax == 1): ?>
		<div class="next-page"><a href="<?php echo esc_url( home_url( '/' ) ); ?>#section-<?php echo esc_attr($accesspress_parallax_first_page); ?>"></a></div>
		<?php endif;
		
		if( !empty($accesspress_slider_category)) :

				$loop = new WP_Query(array(
						'cat' => $accesspress_slider_category,
						'posts_per_page' => -1
					));
					if($loop->have_posts()) : ?>

					<div class="bx-slider">
					<?php
					while($loop->have_posts()) : $loop-> the_post(); 
					$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full', false ); 
					$image_url = "";
					if($accesspress_slider_full_window == "yes") : 
						$image_url =  "style = 'background-image:url(".esc_url($image[0]).");'";
				    endif;
					?>
					<div class="main-slides" <?php echo $image_url; // WPCS: XSS OK.?>>
					
					<?php if($accesspress_slider_full_window == "no") : ?>		
						<img src="<?php echo esc_url($image[0]); ?>">
					<?php endif; ?>
								
						<?php if($accesspress_show_caption == 'yes'): ?>
						<div class="slider-caption">
							<div class="mid-content">
								<div class="caption-title"><?php the_title();?></div>
								<div class="caption-description"><?php the_content();?></div>
							</div>
						</div>
						<?php endif; ?>
				
			        </div>
					<?php endwhile; ?>
					</div>
					<?php endif; ?>

			<?php  endif; ?>
		</section>
		<?php endif; ?>
<?php
}

add_action('accesspress_bxslider','accesspress_parallax_bxslidercb', 10);


//add class for parallax
function accesspress_is_parallax($class){
	$is_parallax = of_get_option('enable_parallax');
	if($is_parallax=='1' || is_page_template('home-page.php')):
		$class[] = "parallax-on"; 
	endif;
	return $class;
}

add_filter('body_class','accesspress_is_parallax');


//Dynamic styles on header
function accesspress_header_styles_scripts(){
	$sections = array();
	$sections = of_get_option('parallax_section');
	$custom_css = of_get_option('custom_css');
	$slider_overlay = of_get_option('slider_overlay');
	$image_url = get_template_directory_uri()."/images/";
	$dyamic_style = "";
	echo "<style type='text/css' media='all'>"; 

	if(!empty($sections)){
	foreach ($sections as $section) {
		$dyamic_style .= "#section-".$section['page']."{ background:url(".$section['image'].") ".$section['repeat']." ".$section['attachment']." ".$section['position']." ".$section['color']."; background-size:".$section['size']."; color:".$section['font_color']."}\n";
		$dyamic_style .= "#section-".$section['page']." .overlay { background:url(".$image_url.$section['overlay'].".png);}\n";
	}
	}

	if($slider_overlay == "yes"){
		$dyamic_style .= "#main-slider .main-slides:after{display:none};";
	}
	echo esc_textarea($dyamic_style);
	echo esc_textarea($custom_css);

	echo "</style>\n"; 

	echo "<script>\n";
	if(of_get_option('enable_animation') == '1' && is_front_page()) : ?>
    jQuery(document).ready(function($){
       wow = new WOW(
        {
          offset:  200 
        }
      )
      wow.init();
    });
    <?php endif;
	echo "</script>\n";
}

add_action('wp_head','accesspress_header_styles_scripts');

function accesspress_footer_count(){
	$count = 0;
	if(is_active_sidebar('footer-1'))
	$count++;

	if(is_active_sidebar('footer-2'))
	$count++;

	if(is_active_sidebar('footer-3'))
	$count++;

	if(is_active_sidebar('footer-4'))
	$count++;

	return $count;
}


function accesspress_social_cb(){
	$facebooklink = of_get_option('facebook');
	$twitterlink = of_get_option('twitter');
	$google_pluslink = of_get_option('google_plus');
	$youtubelink = of_get_option('youtube');
	$pinterestlink = of_get_option('pinterest');
	$linkedinlink = of_get_option('linkedin');
	$flickrlink = of_get_option('flickr');
	$vimeolink = of_get_option('vimeo');
	$instagramlink = of_get_option('instagram');
	$skypelink = of_get_option('skype');
	?>
	<div class="social-icons">
		<?php if(!empty($facebooklink)){ ?>
		<a href="<?php echo esc_url($facebooklink); ?>" class="facebook" data-title="Facebook" target="_blank"><i class="fa fa-facebook"></i><span></span></a>
		<?php } ?>

		<?php if(!empty($twitterlink)){ ?>
		<a href="<?php echo esc_url($twitterlink); ?>" class="twitter" data-title="Twitter" target="_blank"><i class="fa fa-twitter"></i><span></span></a>
		<?php } ?>

		<?php if(!empty($google_pluslink)){ ?>
		<a href="<?php echo esc_url($google_pluslink); ?>" class="gplus" data-title="Google Plus" target="_blank"><i class="fa fa-google-plus"></i><span></span></a>
		<?php } ?>

		<?php if(!empty($youtubelink)){ ?>
		<a href="<?php echo esc_url($youtubelink); ?>" class="youtube" data-title="Youtube" target="_blank"><i class="fa fa-youtube"></i><span></span></a>
		<?php } ?>

		<?php if(!empty($pinterestlink)){ ?>
		<a href="<?php echo esc_url($pinterestlink); ?>" class="pinterest" data-title="Pinterest" target="_blank"><i class="fa fa-pinterest"></i><span></span></a>
		<?php } ?>

		<?php if(!empty($linkedinlink)){ ?>
		<a href="<?php echo esc_url($linkedinlink); ?>" class="linkedin" data-title="Linkedin" target="_blank"><i class="fa fa-linkedin"></i><span></span></a>
		<?php } ?>

		<?php if(!empty($flickrlink)){ ?>
		<a href="<?php echo esc_url($flickrlink); ?>" class="flickr" data-title="Flickr" target="_blank"><i class="fa fa-flickr"></i><span></span></a>
		<?php } ?>

		<?php if(!empty($vimeolink)){ ?>
		<a href="<?php echo esc_url($vimeolink); ?>" class="vimeo" data-title="Vimeo" target="_blank"><i class="fa fa-vimeo-square"></i><span></span></a>
		<?php } ?>

		<?php if(!empty($instagramlink)){ ?>
		<a href="<?php echo esc_url($instagramlink); ?>" class="instagram" data-title="instagram" target="_blank"><i class="fa fa-instagram"></i><span></span></a>
		<?php } ?>
		
		<?php if(!empty($skypelink)){ ?>
		<a href="<?php echo "skype:".esc_attr($skypelink) ?>" class="skype" data-title="Skype"><i class="fa fa-skype"></i><span></span></a>
		<?php } ?>
	</div>

	<script>
	jQuery(document).ready(function($){
		$(window).resize(function(){
			 var socialHeight = $('.social-icons').outerHeight();
			 $('.social-icons').css('margin-top',-(socialHeight/2));
		}).resize();
	});
	</script>
<?php
}
add_action('accesspress_social','accesspress_social_cb', 10);

function accesspress_remove_page_menu_div( $menu ){
    return preg_replace( array( '#^<div[^>]*>#', '#</div>$#' ), '', $menu );
}
add_filter( 'wp_page_menu', 'accesspress_remove_page_menu_div' );

function accesspress_customize_excerpt_more( $more ) {
	return '...';
}
add_filter('excerpt_more', 'accesspress_customize_excerpt_more');

function accesspress_word_count($string, $limit) {
	$words = explode(' ', $string);
	return implode(' ', array_slice($words, 0, $limit));
}

function accesspress_letter_count($content, $limit) {
	$striped_content = strip_tags($content);
	$striped_content = strip_shortcodes($striped_content);
	$limit_content = mb_substr($striped_content, 0 , $limit );

	if( strlen($limit_content) < strlen($content) ){
		$limit_content .= "..."; 
	}
	return $limit_content;
}



function accesspress_register_string(){
	if(function_exists('pll_register_string')){
		$home_text = of_get_option('home_text');
		pll_register_string('Menu: Home Text', $home_text ,'Theme Option Text');
	}
}

add_action('after_setup_theme','accesspress_register_string');

function accesspress_translated_id($orginal_id){
	$translation_title_id = apply_filters( 'wpml_object_id', $orginal_id, 'page', true);
	
	if( ($translation_title_id == $orginal_id) && function_exists('pll_get_post') ){
		$translation_title_id = pll_get_post($orginal_id);
	}
	return $translation_title_id;
}

add_filter( 'accesspress_translate_id', 'accesspress_translated_id' );

function accesspress_translated_string($string, $domain){
	$wpml_translation = apply_filters('wpml_translate_single_string', $string, $domain, $string);
    
    if ($wpml_translation === $string && function_exists('pll__')) {
    	pll_register_string($domain, $string);
        return pll__($string);
    }

    return $wpml_translation;

}

add_filter( 'accesspress_translate_string', 'accesspress_translated_string', 10, 2 );
