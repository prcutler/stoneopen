<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>

<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen, print" />
<script src="<?php bloginfo('stylesheet_directory'); ?>/js/changefont.js" type="text/javascript"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/js/mootools.v1.11.js" type="text/javascript"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/js/jd.gallery.js" type="text/javascript"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/js/jd.gallery.transitions.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/slideshow.css" type="text/css" media="screen" />

<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php 
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
		
	wp_head();
?>
<link href="wp-content/themes/golf-wordpress-theme/images/favicon.ico" rel="icon" type="image/x-icon" />
</head>
<?php if (get_option('show_on_front')=='page') $hpid=get_option('page_on_front'); ?>

<body <?php body_class(); ?>>

<?php $service_page = $wpdb->get_row("SELECT * from $wpdb->posts WHERE post_status = 'publish' AND post_title = 'services'");
$service_page_id=$service_page->ID;?>

<div id="header-outer">
    <div id="header">
        <div id="hleft">
            <a href="<?php bloginfo('home'); ?>/" title="<?php bloginfo('name'); ?>" class="logo"><?php bloginfo('name'); ?></a>
            <p id="desc"><?php bloginfo('description'); ?></p>
        </div>

        <div id="hright">
            <form method="get" action="<?php bloginfo('url'); ?>/" id="searchbox" >
                <div>
                    <input type="text" value="" name="s" class="stext"/>
                    <input type="submit" class="sbtn" value="Find" />
                </div>
            </form>
            <p id="topnav"></p>
            <div class="clr"></div>
        </div>
    </div>
</div>
<?php echo $offer_link_page_id; ?>
<div id="nav-outer">
    <ul id="nav" class="inner">
  
      

<?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary' ) ); ?>

    </div> <!-- // Nav outer -->
    
    
    
    <div id="slider-outer">
        <?php if(is_front_page()){ ?>
                <script type="text/javascript">
                    function startGallery() {
                    var myGallery = new gallery($('myGallery'), {
                        timed: true,
                        showArrows: false,
                        delay: 5000,
                        defaultTransition: "fadebg",
                        showCarousel: false,
                        showArrows: false,
                        showInfopane: false,
                        embedLinks: false


                        });
                    }
                    window.onDomReady(startGallery);
            </script>

             <div id="myGallery" class="inner">
             <?php include (TEMPLATEPATH . "/slideshow.php"); ?>
            </div> <!-- // myGallery -->
            <?php } else { ?>
            
            <div id="blog-header">
                <?php if(is_page()) { ?>
                <h1><?php the_title(); ?></h1>
                <?php } else { ?>
                <?php the_title(); ?></h1>
                <?php } ?>
            </div>
            <?php } ?>
 </div>
<div id="outer">
    <div id="wrapper">
