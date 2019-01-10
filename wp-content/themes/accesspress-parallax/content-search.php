<?php
/**
 * The template part for displaying results in search pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package accesspress_parallax
 */
$post_footer = of_get_option('post_footer');
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('article-wrap'); ?>>
	<header class="entry-header">
		<?php 
		if ( 'post' == get_post_type() ) :
			accesspress_parallax_posted_on(); 
		endif; ?>

		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->

	<?php if( $post_footer == 1 ) : ?>
	<footer class="entry-footer">
		<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
			<?php
				$categories_list = get_the_category_list( ', ' );
				if ( $categories_list && accesspress_parallax_categorized_blog() ) :
			?>
			<span class="cat-links">
				<?php 
				/* translators: Category List */
				printf( esc_html__( 'Posted in %s', 'accesspress-parallax' ), $categories_list ); // WPCS: XSS OK.?>
			</span>
			<?php endif; // End if categories ?>

			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', ', ' );
				if ( $tags_list ) :
			?>
			<span class="tags-links">
				<?php 
				/* translators: Tag List */
				printf( esc_html__( 'Tagged %s', 'accesspress-parallax' ), $tags_list ); // WPCS: XSS OK. ?>
			</span>
			<?php endif; // End if $tags_list ?>
		<?php endif; // End if 'post' == get_post_type() ?>

		<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
			<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'accesspress-parallax' ), __( '1 Comment', 'accesspress-parallax' ), __( '% Comments', 'accesspress-parallax' ) ); ?></span>
		<?php endif; ?>
	</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-## -->