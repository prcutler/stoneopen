<?php
//function to post new event form data to db
function evr_post_event_to_db(){
        global $wpdb;
        //retrieve post data
        //note about coupon code - coupon code information is not posted here, but from the item cost page.
          $event_name = esc_html($_REQUEST ['event_name']);
          $event_identifier = esc_html($_REQUEST ['event_identifier']);
          $display_desc = $_REQUEST ['display_desc'];  // Y or N
          $event_desc = esc_html($_REQUEST ['event_desc']);
          $event_category = serialize($_REQUEST['event_category']);
          $reg_limit = $_REQUEST ['reg_limit'];
          $event_location = $_REQUEST ['event_location'];
          $event_address = $_REQUEST['event_street'];
          $event_city = $_REQUEST['event_city'];
          $event_state =$_REQUEST['event_state'];
          $event_postal=$_REQUEST['event_postcode'];
          $location_list = $_REQUEST['location_list'];
          $google_map = $_REQUEST['google_map'];  // Y or N
          
          $start_month = $_REQUEST ['start_month'];
          $start_day = $_REQUEST ['start_day'];
          $start_year = $_REQUEST ['start_year'];
          $end_month = $_REQUEST ['end_month'];
          $end_day = $_REQUEST ['end_day'];
          $end_year = $_REQUEST ['end_year'];
          $start_time = $_REQUEST ['start_time'];
          $end_time = $_REQUEST ['end_time'];
          $close = $_REQUEST['close'];
          $allow_checks = $_REQUEST['allow_checks'];
          
          $outside_reg = $_REQUEST['outside_reg'];  // Yor N
          $external_site = $_REQUEST['external_site'];
          
          $reg_form_defaults = serialize($_REQUEST['reg_form_defaults']);
          $more_info = $_REQUEST ['more_info'];
          $image_link = $_REQUEST ['image_link'];
          $header_image = $_REQUEST ['header_image'];
          $event_cost = $_REQUEST ['event_cost'];
          $allow_checks = $_REQUEST ['allow_checks'];
          $is_active = $_REQUEST ['is_active'];
          $send_mail = $_REQUEST ['send_mail'];  // Y or N
          $conf_mail = esc_html($_REQUEST ['conf_mail']);
          //build start date
          $start_date = $start_year."-".$start_month."-".$start_day;
          //build end date
          $end_date = $end_year."-".$end_month."-".$end_day;
          //set reg limit if not set
          if ($reg_limit == ''){$reg_limit = 999;}
          //added ver 6.00.13 
          $send_coord = $_REQUEST ['send_coord'];  // Y or N
          $coord_email = $_REQUEST ['coord_email'];
          $coord_msg = esc_html($_REQUEST ['coord_msg']);
          $coord_pay_msg = esc_html($_REQUEST ['coord_pay_msg']);
                        
            
                    
            $sql=array(
            'event_name'=>$event_name,
            'event_desc'=>$event_desc, 
            'location_list'=>$location_list,
            'event_location'=>$event_location,
            'event_address'=>$event_address,
            'event_city'=>$event_city,
            'event_state'=>$event_state,
            'event_postal'=>$event_postal,
            'google_map'=>$google_map,
            'outside_reg'=>$outside_reg,
            'external_site'=>$external_site,
            'display_desc'=>$display_desc, 
            'image_link'=>$image_link, 
            'header_image'=>$header_image,
            'event_identifier'=>$event_identifier,  
            'more_info'=>$more_info, 
            'start_month'=>$start_month, 
            'start_day'=>$start_day, 
            'start_year'=>$start_year, 
            'start_time'=>$start_time, 
            'start_date'=>$start_date,
            'end_month'=>$end_month, 
            'end_day'=>$end_day,
            'end_year'=>$end_year, 
            'end_date'=>$end_date, 
            'end_time'=>$end_time, 
            'close'=>$close,
            'reg_limit'=>$reg_limit,
            'custom_cur'=>$custom_cur, 
            'reg_form_defaults'=>$reg_form_defaults, 
            'allow_checks'=>$allow_checks,
            'send_mail'=>$send_mail, 
            'conf_mail'=>$conf_mail, 
            'is_active'=>$is_active, 
            'category_id'=>$event_category,
            'send_coord'=>$send_coord,
            'coord_email'=>$coord_email,
            'coord_msg'=>$coord_msg,
            'coord_pay_msg'=>$coord_pay_msg); 
                          
        		
            $sql_data = array('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',
                              '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',
                              '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',
                              '%s','%s','%s','%s','%s','%s','%s','%s','%s');
        	
        
            if ($wpdb->insert( get_option('evr_event'), $sql, $sql_data )){ ?>
            	<div id="message" class="updated fade"><p><strong><?php _e('The event ','evr_language'); echo stripslashes($_REQUEST['event_name']); _e('has been added.','evr_language');?> </strong></p>
                <p><strong><?php _e(' . . .Now returning you to event list . . ');?><meta http-equiv="Refresh" content="1; url=<?php echo $_SERVER["REQUEST_URI"]?>"></strong></p></div>
                <?php }else { ?>
        		<div id="message" class="error"><p><strong><?php _e('There was an error in your submission, please try again. The event was not saved!','evr_language');?><?php print mysql_error() ?>.</strong></p>
                <p><strong><?php _e(' . . .Now returning you to event list . . ','evr_language');?><meta http-equiv="Refresh" content="3; url=<?php echo $_SERVER["REQUEST_URI"]?>"></strong></p>
                </div>
                <?php } 
}
?>