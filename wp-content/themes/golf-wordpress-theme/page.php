<?php

/*

Template Name: Normal Page

*/

?>



<?php include (TEMPLATEPATH . "/header.php");?>





    <div id="content">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    





		<div class="post" id="post-<?php the_ID(); ?>" <?php post_class(); ?> >



    <?php if(is_front_page()){ ?><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><h1><?php the_title(); ?></h1></a><?php }?>

    





		  <div class="entry">

                <?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>



                <?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?> 

				<?php edit_post_link('Edit this post', '<p>', '</p>'); ?>

                <div class="clr"></div>

            </div>  <!-- // End Entry -->



        </div>  <!-- // End Post -->

        

    <?php endwhile; endif; ?>



    </div>  <!-- Content -->



<div id="sidebar">

  <?php include (TEMPLATEPATH . "/sidebar-blog.php"); ?>

</div>

<?php include (TEMPLATEPATH . "/footer.php");?>





