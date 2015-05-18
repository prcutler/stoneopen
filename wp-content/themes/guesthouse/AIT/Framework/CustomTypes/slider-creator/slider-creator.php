<?php

/**
 * AIT Theme Admin
 *
 * Copyright (c) 2011, AIT s.r.o (http://ait-themes.club)
 *
 */


 function aitsliderCreatorPostType()
 {
	register_post_type('ait-slider-creator',
		array(
			'labels' => array(
			'name'			=> __('Sliders', 'ait'),
			'singular_name' => __('Slider Item', 'ait'),
			'add_new'		=> __('Add New Item', 'ait'),
			'add_new_item'	=> __('Add New Slider Item', 'ait'),
			'edit_item'		=> __('Edit Slider Item', 'ait'),
			'new_item'		=> __('New Item', 'ait'),
			'view_item'		=> __('View Item', 'ait'),
			'search_items'	=> __('Search Items', 'ait'),
			'not_found'		=> __('No Slider Items found', 'ait'),
			'not_found_in_trash' => __('No items found in Trash', 'ait'),
		),
		'public' => true,
		'hierarchical' => false,
		'rewrite' => array('slug' => 'ait-slider-creator'),
		'supports' => array('title', 'page-attributes'),
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_icon' => AIT_FRAMEWORK_URL . '/CustomTypes/slider-creator/slider-creator.png',
		'menu_position' => $GLOBALS['aitThemeCustomTypes']['slider-creator'],
		)
	);

	aitsliderCreatorTaxonomies();

	//flush_rewrite_rules();
}



function aitsliderCreatorTaxonomies()
{

	register_taxonomy( 'ait-slider-creator-category', array( 'ait-slider-creator' ), array(
		'hierarchical' => true,
		'labels' => array(
			'name'			=> _x( 'Slider Categories', 'taxonomy general name', 'ait'),
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
		'rewrite' => array('slug' => 'ait-slider-creator-category'),
	));
	// add uncategorized term
	if(!term_exists( 'Uncategorized Sliders', 'ait-slider-creator-category' )){
		wp_insert_term( 'Uncategorized Sliders', 'ait-slider-creator-category' );
	}
}

add_action( 'init', 'aitsliderCreatorPostType' );

$sliderCreatorOptions = new WPAlchemy_MetaBox(array(
	'id' => '_ait-slider-creator',
	'title' => 'Slider Item Options',
	'types' => array('ait-slider-creator'),
	'context' => 'normal',
	'priority' => 'core',
	'config' => dirname(__FILE__) . '/' . basename(__FILE__, '.php') . '.neon',
	'js' => dirname(__FILE__) . '/' . basename(__FILE__, '.php') . '.js',
));



function aitsliderCreatorChangeColumns($cols)
{
  $cols = array(
	'cb'              => '<input type="checkbox" />',
	'title'           => __( 'Item Name', 'ait'),
	'slider_type'     => __( 'Type', 'ait'),
	'slider_top_img'  => __( 'Top Image', 'ait'),
	'slider_back_img' => __( 'Background Image', 'ait'),
	'slider_link'     => __( 'Link', 'ait'),
	'menu_order'      => __( 'Order', 'ait'),
	'category'        => __( 'Slider Category', 'ait'),
  );

  return $cols;
}
add_filter( "manage_ait-slider-creator_posts_columns", "aitsliderCreatorChangeColumns");

function aitsliderCreatorCustomColumns($column, $post_id)
{
	global $sliderCreatorOptions;
	$options = $sliderCreatorOptions->the_meta();

	switch ($column){
		case "slider_type":
			if(isset($options['itemType'])){
				echo $options['itemType'];
			} else {
				echo "Image";
			}
			break;

		case "slider_top_img":
		  if(!empty($options['slideType']) && $options['slideType']=='advanced')
      	  {
      	  		switch($options['advancedItemType'])
	        	{
			          case "image":
			            if(isset($options['advancedImageSource'])){
							if(TIMTHUMB_URL)
								echo '<img src="'.TIMTHUMB_URL.'?src='.$options['advancedImageSource'].'&w=100&h=100" alt="" />';
							else
			      				echo '<img src="' . AitImageResizer::resize($options['advancedImageSource'], array('w' => 100, 'h' => 100)) . '" alt="" />';
			      			}
			            break;
			          case "video":
			            if(isset($options['advancedVideoPreview'])){
							if(TIMTHUMB_URL)
								echo '<img src="'.TIMTHUMB_URL.'?src='.$options['advancedVideoPreview'].'&w=100&h=100" alt="" />';
							else
			      				echo '<img src="'. AitImageResizer::resize($options['advancedVideoPreview'], array('w' => 100, 'h' => 100)) . '" alt="" />';
			      			}
			            break;
			          case "flash":
			            if(isset($options['advancedSwfPreview'])){
							if(TIMTHUMB_URL)
								echo '<img src="'.TIMTHUMB_URL.'?src='.$options['advancedSwfPreview'].'&w=100&h=100" alt="" />';
							else
			      				echo '<img src="' . AitImageResizer::resize($options['advancedSwfPreview'], array('w' => 100, 'h' => 100)) . '" alt="" />';
			      			}
			            break;
	        	}
		  }
		  else
      	  {
	        	if(!empty($options['topImage'])){
					if(TIMTHUMB_URL)
						echo '<img src="'.TIMTHUMB_URL.'?src='.$options['topImage'].'&w=100&h=100" alt="" />';
					else
						echo '<img src="' . AitImageResizer::resize($options['topImage'], array('w' => 100, 'h' => 100)) . '" alt="" />';
	  			}
      	  }
		  break;

		case "slider_back_img":
			if(!empty($options['backgroundImage'])){
				if(TIMTHUMB_URL)
					echo '<img src="'.TIMTHUMB_URL.'?src='.$options['backgroundImage'].'&w=100&h=100" alt="" />';
				else
					echo '<img src="'. AitImageResizer::resize($options['backgroundImage'], array('w' => 100, 'h' => 100)) . '" alt="" />';
			}
			break;
		case "slider_link":
			if(!empty($options['itemType']) && $options['itemType'] == "video"){
				echo $options['videoUrl'];
			} else {
				echo $options['link'];
			}
			break;
	}
}
add_action( "manage_posts_custom_column", "aitsliderCreatorCustomColumns", 10, 2 );

function aitsliderCreatorSortableColumns()
{
  return array(
    'title'=> 'title',
    'category'=> 'category',
    'menu_order'=> 'order',
    'slider_type'=> 'slider_type',
  );
}
add_filter( "manage_edit-ait-slider-creator_sortable_columns", "aitsliderCreatorSortableColumns" );
