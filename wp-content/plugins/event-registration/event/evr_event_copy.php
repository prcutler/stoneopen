<?php
//function to copy an existing event
function evr_copy_event(){
        global $wpdb;
    	$event_id = $_REQUEST ['id'];
    	$sql = "SELECT * FROM ". get_option('evr_event') ." WHERE id =" . $event_id;
		$result = mysql_query ($sql);
		while ($row = mysql_fetch_assoc ($result)){
	        //retrieve post data
        //note about coupon code - coupon code information is not posted here, but from the item cost page.
        //no cost information or coupon information is copied when and event is copied
                   	$event_name        = "Copy of ".$row['event_name'];
					$event_identifier  = "CPY-".$row['event_identifier'];
					$event_desc        = $row['event_desc'];
					$image_link        = $row['image_link'];
					$header_image      = $row['header_image'];
					$display_desc      = $row['display_desc'];
					$event_location    = $row['event_location'];
                    $event_address     = $row['event_address'];
                    $event_city        = $row['event_city'];
                    $event_postal      = $row['event_postal'];
                    $event_state       = $row['event_state'];
					$more_info         = $row['more_info'];
					$reg_limit         = $row['reg_limit'];
					$event_cost        = $row['event_cost'];
                    $allow_checks      = $row['allow_checks'];
					$is_active         = $row['is_active'];
					$start_month       = $row['start_month'];
					$start_day         = $row['start_day'];
					$start_year        = $row['start_year'];
					$end_month         = $row['end_month'];
					$end_day           = $row['end_day'];
					$end_year          = $row['end_year'];
					$start_time        = $row['start_time'];
					$end_time          = $row['end_time'];
					$conf_mail         = $row['conf_mail'];
					$send_mail         = $row['send_mail'];
            		$event_category    = $row['event_category'];
					$start_date        = $row['start_date'];
					$end_date          = $row['end_date'];
                    $reg_form_defaults = $row['reg_form_defaults'];
                    $use_coupon         = $row['use_coupon'];
                    $coupon_code        = $row['coupon_code'];
                    $coupon_code_price  = $row['coupon_code_price'];
                    
                        
            $sql=array('event_name'=>$event_name, 'event_desc'=>$event_desc, 'event_location'=>$event_location, 'event_address'=>$event_address,
            'event_city'=>$event_city,'event_state'=>$event_state,'event_postal'=>$event_postal,'display_desc'=>$display_desc, 
            'image_link'=>$image_link, 'header_image'=>$header_image,'event_identifier'=>$event_identifier,  'more_info'=>$more_info, 
            'start_month'=>$start_month, 'start_day'=>$start_day, 'start_year'=>$start_year, 'start_time'=>$start_time, 'start_date'=>$start_date,
            'end_month'=>$end_month, 'end_day'=>$end_day,'end_year'=>$end_year, 'end_date'=>$end_date, 'end_time'=>$end_time, 'reg_limit'=>$reg_limit,
            'custom_cur'=>$custom_cur, 'reg_form_defaults'=>$reg_form_defaults, 'allow_checks'=>$allow_checks, 
            'send_mail'=>$send_mail, 'conf_mail'=>$conf_mail, 'is_active'=>$is_active, 'category_id'=>$event_category, 'use_coupon'=>$use_coupon,
            'coupon_code'=>$coupon_code, 'coupon_code_price'=>$coupon_code_price); 
                          
        		
            $sql_data = array('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',
                              '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',
                              '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s');
        	
        
            if ($wpdb->insert( get_option('evr_event'), $sql, $sql_data )){ 
                $lastID = $wpdb->insert_id;
                
                
                ?>
            	<div id="message" class="updated fade"><p><strong><?php _e('The event ','evr_language'); echo stripslashes($_REQUEST['event_name']); _e('has been added.','evr_language');?> </strong></p></div>
                
               <?php 
                $events_question_tbl = get_option ( 'evr_question' );
                $questions = $wpdb->get_results ( "SELECT * from $events_question_tbl where event_id = $event_id order by sequence ASC" );
                if ($questions) {
         				foreach ( $questions as $question ) {
         				   $sql = array('event_id'=>$lastID, 'sequence'=>$question->sequence,'question_type'=>$question->question_type, 
                              'question'=>$question->question,'response'=>$question->response ,'required'=>$question->required );
                        $sql_data = array('%s','%s','%s','%s','%s','%s');
                        if ($wpdb->insert( get_option('evr_question'), $sql, $sql_data )){ }
                                
                                
               				   }?>
                           <div id="message" class="updated fade"><p><strong><?php _e('The questions have been added.','evr_language');?> </strong></p></div>
                <?php }

                $sql = "SELECT * FROM ". get_option('evr_cost') ." WHERE event_id = " . $event_id;
                $result = mysql_query ($sql);
        		while ($row = mysql_fetch_assoc ($result)){
        		  
        		    $sequence         = $row['sequence'];
        			$event_id         = $lastID;
                    $item_title       = $row['item_title'];
                    $item_description = $row['item_description']; 
                    $item_cat         = $row['item_cat'];
                    $item_limit       = $row['item_limit'];
                    $item_price       = $row['item_price'];
                    $free_item        = $row['free_item'];
                    $item_start_date  = $row['item_available_start_date'];
                    $item_end_date    = $row['item_available_end_date'];
                    $item_custom_cur  = $row['item_custom_cur'];
                    
                    $sql=array('sequence'=>$sequence,'event_id'=>$event_id, 'item_title'=>$item_title, 'item_description'=>$item_description, 
                        'item_cat'=>$item_cat, 'item_limit'=>$item_limit, 'item_price'=>$item_price, 'free_item'=>$free_item,'item_available_start_date'=>$item_start_date,  
                        'item_available_end_date'=>$item_end_date, 'item_custom_cur'=>$item_custom_cur); 
		
			         $sql_data = array('%s','%s','%s','%s','%s','%d','%s','%s','%s','%s','%s');
                     
                   if ($wpdb->insert( get_option('evr_cost'), $sql, $sql_data )){
                    ?>
            	   <div id="message" class="updated fade"><p><strong><?php _e('The cost ','evr_language'); echo $item_title; _e('has been added.','evr_language');?> </strong></p></div>
                    <?php } else { ?>
        		<div id="message" class="error"><p><strong><?php _e('There was an error in your submission, please try again. The cost was not saved!','evr_language');?><?php print mysql_error() ?>.</strong></p></div>
                <?php
                
                    }}
                    
                ?>
                
                <div id="message" class="updated fade"><p><strong><?php _e(' . . .Now returning you to event list . . ','evr_language');?><meta http-equiv="Refresh" content="1; url=<?php echo $_SERVER["REQUEST_URI"]?>"></strong></p></div>
                <?php }else { ?>
        		<div id="message" class="error"><p><strong><?php _e('There was an error in your submission, please try again. The event was not saved!','evr_language');?><?php print mysql_error() ?>.</strong></p></div>
                <div id="message" class="updated fade"><p><strong><?php _e(' . . .Now returning you to event list . . ','evr_language');?><meta http-equiv="Refresh" content="3; url=<?php echo $_SERVER["REQUEST_URI"]?>"></strong></p></div>
                <?php } 
           }     
}
?>