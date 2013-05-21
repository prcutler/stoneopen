<?php

/**
 * AIT Theme Admin
 *
 * Copyright (c) 2011, AIT s.r.o (http://ait-themes.com)
 *
 */

function aitRoomPostType()
{
	register_post_type( 'ait-room',
		array(
			'labels' => array(
				'name'			=> ROOM_CT_NAME.'s',
				'singular_name' => ROOM_CT_NAME,
				'add_new'		=> 'Add new',
				'add_new_item'	=> 'Add new '.strtolower(ROOM_CT_NAME),
				'edit_item'		=> 'Edit '.strtolower(ROOM_CT_NAME),
				'new_item'		=> 'New '.strtolower(ROOM_CT_NAME),
				'not_found'		=> 'No '.strtolower(ROOM_CT_NAME).'s found',
				'not_found_in_trash' => 'No '.strtolower(ROOM_CT_NAME).'s found in Trash',
				'menu_name'		=> ROOM_CT_NAME . 's',
			),
			'description' => __('Manipulating with '.strtolower(ROOM_CT_NAME).'s'),
			'public' => true,
			'show_in_nav_menus' => true,
			'supports' => array(
				'title',
				'thumbnail',
				'editor',
				'excerpt',
				'page-attributes',
				'comments',
			),
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_icon' => AIT_FRAMEWORK_URL . '/CustomTypes/room/room.png',
			'menu_position' => $GLOBALS['aitThemeCustomTypes']['room'],
			'has_archive' => 'rooms',
			'query_var' => 'room',
			'rewrite' => array('slug' => 'room'),
		)
	);
	aitRoomTaxonomies();

	flush_rewrite_rules(false);
}


function aitRoomTaxonomies()
{

	register_taxonomy( 'ait-room-category', array( 'ait-room' ), array(
		'hierarchical' => true,
		'labels' => array(
			'name'			=> _x( ROOM_CT_NAME.' Categories', 'taxonomy general name', 'ait'),
			'singular_name' => _x( 'Category', 'taxonomy singular name', 'ait'),
			'search_items'	=> __( 'Search Category', 'ait'),
			'all_items'		=> __( 'All Gategories', 'ait'),
			'parent_item'	=> __( 'Parent Category', 'ait'),
			'parent_item_colon' => __( 'Parent Category:', 'ait'),
			'edit_item'		=> __( 'Edit Category', 'ait'),
			'update_item'	=> __( 'Update Gategory', 'ait'),
			'add_new_item'	=> __( 'Add New Category', 'ait'),
			'new_item_name' => __( 'New Category Name', 'ait'),
		),
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'rooms' ),
		'query_var' => 'rooms',
	));
	// add uncategorized term
	if(!term_exists( 'Uncategorized Rooms', 'ait-room-category' )){
		wp_insert_term( 'Uncategorized Rooms', 'ait-room-category' );
	}
}
add_action( 'init', 'aitRoomPostType');



function aitRoomFeaturedImageMetabox()
{
	remove_meta_box( 'postimagediv', 'ait-room', 'side' );
	add_meta_box('postimagediv', __('Image for '.strtolower(ROOM_CT_NAME)), 'post_thumbnail_meta_box', 'ait-room', 'normal', 'high');
}
add_action('do_meta_boxes', 'aitRoomFeaturedImageMetabox');


$roomOptions = new WPAlchemy_MetaBox(array
(
	'id' => '_ait-room',
	'title' => __('Options', 'ait'),
	'types' => array('ait-room'),
	'context' => 'normal',
	'priority' => 'core',
	'config' => dirname(__FILE__) . '/' . basename(__FILE__, '.php') . '.neon',
));


function aitRoomChangeColumns($cols)
{
	$cols = array(
		'cb'                     => '<input type="checkbox" />',
		'title'                  => __( 'Title', 'ait'),
		'room_description_short' => __( 'Text', 'ait'),
		'thumbnail'              => __( 'Image', 'ait'),
		'menu_order'             => __( 'Order', 'ait'),
		'category'               => __( 'Category', 'ait'),
	);

	return $cols;
}
add_filter( "manage_ait-room_posts_columns", "aitRoomChangeColumns");



function aitRoomCustomColumns($column, $post_id)
{
	global $roomOptions;

	$options = $roomOptions->the_meta();

	switch ($column){
		case "room_description_short":

			if(isset($options['roomDescriptionShort'])){
				echo "<p>".$options['roomDescriptionShort']."</p>";
			}
			unset($options);
			break;
	}
}
add_action( "manage_posts_custom_column", "aitRoomCustomColumns", 10, 2);

function aitRoomSortableColumns()
{
	return array(
		'title'                  => 'title',
		'room_description_short' => 'room_description_short'
	);
}

add_filter( "manage_edit_ait-room_sortable_columns", "aitRoomSortableColumns" );