<?php

//function to re arrange items in the pricing disly
function evr_reorder_items(){
    ?>

<script type="text/javascript">
          jQuery(function ($)   
             {  
             $("#er_ticket_sortable").sortable({  
                    placeholder: 'ui-state-highlight',  
                   stop: function(i) {  
                       placeholder: 'ui-state-highlight'  
                       $.ajax({  
                            type: "GET",  
                            url: "admin.php?page=events&action=post_reorder_item", 
                             
                            data: $("#er_ticket_sortable").sortable("serialize")});  
                   }  
                });  
        
                $("#er_ticket_sortable").disableSelection();  
             });  
        
        </script>
<link rel="stylesheet" type="text/css" href="<?php echo EVR_PLUGINFULLURL;?>js/jquery.ui.all.css"/>        
<style type="text/css"> 
#er_ticket_sortable { 
list-style-type: none; 
margin: 0; 
padding: 0; 
width: 90%; 
} 
#er_ticket_sortable li { 
margin: 0 3px 3px 3px; 
padding: 0.4em; 
padding-left: 1.5em; 
font-size: .8em; 
height: 30px; 
} 
#er_ticket_sortable li span { 
position: absolute; 
margin-left: -1.3em; 
 } 
 </style> 
<?php
    $events_cost_tbl = get_option ( 'evr_cost' );
    //$event_id = $_REQUEST['event_id'];
    (is_numeric($_REQUEST['event_id'])) ? $event_id = $_REQUEST['event_id'] : $event_id = "0";
    $sql = "SELECT * FROM ". get_option('evr_event') ." WHERE id = $event_id";
                    		$result = mysql_query ($sql);
                                    		while ($row = mysql_fetch_assoc ($result)){  
                         
                            $event_id       = $row['id'];
            				$event_name     = $row['event_name'];
            				$event_location = $row['event_location'];
                            $event_address  = $row['event_address'];
                            $event_city     = $row['event_city'];
                            $event_postal   = $row['event_postal'];
                            $reg_limit      = $row['reg_limit'];
                    		$start_time     = $row['start_time'];
                    		$end_time       = $row['end_time'];
                    		$conf_mail      = $row['conf_mail'];
                            $custom_mail    = $row['custom_mail'];
                    		$start_date     = $row['start_date'];
                    		$end_date       = $row['end_date'];
    }
    
    ?>
<div class="wrap">
<h2><a href="http://www.wpeventregister.com"><img src="<?php echo EVR_PLUGINFULLURL ?>images/evr_icon.png" alt="Event Registration for Wordpress" /></a></h2>
<h2><?php _e('Event Management','evr_language');?></h2>
    <div id="dashboard-widgets-wrap">
        <div id="dashboard-widgets" class="metabox-holder">
        	<div class='postbox-container' style='width:65%;'>
                <div id='normal-sortables' class='meta-box-sortables'>
                    <div id="dashboard_right_now" class="postbox " >
                         
                        <h3 class='hndle'><span><?php _e('ReOrder Event Items/Cost for display:','evr_language');?><?php echo $event_name." at ".$event_location."  ".$start_date."-".$end_date;?></span></h3>
                         <div class="inside">
                            <div class="padding">        
    			                 <ul id="er_ticket_sortable">	
   
                                    <?php    
                                    $sql = "SELECT * FROM " . $events_cost_tbl . " WHERE event_id = " . $event_id. " ORDER BY sequence ASC";
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
                                            ?>
                                            
                                    <li id="item_<?php echo $item_id;?>" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                                    <font color="blue"><b><?php echo $item_cat;?> | <?php echo $item_title;?></B></font>  <?php echo $item_custom_cur." ".$item_price;?><br />
                                    <?php _e('Item Sales Begin:');?> <?php echo $item_start_date;?> - <?php _e('Item Sales End:');?> <?php echo $item_end_date;?> |</li>
                                     <?php }      ?>
			                     </ul>
                            </div>
                        </div>
                        <div class="inside">
                            <div class="padding">
                            
                            <a class="button-primary" href="admin.php?page=events&action=add_item&event_id=<?php echo $event_id;?>" title="Process Change"><?php _e('Apply Changes','evr_language');?></a>
                            
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