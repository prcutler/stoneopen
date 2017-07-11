<?php
/**
 * @package accesspress_parallax
 */

$post_date = of_get_option('post_date');
$post_footer = of_get_option('post_footer');
$post_date_class = ((!empty($post_date) && $post_date == ' ') || has_post_thumbnail()) ? " no-date" : "";
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php if(has_post_thumbnail()) : ?>
		<div class="entry-thumb">
			<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'blog-header' ); ?>
			<img src="<?php echo esc_url($image[0]); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"> 
		</div>
		<?php endif; ?>
		
		<h1 class="entry-title<?php echo esc_attr($post_date_class); ?>"><?php the_title(); ?></h1>

		<div class="entry-meta">
			<?php accesspress_parallax_posted_on(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'accesspress-parallax' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<?php if( $post_footer == 1 ) : ?>
	<footer class="entry-footer">
		<?php
			/* translators: used between list items, there is a space after the comma */
			$category_list = get_the_category_list( ', ' );

			/* translators: used between list items, there is a space after the comma */
			$tag_list = get_the_tag_list( '', ', ' );


			if( '' != $category_list ){
			     echo '<i class="fa fa-folder-open" aria-hidden="true"></i>'.$category_list;
			}
            
            if( '' != $tag_list ){
                echo '<i class="fa fa-tags" aria-hidden="true"></i>'.$tag_list;
            }
		?>
	</footer><!-- .entry-footer -->
<?php endif; ?>
<?php edit_post_link( '<i class="fa fa-pencil-square-o"></i>'. __( 'Edit', 'accesspress-parallax' ), '<span class="edit-link">', '</span>' ); ?>
</article><!-- #post-## -->
