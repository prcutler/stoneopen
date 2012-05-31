<?php
/*
Template Name: Sitemap
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title>
<?php if (is_single() || is_page() || is_archive()) { wp_title('',true); } else { bloginfo('name'); echo(' &#8212; '); bloginfo('description'); } ?>
</title>
<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
<!-- leave this for stats -->
<!-- <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" /> !! Leave this out unless you want to style the page -->
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php wp_head(); ?>
</head>
<body>
<h2><a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>">
  <?php bloginfo('name'); ?>
  </a></h2>
<h2>
  <?php the_title(); ?>
</h2>
<h3>All internal pages:</h3>
<ul>
  <?php wp_list_pages('title_li='); ?>
</ul>
<h3>All internal blog posts:</h3>
<ul>
  <?php $archive_query = new WP_Query('showposts=1000');
		while ($archive_query->have_posts()) : $archive_query->the_post(); ?>
  <li><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>">
    <?php the_title(); ?>
    </a> <strong>
    <?php comments_number('0', '1', '%'); ?>
    </strong></li>
  <?php endwhile; ?>
</ul>
<h3>Monthly archive pages:</h3>
<ul>
  <?php wp_get_archives('type=monthly'); ?>
</ul>
<h3>Topical archive pages:</h3>
<ul>
  <?php wp_list_categories('title_li=0'); ?>
</ul>
<h3>Available RSS Feeds:</h3>
<ul>
  <li><a href="<?php bloginfo('rdf_url'); ?>" title="RDF/RSS 1.0 feed"><acronym title="Resource Description Framework">RDF</acronym>/<acronym title="Really Simple Syndication">RSS</acronym> 1.0 feed</a></li>
  <li><a href="<?php bloginfo('rss_url'); ?>" title="RSS 0.92 feed"><acronym title="Really Simple Syndication">RSS</acronym> 0.92 feed</a></li>
  <li><a href="<?php bloginfo('rss2_url'); ?>" title="RSS 2.0 feed"><acronym title="Really Simple Syndication">RSS</acronym> 2.0 feed</a></li>
  <li><a href="<?php bloginfo('atom_url'); ?>" title="Atom feed">Atom feed</a></li>
</ul>
</body>
</html>
