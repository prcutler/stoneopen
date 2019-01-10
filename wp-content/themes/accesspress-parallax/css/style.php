<?php
    function accesspress_parallax_dynamic_styles() {
            $tpl_color = of_get_option('template_color', '#E66432');

            $custom_css = "";

            if( $tpl_color ) {
                $darker_tpl_color = accesspress_parallax_colour_brightness($tpl_color, -0.8);
                $dark_tpl_color = accesspress_parallax_colour_brightness($tpl_color, -0.9);
                $rgb = accesspress_parallax_hex2rgb($tpl_color);

                /** Color **/
                $custom_css .= "
                    .main-navigation > ul > li.current a,
                    .main-navigation li:hover > a,
                    .blog-list .blog-excerpt .posted-date,
                    .woocommerce p.stars a,
                    .product_meta a,
                    .woocommerce-MyAccount-navigation a,
                    .woocommerce-MyAccount-content a,
                    .woocommerce-cart-form__cart-item a,
                    .woocommerce-info a{
                        color: {$tpl_color};
                    }";
                    
                /** Background Color **/
                $custom_css .= "
                    #main-slider .slick-dots li.slick-active button,
                    .slider-caption .caption-description a:hover, .btn:hover,
                    .testimonial-listing .slick-arrow:hover,
                    .blog-list .blog-excerpt span,
                    .woocommerce ul.products li.product .onsale, .woocommerce span.onsale,
                    .woocommerce ul.products li.product .button,
                    .parallax-section .wpcf7-form .wpcf7-submit, #go-top,
                    .posted-on,
                    .pagination .nav-links a, .pagination .nav-links span,
                    .woocommerce nav.woocommerce-pagination ul li a,
                    .woocommerce nav.woocommerce-pagination ul li span,
                    .woocommerce #respond input#submit.alt,
                    .woocommerce a.button.alt, .woocommerce button.button.alt,
                    .woocommerce input.button.alt,
                    .woocommerce #respond input#submit:hover,
                    .woocommerce a.button:hover, .woocommerce button.button:hover,
                    .woocommerce input.button:hover,
                    .woocommerce #respond input#submit:hover,
                    .woocommerce a.button:hover, .woocommerce button.button:hover,
                    .woocommerce input.button:hover{
                        background: {$tpl_color};
                    }";
                    
                /** Dark Background **/
                $custom_css .= "
                    .woocommerce #respond input#submit.alt:hover,
                    .woocommerce a.button.alt:hover,
                    .woocommerce button.button.alt:hover,
                    .woocommerce input.button.alt:hover{
                        background: {$dark_tpl_color}; 
                    }";
                    
                /** Border Color **/
                $custom_css .= "
                    #masthead,
                    #main-slider .slick-dots li.slick-active button,
                    .slider-caption .caption-description a:hover, .btn:hover,
                    .team-image:hover, .team-image.slick-current,
                    .testimonial-listing .slick-arrow:hover,
                    .blog-list .blog-excerpt,
                    .parallax-section input[type=\"text\"],
                    .parallax-section input[type=\"email\"],
                    .parallax-section input[type=\"url\"],
                    .parallax-section input[type=\"password\"],
                    .parallax-section input[type=\"search\"],
                    .parallax-section input[type=\"tel\"],
                    .parallax-section textarea,
                    #secondary h2.widget-title{
                        border-color: {$tpl_color};
                    }";
                    
                /** Transparent Border Color **/
                $custom_css .= "
                    .posted-on:before{
                        border-color: transparent transparent {$darker_tpl_color} {$darker_tpl_color};
                    }";

            }

            wp_add_inline_style( 'accesspress-parallax-style', $custom_css );
    }

    add_action( 'wp_enqueue_scripts', 'accesspress_parallax_dynamic_styles' );

    function accesspress_parallax_colour_brightness($hex, $percent) {
        // Work out if hash given
        $hash = '';
        if (stristr($hex, '#')) {
            $hex = str_replace('#', '', $hex);
            $hash = '#';
        }
        /// HEX TO RGB
        $rgb = array(hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2)));
        //// CALCULATE 
        for ($i = 0; $i < 3; $i++) {
            // See if brighter or darker
            if ($percent > 0) {
                // Lighter
                $rgb[$i] = round($rgb[$i] * $percent) + round(255 * (1 - $percent));
            } else {
                // Darker
                $positivePercent = $percent - ($percent * 2);
                $rgb[$i] = round($rgb[$i] * $positivePercent) + round(0 * (1 - $positivePercent));
            }
            // In case rounding up causes us to go to 256
            if ($rgb[$i] > 255) {
                $rgb[$i] = 255;
            }
        }
        //// RBG to Hex
        $hex = '';
        for ($i = 0; $i < 3; $i++) {
            // Convert the decimal digit to hex
            $hexDigit = dechex($rgb[$i]);
            // Add a leading zero if necessary
            if (strlen($hexDigit) == 1) {
                $hexDigit = "0" . $hexDigit;
            }
            // Append to the hex string
            $hex .= $hexDigit;
        }
        return $hash . $hex;
    }

    function accesspress_parallax_hex2rgb($hex) {
        $hex = str_replace("#", "", $hex);

        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        $rgb = array($r, $g, $b);
        //return implode(",", $rgb); // returns the rgb values separated by commas
        return $rgb; // returns an array with the rgb values
    }