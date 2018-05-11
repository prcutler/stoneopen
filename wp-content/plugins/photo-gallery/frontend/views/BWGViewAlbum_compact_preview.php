<?php
class BWGViewAlbum_compact_preview {
  public function display($params, $from_shortcode = 0, $bwg = 0) {
    require_once(BWG()->plugin_dir . '/framework/WDWLibrary.php');

    $theme_id = $params['theme_id'];
    $order_by = $params['order_by'];
    $album_view_type = $params['compuct_album_view_type'];

    $image_right_click = isset(BWG()->options->image_right_click) ? BWG()->options->image_right_click : 0;
    $placeholder = $params['placeholder'];
    $gallery_download = $params['gallery_download'];
    $play_icon = $params['play_icon'];

    $type = (isset($_REQUEST['type_' . $bwg]) ? esc_html($_REQUEST['type_' . $bwg]) : (isset($params['type']) ? $params['type'] : 'album'));
    $bwg_search = ((isset($_POST['bwg_search_' . $bwg]) && esc_html($_POST['bwg_search_' . $bwg]) != '') ? esc_html($_POST['bwg_search_' . $bwg]) : '');
    
    $from = (isset($params['from']) ? esc_html($params['from']) : 0);

	  $theme_row = WDWLibrary::get_theme_row_data($theme_id);
    if (!$theme_row) {
      echo WDWLibrary::message(__('There is no theme selected or the theme was deleted.', BWG()->prefix), 'wd_error');
      return;
    }
    if (!isset($theme_row->album_compact_gal_title_font_color)) {
      $theme_row->album_compact_gal_title_font_color = 'CCCCCC';
    }
    if (!isset($theme_row->album_compact_gal_title_font_style)) {
      $theme_row->album_compact_gal_title_font_style = 'segoe ui';
    }
    if (!isset($theme_row->album_compact_gal_title_font_size)) {
      $theme_row->album_compact_gal_title_font_size = 16;
    }
    if (!isset($theme_row->album_compact_gal_title_font_weight)) {
      $theme_row->album_compact_gal_title_font_weight = 'bold';
    }
    if (!isset($theme_row->album_compact_gal_title_margin)) {
      $theme_row->album_compact_gal_title_margin = '2px';
    }
    if (!isset($theme_row->album_compact_gal_title_shadow)) {
      $theme_row->album_compact_gal_title_shadow = '0px 0px 0px #888888';
    }
    if (!isset($theme_row->album_compact_gal_title_align)) {
      $theme_row->album_compact_gal_title_align = 'center';
    }

    $album_gallery_id = (isset($_REQUEST['album_gallery_id_' . $bwg]) ? esc_html($_REQUEST['album_gallery_id_' . $bwg]) : $params['album_id']);
    $album_row = WDWLibrary::get_album_row_data($album_gallery_id, $from === "widget");
    if ( $type == 'album' && !$album_row ) {
      echo WDWLibrary::message(__('There is no album selected or the gallery was deleted.', BWG()->prefix), 'wd_error');
      return;
    }

    if ($type == 'gallery') {
      $items_per_page = $params['compuct_album_images_per_page'];
      $items_per_page_arr = array('images_per_page' => $params['compuct_album_images_per_page'], 'load_more_image_count' => $params['compuct_album_images_per_page']);
      $items_col_num = $params['compuct_album_image_column_number'];
      if (isset($_POST['sortImagesByValue_' . $bwg])) {
        $sort_by = esc_html($_POST['sortImagesByValue_' . $bwg]);
        if ($sort_by == 'random') {
          $params['sort_by'] = 'RAND()';
        }
        else if ($sort_by == 'default')  {
          $params['sort_by'] = $params['sort_by'];
        }
        else {
          $params['sort_by'] = $sort_by;
        }
      }
      $image_rows = WDWLibrary::get_image_rows_data($album_gallery_id, $bwg, 'album_compact', 'bwg_tag_id_bwg_album_compact_' . $bwg, '', $items_per_page, $params['compuct_album_images_per_page'], $params['sort_by'], $order_by);

      $page_nav = $image_rows['page_nav'];
      $image_rows = $image_rows['images'];
      $images_count = count($image_rows);
      if (!$images_count) {
        echo WDWLibrary::message(__('There are no images in this gallery.', BWG()->prefix), 'wd_error');
      }
      $album_gallery_div_id = 'bwg_album_compact_' . $bwg;
      $album_gallery_div_class = 'bwg_standart_thumbnails_' . $bwg;
    }
    else {
      $items_per_page = $params['compuct_albums_per_page'];
      $items_per_page_arr = array('images_per_page' => $params['compuct_albums_per_page'], 'load_more_image_count' => $params['compuct_albums_per_page']);
      $items_col_num = $params['compuct_album_column_number'];
      $sort_by = $params['sort_by'];
      $album_galleries_row = WDWLibrary::get_alb_gals_row($album_gallery_id, $items_per_page, $sort_by, $bwg, $order_by);
      $page_nav = $album_galleries_row['page_nav'];
      $album_galleries_row = $album_galleries_row['rows'];
      if (!$album_galleries_row) {
        echo WDWLibrary::message(__('There is no album selected or the gallery was deleted.', BWG()->prefix), 'wd_error');
        return;
      }
      $album_gallery_div_id = 'bwg_album_compact_' . $bwg;
      $album_gallery_div_class = 'bwg_album_thumbnails_' . $bwg;
    }
	
    if ($type == 'gallery' ) {
      if ( $album_view_type == 'masonry' ) {
        $form_child_div_style = 'background-color:rgba(0, 0, 0, 0); position:relative; text-align:' . $theme_row->masonry_thumb_align . '; width:100%;';
        $form_child_div_id = 'bwg_masonry_thumbnails_div_' . $bwg;
        $album_gallery_div_id = 'bwg_masonry_thumbnails_' . $bwg;
        $album_gallery_div_class = 'bwg_masonry_thumbnails_' . $bwg;
      }
      else if ( $album_view_type == 'mosaic' ) {
        $form_child_div_style = 'background-color:rgba(0, 0, 0, 0); position:relative; text-align:' . $theme_row->mosaic_thumb_align . '; width:100%;';
        $form_child_div_id = 'bwg_mosaic_thumbnails_div_' . $bwg;
        $album_gallery_div_id = 'bwg_mosaic_thumbnails_' . $bwg;
        $album_gallery_div_class = 'bwg_mosaic_thumbnails_' . $bwg;
      }
      else { 
        $form_child_div_style = 'background-color:rgba(0, 0, 0, 0); position:relative; text-align:' . $theme_row->thumb_align . '; width:100%;';
        $form_child_div_id = '';
      }
    }
    else {
      $form_child_div_id = '';
      $form_child_div_style = 'background-color:rgba(0, 0, 0, 0); position:relative; text-align:' . $theme_row->album_compact_thumb_align . '; width:100%;';
    }

    $bwg_previous_album_id = (isset($_REQUEST['bwg_previous_album_id_' . $bwg]) ? esc_html($_REQUEST['bwg_previous_album_id_' . $bwg]) : $params['album_id']);
    $bwg_previous_album_page_number = (isset($_REQUEST['bwg_previous_album_page_number_' . $bwg]) ? esc_html($_REQUEST['bwg_previous_album_page_number_' . $bwg]) : 0);

    $params_array = array(
      'action' => 'GalleryBox',
      'current_view' => $bwg,
      'theme_id' => $params['theme_id'],
      'thumb_width' => $params['compuct_album_image_thumb_width'],
      'thumb_height' => $params['compuct_album_image_thumb_height'],
      'open_with_fullscreen' => $params['popup_fullscreen'],
      'open_with_autoplay' => $params['popup_autoplay'],
      'image_width' => $params['popup_width'],
      'image_height' => $params['popup_height'],
      'image_effect' => $params['popup_effect'],
      'wd_sor' => $params['sort_by'],
      'wd_ord' => $params['order_by'],
      'enable_image_filmstrip' => $params['popup_enable_filmstrip'],
      'image_filmstrip_height' => $params['popup_filmstrip_height'],
      'enable_image_ctrl_btn' => $params['popup_enable_ctrl_btn'],
      'enable_image_fullscreen' => $params['popup_enable_fullscreen'],
      'popup_enable_info' => $params['popup_enable_info'],
      'popup_info_always_show' => $params['popup_info_always_show'],
      'popup_info_full_width' => $params['popup_info_full_width'],
      'popup_hit_counter' => $params['popup_hit_counter'],
      'popup_enable_rate' => $params['popup_enable_rate'],
      'slideshow_interval' => $params['popup_interval'],
      'enable_comment_social' => $params['popup_enable_comment'],
      'enable_image_facebook' => $params['popup_enable_facebook'],
      'enable_image_twitter' => $params['popup_enable_twitter'],
      'enable_image_google' => $params['popup_enable_google'],
      'enable_image_pinterest' => $params['popup_enable_pinterest'],
      'enable_image_tumblr' => $params['popup_enable_tumblr'],
      'watermark_type' => $params['watermark_type'],
      'slideshow_effect_duration' => isset($params['popup_effect_duration']) ? $params['popup_effect_duration'] : 1,
    );
    if ( BWG()->is_pro ) {
      $current_url = (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
      $params_array['current_url'] = urlencode($current_url);
      $params_array['enable_image_ecommerce'] = $params['popup_enable_ecommerce'];
    }
    if ($params['watermark_type'] != 'none') {
      $params_array['watermark_link'] = $params['watermark_link'];
      $params_array['watermark_opacity'] = $params['watermark_opacity'];
      $params_array['watermark_position'] = $params['watermark_position'];
    }
    if ($params['watermark_type'] == 'text') {
      $params_array['watermark_text'] = $params['watermark_text'];
      $params_array['watermark_font_size'] = $params['watermark_font_size'];
      $params_array['watermark_font'] = $params['watermark_font'];
      $params_array['watermark_color'] = $params['watermark_color'];
    }
    elseif ($params['watermark_type'] == 'image') {
      $params_array['watermark_url'] = $params['watermark_url'];
      $params_array['watermark_width'] = $params['watermark_width'];
      $params_array['watermark_height'] = $params['watermark_height'];
    }
    $tags_rows = WDWLibrary::get_tags_rows_data($album_gallery_id);
    $inline_style = $this->inline_styles($bwg, $theme_row, $params, $album_gallery_div_class, $items_col_num, $album_view_type);

	  if (BWG()->options->use_inline_stiles_and_scripts) {
      wp_enqueue_style('bwg_frontend');
      wp_add_inline_style('bwg_frontend', $inline_style);
      wp_enqueue_style('bwg_font-awesome');
      wp_enqueue_style('bwg_mCustomScrollbar');
      wp_enqueue_style('bwg_googlefonts');
      if (isset($params['show_tag_box']) && $params['show_tag_box']) {
        wp_enqueue_style('bwg_sumoselect');
        if (!wp_script_is('bwg_sumoselect', 'done')) {
          wp_print_scripts('bwg_sumoselect');
        }
      }
      if (!wp_script_is('bwg_frontend', 'done')) {
        wp_print_scripts('bwg_frontend');
      }
      if ($params['thumb_click_action'] == 'open_lightbox') {
        if (!wp_script_is('bwg_mCustomScrollbar', 'done')) {
          wp_print_scripts('bwg_mCustomScrollbar');
        }
        if (!wp_script_is('jquery-fullscreen', 'done')) {
          wp_print_scripts('jquery-fullscreen');
        }
        if (!wp_script_is('bwg_gallery_box', 'done')) {
          wp_print_scripts('bwg_gallery_box');
        }
        if ( BWG()->is_pro && !wp_script_is('bwg_raty', 'done') ) {
          wp_print_scripts('bwg_raty');
        }
      }
      if (!wp_script_is('bwg_jquery_mobile', 'done')) {
        wp_print_scripts('bwg_jquery_mobile');
      }
    }
    else {
      echo '<style>' . $inline_style . '</style>';
    }
    ?>
    <div id="bwg_container1_<?php echo $bwg; ?>">
      <div id="bwg_container2_<?php echo $bwg; ?>">
        <form id="gal_front_form_<?php echo $bwg; ?>" method="post" action="#" data-current="<?php echo $bwg; ?>">
          <?php
          if ($params['show_search_box'] && $type == 'gallery') {
            WDWLibrary::ajax_html_frontend_search_box('gal_front_form_' . $bwg, $bwg, $album_gallery_div_id, $images_count, $params['search_box_width'], $placeholder,$album_gallery_id);
          }
          if (isset($params['show_sort_images']) && $params['show_sort_images'] && $type == 'gallery') {
            WDWLibrary::ajax_html_frontend_sort_box('gal_front_form_' . $bwg, $bwg, $album_gallery_div_id, $params['sort_by'], $params['search_box_width']);
          }
          if (isset($params['show_tag_box']) && $params['show_tag_box'] && $type == 'gallery') {
            WDWLibrary::ajax_html_frontend_search_tags('gal_front_form_' . $bwg, $bwg, $album_gallery_div_id, $images_count, $tags_rows);
          }
          ?>
          <div id="<?php echo $form_child_div_id; ?>" style="<?php echo $form_child_div_style; ?>">
            <div id="ajax_loading_<?php echo $bwg; ?>" style="position:absolute;width: 100%; z-index: 115; text-align: center; height: 100%; vertical-align: middle; display: none;">
              <div style="display: table; vertical-align: middle; width: 100%; height: 100%; background-color: #FFFFFF; opacity: 0.7; filter: Alpha(opacity=70);">
                <div style="display: table-cell; text-align: center; position: relative; vertical-align: middle;" >
                  <div id="loading_div_<?php echo $bwg; ?>" class="bwg_spider_ajax_loading" style="display: inline-block; text-align:center; position:relative; vertical-align:middle; background-image:url(<?php echo BWG()->plugin_url . '/images/ajax_loader.gif'; ?>); float: none; width:30px;height:30px;background-size:30px 30px;">
                  </div>
                </div>
              </div>
            </div>
            <?php
            if ($params['compuct_album_enable_page'] && $items_per_page && ($theme_row->page_nav_position == 'top') && $page_nav['total']) {
              WDWLibrary::ajax_html_frontend_page_nav($theme_row, $page_nav['total'], $page_nav['limit'], 'gal_front_form_' . $bwg, $items_per_page_arr, $bwg, $album_gallery_div_id, $params['album_id'], $type, BWG()->options->enable_seo, $params['compuct_album_enable_page']);
            }
            if ($bwg_previous_album_id != $params['album_id']) {
              ?>
              <a class="bwg_back_<?php echo $bwg; ?>" onclick="spider_frontend_ajax('gal_front_form_<?php echo $bwg; ?>', '<?php echo $bwg; ?>', '<?php echo $album_gallery_div_id; ?>', 'back', '', 'album')"><?php echo __('Back', BWG()->prefix); ?></a>
              <?php
            }
            if ($params['show_album_name']) {
              if ($type == 'gallery') {
                ?>
              <div class="bwg_gal_title_<?php echo $bwg; ?>" ><?php echo isset($_POST['title_' . $bwg]) ? esc_html($_POST['title_' . $bwg]) : ''; ?></div>
                <?php
              }
              else {
                ?>
              <div class="bwg_back_<?php echo $bwg; ?>"><?php 	echo $album_gallery_id ? $album_row->name:__('All Galleries', BWG()->prefix); ?></div>
                <?php
              }
            }
            ?>
            <div id="<?php echo $album_gallery_div_id; ?>" class="<?php echo $album_gallery_div_class; ?>">
              <input type="hidden" id="bwg_previous_album_id_<?php echo $bwg; ?>" name="bwg_previous_album_id_<?php echo $bwg; ?>" value="<?php echo $bwg_previous_album_id; ?>" />
              <input type="hidden" id="bwg_previous_album_page_number_<?php echo $bwg; ?>" name="bwg_previous_album_page_number_<?php echo $bwg; ?>" value="<?php echo $bwg_previous_album_page_number; ?>" />
              <?php 
              if ($type != 'gallery') {

                if (!$page_nav['total']) {
                  ?>
                  <span class="bwg_back_<?php echo $bwg; ?>"><?php echo __('Album is empty.', BWG()->prefix); ?></span>
                  <?php
                }
                foreach ($album_galleries_row as $album_galallery_row) {
                    // Check if selected All Galleries
                    if( $album_gallery_id ) {
                        if ($album_galallery_row->is_album) {
                            $album_row = WDWLibrary::get_album_row_data($album_galallery_row->alb_gal_id, $from === "widget");
                            if (!$album_row) {
                                continue;
                            }
                            $preview_image = $album_row->preview_image;
                            if (!$preview_image) {
                                $preview_image = $album_row->random_preview_image;
                            }
                            $def_type = 'album';
                            $title = $album_row->name;
                            $permalink = isset($album_row->permalink) ? $album_row->permalink : '';
                        } else {
                            $gallery_row = WDWLibrary::get_gallery_row_data($album_galallery_row->alb_gal_id, ($from === "widget" ? "compact" : ""));
                            if (!$gallery_row) {
                                continue;
                            }
                            $preview_image = $gallery_row->preview_image;
                            if (!$preview_image) {
                                $preview_image = $gallery_row->random_preview_image;
                            }
                            $def_type = 'gallery';
                            $title = $gallery_row->name;
                            $permalink = isset($gallery_row->permalink) ? $gallery_row->permalink : '';
                        }
                    }
                    else {
                        $preview_image = $album_galallery_row->preview_image;
                        if (!$preview_image) {
                            $preview_image = $album_galallery_row->random_preview_image;
                        }
                        $def_type = 'gallery';
                        $title = $album_galallery_row->name;
                        $permalink = isset($album_galallery_row->permalink) ? $album_galallery_row->permalink : '';
                    }

                  $local_preview_image = true;
                  $parsed_prev_url = parse_url($preview_image, PHP_URL_SCHEME);
                  
                  if($parsed_prev_url =='http' || $parsed_prev_url =='https'){
                    $local_preview_image = false;
                  }

                  if (!$preview_image) {
                    $preview_url = BWG()->plugin_url . '/images/no-image.png';
                    $preview_path = BWG()->plugin_dir . '/images/no-image.png';
                  }
                  else {
                    if($local_preview_image){
                      $preview_url = site_url() . '/' . BWG()->upload_dir . $preview_image;
                      $preview_path = ABSPATH . BWG()->upload_dir . $preview_image;
                    }
                    else{
                      $preview_url = $preview_image;
                      $preview_path = $preview_image;
                    }
                  }
                  if($local_preview_image){
                    $preview_path_url = htmlspecialchars_decode($preview_path, ENT_COMPAT | ENT_QUOTES);
                    $preview_path_url = explode('?bwg',$preview_path_url);
                    list($image_thumb_width, $image_thumb_height) = getimagesize($preview_path_url[0]);
                    $scale = max($params['compuct_album_thumb_width'] / $image_thumb_width, $params['compuct_album_thumb_height'] / $image_thumb_height);
                    $image_thumb_width *= $scale;
                    $image_thumb_height *= $scale;
                    $thumb_left = ($params['compuct_album_thumb_width'] - $image_thumb_width) / 2;
                    $thumb_top = ($params['compuct_album_thumb_height'] - $image_thumb_height) / 2;
                  }
                  else{
                    $image_thumb_width = $params['compuct_album_thumb_width'];
                    $image_thumb_height = $params['compuct_album_thumb_height'];
                    $thumb_left = 0;
                    $thumb_top = 0;
                  }
                  if ($type != 'gallery') {
                    ?>
                    <a class="bwg_album_<?php echo $bwg; ?>" <?php echo ($from !== "widget" ? (BWG()->options->enable_seo ? "href='" . esc_url(add_query_arg(array("type_" . $bwg => $def_type, "album_gallery_id_" . $bwg => (($album_gallery_id != 0) ? $album_galallery_row->alb_gal_id : $album_galallery_row->id), "bwg_previous_album_id_" . $bwg => $album_gallery_id . ',' . $bwg_previous_album_id , "bwg_previous_album_page_number_" . $bwg => (isset($_REQUEST['page_number_' . $bwg]) ? esc_html($_REQUEST['page_number_' . $bwg]) : 0) . ',' . $bwg_previous_album_page_number), $_SERVER['REQUEST_URI'])) . "'" : "") . " data-alb_gal_id=\"" . (($album_gallery_id != 0) ? $album_galallery_row->alb_gal_id : $album_galallery_row->id) . "\" data-def_type=\"" . $def_type . "\" data-title=\"" . htmlspecialchars(addslashes($title)) . "\"" : "href='" . $permalink . "'") ?>>
                      <span class="bwg_album_thumb_<?php echo $bwg; ?>">
                        <?php
                        if ($params['compuct_album_title'] == 'show' && $theme_row->album_compact_thumb_title_pos == 'top') {
                          ?>
                          <span class="bwg_title_spun1_<?php echo $bwg; ?>">
                            <span class="bwg_title_spun2_<?php echo $bwg; ?>">
                              <?php echo $title; ?>
                            </span>
                          </span>
                          <?php
                        }
                        ?>
                        <span class="bwg_album_thumb_spun1_<?php echo $bwg; ?>">
                          <span class="bwg_album_thumb_spun2_<?php echo $bwg; ?>">
                            <img class="bwg_img_clear bwg_img_custom" style="width: <?php echo $image_thumb_width; ?>px; height:<?php echo $image_thumb_height; ?>px; margin-left: <?php echo $thumb_left; ?>px; margin-top: <?php echo $thumb_top; ?>px;" src="<?php echo $preview_url; ?>" alt="<?php echo $title; ?>" />
                            <?php
                            if ($params['compuct_album_title'] == 'hover') {
                              ?>
                              <span class="bwg_title_spun1_<?php echo $bwg; ?>">
                                <span class="bwg_title_spun2_<?php echo $bwg; ?>">
                                  <?php echo $title; ?>
                                </span>
                              </span>
                              <?php
                            }
                            ?>
                          </span>
                        </span>
                        <?php
                        if ($params['compuct_album_title'] == 'show' && $theme_row->album_compact_thumb_title_pos == 'bottom') {
                          ?>
                          <span class="bwg_title_spun1_<?php echo $bwg; ?>">
                            <span class="bwg_title_spun2_<?php echo $bwg; ?>">
                              <?php echo $title; ?>
                            </span>
                          </span>
                          <?php
                        }
                        ?>
                      </span>
                    </a>
                    <?php
                  }
                }
              }
              elseif ($type == 'gallery') {
                if (!$page_nav['total']) {
                  if ($bwg_search != '') {
                    ?>
                    <span class="bwg_back_<?php echo $bwg; ?>"><?php echo __('There are no images matching your search.', BWG()->prefix); ?></span>
                    <?php
                  }
                  else {
                    ?>
                    <span class="bwg_back_<?php echo $bwg; ?>"><?php echo __('Gallery is empty.', BWG()->prefix); ?></span>
                    <?php
                  }
                }
                foreach ($image_rows as $image_row) {
                  $is_embed = preg_match('/EMBED/', $image_row->filetype) == 1 ? true : false;
                  $is_embed_video = preg_match('/VIDEO/', $image_row->filetype) == 1 ? true : false;
                  $is_embed_instagram = preg_match('/EMBED_OEMBED_INSTAGRAM/', $image_row->filetype) == 1 ? true : false;
                  if (!$is_embed) {
				          	$thumb_path_url = htmlspecialchars_decode(ABSPATH . BWG()->upload_dir . $image_row->thumb_url, ENT_COMPAT | ENT_QUOTES);
                    $thumb_path_url = explode('?bwg', $thumb_path_url);
					          list($image_thumb_width, $image_thumb_height) = getimagesize($thumb_path_url[0]);
                  }
                  else {
                    if ($image_row->resolution != '') {
                      if (!$is_embed_instagram) {
                        $resolution_arr = explode(" ", $image_row->resolution);
                        $resolution_w = intval($resolution_arr[0]);
                        $resolution_h = intval($resolution_arr[2]);
                        if($resolution_w != 0 && $resolution_h != 0){
                          $scale = $scale = max($params['compuct_album_image_thumb_width'] / $resolution_w, $params['compuct_album_image_thumb_height'] / $resolution_h);
                          $image_thumb_width = $resolution_w * $scale;
                          $image_thumb_height = $resolution_h * $scale;
                        }
                        else{
                          $image_thumb_width = $params['compuct_album_image_thumb_width'];
                          $image_thumb_height = $params['compuct_album_image_thumb_height'];
                        }
                      }
                      else {
                        // this will be ok while instagram thumbnails width and height are the same
                        $image_thumb_width = min($params['compuct_album_image_thumb_width'], $params['compuct_album_image_thumb_height']);
                        $image_thumb_height = $image_thumb_width;
                      }
                    }
                    else{
                      $image_thumb_width = $params['compuct_album_image_thumb_width'];
                      $image_thumb_height = $params['compuct_album_image_thumb_height'];
                    }               
                  }
                  $scale = max($params['compuct_album_image_thumb_width'] / $image_thumb_width, $params['compuct_album_image_thumb_height'] / $image_thumb_height);
                  $image_thumb_width *= $scale;
                  $image_thumb_height *= $scale;
                  $thumb_left = ($params['compuct_album_image_thumb_width'] - $image_thumb_width) / 2;
                  $thumb_top = ($params['compuct_album_image_thumb_height'] - $image_thumb_height) / 2;
                  if ($album_view_type == 'thumbnail') {
                    ?>
                    <a <?php echo ($params['thumb_click_action'] == 'open_lightbox' ? (' class="bwg_lightbox_' . $bwg . '"' . (BWG()->options->enable_seo ? ' href="' . ($is_embed ? $image_row->thumb_url : site_url() . '/' . BWG()->upload_dir . $image_row->image_url) . '"' : '') . ' data-image-id="' . $image_row->id . '" data-gallery-id="' . $album_gallery_id . '"') : ($image_row->redirect_url ? 'href="' . $image_row->redirect_url . '" target="' .  ($params['thumb_link_target'] ? '_blank' : '')  . '"' : '')) ?>>
                    <span class="bwg_standart_thumb_<?php echo $bwg; ?>">
                      <?php
                      if ($params['compuct_album_image_title'] == 'show' && $theme_row->album_compact_thumb_title_pos == 'top') {
                        ?>
                        <span class="bwg_image_title_spun1_<?php echo $bwg; ?>">
                          <span class="bwg_image_title_spun2_<?php echo $bwg; ?>">
                            <?php echo $image_row->alt; ?>
                          </span>
                        </span>
                        <?php
                      }
                      ?>
                      <span class="bwg_standart_thumb_spun1_<?php echo $bwg; ?>">
                        <span class="bwg_standart_thumb_spun2_<?php echo $bwg; ?>">
                          <?php
                          if ($play_icon && $is_embed_video) {
                            ?>
                          <span class="bwg_play_icon_spun_<?php echo $bwg; ?>">
                             <i title="<?php echo __('Play', BWG()->prefix); ?>"  class="fa fa-play bwg_play_icon_<?php echo $bwg; ?>"></i>
                          </span>
                            <?php
                          }
                          if ($params['compuct_album_image_title'] == 'hover') {
                            ?>
                            <span class="bwg_image_title_spun1_<?php echo $bwg; ?>">
                              <span class="bwg_image_title_spun2_<?php echo $bwg; ?>">
                                <?php echo $image_row->alt; ?>
                              </span>
                            </span>
                            <?php
                          }
                          
                          if(function_exists('BWGEC') && $params['ecommerce_icon'] == 'hover' && $image_row->pricelist_id){
                            ?>	
                          <span class="bwg_ecommerce_spun1_<?php echo $bwg; ?>">
                            <span class="bwg_ecommerce_spun2_<?php echo $bwg; ?>">
                              <i title="<?php echo __('Open', BWG()->prefix); ?>" class="bwg_ctrl_btn bwg_open fa fa-share-square" ></i>
                              <i title="<?php echo __('Ecommerce', BWG()->prefix); ?>" class="bwg_ctrl_btn bwg_ecommerce fa fa-shopping-cart" ></i>
                            </span>
                          </span>                               
                       <?php
                          }                              
                      ?>
                          <img class="bwg_img_clear bwg_img_custom" style="width:<?php echo $image_thumb_width; ?>px; height:<?php echo $image_thumb_height; ?>px; margin-left: <?php echo $thumb_left; ?>px; margin-top: <?php echo $thumb_top; ?>px;" id="<?php echo $image_row->id; ?>" src="<?php echo ( $is_embed ? "" : site_url() . '/' . BWG()->upload_dir) . $image_row->thumb_url; ?>" alt="<?php echo $image_row->alt; ?>" />
                        </span>
                      </span>
                      <?php
                      if ($params['compuct_album_image_title'] == 'show' && $theme_row->album_compact_thumb_title_pos == 'bottom') {
                        ?>
                        <span class="bwg_image_title_spun1_<?php echo $bwg; ?>">
                          <span class="bwg_image_title_spun2_<?php echo $bwg; ?>">
                            <?php echo $image_row->alt; ?>
                          </span>
                        </span>
                        <?php
                      }
                      ?>
                    <?php
                      if (function_exists('BWGEC') && $params['ecommerce_icon'] == 'show' && $image_row->pricelist_id) {
                        ?>
                        <span class="bwg_ecommerce_spun1_<?php echo $bwg; ?>">
                            <span class="bwg_ecommerce_spun2_<?php echo $bwg; ?>">
                              <i title="<?php echo __('Open', BWG()->prefix); ?>" class="bwg_ctrl_btn bwg_open fa fa-share-square" ></i>
                              <i title="<?php echo __('Ecommerce', BWG()->prefix); ?>" class="bwg_ctrl_btn bwg_ecommerce fa fa-shopping-cart" ></i>
                            </span>
                          </span> 
                        <?php
                      }
                      ?>
                    </span>
                  </a>
                    <?php
                  }
                }
              }
              ?>
            </div>
            <?php
            if ( $type == 'gallery' ) {
              if ( BWG()->is_pro && $gallery_download && $image_rows ) {
                $query_url = addslashes(add_query_arg(array(
                                                        "action" => "download_gallery",
                                                        "gallery_id" => $album_gallery_id,
                                                      ), admin_url('admin-ajax.php')));
                ?>
                <div class="bwg_download_gallery">
                  <a href="<?php echo $query_url; ?>">
                    <i title="<?php _e('Download gallery', BWG()->prefix); ?>" class="bwg_ctrl_btn fa fa-download"></i>
                  </a>
                </div>
                <?php
              }
            }
            if ($params['compuct_album_enable_page'] && $items_per_page && ($theme_row->page_nav_position == 'bottom') && $page_nav['total']) {
              WDWLibrary::ajax_html_frontend_page_nav($theme_row, $page_nav['total'], $page_nav['limit'], 'gal_front_form_' . $bwg, $items_per_page_arr, $bwg, $album_gallery_div_id, $params['album_id'], $type, BWG()->options->enable_seo, $params['compuct_album_enable_page']);
            }
            ?>
          </div>
        </form>
        <div id="bwg_spider_popup_loading_<?php echo $bwg; ?>" class="bwg_spider_popup_loading"></div>
        <div id="spider_popup_overlay_<?php echo $bwg; ?>" class="spider_popup_overlay" onclick="spider_destroypopup(1000)"></div>
      </div>
    </div>
    <script>
      function bwg_masonry_<?php echo $bwg; ?>() {}
      function bwg_mosaic_<?php echo $bwg; ?>(event_type) {}
      function bwg_gallery_box_<?php echo $bwg; ?>(gallery_id, image_id,openEcommerce) {
        if (typeof openEcommerce == undefined) {
          openEcommerce = false;
        }
        var ecommerce = openEcommerce == true ? "&open_ecommerce=1" : "";
        var filterTags = jQuery("#bwg_tags_id_bwg_album_compact_<?php echo $bwg; ?>" ).val() ? jQuery("#bwg_tags_id_bwg_album_compact_<?php echo $bwg; ?>" ).val() : 0;
        var filtersearchname = jQuery("#bwg_search_input_<?php echo $bwg; ?>" ).val() ? "&filter_search_name_<?php echo $bwg; ?>=" + jQuery("#bwg_search_input_<?php echo $bwg; ?>" ).val() : '';
        spider_createpopup('<?php echo addslashes(add_query_arg($params_array, admin_url('admin-ajax.php'))); ?>&gallery_id=' + gallery_id + '&image_id=' + image_id + "&filter_tag_<?php echo $bwg; ?>=" +  filterTags + ecommerce + filtersearchname, '<?php echo $bwg; ?>', '<?php echo $bwg; ?>', '<?php echo $params['popup_width']; ?>', '<?php echo $params['popup_height']; ?>', 1, 'testpopup', 5, "<?php echo $theme_row->lightbox_ctrl_btn_pos ;?>");
      }
      <?php
      if ( BWG()->is_pro ) {
        ?>
      var bwg_hash = window.location.hash.substring(1);
      if (bwg_hash) {
        if (bwg_hash.indexOf("bwg") != "-1") {
          bwg_hash_array = bwg_hash.replace("bwg", "").split("/");
          bwg_gallery_box_<?php echo $bwg; ?>(bwg_hash_array[0], bwg_hash_array[1]);
        }
      }
        <?php
      }
      ?>
      function bwg_document_ready_<?php echo $bwg; ?>() {
        var bwg_touch_flag = false;
        jQuery(".bwg_lightbox_<?php echo $bwg; ?>").on("click", function () {
          if (!bwg_touch_flag) {
            bwg_touch_flag = true;
            setTimeout(function(){ bwg_touch_flag = false; }, 100);
            bwg_gallery_box_<?php echo $bwg; ?>(jQuery(this).attr("data-gallery-id"), jQuery(this).attr("data-image-id"));
            return false;
          }
        });
        jQuery(".bwg_lightbox_<?php echo $bwg; ?> .bwg_ecommerce").on("click", function (event) {
          event.stopPropagation();
          if (!bwg_touch_flag) {
            bwg_touch_flag = true;
            setTimeout(function(){ bwg_touch_flag = false; }, 100);
            var image_id = jQuery(this).closest(".bwg_lightbox_<?php echo $bwg; ?>").attr("data-image-id");
            var gallery_id = jQuery(this).closest(".bwg_lightbox_<?php echo $bwg; ?>").attr("data-gallery-id");
            bwg_gallery_box_<?php echo $bwg; ?>(gallery_id, image_id, true);
            return false;
          }
        });
        <?php if ($from !== "widget") { ?>
        jQuery(".bwg_album_<?php echo $bwg; ?>").on("click", function () {
          if (!bwg_touch_flag) {
            bwg_touch_flag = true;
            setTimeout(function(){ bwg_touch_flag = false; }, 100);
            spider_frontend_ajax('gal_front_form_<?php echo $bwg; ?>', '<?php echo $bwg; ?>', 'bwg_album_compact_<?php echo $bwg; ?>', jQuery(this).attr("data-alb_gal_id"), '<?php echo $album_gallery_id; ?>', jQuery(this).attr("data-def_type"), '', jQuery(this).attr("data-title"), 'default');
            return false;
          }
        });
        <?php }
        if ($image_right_click) {
          ?>
          /* Disable right click.*/
          jQuery('div[id^="bwg_container"]').bind("contextmenu", function () {
            return false;
          });
          jQuery('div[id^="bwg_container"]').css('webkitTouchCallout','none');
          <?php
        }
        ?>
      }
      jQuery(document).ready(function () {
        bwg_document_ready_<?php echo $bwg; ?>();
      });
      function bwg_mosaic_ajax_<?php echo $bwg; ?>(tot_cccount_mosaic_ajax) {}
      function bwg_masonry_ajax_<?php echo $bwg; ?>(tot_cccount_masonry_ajax) {}
    </script>
    <?php
    if ($from_shortcode) {
      return;
    }
    else {
      die();
    }
  }

  private function inline_styles($bwg, $theme_row, $params, $album_gallery_div_class, $items_col_num, $album_view_type) {
    ob_start();
    $rgb_page_nav_font_color = WDWLibrary::spider_hex2rgb($theme_row->page_nav_font_color);
    $rgb_album_compact_thumbs_bg_color = WDWLibrary::spider_hex2rgb($theme_row->album_compact_thumbs_bg_color);
    $rgb_thumbs_bg_color = WDWLibrary::spider_hex2rgb($theme_row->thumbs_bg_color);

    ?>
      /* Style for masonry view.*/
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumbnails_<?php echo $bwg; ?> * {
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumb_<?php echo $bwg; ?> {
        visibility: hidden;
        text-align: center;
        display: inline-block;
        vertical-align: middle;     
        width: <?php echo $params['compuct_album_image_thumb_width']; ?>px !important;
        border-radius: <?php echo $theme_row->masonry_thumb_border_radius; ?>;
        border: <?php echo $theme_row->masonry_thumb_border_width; ?>px <?php echo $theme_row->masonry_thumb_border_style; ?> #<?php echo $theme_row->masonry_thumb_border_color; ?>;
        background-color: #<?php echo $theme_row->thumb_bg_color; ?>;
        margin: 0;
        padding: <?php echo $theme_row->masonry_thumb_padding; ?>px !important;
        opacity: <?php echo number_format($theme_row->masonry_thumb_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->masonry_thumb_transparent; ?>);
        <?php echo ($theme_row->masonry_thumb_transition) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>
        z-index: 100;
      }
    /*  #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumb_<?php echo $bwg; ?>:hover {
        opacity: 1;
        filter: Alpha(opacity=100);
        transform: <?php echo $theme_row->masonry_thumb_hover_effect; ?>(<?php echo $theme_row->masonry_thumb_hover_effect_value; ?>);
        -ms-transform: <?php echo $theme_row->masonry_thumb_hover_effect; ?>(<?php echo $theme_row->masonry_thumb_hover_effect_value; ?>);
        -webkit-transform: <?php echo $theme_row->masonry_thumb_hover_effect; ?>(<?php echo $theme_row->masonry_thumb_hover_effect_value; ?>);
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        -moz-backface-visibility: hidden;
        -ms-backface-visibility: hidden;
        z-index: 102;
        position: absolute;
      }*/
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumbnails_<?php echo $bwg; ?> {
        -moz-box-sizing: border-box;
        background-color: rgba(<?php echo $rgb_thumbs_bg_color['red']; ?>, <?php echo $rgb_thumbs_bg_color['green']; ?>, <?php echo $rgb_thumbs_bg_color['blue']; ?>, <?php echo number_format($theme_row->masonry_thumb_bg_transparent / 100, 2, ".", ""); ?>);
        box-sizing: border-box;
        display: inline-block;
        font-size: 0;
        /*width: <?php echo $params['compuct_album_image_column_number'] * ($params['compuct_album_image_thumb_width'] + 2 * ($theme_row->masonry_thumb_padding + $theme_row->masonry_thumb_border_width)); ?>px;*/
        width: 100%;
        position: relative;
        text-align: <?php echo $theme_row->masonry_thumb_align; ?>;
      }
      @media only screen and (max-width : <?php echo $params['compuct_album_image_column_number'] * ($params['compuct_album_image_thumb_width'] + 2 * ($theme_row->masonry_thumb_padding + $theme_row->masonry_thumb_border_width)); ?>px) {
        #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumbnails_<?php echo $bwg; ?> {
          width: inherit;
        }
      }	  	  
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #spider_popup_overlay_<?php echo $bwg; ?> {
        background-color: #<?php echo $theme_row->lightbox_overlay_bg_color; ?>;
        opacity: <?php echo number_format($theme_row->lightbox_overlay_bg_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_overlay_bg_transparent; ?>);
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumb_spun_<?php echo $bwg; ?> {
        position: absolute;
      }
      /* Style for thumbnail view.*/
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_album_thumb_<?php echo $bwg; ?> {
        display: inline-block;
        text-align: center;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .<?php echo $album_gallery_div_class; ?> * {
        -moz-box-sizing: content-box;
        box-sizing: content-box;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_album_thumb_spun1_<?php echo $bwg; ?> {
        background-color: #<?php echo $theme_row->album_compact_thumb_bg_color; ?>;
        display: inline-block;
        width: <?php echo $params['compuct_album_thumb_width']; ?>px;
        height: <?php echo $params['compuct_album_thumb_height']; ?>px;
        margin: <?php echo $theme_row->album_compact_thumb_margin; ?>px;
        opacity: <?php echo number_format($theme_row->album_compact_thumb_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->album_compact_thumb_transparent; ?>);
        <?php echo ($theme_row->album_compact_thumb_transition) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>
        padding: <?php echo $theme_row->album_compact_thumb_padding; ?>px;
        text-align: center;
        vertical-align: middle;
        z-index: 100;
        -webkit-backface-visibility: visible;
        -ms-backface-visibility: visible;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_album_thumb_spun1_<?php echo $bwg; ?>:hover {
        opacity: 1;
        filter: Alpha(opacity=100);
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        -moz-backface-visibility: hidden;
        -ms-backface-visibility: hidden;
        z-index: 102;
      }

      @media only screen and (min-width: 480px) {
          #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_album_thumb_spun1_<?php echo $bwg; ?>:hover {
              transform: <?php echo $theme_row->album_compact_thumb_hover_effect; ?>(<?php echo $theme_row->album_compact_thumb_hover_effect_value; ?>);
              -ms-transform: <?php echo $theme_row->album_compact_thumb_hover_effect; ?>(<?php echo $theme_row->album_compact_thumb_hover_effect_value; ?>);
              -webkit-transform: <?php echo $theme_row->album_compact_thumb_hover_effect; ?>(<?php echo $theme_row->album_compact_thumb_hover_effect_value; ?>);
          }
      }

      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_album_thumb_spun2_<?php echo $bwg; ?> {
        border-radius: <?php echo $theme_row->album_compact_thumb_border_radius; ?>;
        border: <?php echo $theme_row->album_compact_thumb_border_width; ?>px <?php echo $theme_row->album_compact_thumb_border_style; ?> #<?php echo $theme_row->album_compact_thumb_border_color; ?>;
        box-shadow: <?php echo $theme_row->album_compact_thumb_box_shadow; ?>;
        display: inline-block;
        height: <?php echo $params['compuct_album_thumb_height']; ?>px;
        overflow: hidden;
        width: <?php echo $params['compuct_album_thumb_width']; ?>px;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_album_thumbnails_<?php echo $bwg; ?> {
        display: inline-block;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        background-color: rgba(<?php echo $rgb_album_compact_thumbs_bg_color['red']; ?>, <?php echo $rgb_album_compact_thumbs_bg_color['green']; ?>, <?php echo $rgb_album_compact_thumbs_bg_color['blue']; ?>, <?php echo number_format($theme_row->album_compact_thumb_bg_transparent / 100, 2, ".", ""); ?>);
        font-size: 0;
        text-align: <?php echo $theme_row->album_compact_thumb_align; ?>;
        max-width: <?php echo $items_col_num * ($params['compuct_album_thumb_width'] + 2 * (2 + $theme_row->album_compact_thumb_margin + $theme_row->album_compact_thumb_padding + $theme_row->album_compact_thumb_border_width)); ?>px;
      }
      <?php
      if ($params['compuct_album_title'] == 'show') { /* Show album/gallery title at the bottom.*/
        ?>
        #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_title_spun1_<?php echo $bwg; ?> {
          display: block;
          opacity: 1;
          filter: Alpha(opacity=100);
          text-align: center;
          width: <?php echo $params['compuct_album_thumb_width']; ?>px;
        }
        <?php
      }
      elseif ($params['compuct_album_title'] == 'hover') { /* Show album/gallery title on hover.*/
        ?>
        #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_title_spun1_<?php echo $bwg; ?> {
          display: table;
          height: inherit;
          left: -3000px;
          opacity: 0;
          filter: Alpha(opacity=0);
          position: absolute;
          top: 0px;
          width: inherit;
        }
        <?php
      }
      ?>
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_album_thumb_spun1_<?php echo $bwg; ?>:hover .bwg_title_spun1_<?php echo $bwg; ?> {
        left: <?php echo $theme_row->album_compact_thumb_padding; ?>px;
        top: <?php echo $theme_row->album_compact_thumb_padding; ?>px;
        opacity: 1;
        filter: Alpha(opacity=100);
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_title_spun2_<?php echo $bwg; ?> {
        color: #<?php echo $theme_row->album_compact_title_font_color; ?>;
        display: table-cell;
        font-family: <?php echo $theme_row->album_compact_title_font_style; ?>;
        font-size: <?php echo $theme_row->album_compact_title_font_size; ?>px;
        font-weight: <?php echo $theme_row->album_compact_title_font_weight; ?>;
        height: inherit;
        padding: <?php echo $theme_row->album_compact_title_margin; ?>;
        text-shadow: <?php echo $theme_row->album_compact_title_shadow; ?>;
        vertical-align: middle;
        width: inherit;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_album_thumbnails_<?php echo $bwg; ?> {
        display: inline-block;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        background-color: rgba(<?php echo $rgb_album_compact_thumbs_bg_color['red']; ?>, <?php echo $rgb_album_compact_thumbs_bg_color['green']; ?>, <?php echo $rgb_album_compact_thumbs_bg_color['blue']; ?>, <?php echo number_format($theme_row->album_compact_thumb_bg_transparent / 100, 2, ".", ""); ?>);
        font-size: 0;
        text-align: <?php echo $theme_row->album_compact_thumb_align; ?>;
        max-width: <?php echo $items_col_num * ($params['compuct_album_thumb_width'] + 2 * (2 + $theme_row->album_compact_thumb_margin + $theme_row->album_compact_thumb_padding + $theme_row->album_compact_thumb_border_width)); ?>px;
      }

      <?php if($album_view_type != 'mosaic'): ?>
      /* Style for masonry view.*/
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumbnails_<?php echo $bwg; ?> * {
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumb_<?php echo $bwg; ?> {
        visibility: hidden;
        text-align: center;
        display: inline-block;
        vertical-align: middle;     
        width: <?php echo $params['compuct_album_image_thumb_width']; ?>px !important;
        border-radius: <?php echo $theme_row->masonry_thumb_border_radius; ?>;
        border: <?php echo $theme_row->masonry_thumb_border_width; ?>px <?php echo $theme_row->masonry_thumb_border_style; ?> #<?php echo $theme_row->masonry_thumb_border_color; ?>;
        background-color: #<?php echo $theme_row->thumb_bg_color; ?>;
        margin: 0;
        padding: <?php echo $theme_row->masonry_thumb_padding; ?>px !important;
        opacity: <?php echo number_format($theme_row->masonry_thumb_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->masonry_thumb_transparent; ?>);
        <?php echo ($theme_row->masonry_thumb_transition) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>
        z-index: 100;
      }
     /* #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumb_<?php echo $bwg; ?>:hover {
        opacity: 1;
        filter: Alpha(opacity=100);
        transform: <?php echo $theme_row->masonry_thumb_hover_effect; ?>(<?php echo $theme_row->masonry_thumb_hover_effect_value; ?>);
        -ms-transform: <?php echo $theme_row->masonry_thumb_hover_effect; ?>(<?php echo $theme_row->masonry_thumb_hover_effect_value; ?>);
        -webkit-transform: <?php echo $theme_row->masonry_thumb_hover_effect; ?>(<?php echo $theme_row->masonry_thumb_hover_effect_value; ?>);
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        -moz-backface-visibility: hidden;
        -ms-backface-visibility: hidden;
        z-index: 102;
        position: absolute;
      }*/
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumbnails_<?php echo $bwg; ?> {
        -moz-box-sizing: border-box;
        background-color: rgba(<?php echo $rgb_thumbs_bg_color['red']; ?>, <?php echo $rgb_thumbs_bg_color['green']; ?>, <?php echo $rgb_thumbs_bg_color['blue']; ?>, <?php echo number_format($theme_row->masonry_thumb_bg_transparent / 100, 2, ".", ""); ?>);
        box-sizing: border-box;
        display: inline-block;
        font-size: 0;
        /*width: <?php echo $params['compuct_album_image_column_number'] * ($params['compuct_album_image_thumb_width'] + 2 * ($theme_row->masonry_thumb_padding + $theme_row->masonry_thumb_border_width)); ?>px;*/
        width: 100%;
        position: relative;
        text-align: <?php echo $theme_row->masonry_thumb_align; ?>;
      }
      @media only screen and (max-width : <?php echo $params['compuct_album_image_column_number'] * ($params['compuct_album_image_thumb_width'] + 2 * ($theme_row->masonry_thumb_padding + $theme_row->masonry_thumb_border_width)); ?>px) {
        #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumbnails_<?php echo $bwg; ?> {
          width: inherit;
        }
      }	  	  
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #spider_popup_overlay_<?php echo $bwg; ?> {
        background-color: #<?php echo $theme_row->lightbox_overlay_bg_color; ?>;
        opacity: <?php echo number_format($theme_row->lightbox_overlay_bg_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_overlay_bg_transparent; ?>);
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumb_spun_<?php echo $bwg; ?> {
        position: absolute;
      }
      /*Image thumbs styles.*/
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_standart_thumb_spun1_<?php echo $bwg; ?> {
        background-color: #<?php echo $theme_row->thumb_bg_color; ?>;
        display: inline-block;
        height: <?php echo $params['compuct_album_image_thumb_height']; ?>px;
        margin: <?php echo $theme_row->thumb_margin; ?>px;
        opacity: <?php echo number_format($theme_row->thumb_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->thumb_transparent; ?>);
        <?php echo ($theme_row->thumb_transition) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>
        padding: <?php echo $theme_row->thumb_padding; ?>px;
        text-align: center;
        vertical-align: middle;
        width: <?php echo $params['compuct_album_image_thumb_width']; ?>px;
        z-index: 100;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_standart_thumb_spun1_<?php echo $bwg; ?>:hover {
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        -moz-backface-visibility: hidden;
        -ms-backface-visibility: hidden;
        opacity: 1;
        filter: Alpha(opacity=100);
        z-index: 102;
        position: relative;
      }
      @media only screen and (min-width: 480px) {
          #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_standart_thumb_spun1_<?php echo $bwg; ?>:hover {
              -ms-transform: <?php echo $theme_row->thumb_hover_effect; ?>(<?php echo $theme_row->thumb_hover_effect_value; ?>);
              -webkit-transform: <?php echo $theme_row->thumb_hover_effect; ?>(<?php echo $theme_row->thumb_hover_effect_value; ?>);
              transform: <?php echo $theme_row->thumb_hover_effect; ?>(<?php echo $theme_row->thumb_hover_effect_value; ?>);
          }
      }

          #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_standart_thumb_spun2_<?php echo $bwg; ?> {
        border-radius: <?php echo $theme_row->thumb_border_radius; ?>;
        border: <?php echo $theme_row->thumb_border_width; ?>px <?php echo $theme_row->thumb_border_style; ?> #<?php echo $theme_row->thumb_border_color; ?>;
        box-shadow: <?php echo $theme_row->thumb_box_shadow; ?>;
        display: inline-block;
        height: <?php echo $params['compuct_album_image_thumb_height']; ?>px;
        overflow: hidden;
        width: <?php echo $params['compuct_album_image_thumb_width']; ?>px;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_standart_thumbnails_<?php echo $bwg; ?> {
        -moz-box-sizing: border-box;
        display: inline-block;
        background-color: rgba(<?php echo $rgb_thumbs_bg_color['red']; ?>, <?php echo $rgb_thumbs_bg_color['green']; ?>, <?php echo $rgb_thumbs_bg_color['blue']; ?>, <?php echo number_format($theme_row->thumb_bg_transparent / 100, 2, ".", ""); ?>);
        box-sizing: border-box;
        font-size: 0;
        max-width: <?php echo $params['compuct_album_image_column_number'] * ($params['compuct_album_image_thumb_width'] + 2 * (2 + $theme_row->thumb_margin + $theme_row->thumb_padding + $theme_row->thumb_border_width)); ?>px;
        text-align: <?php echo $theme_row->thumb_align; ?>;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_standart_thumb_<?php echo $bwg; ?> {
        display: inline-block;
        text-align: center;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_gal_title_<?php echo $bwg; ?> {
        background-color: rgba(0, 0, 0, 0);
        color: #<?php echo $theme_row->album_compact_gal_title_font_color; ?>;
        display: block;
        font-family: <?php echo $theme_row->album_compact_gal_title_font_style; ?>;
        font-size: <?php echo $theme_row->album_compact_gal_title_font_size; ?>px;
        font-weight: <?php echo $theme_row->album_compact_gal_title_font_weight; ?>;
        padding: <?php echo $theme_row->album_compact_gal_title_margin; ?>;
        text-shadow: <?php echo $theme_row->album_compact_gal_title_shadow; ?>;
        text-align: <?php echo $theme_row->album_compact_gal_title_align; ?>;
    }
      <?php
      if(function_exists('BWGEC')){
        if( $params['ecommerce_icon'] == 'show' ){
        ?>
        #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_ecommerce_spun1_<?php echo $bwg; ?>{
          display: block;
          margin: 0 auto;
          opacity: 1;
          filter: Alpha(opacity=100);
          text-align: right;
          width: <?php echo $params['compuct_album_image_thumb_width']; ?>px;    
        }
        <?php
        }
        elseif ($params['ecommerce_icon'] == 'hover') { /* Show ecommerce icon on hover.*/
        ?>
        #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_ecommerce_spun1_<?php echo $bwg; ?> {
          display: table;
          height: inherit;
          left: -3000px;
          opacity: 0;
          filter: Alpha(opacity=0);
          position: absolute;
          top: 0px;
          width: inherit;
        }
        #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_standart_thumb_spun1_<?php echo $bwg; ?>:hover img{
            opacity:0.5;
        }
        #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_standart_thumb_spun1_<?php echo $bwg; ?>:hover{
            background:#000;
        }
        #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_ecommerce_spun1_<?php echo $bwg; ?>, #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_ecommerce_spun2_<?php echo $bwg; ?>, #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_ecommerce_spun2_<?php echo $bwg; ?> i{
            opacity:1 !important;
            font-size:20px !important;
            z-index: 45;
        }
        <?php
        }
      }  
      if ($params['compuct_album_image_title'] == 'show') { /* Show image title at the bottom.*/
        ?>
        #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_image_title_spun1_<?php echo $bwg; ?> {
          display: block;
          margin: 0 auto;
          opacity: 1;
          filter: Alpha(opacity=100);
          text-align: center;
          width: <?php echo $params['compuct_album_thumb_width']; ?>px;
        }
        <?php
      }
      elseif ($params['compuct_album_image_title'] == 'hover') { /* Show image title on hover.*/
        ?>
        #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_image_title_spun1_<?php echo $bwg; ?> {
          display: table;
          height: inherit;
          left: -3000px;
          opacity: 0;
          filter: Alpha(opacity=0);
          position: absolute;
          top: 0px;
          width: inherit;
        }
        <?php
      }
      ?>
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_standart_thumb_spun1_<?php echo $bwg; ?>:hover .bwg_image_title_spun1_<?php echo $bwg; ?>, #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_standart_thumb_spun1_<?php echo $bwg; ?>:hover .bwg_ecommerce_spun1_<?php echo $bwg; ?> {
        left: <?php echo $theme_row->thumb_padding; ?>px;
        top: <?php echo $theme_row->thumb_padding; ?>px;
        opacity: 1;
        filter: Alpha(opacity=100);
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_image_title_spun2_<?php echo $bwg; ?>,  #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_ecommerce_spun2_<?php echo $bwg; ?>{
        color: #<?php echo $theme_row->thumb_title_font_color; ?>;
        display: table-cell;
        font-family: <?php echo $theme_row->thumb_title_font_style; ?>;
        font-size: <?php echo $theme_row->thumb_title_font_size; ?>px;
        font-weight: <?php echo $theme_row->thumb_title_font_weight; ?>;
        height: inherit;
        margin: <?php echo $theme_row->thumb_title_margin; ?>;
        text-shadow: <?php echo $theme_row->thumb_title_shadow; ?>;
        vertical-align: middle;
        width: inherit;
        word-wrap: break-word;
      }
      /*Pagination styles.*/
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .tablenav-pages_<?php echo $bwg; ?> {
        text-align: <?php echo $theme_row->page_nav_align; ?>;
        font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
        font-family: <?php echo $theme_row->page_nav_font_style; ?>;
        font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
        color: #<?php echo $theme_row->page_nav_font_color; ?>;
        margin: 6px 0 4px;
        display: block;
        height: 30px;
        line-height: 30px;
      }
      @media only screen and (max-width : 320px) {
        #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .displaying-num_<?php echo $bwg; ?> {
          display: none;
        }
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .displaying-num_<?php echo $bwg; ?> {
        font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
        font-family: <?php echo $theme_row->page_nav_font_style; ?>;
        font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
        color: #<?php echo $theme_row->page_nav_font_color; ?>;
        margin-right: 10px;
        vertical-align: middle;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .paging-input_<?php echo $bwg; ?> {
        font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
        font-family: <?php echo $theme_row->page_nav_font_style; ?>;
        font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
        color: #<?php echo $theme_row->page_nav_font_color; ?>;
        vertical-align: middle;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .tablenav-pages_<?php echo $bwg; ?> a.disabled,
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .tablenav-pages_<?php echo $bwg; ?> a.disabled:hover,
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .tablenav-pages_<?php echo $bwg; ?> a.disabled:focus {
        cursor: default;
        color: rgba(<?php echo $rgb_page_nav_font_color['red']; ?>, <?php echo $rgb_page_nav_font_color['green']; ?>, <?php echo $rgb_page_nav_font_color['blue']; ?>, 0.5);
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .tablenav-pages_<?php echo $bwg; ?> a {
        cursor: pointer;
        font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
        font-family: <?php echo $theme_row->page_nav_font_style; ?>;
        font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
        color: #<?php echo $theme_row->page_nav_font_color; ?>;
        text-decoration: none;
        padding: <?php echo $theme_row->page_nav_padding; ?>;
        margin: <?php echo $theme_row->page_nav_margin; ?>;
        border-radius: <?php echo $theme_row->page_nav_border_radius; ?>;
        border-style: <?php echo $theme_row->page_nav_border_style; ?>;
        border-width: <?php echo $theme_row->page_nav_border_width; ?>px;
        border-color: #<?php echo $theme_row->page_nav_border_color; ?>;
        background-color: #<?php echo $theme_row->page_nav_button_bg_color; ?>;
        opacity: <?php echo number_format($theme_row->page_nav_button_bg_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->page_nav_button_bg_transparent; ?>);
        box-shadow: <?php echo $theme_row->page_nav_box_shadow; ?>;
        <?php echo ($theme_row->page_nav_button_transition ) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_back_<?php echo $bwg; ?> {
        background-color: rgba(0, 0, 0, 0);
        color: #<?php echo $theme_row->album_compact_back_font_color; ?> !important;
        cursor: pointer;
        display: block;
        font-family: <?php echo $theme_row->album_compact_back_font_style; ?>;
        font-size: <?php echo $theme_row->album_compact_back_font_size; ?>px;
        font-weight: <?php echo $theme_row->album_compact_back_font_weight; ?>;
        text-decoration: none;
        padding: <?php echo $theme_row->album_compact_back_padding; ?>;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #spider_popup_overlay_<?php echo $bwg; ?> {
        background-color: #<?php echo $theme_row->lightbox_overlay_bg_color; ?>;
        opacity: <?php echo number_format($theme_row->lightbox_overlay_bg_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_overlay_bg_transparent; ?>);
      }
      .bwg_play_icon_spun_<?php echo $bwg; ?>	 {
        width: inherit;
        height: inherit;
        display: table;
        position: absolute;
      }	 
     .bwg_play_icon_<?php echo $bwg; ?> {
        color: #<?php echo $theme_row->thumb_title_font_color; ?>;
        font-size: <?php echo 2 * $theme_row->thumb_title_font_size; ?>px;
        vertical-align: middle;
        display: table-cell !important;
        z-index: 1;
        text-align: center;
        margin: 0 auto;
      }
       #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumb_description_<?php echo $bwg; ?> {
				padding: 0 5px !important;						
				line-height: 1.4 !important;
				font-size: <?php echo $theme_row->masonry_description_font_size; ?>px;
        color: #<?php echo $theme_row->masonry_description_color; ?>;
        font-family: <?php echo $theme_row->masonry_description_font_style; ?>;   
				text-align: center;			
			}
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumb_spun_<?php echo $bwg; ?>:hover {
        opacity: 1;
        filter: Alpha(opacity=100);
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        -moz-backface-visibility: hidden;
        -ms-backface-visibility: hidden;
        z-index: 102;
        position: absolute;
      }
      @media only screen and (min-width: 480px) {
          #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumb_spun_<?php echo $bwg; ?>:hover {
              transform: <?php echo $theme_row->masonry_thumb_hover_effect; ?>(<?php echo $theme_row->masonry_thumb_hover_effect_value; ?>);
              -ms-transform: <?php echo $theme_row->masonry_thumb_hover_effect; ?>(<?php echo $theme_row->masonry_thumb_hover_effect_value; ?>);
              -webkit-transform: <?php echo $theme_row->masonry_thumb_hover_effect; ?>(<?php echo $theme_row->masonry_thumb_hover_effect_value; ?>);
          }
      }

          <?php
    /*for mosaic view of images*/
    else :
    ?>
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_mosaic_thumb_<?php echo $bwg; ?> {
        visibility: hidden;
        display: block;
        -moz-box-sizing: content-box !important;
        -webkit-box-sizing: content-box !important;
        box-sizing: content-box !important;                
        border-radius: <?php echo $theme_row->mosaic_thumb_border_radius; ?>;
        border: <?php echo $theme_row->mosaic_thumb_border_width; ?>px <?php echo $theme_row->mosaic_thumb_border_style; ?> #<?php echo $theme_row->mosaic_thumb_border_color; ?>;
        background-color: #<?php echo $theme_row->thumb_bg_color; ?>;
        margin: 0;
        padding: <?php echo $theme_row->mosaic_thumb_padding; ?>px !important;
        opacity: <?php echo number_format($theme_row->mosaic_thumb_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->mosaic_thumb_transparent; ?>);
        z-index: 100;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_mosaic_thumb_spun_<?php echo $bwg; ?>:hover {
        opacity: 1;
        filter: Alpha(opacity=100);
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        -moz-backface-visibility: hidden;
        -ms-backface-visibility: hidden;
        z-index: 102;
        position: absolute;
      }
        @media only screen and (min-width: 480px) {
            #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_mosaic_thumb_spun_<?php echo $bwg; ?>:hover {
                transform: <?php echo $theme_row->mosaic_thumb_hover_effect; ?>(<?php echo $theme_row->mosaic_thumb_hover_effect_value; ?>);
                -ms-transform: <?php echo $theme_row->mosaic_thumb_hover_effect; ?>(<?php echo $theme_row->mosaic_thumb_hover_effect_value; ?>);
                -webkit-transform: <?php echo $theme_row->mosaic_thumb_hover_effect; ?>(<?php echo $theme_row->mosaic_thumb_hover_effect_value; ?>);
            }
        }

        #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_mosaic_thumbnails_<?php echo $bwg; ?> {
        background-color: rgba(<?php echo $rgb_thumbs_bg_color['red']; ?>, <?php echo $rgb_thumbs_bg_color['green']; ?>, <?php echo $rgb_thumbs_bg_color['blue']; ?>, <?php echo number_format($theme_row->mosaic_thumb_bg_transparent / 100, 2, ".", ""); ?>);
        font-size: 0;
        position: relative;
        text-align: <?php echo $theme_row->mosaic_thumb_align; ?>;
        display: inline-block;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_mosaic_thumb_spun_<?php echo $bwg; ?> {
        display:block;
        position: absolute;
        -moz-box-sizing: content-box !important;
        -webkit-box-sizing: content-box !important;
        box-sizing: content-box !important;
      }
      /*image title styles*/
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_mosaic_title_spun1_<?php echo $bwg; ?> {
        position: absolute;
        display:block;
        opacity: 0;
        filter: Alpha(opacity=0);
        left: -10000px;
        top: 0px;
        box-sizing:border-box;
        text-align: center;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_mosaic_title_spun2_<?php echo $bwg; ?> {
        color: #<?php echo $theme_row->mosaic_thumb_title_font_color; ?>;
        font-family: <?php echo $theme_row->mosaic_thumb_title_font_style; ?>;
        font-size: <?php echo $theme_row->mosaic_thumb_title_font_size; ?>px;
        font-weight: <?php echo $theme_row->mosaic_thumb_title_font_weight; ?>;        
        text-shadow: <?php echo $theme_row->mosaic_thumb_title_shadow; ?>;
        vertical-align: middle;
        word-wrap: break-word;

      }
      .bwg_mosaic_play_icon_spun_<?php echo $bwg; ?>  {
        display: table;
        position: absolute;
        left: -10000px;
        top: 0px;
        opacity: 0;
        filter: Alpha(opacity=0);
      }
      .bwg_mosaic_play_icon_<?php echo $bwg; ?> {
        color: #<?php echo $theme_row->mosaic_thumb_title_font_color; ?>;
        font-size: <?php echo 2 * $theme_row->mosaic_thumb_title_font_size; ?>px;
        vertical-align: middle;
        display: table-cell !important;
        z-index: 1;
        text-align: center;
        margin: 0 auto;
      }

      /*pagination styles*/
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .tablenav-pages_<?php echo $bwg; ?> {
        text-align: <?php echo $theme_row->page_nav_align; ?>;
        font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
        font-family: <?php echo $theme_row->page_nav_font_style; ?>;
        font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
        color: #<?php echo $theme_row->page_nav_font_color; ?>;
        margin: 6px 0 4px;
        display: block;
        height: 30px;
        line-height: 30px;
      }
          
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .displaying-num_<?php echo $bwg; ?> {
        font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
        font-family: <?php echo $theme_row->page_nav_font_style; ?>;
        font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
        color: #<?php echo $theme_row->page_nav_font_color; ?>;
        margin-right: 10px;
        vertical-align: middle;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .paging-input_<?php echo $bwg; ?> {
        font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
        font-family: <?php echo $theme_row->page_nav_font_style; ?>;
        font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
        color: #<?php echo $theme_row->page_nav_font_color; ?>;
        vertical-align: middle;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .tablenav-pages_<?php echo $bwg; ?> a.disabled,
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .tablenav-pages_<?php echo $bwg; ?> a.disabled:hover,
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .tablenav-pages_<?php echo $bwg; ?> a.disabled:focus {
        cursor: default;
        color: rgba(<?php echo $rgb_page_nav_font_color['red']; ?>, <?php echo $rgb_page_nav_font_color['green']; ?>, <?php echo $rgb_page_nav_font_color['blue']; ?>, 0.5);
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .tablenav-pages_<?php echo $bwg; ?> a {
        cursor: pointer;
        font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
        font-family: <?php echo $theme_row->page_nav_font_style; ?>;
        font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
        color: #<?php echo $theme_row->page_nav_font_color; ?>;
        text-decoration: none;
        padding: <?php echo $theme_row->page_nav_padding; ?>;
        margin: <?php echo $theme_row->page_nav_margin; ?>;
        border-radius: <?php echo $theme_row->page_nav_border_radius; ?>;
        border-style: <?php echo $theme_row->page_nav_border_style; ?>;
        border-width: <?php echo $theme_row->page_nav_border_width; ?>px;
        border-color: #<?php echo $theme_row->page_nav_border_color; ?>;
        background-color: #<?php echo $theme_row->page_nav_button_bg_color; ?>;
        opacity: <?php echo number_format($theme_row->page_nav_button_bg_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->page_nav_button_bg_transparent; ?>);
        box-shadow: <?php echo $theme_row->page_nav_box_shadow; ?>;
        <?php echo ($theme_row->page_nav_button_transition ) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_back_<?php echo $bwg; ?> {
        background-color: rgba(0, 0, 0, 0);
        color: #<?php echo $theme_row->album_compact_back_font_color; ?> !important;
        cursor: pointer;
        display: block;
        font-family: <?php echo $theme_row->album_compact_back_font_style; ?>;
        font-size: <?php echo $theme_row->album_compact_back_font_size; ?>px;
        font-weight: <?php echo $theme_row->album_compact_back_font_weight; ?>;
        text-decoration: none;
        padding: <?php echo $theme_row->album_compact_back_padding; ?>;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #spider_popup_overlay_<?php echo $bwg; ?> {
        background-color: #<?php echo $theme_row->lightbox_overlay_bg_color; ?>;
        opacity: <?php echo number_format($theme_row->lightbox_overlay_bg_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_overlay_bg_transparent; ?>);
      }
    <?php 
    endif;/*for mosaic view or (thumbnail or masonry) view*/
	?>
	  @media screen and (max-width: <?php echo $params['compuct_album_thumb_width'] + 100; ?>px) { 
		div[class^="bwg_album_thumbnails_"],
		span[class^="bwg_album_thumb_spun1_"],
		span[class^="bwg_album_thumb_spun2_"],
		span[class^="bwg_album_thumb_spun2_"] .bwg_img_custom
		{
			width: 100% !important;
			height: auto !important;
		}
		span[class^="bwg_album_thumb_spun2_"] .bwg_img_custom {
			margin:0px auto !important;
		}
		span[class^="bwg_title_spun1_"] {
			width: 100% !important;
		}
	  }
	  @media screen and (max-width: <?php echo $params['compuct_album_image_thumb_width'] + 100; ?>px) { 
		div[class^="bwg_standart_thumbnails_"],
		span[class^="bwg_standart_thumb_spun1_"],
		span[class^="bwg_standart_thumb_spun2_"],
		span[class^="bwg_standart_thumb_spun2_"] .bwg_img_custom
		{
			width: 100% !important;
			height: auto !important;
		}
		span[class^="bwg_standart_thumb_spun2_"] .bwg_img_custom {
			margin: 0px auto !important;
		}
	  }
	<?php
    return ob_get_clean();
  }
}
