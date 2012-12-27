<?php

/**
 * @author David Fleming
 * @copyright 2011
 */
?>
<!-- Main Page Event PopUp Start -->
<div id="popup<?php echo $event_id;?>" style="display:none">
<!--<div id="popup<?php echo $event_id;?>" class="poplight"> -->

<div id="evr_pop_top"><span style="float:center;"><?php  if ($header_image != ""){ ?> <img class="evr_pop_hdr_img" src="<?php echo $header_image;?>" /><?php } ?></span></div>
<div id="evr_pop_title"><span style="float:left;"><h3><?php echo $event_name;?></h3></span>
                        <span style="float:right;"><a href="<?php echo EVR_PLUGINFULLURL."evr_ics.php";?>?event_id=<?php echo $event_id;?>">
                        <img src="<?php echo EVR_PLUGINFULLURL;?>images/ical-logo.jpg" /></a></span>
                        </div>
<div id="evr_pop_date_row" class="evr_pop_date"><?php echo "<br/>".date($evr_date_format,strtotime($start_date))."  -  ";
                        if ($end_date != $start_date) {echo date($evr_date_format,strtotime($end_date));}
                        echo __('Time: ','evr_language')." ".$start_time." - ".$end_time;?>
                        </div> 
<div class="evr_spacer"></div> 
<div id="evr_pop_body" STYLE="text-align: justify;white-space:pre-wrap;"><?php echo html_entity_decode($event_desc);?></div>
<div id="evr_pop_image"><?php if ($image_link !=""){?><img class="evr_pop_img" src="<?php echo $image_link;?>" alt="Thumbnail Image" /><?php } else { ?>
                        <img class="evr_pop_img" src="<?php echo EVR_PLUGINFULLURL;?>images/event_icon.png" />
                        <?php } ?>
                        </div>
                                              
<div class="evr_spacer"><hr /></div>  

<div id="evr_pop_venue"><div id="evr_pop_address"><b><u>Location</u></b><br/><br/>
                        <?php echo stripslashes($event_location);?><br />
                        <?php echo $event_address;?><br />
                        <?php echo $event_city.", ".$event_state." ".$event_postal;?><br />
                        </div>
<div id="evr_pop_map"><?php if ($google_map == "Y"){?>
                        <img border="0" src="http://maps.google.com/maps/api/staticmap?center=<?php echo $event_address.",".$event_city.",".$event_state;?>&zoom=14&size=280x180&maptype=roadmap&markers=size:mid|color:0xFFFF00|label:*|<?php echo $event_address.",".$event_city;?>&sensor=false" />
                        <?php } ?>
                        </div></div>		                          
<div id="evr_pop_price"><b><u><?php _e('Event Fees','evr_language');?>:</u></b><br /><br />
                        <?php
                        $curdate = date("Y-m-d");
                        $sql2 = "SELECT * FROM " . get_option('evr_cost') . " WHERE event_id = " . $event_id. " ORDER BY sequence ASC";
                        $result2 = mysql_query ( $sql2 );
                            while ($row2 = mysql_fetch_assoc ($result2)){
                                $item_id          = $row2['id'];
                                $item_sequence    = $row2['sequence'];
                                $event_id         = $row2['event_id'];
                                $item_title       = $row2['item_title'];
                                $item_description = $row2['item_description'];
                                $item_cat         = $row2['item_cat'];
                                $item_limit       = $row2['item_limit'];
                                $item_price       = $row2['item_price'];
                                $free_item        = $row2['free_item'];
                                $item_start_date  = $row2['item_available_start_date'];
                                $item_end_date    = $row2['item_available_end_date'];
                                $item_custom_cur  = $row2['item_custom_cur'];
                                if ($item_custom_cur == "GBP"){$item_custom_cur = "&pound;";}
                                if ($item_custom_cur == "USD"){$item_custom_cur = "$";}
                                echo $item_title.'   '.$item_custom_cur.' '.$item_price.'<br />';
                                } ?>
                        
                        </div><div class="evr_spacer"></div>
<div id="evr_pop_foot"><p align="center">

<?php if ($more_info !=""){ ?>
<input type="button" onClick="window.open('<?php echo $more_info;?>');" value='MORE INFO'/> 
<?php	} ?>

<?php if ($outside_reg == "Y"){ ?>
<input type="button" onClick="window.open('<?php echo $external_site;?>');" value='External Registration'/> 
<?php	}  else {?>
                        <input type="button" onClick="location.href='<?php echo evr_permalink($company_options['evr_page_id']);?>action=evregister&event_id=<?php echo $event_id;?>'" value='REGISTER'/> 
 <?php } ?>                       
                        
                        
                        </p></div>               		
                        
</div>
<!-- EventPopUpEnd -->	
