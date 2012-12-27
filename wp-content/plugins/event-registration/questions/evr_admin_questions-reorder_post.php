<?php

function evr_post_question_order(){
			
			global $wpdb;
			foreach($_GET['item'] as $key=>$value) {  
                mysql_query("UPDATE ".get_option('evr_question')." SET sequence = '$key' WHERE id ='$value';");  
            }  
}
?>