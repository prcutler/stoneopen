<?php
//remved date filter on pricing in attendee admin

function evr_admin_add_attendee(){
    global $wpdb;
    
    //$event_id = $_REQUEST['event_id'];
    (is_numeric($_REQUEST['event_id'])) ? $event_id = $_REQUEST['event_id'] : $event_id = "0";
    
    $sql = "SELECT * FROM ". get_option('evr_event') ." WHERE id = $event_id";
    $result = mysql_query ($sql);
    while ($row = mysql_fetch_assoc ($result)){  
        $reg_form_defaults = unserialize($row['reg_form_defaults']);
        if ($reg_form_defaults !=""){
            if (in_array("Address", $reg_form_defaults)) {$inc_address = "Y";}
            if (in_array("City", $reg_form_defaults)) {$inc_city = "Y";}
            if (in_array("State", $reg_form_defaults)) {$inc_state = "Y";}
            if (in_array("Zip", $reg_form_defaults)) {$inc_zip = "Y";}
            if (in_array("Phone", $reg_form_defaults)) {$inc_phone = "Y";}
        }
        $use_coupon = $row['use_coupon'];
        $reg_limit = $row['reg_limit'];
        $event_name = $row['event_name'];
    }
?>
<div class="wrap">
<h2><a href="http://www.wpeventregister.com"><img src="<?php echo EVR_PLUGINFULLURL ?>images/evr_icon.png" alt="Event Registration for Wordpress" /></a></h2>
<h2><?php _e('Attendee Management','evr_language');?></h2>
    <div id="dashboard-widgets-wrap">
        <div id="dashboard-widgets" class="metabox-holder">
        	<div class='postbox-container' style='width:65%;'>
                <div id='normal-sortables' class='meta-box-sortables'>
                    <div id="dashboard_right_now" class="postbox " >
                         
                        <h3 class='hndle'><span><?php _e('Add Attendee:','evr_language');?><?php echo "  ".$event_name;?></span></h3>
                         <div class="inside">
                            <div class="padding">        
                            <form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" onSubmit="return validateForm(this)">
                            <ul>
                                <li><label for="fname"><?php _e('First Name','evr_language');?></label><input id="fname" name="fname" /></li>
                                <li><label for="lname"><?php _e('Last Name','evr_language');?></label><input id="lname" name="lname" /></li>
                                <li><label for="email" ><?php _e('Email Address','evr_language');?></label><input id="email" name="email" /></li>
                                    <?php if ($inc_phone == "Y") { ?>
                                <li><label for="phone" ><?php _e('Phone Number','evr_language');?></label><input id="phone" name="phone" /></li>
                                    <?php } ?>
                                    <?php if ($inc_address == "Y") { ?> 
                                <li><label for="address"><?php _e('Address','evr_language');?></label><input id="address" name="address" /></li>
                                    <?php } ?>
                                    <?php if ($inc_city == "Y") { ?> 
                                <li><label for="city"><?php _e('City','evr_language');?></label><input id="city" name="city" /></li>
                                    <?php } ?>
                                    <?php if ($inc_state == "Y") { ?> 
                                <li><label for="state"><?php _e('State/Province','evr_language');?></label><input id="state" name="state" /></li>
                                    <?php } ?> 
                                    <?php if ($inc_zip == "Y") { ?> 
                                <li><label for="zip"><?php _e('Zip/Postal Code','evr_language');?></label><input id="zip" name="zip" /></li>
                                    <?php } ?>       
                                    <?php if ($use_coupon == "Y") { ?>
                                <li><label for="coupon"><?php _e('Enter coupon code for discount off primary registration?','evr_language');?></label><input id="coupon" name="coupon" /></li>
                                    <?php } ?>
                                    <?php     
                                    $questions = $wpdb->get_results("SELECT * from ".get_option('evr_question')." where event_id = '$event_id' order by sequence");
                                    if ($questions) {
                                        foreach ($questions as $question) { ?>
                                            <li><label><?php
                                            echo $question->question;
                                             ?>
                                             </label>
                                             <?php
                                            evr_form_build($question);
                                            ?></li>
                                            <?php }
                                            }
                                    ?>
                            </ul>
                            <?php 	
                            
                            $num = 0;                              
                            $sql2= "SELECT SUM(quantity) FROM " . get_option('evr_attendee') . " WHERE event_id='$event_id'";
                            
                            $attendee_count  = $wpdb->get_var($sql2);
                            
                            
                            If ($attendee_count >= 1) {$num = $attendee_count;}
                            $available = $reg_limit - $num;
                            //echo "count is ". $attendee_count." !";
                                                                
                            if ($available >= "1"){ 
    
  /*  
    $sql2= "SELECT SUM(quantity) FROM " . get_option('evr_attendee') . " WHERE event_id='$event_id'";
                            		$result2 = mysql_query($sql2);
                                            $num = 0;   
                            		while($row = mysql_fetch_array($result2)){$num =  $row['SUM(quantity)'];};
                                    
                                    $available = $reg_limit - $num;
                                    
                                    if ($available > "1"){ */
                                        
                                        ?>                
                            <hr />
                            <h2 align="center">REGISTRATION FEES</h2><br />
                             <?php
                                
                                $open_seats = $available;
                                $curdate = date("Y-m-d");
                                $sql = "SELECT * FROM " . get_option('evr_cost') . " WHERE event_id = " . $event_id. " ORDER BY sequence ASC";
                                $result = mysql_query ( $sql );
                            	while ($row = mysql_fetch_assoc ($result)){
                            	        $item_id          = $row['id'];
                                        $item_sequence    = $row['sequence'];
                            			$event_id         = $row['event_id'];
                                        $item_title       = $row['item_title'];
                                        $item_description = $row['item_description']; 
                                        $item_cat         = $row['item_cat'];
                                        $item_limit       = $row['item_limit'];
                                        $item_price       = $row['item_price'];
                                        $free_item        = $row['free_item'];
                                        $item_start_date  = $row['item_available_start_date'];
                                        $item_end_date    = $row['item_available_end_date'];
                                        $item_custom_cur  = $row['item_custom_cur'];
                                   
                                    //if (($item_start_date >= $curdate) && ($item_end_date <= $curdate)) {
                                    // Remove date filter if((evr_greaterDate($curdate,$item_start_date))&& (evr_greaterDate($item_end_date,$curdate))){
                               
                            ?>
                            <input type="hidden" name="reg_type" value="RGLR"/>
                            <p><select name="PROD_<?php echo $event_id . "-" . $item_id . "_" . $item_price; ?>" id = "PROD_<?php echo
                                    $event_id . "-" . $item_id . "_" . $item_price; ?>" onChange="CalculateTotal(this.form)"  >
                            <option value="0">0</option>
                            <?php
                            if ($item_cat == "REG"){
                             if ($ticket_limit != ""){
                                if ($available >= $item_limit){$available = $item_limit;}
                                }
                             for($i=1; $i<$available+1; $i++) { ?>
                                    <option value="<?php echo($i); ?>"><?php echo($i); ?></option>
                            	<?php } }
                            if ($item_cat != "REG"){
                            $num_select = "10";    
                            if ($ticket_limit != ""){
                                $num_select = $item_limit;}
                                
                             for($i=1; $i<$num_select+1; $i++) { ?>
                                    <option value="<?php echo($i); ?>"><?php echo($i); ?></option>
                            	<?php } } 
                            ?>
                            </select>
                            <?php echo $item_title . "    " . $item_custom_cur . " " . $item_price; ?></p>
                            <?php 
                            // }
                            
                             }?>
                            <br /><b>Registration TOTAL  <input type="text" name="total" id="total" size="10" value="0.00" onFocus="this.form.elements[0].focus()"/></b>
                            <br />
                            <?php
                               
                            
                            } else {
                                echo '<hr><br><b><font color="red">';
                                _e('This event has reached registration capacity.','evr_language');
                                echo "<br>";
                                _e('Please provide your information to be placed on the waiting list.','evr_language');
                                echo '</b></font>';
                                ?>
                                <input type="hidden" name="reg_type" value="WAIT" />
                                <?php   
                            }
                            ?>
                            
                            <script language="JavaScript" type="text/javascript">
                            <!--
                            
                            /* This script is Copyright (c) Paul McFedries and 
                            Logophilia Limited (http://www.mcfedries.com/).
                            Permission is granted to use this script as long as 
                            this Copyright notice remains in place.*/
                            
                            function CalculateTotal(frm) {
                                var order_total = 0
                            
                                // Run through all the form fields
                                for (var i=0; i < frm.elements.length; ++i) {
                            
                                    // Get the current field
                                    form_field = frm.elements[i]
                            
                                    // Get the field's name
                                    form_name = form_field.name
                            
                                    // Is it a "product" field?
                                    if (form_name.substring(0,4) == "PROD") {
                            
                                        // If so, extract the price from the name
                                        item_price = parseFloat(form_name.substring(form_name.lastIndexOf("_") + 1))
                            
                                        // Get the quantity
                                        item_quantity = parseInt(form_field.value)
                            
                                        // Update the order total
                                        if (item_quantity >= 0) {
                                            order_total += item_quantity * item_price
                                        }
                                    }
                                }
                            
                                // Display the total rounded to two decimal places
                                frm.total.value = round_decimals(order_total, 2)
                            }
                            function round_decimals(original_number, decimals) {
                                var result1 = original_number * Math.pow(10, decimals)
                                var result2 = Math.round(result1)
                                var result3 = result2 / Math.pow(10, decimals)
                                return pad_with_zeros(result3, decimals)
                            }
                            
                            function pad_with_zeros(rounded_value, decimal_places) {
                            
                                // Convert the number to a string
                                var value_string = rounded_value.toString()
                                
                                // Locate the decimal point
                                var decimal_location = value_string.indexOf(".")
                            
                                // Is there a decimal point?
                                if (decimal_location == -1) {
                                    
                                    // If no, then all decimal places will be padded with 0s
                                    decimal_part_length = 0
                                    
                                    // If decimal_places is greater than zero, tack on a decimal point
                                    value_string += decimal_places > 0 ? "." : ""
                                }
                                else {
                            
                                    // If yes, then only the extra decimal places will be padded with 0s
                                    decimal_part_length = value_string.length - decimal_location - 1
                                }
                                
                                // Calculate the number of decimal places that need to be padded with 0s
                                var pad_total = decimal_places - decimal_part_length
                                
                                if (pad_total > 0) {
                                    
                                    // Pad the string with 0s
                                    for (var counter = 1; counter <= pad_total; counter++) 
                                        value_string += "0"
                                    }
                                return value_string
                            }
                            
                            //-->
                            </script>
                            
                            <hr />
                            
                            <?php if ($ER_org_data['captcha'] == 'Y') { ?>
                            <p>Enter the security code as it is shown (required):<script type="text/javascript">sjcap("altTextField");</script>
                            		<noscript><p>[This resource requires a Javascript enabled browser.]</p></noscript>
                            <?php } ?>
                            
                            <input type="hidden" name="action" value="post_attendee"/> 
                            <input type="hidden" name="event_id" value="<?php echo $event_id; ?>"/>
                            <p align="center"><input type="submit" name="Submit" value="<?php _e('SUBMIT','evr_language');?>"/> </p>
                            </form>
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
?>