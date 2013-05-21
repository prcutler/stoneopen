<?php

/**
 * AIT Theme Admin
 *
 * Copyright (c) 2011, AIT s.r.o (http://ait-themes.com)
 *
 */

function aitItemPostType()
{
	register_post_type( 'ait-item',
		array(
			'labels' => array(
				'name'			=> EDITABLE_CT_NAME.'s',
				'singular_name' => EDITABLE_CT_NAME,
				'add_new'		=> 'Add new',
				'add_new_item'	=> 'Add new '.strtolower(EDITABLE_CT_NAME),
				'edit_item'		=> 'Edit '.strtolower(EDITABLE_CT_NAME),
				'new_item'		=> 'New '.strtolower(EDITABLE_CT_NAME),
				'not_found'		=> 'No '.strtolower(EDITABLE_CT_NAME).'s found',
				'not_found_in_trash' => 'No '.strtolower(EDITABLE_CT_NAME).'s found in Trash',
				'menu_name'		=> EDITABLE_CT_NAME.'s',
			),
			'description' => 'Manipulating with '.strtolower(EDITABLE_CT_NAME).'s',
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
			'menu_icon' => AIT_FRAMEWORK_URL . '/CustomTypes/item/item.png',
			'menu_position' => $GLOBALS['aitThemeCustomTypes']['item'],
			'has_archive' => 'items',
			'query_var' => 'item',
			'rewrite' => array('slug' => 'item'),
		)
	);
	aitItemTaxonomies();

	flush_rewrite_rules(false);
}


function aitItemTaxonomies()
{

	register_taxonomy( 'ait-item-category', array( 'ait-item' ), array(
		'hierarchical' => true,
		'labels' => array(
			'name'			=> EDITABLE_CT_NAME . ' Categories',
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
		'rewrite' => array( 'slug' => 'items' ),
		'query_var' => 'items',
	));
	// add uncategorized term
	if(!term_exists( 'Uncategorized', 'ait-item-category' )){
		wp_insert_term( 'Uncategorized', 'ait-item-category' );
	}

	create_metadata_table_item('ait_item_category');
}
add_action( 'init', 'aitItemPostType');

function aitItemFeaturedImageMetabox()
{
	remove_meta_box( 'postimagediv', 'ait-item', 'side' );
	add_meta_box('postimagediv', 'Image for '.strtolower(EDITABLE_CT_NAME), 'post_thumbnail_meta_box', 'ait-item', 'normal', 'high');
}
add_action('do_meta_boxes', 'aitItemFeaturedImageMetabox');

/* CATEGORY */
add_action( 'ait-item-category_edit_form_fields', 'edit_item_category', 10, 2);
add_action( 'ait-item-category_add_form_fields', 'add_item_category', 10, 2);
function edit_item_category($tag, $taxonomy)
{
	$thumbnail = get_metadata(str_replace("-","_",$tag->taxonomy), $tag->term_id, 'ait_item_category_thumbnail', true);

	?>
	<tr class="form-field">
        <th scope="row" valign="top"><label for="ait_item_category_thumbnail">Thumbnail</label></th>
        <td>
            <input type="text" name="ait_item_category_thumbnail" id="ait_item_category_thumbnail" value="<?php echo $thumbnail; ?>" style="width: 80%;"/>
            <input type="button" value="Select Image" class="media-select" id="ait_item_category_thumbnail_selectMedia" name="ait_item_category_thumbnail_selectMedia" style="width: 15%;">
            <br />
            <p class="description">Thumbnail for theme</p>
        </td>
    </tr>
    <?php
}
function add_item_category($tag, $taxonomy = "")
{
	$thumbnail = "";
	if(isset($tag) && isset($tag->taxonomy) && isset($tag->term_id)){
		$thumbnail = get_metadata(str_replace("-","_",$tag->taxonomy), $tag->term_id, 'ait_item_category_thumbnail', true);
	}

	?>
	<div class="form-field">
		<label for="ait_item_category_thumbnail">Thumbnail</label>
		<input type="text" name="ait_item_category_thumbnail" id="ait_item_category_thumbnail" value="<?php echo $thumbnail; ?>" style="width: 80%;"/>
        <input type="button" value="Select Image" class="media-select" id="ait_item_category_thumbnail_selectMedia" name="ait_item_category_thumbnail_selectMedia" style="width: 15%;">
            <br />
            <p class="description">Thumbnail for theme</p>
	</div>
	<?php
}

add_action( 'created_ait-item-category', 'save_item_category', 10, 2);
add_action( 'edited_ait-item-category', 'save_item_category', 10, 2);
function save_item_category($term_id, $tt_id)
{
    if (!$term_id) return;

	if (isset($_POST['ait_item_category_thumbnail']))
        update_metadata(str_replace("-","_",$_POST['taxonomy']), $term_id, 'ait_item_category_thumbnail', $_POST['ait_item_category_thumbnail']);
}

function create_metadata_table_item($type) {
	global $wpdb;
	$table_name = $wpdb->prefix . $type . 'meta';

	$variable_name = $type . 'meta';
	$wpdb->$variable_name = $table_name;

	if (!empty ($wpdb->charset))
		$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
	if (!empty ($wpdb->collate))
		$charset_collate .= " COLLATE {$wpdb->collate}";

	  $sql = "CREATE TABLE {$table_name} (
	  	meta_id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  	{$type}_id bigint(20) NOT NULL default 0,
		meta_key varchar(255) DEFAULT NULL,
		meta_value longtext DEFAULT NULL
	) {$charset_collate};";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);

}

// Edit taxonomy columns
add_filter("manage_edit-ait-item-category_columns", 'item_category_columns_theme');

function item_category_columns_theme($skin_category_columns) {
	$new_columns = array(
		'cb'        => '<input type="checkbox" />',
		'name'      => __('Name', 'ait'),
		'thumbnail' => __('Thumbnail', 'ait'),
		'slug'      => __('Slug', 'ait'),
		'posts'     => __('Docs', 'ait'),
		);
	return $new_columns;
}

add_filter("manage_ait-item-category_custom_column", 'manage_item_category_columns_theme', 10, 3);

function manage_item_category_columns_theme($out, $column_name, $cat_id) {
	$thumbnail = get_metadata("ait_item_category", $cat_id, 'ait_item_category_thumbnail', true);

	switch ($column_name) {
		case 'thumbnail':
			if(!empty($thumbnail)){
				$out .= '<img src="'.$thumbnail.'" alt="" width="80" height="80">';
			}
 			break;

		default:
			break;
	}
	return $out;
}