<?php
class BWGControllerSite {

  private $model;
  private $view;

  public function __construct($view) {
    require_once BWG()->plugin_dir . "/frontend/models/model.php";
    $this->model = new BWGModelSite();

    require_once BWG()->plugin_dir . "/frontend/views/view.php";
    require_once BWG()->plugin_dir . '/frontend/views/BWGView' . $view . '.php';
    $view_class = 'BWGView' . $view;
    $this->view = new $view_class();
  }

  public function execute($params = array(), $from_shortcode = 0, $bwg = 0) {
    $theme_id = $params['theme_id'];
    $theme_row = $this->model->get_theme_row_data($theme_id);
    if ( !$theme_row ) {
      echo WDWLibrary::message(__('There is no theme selected or the theme was deleted.', BWG()->prefix), 'wd_error');

      return;
    }
    else {
      if (!isset($theme_row->thumb_gal_title_font_color)) {
        $theme_row->thumb_gal_title_font_color = 'CCCCCC';
      }
      if (!isset($theme_row->thumb_gal_title_font_style)) {
        $theme_row->thumb_gal_title_font_style = 'segoe ui';
      }
      if (!isset($theme_row->thumb_gal_title_font_size)) {
        $theme_row->thumb_gal_title_font_size = 16;
      }
      if (!isset($theme_row->thumb_gal_title_font_weight)) {
        $theme_row->thumb_gal_title_font_weight = 'bold';
      }
      if (!isset($theme_row->thumb_gal_title_margin)) {
        $theme_row->thumb_gal_title_margin = '2px';
      }
      if (!isset($theme_row->thumb_gal_title_shadow)) {
        $theme_row->thumb_gal_title_shadow = '0px 0px 0px #888888';
      }
      if (!isset($theme_row->thumb_gal_title_align)) {
        $theme_row->thumb_gal_title_align = 'center';
      }
      if (!isset($theme_row->container_margin)) {
        $theme_row->container_margin = 1;
      }
      $params['theme_row'] = $theme_row;
    }

    if ( !isset($params['type']) ) {
      $params['type'] = '';
    }

    $gallery_row = $this->model->get_gallery_row_data($params['gallery_id']);
    if ( !$gallery_row && ($params['type'] == '') && $params["tag"] == 0 ) {
      echo WDWLibrary::message(__('There is no gallery selected or the gallery was deleted.', BWG()->prefix), 'wd_error');

      return;
    }
    else {
      $params['gallery_row'] = $gallery_row;
    }

    if ( isset($_POST['sortImagesByValue_' . $bwg]) ) {
      $sort_by = esc_html($_POST['sortImagesByValue_' . $bwg]);
      if ( $sort_by == 'random' ) {
        $params['sort_by'] = 'RAND()';
      }
      else {
        if ( $sort_by == 'default' ) {
          $params['sort_by'] = $params['sort_by'];
        }
        else {
          $params['sort_by'] = $sort_by;
        }
      }
    }

    $params['load_more_image_count'] = (isset($params['load_more_image_count']) && ($params['image_enable_page'] == 2)) ? $params['load_more_image_count'] : $params['images_per_page'];
    $params['items_per_page'] = array('images_per_page' => $params['images_per_page'], 'load_more_image_count' => $params['load_more_image_count']);

    $image_rows = $this->model->get_image_rows_data($params['gallery_id'], $bwg, $params['type'], 'bwg_tag_id_bwg_standart_thumbnails_' . $bwg, $params['tag'], $params['images_per_page'], $params['load_more_image_count'], $params['sort_by'], $params['order_by']);
    $images_count = count($image_rows['images']);
    if ( !$images_count ) {
      if ( $params['tag'] ) {
        echo WDWLibrary::message(__('There are no images.', BWG()->prefix), 'wd_error');
      }
      else {
        echo WDWLibrary::message(__('There are no images in this gallery.', BWG()->prefix), 'wd_error');
      }

      return;
    }
    $params['image_rows'] = $image_rows;

    $params['tags_rows'] = $this->model->get_tags_rows_data($params['gallery_id']);

    $params['current_url'] = (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    $params_array = array(
      'action' => 'GalleryBox',
      'tags' => (isset($params['tag']) ? $params['tag'] : 0),
      'current_view' => $bwg,
      'gallery_id' => $params['gallery_id'],
      'theme_id' => $params['theme_id'],
      'thumb_width' => $params['thumb_width'],
      'thumb_height' => $params['thumb_height'],
      'open_with_fullscreen' => $params['popup_fullscreen'],
      'open_with_autoplay' => $params['popup_autoplay'],
      'image_width' => $params['popup_width'],
      'image_height' => $params['popup_height'],
      'image_effect' => $params['popup_effect'],
      'wd_sor' => ($params['sort_by'] == 'RAND()') ? 'order' : $params['sort_by'],
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
      'enable_image_ecommerce' => $params['popup_enable_ecommerce'],
      'enable_image_pinterest' => $params['popup_enable_pinterest'],
      'enable_image_tumblr' => $params['popup_enable_tumblr'],
      'watermark_type' => $params['watermark_type'],
      'slideshow_effect_duration' => isset($params['popup_effect_duration']) ? $params['popup_effect_duration'] : 1,
      'current_url' => urlencode($params['current_url']),
      'popup_enable_email' => $params['popup_enable_email'],
      'popup_enable_captcha' => $params['popup_enable_captcha'],
      'comment_moderation' => $params['comment_moderation'],
      'autohide_lightbox_navigation' => $params['autohide_lightbox_navigation'],
      'popup_enable_fullsize_image' => $params['popup_enable_fullsize_image'],
      'popup_enable_download' => $params['popup_enable_download'],
      'show_image_counts' => $params['show_image_counts'],
      'enable_loop' => $params['enable_loop'],
      'enable_addthis' => $params['enable_addthis'],
      'addthis_profile_id' => $params['addthis_profile_id']
    );
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
    $params['params_array'] = $params_array;
	  $this->display($params, $from_shortcode, $bwg);
  }

  public function display($params, $from_shortcode = 0, $bwg = 0) {
    $this->view->display($params, $from_shortcode, $bwg);
  }
}