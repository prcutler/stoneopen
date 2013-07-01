<?php
function get_custom_room( $params ) {
    extract( shortcode_atts( array (
        'id' => '',
		'excerpt' => 290,
		'display' => '1',
		'readmore' => 'no',
		'readmoretext' => 'Read more'
    ), $params ) );
    if ($id) {
        $latest_post['0'] = get_post($id);
    }
    else {
        $latest_post = query_posts( 'post_type=ait-room&p=' . $id );
        wp_reset_query();
    }

    if($display == "1"){
        // type 1 -> like normal rooms loop
        $result = '<section id="rooms" class="clear">';
        $count = count($latest_posts);

        $author = get_the_author_meta('nickname', $latest_post['0']->post_author );
        $post_link = get_permalink( $latest_post['0']->ID );
        $date = mysql2date(get_option('date_format'), $latest_post['0']->post_date);
        $category = get_the_category_list( ', ', $parents = '', $latest_post['0']->ID );

          $result .= '<div class="item clearfix shortcodeCustomType">';
          // POST THUMBNAIL
          if (has_post_thumbnail( $latest_post['0']->ID )) {
              $result .= '<article class="ait-room type-ait-room status-publish hentry thumbnail pf-page">';
              $result .= '<header class="entry-header">';
              $result .= '<div class="entry-thumbnail">';
              $result .= '<div class="entry-thumb-img">';
              $result .= '<a href="' . $post_link . '">';
			if(TIMTHUMB_URL)
				$result .= '<img src="'.TIMTHUMB_URL . '?src='.wp_get_attachment_url( get_post_thumbnail_id($latest_post['0']->ID) ).'&amp;w=309&amp;h=120" alt="'.$latest_post['0']->post_title.'" />';
            else
				$result .= '<img src="' . AitImageResizer::resize(wp_get_attachment_url(get_post_thumbnail_id($latest_post['0']->ID)), array('w' => 309, 'h' => 120)) . '" alt="'.$latest_post['0']->post_title.'" />';
              $result .= '</a>';
              $result .= '</div>';
              $result .= '</div>';
              $result .= '</header>';
              $result .= '<h2 class="entry-title"><a href="' . $post_link . '" title="Permalink to '.$latest_post['0']->post_title.'" rel="Bookmark">'.$latest_post['0']->post_title.'</a></h2>';
              if(is_search()){
                $result .= '<div class="entry-summary">';
                $result .= $latest_post['0']->post_excerpt;
                $result .= '</div>';
              } else {
                $result .= '<div class="entry-content thumbnail">';
                if($latest_post['0']->post_excerpt != ""){
                  $result .= $latest_post['0']->post_excerpt;
                } else {
                  $result .= "";
                }
                $result .= '</div>';
              }
              $result .= '</article>';
          } else {
              $result .= '<article class="no-thumbnail pf-page">';
              $result .= '<header class="entry-header"></header>';
              $result .= '<h2 class="entry-title no-thumbnail"><a href="' . $post_link . '" title="Permalink to '.$latest_post['0']->post_title.'" rel="Bookmark">'.$latest_post['0']->post_title.'</a></h2>';
              if(is_search()){
                $result .= '<div class="entry-summary">';
                $result .= $latest_post['0']->post_excerpt;
                $result .= '</div>';
              } else {
                $result .= '<div class="entry-content no-thumbnail">';
                if($latest_post['0']->post_excerpt != ""){
                  $result .= $latest_post['0']->post_excerpt;
                } else {
                  $result .= "";
                }
                $result .= '</div>';
              }
              $result .= '</article>';
          }
          $result .= '</div>';

        $result .= '</section>';
      } else {
        $author = get_the_author_meta('nickname', $latest_post['0']->post_author );
        $post_link = get_permalink( $latest_post['0']->ID );
        $date = mysql2date(get_option('date_format'), $latest_post['0']->post_date);
        $category = get_the_category_list( ', ', $parents = '', $latest_post['0']->ID );

        $result = '<div class="sc-page"><div class="item clearfix">';
        // POST THUMBNAIL
        if (get_the_post_thumbnail( $latest_post['0']->ID, 'thumbnail' )) {
            $result .= '<div class="image">';
            $result .= '<a href="' . $post_link . '" class="greyscale">';
            $result .= get_the_post_thumbnail( $latest_post['0']->ID, 'thumbnail' );
            $result .= '</a>';
            $result .= '</div>';
        }

        // POST BODY
        $result .= '<div class="text">';
        $result .= '<div class="title"><h3><a href="' . $post_link. '">' . $latest_post['0']->post_title . '</a></h3></div>';
        if ( $latest_post['0']->post_excerpt ) {
            $result .= '<p>' . $latest_post['0']->post_excerpt . '</p>';
        }
        else {
            $limit = $excerpt;
            $my_text = substr($latest_post['0']->post_content, 0, $limit);
            $pos = strrpos($my_text, " ");
            $my_post_text = substr($my_text, 0, ($pos ? $pos : -1)) . "...";
    		$read = "";
        if($readmore == 'yes'){
    			$read = '&nbsp;<a href="' . $post_link. '">'.$readmoretext.'</a>';
    		}
            $result .= '<p>' . strip_tags($my_post_text) . $read .'</p>';
            //$result .= '<p>' . substr_replace( $latest_post['0']->post_content, '...', 350 ) . '</p>';
        }

        $result .= '</div><!-- /.text -->';

        $result .= '</div></div>';
      }

    return $result;
}
add_shortcode( "get_room", "get_custom_room" );

function get_custom_rooms( $params ) {
    extract( shortcode_atts( array (
        'number' => '1',
		'excerpt' => 290,
		'display' => '1',
		'readmore' => 'no',
		'readmoretext' => 'Read more'
    ), $params ) );

    $latest_posts = query_posts( array( 'post_type' => 'ait-room', 'orderby' => 'menu_order', 'order' => 'ASC', 'posts_per_page' => $number )  );
    wp_reset_query();

    if($display == "1"){
        // type 1 -> like normal rooms loop
        $result = '<section id="rooms" class="clear">';
        $count = count($latest_posts);
        foreach ($latest_posts as $key => $latest_post) {
          $author = get_the_author_meta('nickname', $latest_post->post_author );
          $post_link = get_permalink( $latest_post->ID );
          $date = mysql2date(get_option('date_format'), $latest_post->post_date);
          $category = get_the_category_list( ', ', $parents = '', $latest_post->ID );

          $result .= '<div class="item clearfix shortcodeCustomType">';
          // POST THUMBNAIL
          if (has_post_thumbnail( $latest_post->ID )) {
              $result .= '<article class="ait-room type-ait-room status-publish hentry thumbnail pf-page">';
              $result .= '<header class="entry-header">';
              $result .= '<div class="entry-thumbnail">';
              $result .= '<div class="entry-thumb-img">';
              $result .= '<a href="' . $post_link . '">';
			if(TIMTHUMB_URL)
				$result .= '<img src="' . TIMTHUMB_URL . '?src='.wp_get_attachment_url( get_post_thumbnail_id($latest_post->ID) ).'&amp;w=309&amp;h=120" alt="'.$latest_post->post_title.'" />';
            else
				$result .= '<img src="' . AitImageResizer::resize(wp_get_attachment_url(get_post_thumbnail_id($latest_post->ID)), array('w' => 309, 'h' => 120)) . '" alt="'.$latest_post->post_title.'" />';
              $result .= '</a>';
              $result .= '</div>';
              $result .= '</div>';
              $result .= '</header>';
              $result .= '<h2 class="entry-title"><a href="' . $post_link . '" title="Permalink to '.$latest_post->post_title.'" rel="Bookmark">'.$latest_post->post_title.'</a></h2>';
              if(is_search()){
                $result .= '<div class="entry-summary">';
                $result .= $latest_post->post_excerpt;
                $result .= '</div>';
              } else {
                $result .= '<div class="entry-content thumbnail">';
                if($latest_post->post_excerpt != ""){
                  $result .= $latest_post->post_excerpt;
                } else {
                  $result .= "";
                }
                $result .= '</div>';
              }
              $result .= '</article>';
          } else {
              $result .= '<article class="no-thumbnail pf-page">';
              $result .= '<header class="entry-header"></header>';
              $result .= '<h2 class="entry-title no-thumbnail"><a href="' . $post_link . '" title="Permalink to '.$latest_post->post_title.'" rel="Bookmark">'.$latest_post->post_title.'</a></h2>';
              if(is_search()){
                $result .= '<div class="entry-summary">';
                $result .= $latest_post->post_excerpt;
                $result .= '</div>';
              } else {
                $result .= '<div class="entry-content no-thumbnail">';
                if($latest_post->post_excerpt != ""){
                  $result .= $latest_post->post_excerpt;
                } else {
                  $result .= "";
                }
                $result .= '</div>';
              }
              $result .= '</article>';
          }
          $result .= '</div>';
          }
        $result .= '</section>';
      } else {

        $result = '<div class="latest-posts">';
        $count = count($latest_posts);
        foreach ($latest_posts as $key => $latest_post) {
          $author = get_the_author_meta('nickname', $latest_post->post_author );
          $post_link = get_permalink( $latest_post->ID );
          $date = mysql2date(get_option('date_format'), $latest_post->post_date);
          $category = get_the_category_list( ', ', $parents = '', $latest_post->ID );

          $result .= '<div class="sc-page"><div class="item clearfix">';
          // POST THUMBNAIL
          if (get_the_post_thumbnail( $latest_post->ID, 'thumbnail' )) {
              $result .= '<div class="image">';
              $result .= '<a href="' . $post_link . '" class="greyscale">';
              $result .= get_the_post_thumbnail( $latest_post->ID, 'thumbnail' );
              $result .= '</a>';
              $result .= '</div>';
          }

          // POST BODY
          $result .= '<div class="text">';
          $result .= '<div class="title"><h3><a href="' . $post_link. '">' . $latest_post->post_title . '</a></h3></div>';
          if ( $latest_post->post_excerpt ) {
              $result .= '<p>' . $latest_post->post_excerpt . '</p>';
          }
          else {
              $limit = $excerpt;
              $my_text = substr($latest_post->post_content, 0, $limit);
              $pos = strrpos($my_text, " ");
              $my_post_text = substr($my_text, 0, ($pos ? $pos : -1)) . "...";
              $read = "";
    		  if($readmore == 'yes'){
    			$read = '&nbsp;<a href="' . $post_link. '">'.$readmoretext.'</a>';
    	  	  }
              $result .= '<p>' . strip_tags($my_post_text) . $read . '</p>';
              //$result .= '<p>' . substr_replace( $latest_post['0']->post_content, '...', 350 ) . '</p>';
          }

          $result .= '</div><!-- /.text -->';

          if ($count - 1 != $key)
          $result .= do_shortcode('[rule]');

    	  $result .= '</div></div>';
        }
        $result .= '</div>';
    }
    return $result;
}
add_shortcode( "get_rooms", "get_custom_rooms" );

function get_category_rooms( $params ) {
    extract( shortcode_atts( array (
        'number' => '1',
        'category' => '1',
        'excerpt' => 290,
        'display' => '1',
        'readmore' => 'no',
		    'readmoretext' => 'Read more'
    ), $params ) );

    $latest_posts = query_posts( array( 'post_type' => 'ait-room', 'orderby' => 'menu_order', 'order' => 'ASC', 'posts_per_page' => $number, 'tax_query' => array( array( 'taxonomy' => 'ait-room-category', 'field' => 'id', 'terms' => $category) ) )  );
    wp_reset_query();

    if($display == "1"){
        // type 1 -> like normal rooms loop
        $result = '<section id="rooms" class="clear">';
        $count = count($latest_posts);
        foreach ($latest_posts as $key => $latest_post) {
          $author = get_the_author_meta('nickname', $latest_post->post_author );
          $post_link = get_permalink( $latest_post->ID );
          $date = mysql2date(get_option('date_format'), $latest_post->post_date);
          $category = get_the_category_list( ', ', $parents = '', $latest_post->ID );

          $result .= '<div class="item clearfix shortcodeCustomType">';
          // POST THUMBNAIL
          //var_dump(get_bloginfo('template_url'));
          if (has_post_thumbnail( $latest_post->ID )) {
              $result .= '<article class="ait-room type-ait-room status-publish hentry thumbnail pf-page">';
              $result .= '<header class="entry-header">';
              $result .= '<div class="entry-thumbnail">';
              $result .= '<div class="entry-thumb-img">';
              $result .= '<a href="' . $post_link . '">';
			if(TIMTHUMB_URL)
				$result .= '<img src="' . TIMTHUMB_URL . '?src='.wp_get_attachment_url( get_post_thumbnail_id($latest_post->ID) ).'&amp;w=309&amp;h=120" alt="'.$latest_post->post_title.'" />';
			else
				$result .= '<img src="' . AitImageResizer::resize(wp_get_attachment_url(get_post_thumbnail_id($latest_post->ID)), array('w' => 309, 'h' => 120)) . '"'.$latest_post->post_title.'" />';
              $result .= '</a>';
              $result .= '</div>';
              $result .= '</div>';
              $result .= '</header>';
              $result .= '<h2 class="entry-title"><a href="' . $post_link . '" title="Permalink to '.$latest_post->post_title.'" rel="Bookmark">'.$latest_post->post_title.'</a></h2>';
              if(is_search()){
                $result .= '<div class="entry-summary">';
                $result .= $latest_post->post_excerpt;
                $result .= '</div>';
              } else {
                $result .= '<div class="entry-content thumbnail">';
                if($latest_post->post_excerpt != ""){
                  $result .= $latest_post->post_excerpt;
                } else {
                  $result .= "";
                }
                $result .= '</div>';
              }
              $result .= '</article>';
          } else {
              $result .= '<article class="no-thumbnail pf-page">';
              $result .= '<header class="entry-header"></header>';
              $result .= '<h2 class="entry-title no-thumbnail"><a href="' . $post_link . '" title="Permalink to '.$latest_post->post_title.'" rel="Bookmark">'.$latest_post->post_title.'</a></h2>';
              if(is_search()){
                $result .= '<div class="entry-summary">';
                $result .= $latest_post->post_excerpt;
                $result .= '</div>';
              } else {
                $result .= '<div class="entry-content no-thumbnail">';
                if($latest_post->post_excerpt != ""){
                  $result .= $latest_post->post_excerpt;
                } else {
                  $result .= "";
                }
                $result .= '</div>';
              }
              $result .= '</article>';
          }
          $result .= '</div>';
          }
        $result .= '</section>';
      } else {
        // type 2 -> default post display
        $result = '<div class="latest-posts">';
        $count = count($latest_posts);
        foreach ($latest_posts as $key => $latest_post) {
          $author = get_the_author_meta('nickname', $latest_post->post_author );
          $post_link = get_permalink( $latest_post->ID );
          $date = mysql2date(get_option('date_format'), $latest_post->post_date);
          $category = get_the_category_list( ', ', $parents = '', $latest_post->ID );

          $result .= '<div class="sc-page"><div class="item clearfix">';
          // POST THUMBNAIL
          if (get_the_post_thumbnail( $latest_post->ID, 'thumbnail' )) {
              $result .= '<div class="image">';
              $result .= '<a href="' . $post_link . '" class="greyscale">';
              $result .= get_the_post_thumbnail( $latest_post->ID, 'thumbnail' );
              $result .= '</a>';
              $result .= '</div>';
          }

          // POST BODY
          $result .= '<div class="text">';
          $result .= '<div class="title"><h3><a href="' . $post_link. '">' . $latest_post->post_title . '</a></h3></div>';
          if ( $latest_post->post_excerpt ) {
              $result .= '<p>' . $latest_post->post_excerpt . '</p>';
          }
          else {
              $limit = $excerpt;
              $my_text = substr($latest_post->post_content, 0, $limit);
              $pos = strrpos($my_text, " ");
              $my_post_text = substr($my_text, 0, ($pos ? $pos : -1)) . "...";
    		  $read="";
          if($readmore == 'yes'){
    			$read = '&nbsp;<a href="' . $post_link. '">'.$readmoretext.'</a>';
    	  	  }
              $result .= '<p>' . strip_tags($my_post_text) . $read . '</p>';
              //$result .= '<p>' . substr_replace( $latest_post['0']->post_content, '...', 350 ) . '</p>';
          }

          $result .= '</div><!-- /.text -->';

    	  if ($count - 1 != $key)
          $result .= do_shortcode('[rule]');

          $result .= '</div></div>';
        }
        $result .= '</div>';
    }

    return $result;
}
add_shortcode( "get_room_category", "get_category_rooms" );
