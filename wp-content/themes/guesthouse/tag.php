<?php

$latteParams['bodyClasses'] .= ' with-sidebar';
$latteParams['bodyId'] = 'normal-page';

$latteParams['tag'] = new WpLatteTagEntity($GLOBALS['wp_query']->queried_object);

$latteParams['posts'] = WpLatte::createPostEntity($GLOBALS['wp_query']->posts);

$latteParams['customTypeName'] = defined('EDITABLE_CT_NAME') ? EDITABLE_CT_NAME : '';
$latteParams['customTypeShow'] = defined('SHOW_EDITABLE_CT_IN_MENU') ? SHOW_EDITABLE_CT_IN_MENU : '';
$latteParams['roomTypeName'] = defined('ROOM_CT_NAME') ? ROOM_CT_NAME : '';

/* GET CATEGORIES FOR GROUPING IN SLIDER */
$sliderOptions = aitGetOptions($latteParams['post'],'page_slider');
if($sliderOptions->sliderFormCat == "0"){
  $categories = array();
  $term = $wpdb->get_results( "SELECT * FROM " .$wpdb->prefix . "term_taxonomy WHERE `taxonomy` LIKE 'ait-item-category'" );
  foreach($term as $item){
    if($item->term_id == 1){
    } else {
      $cat = $wpdb->get_results( "SELECT * FROM " .$wpdb->prefix . "wp_terms WHERE `term_id` =".$item->term_id);
      array_push($categories, $cat[0]->name);
    }
  }
}
$latteParams['itemSliderCategories'] = $categories;
/* GET CATEGORIES FOR GROUPING IN SLIDER */

WPLatte::createTemplate(basename(__FILE__, '.php'), $latteParams)->render();
