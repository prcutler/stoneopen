<?php
function ait_portfolio( $params ) {
    extract( shortcode_atts( array (
        'slug' => 'all',
        'cat_id' => 0,
        'width' => '',
        'height' => '',
        'showdescription' => '',
        'cols' => '1',
    ), $params ) );



    if($cat_id != 0) {
		$args = array( 'numberposts' => 500, 'post_type' => 'ait-portfolio', 'orderby' => 'menu_order', 'order' => 'ASC', 'suppress_filters' => false, 'tax_query' => array( array( 'taxonomy' => 'ait-portfolio-category', 'field' => 'id', 'terms' => $cat_id) ) );
  	} else {
		$args = array( 'numberposts' => 500, 'post_type' => 'ait-portfolio', 'orderby' => 'menu_order', 'order' => 'ASC', 'suppress_filters' => false, 'category' => $cat_id );
  	}

	if ($cols > 4) $cols = 1;

    $portfolio_images = get_posts($args);
    $class = "ait-portfolio";
	$descrAdd = "";
	if (!empty($showdescription)) {
		if ($showdescription == "down") {
			$class .= " desc-down";
		}
		if ($showdescription == "right") {
			$class .= " desc-right";
			$descrAdd = 'style="margin-left: '.($width+4).'px;"';
		}
	}
	$class .= " pf-col".$cols;

    $result = '<div class="'.$class.'">';
    $count = count($portfolio_images);
	$i=0;
    foreach ($portfolio_images as $key => $post_image) {
      //$author = get_the_author_meta('nickname', $post_image->post_author );
      //$post_link = get_permalink( $post_image->ID );
      //$date = mysql2date(get_option('date_format'), $post_image->post_date);
      //$category = get_the_category_list( ', ', $parents = '', $post_image->ID );

      $result .= '<div class="pf-page"><div class="item clearfix">';

	  // ITEM LINK
	  $meta = get_post_meta($post_image->ID, '_ait-portfolio', TRUE);

	  if(is_string($meta) and empty($meta)) $meta = array();
	  $item_link = '';
	  $item_type = '';

	  if(isset( $meta['itemType']) and $meta['itemType'] == "image"){
		$item_link = $meta['imageLink'];
		$item_type = 'image-type';
	  } elseif(isset($meta['itemType']) and $meta['itemType'] == "website"){
		$item_link = $meta['websiteLink'];
		$item_type = 'website-type';
	  } elseif(isset($meta['itemType']) and $meta['itemType'] == "video"){
		$item_link = $meta['videoLink'];
		$item_type = 'video-type';
	  }

	  // ITEM GROUP - CATEGORY
	  $itemGroup = "group-";
	  if($cat_id != '') {
		$itemGroup .= $cat_id;
	  } else {
		$itemGroup .= $slug;
  	  }

      // ITEM THUMBNAIL
      if (get_the_post_thumbnail( $post_image->ID )) {
          $result .= '<div class="image" style="width: '.($width+4).'px;">';
		  if($width != "" && $height != ""){
		  	$result .= '<a data-rel="'.$itemGroup.'" class="zoom '.$item_type.'" href="'.$item_link.'" style="line-height:'.$height.'px; width:'.$width.'px; height:'.$height.'px;">';

		  	$thumbnail_id = get_post_thumbnail_id( $post_image->ID );
          	$thumbnail_args = wp_get_attachment_image_src( $thumbnail_id, 'large' );

			if(TIMTHUMB_URL)
				$result .= '<img width="'.$width.'" height="'.$height.'" src="'.TIMTHUMB_URL.'?src='.$thumbnail_args['0'].'&amp;w='.$width.'&amp;h='.$height.'" alt="" />';
			else
				$result .= '<img width="'.$width.'" height="'.$height.'" src="' . AitImageResizer::resize($thumbnail_args['0'], array('w' => $width, 'h' => $height)) . '" alt="" />';
	  	  } else {
	  	  	$result .= '<a data-rel="'.$itemGroup.'" class="zoom '.$item_type.'" href="'.$item_link.'" style="line-height:150px;" width:150px; height:150px;">';

	  	  	$thumbnail_id = get_post_thumbnail_id( $post_image->ID );
          	$thumbnail_args = wp_get_attachment_image_src( $thumbnail_id, 'large' );

			if(TIMTHUMB_URL)
				$result .= '<img width="150" height="150" src="'.TIMTHUMB_URL.'?src='.$thumbnail_args['0'].'&amp;w=150&amp;h=150" alt="" />';
			else
				$result .= '<img width="150" height="150" src="'. AitImageResizer::resize($thumbnail_args['0'], array('w' => 150, 'h' => 150)) . '" alt="" />';
	  	  }

          $result .= '</a>';
		  if (!empty($showdescription) && ($showdescription == "right" || $showdescription == "down")) {
		    $result .= '<script class="image-description" type="text/template"></script>';
		    $result .= '<h3 '.$descrAdd.'>'.$meta['itemTitle'].'</h3>';
		    if(!empty($meta['itemDescription'])){
				$result .= '<div class="portfolio-item-description" '.$descrAdd.'>'.$meta['itemDescription'].'</div>';
			}
		  } else {
		  	if(isset($meta['itemTitle'])){
		    	$result .= '<script class="image-description" type="text/template">'.$meta['itemTitle'].'</script>';
			} else {
				$result .= '<script class="image-description" type="text/template"></script>';
			}
		  }
          $result .= '</div>';

      }

      $result .= '</div></div>';
	  $i++;
	  if ($cols && ($i%$cols==0)) {
		$result .= '<div class="pf-clear"></div>';
	  }
    }
    $result .= '</div><!-- end of .ait-portfolio -->';
    wp_reset_query();
    return $result;
}
add_shortcode( "ait-portfolio", "ait_portfolio" );