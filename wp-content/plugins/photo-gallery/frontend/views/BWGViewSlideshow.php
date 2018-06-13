<?php
class BWGViewSlideshow extends BWGViewSite {
  public function display($params, $from_shortcode = 0, $bwg = 0) {
    $theme_row = $params['theme_row'];
    $image_rows = $params['image_rows'];
    $image_rows = $image_rows['images'];

    $filmstrip_direction = 'horizontal';
    if ($theme_row->slideshow_filmstrip_pos == 'right' || $theme_row->slideshow_filmstrip_pos == 'left') {
      $filmstrip_direction = 'vertical';
    }

    $slideshow_effect = $params['slideshow_effect'];
    $enable_slideshow_autoplay = $params['enable_slideshow_autoplay'];
    $enable_slideshow_shuffle = $params['enable_slideshow_shuffle'];
    $enable_slideshow_ctrl = $params['enable_slideshow_ctrl'];
    $enable_slideshow_filmstrip = BWG()->is_pro ? $params['enable_slideshow_filmstrip'] : 0;

    $slideshow_filmstrip_height = 0;
    $slideshow_filmstrip_width = 0;
    if ( $enable_slideshow_filmstrip ) {
      $thumb_width = BWG()->options->thumb_width;
      $thumb_height = BWG()->options->thumb_height;
      if ( $filmstrip_direction == 'horizontal' ) {
        $slideshow_filmstrip_height = $params['slideshow_filmstrip_height'];
        $thumb_ratio = $thumb_width / $thumb_height;
        $slideshow_filmstrip_width = round($thumb_ratio * $slideshow_filmstrip_height);
      }
      else {
        $slideshow_filmstrip_width = $params['slideshow_filmstrip_height'];
        $thumb_ratio = $thumb_height / $thumb_width;
        $slideshow_filmstrip_height = round($thumb_ratio * $slideshow_filmstrip_width);
      }
    }

    $enable_image_title = $params['slideshow_enable_title'];
    $slideshow_title_position = explode('-', $params['slideshow_title_position']);
    $enable_image_description = $params['slideshow_enable_description'];
    $slideshow_description_position = explode('-', $params['slideshow_description_position']);
    $enable_slideshow_music = $params['enable_slideshow_music'];
    $slideshow_music_url = $params['slideshow_music_url'];
    // Validate url. If not valid add upload url.
    $url = filter_var($slideshow_music_url, FILTER_SANITIZE_URL);
    if (false === filter_var($url, FILTER_VALIDATE_URL)) {
      $slideshow_music_url = site_url() . '/' . BWG()->upload_dir . $slideshow_music_url;
    }
    $image_width = $params['slideshow_width'];
    $image_height = $params['slideshow_height'];

    $watermark_font_size = $params['watermark_font_size'];
    $watermark_font = $params['watermark_font'];
    $watermark_color = $params['watermark_color'];
    $watermark_opacity = $params['watermark_opacity'];
    $watermark_position = explode('-', $params['watermark_position']);
    $watermark_link = $params['watermark_link'];
    $watermark_url = $params['watermark_url'];
    $watermark_width = $params['watermark_width'];
    $watermark_height = $params['watermark_height'];

    $current_image_id = ($image_rows ? $image_rows[0]->id : 0);
    $play_pause_button_display = 'undefined';
    $filmstrip_thumb_margin = $theme_row->slideshow_filmstrip_thumb_margin;
    $margins_split = explode(" ", $filmstrip_thumb_margin);
    $temp_iterator = ($filmstrip_direction == 'horizontal' ? 1 : 0);
    if (isset($margins_split[$temp_iterator])) {
      $filmstrip_thumb_margin_right = (int) $margins_split[$temp_iterator];
      if (isset($margins_split[$temp_iterator + 2])) {
        $filmstrip_thumb_margin_left = (int) $margins_split[$temp_iterator + 2];
      }
      else {
        $filmstrip_thumb_margin_left = $filmstrip_thumb_margin_right;
      }
    }
    elseif (isset($margins_split[0])) {
      $filmstrip_thumb_margin_right = (int) $margins_split[0];
      $filmstrip_thumb_margin_left = $filmstrip_thumb_margin_right;
    }
    $filmstrip_thumb_margin_hor = $filmstrip_thumb_margin_right + $filmstrip_thumb_margin_left;
    if (!$enable_slideshow_filmstrip) {
      if ($theme_row->slideshow_filmstrip_pos == 'left') {
        $theme_row->slideshow_filmstrip_pos = 'top';
      }
      if ($theme_row->slideshow_filmstrip_pos == 'right') {
        $theme_row->slideshow_filmstrip_pos = 'bottom';
      }
    }
    $left_or_top = 'left';
    $width_or_height = 'width';
    $outerWidth_or_outerHeight = 'outerWidth';
    if (!($filmstrip_direction == 'horizontal')) {
      $left_or_top = 'top';
      $width_or_height = 'height';
      $outerWidth_or_outerHeight = 'outerHeight';
    }

    $inline_style = $this->inline_styles($bwg, $theme_row, $params, $image_width, $image_height, $filmstrip_direction, $slideshow_filmstrip_height, BWG()->options, $left_or_top, $width_or_height, $filmstrip_thumb_margin_hor, $slideshow_filmstrip_width, $image_rows, $watermark_position, $slideshow_title_position, $slideshow_description_position, $watermark_height, $watermark_width, $watermark_opacity, $watermark_font_size, $watermark_font, $watermark_color, $enable_slideshow_filmstrip);
    if ( BWG()->options->use_inline_stiles_and_scripts ) {
      wp_add_inline_style('bwg_frontend', $inline_style);
      if ( !wp_script_is('bwg_embed', 'done') ) {
        wp_print_scripts('bwg_embed');
      }
      if ( !wp_script_is('bwg_jquery_mobile', 'done') ) {
        wp_print_scripts('bwg_jquery_mobile');
      }
    }
    else {
      echo '<style>' . $inline_style . '</style>';
    }

    ?>
    <script>
      var data_<?php echo $bwg; ?> = [];
      var event_stack_<?php echo $bwg; ?> = [];
      <?php
      foreach ($image_rows as $key => $image_row) {
        if ($image_row->id == $current_image_id) {
          $current_image_alt = $image_row->alt;
          $current_image_description = str_replace(array("\r\n", "\n", "\r"), esc_html('<br />'), $image_row->description);
        }
        ?>
        data_<?php echo $bwg; ?>["<?php echo $key; ?>"] = [];
        data_<?php echo $bwg; ?>["<?php echo $key; ?>"]["id"] = "<?php echo $image_row->id; ?>";
        data_<?php echo $bwg; ?>["<?php echo $key; ?>"]["alt"] = "<?php echo str_replace(array("\r\n", "\n", "\r"), esc_html('<br />'), $image_row->alt); ?>";
        data_<?php echo $bwg; ?>["<?php echo $key; ?>"]["description"] = "<?php echo str_replace(array("\r\n", "\n", "\r"), esc_html('<br />'), $image_row->description); ?>";
        data_<?php echo $bwg; ?>["<?php echo $key; ?>"]["filetype"] = "<?php echo $image_row->filetype; ?>";
        data_<?php echo $bwg; ?>["<?php echo $key; ?>"]["filename"] = "<?php echo $image_row->filename; ?>";
        data_<?php echo $bwg; ?>["<?php echo $key; ?>"]["image_url"] = "<?php echo $image_row->image_url; ?>";
        data_<?php echo $bwg; ?>["<?php echo $key; ?>"]["thumb_url"] = "<?php echo $image_row->thumb_url; ?>";
        data_<?php echo $bwg; ?>["<?php echo $key; ?>"]["redirect_url"] = "<?php echo $image_row->redirect_url; ?>";
        data_<?php echo $bwg; ?>["<?php echo $key; ?>"]["date"] = "<?php echo $image_row->date; ?>";
        data_<?php echo $bwg; ?>["<?php echo $key; ?>"]["is_embed"] = "<?php echo (preg_match('/EMBED/',$image_row->filetype)==1 ? true :false); ?>";
        data_<?php echo $bwg; ?>["<?php echo $key; ?>"]["is_embed_video"] = "<?php echo (((preg_match('/EMBED/',$image_row->filetype)==1) && (preg_match('/_VIDEO/',$image_row->filetype)==1)) ? true :false); ?>";
        <?php
      }
      ?>
    </script>
    <?php
    ob_start();
    ?>
    <div id="gal_front_form_<?php echo $bwg; ?>" class="bwg-hidden bwg_slideshow_image_wrap_<?php echo $bwg; ?>">
      <?php
      $current_pos = 0;
      if ($enable_slideshow_filmstrip) {
        ?>
        <div class="bwg_slideshow_filmstrip_container_<?php echo $bwg; ?>">
          <div class="bwg_slideshow_filmstrip_left_<?php echo $bwg; ?>"><i class="fa <?php echo ($filmstrip_direction == 'horizontal'? 'fa-angle-left' : 'fa-angle-up'); ?>"></i></div>
          <div class="bwg_slideshow_filmstrip_<?php echo $bwg; ?>">
            <div class="bwg_slideshow_filmstrip_thumbnails_<?php echo $bwg; ?>">
              <?php
              foreach ($image_rows as $key => $image_row) {
                if ($image_row->id == $current_image_id) {
                  $current_pos = $key * (($filmstrip_direction == 'horizontal' ? $slideshow_filmstrip_width : $slideshow_filmstrip_height) + $filmstrip_thumb_margin_hor);
                  $current_key = $key;
                }
                $is_embed = preg_match('/EMBED/', $image_row->filetype) == 1 ? true :false;
                $is_embed_video = ($is_embed && preg_match('/_VIDEO/', $image_row->filetype) == 1) ? true : false;
                $is_embed_instagram = preg_match('/EMBED_OEMBED_INSTAGRAM/', $image_row->filetype) == 1 ? true : false;
                if ($play_pause_button_display === 'undefined') {
                  if ($is_embed_video) {
                    $play_pause_button_display = 'none';
                  }
                  else {
                    $play_pause_button_display = '';
                  }
                }
                if (!$is_embed) {
                  $thumb_path_url = htmlspecialchars_decode(ABSPATH . BWG()->upload_dir . $image_row->thumb_url, ENT_COMPAT | ENT_QUOTES);
                  $thumb_path_url = explode('?bwg', $thumb_path_url);
                  list($image_thumb_width, $image_thumb_height) = getimagesize($thumb_path_url[0]);
                }
                else {
                  if ($image_row->resolution != '') {
                    if (!$is_embed_instagram) {
                      $resolution_arr = explode(" ",$image_row->resolution);
                      $resolution_w = intval($resolution_arr[0]);
                      $resolution_h = intval($resolution_arr[2]);
                      if ($resolution_w != 0 && $resolution_h != 0){
                        $scale = $scale = max($slideshow_filmstrip_width / $resolution_w, $slideshow_filmstrip_height / $resolution_h);
                        $image_thumb_width = $resolution_w * $scale;
                        $image_thumb_height = $resolution_h * $scale;
                      }
                      else {
                        $image_thumb_width = $slideshow_filmstrip_width;
                        $image_thumb_height = $slideshow_filmstrip_height;
                      }
                    }
                    else {
                      // this will be ok while instagram thumbnails width and height are the same
                      $image_thumb_width = min($slideshow_filmstrip_width, $slideshow_filmstrip_height);
                      $image_thumb_height = $image_thumb_width;
                    }
                  }
                  else {
                    $image_thumb_width = $slideshow_filmstrip_width;
                    $image_thumb_height = $slideshow_filmstrip_height;
                  }
                }
                $scale = max($slideshow_filmstrip_width / $image_thumb_width, $slideshow_filmstrip_height / $image_thumb_height);
                $image_thumb_width *= $scale;
                $image_thumb_height *= $scale;
                $thumb_left = ($slideshow_filmstrip_width - $image_thumb_width) / 2;
                $thumb_top = ($slideshow_filmstrip_height - $image_thumb_height) / 2;
              ?>
              <div id="bwg_filmstrip_thumbnail_<?php echo $key; ?>_<?php echo $bwg; ?>" class="bwg_slideshow_filmstrip_thumbnail_<?php echo $bwg; ?> <?php echo (($image_row->id == $current_image_id) ? 'bwg_slideshow_thumb_active_' . $bwg : 'bwg_slideshow_thumb_deactive_' . $bwg); ?>">
                <img style="width:<?php echo $image_thumb_width; ?>px; height:<?php echo $image_thumb_height; ?>px; margin-left: <?php echo $thumb_left; ?>px; margin-top: <?php echo $thumb_top; ?>px;" class="bwg_filmstrip_thumbnail_img bwg_slideshow_filmstrip_thumbnail_img_<?php echo $bwg; ?>" src="<?php echo ( $is_embed ? "" : site_url() . '/' . BWG()->upload_dir) . $image_row->thumb_url; ?>" onclick="bwg_change_image_<?php echo $bwg; ?>(parseInt(jQuery('#bwg_current_image_key_<?php echo $bwg; ?>').val()), '<?php echo $key; ?>', data_<?php echo $bwg; ?>)" image_id="<?php echo $image_row->id; ?>" image_key="<?php echo $key; ?>" alt="<?php echo $image_row->alt; ?>"/>
              </div>
              <?php
              }
              ?>
            </div>
          </div>
          <div class="bwg_slideshow_filmstrip_right_<?php echo $bwg; ?>"><i class="fa <?php echo ($filmstrip_direction == 'horizontal'? 'fa-angle-right' : 'fa-angle-down'); ?>"></i></div>
        </div>
        <?php
      }
      else {
        ?>
        <div class="bwg_slideshow_dots_container_<?php echo $bwg; ?>">
          <div class="bwg_slideshow_dots_thumbnails_<?php echo $bwg; ?>">
            <?php
            foreach ($image_rows as $key => $image_row) {
              if ($image_row->id == $current_image_id) {
                $current_pos = $key * ($slideshow_filmstrip_width + 2);
                $current_key = $key;
              }
            ?>
            <span id="bwg_dots_<?php echo $key; ?>_<?php echo $bwg; ?>" class="bwg_slideshow_dots_<?php echo $bwg; ?> <?php echo (($image_row->id == $current_image_id) ? 'bwg_slideshow_dots_active_' . $bwg : 'bwg_slideshow_dots_deactive_' . $bwg); ?>" onclick="bwg_change_image_<?php echo $bwg; ?>(parseInt(jQuery('#bwg_current_image_key_<?php echo $bwg; ?>').val()), '<?php echo $key; ?>', data_<?php echo $bwg; ?>)" image_id="<?php echo $image_row->id; ?>" image_key="<?php echo $key; ?>"></span>
            <?php
            }
            ?>
          </div>
        </div>
        <?php
      }
      ?>
      <div id="bwg_slideshow_image_container_<?php echo $bwg; ?>" class="bwg_slideshow_image_container_<?php echo $bwg; ?>">
        <div class="bwg_slide_container_<?php echo $bwg; ?>">
          <div class="bwg_slide_bg_<?php echo $bwg; ?>">
            <div class="bwg_slider_<?php echo $bwg; ?>">
            <?php
            foreach ($image_rows as $key => $image_row) {
              $is_embed = preg_match('/EMBED/',$image_row->filetype)==1 ? true :false;
              $is_embed_instagram_post = preg_match('/INSTAGRAM_POST/',$image_row->filetype)==1 ? true :false;
              $is_embed_instagram_video = preg_match('/INSTAGRAM_VIDEO/', $image_row->filetype) == 1 ? true :false;
              if ($image_row->id == $current_image_id) {
                $current_key = $key;
                ?>
                <span class="bwg_slideshow_image_spun_<?php echo $bwg; ?>" id="image_id_<?php echo $bwg; ?>_<?php echo $image_row->id; ?>">
                  <span class="bwg_slideshow_image_spun1_<?php echo $bwg; ?>">
                    <span class="bwg_slideshow_image_spun2_<?php echo $bwg; ?>">
                      <?php
                        if (!$is_embed) {
                        ?>
                        <a <?php echo ($params['thumb_click_action'] == 'open_lightbox' ? (' class="bwg_lightbox"' . (BWG()->options->enable_seo ? ' href="' . ($is_embed ? $image_row->thumb_url : site_url() . '/' . BWG()->upload_dir . $image_row->image_url) . '"' : '') . ' data-image-id="' . $image_row->id . '"') : ($params['thumb_click_action'] == 'redirect_to_url' && $image_row->redirect_url ? 'href="' . $image_row->redirect_url . '" target="' .  ($params['thumb_link_target'] ? '_blank' : '')  . '"' : '')) ?>>
                        <img id="bwg_slideshow_image_<?php echo $bwg; ?>" class="bwg_slide bwg_slideshow_image_<?php echo $bwg; ?>" src="<?php echo site_url() . '/' . BWG()->upload_dir . $image_row->image_url; ?>" image_id="<?php echo $image_row->id; ?>" alt="<?php echo $image_row->alt; ?>"/>
                        </a>
                        <?php
                        }
                        else{  /*$is_embed*/?>
                        <span id="bwg_slideshow_image_<?php echo $bwg; ?>" class="bwg_slideshow_embed_<?php echo $bwg; ?>" image_id="<?php echo $image_row->id; ?>">
                          <?php echo $is_embed_instagram_video ? '<span class="bwg_inst_play_btn_cont" onclick="bwg_play_instagram_video(this)" ><span class="bwg_inst_play"></span></span>' : '';
                          if ($is_embed_instagram_post) {
                            $post_width = $image_width - ($filmstrip_direction == 'vertical' ? $slideshow_filmstrip_width : 0);
                            $post_height = $image_height - ($filmstrip_direction == 'horizontal' ? $slideshow_filmstrip_height : 0);
                            if ($post_height < $post_width + 88) {
                              $post_width = $post_height - 88;
                            }
                            else {
                              $post_height = $post_width + 88;
                            }
                            $instagram_post_width = $post_width;
                            $instagram_post_height = $post_height;
                            $image_resolution = explode(' x ', $image_row->resolution);
                            if (is_array($image_resolution)) {
                              $instagram_post_width = $image_resolution[0];
                              $instagram_post_height = explode(' ', $image_resolution[1]);
                              $instagram_post_height = $instagram_post_height[0];
                            }
                            WDWLibraryEmbed::display_embed($image_row->filetype, $image_row->image_url, $image_row->filename, array('class' => "bwg_embed_frame_" . $bwg, 'data-width' => $instagram_post_width, 'data-height' => $instagram_post_height, 'frameborder' => "0", 'style' => "width:" . $post_width . "px; height:" . $post_height . "px; vertical-align:middle; display:inline-block; position:relative;"));
                          }
                          else {
                            WDWLibraryEmbed::display_embed($image_row->filetype, $image_row->image_url, $image_row->filename, array('class'=>"bwg_embed_frame_".$bwg, 'frameborder'=>"0", 'allowfullscreen'=>"allowfullscreen", 'style'=>"width:inherit; height:inherit; vertical-align:middle; display:table-cell;"));
                          }
                          ?>
                        </span>
                        <?php
                        }
                      ?>
                    </span>
                  </span>
                </span>
                <span class="bwg_slideshow_image_second_spun_<?php echo $bwg; ?>">
                </span>
                <input type="hidden" id="bwg_current_image_key_<?php echo $bwg; ?>" value="<?php echo $key; ?>" />
                <?php
                break;
              }
              else {
                ?>
                <span class="bwg_slideshow_image_second_spun_<?php echo $bwg; ?>" id="image_id_<?php echo $bwg; ?>_<?php echo $image_row->id; ?>">
                  <span class="bwg_slideshow_image_spun1_<?php echo $bwg; ?>">
                    <span class="bwg_slideshow_image_spun2_<?php echo $bwg; ?>">
                      <?php
                        if (!$is_embed) {
                        ?>
                        <a <?php echo ($params['thumb_click_action'] == 'open_lightbox' ? (' class="bwg_lightbox_' . $bwg . '"' . (BWG()->options->enable_seo ? ' href="' . ($is_embed ? $image_row->thumb_url : site_url() . '/' . BWG()->upload_dir . $image_row->image_url) . '"' : '') . ' data-image-id="' . $image_row->id . '"') : ($params['thumb_click_action'] == 'redirect_to_url' && $image_row->redirect_url ? 'href="' . $image_row->redirect_url . '" target="' .  ($params['thumb_link_target'] ? '_blank' : '')  . '"' : '')) ?>>
                        <img id="bwg_slideshow_image_<?php echo $bwg; ?>" class="bwg_slide bwg_slideshow_image_<?php echo $bwg; ?>" src="<?php echo site_url() . '/' . BWG()->upload_dir . $image_row->image_url; ?>" image_id="<?php echo $image_row->id; ?>" alt="<?php echo $image_row->alt; ?>"/>
                        </a>
                        <?php
                        }
                        else {   /*$is_embed*/ ?>
                        <span class="bwg_slideshow_embed_<?php echo $bwg; ?>">
                            <?php
                          if($is_embed_instagram_post){
                            $post_width = $image_width - ($filmstrip_direction == 'vertical' ? $slideshow_filmstrip_width : 0);
                            $post_height = $image_height - ($filmstrip_direction == 'horizontal' ? $slideshow_filmstrip_height : 0);
                            if($post_height < $post_width + 88) {
                              $post_width = $post_height - 88;
                            }
                            else{
                              $post_height = $post_width + 88;
                            }
                            $instagram_post_width = $post_width;
                            $instagram_post_height = $post_height;
                            $image_resolution = explode(' x ', $image_row->resolution);
                            if (is_array($image_resolution)) {
                              $instagram_post_width = $image_resolution[0];
                              $instagram_post_height = explode(' ', $image_resolution[1]);
                              $instagram_post_height = $instagram_post_height[0];
                            }
                            WDWLibraryEmbed::display_embed($image_row->filetype, $image_row->image_url, $image_row->filename, array('class'=>"bwg_embed_frame_".$bwg, 'data-width' => $instagram_post_width, 'data-height' => $instagram_post_height, 'frameborder'=>"0", 'style'=>"width:".$post_width."px; height:".$post_height."px; vertical-align:middle; display:inline-block; position:relative;"));
                          }
                          else{
                          WDWLibraryEmbed::display_embed($image_row->filetype, $image_row->image_url, $image_row->filename, array('class'=>"bwg_embed_frame_".$bwg, 'frameborder'=>"0", 'allowfullscreen'=>"allowfullscreen", 'style'=>"width:inherit; height:inherit; vertical-align:middle; display:table-cell;"));
                          }
                          ?>
                        </span>
                        <?php
                        }
                      ?>
                    </span>
                  </span>
                </span>
                <?php
              }
            }
            ?>
            </div>
          </div>
        </div>
        <?php
          if ($enable_slideshow_ctrl) {
            ?>
          <a id="spider_slideshow_left_<?php echo $bwg; ?>" onclick="bwg_change_image_<?php echo $bwg; ?>(parseInt(jQuery('#bwg_current_image_key_<?php echo $bwg; ?>').val()), (parseInt(jQuery('#bwg_current_image_key_<?php echo $bwg; ?>').val()) + data_<?php echo $bwg; ?>.length - bwg_iterator_<?php echo $bwg; ?>()) % data_<?php echo $bwg; ?>.length, data_<?php echo $bwg; ?>); return false;"><span id="spider_slideshow_left-ico_<?php echo $bwg; ?>"><span><i class="bwg_slideshow_prev_btn_<?php echo $bwg; ?> fa <?php echo $theme_row->slideshow_rl_btn_style; ?>-left"></i></span></span></a>
          <span id="bwg_slideshow_play_pause_<?php echo $bwg; ?>" style="display: <?php echo $play_pause_button_display; ?>;"><span><span id="bwg_slideshow_play_pause-ico_<?php echo $bwg; ?>"><i class="bwg_ctrl_btn_<?php echo $bwg; ?> bwg_slideshow_play_pause_<?php echo $bwg; ?> fa fa-play"></i></span></span></span>
          <a id="spider_slideshow_right_<?php echo $bwg; ?>" onclick="bwg_change_image_<?php echo $bwg; ?>(parseInt(jQuery('#bwg_current_image_key_<?php echo $bwg; ?>').val()), (parseInt(jQuery('#bwg_current_image_key_<?php echo $bwg; ?>').val()) + bwg_iterator_<?php echo $bwg; ?>()) % data_<?php echo $bwg; ?>.length, data_<?php echo $bwg; ?>); return false;"><span id="spider_slideshow_right-ico_<?php echo $bwg; ?>"><span><i class="bwg_slideshow_next_btn_<?php echo $bwg; ?> fa <?php echo $theme_row->slideshow_rl_btn_style; ?>-right"></i></span></span></a>
          <?php
          }
        ?>
      </div>
      <?php
      if ($params['watermark_type'] != 'none') {
      ?>
      <div class="bwg_slideshow_image_container_<?php echo $bwg; ?>" style="position: absolute;">
        <div class="bwg_slideshow_watermark_container_<?php echo $bwg; ?>">
          <div style="display:table; margin:0 auto;">
            <span class="bwg_slideshow_watermark_spun_<?php echo $bwg; ?>" id="bwg_slideshow_watermark_container_<?php echo $bwg; ?>">
              <?php
              if ($params['watermark_type'] == 'image') {
              ?>
              <a href="<?php echo urldecode($watermark_link); ?>" target="_blank">
                <img class="bwg_slideshow_watermark_image_<?php echo $bwg; ?> bwg_slideshow_watermark_<?php echo $bwg; ?>" src="<?php echo urldecode($watermark_url); ?>" />
              </a>
              <?php
              }
              elseif ($params['watermark_type'] == 'text') {
              ?>
              <a class="bwg_none_selectable_<?php echo $bwg; ?> bwg_slideshow_watermark_text_<?php echo $bwg; ?> bwg_slideshow_watermark_<?php echo $bwg; ?>" target="_blank" href="<?php echo urldecode($watermark_link); ?>"><?php echo $params['watermark_text']; ?></a>
              <?php
              }
              ?>
            </span>
          </div>
        </div>
      </div>
      <?php
      }
      if ($enable_image_title) {
      ?>
      <div class="bwg_slideshow_image_container_<?php echo $bwg; ?>" style="position: absolute;">
        <div class="bwg_slideshow_watermark_container_<?php echo $bwg; ?>">
          <div style="display:table; margin:0 auto;">
            <span class="bwg_slideshow_title_spun_<?php echo $bwg; ?>">
              <div class="bwg_slideshow_title_text_<?php echo $bwg; ?>" style="<?php if (!$current_image_alt) echo 'display:none;'; ?>">
                <?php echo html_entity_decode($current_image_alt); ?>
              </div>
            </span>
          </div>
        </div>
      </div>
      <?php
      }
      if ($enable_image_description) {
      ?>
      <div class="bwg_slideshow_image_container_<?php echo $bwg; ?>" style="position: absolute;">
        <div class="bwg_slideshow_watermark_container_<?php echo $bwg; ?>">
          <div style="display:table; margin:0 auto;">
            <span class="bwg_slideshow_description_spun_<?php echo $bwg; ?>">
              <div class="bwg_slideshow_description_text_<?php echo $bwg; ?>" style="<?php if (!$current_image_description) echo 'display:none;'; ?>">
                <?php echo html_entity_decode(str_replace("\r\n", esc_html('<br />'), $current_image_description)); ?>
              </div>
            </span>
          </div>
        </div>
      </div>
      <?php
      }
      if ($enable_slideshow_music) {
        ?>
        <audio id="bwg_audio_<?php echo $bwg; ?>" src="<?php echo $slideshow_music_url ?>" loop volume="1.0"></audio>
        <?php
      }
      ?>
    </div>
    <?php

    parent::display($params, $bwg, ob_get_clean());

    ?>
	  <script>
      var bwg_trans_in_progress_<?php echo $bwg; ?> = false;
      var bwg_transition_duration_<?php echo $bwg; ?> = <?php echo (($params['slideshow_interval'] < 4) && ($params['slideshow_interval'] != 0)) ? ($params['slideshow_interval'] * 1000) / 4 : ($params['slideshow_effect_duration'] * 1000); ?>;
      var bwg_playInterval_<?php echo $bwg; ?>;
      /* Stop autoplay.*/
      window.clearInterval(bwg_playInterval_<?php echo $bwg; ?>);
      /* Set watermark container size.*/
      function bwg_change_watermark_container_<?php echo $bwg; ?>() {
        jQuery(".bwg_slider_<?php echo $bwg; ?>").children().each(function() {
          if (jQuery(this).css("zIndex") == 2) {
            /* For images.*/
            var bwg_current_image_span = jQuery(this).find("img");
            if (bwg_current_image_span.length) {
              if (bwg_current_image_span.prop('complete')) {
                var width = bwg_current_image_span.width();
                var height = bwg_current_image_span.height();
                bwg_change_each_watermark_container_<?php echo $bwg; ?>(width, height);
              }
              else {
                bwg_current_image_span.on("load", function () {
                  var width = bwg_current_image_span.width();
                  var height = bwg_current_image_span.height();
                  bwg_change_each_watermark_container_<?php echo $bwg; ?>(width, height);
                });
              }
            }
            else {
              /* For embeds and videos.*/
              bwg_current_image_span = jQuery(this).find("iframe");
              if (!bwg_current_image_span.length) {
                bwg_current_image_span = jQuery(this).find("video");
              }
              var width = bwg_current_image_span.width();
              var height = bwg_current_image_span.height();
              bwg_change_each_watermark_container_<?php echo $bwg; ?>(width, height);
            }
          }
        });
      }
      /* Set each watermark container size.*/
      function bwg_change_each_watermark_container_<?php echo $bwg; ?>(width, height) {
        jQuery(".bwg_slideshow_watermark_spun_<?php echo $bwg; ?>").width(width);
        jQuery(".bwg_slideshow_watermark_spun_<?php echo $bwg; ?>").height(height);
        jQuery(".bwg_slideshow_title_spun_<?php echo $bwg; ?>").width(width);
        jQuery(".bwg_slideshow_title_spun_<?php echo $bwg; ?>").height(height);
        jQuery(".bwg_slideshow_description_spun_<?php echo $bwg; ?>").width(width);
        jQuery(".bwg_slideshow_description_spun_<?php echo $bwg; ?>").height(height);
        jQuery(".bwg_slideshow_watermark_<?php echo $bwg; ?>").css({display: ''});
        if (jQuery.trim(jQuery(".bwg_slideshow_title_text_<?php echo $bwg; ?>").text())) {
          jQuery(".bwg_slideshow_title_text_<?php echo $bwg; ?>").css({display: ''});
        }
        if (jQuery.trim(jQuery(".bwg_slideshow_description_text_<?php echo $bwg; ?>").text())) {
          jQuery(".bwg_slideshow_description_text_<?php echo $bwg; ?>").css({display: ''});
        }
      }
      var bwg_current_key_<?php echo $bwg; ?> = '<?php echo (isset($current_key) ? $current_key : ''); ?>';
      var bwg_current_filmstrip_pos_<?php echo $bwg; ?> = <?php echo $current_pos; ?>;
      /* Set filmstrip initial position.*/
      function bwg_set_filmstrip_pos_<?php echo $bwg; ?>(filmStripWidth) {
        var selectedImagePos = -bwg_current_filmstrip_pos_<?php echo $bwg; ?> - (jQuery(".bwg_slideshow_filmstrip_thumbnail_<?php echo $bwg; ?>").<?php echo $width_or_height; ?>() + <?php echo $filmstrip_thumb_margin_hor; ?>) / 2;
        var imagesContainerLeft = Math.min(0, Math.max(filmStripWidth - jQuery(".bwg_slideshow_filmstrip_thumbnails_<?php echo $bwg; ?>").<?php echo $width_or_height; ?>(), selectedImagePos + filmStripWidth / 2));
        jQuery(".bwg_slideshow_filmstrip_thumbnails_<?php echo $bwg; ?>").animate({
            <?php echo $left_or_top; ?>: imagesContainerLeft
          }, {
            duration: 500,
            complete: function () { bwg_filmstrip_arrows_<?php echo $bwg; ?>(); }
          });
      }
      function bwg_move_filmstrip_<?php echo $bwg; ?>() {
        var image_left = jQuery(".bwg_slideshow_thumb_active_<?php echo $bwg; ?>").position().<?php echo $left_or_top; ?>;
        var image_right = jQuery(".bwg_slideshow_thumb_active_<?php echo $bwg; ?>").position().<?php echo $left_or_top; ?> + jQuery(".bwg_slideshow_thumb_active_<?php echo $bwg; ?>").<?php echo $outerWidth_or_outerHeight; ?>(true);
        var bwg_filmstrip_width = jQuery(".bwg_slideshow_filmstrip_<?php echo $bwg; ?>").<?php echo $outerWidth_or_outerHeight; ?>(true);
        var bwg_filmstrip_thumbnails_width = jQuery(".bwg_slideshow_filmstrip_thumbnails_<?php echo $bwg; ?>").<?php echo $outerWidth_or_outerHeight; ?>(true);
        var long_filmstrip_cont_left = jQuery(".bwg_slideshow_filmstrip_thumbnails_<?php echo $bwg; ?>").position().<?php echo $left_or_top; ?>;
        var long_filmstrip_cont_right = Math.abs(jQuery(".bwg_slideshow_filmstrip_thumbnails_<?php echo $bwg; ?>").position().<?php echo $left_or_top; ?>) + bwg_filmstrip_width;
        if (bwg_filmstrip_width > bwg_filmstrip_thumbnails_width) {
          return;
        }
        if (image_left < Math.abs(long_filmstrip_cont_left)) {
          jQuery(".bwg_slideshow_filmstrip_thumbnails_<?php echo $bwg; ?>").animate({
            <?php echo $left_or_top; ?>: -image_left
          }, {
            duration: 500,
            complete: function () { bwg_filmstrip_arrows_<?php echo $bwg; ?>(); }
          });
        }
        else if (image_right > long_filmstrip_cont_right) {
          jQuery(".bwg_slideshow_filmstrip_thumbnails_<?php echo $bwg; ?>").animate({
            <?php echo $left_or_top; ?>: -(image_right - bwg_filmstrip_width)
          }, {
            duration: 500,
            complete: function () { bwg_filmstrip_arrows_<?php echo $bwg; ?>(); }
          });
        }
      }
      function bwg_move_dots_<?php echo $bwg; ?>() {
        var image_left = jQuery(".bwg_slideshow_dots_active_<?php echo $bwg; ?>").position().left;
        var image_right = jQuery(".bwg_slideshow_dots_active_<?php echo $bwg; ?>").position().left + jQuery(".bwg_slideshow_dots_active_<?php echo $bwg; ?>").outerWidth(true);
        var bwg_dots_width = jQuery(".bwg_slideshow_dots_container_<?php echo $bwg; ?>").outerWidth(true);
        var bwg_dots_thumbnails_width = jQuery(".bwg_slideshow_dots_thumbnails_<?php echo $bwg; ?>").outerWidth(false);
        var long_filmstrip_cont_left = jQuery(".bwg_slideshow_dots_thumbnails_<?php echo $bwg; ?>").position().left;
        var long_filmstrip_cont_right = Math.abs(jQuery(".bwg_slideshow_dots_thumbnails_<?php echo $bwg; ?>").position().left) + bwg_dots_width;
        if (bwg_dots_width > bwg_dots_thumbnails_width) {
          return;
        }
        if (image_left < Math.abs(long_filmstrip_cont_left)) {
          jQuery(".bwg_slideshow_dots_thumbnails_<?php echo $bwg; ?>").animate({
            left: -image_left
          }, {
            duration: 500,
            complete: function () {  }
          });
        }
        else if (image_right > long_filmstrip_cont_right) {
          jQuery(".bwg_slideshow_dots_thumbnails_<?php echo $bwg; ?>").animate({
            left: -(image_right - bwg_dots_width)
          }, {
            duration: 500,
            complete: function () {  }
          });
        }
      }
      /* Show/hide filmstrip arrows.*/
      function bwg_filmstrip_arrows_<?php echo $bwg; ?>() {
        if (jQuery(".bwg_slideshow_filmstrip_thumbnails_<?php echo $bwg; ?>").<?php echo $width_or_height; ?>() < jQuery(".bwg_slideshow_filmstrip_<?php echo $bwg; ?>").<?php echo $width_or_height; ?>()) {
          jQuery(".bwg_slideshow_filmstrip_left_<?php echo $bwg; ?>").hide();
          jQuery(".bwg_slideshow_filmstrip_right_<?php echo $bwg; ?>").hide();
        }
        else {
          jQuery(".bwg_slideshow_filmstrip_left_<?php echo $bwg; ?>").show();
          jQuery(".bwg_slideshow_filmstrip_right_<?php echo $bwg; ?>").show();
        }
      }
      function bwg_testBrowser_cssTransitions_<?php echo $bwg; ?>() {
        return bwg_testDom_<?php echo $bwg; ?>('Transition');
      }
      function bwg_testBrowser_cssTransforms3d_<?php echo $bwg; ?>() {
        return bwg_testDom_<?php echo $bwg; ?>('Perspective');
      }
      function bwg_testDom_<?php echo $bwg; ?>(prop) {
        /* Browser vendor CSS prefixes.*/
        var browserVendors = ['', '-webkit-', '-moz-', '-ms-', '-o-', '-khtml-'];
        /* Browser vendor DOM prefixes.*/
        var domPrefixes = ['', 'Webkit', 'Moz', 'ms', 'O', 'Khtml'];
        var i = domPrefixes.length;
        while (i--) {
          if (typeof document.body.style[domPrefixes[i] + prop] !== 'undefined') {
            return true;
          }
        }
        return false;
      }

      bwg_param = {
        bwg_current_key : bwg_current_key_<?php echo $bwg; ?>,
        bwg_transition_duration : bwg_transition_duration_<?php echo $bwg; ?>,
        bwg_trans_in_progress : false,
        data : data_<?php echo $bwg; ?>,
      };

      if ( typeof bwg_params == "undefined" ) {
        var bwg_params = [];
      }
      bwg_params[<?php echo $bwg; ?>] = bwg_param;

      /* For browsers that does not support transitions.*/
      function bwg_fallback_<?php echo $bwg; ?>(current_image_class, next_image_class, direction) {
        bwg_fade(current_image_class, next_image_class, direction, <?php echo $bwg; ?>);
      }
      /* For browsers that support transitions, but not 3d transforms (only used if primary transition makes use of 3d-transforms).*/
      function bwg_fallback3d_<?php echo $bwg; ?>(current_image_class, next_image_class, direction) {
        bwg_sliceV(current_image_class, next_image_class, direction, <?php echo $bwg; ?>);
      }
      function bwg_none_<?php echo $bwg; ?>(current_image_class, next_image_class, direction) {
        jQuery(current_image_class).css({'opacity' : 0, 'z-index': 1});
        jQuery(next_image_class).css({'opacity' : 1, 'z-index' : 2});
        bwg_change_watermark_container_<?php echo $bwg; ?>();
        /* Set active thumbnail.*/
        jQuery(".bwg_slideshow_filmstrip_thumbnail_<?php echo $bwg; ?>").removeClass("bwg_slideshow_thumb_active_<?php echo $bwg; ?>").addClass("bwg_slideshow_thumb_deactive_<?php echo $bwg; ?>");
        jQuery("#bwg_filmstrip_thumbnail_" + bwg_current_key_<?php echo $bwg; ?> + "_<?php echo $bwg; ?>").removeClass("bwg_slideshow_thumb_deactive_<?php echo $bwg; ?>").addClass("bwg_slideshow_thumb_active_<?php echo $bwg; ?>");
        jQuery(".bwg_slideshow_dots_<?php echo $bwg; ?>").removeClass("bwg_slideshow_dots_active_<?php echo $bwg; ?>").addClass("bwg_slideshow_dots_deactive_<?php echo $bwg; ?>");
        jQuery("#bwg_dots_" + bwg_current_key_<?php echo $bwg; ?> + "_<?php echo $bwg; ?>").removeClass("bwg_slideshow_dots_deactive_<?php echo $bwg; ?>").addClass("bwg_slideshow_dots_active_<?php echo $bwg; ?>");
      }
      function bwg_iterator_<?php echo $bwg; ?>() {
        var iterator = 1;
        if (<?php echo $enable_slideshow_shuffle; ?>) {
          iterator = Math.floor((data_<?php echo $bwg; ?>.length - 1) * Math.random() + 1);
        }
        return iterator;
      }
      function bwg_change_image_<?php echo $bwg; ?>(current_key, key, data_<?php echo $bwg; ?>, from_effect) {
        /* Pause videos.*/
        jQuery("#bwg_slideshow_image_container_<?php echo $bwg; ?>").find("iframe").each(function () {
          jQuery(this)[0].contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
          jQuery(this)[0].contentWindow.postMessage('{ "method": "pause" }', "*");
          jQuery(this)[0].contentWindow.postMessage('pause', '*');
        });
        /* Pause videos facebook video.*/
        jQuery('#image_id_<?php echo $bwg; ?>_' + data_<?php echo $bwg; ?>[current_key]["id"]).find('.bwg_fb_video').each(function () {
          jQuery(this).attr('src', jQuery(this).attr('src'));
        });
        if (data_<?php echo $bwg; ?>[key]) {
          if (jQuery('.bwg_ctrl_btn_<?php echo $bwg; ?>').hasClass('fa-pause')) {
            bwg_play_<?php echo $bwg; ?>();
          }
          if (!from_effect) {
            /* Change image key.*/
            jQuery("#bwg_current_image_key_<?php echo $bwg; ?>").val(key);
            if (current_key == '-1') { /* Filmstrip.*/
              current_key = jQuery(".bwg_slideshow_thumb_active_<?php echo $bwg; ?>").children("img").attr("image_key");
            }
            else if (current_key == '-2') { /* Dots.*/
              current_key = jQuery(".bwg_slideshow_dots_active_<?php echo $bwg; ?>").attr("image_key");
            }
          }
          if (bwg_params[<?php echo $bwg; ?>]['bwg_trans_in_progress']) {
            event_stack_<?php echo $bwg; ?>.push(current_key + '-' + key);
            bwg_params[<?php echo $bwg; ?>]['event_stack'] = event_stack_<?php echo $bwg; ?>;
            return;
          }
          var direction = 'right';
          if (bwg_current_key_<?php echo $bwg; ?> > key) {
            var direction = 'left';
          }
          else if (bwg_current_key_<?php echo $bwg; ?> == key) {
            return;
          }
          jQuery(".bwg_slideshow_watermark_<?php echo $bwg; ?>").css({display: 'none'});
          jQuery(".bwg_slideshow_title_text_<?php echo $bwg; ?>").css({display: 'none'});
          jQuery(".bwg_slideshow_description_text_<?php echo $bwg; ?>").css({display: 'none'});
          /* Set active thumbnail position.*/
          bwg_current_filmstrip_pos_<?php echo $bwg; ?> = key * (jQuery(".bwg_slideshow_filmstrip_thumbnail_<?php echo $bwg; ?>").<?php echo $width_or_height; ?>() + 2 + 2 * <?php echo $theme_row->lightbox_filmstrip_thumb_border_width; ?>);
          bwg_current_key_<?php echo $bwg; ?> = key;
          bwg_params[<?php echo $bwg; ?>]['bwg_current_key'] = bwg_current_key_<?php echo $bwg; ?>;
          /* Change image id, title, description.*/
          jQuery("#bwg_slideshow_image_<?php echo $bwg; ?>").attr('image_id', data_<?php echo $bwg; ?>[key]["id"]);
          jQuery(".bwg_slideshow_title_text_<?php echo $bwg; ?>").html(jQuery('<span style="display: block;" />').html(data_<?php echo $bwg; ?>[key]["alt"]).text());
          jQuery(".bwg_slideshow_description_text_<?php echo $bwg; ?>").html(jQuery('<span style="display: block;" />').html(data_<?php echo $bwg; ?>[key]["description"]).text());
          var current_image_class = jQuery(".bwg_slideshow_image_spun_<?php echo $bwg; ?>").css("zIndex") == 2 ? ".bwg_slideshow_image_spun_<?php echo $bwg; ?>" : ".bwg_slideshow_image_second_spun_<?php echo $bwg; ?>";
          var next_image_class = current_image_class == ".bwg_slideshow_image_second_spun_<?php echo $bwg; ?>" ? ".bwg_slideshow_image_spun_<?php echo $bwg; ?>" : ".bwg_slideshow_image_second_spun_<?php echo $bwg; ?>";
          var is_embed = data_<?php echo $bwg; ?>[key]['filetype'].indexOf("EMBED_") > -1 ? true : false;
          var is_embed_instagram_post = data_<?php echo $bwg; ?>[key]['filetype'].indexOf('INSTAGRAM_POST') > -1 ? true :false;
          var is_embed_instagram_video = data_<?php echo $bwg; ?>[key]['filetype'].indexOf('INSTAGRAM_VIDEO') > -1 ? true :false;
          var cur_height = jQuery(current_image_class).height();
          var cur_width = jQuery(current_image_class).width();
          var innhtml = '<span class="bwg_slideshow_image_spun1_<?php echo $bwg; ?>" style="display:  ' + (!is_embed ? 'table' : 'block') + ' ;width: inherit; height: inherit;"><span class="bwg_slideshow_image_spun2_<?php echo $bwg; ?>" style="display: ' + (!is_embed ? 'table-cell' : 'block') + '; vertical-align: middle; text-align: center; ">';
          if (!is_embed) {
            <?php
              if ($params['thumb_click_action'] != 'do_nothing' ) {
            ?>
                innhtml += '<a' + <?php if ($params['thumb_click_action'] == 'open_lightbox') { echo '\' class="bwg_lightbox" data-image-id="\' + data_' . $bwg . '[key][\'id\'] + \'"'; }  else { echo ($params['thumb_click_action'] == 'redirect_to_url' && 'data_' . $bwg . '[key][\'redirect_url\']') ? '\' href="\'+ data_' . $bwg . '[key][\'redirect_url\']+\'" target="' . ($params['thumb_link_target'] ? '_blank' : '')  . '"' : '';} ?> '+'>';
            <?php    
              }
            ?>
              innhtml += '<img style="max-height: ' + cur_height + 'px !important; max-width: ' + cur_width + 'px !important;display:inline-block;" class="bwg_slide bwg_slideshow_image_<?php echo $bwg; ?>" id="bwg_slideshow_image_<?php echo $bwg; ?>" src="<?php echo site_url() . '/' . BWG()->upload_dir; ?>' + jQuery('<span style="display: block;" />').html(data_<?php echo $bwg; ?>[key]["image_url"]).text() + '" alt="' + data_<?php echo $bwg; ?>[key]["alt"] + '" image_id="' + data_<?php echo $bwg; ?>[key]["id"] + '" /></a>';
          }
          else { /*is_embed*/
            innhtml += '<span style="height: ' + cur_height + 'px; width: ' + cur_width + 'px;" class="bwg_popup_embed bwg_popup_watermark">';
            if (is_embed_instagram_video ) {
              innhtml += '<span class="bwg_inst_play_btn_cont" onclick="bwg_play_instagram_video(this)"><span class="bwg_inst_play"></span></span>';
            }
            if (is_embed_instagram_post) {
              var post_width = 0;
              var post_height = 0;
              if (cur_height < cur_width + 88) {
                post_height = cur_height;
                post_width = post_height - 88;
              }
              else {
                post_width = cur_width;
                post_height = post_width + 88;
              }
              innhtml += spider_display_embed(data_<?php echo $bwg; ?>[key]['filetype'], data_<?php echo $bwg; ?>[key]['image_url'], data_<?php echo $bwg; ?>[key]['filename'], {class:"bwg_embed_frame", 'data-width': data_<?php echo $bwg; ?>[key]['image_width'], 'data-height': data_<?php echo $bwg; ?>[key]['image_height'], frameborder: "0", allowfullscreen: "allowfullscreen", style: "width:" + post_width + "px; height:" + post_height + "px; vertical-align:middle; display:inline-block; position:relative;"});
            }
            else {
              innhtml += spider_display_embed(data_<?php echo $bwg; ?>[key]['filetype'], data_<?php echo $bwg; ?>[key]['image_url'], data_<?php echo $bwg; ?>[key]['filename'], {class:"bwg_embed_frame", frameborder:"0", allowfullscreen:"allowfullscreen", style:"width:inherit; height:inherit; vertical-align:middle; display:table-cell;" });
            }
            innhtml += "</span>";
          }
          innhtml += '</span></span>';
          jQuery(next_image_class).html(innhtml);
          <?php
          if (BWG()->options->preload_images) {
            echo "bwg_preload_images_" .  $bwg . "(key);";
          }
          ?>
          bwg_<?php echo $slideshow_effect; ?>(current_image_class, next_image_class, direction, <?php echo $bwg; ?>);
          <?php
          if ($enable_slideshow_filmstrip) {
            ?>
            bwg_move_filmstrip_<?php echo $bwg; ?>();
            <?php
          }
          else {            
            ?>
            bwg_move_dots_<?php echo $bwg; ?>();
            <?php
          }
          ?>
          if (data_<?php echo $bwg; ?>[key]["is_embed_video"]) {
            jQuery("#bwg_slideshow_play_pause_<?php echo $bwg; ?>").css({display: 'none'});
          }
          else {
            jQuery("#bwg_slideshow_play_pause_<?php echo $bwg; ?>").css({display: ''});            
          }
        }
        bwg_document_ready();
      }
      function bwg_preload_images_<?php echo $bwg; ?>(key) {
        count = <?php echo (int) BWG()->options->preload_images_count / 2; ?>;
        var count_all = data_<?php echo $bwg; ?>.length;
        if (count_all < <?php echo BWG()->options->preload_images_count; ?>) {
          count = 0;
        }
        if (count != 0) {
          for (var i = key - count; i < key + count; i++) {
            var index = parseInt((i + count_all) % count_all);
            var is_embed = data_<?php echo $bwg; ?>[index]['filetype'].indexOf("EMBED_") > -1 ? true : false;
            if (typeof data_<?php echo $bwg; ?>[index] != "undefined") {
              if (!is_embed) {
                jQuery("<img/>").attr("src", '<?php echo site_url() . '/' . BWG()->upload_dir; ?>' + jQuery('<span style="display: block;" />').html(data_<?php echo $bwg; ?>[index]["image_url"]).text());
              }
            }
          }
        }
        else {
          for (var i = 0; i < data_<?php echo $bwg; ?>.length; i++) {
            var is_embed = data_<?php echo $bwg; ?>[i]['filetype'].indexOf("EMBED_") > -1 ? true : false;
            if (typeof data_<?php echo $bwg; ?>[i] != "undefined") {
              if (!is_embed) {
                jQuery("<img/>").attr("src", '<?php echo site_url() . '/' . BWG()->upload_dir; ?>' + jQuery('<span style="display: block;" />').html(data_<?php echo $bwg; ?>[i]["image_url"]).text());
              }
            }
          }
        }
      }
      function bwg_popup_resize_<?php echo $bwg; ?>() {
        var parent_width = jQuery(".bwg_slideshow_image_wrap_<?php echo $bwg; ?>").parent().width();
        if (parent_width >= <?php echo $image_width; ?>) {
          jQuery(".bwg_slideshow_image_wrap_<?php echo $bwg; ?>").css({width: <?php echo $image_width; ?>});
          jQuery(".bwg_slideshow_image_wrap_<?php echo $bwg; ?>").css({height: <?php echo $image_height; ?>});
          jQuery(".bwg_slideshow_image_container_<?php echo $bwg; ?>").css({width: <?php echo ($filmstrip_direction == 'horizontal' ? $image_width : $image_width - $slideshow_filmstrip_width); ?>});
          jQuery(".bwg_slideshow_image_container_<?php echo $bwg; ?>").css({height: (<?php echo ($filmstrip_direction == 'horizontal' ? $image_height - $slideshow_filmstrip_height : $image_height); ?>)});
          jQuery(".bwg_slideshow_image_<?php echo $bwg; ?>").css({
            cssText: "max-width: <?php echo ($filmstrip_direction == 'horizontal' ? $image_width : $image_width - $slideshow_filmstrip_width); ?>px !important; max-height: <?php echo ($filmstrip_direction == 'horizontal' ? $image_height - $slideshow_filmstrip_height : $image_height); ?>px !important;"
          });
          jQuery(".bwg_slideshow_embed_<?php echo $bwg; ?>").css({
            cssText: "width: <?php echo ($filmstrip_direction == 'horizontal' ? $image_width : $image_width - $slideshow_filmstrip_width); ?>px !important; height: <?php echo ($filmstrip_direction == 'horizontal' ? $image_height - $slideshow_filmstrip_height : $image_height); ?>px !important;"
          });
          bwg_resize_instagram_post_<?php echo $bwg?>();
          /* Set watermark container size.*/
          bwg_change_watermark_container_<?php echo $bwg; ?>();
          jQuery(".bwg_slideshow_filmstrip_container_<?php echo $bwg; ?>").css({<?php echo ($filmstrip_direction == 'horizontal' ? 'width: ' . $image_width : 'height: ' . $image_height); ?>});
          jQuery(".bwg_slideshow_filmstrip_<?php echo $bwg; ?>").css({<?php echo ($filmstrip_direction == 'horizontal' ? 'width: ' . ($image_width - 40) : 'height: ' . ($image_height - 40)); ?>});
          jQuery(".bwg_slideshow_dots_container_<?php echo $bwg; ?>").css({width: <?php echo $image_width; ?>});
          jQuery("#bwg_slideshow_play_pause-ico_<?php echo $bwg; ?>").css({fontSize: (<?php echo $theme_row->slideshow_play_pause_btn_size; ?>)});
          jQuery(".bwg_slideshow_watermark_image_<?php echo $bwg; ?>").css({maxWidth: <?php echo $watermark_width; ?>, maxHeight: <?php echo $watermark_height; ?>});
          jQuery(".bwg_slideshow_watermark_text_<?php echo $bwg; ?>, .bwg_slideshow_watermark_text_<?php echo $bwg; ?>:hover").css({fontSize: (<?php echo $watermark_font_size; ?>)});
          jQuery(".bwg_slideshow_title_text_<?php echo $bwg; ?>").css({fontSize: (<?php echo $theme_row->slideshow_title_font_size * 2; ?>)});
          jQuery(".bwg_slideshow_description_text_<?php echo $bwg; ?>").css({fontSize: (<?php echo $theme_row->slideshow_description_font_size * 2; ?>)});
        }
        else {
          jQuery(".bwg_slideshow_image_wrap_<?php echo $bwg; ?>").css({width: (parent_width)});
          jQuery(".bwg_slideshow_image_wrap_<?php echo $bwg; ?>").css({height: ((parent_width) * <?php echo $image_height / $image_width ?>)});
          jQuery(".bwg_slideshow_image_container_<?php echo $bwg; ?>").css({width: (parent_width - <?php echo ($filmstrip_direction == 'horizontal' ? 0 : $slideshow_filmstrip_width); ?>)});
          jQuery(".bwg_slideshow_image_container_<?php echo $bwg; ?>").css({height: ((parent_width) * <?php echo $image_height / $image_width ?> - <?php echo ($filmstrip_direction == 'horizontal' ? $slideshow_filmstrip_height : 0); ?>)});
          jQuery(".bwg_slideshow_image_<?php echo $bwg; ?>").css({
            cssText: "max-width: " + (parent_width - <?php echo ($filmstrip_direction == 'horizontal' ? 0 : $slideshow_filmstrip_width) ?>) + "px !important; max-height: " + (parent_width * (<?php echo $image_height / $image_width ?>) - <?php echo ($filmstrip_direction == 'horizontal' ? $slideshow_filmstrip_height : 0); ?> - 1) + "px !important;"
          });
          jQuery(".bwg_slideshow_embed_<?php echo $bwg; ?>").css({
            cssText: "width: " + (parent_width - <?php echo ($filmstrip_direction == 'horizontal' ? 0 : $slideshow_filmstrip_width) ?>) + "px !important; height: " + (parent_width * (<?php echo $image_height / $image_width ?>) - <?php echo ($filmstrip_direction == 'horizontal' ? $slideshow_filmstrip_height : 0); ?> - 1) + "px !important;"
          });
          bwg_resize_instagram_post_<?php echo $bwg?>();
          /* Set watermark container size.*/
          bwg_change_watermark_container_<?php echo $bwg; ?>();
          <?php if ($filmstrip_direction == 'horizontal') { ?>
          jQuery(".bwg_slideshow_filmstrip_container_<?php echo $bwg; ?>").css({width: (parent_width)});
          jQuery(".bwg_slideshow_filmstrip_<?php echo $bwg; ?>").css({width: (parent_width - 40)});
          <?php }
          else {
          ?>
          jQuery(".bwg_slideshow_filmstrip_container_<?php echo $bwg; ?>").css({height: (parent_width * <?php echo $image_height / $image_width ?>)});          
          jQuery(".bwg_slideshow_filmstrip_<?php echo $bwg; ?>").css({height: (parent_width * <?php echo $image_height / $image_width ?> - 40)});
          <?php
          }
          ?>
          jQuery(".bwg_slideshow_dots_container_<?php echo $bwg; ?>").css({width: (parent_width)});
          jQuery("#bwg_slideshow_play_pause-ico_<?php echo $bwg; ?>").css({fontSize: ((parent_width) * <?php echo $theme_row->slideshow_play_pause_btn_size / $image_width; ?>)});
          jQuery(".bwg_slideshow_watermark_image_<?php echo $bwg; ?>").css({maxWidth: ((parent_width) * <?php echo $watermark_width / $image_width; ?>), maxHeight: ((parent_width) * <?php echo $watermark_height / $image_width; ?>)});
          jQuery(".bwg_slideshow_watermark_text_<?php echo $bwg; ?>, .bwg_slideshow_watermark_text_<?php echo $bwg; ?>:hover").css({fontSize: ((parent_width) * <?php echo $watermark_font_size / $image_width; ?>)});
          jQuery(".bwg_slideshow_title_text_<?php echo $bwg; ?>").css({fontSize: ((parent_width) * <?php echo 2 * $theme_row->slideshow_title_font_size / $image_width; ?>)});
          jQuery(".bwg_slideshow_description_text_<?php echo $bwg; ?>").css({fontSize: ((parent_width) * <?php echo 2 * $theme_row->slideshow_description_font_size / $image_width; ?>)});
          jQuery(".bwg_slideshow_image_<?php echo $bwg; ?>").css({'display':'inline-block'});
        }
        if (data_<?php echo $bwg; ?>[parseInt(jQuery("#bwg_current_image_key_<?php echo $bwg; ?>").val())]["is_embed_video"]) {
          jQuery("#bwg_slideshow_play_pause_<?php echo $bwg; ?>").css({display: 'none'});
        }
        else {
          jQuery("#bwg_slideshow_play_pause_<?php echo $bwg; ?>").css({display: ''});            
        }
      }
      jQuery(window).resize(function() {
        bwg_popup_resize_<?php echo $bwg; ?>();
      });
      jQuery(document).ready(function () {
        bwg_container_loaded('<?php echo $bwg; ?>');
      	<?php
        if ( BWG()->options->image_right_click ) {
          ?>
          /* Disable right click.*/
          jQuery('div[id^="bwg_container"]').bind("contextmenu", function () {
            return false;
          });
          jQuery('div[id^="bwg_container"]').css('webkitTouchCallout','none');
          <?php
        }
        ?>
        if (typeof jQuery().swiperight !== 'undefined') {
          if (jQuery.isFunction(jQuery().swiperight)) {
            jQuery('#bwg_container1_<?php echo $bwg; ?>').swiperight(function () {
              bwg_change_image_<?php echo $bwg; ?>(parseInt(jQuery('#bwg_current_image_key_<?php echo $bwg; ?>').val()), (parseInt(jQuery('#bwg_current_image_key_<?php echo $bwg; ?>').val()) - bwg_iterator_<?php echo $bwg; ?>()) >= 0 ? (parseInt(jQuery('#bwg_current_image_key_<?php echo $bwg; ?>').val()) - bwg_iterator_<?php echo $bwg; ?>()) % data_<?php echo $bwg; ?>.length : data_<?php echo $bwg; ?>.length - 1, data_<?php echo $bwg; ?>);
              return false;
            });
          }
        }
        if (typeof jQuery().swipeleft !== 'undefined') {
          if (jQuery.isFunction(jQuery().swipeleft)) {
            jQuery('#bwg_container1_<?php echo $bwg; ?>').swipeleft(function () {
              bwg_change_image_<?php echo $bwg; ?>(parseInt(jQuery('#bwg_current_image_key_<?php echo $bwg; ?>').val()), (parseInt(jQuery('#bwg_current_image_key_<?php echo $bwg; ?>').val()) + bwg_iterator_<?php echo $bwg; ?>()) % data_<?php echo $bwg; ?>.length, data_<?php echo $bwg; ?>);
              return false;
            });
          }
        }
        var isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
        var bwg_click = isMobile ? 'touchend' : 'click';
        bwg_popup_resize_<?php echo $bwg; ?>();
        jQuery(".bwg_slideshow_watermark_<?php echo $bwg; ?>").css({display: 'none'});
        jQuery(".bwg_slideshow_title_text_<?php echo $bwg; ?>").css({display: 'none'});
        jQuery(".bwg_slideshow_description_text_<?php echo $bwg; ?>").css({display: 'none'});
        setTimeout(function () {
          bwg_change_watermark_container_<?php echo $bwg; ?>();
        }, 500);
        /* Set image container height.*/
        <?php if ($filmstrip_direction == 'horizontal') { ?>
        jQuery(".bwg_slideshow_image_container_<?php echo $bwg; ?>").height(jQuery(".bwg_slideshow_image_wrap_<?php echo $bwg; ?>").height() - <?php echo $slideshow_filmstrip_height; ?>);
          <?php }
        else {
          ?>
          jQuery(".bwg_slideshow_image_container_<?php echo $bwg; ?>").width(jQuery(".bwg_slideshow_image_wrap_<?php echo $bwg; ?>").width() - <?php echo $slideshow_filmstrip_width; ?>);
          <?php
        } ?>
        var mousewheelevt = (/Firefox/i.test(navigator.userAgent)) ? "DOMMouseScroll" : "mousewheel"; /* FF doesn't recognize mousewheel as of FF3.x */
        jQuery('.bwg_slideshow_filmstrip_<?php echo $bwg; ?>').bind(mousewheelevt, function(e) {
          var evt = window.event || e; /* Equalize event object.*/
          evt = evt.originalEvent ? evt.originalEvent : evt; /* Convert to originalEvent if possible.*/
          var delta = evt.detail ? evt.detail*(-40) : evt.wheelDelta; /* Check for detail first, because it is used by Opera and FF.*/
          if (delta > 0) {
            /* Scroll up.*/
            jQuery(".bwg_slideshow_filmstrip_left_<?php echo $bwg; ?>").trigger("click");
          }
          else {
            /* Scroll down.*/
            jQuery(".bwg_slideshow_filmstrip_right_<?php echo $bwg; ?>").trigger("click");
          }
          return false;
        });
        jQuery(".bwg_slideshow_filmstrip_right_<?php echo $bwg; ?>").on(bwg_click, function () {
          jQuery( ".bwg_slideshow_filmstrip_thumbnails_<?php echo $bwg; ?>" ).stop(true, false);
          if (jQuery(".bwg_slideshow_filmstrip_thumbnails_<?php echo $bwg; ?>").position().<?php echo $left_or_top; ?> >= -(jQuery(".bwg_slideshow_filmstrip_thumbnails_<?php echo $bwg; ?>").<?php echo $width_or_height; ?>() - jQuery(".bwg_slideshow_filmstrip_<?php echo $bwg; ?>").<?php echo $width_or_height; ?>())) {
            jQuery(".bwg_slideshow_filmstrip_left_<?php echo $bwg; ?>").css({opacity: 1, filter: "Alpha(opacity=100)"});
            if (jQuery(".bwg_slideshow_filmstrip_thumbnails_<?php echo $bwg; ?>").position().<?php echo $left_or_top; ?> < -(jQuery(".bwg_slideshow_filmstrip_thumbnails_<?php echo $bwg; ?>").<?php echo $width_or_height; ?>() - jQuery(".bwg_slideshow_filmstrip_<?php echo $bwg; ?>").<?php echo $width_or_height; ?>() - <?php echo $filmstrip_thumb_margin_hor + $slideshow_filmstrip_width; ?>)) {
              jQuery(".bwg_slideshow_filmstrip_thumbnails_<?php echo $bwg; ?>").animate({<?php echo $left_or_top; ?>: -(jQuery(".bwg_slideshow_filmstrip_thumbnails_<?php echo $bwg; ?>").<?php echo $width_or_height; ?>() - jQuery(".bwg_slideshow_filmstrip_<?php echo $bwg; ?>").<?php echo $width_or_height; ?>())}, 500, 'linear');
            }
            else {
              jQuery(".bwg_slideshow_filmstrip_thumbnails_<?php echo $bwg; ?>").animate({<?php echo $left_or_top; ?>: (jQuery(".bwg_slideshow_filmstrip_thumbnails_<?php echo $bwg; ?>").position().<?php echo $left_or_top; ?> - <?php echo $filmstrip_thumb_margin_hor + $slideshow_filmstrip_width; ?>)}, 500, 'linear');
            }
          }
          /* Disable right arrow.*/
          window.setTimeout(function(){
            if (jQuery(".bwg_slideshow_filmstrip_thumbnails_<?php echo $bwg; ?>").position().<?php echo $left_or_top; ?> == -(jQuery(".bwg_slideshow_filmstrip_thumbnails_<?php echo $bwg; ?>").<?php echo $width_or_height; ?>() - jQuery(".bwg_slideshow_filmstrip_<?php echo $bwg; ?>").<?php echo $width_or_height; ?>())) {
              jQuery(".bwg_slideshow_filmstrip_right_<?php echo $bwg; ?>").css({opacity: 0.3, filter: "Alpha(opacity=30)"});
            }
          }, 500);
        });
        jQuery(".bwg_slideshow_filmstrip_left_<?php echo $bwg; ?>").on(bwg_click, function () {
          jQuery( ".bwg_slideshow_filmstrip_thumbnails_<?php echo $bwg; ?>" ).stop(true, false);
          if (jQuery(".bwg_slideshow_filmstrip_thumbnails_<?php echo $bwg; ?>").position().<?php echo $left_or_top; ?> < 0) {
            jQuery(".bwg_slideshow_filmstrip_right_<?php echo $bwg; ?>").css({opacity: 1, filter: "Alpha(opacity=100)"});
            if (jQuery(".bwg_slideshow_filmstrip_thumbnails_<?php echo $bwg; ?>").position().<?php echo $left_or_top; ?> > - <?php echo $filmstrip_thumb_margin_hor + $slideshow_filmstrip_width; ?>) {
              jQuery(".bwg_slideshow_filmstrip_thumbnails_<?php echo $bwg; ?>").animate({<?php echo $left_or_top; ?>: 0}, 500, 'linear');
            }
            else {
              jQuery(".bwg_slideshow_filmstrip_thumbnails_<?php echo $bwg; ?>").animate({<?php echo $left_or_top; ?>: (jQuery(".bwg_slideshow_filmstrip_thumbnails_<?php echo $bwg; ?>").position().<?php echo $left_or_top; ?> + <?php echo $filmstrip_thumb_margin_hor + $slideshow_filmstrip_width; ?>)}, 500, 'linear');
            }
          }
          /* Disable left arrow.*/
          window.setTimeout(function(){
            if (jQuery(".bwg_slideshow_filmstrip_thumbnails_<?php echo $bwg; ?>").position().<?php echo $left_or_top; ?> == 0) {
              jQuery(".bwg_slideshow_filmstrip_left_<?php echo $bwg; ?>").css({opacity: 0.3, filter: "Alpha(opacity=30)"});
            }
          }, 500);
        });
        /* Set filmstrip initial position.*/
        bwg_set_filmstrip_pos_<?php echo $bwg; ?>(jQuery(".bwg_slideshow_filmstrip_<?php echo $bwg; ?>").<?php echo $width_or_height; ?>());
        /* Play/pause.*/
        jQuery("#bwg_slideshow_play_pause_<?php echo $bwg; ?>").on(bwg_click, function () {
          if (jQuery(".bwg_ctrl_btn_<?php echo $bwg; ?>").hasClass("fa-play")) {
            bwg_play_<?php echo $bwg; ?>();
            jQuery(".bwg_slideshow_play_pause_<?php echo $bwg; ?>").attr("title", "<?php echo __('Pause', BWG()->prefix); ?>");
            jQuery(".bwg_slideshow_play_pause_<?php echo $bwg; ?>").attr("class", "bwg_ctrl_btn_<?php echo $bwg; ?> bwg_slideshow_play_pause_<?php echo $bwg; ?> fa fa-pause");
            if (<?php echo $enable_slideshow_music ?>) {
              document.getElementById("bwg_audio_<?php echo $bwg; ?>").play();
            }
          }
          else {
            /* Pause.*/
            window.clearInterval(bwg_playInterval_<?php echo $bwg; ?>);
            jQuery(".bwg_slideshow_play_pause_<?php echo $bwg; ?>").attr("title", "<?php echo __('Play', BWG()->prefix); ?>");
            jQuery(".bwg_slideshow_play_pause_<?php echo $bwg; ?>").attr("class", "bwg_ctrl_btn_<?php echo $bwg; ?> bwg_slideshow_play_pause_<?php echo $bwg; ?> fa fa-play");
            if (<?php echo $enable_slideshow_music ?>) {
              document.getElementById("bwg_audio_<?php echo $bwg; ?>").pause();
            }
          }
        });
        if (<?php echo $enable_slideshow_autoplay; ?>) {
          bwg_play_<?php echo $bwg; ?>();
          jQuery(".bwg_slideshow_play_pause_<?php echo $bwg; ?>").attr("title", "<?php echo __('Pause', BWG()->prefix); ?>");
          jQuery(".bwg_slideshow_play_pause_<?php echo $bwg; ?>").attr("class", "bwg_ctrl_btn_<?php echo $bwg; ?> bwg_slideshow_play_pause_<?php echo $bwg; ?> fa fa-pause");
          if (<?php echo $enable_slideshow_music ?>) {
            document.getElementById("bwg_audio_<?php echo $bwg; ?>").play();
          }
        }
        <?php
        if (BWG()->options->preload_images) {
          echo "bwg_preload_images_" .  $bwg . "(parseInt(jQuery('#bwg_current_image_key_" . $bwg . "').val()));";
        }
        ?>
        jQuery(".bwg_slideshow_image_<?php echo $bwg; ?>").removeAttr("width");
        jQuery(".bwg_slideshow_image_<?php echo $bwg; ?>").removeAttr("height");
      });
      function bwg_resize_instagram_post_<?php echo $bwg?>() {
        if (jQuery('.inner_instagram_iframe_bwg_embed_frame_<?php echo $bwg?>').length) {
          var post_width = jQuery('.bwg_slideshow_embed_<?php echo $bwg?>').width();
          var post_height = jQuery('.bwg_slideshow_embed_<?php echo $bwg?>').height();
          jQuery('.inner_instagram_iframe_bwg_embed_frame_<?php echo $bwg?>').each(function() {
            var parent_container = jQuery(this).parent();
            if (post_height / (parseInt(parent_container.attr('data-height')) + 96) < post_width / parseInt(parent_container.attr('data-width'))) {
              parent_container.height(post_height);
              parent_container.width((parent_container.height() - 96) * parent_container.attr('data-width') / parent_container.attr('data-height') + 16);
            }
            else {
              parent_container.width(post_width);
              parent_container.height((parent_container.width() - 16) * parent_container.attr('data-height') / parent_container.attr('data-width') + 96);
            }
          });
        }
        bwg_change_watermark_container_<?php echo $bwg; ?>();
      }
      function bwg_play_<?php echo $bwg; ?>() {
        window.clearInterval(bwg_playInterval_<?php echo $bwg; ?>);
        /* Play.*/
        bwg_playInterval_<?php echo $bwg; ?> = setInterval(function () {
          var iterator = 1;
          if (<?php echo $enable_slideshow_shuffle; ?>) {
            iterator = Math.floor((data_<?php echo $bwg; ?>.length - 1) * Math.random() + 1);
          }
          bwg_change_image_<?php echo $bwg; ?>(parseInt(jQuery('#bwg_current_image_key_<?php echo $bwg; ?>').val()), (parseInt(jQuery('#bwg_current_image_key_<?php echo $bwg; ?>').val()) + iterator) % data_<?php echo $bwg; ?>.length, data_<?php echo $bwg; ?>)
        }, '<?php echo $params['slideshow_interval'] * 1000; ?>');
      }
      jQuery(window).focus(function() {
        if (!jQuery(".bwg_ctrl_btn_<?php echo $bwg; ?>").hasClass("fa-play")) {
          bwg_play_<?php echo $bwg; ?>();
        }
        var i_<?php echo $bwg; ?> = 0;
        jQuery(".bwg_slider_<?php echo $bwg; ?>").children("span").each(function () {
          if (jQuery(this).css('opacity') == 1) {
            jQuery("#bwg_current_image_key_<?php echo $bwg; ?>").val(i_<?php echo $bwg; ?>);
          }
          i_<?php echo $bwg; ?>++;
        });
      });
      jQuery(window).blur(function() {
        event_stack_<?php echo $bwg; ?> = [];
        window.clearInterval(bwg_playInterval_<?php echo $bwg; ?>);
      });
    </script>
    <?php
    if ($from_shortcode) {
      return;
    }
    else {
      die();
    }
  }

  private function inline_styles($bwg, $theme_row, $params, $image_width, $image_height, $filmstrip_direction, $slideshow_filmstrip_height, $options, $left_or_top, $width_or_height, $filmstrip_thumb_margin_hor, $slideshow_filmstrip_width, $image_rows, $watermark_position, $slideshow_title_position, $slideshow_description_position, $watermark_height, $watermark_width, $watermark_opacity, $watermark_font_size, $watermark_font, $watermark_color, $enable_slideshow_filmstrip) {
    ob_start();
    ?>
    #bwg_container1_<?php echo $bwg; ?> {
      /*visibility: hidden;*/
    }
    #bwg_container1_<?php echo $bwg; ?> * {
      -moz-user-select: none;
      -khtml-user-select: none;
      -webkit-user-select: none;
      -ms-user-select: none;
      user-select: none;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_image_wrap_<?php echo $bwg; ?> * {
      box-sizing: border-box;
      -moz-box-sizing: border-box;
      -webkit-box-sizing: border-box;
      /*backface-visibility: hidden;
      -webkit-backface-visibility: hidden;
      -moz-backface-visibility: hidden;
      -ms-backface-visibility: hidden;*/
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_image_wrap_<?php echo $bwg; ?> {
      background-color: #<?php echo $theme_row->slideshow_cont_bg_color; ?>;
      border-collapse: collapse;
      display: table;
      position: relative;
      text-align: center;
      width: <?php echo $image_width; ?>px;
      height: <?php echo $image_height; ?>px;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_image_<?php echo $bwg; ?> {
      padding: 0 !important;
      margin: 0 !important;
      float: none !important;
      max-width: <?php echo $image_width - ($filmstrip_direction == 'vertical' ? $slideshow_filmstrip_width : 0); ?>px;
      max-height: <?php echo $image_height - ($filmstrip_direction == 'horizontal' ? $slideshow_filmstrip_height : 0); ?>px;
      vertical-align: middle;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_embed_<?php echo $bwg; ?> {
      padding: 0 !important;
      margin: 0 !important;
      float: none !important;
      width: <?php echo $image_width - ($filmstrip_direction == 'vertical' ? $slideshow_filmstrip_width : 0); ?>px;
      height: <?php echo $image_height - ($filmstrip_direction == 'horizontal' ? $slideshow_filmstrip_height : 0); ?>px;
      vertical-align: middle;
      display: inline-block;
      text-align: center;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_watermark_<?php echo $bwg; ?> {
      position: relative;
      z-index: 15;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #bwg_slideshow_play_pause_<?php echo $bwg; ?> {
      background: transparent url("<?php echo BWG()->plugin_url . '/images/blank.gif'; ?>") repeat scroll 0 0;
      bottom: 0;
      cursor: pointer;
      display: table;
      height: inherit;
      outline: medium none;
      position: absolute;
      width: 30%;
      left: 35%;
      z-index: 13;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #bwg_slideshow_play_pause_<?php echo $bwg; ?>:hover #bwg_slideshow_play_pause-ico_<?php echo $bwg; ?> {
      display: inline-block !important;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #bwg_slideshow_play_pause_<?php echo $bwg; ?>:hover span {
      position: relative;
      z-index: 13;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #bwg_slideshow_play_pause_<?php echo $bwg; ?> span {
      display: table-cell;
      text-align: center;
      vertical-align: middle;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #bwg_slideshow_play_pause-ico_<?php echo $bwg; ?> {  
      display: none !important;
      color: #<?php echo $theme_row->slideshow_rl_btn_color; ?>;        
      font-size: <?php echo $theme_row->slideshow_play_pause_btn_size; ?>px;
      cursor: pointer;
      position: relative;
      z-index: 13;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #bwg_slideshow_play_pause-ico_<?php echo $bwg; ?>:hover {  
      color: #<?php echo $theme_row->slideshow_close_rl_btn_hover_color; ?>;
      display: inline-block;
      position: relative;
      z-index: 13;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #spider_slideshow_left_<?php echo $bwg; ?>,
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #spider_slideshow_right_<?php echo $bwg; ?> {
      background: transparent url("<?php echo BWG()->plugin_url . '/images/blank.gif'; ?>") repeat scroll 0 0;
      bottom: 35%;
      cursor: pointer;
      display: inline;
      height: 30%;
      outline: medium none;
      position: absolute;
      width: 35%;
      /*z-index: 10130;*/
      z-index: 13;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #spider_slideshow_left_<?php echo $bwg; ?> {
      left: 0;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #spider_slideshow_right_<?php echo $bwg; ?> {
      right: 0;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #spider_slideshow_left_<?php echo $bwg; ?>:hover,
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #spider_slideshow_right_<?php echo $bwg; ?>:hover {
      visibility: visible;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #spider_slideshow_left_<?php echo $bwg; ?>:hover span {
      left: 20px;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #spider_slideshow_right_<?php echo $bwg; ?>:hover span {
      left: auto;
      right: 20px;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #spider_slideshow_left-ico_<?php echo $bwg; ?> span,
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #spider_slideshow_right-ico_<?php echo $bwg; ?> span {
      display: table-cell;
      text-align: center;
      vertical-align: middle;
      z-index: 13;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #spider_slideshow_left-ico_<?php echo $bwg; ?>,
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #spider_slideshow_right-ico_<?php echo $bwg; ?> {
      background-color: #<?php echo $theme_row->slideshow_rl_btn_bg_color; ?>;
      border-radius: <?php echo $theme_row->slideshow_rl_btn_border_radius; ?>;
      border: <?php echo $theme_row->slideshow_rl_btn_border_width; ?>px <?php echo $theme_row->slideshow_rl_btn_border_style; ?> #<?php echo $theme_row->slideshow_rl_btn_border_color; ?>;
      box-shadow: <?php echo $theme_row->slideshow_rl_btn_box_shadow; ?>;
      color: #<?php echo $theme_row->slideshow_rl_btn_color; ?>;
      height: <?php echo $theme_row->slideshow_rl_btn_height; ?>px;
      font-size: <?php echo $theme_row->slideshow_rl_btn_size; ?>px;
      width: <?php echo $theme_row->slideshow_rl_btn_width; ?>px;
      z-index: 13;
      -moz-box-sizing: content-box;
      box-sizing: content-box;
      cursor: pointer;
      display: table;
      line-height: 0;
      margin-top: -15px;
      position: absolute;
      top: 50%;
      /*z-index: 10135;*/
      opacity: <?php echo number_format($theme_row->slideshow_close_btn_transparent / 100, 2, ".", ""); ?>;
      filter: Alpha(opacity=<?php echo $theme_row->slideshow_close_btn_transparent; ?>);
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #spider_slideshow_left-ico_<?php echo $bwg; ?>:hover,
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #spider_slideshow_right-ico_<?php echo $bwg; ?>:hover {
      color: #<?php echo $theme_row->slideshow_close_rl_btn_hover_color; ?>;
      cursor: pointer;
    }
    <?php
  if($options->autohide_slideshow_navigation){?>
  #spider_slideshow_left-ico_<?php echo $bwg; ?>{
     left: -9999px;
  }
#spider_slideshow_right-ico_<?php echo $bwg; ?>{
  left: -9999px;
 }
  
<?php }
     else{ ?>
    #spider_slideshow_left-ico_<?php echo $bwg; ?>{
     left: 20px;
  }
   #spider_slideshow_right-ico_<?php echo $bwg; ?>{
     left: auto;
         right: 20px;
  }
  <?php } ?>
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_image_container_<?php echo $bwg; ?> {
      display: table;
      position: absolute;
      text-align: center;
      <?php echo $theme_row->slideshow_filmstrip_pos; ?>: <?php echo ($filmstrip_direction == 'horizontal' ? $slideshow_filmstrip_height : $slideshow_filmstrip_width); ?>px;
      vertical-align: middle;
      width: <?php echo $image_width; ?>px;
      height: <?php echo $image_height; ?>px;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_filmstrip_container_<?php echo $bwg; ?> {
      display: <?php echo ($filmstrip_direction == 'horizontal'? 'table' : 'block'); ?>;
      height: <?php echo ($filmstrip_direction == 'horizontal'? $slideshow_filmstrip_height : $image_height); ?>px;
      position: absolute;
      width: <?php echo ($filmstrip_direction == 'horizontal' ? $image_width : $slideshow_filmstrip_width); ?>px;
      /*z-index: 10105;*/
      <?php echo $theme_row->slideshow_filmstrip_pos; ?>: 0;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_filmstrip_<?php echo $bwg; ?> {
      <?php echo $left_or_top; ?>: 20px;
      overflow: hidden;
      position: absolute;
      <?php echo $width_or_height; ?>: <?php echo ($filmstrip_direction == 'horizontal' ? $image_width - 40 : $image_height - 40); ?>px;
      /*z-index: 10106;*/
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_filmstrip_thumbnails_<?php echo $bwg; ?> {
      height: <?php echo ($filmstrip_direction == 'horizontal' ? $slideshow_filmstrip_height : ($slideshow_filmstrip_height + $filmstrip_thumb_margin_hor) * count($image_rows)); ?>px;
      <?php echo $left_or_top; ?>: 0px;
      margin: 0 auto;
      overflow: hidden;
      position: relative;
      width: <?php echo ($filmstrip_direction == 'horizontal' ? ($slideshow_filmstrip_width + $filmstrip_thumb_margin_hor) * count($image_rows) : $slideshow_filmstrip_width); ?>px;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_filmstrip_thumbnail_<?php echo $bwg; ?> {
      position: relative;
      background: none;
      border: <?php echo $theme_row->slideshow_filmstrip_thumb_border_width; ?>px <?php echo $theme_row->slideshow_filmstrip_thumb_border_style; ?> #<?php echo $theme_row->slideshow_filmstrip_thumb_border_color; ?>;
      border-radius: <?php echo $theme_row->slideshow_filmstrip_thumb_border_radius; ?>;
      cursor: pointer;
      float: left;
      height: <?php echo $slideshow_filmstrip_height; ?>px;
      margin: <?php echo $theme_row->slideshow_filmstrip_thumb_margin; ?>;
      width: <?php echo $slideshow_filmstrip_width; ?>px;
      overflow: hidden;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_thumb_active_<?php echo $bwg; ?> {
      opacity: 1;
      filter: Alpha(opacity=100);
      border: <?php echo $theme_row->slideshow_filmstrip_thumb_active_border_width; ?>px solid #<?php echo $theme_row->slideshow_filmstrip_thumb_active_border_color; ?>;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_thumb_deactive_<?php echo $bwg; ?> {
      opacity: <?php echo number_format($theme_row->slideshow_filmstrip_thumb_deactive_transparent / 100, 2, ".", ""); ?>;
      filter: Alpha(opacity=<?php echo $theme_row->slideshow_filmstrip_thumb_deactive_transparent; ?>);
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_filmstrip_thumbnail_img_<?php echo $bwg; ?> {
      display: block;
      opacity: 1;
      filter: Alpha(opacity=100);
      padding: 0 !important;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_filmstrip_left_<?php echo $bwg; ?> {
      background-color: #<?php echo $theme_row->slideshow_filmstrip_rl_bg_color; ?>;
      cursor: pointer;
      display: <?php echo ($filmstrip_direction == 'horizontal' ? 'table-cell' : 'block') ?>;
      vertical-align: middle;
      <?php echo $width_or_height; ?>: 20px;
      /*z-index: 10106;*/
      <?php echo $left_or_top; ?>: 0;
      <?php echo ($filmstrip_direction == 'horizontal' ? '' : 'position: absolute;') ?>
      <?php echo ($filmstrip_direction == 'horizontal' ? '' : 'width: 100%;') ?> 
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_filmstrip_right_<?php echo $bwg; ?> {
      background-color: #<?php echo $theme_row->slideshow_filmstrip_rl_bg_color; ?>;
      cursor: pointer;
      <?php echo($filmstrip_direction == 'horizontal' ? 'right' : 'bottom') ?>: 0;
      <?php echo $width_or_height; ?>: 20px;
      display: <?php echo ($filmstrip_direction == 'horizontal' ? 'table-cell' : 'block') ?>;
      vertical-align: middle;
      /*z-index: 10106;*/
      <?php echo ($filmstrip_direction == 'horizontal' ? '' : 'position: absolute;') ?>
      <?php echo ($filmstrip_direction == 'horizontal' ? '' : 'width: 100%;') ?>
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_filmstrip_left_<?php echo $bwg; ?> i,
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_filmstrip_right_<?php echo $bwg; ?> i {
      color: #<?php echo $theme_row->slideshow_filmstrip_rl_btn_color; ?>;
      font-size: <?php echo $theme_row->slideshow_filmstrip_rl_btn_size; ?>px;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_none_selectable_<?php echo $bwg; ?> {
      -webkit-touch-callout: none;
      -webkit-user-select: none;
      -khtml-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_watermark_container_<?php echo $bwg; ?> {
      display: table-cell;
      margin: 0 auto;
      position: relative;
      vertical-align: middle;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_watermark_spun_<?php echo $bwg; ?> {
      display: table-cell;
      overflow: hidden;
      position: relative;
      text-align: <?php echo $watermark_position[1]; ?>;
      vertical-align: <?php echo $watermark_position[0]; ?>;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_title_spun_<?php echo $bwg; ?> {
      display: table-cell;
      overflow: hidden;
      position: relative;
      text-align: <?php echo $slideshow_title_position[1]; ?>;
      vertical-align: <?php echo $slideshow_title_position[0]; ?>;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_description_spun_<?php echo $bwg; ?> {
      display: table-cell;
      overflow: hidden;
      position: relative;
      text-align: <?php echo $slideshow_description_position[1]; ?>;
      vertical-align: <?php echo $slideshow_description_position[0]; ?>;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_watermark_image_<?php echo $bwg; ?> {
      padding: 0 !important;
      float: none !important;
      margin: 4px !important;
      max-height: <?php echo $watermark_height; ?>px;
      max-width: <?php echo $watermark_width; ?>px;
      opacity: <?php echo number_format($watermark_opacity / 100, 2, ".", ""); ?>;
      filter: Alpha(opacity=<?php echo $watermark_opacity; ?>);
      position: relative;
      z-index: 15;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_watermark_text_<?php echo $bwg; ?>,
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_watermark_text_<?php echo $bwg; ?>:hover {
      text-decoration: none;
      margin: 4px;
      font-size: <?php echo $watermark_font_size; ?>px;
      font-family: <?php echo $watermark_font; ?>;
      color: #<?php echo $watermark_color; ?> !important;
      opacity: <?php echo number_format($watermark_opacity / 100, 2, ".", ""); ?>;
      filter: Alpha(opacity=<?php echo $watermark_opacity; ?>);
      position: relative;
      z-index: 15;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_title_text_<?php echo $bwg; ?> {
      text-decoration: none;
      font-size: <?php echo $theme_row->slideshow_title_font_size; ?>px;
      font-family: <?php echo $theme_row->slideshow_title_font; ?>;
      color: #<?php echo $theme_row->slideshow_title_color; ?> !important;
      opacity: <?php echo number_format($theme_row->slideshow_title_opacity / 100, 2, ".", ""); ?>;
      filter: Alpha(opacity=<?php echo $theme_row->slideshow_title_opacity; ?>);
      position: relative;
      z-index: 11;
      border-radius: <?php echo $theme_row->slideshow_title_border_radius; ?>;
      background-color: #<?php echo $theme_row->slideshow_title_background_color; ?>;
      padding: <?php echo $theme_row->slideshow_title_padding; ?>;
      <?php if($params['slideshow_title_full_width']) { ?>
      width: 100%;
      <?php } else { ?>
      margin: 5px;
      <?php } ?>
      display: inline-block;
      word-wrap: break-word;
      word-break: break-word;
      <?php if (!$enable_slideshow_filmstrip && $slideshow_title_position[0] == $theme_row->slideshow_filmstrip_pos) echo $theme_row->slideshow_filmstrip_pos . ':' . ($theme_row->slideshow_dots_height + 4) . 'px;'; ?>
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_description_text_<?php echo $bwg; ?> {
      text-decoration: none;
      font-size: <?php echo $theme_row->slideshow_description_font_size; ?>px;
      font-family: <?php echo $theme_row->slideshow_description_font; ?>;
      color: #<?php echo $theme_row->slideshow_description_color; ?> !important;
      opacity: <?php echo number_format($theme_row->slideshow_description_opacity / 100, 2, ".", ""); ?>;
      filter: Alpha(opacity=<?php echo $theme_row->slideshow_description_opacity; ?>);
      position: relative;
      z-index: 15;
      border-radius: <?php echo $theme_row->slideshow_description_border_radius; ?>;
      background-color: #<?php echo $theme_row->slideshow_description_background_color; ?>;
      padding: <?php echo $theme_row->slideshow_description_padding; ?>;
      margin: 5px;
      display: inline-block;
      word-wrap: break-word;
      word-break: break-word;
      <?php if (!$enable_slideshow_filmstrip && $slideshow_description_position[0] == $theme_row->slideshow_filmstrip_pos) echo $theme_row->slideshow_filmstrip_pos . ':' . ($theme_row->slideshow_dots_height + 4) . 'px;'; ?>        
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_description_text_<?php echo $bwg; ?> * {
      text-decoration: none;
      color: #<?php echo $theme_row->slideshow_description_color; ?> !important;                
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slide_container_<?php echo $bwg; ?> {
      display: table-cell;
      margin: 0 auto;
      position: absolute;
      vertical-align: middle;
      width: 100%;
      height: 100%;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slide_bg_<?php echo $bwg; ?> {
      margin: 0 auto;
      width: inherit;
      height: inherit;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slider_<?php echo $bwg; ?> {
      height: inherit;
      width: inherit;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_image_spun_<?php echo $bwg; ?> {
      width: inherit;
      height: inherit;
      display: table-cell;
      filter: Alpha(opacity=100);
      opacity: 1;
      position: absolute;
      vertical-align: middle;
      z-index: 2;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_image_second_spun_<?php echo $bwg; ?> {
      width: inherit;
      height: inherit;
      display: table-cell;
      filter: Alpha(opacity=0);
      opacity: 0;
      position: absolute;
      vertical-align: middle;
      z-index: 1;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_grid_<?php echo $bwg; ?> {
      display: none;
      height: 100%;
      overflow: hidden;
      position: absolute;
      width: 100%;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_gridlet_<?php echo $bwg; ?> {
      opacity: 1;
      filter: Alpha(opacity=100);
      position: absolute;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_dots_<?php echo $bwg; ?> {
      display: inline-block;
      position: relative;
      width: <?php echo $theme_row->slideshow_dots_width; ?>px;
      height: <?php echo $theme_row->slideshow_dots_height; ?>px;
      border-radius: <?php echo $theme_row->slideshow_dots_border_radius; ?>;
      background: #<?php echo $theme_row->slideshow_dots_background_color; ?>;
      margin: <?php echo $theme_row->slideshow_dots_margin; ?>px;
      cursor: pointer;
      overflow: hidden;
      z-index: 17;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_dots_container_<?php echo $bwg; ?> {
      display: block;
      overflow: hidden;
      position: absolute;
      width: <?php echo $image_width; ?>px;
      <?php echo $theme_row->slideshow_filmstrip_pos; ?>: 0;
      z-index: 17;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_dots_thumbnails_<?php echo $bwg; ?> {
      left: 0px;
      font-size: 0;
      margin: 0 auto;
      overflow: hidden;
      position: relative;
      height: <?php echo ($theme_row->slideshow_dots_height + $theme_row->slideshow_dots_margin * 2); ?>px;
      width: <?php echo ($theme_row->slideshow_dots_width + $theme_row->slideshow_dots_margin * 2) * count($image_rows); ?>px;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_dots_active_<?php echo $bwg; ?> {
      background: #<?php echo $theme_row->slideshow_dots_active_background_color; ?>;
      opacity: 1;
      filter: Alpha(opacity=100);
      border: <?php echo $theme_row->slideshow_dots_active_border_width; ?>px solid #<?php echo $theme_row->slideshow_dots_active_border_color; ?>;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_dots_deactive_<?php echo $bwg; ?> {
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_image_spun1_<?php echo $bwg; ?> {
      display: table; 
      width: inherit; 
      height: inherit;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_slideshow_image_spun2_<?php echo $bwg; ?> {
      display: table-cell; 
      vertical-align: middle; 
      text-align: center;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #spider_popup_overlay_<?php echo $bwg; ?> {
      background-color: #<?php echo $theme_row->lightbox_overlay_bg_color; ?>;
      opacity: <?php echo number_format($theme_row->lightbox_overlay_bg_transparent / 100, 2, ".", ""); ?>;
      filter: Alpha(opacity=<?php echo $theme_row->lightbox_overlay_bg_transparent; ?>);
    }
    <?php
    return ob_get_clean();
  }
}