<?php
//function to post changes when items are rearranged
function evr_post_update_item_order(){
    global $wpdb;
			$events_cost_tbl = get_option ( 'evr_cost' );
            foreach($_GET['item'] as $key=>$value) {  
                mysql_query("UPDATE $events_cost_tbl SET sequence = '$key' WHERE id ='$value';");  
            }  
}
?>