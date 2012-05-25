<?php include (TEMPLATEPATH . "/header.php"); ?>
<div id="content">
	<?php if (have_posts()) : ?>
	
        	<?php /* If this is a 404 page */ if (is_404()) { ?>
			<?php /* If this is a category archive */ } elseif (is_category()) { ?>
			<h2 class="pagetitle">Archief van: <?php single_cat_title(''); ?></h2>

			<?php /* If this is a yearly archive */ } elseif (is_day()) { ?>
			<h2 class="pagetitle">Archief van: <?php the_time('l, F jS, Y'); ?></h2>

			<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
			<h2 class="pagetitle">Archief van: <?php the_time('F, Y'); ?> </h2>

			<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
			<h2 class="pagetitle">Archives van: <?php the_time('Y'); ?></h2>

			<?php /* If this is a monthly archive */ } elseif (is_search()) { ?>
			<h2 class="pagetitle">Resultaat voor: '<?php the_search_query(); ?>'</h2>

			<?php } ?>

		<?php while (have_posts()) : the_post(); ?>

			<div class="post" id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
				<?php include (TEMPLATEPATH . "/posts.php"); ?>
			</div>

		<?php endwhile; ?>

		<div class="navigation">

			<div class="alignleft"><?php previous_posts_link('&#9668; Previous') ?></div>
			<div class="alignright"><?php next_posts_link('Next &#9658;') ?></div>
			<div class="clr"></div>
		</div>

	<?php else : ?>

		<h2 class="center">Not Found</h2>
		<p class="center">You are looking for something that isn't here.</p>
		<?php include (TEMPLATEPATH . "/searchform.php"); ?>

	<?php endif; ?>


	</div>  <!-- Content -->
        <div id="sidebar">
            <?php include (TEMPLATEPATH . "/sidebar-blog.php"); ?>
        </div>
<?php include (TEMPLATEPATH . "/footer.php"); ?>
