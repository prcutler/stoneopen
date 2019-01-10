<?php
/**
 * @package accesspress_parallax
 */

$post_date = of_get_option('post_date');
$post_footer = of_get_option('post_footer');
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('article-wrap'); ?>>
	<?php if(has_post_thumbnail()) : ?>
	<div class="entry-thumb">
		<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'blog-header' ); ?>
		<a href="<?php echo esc_url(get_permalink()); ?>"><img src="<?php echo esc_url($image[0]); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>"></a> 
	</div>
	<?php endif; ?>

	<header class="entry-header">
		<?php 
		if ( 'post' == get_post_type() ) :
			accesspress_parallax_posted_on(); 
		endif; ?>

		<h2 class="entry-title"><a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a></h2>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'accesspress-parallax' ) ); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'accesspress-parallax' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<?php if( $post_footer == 1 ) : ?>
	<footer class="entry-footer">
		<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
			<?php
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( ', ' );
				if ( $categories_list && accesspress_parallax_categorized_blog() ) :
				?>
				<span class="cat-links">
					<i class="fa fa-folder-open"></i><?php 
					/* translators: category list */
					printf( esc_html__( 'Posted in %1$s', 'accesspress-parallax' ), $categories_list ); // WPCS: XSS OK. ?>
				</span>
			<?php endif; // End if categories ?>

			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', ', ' );
				if ( $tags_list ) :
				?>
				<span class="tags-links">
					<i class="fa fa-tags"></i><?php 
					/* translators: Tags list */
					printf( esc_html__( 'Tagged %1$s', 'accesspress-parallax' ), $tags_list ); // WPCS: XSS OK. ?>
				</span>
			<?php endif; // End if $tags_list ?>
		<?php endif; // End if 'post' == get_post_type() ?>

		<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
			<span class="comments-link"><?php comments_popup_link( __( '<i class="fa fa-comments"></i>Leave a comment', 'accesspress-parallax' ), __( '<i class="fa fa-comments"></i>1 Comment', 'accesspress-parallax' ), __( '<i class="fa fa-comments"></i>% Comments', 'accesspress-parallax' ) ); ?></span>
		<?php endif; ?>
	</footer><!-- .entry-footer -->
	<?php endif; ?>
	
	<?php edit_post_link( '<i class="fa fa-pencil-square-o"></i>'. __( 'Edit', 'accesspress-parallax' ), '<span class="edit-link">', '</span>' ); ?>
</article><!-- #post-## -->