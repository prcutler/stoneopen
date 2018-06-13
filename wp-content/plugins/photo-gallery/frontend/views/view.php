<?php
class BWGViewSite {
  public function display($params, $bwg = 0, $content = '') {
    if (BWG()->options->use_inline_stiles_and_scripts) {
      wp_enqueue_style('bwg_frontend');
      wp_enqueue_style('bwg_font-awesome');
      wp_enqueue_style('bwg_googlefonts');
      if (isset($params['show_tag_box']) && $params['show_tag_box']) {
        wp_enqueue_style('bwg_sumoselect');
        if (!wp_script_is('bwg_sumoselect', 'done')) {
          wp_print_scripts('bwg_sumoselect');
        }
      }
      if ($params['thumb_click_action'] == 'open_lightbox') {
        if ( $params['popup_enable_comment'] ) {
          wp_enqueue_style('bwg_mCustomScrollbar');
          if ( !wp_script_is('bwg_mCustomScrollbar', 'done') ) {
            wp_print_scripts('bwg_mCustomScrollbar');
          }
        }
        if ( $params['popup_enable_fullscreen'] ) {
          if ( !wp_script_is('jquery-fullscreen', 'done') ) {
            wp_print_scripts('jquery-fullscreen');
          }
        }
        if (!wp_script_is('bwg_gallery_box', 'done')) {
          wp_print_scripts('bwg_gallery_box');
        }
        if ( $params['popup_enable_rate'] ) {
          if ( !wp_script_is('bwg_raty', 'done') ) {
            wp_print_scripts('bwg_raty');
          }
        }
      }
      if (!wp_script_is('bwg_frontend', 'done')) {
        wp_print_scripts('bwg_frontend');
      }
      if (!wp_script_is('bwg_jquery_mobile', 'done')) {
        wp_print_scripts('bwg_jquery_mobile');
      }
    }

    $params_array = $params['params_array'];
    $theme_row = $params['theme_row'];
    $images_count = count($params['image_rows']['images']);
    $gallery_row = $params['gallery_row'];
    $tags_rows = $params['tags_rows'];
    $items_per_page = $params['items_per_page'];
    ?>
    <div id="bwg_container1_<?php echo $bwg; ?>"
         class="bwg_container"
         data-right-click-protection="<?php echo BWG()->options->image_right_click; ?>"
         data-bwg="<?php echo $bwg; ?>"
         data-lightbox-url="<?php echo addslashes(add_query_arg($params_array, admin_url('admin-ajax.php'))); ?>"
         data-gallery-id="<?php echo $params_array['gallery_id']; ?>"
         data-popup-width="<?php echo $params["popup_width"]; ?>"
         data-popup-height="<?php echo $params["popup_height"]; ?>"
         data-buttons-position="<?php echo $theme_row->lightbox_ctrl_btn_pos; ?>">
      <div id="bwg_container2_<?php echo $bwg; ?>">
        <?php
        $this->loading($bwg);
        if ( $params['image_enable_page'] ) {
          ?>
        <form id="gal_front_form_<?php echo $bwg; ?>" class="bwg-hidden" method="post" action="#" data-current="<?php echo $bwg; ?>">
          <?php
        }
        if (isset($params['show_search_box']) && $params['show_search_box']) {
          $this->ajax_html_frontend_search_box('gal_front_form_' . $bwg, $bwg, 'bwg_standart_thumbnails_' . $bwg, $images_count, $params['search_box_width'], $params['placeholder']);
        }
        if (isset($params['show_sort_images']) && $params['show_sort_images']) {
          $this->ajax_html_frontend_sort_box('gal_front_form_' . $bwg, $bwg, 'bwg_standart_thumbnails_' . $bwg, $params['sort_by'], $params['search_box_width']);
        }
        if (isset($params['show_tag_box']) && $params['show_tag_box']) {
          $this->ajax_html_frontend_search_tags('gal_front_form_' . $bwg, $bwg, 'bwg_standart_thumbnails_' . $bwg, $images_count, $tags_rows);
        }
        if ( isset($params['showthumbs_name']) ) {
          $this->title_description($bwg, $gallery_row, $params, $theme_row);
        }
        if ( $params['image_enable_page'] ) {
          ?>
        <div style="background-color: rgba(0, 0, 0, 0); width: 100%; position: relative;<?php echo !$theme_row->container_margin && $theme_row->thumb_margin ? ' overflow: hidden;' : ''; ?>">
          <?php
        }
        if (isset($params['image_enable_page']) && isset($params['images_per_page']) && $params['image_enable_page']  && $params['images_per_page'] && ($theme_row->page_nav_position == 'top')) {
          $page_nav = $params['image_rows']['page_nav'];
          $this->ajax_html_frontend_page_nav($theme_row, $page_nav['total'], $page_nav['limit'], 'gal_front_form_' . $bwg, $items_per_page, $bwg, 'bwg_standart_thumbnails_' . $bwg, 0, 'album', BWG()->options->enable_seo, $params['image_enable_page']);
        }
        echo $content;
        if ( isset($params['gallery_download']) ) {
          $this->download_button($params, $images_count);
        }
        if ( isset($params['image_enable_page']) && isset($params['images_per_page']) && $params['image_enable_page'] && $params['images_per_page'] && ($theme_row->page_nav_position == 'bottom') ) {
          $page_nav = $params['image_rows']['page_nav'];
          $this->ajax_html_frontend_page_nav($theme_row, $page_nav['total'], $page_nav['limit'], 'gal_front_form_' . $bwg, $items_per_page, $bwg, 'bwg_standart_thumbnails_' . $bwg, 0, 'album', BWG()->options->enable_seo, $params['image_enable_page']);
        }
        if ( $params['image_enable_page'] ) {
          ?>
          </div>
        </form>
          <?php
        }
        if ( $params['thumb_click_action'] == 'open_lightbox' ) {
          ?>
        <div id="bwg_spider_popup_loading_<?php echo $bwg; ?>" class="bwg_spider_popup_loading"></div>
        <div id="spider_popup_overlay_<?php echo $bwg; ?>" class="spider_popup_overlay" onclick="spider_destroypopup(1000)"></div>
          <?php
        }
        ?>
      </div>
    </div>
    <?php
  }

  public function loading($bwg) {
    ?>
    <div id="ajax_loading_<?php echo $bwg; ?>" class="bwg_loading_div_1">
      <div class="bwg_loading_div_2">
        <div class="bwg_loading_div_3">
          <div id="loading_div_<?php echo $bwg; ?>" class="bwg_spider_ajax_loading">
          </div>
        </div>
      </div>
    </div>
    <?php
  }

  public function title_description($bwg, $gallery_row, $params, $theme_row) {
    if (($params['showthumbs_name'] && $gallery_row->name != '') || ($params['show_gallery_description'] && $gallery_row->name != '')) {
      ob_start();
      ?>
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_gal_title_<?php echo $bwg; ?> {
      background-color: rgba(0, 0, 0, 0);
      color: #<?php echo $theme_row->thumb_gal_title_font_color; ?>;
      display: block;
      font-family: <?php echo $theme_row->thumb_gal_title_font_style; ?>;
      font-size: <?php echo $theme_row->thumb_gal_title_font_size; ?>px;
      font-weight: <?php echo $theme_row->thumb_gal_title_font_weight; ?>;
      padding: <?php echo $theme_row->thumb_gal_title_margin; ?>;
      text-shadow: <?php echo $theme_row->thumb_gal_title_shadow; ?>;
      text-align: <?php echo $theme_row->thumb_gal_title_align; ?>;
      }
      <?php
      $inline_style = ob_get_clean();

      if ( BWG()->options->use_inline_stiles_and_scripts ) {
        wp_add_inline_style( 'bwg_frontend', $inline_style );
      } else {
        echo '<style>' . $inline_style . '</style>';
      }
    }

    if ($params['showthumbs_name'] && $gallery_row->name != '') {
      ?>
      <div class="bwg_gal_title_<?php echo $bwg; ?>"><?php echo $gallery_row->name; ?></div>
      <?php
    }
    if ($params['show_gallery_description'] && $gallery_row->name != '') {
      ?>
      <div class="bwg_gal_title_<?php echo $bwg; ?>"><?php echo $gallery_row->description; ?></div>
      <?php
    }
  }

  public function download_button($params, $images_count) {
    if ( $params['gallery_download'] && $images_count && ($params['gallery_row']->id == 0 || ($params['gallery_row']->gallery_type != 'facebook' && $params['gallery_row']->gallery_type != 'instagram' && $params['gallery_row']->gallery_type != 'instagram_post')) ) {
        $query_url = addslashes(add_query_arg(array(
        "action" => "download_gallery",
        "gallery_id" => $params['gallery_id'],
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

  public function ajax_html_frontend_search_box($form_id, $current_view, $cur_gal_id, $images_count, $search_box_width = 180, $placeholder = '', $album_gallery_id = 0) {
    $bwg_search = ((isset($_POST['bwg_search_' . $current_view]) && esc_html($_POST['bwg_search_' . $current_view]) != '') ? esc_html($_POST['bwg_search_' . $current_view]) : '');
    $type = (isset($_POST['type_' . $current_view]) ? esc_html($_POST['type_' . $current_view]) : ($album_gallery_id ? 'gallery' : 'album'));
    $album_gallery_id = (isset($_POST['album_gallery_id_' . $current_view]) ? esc_html($_POST['album_gallery_id_' . $current_view]) : ( $album_gallery_id ? $album_gallery_id : 0));

    ob_start();
    ?>
    #bwg_search_container_2_<?php echo $current_view; ?> {
    width: <?php echo $search_box_width; ?>px;
    }
    <?php
    $inline_style = ob_get_clean();

    if (BWG()->options->use_inline_stiles_and_scripts) {
      wp_add_inline_style('bwg_frontend', $inline_style);
    }
    else {
      echo '<style>' . $inline_style . '</style>';
    }
    ?>
    <div class="bwg_search_container_1" id="bwg_search_container_1_<?php echo $current_view; ?>">
      <div class="bwg_search_container_2" id="bwg_search_container_2_<?php echo $current_view; ?>">
        <span class="bwg_search_reset_container" >
          <i title="<?php echo __('Reset', BWG()->prefix); ?>" class="bwg_reset fa fa-times" onclick="bwg_clear_search_input('<?php echo $current_view; ?>'),spider_frontend_ajax('<?php echo $form_id; ?>', '<?php echo $current_view; ?>', '<?php echo $cur_gal_id; ?>', <?php echo $album_gallery_id; ?>, '', '<?php echo $type; ?>', 1)"></i>
        </span>
        <span class="bwg_search_loupe_container" >
          <i title="<?php echo __('Search', BWG()->prefix); ?>" class="bwg_search fa fa-search" onclick="spider_frontend_ajax('<?php echo $form_id; ?>', '<?php echo $current_view; ?>', '<?php echo $cur_gal_id; ?>', <?php echo $album_gallery_id; ?>, '', '<?php echo $type; ?>', 1)"></i>
        </span>
        <span class="bwg_search_input_container">
          <input id="bwg_search_input_<?php echo $current_view; ?>" class="bwg_search_input" type="text" onkeypress="return bwg_check_search_input_enter(this, event)" name="bwg_search_<?php echo $current_view; ?>" value="<?php echo $bwg_search; ?>" placeholder="<?php echo $placeholder; ?>" />
          <input id="bwg_images_count_<?php echo $current_view; ?>" class="bwg_search_input" type="hidden" name="bwg_images_count_<?php echo $current_view; ?>" value="<?php echo $images_count; ?>" >
        </span>
      </div>
    </div>
    <?php
  }

  public function ajax_html_frontend_sort_box($form_id, $current_view, $cur_gal_id, $sort_by = '', $search_box_width = 180) {
    $type = (isset($_POST['type_' . $current_view]) ? esc_html($_POST['type_' . $current_view]) : 'album');
    $album_gallery_id = (isset($_POST['album_gallery_id_' . $current_view]) ? esc_html($_POST['album_gallery_id_' . $current_view]) : 0);

    ob_start();
    ?>
    #bwg_order_<?php echo $current_view; ?> {
    width: <?php echo $search_box_width; ?>px;
    }
    <?php
    $inline_style = ob_get_clean();

    if (BWG()->options->use_inline_stiles_and_scripts) {
      wp_add_inline_style('bwg_frontend', $inline_style);
    }
    else {
      echo '<style>' . $inline_style . '</style>';
    }
    ?>
    <div class="bwg_order_cont">
      <span class="bwg_order_label"><?php echo __('Order by: ', BWG()->prefix); ?></span>
      <select id="bwg_order_<?php echo $current_view; ?>" class="bwg_order bwg_order_<?php echo $current_view; ?>" onchange="spider_frontend_ajax('<?php echo $form_id; ?>', '<?php echo $current_view; ?>', '<?php echo $cur_gal_id; ?>', <?php echo $album_gallery_id; ?>, '', '<?php echo $type; ?>', 1, '', this.value)">
        <option <?php if ($sort_by == 'default') echo 'selected'; ?> value="default"><?php echo __('Default', BWG()->prefix); ?></option>
        <option <?php if ($sort_by == 'filename') echo 'selected'; ?> value="filename"><?php echo __('Filename', BWG()->prefix); ?></option>
        <option <?php if ($sort_by == 'size') echo 'selected'; ?> value="size"><?php echo __('Size', BWG()->prefix); ?></option>
        <option <?php if ($sort_by == 'random' || $sort_by == 'RAND()') echo 'selected'; ?> value="random"><?php echo __('Random', BWG()->prefix); ?></option>
      </select>
    </div>
    <?php
  }

  public function ajax_html_frontend_search_tags($form_id, $current_view, $cur_gal_id, $images_count, $tags_rows) {
    $type = (isset($_POST['type_' . $current_view]) ? esc_html($_POST['type_' . $current_view]) : 'album');
    $bwg_search_tags = (isset($_POST['bwg_tag_id_' . $cur_gal_id]) && $_POST['bwg_tag_id_' . $cur_gal_id] != '' )? $_POST['bwg_tag_id_' . $cur_gal_id] : array();
    $album_gallery_id = (isset($_POST['album_gallery_id_' . $current_view]) ? esc_html($_POST['album_gallery_id_' . $current_view]) : 0);
    ?>
    <div id="bwg_tag_wrap">
      <div id="bwg_tag_container">
        <select class="search_tags" id="bwg_tag_id_<?php echo $cur_gal_id; ?>" multiple="multiple">
          <?php
          foreach($tags_rows as $tags_row) {
            $selected = (in_array($tags_row->term_id ? $tags_row->term_id : '', $bwg_search_tags)) ? 'selected="selected"' : '';
            ?>
            <option value="<?php echo $tags_row->term_id ?>" <?php echo $selected;?>><?php echo $tags_row->name ?></option>
            <?php
          }
          ?>
        </select>
        <span class="bwg_search_loupe_container" >
          <i title="<?php _e('Search', BWG()->prefix); ?>" class="bwg_search fa fa-search" onclick="bwg_select_tag('<?php echo $current_view; ?>' ,'<?php echo $form_id; ?>', '<?php echo $cur_gal_id; ?>', <?php echo $album_gallery_id; ?>, '<?php echo $type; ?>', false);"></i>
        </span>
        <span class="bwg_search_reset_container" >
          <i title="<?php _e('Reset', BWG()->prefix); ?>" class="bwg_reset fa fa-times" onclick="bwg_select_tag('<?php echo $current_view; ?>' ,'<?php echo $form_id; ?>', '<?php echo $cur_gal_id; ?>', <?php echo $album_gallery_id; ?>, '<?php echo $type; ?>', '<?php echo $cur_gal_id; ?>');"></i>
        </span>
        <input type="hidden" id="bwg_tags_id_<?php echo $cur_gal_id;  ?>" value="" />
      </div>
      <div style="clear:both"></div>
    </div>
    <?php
  }

  public function ajax_html_frontend_page_nav($theme_row, $count_items, $page_number, $form_id, $items_per_page, $current_view, $id, $cur_alb_gal_id = 0, $type = 'album', $enable_seo = false, $pagination = 1) {
    ob_start();
    $rgb_page_nav_font_color = WDWLibrary::spider_hex2rgb($theme_row->page_nav_font_color);
    ?>
    /*pagination styles*/
    #bwg_container1_<?php echo $current_view; ?> #bwg_container2_<?php echo $current_view; ?> .tablenav-pages_<?php echo $current_view; ?> {
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
    #bwg_container1_<?php echo $current_view; ?> #bwg_container2_<?php echo $current_view; ?> .displaying-num_<?php echo $current_view; ?> {
    display: none;
    }
    }
    #bwg_container1_<?php echo $current_view; ?> #bwg_container2_<?php echo $current_view; ?> .displaying-num_<?php echo $current_view; ?> {
    font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
    font-family: <?php echo $theme_row->page_nav_font_style; ?>;
    font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
    color: #<?php echo $theme_row->page_nav_font_color; ?>;
    margin-right: 10px;
    vertical-align: middle;
    }
    #bwg_container1_<?php echo $current_view; ?> #bwg_container2_<?php echo $current_view; ?> .paging-input_<?php echo $current_view; ?> {
    font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
    font-family: <?php echo $theme_row->page_nav_font_style; ?>;
    font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
    color: #<?php echo $theme_row->page_nav_font_color; ?>;
    vertical-align: middle;
    }
    #bwg_container1_<?php echo $current_view; ?> #bwg_container2_<?php echo $current_view; ?> .tablenav-pages_<?php echo $current_view; ?> a.disabled,
    #bwg_container1_<?php echo $current_view; ?> #bwg_container2_<?php echo $current_view; ?> .tablenav-pages_<?php echo $current_view; ?> a.disabled:hover,
    #bwg_container1_<?php echo $current_view; ?> #bwg_container2_<?php echo $current_view; ?> .tablenav-pages_<?php echo $current_view; ?> a.disabled:focus {
    cursor: default;
    color: rgba(<?php echo $rgb_page_nav_font_color['red']; ?>, <?php echo $rgb_page_nav_font_color['green']; ?>, <?php echo $rgb_page_nav_font_color['blue']; ?>, 0.5);
    }
    #bwg_container1_<?php echo $current_view; ?> #bwg_container2_<?php echo $current_view; ?> .tablenav-pages_<?php echo $current_view; ?> a {
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
    <?php
    $inline_style = ob_get_clean();

    if (BWG()->options->use_inline_stiles_and_scripts) {
      wp_add_inline_style('bwg_frontend', $inline_style);
    }
    else {
      echo '<style>' . $inline_style . '</style>';
    }

    $limit = $page_number > 1 ? $items_per_page['load_more_image_count'] : $items_per_page['images_per_page'];
    $limit = $limit ? $limit : 1;
    $type = (isset($_POST['type_' . $current_view]) ? esc_html($_POST['type_' . $current_view]) : $type);
    $album_gallery_id = (isset($_POST['album_gallery_id_' . $current_view]) ? esc_html($_POST['album_gallery_id_' . $current_view]) : $cur_alb_gal_id);
    if ($count_items) {
      if ($count_items % $limit) {
        $items_county = ($count_items - $count_items % $limit) / $limit + 1;
      }
      else {
        $items_county = ($count_items - $count_items % $limit) / $limit;
      }
      if ($pagination == 2) {
        $items_county++;
      }
    }
    else {
      $items_county = 1;
    }
    if ($page_number > $items_county) {
      return;
    }
    $first_page = "first-page-" . $current_view;
    $prev_page = "prev-page-" . $current_view;
    $next_page = "next-page-" . $current_view;
    $last_page = "last-page-" . $current_view;
    ?>
    <span class="bwg_nav_cont_<?php echo $current_view; ?>">
    <?php
    if ($pagination == 1) {
      ?>
      <div class="tablenav-pages_<?php echo $current_view; ?>">
      <?php
      if ($theme_row->page_nav_number) {
        ?>
        <span class="displaying-num_<?php echo $current_view; ?>"><?php echo $count_items . ' ' . __(' item(s)', BWG()->prefix); ?></span>
        <?php
      }
      if ($count_items > $limit) {
        if ($theme_row->page_nav_button_text) {
          $first_button = __('First', BWG()->prefix);
          $previous_button = __('Previous', BWG()->prefix);
          $next_button = __('Next', BWG()->prefix);
          $last_button = __('Last', BWG()->prefix);
        }
        else {
          $first_button = '«';
          $previous_button = '‹';
          $next_button = '›';
          $last_button = '»';
        }
        if ($page_number == 1) {
          $first_page = "first-page disabled";
          $prev_page = "prev-page disabled";
        }
        if ($page_number >= $items_county) {
          $next_page = "next-page disabled";
          $last_page = "last-page disabled";
        }
        ?>
        <span class="pagination-links_<?php echo $current_view; ?>">
        <a class="<?php echo $first_page; ?>" title="<?php echo __('Go to the first page', BWG()->prefix); ?>"><?php echo $first_button; ?></a>
        <a class="<?php echo $prev_page; ?>" title="<?php echo __('Go to the previous page', BWG()->prefix); ?>" <?php echo  $page_number > 1 && $enable_seo ? 'href="' . esc_url(add_query_arg(array("page_number_" . $current_view => $page_number - 1), $_SERVER['REQUEST_URI'])) . '"' : ""; ?>><?php echo $previous_button; ?></a>
        <span class="paging-input_<?php echo $current_view; ?>">
          <span class="total-pages_<?php echo $current_view; ?>"><?php echo $page_number; ?></span> <?php echo __('of', BWG()->prefix); ?> <span class="total-pages_<?php echo $current_view; ?>">
            <?php echo $items_county; ?>
          </span>
        </span>
        <a class="<?php echo $next_page ?>" title="<?php echo __('Go to the next page', BWG()->prefix); ?>" <?php echo  $page_number + 1 <= $items_county && $enable_seo ? 'href="' . esc_url(add_query_arg(array("page_number_" . $current_view => $page_number + 1), $_SERVER['REQUEST_URI'])) . '"' : ""; ?>><?php echo $next_button; ?></a>
        <a class="<?php echo $last_page ?>" title="<?php echo __('Go to the last page', BWG()->prefix); ?>"><?php echo $last_button; ?></a>
      </span>
        <?php
      }
      ?>
    </div>
      <?php
    }
    elseif ($pagination == 2) {
    if ($count_items > ($limit * ($page_number - 1)) + $items_per_page['images_per_page']) {
    ?>
		<div id="bwg_load_<?php echo $current_view; ?>" class="tablenav-pages_<?php echo $current_view; ?>">
			<a class="bwg_load_btn_<?php echo $current_view; ?> bwg_load_btn" href="javascript:void(0);"><?php echo __('Load More...', BWG()->prefix); ?></a>
			<input type="hidden" id="bwg_load_more_<?php echo $current_view; ?>" name="bwg_load_more_<?php echo $current_view; ?>" value="on" />
		</div>
    <?php
    }
    }
    elseif ($pagination == 3) {
    if ($count_items > $limit * $page_number) {
    ?>
		<script type="text/javascript">
		  jQuery(window).on("scroll", function() {
        if (jQuery(document).scrollTop() + jQuery(window).height() > (jQuery('#<?php echo $form_id; ?>').offset().top + jQuery('#<?php echo $form_id; ?>').height())) {
          spider_page_<?php echo $current_view; ?>('', <?php echo $page_number; ?>, 1, true);
          jQuery(window).off("scroll");
          return false;
        }
      });
		</script>
      <?php
    }
    }
    ?>
      <input type="hidden" id="page_number_<?php echo $current_view; ?>" name="page_number_<?php echo $current_view; ?>" value="<?php echo ((isset($_POST['page_number_' . $current_view])) ? (int) $_POST['page_number_' . $current_view] : 1); ?>" />
    <script type="text/javascript">
      function spider_page_<?php echo $current_view; ?>(cur, x, y, load_more) {
        if (typeof load_more == "undefined") {
          var load_more = false;
        }
        if (jQuery(cur).hasClass('disabled')) {
          return false;
        }
        var items_county_<?php echo $current_view; ?> = <?php echo $items_county; ?>;
        switch (y) {
          case 1:
            if (x >= items_county_<?php echo $current_view; ?>) {
              document.getElementById('page_number_<?php echo $current_view; ?>').value = items_county_<?php echo $current_view; ?>;
            }
            else {
              document.getElementById('page_number_<?php echo $current_view; ?>').value = x + 1;
            }
            break;
          case 2:
            document.getElementById('page_number_<?php echo $current_view; ?>').value = items_county_<?php echo $current_view; ?>;
            break;
          case -1:
            if (x == 1) {
              document.getElementById('page_number_<?php echo $current_view; ?>').value = 1;
            }
            else {
              document.getElementById('page_number_<?php echo $current_view; ?>').value = x - 1;
            }
            break;
          case -2:
            document.getElementById('page_number_<?php echo $current_view; ?>').value = 1;
            break;
          default:
            document.getElementById('page_number_<?php echo $current_view; ?>').value = 1;
        }
        spider_frontend_ajax('<?php echo $form_id; ?>', '<?php echo $current_view; ?>', '<?php echo $id; ?>', '<?php echo $album_gallery_id; ?>', '', '<?php echo $type; ?>', 0, '', '', load_more);
      }
      jQuery('.<?php echo $first_page; ?>').on('click', function() {
        spider_page_<?php echo $current_view; ?>(this, <?php echo $page_number; ?>, -2);
      });
      jQuery('.<?php echo $prev_page; ?>').on('click', function() {
        spider_page_<?php echo $current_view; ?>(this, <?php echo $page_number; ?>, -1);
        return false;
      });
      jQuery('.<?php echo $next_page; ?>').on('click', function() {
        spider_page_<?php echo $current_view; ?>(this, <?php echo $page_number; ?>, 1);
        return false;
      });
      jQuery('.<?php echo $last_page; ?>').on('click', function() {
        spider_page_<?php echo $current_view; ?>(this, <?php echo $page_number; ?>, 2);
      });
      jQuery('.bwg_load_btn_<?php echo $current_view; ?>').on('click', function() {
        spider_page_<?php echo $current_view; ?>(this, <?php echo $page_number; ?>, 1, true);
        return false;
      });
    </script>
    </span>
    <?php
  }
}
