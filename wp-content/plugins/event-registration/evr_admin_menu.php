<?php
/**
 * @author David Fleming
 * @copyright 2010
 */

//function for the splash page of the plugin
function evr_splash(){
    global $wpdb, $sponsorship_promo;
?>
<div class="wrap"><br />
<a href="http://www.wpeventregister.com"><img src="<?php echo EVR_PLUGINFULLURL ?>images/evr_icon.png" alt="Event Registration for Wordpress" /></a>
<br />
<br />
<div class="evr_plugin">
<?php //Sponsor Add Goes here  ?>
<div class="content">
    	<div class="evr_content_third">
    		<h3>Event Registration Installation Info</h3>
    		<div class="inside">
    		<div class="padding">
                    <table class="table">
                        <tr><th valign="top" align="left" scope="row"><?php _e('Attendee Table Name:','evr_language');?></th>
                        <td><?php echo get_option('evr_attendee'); ?></td></tr>
                        <tr></tr>
                        <tr><th scope="row" valign="top" align="left"><?php _e('Attendee Table Version:','evr_language');?></th>
                        <td><?php echo get_option('evr_attendee_version'); ?></td></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr><th scope="row" valign="top" align="left"><?php _e('Event Table Name:','evr_language');?></th>
                        <td><?php echo get_option('evr_event'); ?></td></tr>
                        <tr></tr>
                        <tr><th scope="row" valign="top" align="left"><?php _e('Event Table Version:','evr_language');?></th>
                        <td><?php echo get_option('evr_event_version'); ?></td></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr><th scope="row" valign="top" align="left"><?php _e('Question Table Name:','evr_language');?></th>
                        <td><?php echo get_option('evr_question'); ?></td></tr>
                        <tr></tr>
                        <tr><th scope="row" valign="top" align="left"><?php _e('Question Table Version:','evr_language');?></th>
                        <td><?php echo get_option('evr_question_version'); ?></td></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr><th scope="row" valign="top" align="left"><?php _e('Answer Table Name:','evr_language');?></th>
                        <td><?php echo get_option('evr_answer'); ?></td></tr>
                        <tr></tr>
                        <tr><th scope="row" valign="top" align="left"><?php _e('Answer Table Version:','evr_language');?></th>
                        <td><?php echo get_option('evr_answer_version'); ?></td></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr><th scope="row" valign="top" align="left"><?php _e('Category Table Name:','evr_language');?></th>
                        <td><?php echo get_option('evr_category'); ?></td></tr>
                        <tr></tr>
                        <tr><th scope="row" valign="top" align="left"><?php _e('Category Table Version:','evr_language');?></th>
                        <td><?php echo get_option('evr_category_version'); ?></td></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr><th scope="row" valign="top" align="left"><?php _e('Cost Table Name:','evr_language');?></th>
                        <td><?php echo get_option('evr_cost'); ?></td></tr>
                        <tr></tr>
                        <tr><th scope="row" valign="top" align="left"><?php _e('Cost Table Version:','evr_language');?></th>
                        <td><?php echo get_option('evr_cost_version'); ?></td></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr><th scope="row" valign="top" align="left"><?php _e('Payment Table Name:','evr_language');?></th>
                        <td><?php echo get_option('evr_payment'); ?></td></tr>
                        <tr></tr>
                        <tr><th scope="row" valign="top" align="left"><?php _e('Payment Table Version:','evr_language');?></th>
                        <td><?php echo get_option('evr_payment_version'); ?></td></tr>
                    </table>
                    </div>
                </div>  		
            </div>
    	<div class="evr_content_third" style="margin-right:0;">
    		<h3>Links &amp; Documentation</h3>
    		<div class="inside">
    			<p>Need help? FAQ, Usage instructions and other notes can be found on the WordPress.org plugin page.</p>    			<ul>
    				<li><a href="http://wordpress.org/extend/plugins/event-registration/">Download Event Registration on WordPress.org</a></li>
    				<li><a href="http://wpeventregister.com/downloads">Download Event Registration Guide</a></li>
    				<li><a href="http://www.wpeventregister.com">View Online Documentation</a></li>
    			</ul>            
    		</div>
    	</div> 
    	        <div class="evr_content_third" style="margin-right:0; float:right;">
    		<h3>Support Event Registration</h3>
    		<div class="inside">
    			<div style="clear: both; display: block; padding: 10px 0; text-align:center;"><br />If you find this plugin useful,<br /> please contribute to enable its continued development!<br /><br />
                    <p align="center">
                    <!--New Button for wpeventregister.com-->
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="4G8G3YUK9QEDA">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
                    </p>
    		    </div>
            </div>		
		  <div class="clear"></div>
	   </div>
</div>
 <hr />
 <div class="content">
     	<div class="evr_content_third">
            <div class="alert"><h3>ALERT</h3></div>
    		<div class="inside">
                    <?php 
                    global $table_message;
                    echo get_option('plugin_error');
                    if ($table_message !=''){
                        ?>
                        <div id="message" class="updated fade"><?php echo $table_message;?></div>
                        <?php
                    }
                    if (get_option('evr_was_upgraded')== "Y") {?>
                    <div id="message" class="error"><p><strong><?php 
                    _e('You upgraded from a previous version of Event Registration.  All existing data has been imported.  Please verify data before deleting old tables!','evr_language');?></strong></p>
                    </div>
                    <a href="admin.php?page=purge">Remove Old Data Tables</a>
                    <?php }?>
   			</div>		
    		<div class="clear"></div>
        </div>
       <div class="evr_content_third" style="margin-right:0;">
            <h3>Event Registration News</h3>
    		  <div class="inside">
            <?php
                    // import rss feed
                    if(function_exists('fetch_feed')) {
                    	// fetch feed items
                    	$rss = fetch_feed('http://wpeventregister.com/feed');
                    	if(!is_wp_error($rss)) : // error check
                    		$maxitems = $rss->get_item_quantity(10); // number of items
                    		$rss_items = $rss->get_items(0, $maxitems);
                    	endif;
                    	// display feed items ?>
                    	<dl>
                    	<?php if($maxitems == 0) echo '<dt>Feed not available.</dt>'; // if empty
                    	else foreach ($rss_items as $item) : ?>
                    		<dt>
                    			<a href="<?php echo $item->get_permalink(); ?>" 
                    			title="<?php echo $item->get_date('j F Y @ g:i a'); ?>">
                    			<?php echo $item->get_title(); ?>
                    			</a>
                    		</dt>
                    		<dd>
                    			<?php echo $item->get_description(); ?>
                    		</dd>
                    	<?php endforeach; ?>
                    	</dl>
                    <?php } ?>
    	       </div>		
		  <div class="clear"></div>
        </div>
       <div class="evr_content_third" style="margin-right:0; float:right;">
    		<h3>From the Forum</h3>
    		<div class="inside">
                 <?php
                    // import rss feed
                    if(function_exists('fetch_feed')) {
                    	// fetch feed items
                    	$rss = fetch_feed('http://wpeventregister.com/forum/index.php?type=rss;action=.xml;limit=10');
                    	if(!is_wp_error($rss)) : // error check
                    		$maxitems = $rss->get_item_quantity(10); // number of items
                    		$rss_items = $rss->get_items(0, $maxitems);
                    	endif;
                    	// display feed items ?>
                    	<dl>
                    	<?php if($maxitems == 0) echo '<dt>Feed not available.</dt>'; // if empty
                    	else foreach ($rss_items as $item) : ?>
                    		<dt>
                    			<a href="<?php echo $item->get_permalink(); ?>" 
                    			title="<?php echo $item->get_date('j F Y @ g:i a'); ?>">
                    			<?php echo $item->get_title(); ?>
                    			</a>
                    		</dt>
                    		<dd>
                    			<?php echo $item->get_description(); ?>
                    		</dd>
                    	<?php endforeach; ?>
                    	</dl>
                    <?php } ?>
    		</div>
    	</div> 
    </div>     	
<div class="content">
    	<div class="evr_content_third">
<?php if((get_option('evr_dontshowpopup')== "Y")){$nag = EVR_PLUGINFULLURL."images/yes.png";} else {$nag = EVR_PLUGINFULLURL."images/no.png";} ?>
<?php if((get_option('evr_coordinator_active')== "Y")){$coordindicator = EVR_PLUGINFULLURL."images/yes.png";} else {$coordindicator = EVR_PLUGINFULLURL."images/no.png";} ?>
<?php if((get_option('evr_cal_active')== "Y")){$calendarindicator = EVR_PLUGINFULLURL."images/yes.png";} else {$calendarindicator = EVR_PLUGINFULLURL."images/no.png";} ?>
<?php if((get_option('evr_location_active')== "Y")){$locationindicator = EVR_PLUGINFULLURL."images/yes.png";} else {$locationindicator = EVR_PLUGINFULLURL."images/no.png";} ?>
<?php if((get_option('evr_email_active')== "Y")){$emailindicator = EVR_PLUGINFULLURL."images/yes.png";} else {$emailindicator = EVR_PLUGINFULLURL."images/no.png";} ?>
<?php if((get_option('evr_survey_active')== "Y")){$surveyindicator = EVR_PLUGINFULLURL."images/yes.png";} else {$surveyindicator = EVR_PLUGINFULLURL."images/no.png";} ?>
    		<h3>Event Registration Module Info</h3>
    		<div class="inside">
    		<div class="padding">
                    <table class="table">
                        <tr><td><img  src="<?php echo $nag; ?>" height="25px" width="25px"/></td><td>Donated to Event Registration</td></tr>
                        <tr><td><img  src="<?php echo $coordindicator; ?>" height="25px" width="25px"/></td><td>Coordinator Module</td></tr>
                        <tr><td><img  src="<?php echo $calendarindicator; ?>" height="25px" width="25px"/></td><td>Calendar Module</td></tr>
                        <tr><td><img  src="<?php echo $locationindicator; ?>" height="25px" width="25px"/></div></td><td>Location Module</td></tr>
                        <tr><td><img  src="<?php echo $emailindicator; ?>" height="25px" width="25px"/></div></td><td>Email Module</td></tr>
                        <tr><td><img  src="<?php echo $surveyindicator; ?>" height="25px" width="25px"/></div></td><td>Survey Module</td></tr>
                    </table>
                    </div>
                </div>  		
            </div>
    	<div class="evr_content_third" style="margin-right:0;">
    		<h3>Support Our Sponsors</h3>
    		<div class="inside">
            </div>
    	</div> 
     <div class="evr_content_third" style="margin-right:0; float:right;">
    		<h3></h3>
    		<div class="inside">
            </div>		
		  <div class="clear"></div>
	   </div>
</div>
</div>
</div>
<?php 
}


function evr_xml2array($url, $get_attributes = 1, $priority = 'tag')
 {
     $contents = "";
     if (!function_exists('xml_parser_create'))
     {
         return array ();
     }
     $parser = xml_parser_create('');
     if (!($fp = @ fopen($url, 'rb')))
     {
         return array ();
     }
     while (!feof($fp))
     {
         $contents .= fread($fp, 8192);
     }
     fclose($fp);
     xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
     xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
     xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
     xml_parse_into_struct($parser, trim($contents), $xml_values);
     xml_parser_free($parser);
     if (!$xml_values)
         return; //Hmm...
     $xml_array = array ();
     $parents = array ();
     $opened_tags = array ();
     $arr = array ();
     $current = & $xml_array;
     $repeated_tag_index = array (); 
    foreach ($xml_values as $data)
     {
         unset ($attributes, $value);
         extract($data);
         $result = array ();
         $attributes_data = array ();
         if (isset ($value))
         {
             if ($priority == 'tag')
                 $result = $value;
             else
                 $result['value'] = $value;
         }
         if (isset ($attributes) and $get_attributes)
         {
             foreach ($attributes as $attr => $val)
             {
                 if ($priority == 'tag')
                     $attributes_data[$attr] = $val;
                 else
                     $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
             }
         }
         if ($type == "open")
         { 
            $parent[$level -1] = & $current;
             if (!is_array($current) or (!in_array($tag, array_keys($current))))
             {
                 $current[$tag] = $result;
                 if ($attributes_data)
                     $current[$tag . '_attr'] = $attributes_data;
                 $repeated_tag_index[$tag . '_' . $level] = 1;
                 $current = & $current[$tag];
             }
             else
             {
                 if (isset ($current[$tag][0]))
                 {
                     $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                     $repeated_tag_index[$tag . '_' . $level]++;
                 }
                 else
                 { 
                    $current[$tag] = array (
                         $current[$tag],
                         $result
                     ); 
                    $repeated_tag_index[$tag . '_' . $level] = 2;
                     if (isset ($current[$tag . '_attr']))
                     {
                         $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                         unset ($current[$tag . '_attr']);
                     }
                 }
                 $last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
                 $current = & $current[$tag][$last_item_index];
             }
         }
         elseif ($type == "complete")
         {
             if (!isset ($current[$tag]))
             {
                 $current[$tag] = $result;
                 $repeated_tag_index[$tag . '_' . $level] = 1;
                 if ($priority == 'tag' and $attributes_data)
                     $current[$tag . '_attr'] = $attributes_data;
             }
             else
             {
                 if (isset ($current[$tag][0]) and is_array($current[$tag]))
                 {
                     $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                     if ($priority == 'tag' and $get_attributes and $attributes_data)
                     {
                         $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                     }
                     $repeated_tag_index[$tag . '_' . $level]++;
                 }
                 else
                 {
                     $current[$tag] = array (
                         $current[$tag],
                         $result
                     ); 
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                     if ($priority == 'tag' and $get_attributes)
                     {
                         if (isset ($current[$tag . '_attr']))
                         { 
                            $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                             unset ($current[$tag . '_attr']);
                         }
                         if ($attributes_data)
                         {
                             $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                         }
                     }
                     $repeated_tag_index[$tag . '_' . $level]++; //0 and 1 index is already taken
                 }
             }
         }
         elseif ($type == 'close')
         {
             $current = & $parent[$level -1];
         }
     }
     return ($xml_array);
}
?>