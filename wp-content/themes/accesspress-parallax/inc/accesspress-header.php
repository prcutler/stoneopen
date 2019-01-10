<?php
/**
 * Sample implementation of the Custom Header feature
 * http://codex.wordpress.org/Custom_Headers
 *
 * @package accesspress_parallax
 */

/**
 * Setup the WordPress core custom header feature.
 *
 * @uses accesspress_parallax_header_style()
 */

function accesspress_parallax_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'accesspress_parallax_custom_header_args', array(
		'default-image'          => '',
		'default-text-color'     => '000000',
		'width'                  => 250,
		'height'                 => 170,
		'flex-width'             => true,
		'flex-height'            => true,
		'wp-head-callback'       => 'accesspress_parallax_header_style',
	) ) );
}
add_action( 'after_setup_theme', 'accesspress_parallax_custom_header_setup' );

if ( ! function_exists( 'accesspress_parallax_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see accesspress_parallax_custom_header_setup().
 */
function accesspress_parallax_header_style() {
	$header_text_color = get_header_textcolor();

	/*
	 * If no custom options for text are set, let's bail.
	 * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: add_theme_support( 'custom-header' ).
	 */
	if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
		return;
	}

	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == $header_text_color ) :
	?>
		.site-title,
		.site-description {
			position: absolute;
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		.site-title a,
		.site-description {
			color: #<?php echo esc_attr($header_text_color); ?>;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // accesspress_parallax_header_style
