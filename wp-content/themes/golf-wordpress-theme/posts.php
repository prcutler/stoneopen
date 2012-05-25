       
<?php if(!is_page()) {?>
  <?php } ?>
<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><h1><?php the_title(); ?></h1></a>
    <div class="entry">
        <?php the_content('Moor &#9658;'); ?>
        <div class="clr"></div>
        
        <?php the_tags('<p class="tags"><strong>Tags: </strong>', ', ', '</p>'); ?>
		
		  <p class="postmeta">
           <?php the_time('m F Y') ?> in <?php the_category(', ') ?> <a href="<?php comments_link(); ?>"><?php comments_number('0 Comments', '1 Comment', '% Comments'); ?></a> <br />
            <?php edit_post_link('Edit this post', ' | ', ' | '); ?>
        </p>
        
        <?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>

    </div>
        

