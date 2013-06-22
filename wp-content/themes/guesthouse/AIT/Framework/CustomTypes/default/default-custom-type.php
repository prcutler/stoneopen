<?php

/**
 * AIT Theme Admin
 *
 * Copyright (c) 2011, AIT s.r.o (http://ait-themes.com)
 *
 */
if(defined('WOOCOMMERCE_VERSION') and isset($_GET['post_type']) and $_GET['post_type'] == 'product') return;

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
			$thumb = wp_get_attachment_image( $thumbnail_id, array($width, $height), true );
		elseif ($attachments) {
			foreach ( $attachments as $attachment_id => $attachment ) {
				$thumb = wp_get_attachment_image( $attachment_id, array($width, $height), true );
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
add_action( "manage_posts_custom_column", "ait_type_custom_columns", 10, 2 );

function ait_type_taxonomy_filter_restrict_manage_posts() {
    global $typenow;

    $post_types = get_post_types( array( '_builtin' => false ) );

    if ( in_array( $typenow, $post_types ) ) {
    	$filters = get_object_taxonomies( $typenow );

        foreach ( $filters as $tax_slug ) {
            $tax_obj = get_taxonomy( $tax_slug );
            wp_dropdown_categories( array(
                'show_option_all' => sprintf(__('Show All %s', THEME_CODE_NAME), $tax_obj->label),
                'taxonomy' 	  => $tax_slug,
                'name' 		  => $tax_obj->name,
                'orderby' 	  => 'name',
                'selected' 	  => (isset($_GET[$tax_slug])) ? $_GET[$tax_slug] : 0,
                'hierarchical' 	  => $tax_obj->hierarchical,
                'hide_empty' => 0,
				'show_count' => 1
            ) );
        }
    }
}
add_action( 'restrict_manage_posts', 'ait_type_taxonomy_filter_restrict_manage_posts' );

function ait_type_taxonomy_filter_request( $query ) {
  global $pagenow, $typenow;

  if ( 'edit.php' == $pagenow ) {
    $filters = get_object_taxonomies( $typenow );
    foreach ( $filters as $tax_slug ) {
      $var = &$query->query_vars[$tax_slug];
      if ( isset( $var ) ) {
        $term = get_term_by( 'id', $var, $tax_slug );
        if($term)
        	$var = $term->slug;
      }
    }
  }
}

add_filter( 'parse_query', 'ait_type_taxonomy_filter_request' );