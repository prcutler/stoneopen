<?php
function evr_admin_view_attendee(){
    global $wpdb;
    //$event_id = $_REQUEST['event_id'];
    (is_numeric($_REQUEST['event_id'])) ? $event_id = $_REQUEST['event_id'] : $event_id = "0";
    $event = $wpdb->get_row('SELECT * FROM ' . get_option('evr_event') . ' WHERE id = ' . $event_id);
    $reg_form_defaults = unserialize($event->reg_form_defaults);
    if ($reg_form_defaults !=""){
                            if (in_array("Address", $reg_form_defaults)) {$inc_address = "Y";}
                            if (in_array("City", $reg_form_defaults)) {$inc_city = "Y";}
                            if (in_array("State", $reg_form_defaults)) {$inc_state = "Y";}
                            if (in_array("Zip", $reg_form_defaults)) {$inc_zip = "Y";}
                            if (in_array("Phone", $reg_form_defaults)) {$inc_phone = "Y";}
                            }
    $event_category = unserialize($event->event_category);
   /* if ($event->start_date <= date('Y-m-d')){
            					$active_event = '<span style="color: #F00; font-weight:bold;">EXPIRED EVENT</span>';
            				} else{
            					$active_event = '<span style="color: #090; font-weight:bold;">ACTIVE EVENT</span>';
            				} */
                                $exp_date = $event->end_date;
                               $todays_date = date("Y-m-d");
                               $today = strtotime($todays_date);
                               $expiration_date = strtotime($exp_date);
                             if ($expiration_date <= $today){
            					$active_event = '<span style="color: #F00; font-weight:bold;">'.__('EXPIRED','evr_language').'</span>';
            				} else{
            					$active_event = '<span style="color: #090; font-weight:bold;">'.__('ACTIVE','evr_language').'</span>';
            				}  
?>    
<div class="wrap">
<h2><a href="http://www.wpeventregister.com"><img src="<?php echo EVR_PLUGINFULLURL ?>images/evr_icon.png" alt="Event Registration for Wordpress" /></a></h2>
<h2><?php _e('Attendee Management','evr_language');?><?php echo " - ";?><?php echo stripslashes($event->event_name); ?></h2> <span>
                              <form name="form" method="post" action="<?php echo $_SERVER["REQUEST_URI"]?>">
                                <input type="hidden" name="action" value="add_attendee"/>
                                <input type="hidden" name="event_id" value="<?php echo $event->id;?>">
                                <input class="button-primary" type="submit" name="Add Attendees" value="<?php _e('Add Attendee','evr_language');?>" />
                              </form>
   <form name="form" method="post" action="admin.php?page=attendee">
                                <input type="hidden" name="action" value=""/>
                                <input class="button-primary" type="submit" name="Different Event" value="<?php _e('Select Another Event','evr_language');?>" />
                              </form></span>
    <div id="dashboard-widgets-wrap">
        <div id="dashboard-widgets" class="metabox-holder">
        	<div class='postbox-container' style='width:auto;'>
                <div id='normal-sortables' class='meta-box-sortables'>
                    <div id="dashboard_right_now" class="postbox " >
                        <h3 class='hndle'><span><?php _e('View Attendee:','evr_language');?><a href="#?w=700" rel="popup<?php echo $event->id;?>" class="poplight">   <?php echo stripslashes($event->event_name); ?></a></span></h3>
                         <div class="inside">
                            <div class="padding">        
                            <table class="widefat">
                                <thead>
                                <tr><th><?php _e('Type','evr_language');?></th><th><?php _e('# People','evr_language');?></th><th><?php _e('Registered Name','evr_language');?> </th><th><?php _e('Attendees','evr_language');?></th><th><?php _e('Email','evr_language');?></th><th><?php _e('Phone','evr_language');?></th><th><?php _e('Action','evr_language');?></th></tr>
                                </thead>
                                <tfoot>
                                <tr><th><?php _e('Type','evr_language');?></th><th><?php _e('# People','evr_language');?></th><th><?php _e('Registered Name','evr_language');?> </th><th><?php _e('Attendees','evr_language');?></th><th><?php _e('Email','evr_language');?></th><th><?php _e('Phone','evr_language');?></th><th><?php _e('Action','evr_language');?></th></tr>
                                </tfoot>
                                <tbody>
<?php
$rows = $wpdb->get_results('SELECT * FROM ' . get_option('evr_attendee') . ' WHERE event_id = ' . $event_id);
if ($rows){
    foreach ($rows as $attendee){
        echo "<tr><td>".$attendee->reg_type."</td><td>".$attendee->quantity."</td><td align='left'>" . $attendee->lname . ", " . $attendee->fname . " ( ID: ".$attendee->id.")</td><td>";
        /*if ($attendee->attendees ==""){echo "<font color='red'>Please Update This Attendee</font>";}*/
        /* Glenn Tate: */
        if ($attendee->attendees =="" || $attendee->attendees =="N;"){echo "<font color='red'>Please Update This Attendee</font>";}
        else {$attendee_array = unserialize($attendee->attendees);
        foreach($attendee_array as $ma) 
            echo $ma["first_name"]." ".$ma["last_name"]."<br/>";}
        echo "</td><td>" . $attendee->email . "</td><td>" . $attendee->phone . "</td>";
        echo "<td>";
        ?>
                                <a href="admin.php?page=attendee&action=edit_attendee&event_id=<?php echo $event->id;?>&attendee_id=<?php echo $attendee->id; ?>"><?php _e('EDIT','evr_language');?></a>  |
                                <a href="admin.php?page=attendee&action=delete_attendee&event_id=<?php echo $event->id;?>&attendee_id=<?php echo $attendee->id; ?>" 
                                ONCLICK="return confirm('Are you sure you want to delete attendee <?php echo $attendee->fname." ".$attendee->lname;?>?')"><?php _e('DELETE','evr_language');?></a></td> </tr>
<?php   } } 
?>
                            </table>
                            <br />
                            <div style="clear:both;"></div>
                            <?php evr_excel_export($event_id);?>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>            
    </div>
</div>
<?php
}
function evr_excel_export($event_id){
    global $wpdb;
    $today = date("Y-m-d_Hi",time()); 
$info = array(
        "dbuser" => $wpdb->dbuser, 
        "dbpass" => $wpdb->dbpassword, 
        "db" =>$wpdb->dbname, 
        "host" =>$wpdb->dbhost
        );
$tables = array(
        "evr_answer" => get_option('evr_answer'),
        "evr_question" => get_option('evr_question'),
        "evr_event" => get_option('evr_event'),
        "evr_attendee" => get_option('evr_attendee'),
        "evr_payment" => get_option ('evr_payment')
        );
?>
<div style="float:left; margin-right:10px;">
    <form method="POST" action="<?php echo EVR_PLUGINFULLURL . 'evr_admin_export.php';?>">
                                <input type="hidden" name="id" value="<?php echo $event_id; ?>" /> 
                               <input type="hidden" name="export" value="report" /> 
                               <input type="hidden" name="action" value="excel" />
                               <input type="hidden" name="key" value="5678" /> 
                               <input type="hidden" name="info" value="<?php echo base64_encode(serialize($info)); ?>" />
                               <input type="hidden" name="tables" value="<?php echo base64_encode(serialize($tables)); ?>" />
                               <input class="xls_btn" type="submit" value="Export Details - Excel"/>
                                </form>
    </div>
<div style="float:left;">
    <form method="POST" action="<?php echo EVR_PLUGINFULLURL . 'evr_admin_export.php';?>">
                                <input type="hidden" name="id" value="<?php echo $event_id; ?>" /> 
                               <input type="hidden" name="export" value="report" /> 
                               <input type="hidden" name="action" value="csv" />
                               <input type="hidden" name="key" value="5678" /> 
                               <input type="hidden" name="info" value="<?php echo base64_encode(serialize($info)); ?>" />
                               <input type="hidden" name="tables" value="<?php echo base64_encode(serialize($tables)); ?>" />
                               <input class="csv_btn" type="submit" value="Export Details - CSV"/>
                                </form>
    </div>
<?php
}
function evr_payment_export($event_id){
    global $wpdb;
    $today = date("Y-m-d_Hi",time()); 
$info = array(
        "dbuser" => $wpdb->dbuser, 
        "dbpass" => $wpdb->dbpassword, 
        "db" =>$wpdb->dbname, 
        "host" =>$wpdb->dbhost
        );
$tables = array(
        "evr_answer" => get_option('evr_answer'),
        "evr_question" => get_option('evr_question'),
        "evr_event" => get_option('evr_event'),
        "evr_attendee" => get_option('evr_attendee'),
        "evr_payment" => get_option ('evr_payment')
        );
?>
<div style="display: inline-block;">
    <form method="POST" action="<?php echo EVR_PLUGINFULLURL . 'evr_admin_export.php';?>">
                                <input type="hidden" name="id" value="<?php echo $event_id; ?>" /> 
                               <input type="hidden" name="export" value="report" /> 
                               <input type="hidden" name="action" value="payment" />
                               <input type="hidden" name="key" value="5678" /> 
                               <input type="hidden" name="info" value="<?php echo base64_encode(serialize($info)); ?>" />
                               <input type="hidden" name="tables" value="<?php echo base64_encode(serialize($tables)); ?>" />
                               <input type="submit" value="Export Payments - Excel"/>
                                </form>
    </div>
<?php
}	
?>