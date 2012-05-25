<?php if(is_front_page()) {?>
<?php if($quote_title) echo "<h2>$quote_title</h2>" ; ?>
                <div class="sbbox">
                    
                    <blockquote>
                        <?php if($quote_content) echo "$quote_content" ; ?>
                        <p class="qtr"><span class="blue"><?php if($quote_by) echo "$quote_by" ; ?>&nbsp;</span></p>
                    </blockquote>
</div>
<div class="sbbox">
                    <blockquote>
                        <?php if($quote_content) echo "Eindelijk een website die alle aspecten van het putten behandelt." ; ?>
                        <p class="qtr"><span class="blue"><?php if($quote_by) echo "Michel" ; ?>&nbsp;</span></p>
                    </blockquote>


                </div>

    <?php } else { ?>

    <?php 	/* Widgetized sidebar, if you have the plugin installed. */
	if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Blog Sidebar') ) : ?>
	
	
    <?php if($service_page_id or $service_page_id != 0) {?>
	<div class="sbbox">
	   <h2>Services We Offer</h2>
	   <ul>
	       <?php wp_list_pages("title_li=&child_of=$service_page_id&depth=1");?>
	   </ul>
	</div>
    <?php } ?>
    
    <?php endif;  } ?>
		

