<?php

/**
 * AIT Theme Admin
 *
 * Copyright (c) 2011, AIT s.r.o (http://ait-themes.com)
 *
 */
if(defined('WOOCOMMERCE_VERSION') and isset($_GET['post_type']) and $_GET['post_type'] == 'product') return;

add_action( "manage_posts_custom_column", "ait_type_custom_columns", 10, 2 );
function ait_type_custom_columns( $column, $post_id ) {
  switch ( $column ) {
	case "thumbnail":
		$width = (int) 100;
		$height = (int) 100;

		// thumbnail of WP 2.9
		$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
		// image from gallery
		$attachments = get_children( array('post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' => 'image') );
		if ($thumbnail_id)
			$thumb = @wp_get_attachment_image( $thumbnail_id, array($width, $height), true );
		elseif ($attachments) {
			foreach ( $attachments as $attachment_id => $attachment ) {
				$thumb = @wp_get_attachment_image( $attachment_id, array($width, $height), true );
			}
		}
		if ( isset($thumb) && $thumb ) {
			echo $thumb;
		} else {
			echo __('None', 'ait');
		}
		break;
	case "menu_order":
		$post = (get_post($post_id));
		echo $post->menu_order;
		unset($post);
		break;
	case "content":
		$post = (get_post($post_id));
		echo strip_tags($post->post_content);
		unset($post);
		break;
	case "category":
		$post_type = get_post_type($post_id);
		echo get_the_term_list($post_id, $post_type.'-category', '', ', ');
		break;
	case "author":
		$post = (get_post($post_id));
		the_author_meta( 'user_nicename' , $post->post_author );
		break;
  }
}

add_action( 'restrict_manage_posts', 'ait_type_taxonomy_filter_restrict_manage_posts' );
function ait_type_taxonomy_filter_restrict_manage_posts() {

	global $pagenow, $current_screen;
	if (is_admin() && isset($current_screen) && ('edit.php' == $pagenow)) {
		$post_types = get_post_types( array( '_builtin' => false ) );
		if ( in_array( $current_screen->post_type, $post_types ) ) {
			$filters = get_object_taxonomies( $current_screen->post_type );

			$t = array('category' => true, 'post_tag' => true, 'nav_menu' => true, 'link_category' => true, 'post_format' => true);

			foreach ( $filters as $tax_slug ) {
				$isCustomTax = !isset($t[$tax_slug]);
				$tax_obj = get_taxonomy( $tax_slug );
				$qvar = $tax_obj->query_var;

				wp_dropdown_categories( array(
					'show_option_all' => sprintf(__('Show All %s', THEME_CODE_NAME), $tax_obj->label),
					'taxonomy' 	  => $tax_slug,
					'name' 		  => $isCustomTax ? $qvar : $tax_slug,
					'orderby' 	  => 'name',
					'selected' 	  => (isset($_GET[$qvar])) ? $_GET[$qvar] : 0,
					'hierarchical' 	  => $tax_obj->hierarchical,
					'hide_empty' => 0,
					'show_count' => 1
				) );
			}
		}
	}

}

add_action('parse_query', 'ait_type_taxonomy_filter_request');

function ait_type_taxonomy_filter_request($query)
{
	global $pagenow, $current_screen;

	if(is_admin() and isset($current_screen) and ('edit.php' == $pagenow)){
		$taxonomies = get_object_taxonomies($current_screen->post_type, 'objects');
		$q = &$query->query_vars;

		foreach($taxonomies as $tax){
			$qvar = $tax->query_var;
			if(isset($q[$qvar]) and is_numeric($q[$qvar]) and $q[$qvar] != '0'){
  				$term = get_term_by('id', $q[$qvar], $tax->name);
  				if($term)
  					$q[$qvar] = $term->slug;
			}
		}
	}
}

