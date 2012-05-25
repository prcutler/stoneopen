<?php include (TEMPLATEPATH . "/header.php"); ?>


	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div id="content">

		

		<div class="post" id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
              <?php include (TEMPLATEPATH . "/posts.php"); ?>
        </div>
        <div class="post" >
            <?php comments_template(); ?>
        </div>
        
        
        
        
        
        <?php if ( get_the_author_meta( 'description' ) ) : // If a user has filled out their description, show a bio on their entries  ?>
					<div id="entry-author-info">
						<div id="author-avatar">
							<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'twentyten_author_bio_avatar_size', 60 ) ); ?>
						</div><!-- #author-avatar -->
						<div id="author-description">
							<h2><?php printf( esc_attr__( 'About %s', 'twentyten' ), get_the_author() ); ?></h2>
							<?php the_author_meta( 'description' ); ?>
							<div id="author-link">
								<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
									<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'twentyten' ), get_the_author() ); ?>
								</a>
							</div><!-- #author-link	-->
						</div><!-- #author-description -->
					</div><!-- #entry-author-info -->
<?php endif; ?>
        
        
        
        
	<?php endwhile; else: ?>
        <div id="content">
        <h2 class="center">Not Found</h2>
		<p class="center">Sorry, but you are looking for something that isn't here.</p>
		<?php include (TEMPLATEPATH . "/searchform.php"); ?>

<?php endif; ?>
        
        
        </div>  <!-- Content -->
        <div id="sidebar">
            <?php include (TEMPLATEPATH . "/sidebar-blog.php"); ?>
        </div>

<?php include (TEMPLATEPATH . "/footer.php");?>
