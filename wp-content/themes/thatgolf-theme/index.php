<?php get_header(); ?>

<div id="container">
  <?php if(have_posts()): ?>
  <?php while(have_posts()):the_post(); ?>
  <div class="post">
    <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>">
      <?php the_title(); ?>
      </a></h2>
    <div class="postinfo">
      <?php _e('Posted on'); ?>
      <span class="postdate">
      <?php the_time('F jS, Y') ?>
      </span>
      <?php _e('by'); ?>
      <?php the_author() ?><br />Filed Under: <?php the_category(', ') ?>
    </div>
    <div class="entry">
      <?php the_content("[Read more &rarr;]"); ?>
      <p class="postmetadata">
        <?php the_tags( 'Tags: ', ', '); ?>//
           Category <?php the_category(', ') ?>
        <?php edit_post_link('Edit', ' &#124; ', ''); ?>
        // <strong>
        <?php comments_popup_link('Add Comment &#187;', '1 Comment &#187;', '% Comments &#187;'); ?>
        </strong> </p>
    </div>
  </div>
  <?php endwhile; ?>
  <div class="navigation">
    <?php posts_nav_link(); ?>
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