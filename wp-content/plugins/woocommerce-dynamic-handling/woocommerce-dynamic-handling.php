<?php
/**
 * Plugin Name:     WooCommerce Dynamic Handling
 * Description:     WooCommerce plugin for add dynamic handling charge of products
 * Author:          Webskitters
 * Version:         1.0.2
 * Licence:         GPLv3
 * Author URI:      http://webskitters.com
 * Upgrade Check:   none
 * Last Change:     
 */
?>
<?php



add_action( 'add_meta_boxes', 'wdh_add_handling_metaboxes' );
function wdh_add_handling_metaboxes()
        {	
			add_meta_box('wdh_handling_meta_side', 'Handling', 'wdh_handling_fee', 'product', 'side', 'default');	
		}
		
function wdh_handling_fee()
        {
			global $post, $woocommerce ;
			echo '<table class="form-table">';
			// Noncename needed to verify where the data originated
			echo '<input type="hidden" name="wdc_handling_fee_noncename" id="wdc_handling_fee_noncename" value="' . 
			wp_create_nonce( plugin_basename(__FILE__) ) . '" />';    
			echo '<tr>';
			echo '<th style="width:40%"><label for="lead_name"><strong>Handling fee:</strong></label></th>';
			// Get the location data if its already been entered
			$wdc_handling_fee = get_post_meta($post->ID, 'wdc_handling_fee', true);
			// Echo out the field	
			echo '<td><input type="text" name="wdc_handling_fee" value="' . $wdc_handling_fee  . '"  size="30" style="width:60%" /></td>';
			echo '</tr>';
			
			echo "</table>";	

		}

// Save the Metabox Data
function wdh_save_handling_meta($post_id, $post) 
        {
	
			// verify this came from the our screen and with proper authorization,
			// because save_post can be triggered at other times
			if ( !wp_verify_nonce( $_POST['wdc_handling_fee_noncename'], plugin_basename(__FILE__) )) {
			return $post->ID;
			}
		
			// Is the user allowed to edit the post or page?
			if ( !current_user_can( 'edit_post', $post->ID ))
				return $post->ID;
		
			// OK, we're authenticated: we need to find and save the data
			// We'll put it into an array to make it easier to loop though.
		
			$lead_meta['wdc_handling_fee'] = $_POST['wdc_handling_fee'];
			
		
			
		
				// Add values of $lead_meta as custom fields
			
				foreach ($lead_meta as $key => $value) { // Cycle through the $lead_meta array!
					if( $post->post_type == 'revision' ) return; // Don't store custom data twice
					$value = implode(',', (array)$value); 
					if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
						update_post_meta($post->ID, $key, $value);
					} else { // If the custom field doesn't have a value
						add_post_meta($post->ID, $key, $value);
					}
					if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
				}

		}

add_action('save_post', 'wdh_save_handling_meta', 1, 2); // save the custom fields


class WC_Settings_Tab_Handling 
     {

    	 public static function init() {
        add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
    	}

		public static function add_settings_tab( $settings_tabs ) {
			$settings_tabs['handling'] = __( 'Handling', 'woocommerce-handling' );
			return $settings_tabs;
		}
	}
	
	WC_Settings_Tab_Handling::init();


add_action( 'woocommerce_settings_tabs_handling', 'wdh_settings_tab_handling' );

function wdh_settings_tab_handling() {
    woocommerce_admin_fields( wdh_get_settings_handling() );
}

function wdh_get_settings_handling() {
    $settings = array(
        'section_title' => array(
            'name'     => __( 'Handling Settings', 'woocommerce-settings-tab-handling' ),
            'type'     => 'title',
            'desc'     => '',
            'id'       => 'wc_settings_tab_handling_section_title'
        ),
        'title' => array(
            'name' => __( 'Costs Added...', 'woocommerce-handling' ),
            'type' => 'select',
            'desc' => __( '', 'woocommerce-handling' ),
            'id'   => 'wc_settings_tab_handling_type',
			'options'  => array(
								'per_item' => __('Per Item - charge handling fee for each item individually', 'woocommerce' ),
								'per_order' => __('Per Order - charge handling fee for the entire order as whole', 'woocommerce' ),
								'per_max_item' => __('Per Max Item - Charge handling fee for the entire order as whole(ony max handling will charge)', 'woocommerce' ),
								)
        ),
        
        'section_end' => array(
             'type' => 'sectionend',
             'id' => 'wc_settings_tab__handling_section_end'
        )
    );
    return apply_filters( 'wc_handling_settings', $settings );
}

add_action( 'woocommerce_update_options_handling', 'wdh_update_settings_handling' );

function wdh_update_settings_handling() {
    woocommerce_update_options( wdh_get_settings_handling() );
}


/*
** Add handling fee to cart
*/

add_action( 'woocommerce_cart_calculate_fees','wdh_charge_handling_fee' );
function wdh_charge_handling_fee() {
     global $woocommerce;
	 
     $handling_type = get_option('wc_settings_tab_handling_type');
	 foreach($woocommerce->cart->get_cart() as $cart_item_key => $values ) {
		$_product = $values['data'];		
		$handling[$_product->id] = floatval(get_post_meta($_product->id, 'wdc_handling_fee', true));
		
	 }
	 
	 if($handling_type == 'per_max_item')
	    {
		  $fee = max($handling);
		  if ( is_admin() && ! defined( 'DOING_AJAX' ) )
          return;
     
     	  $woocommerce->cart->add_fee( 'Handling', $fee, true, 'standard' );
		}
	 elseif($handling_type == 'per_order')
	    {
		  $fee = array_sum($handling);
		  if ( is_admin() && ! defined( 'DOING_AJAX' ) )
          return;
     
     	  $woocommerce->cart->add_fee( 'Handling', $fee, true, 'standard' );
		}
	elseif($handling_type == 'per_item')
	    {
			foreach($handling as $key => $item)
			        {
						$p_name = get_the_title( $key );
						$h_title = 'Handling for '.$p_name;
						$woocommerce->cart->add_fee( $h_title, $item, true, 'standard' );
						
					}
		}else{
			$fee = array_sum($handling);
		  	if ( is_admin() && ! defined( 'DOING_AJAX' ) )
         	return;
     
     	  	$woocommerce->cart->add_fee( 'Handling', $fee, true, 'standard' );
		}
	    
 
     
}