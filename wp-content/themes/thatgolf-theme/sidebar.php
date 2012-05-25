<!-- Start left_sidebar -->

<div class="left_sidebar">
  <ul>
    <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(1) ) : else : ?>
    <li>
      <h2>Navigation</h2>
      <ul>
        <li><a <?php if (is_home()) echo('class="current" '); ?>href="<?php bloginfo('url'); ?>">Home</a></li>
        <?php wp_list_pages('depth=1&title_li='); ?>
      </ul>
    </li>
    <?php query_posts('showposts=5'); ?>
    <li>
      <h2>Recent Posts</h2>
      <ul>
        <?php while (have_posts()) : the_post(); ?>
        <li><a href='<?php the_permalink() ?>'>
          <?php the_title(); ?>
          </a></li>
        <?php endwhile; ?>
      </ul>
    </li>
    <?php if (function_exists('get_recent_comments')) { ?>
    <li>
      <h2>
        <?php _e('Recent Comments:'); ?>
      </h2>
      <ul>
        <?php get_recent_comments(); ?>
      </ul>
    </li>
    <?php } ?>
    <?php if (function_exists('get_recent_trackbacks')) { ?>
    <li>
      <h2>
        <?php _e('Recent Trackbacks:'); ?>
      </h2>
      <ul>
        <?php get_recent_trackbacks(); ?>
      </ul>
    </li>
    <?php } ?>
    <?php if(function_exists('ns_show_top_commentators')) { ?>
    <li>
      <h2>Top Commenters</h2>
      <ul>
        <?php ns_show_top_commentators(); ?>
      </ul>
    </li>
    <?php } ?>
    <?php get_links_list(); ?>
    <?php endif; ?>
  </ul>
</div>
<!-- End left_sidebar -->
<!-- Start right sidebar -->
<div class="right_sidebar">
  <ul>
    <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(2)) : else : ?>
    <li>
      <h2>
        <?php _e('Categories'); ?>
      </h2>
      <ul>
        <?php wp_list_cats('sort_column=name'); ?>
      </ul>
    </li>
    <li>
      <h2>
        <?php _e('Archives'); ?>
      </h2>
      <ul>
        <?php wp_get_archives('type=monthly'); ?>
      </ul>
    <li>
      <h2>
        <?php _e('Meta'); ?>
      </h2>
      <ul>
        <?php wp_register(); ?>
        <li>
          <?php wp_loginout(); ?>
        </li>
        <?php wp_meta(); ?>
      </ul>
    </li>
    <?php endif; ?>
  </ul>
</div>
<!-- End Sidebar -->
