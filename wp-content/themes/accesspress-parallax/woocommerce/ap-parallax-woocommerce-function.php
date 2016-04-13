<?php
remove_action('woocommerce_sidebar','woocommerce_get_sidebar',10);
add_action('woocommerce_before_main_content','archive_page_start',5);
add_action('woocommerce_after_main_content','archive_page_end',5);


function archive_page_start(){ 
    echo '<div class="mid-content clearfix">';
    echo '<section id="primary" class="content-area">';
    echo '<main id="main" class="site-main" role="main">';
}
function archive_page_end(){ 
    echo '</main>';
	echo '</section>';
    get_sidebar();
    echo '</div>';
}