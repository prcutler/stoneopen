<?php get_header(); ?>
<div id="container">
  <?php if(have_posts()): ?>
  <?php while(have_posts()):the_post(); ?>
  <div class="post">
    <h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
      <?php the_title(); ?>
      </a></h2>
    <div class="postinfo">
      <?php _e('Posted on'); ?>
      <span class="postdate">
      <?php the_time('F jS, Y') ?>
      </span>
      <?php _e('by'); ?>
      <?php the_author() ?>
    </div>
    <div class="entry">
      <?php the_content(); ?>
      <?php link_pages('<p><strong>Pages:</strong>','</p>','number'); ?>
      <p class="postmetadata">
        <?php _e('Tags&#58;'); ?>
        <?php the_category(', ') ?>
        <?php edit_post_link('Edit', ' &#124; ', ''); ?>
        // <strong>
        <?php comments_popup_link('Add Comment &#187;', '1 Comment &#187;', '% Comments &#187;'); ?>
        </strong> </p>
    </div>
    <div class="comments-template">
      <?php comments_template(); ?>
    </div>
  </div>
  <?php endwhile; ?>
  <div class="navigation">
    <?php previous_post_link('&laquo; %link') ?>
    <?php next_post_link('%link &raquo;') ?>
  </div>
  <?php else: ?>
  <div class="post" id="post-<?php the_ID(); ?>">
    <h2>
      <?php _e('Not Found'); ?>
    </h2>
  </div>
  <?php endif; ?>
</div>
<?php get_sidebar(); ?>
</div>
<?php get_footer() ?>
</body>
</html>