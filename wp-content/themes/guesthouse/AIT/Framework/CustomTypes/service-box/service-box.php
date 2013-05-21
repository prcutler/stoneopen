<?php

/**
 * AIT Theme Admin
 *
 * Copyright (c) 2011, AIT s.r.o (http://ait-themes.com)
 *
 */

function aitServicesBoxPostType()
{
	register_post_type( 'ait-service-box',
		array(
			'labels' => array(
				'name'               => __('Service boxes', 'ait'),
				'singular_name'      => __('Service box', 'ait'),
				'add_new'            => __('Add new', 'ait'),
				'add_new_item'       => __('Add new box', 'ait'),
				'edit_item'          => __('Edit box', 'ait'),
				'new_item'           => __('New box', 'ait'),
				'not_found'          => __('No boxes found', 'ait'),
				'not_found_in_trash' => __('No boxes found in Trash', 'ait'),
				'menu_name'          => __('Service Boxes', 'ait'),
			),
			'public' => false,
			'show_in_nav_menus' => false,
			'supports' => array(
				'title',
				'thumbnail',
				'page-attributes',
			),
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_icon' => AIT_FRAMEWORK_URL . '/CustomTypes/service-box/service-box.png',
			'menu_position' => $GLOBALS['aitThemeCustomTypes']['service-box'],
		)
	);
	aitServiceBoxTaxonomies();

	flush_rewrite_rules(false);
}


function aitServiceBoxTaxonomies()
{

	register_taxonomy( 'ait-service-box-category', array( 'ait-service-box' ), array(
		'hierarchical' => true,
		'labels' => array(
			'name'              => _x( 'Service Box Categories', 'taxonomy general name', 'ait'),
			'singular_name'     => _x( 'Category', 'taxonomy singular name', 'ait'),
			'search_items'      => __( 'Search Category', 'ait'),
			'all_items'         => __( 'All Gategories', 'ait'),
			'parent_item'       => __( 'Parent Category', 'ait'),
			'parent_item_colon' => __( 'Parent Category:', 'ait'),
			'edit_item'         => __( 'Edit Category', 'ait'),
			'update_item'       => __( 'Update Gategory', 'ait'),
			'add_new_item'      => __( 'Add New Category', 'ait'),
			'new_item_name'     => __( 'New Category Name', 'ait'),
		),
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'ait-service-box-category' ),
	));
	// add uncategorized term
	if(!term_exists( 'Uncategorized Service-Boxes', 'ait-service-box-category' )){
		wp_insert_term( 'Uncategorized Service-Boxes', 'ait-service-box-category' );
	}
}
add_action( 'init', 'aitServicesBoxPostType');



function aitServiceBoxFeaturedImageMetabox()
{
	remove_meta_box( 'postimagediv', 'ait-service-box', 'side' );
	add_meta_box('postimagediv', __('Service image', 'ait'), 'post_thumbnail_meta_box', 'ait-service-box', 'normal', 'high');
}
add_action('do_meta_boxes', 'aitServiceBoxFeaturedImageMetabox');


$configFile = dirname(__FILE__) . '/' . basename(__FILE__, '.php') . '.neon';

if($customTypeVersion){
	$configFile = dirname(__FILE__) . '/' . basename(__FILE__, '.php') . '.' . $customTypeVersion . '.neon';
}

$serviceBoxOptions = new WPAlchemy_MetaBox(array(
	'id'       => '_ait-service-box',
	'title'    => __('Options for service box', 'ait'),
	'types'    => array('ait-service-box'),
	'context'  => 'normal',
	'priority' => 'core',
	'config'   => $configFile,
));



function aitServiceBoxChangeColumns($cols)
{
	$cols = array(
		'cb'               => '<input type="checkbox" />',
		'title'            => __( 'Title', 'ait'),
		'service_box_text' => __( 'Text', 'ait'),
		'service_box_link' => __( 'Link', 'ait'),
		'thumbnail'        => __( 'Image', 'ait'),
		'menu_order'       => __( 'Order', 'ait'),
		'category'         => __( 'Category', 'ait'),
	);

	return $cols;
}
add_filter( "manage_ait-service-box_posts_columns", "aitServiceBoxChangeColumns");



function aitServiceBoxCustomColumns($column, $post_id)
{
	global $serviceBoxOptions;
	$options = $serviceBoxOptions->the_meta();

	switch ($column){
		case "service_box_text":

			if(isset($options['boxText'])){
				echo "<p>" . esc_html($options['boxText']) . "</p>";
			}
			unset($options);
			break;

		case "service_box_link":

			if(isset($options['boxLink'])){
				echo '<a href="' . esc_url($options['boxLink']) . '">' . esc_html($options['boxLink']) . "</a>";
			}
			unset($options);
			break;
	}
}
add_action( "manage_posts_custom_column", "aitServiceBoxCustomColumns", 10, 2);



function aitServiceBoxSortableColumns()
{
	return array(
		'title' => 'title',
		'menu_order' => 'order',
		'category' => 'category',
	);
}

add_filter( "manage_edit-ait-service-box_sortable_columns", "aitServiceBoxSortableColumns" );
