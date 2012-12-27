<?php
function evr_new_event_button(){
     ?>
     <form name="form" method="post" action="<?php echo $_SERVER["REQUEST_URI"]?>">
                                <input type="hidden" name="action" value="new">
                                <input class="evr_button evr_add" type="submit" name="new" value="<?php  _e('ADD EVENT','evr_language');?>" />
     </form>
     <?php
}
//function that displays events with option buttons
function evr_event_listing(){
//define # of records to display per page
$record_limit = 10;
//get today's date to sort records between current & expired'
$curdate = date("Y-m-d");
//initiate connection to wordpress database.
global $wpdb, $evr_date_format;

?>
<div class="wrap">
<h2 style="font-family: segoe;"><a href="http://www.wpeventregister.com"><img src="<?php echo EVR_PLUGINFULLURL; ?>images/evr_icon.png" alt="Event Registration for Wordpress" /></a></h2>
<h2><?php _e('Event Management','evr_language');?></h2>
<?php evr_new_event_button();?>
 <?php
                //check database for number of records with date of today or in the future
                $sql = "SELECT * FROM ".get_option('evr_event');
                $records = mysql_query($sql);
                $items = mysql_num_rows($records); // number of total rows in the database
                	if($items > 0) {
                		$p = new evr_pagination;
                		$p->items($items);
                		$p->limit($record_limit); // Limit entries per page
                		$p->target("admin.php?page=events");
                		if(!isset($_GET['paging'])) {
                			$p->page = 1;
                		} else {
                			$p->page = $_GET['paging'];
                		}
                
                        
                        $p->currentPage($p->page); // Gets and validates the current page
                		$p->calculate(); // Calculates what to show
                		$p->parameterName('paging');
                		$p->adjacents(1); //No. of page away from the current page
                
                		
                		//Query for limit paging
                		$limit = "LIMIT " . ($p->page - 1) * $p->limit  . ", " . $p->limit;
                } else {
                	echo "No Record Found";
                }//End pagination
                ?>
                    <div class="padding">
                    <div class="tablenav">
                        <div class='tablenav-pages'>
                            <?php if($items > 0) { echo $p->show(); } // Echo out the list of paging. ?>
                        </div>
                    </div>
                         <table class="widefat">
                         <thead>
                          <tr>
                            <th>Start Date</th>
                            <th>Event ID</th>
                            <th>Name</th>
                            <th>ShortCode</th>
                            <th>Status</th>
                            <th># Attendees</th>
                            <th>Manage</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tfoot>
                          <tr>
                            <th>Start Date</th>
                            <th>Event ID</th>
                            <th>Name</th>
                            <th>ShortCode</th>
                            <th>Status</th>
                            <th># Attendees</th>
                            <th>Manage</th>
                            <th>Action</th>
                          </tr>
                        </tfoot>
                        <tbody>
                        <?php
                       	$rows = $wpdb->get_results( "SELECT * FROM ". get_option('evr_event') ." ORDER BY date(start_date) DESC ".$limit );
                          if ($rows){
                            foreach ($rows as $event){
                                        $event_id       = $event->id;
                        				$event_name     = stripslashes($event->event_name);
                                        if((get_option('evr_location_active')=="Y") && ( $event->location_list >= '1')){
                                            $sql = "SELECT * FROM " . get_option('evr_location')." WHERE id =".$event->location_list;
                                            $location = $wpdb->get_row( $sql, OBJECT );//default object
                                            //$object->field;
                                            if( !empty( $location ) ) {
                                            $event_location = stripslashes($location->location_name);
                                            $event_address  = $location->street;
                                            $event_city     = $location->city;
                                            $event_state    = $location->state;
                                            $event_postal   = $location->postal;
                                            $event_phone    = $location->phone;
                                            }
                                        } else {
                                        $event_location = stripslashes($event->event_location);
                                        $event_address  = $event->event_address;
                                        $event_city     = $event->event_city;
                                        $event_postal   = $event->event_postal;
                                        }
                                        $reg_limit      = $event->reg_limit;
                                		$start_time     = $event->start_time;
                                		$end_time       = $event->end_time;
                                		$conf_mail      = $event->conf_mail;
                                        $custom_mail    = $event->custom_mail;
                                		$start_date     = $event->start_date;
                                		$end_date       = $event->end_date;
                                        $event_close    = $event->close;
                            $number_attendees = $wpdb->get_var($wpdb->prepare("SELECT SUM(quantity) FROM " . get_option('evr_attendee') . " WHERE event_id= %d",$event_id));
            				if ($number_attendees == '' || $number_attendees == 0){
            					$number_attendees = '0';
            				}
            				if ($reg_limit == "" || $reg_limit == " "){
            					$reg_limit = "Unlimited";}
                               $available_spaces = $reg_limit;
                               
                               //$current_dt= date('Y-m-d H:i a',current_time('timestamp',0));
$current_dt= date('Y-m-d H:i',current_time('timestamp',0));
if ($event_close == "start"){$close_dt = $start_date." ".$start_time;}
else if ($event_close == "end"){$close_dt = $end_date." ".$end_time;}
else if ($event_close == ""){$close_dt = $start_date." ".$start_time;}
$stp = DATE("Y-m-d H:i", STRTOTIME($close_dt));
$expiration_date = strtotime($stp);
$today = strtotime($current_dt);
if ($expiration_date <= $today){
           					$active_event = '<span style="color: #F00; font-weight:bold;">'.__('EXPIRED','evr_language').'</span>';
            				} else{
            					$active_event = '<span style="color: #090; font-weight:bold;">'.__('ACTIVE','evr_language').'</span>';
            				}   
                        	?>
                            <tr></tr>
                          <tr>
                            <td style="white-space: nowrap;"><?php echo $start_date; ?></td>
                            <td><?php echo $event_id; ?></td>
                            <td>
                            <a href="#TB_inline?height=600&width=400&inlineId=popup<?php echo $event_id;?>" class="thickbox"><?php echo evr_truncateWords($event_name, 8, "..."); ?></a>
                            <br />
                            <?php echo $event_location; ?><?php echo ", ".$event_city; ?>
                            </td>
                            <td style="white-space: nowrap;">[EVR_SINGLE event_id="<?php echo $event_id;?>"] </td>
                            <td><?php echo $active_event ; ?></td>
                            <td><?php echo $number_attendees;?> / <?php echo $reg_limit; ?></td>
                            <td>
                            <div style="float:left; margin-right:10px;">
                              <form name="form" method="post" action="<?php echo $_SERVER["REQUEST_URI"]?>">
                                <input type="hidden" name="action" value="add_item"/>
                                <input type="hidden" name="event_id" value="<?php echo $event_id?>">
                                <input class="fees_btn" type="submit" name="items" value="<?php _e('Fees/Items','evr_language');?>" />
                              </form>
                            </div>
                            <div style="float:left; margin-right:10px;">
                                        <form name="form" method="post" action="admin.php?page=questions">
                                          <input type="hidden" name="action" value="new">
                                          <input type="hidden" name="event_id" value="<?php echo $event_id;?>">
                                          <input type="hidden" name="event_name" value="<?php echo $event_name;?>">
                                          <input class="question_btn" type="submit" name="questions" value="<?php _e('Questions','evr_language');?>" />
                                        </form>
                                    </div>
                                    <div style="float:left;">
                                        <form name="form" method="post" action="admin.php?page=attendee">
                                          <input type="hidden" name="action" value="view_attendee">
                                          <input type="hidden" name="event_id" value="<?php echo $event_id?>">
                                          <input class="attendee_btn" type="submit" name="Attendees" value="<?php _e('Attendees','evr_language');?>" />
                                        </form>
                                    </div>
                            </td><td>
                            <div style="float:left; margin-right:10px;">
                              <form name="form" method="post" action="<?php echo $_SERVER["REQUEST_URI"]?>">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="id" value="<?php echo $event_id?>">
                                <input class="edit_btn" type="submit" name="edit" value="<?php _e('Edit','evr_language');?>" id="edit_event_setting-<?php echo $event_id?>" />
                              </form>
                            </div>
                            <div style="float:left; margin-right:10px;">
                                        <form name="form" method="post" action="<?php echo $_SERVER["REQUEST_URI"]?>">
                                          <input type="hidden" name="action" value="copy_event">
                                          <input type="hidden" name="id" value="<?php echo $event_id?>">
                                          <input class="copy_btn" type="submit" name="copy" value="<?php _e('Copy','evr_language');?>" id="copy_event_setting-<?php echo $event_id?>"  onclick="return confirm('<?php _e('Are you sure you want to copy','evr_language');?> <?php echo $event_name?>?')"/>
                                        </form>
                                    </div>
                            <div style="float:left;">
                              <form name="form" method="post" action="<?php echo $_SERVER["REQUEST_URI"]?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $event_id?>">
                                <input class="delete_btn" type="submit" name="delete" value="<?php _e('Delete','evr_language');?>" id="delete_event-<?php echo $event_id?>" onclick="return confirm('<?php _e('Are you sure you want to delete','evr_language');?> <?php echo $event_name?>?')"/>
                              </form>
                            </div>
                            </td>
                            </tr>
                            <tr></tr>
                          <?php
                        	}
                         } else { ?>
                          <tr>
                            <td>No Record Found!</td>
                          <tr>
                            <?php	}?>
                          </tbody>
                        </table>
                        <div class="tablenav">
                        <div class='tablenav-pages'>
                            <?php if($items > 0) {echo $p->show();}  // Echo out the list of paging. ?>
                        </div>
                    </div>
    <div style="clear: both; display: block; padding: 10px 0; text-align:center;">If you find this plugin useful, please contribute to enable its continued development!<br />
<p align="center">
<!--New Button for wpeventregister.com-->
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="4G8G3YUK9QEDA">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
</div>
</div>
 <?php $company_options = get_option('evr_company_settings');?>
<?php
$sql = "SELECT * FROM ". get_option('evr_event') ." ORDER BY date(start_date) DESC ".$limit;
                    		$result = mysql_query ($sql);
                            if ($items > 0 ) {
                    		while ($row = mysql_fetch_assoc ($result)){  
                            $event_id       = $row['id'];
                    	    $event_name = stripslashes($row['event_name']);
        					$event_identifier = stripslashes($row['event_identifier']);
        					$display_desc = $row['display_desc'];  // Y or N
                            $event_desc = stripslashes($row['event_desc']);
                            $event_category = unserialize($row['category_id']);
        					$reg_limit = $row['reg_limit'];
        					$event_location = $row['event_location'];
                            $event_address = $row['event_address'];
                            $event_city = $row['event_city'];
                            $event_state =$row['event_state'];
                            $event_postal=$row['event_postal'];
                            $google_map = $row['google_map'];  // Y or N
                            $start_month = $row['start_month'];
        					$start_day = $row['start_day'];
        					$start_year = $row['start_year'];
                            $end_month = $row['end_month'];
        					$end_day = $row['end_day'];
        					$end_year = $row['end_year'];
                            $start_time = $row['start_time'];
        					$end_time = $row['end_time'];
                            $allow_checks = $row['allow_checks'];
                            $outside_reg = $row['outside_reg'];  // Yor N
                            $external_site = $row['external_site'];
                            $reg_form_defaults = unserialize($row['reg_form_defaults']);
                            $more_info = $row['more_info'];
        					$image_link = $row['image_link'];
        					$header_image = $row['header_image'];
                            //$event_cost = $row['event_cost'];
                            $allow_checks = $row['allow_checks'];
        					$is_active = $row['is_active'];
        					$send_mail = $row['send_mail'];  // Y or N
        					$conf_mail = stripslashes($row['conf_mail']);
        					$start_date = $row['start_date'];
                            $end_date = $row['end_date'];
                            //set reg limit if not set
                            if ($reg_limit == ''){$reg_limit = 999;} 
                            $sql2= "SELECT * FROM " . get_option('evr_attendee') . " WHERE event_id='$event_id'";
                             $result2 = mysql_query($sql2);
            			     $num = mysql_num_rows($result2);
                             $number_attendees = $num;
            				if ($number_attendees == '' || $number_attendees == 0){
            					$number_attendees = '0';
            				}
            				if ($reg_limit == "" || $reg_limit == " "){
            					$reg_limit = "Unlimited";}
                               $available_spaces = $reg_limit;
            		           $exp_date = $end_date;
                               $todays_date = date("Y-m-d");
                               $today = strtotime($todays_date);
                               $expiration_date = strtotime($exp_date);
                             if ($expiration_date <= $today){
            					$active_event = '<span style="color: #F00; font-weight:bold;">'.__('EXPIRED EVENT','evr_language').'</span>';
            				} else{
            					$active_event = '<span style="color: #090; font-weight:bold;">'.__('ACTIVE EVENT','evr_language').'</span>';
            				}   
                            //div for popup goes here.
                            include "evr_event_popup_pop.php";         
                            }}
?>
</div>
<?php
}
?>