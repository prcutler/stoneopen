<?php

/**
 * AIT Theme Admin
 *
 * Copyright (c) 2011, AIT s.r.o (http://ait-themes.com)
 *
 */


 function aitPortfolioPostType()
 {
	register_post_type('ait-portfolio',
		array(
			'labels' => array(
			'name'			=> __('Portfolios', 'ait'),
			'singular_name' => __('Portfolio Item', 'ait'),
			'add_new'		=> __('Add New', 'ait'),
			'add_new_item'	=> __('Add New Portfolio Item', 'ait'),
			'edit_item'		=> __('Edit Portfolio Item', 'ait'),
			'new_item'		=> __('New Item', 'ait'),
			'view_item'		=> __('View Item', 'ait'),
			'search_items'	=> __('Search Items', 'ait'),
			'not_found'		=> __('No Portfolio Items found', 'ait'),
			'not_found_in_trash' => __('No items found in Trash', 'ait'),
		),
		'public' => true,
		'hierarchical' => false,
		'rewrite' => array('slug' => 'ait-portfolio'),
		'supports' => array('title', 'thumbnail', 'page-attributes'),
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_icon' => AIT_FRAMEWORK_URL . '/CustomTypes/portfolio/portfolio.png',
		'menu_position' => $GLOBALS['aitThemeCustomTypes']['portfolio'],
		)
	);

	aitPortfolioTaxonomies();

	//flush_rewrite_rules();
}



function aitPortfolioTaxonomies()
{

	register_taxonomy( 'ait-portfolio-category', array( 'ait-portfolio' ), array(
		'hierarchical' => true,
		'labels' => array(
			'name'			=> _x( 'Portfolio Categories', 'taxonomy general name', 'ait'),
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
		'rewrite' => array('slug' => 'ait-portfolio-category'),
	));
	// add uncategorized term
	if(!term_exists( 'Uncategorized Portfolios', 'ait-portfolio-category' )){
		wp_insert_term( 'Uncategorized Portfolios', 'ait-portfolio-category' );
	}
}

add_action( 'init', 'aitPortfolioPostType' );



function aitPortfolioImageMetabox()
{
	remove_meta_box( 'postimagediv', 'ait-portfolio', 'side' );
	add_meta_box('postimagediv', __('Portfolio Item Thumbnail', 'ait'), 'post_thumbnail_meta_box', 'ait-portfolio', 'normal', 'high');
}
add_action('do_meta_boxes', 'aitPortfolioImageMetabox');



$portfolioOptions = new WPAlchemy_MetaBox(array(
	'id' => '_ait-portfolio',
	'title' => __('Portfolio Item Options', 'ait'),
	'types' => array('ait-portfolio'),
	'context' => 'normal',
	'priority' => 'core',
	'config' => dirname(__FILE__) . '/' . basename(__FILE__, '.php') . '.neon',
	'js' => dirname(__FILE__) . '/' . basename(__FILE__, '.php') . '.js',
));



function aitPortfolioChangeColumns($cols)
{
  $cols = array(
	'cb'         => '<input type="checkbox" />',
	'title'      => __( 'Portfolio Item Name', 'ait'),
	'itemType'   => __( 'Type', 'ait'),
	'itemUrl'    => __( 'Link', 'ait'),
	'thumbnail'  => __( 'Thumbnail', 'ait'),
	'menu_order' => __( 'Order', 'ait'),
	'category'   => __( 'Portfolio Category', 'ait'),
  );

  return $cols;
}
add_filter( "manage_ait-portfolio_posts_columns", "aitPortfolioChangeColumns");



function aitPortfolioCustomColumns($column, $post_id)
{
	switch ($column){
		case "itemType":
			$meta_type = get_post_meta($post_id, '_ait-portfolio', TRUE);
			if($meta_type['itemType'] == "image"){
				echo "Large image";
			}elseif($meta_type['itemType'] == "website"){
				echo "Website";
			}elseif($meta_type['itemType'] == "video"){
				echo "Video";
			}
		break;

		case "itemUrl":
			$meta_type = get_post_meta($post_id, '_ait-portfolio', TRUE);

			$link = '';

			if($meta_type['itemType'] == "image"){
				if(isset($meta_type['imageLink']))
					$link = $meta_type['imageLink'];

			}elseif($meta_type['itemType'] == "website"){
				if(isset($meta_type['websiteLink']))
					$link = $meta_type['websiteLink'];
			}elseif($meta_type['itemType'] == "video"){
				if(isset($meta_type['videoLink']))
					$link = $meta_type['videoLink'];
			}
			if(!empty($link))
				echo '<a href="' . esc_url($link) . '">' . htmlspecialchars($link) . '</a>';
			else
				echo '';
		break;
	}
}
add_action( "manage_posts_custom_column", "aitPortfolioCustomColumns", 10, 2 );

function aitPortfolioSortableColumns()
{
  return array(
    'title' => 'title',
    'itemType' => 'itemType',
    'category' => 'category',
    'menu_order' => 'order',
  );
}
add_filter( "manage_edit-ait-portfolio_sortable_columns", "aitPortfolioSortableColumns" );
