<?php

/**
 * Class OptionsController_bwg
 */
class OptionsController_bwg {

  public function __construct() {
    $this->model = new OptionsModel_bwg();
    $this->view = new OptionsView_bwg();
    $this->page = WDWLibrary::get('page');
  }

  public function execute() {
    $task = WDWLibrary::get('task');
    if($task != ''){
      check_admin_referer(BWG()->nonce, BWG()->nonce);
    }
    $params = array();
    $params['permissions'] = array(
      'manage_options' => 'Administrator',
      'moderate_comments' => 'Editor',
      'publish_posts' => 'Author',
      'edit_posts' => 'Contributor',
    );
    $built_in_watermark_fonts = array();
    foreach (scandir(path_join(BWG()->plugin_dir, 'fonts')) as $filename) {
      if (strpos($filename, '.') === 0) continue;
      else $built_in_watermark_fonts[] = $filename;
    }
    $params['built_in_watermark_fonts'] = $built_in_watermark_fonts;
    $params['watermark_fonts'] = array(
      'arial' => 'Arial',
      'Lucida grande' => 'Lucida grande',
      'segoe ui' => 'Segoe ui',
      'tahoma' => 'Tahoma',
      'trebuchet ms' => 'Trebuchet ms',
      'verdana' => 'Verdana',
      'cursive' =>'Cursive',
      'fantasy' => 'Fantasy',
      'monospace' => 'Monospace',
      'serif' => 'Serif',
    );
    $params['effects'] = array(
      'none' => 'None',
      'cubeH' => 'Cube Horizontal',
      'cubeV' => 'Cube Vertical',
      'fade' => 'Fade',
      'sliceH' => 'Slice Horizontal',
      'sliceV' => 'Slice Vertical',
      'slideH' => 'Slide Horizontal',
      'slideV' => 'Slide Vertical',
      'scaleOut' => 'Scale Out',
      'scaleIn' => 'Scale In',
      'blockScale' => 'Block Scale',
      'kaleidoscope' => 'Kaleidoscope',
      'fan' => 'Fan',
      'blindH' => 'Blind Horizontal',
      'blindV' => 'Blind Vertical',
      'random' => 'Random',
    );
    $params['page_title'] = __('Edit options', BWG()->prefix);
    $params['active_tab'] = WDWLibrary::get('active_tab', 0);
    $params['gallery_type'] = WDWLibrary::get('gallery_type', 'thumbnails');
    $params['album_type'] = WDWLibrary::get('album_type', 'album_compact_preview');
    $params['gallery_types_name'] = array(
      'thumbnails' => __('Thumbnails', BWG()->prefix),
      'thumbnails_masonry' => __('Masonry', BWG()->prefix),
      'thumbnails_mosaic' => __('Mosaic', BWG()->prefix),
      'slideshow' => __('Slideshow', BWG()->prefix),
      'image_browser' => __('Image Browser', BWG()->prefix),
      'blog_style' => __('Blog Style', BWG()->prefix),
      'carousel' => __('Carousel', BWG()->prefix),
    );
    $params['album_types_name'] = array(
      'album_compact_preview' => __('Compact', BWG()->prefix),
      'album_masonry_preview' => __('Masonry', BWG()->prefix),
      'album_extended_preview' => __('Extended', BWG()->prefix),
    );
    if (method_exists($this, $task)) {
      $this->$task($params);
    }
    else {
      $this->display($params);
    }
  }

    /**
     * Display.
     *
     * @param $params
     */
  public function display($params = array()) {
    $row = new WD_BWG_Options();
    // Set Instagram access token.
    $instagram_access_token = WDWLibrary::get('access_token');
    if ( $instagram_access_token ) {
      ob_end_clean();
      $success = $this->model->set_instagram_access_token($instagram_access_token);
      if ( $success ) {
        wp_redirect( add_query_arg( array('page' => $this->page .'&instagram_token=' . time() ), admin_url('admin.php')) );
      }
    }

    $params['row']  = $row;
    $params['page'] = $this->page;
	  $params['instagram_return_url'] = 'https://api.instagram.com/oauth/authorize/?client_id=54da896cf80343ecb0e356ac5479d9ec&scope=basic+public_content&redirect_uri=http://api.web-dorado.com/instagram/?return_url=' . urlencode( admin_url('admin.php?page=options_bwg')) . '&response_type=token';
    $params['instagram_reset_href'] =  add_query_arg( array(
			'page' => $this->page,
			'task' => 'reset_instagram_access_token',
			BWG()->nonce => wp_create_nonce(BWG()->nonce),
		), admin_url('admin.php'));
    $this->view->display($params);
  }

    /**
     * Reset.
     *
     * @param array $params
     */
  public function reset( $params = array() ) {
    $params['row'] = new WD_BWG_Options(true);
    $params['page'] = $this->page;
    $params['instagram_return_url'] = 'https://api.instagram.com/oauth/authorize/?client_id=54da896cf80343ecb0e356ac5479d9ec&scope=basic+public_content&redirect_uri=http://api.web-dorado.com/instagram/?return_url=' . urlencode( admin_url('admin.php?page=options_bwg')) . '&response_type=token';
    $params['instagram_reset_href'] =  add_query_arg( array(
			'page' => $this->page,
			'task' => 'reset_instagram_access_token',
			BWG()->nonce => wp_create_nonce(BWG()->nonce),
		), admin_url('admin.php'));
    echo WDWLibrary::message_id(0, __('Default values restored. Changes must be saved.', BWG()->prefix), 'notice notice-warning');
    $this->view->display($params);
  }

  /**
   * Reset instagram access token.
   *
   * @param array $params
   */
  function reset_instagram_access_token ( $params = array() ) {
    ob_end_clean();
    $success = $this->model->set_instagram_access_token();
    if ( $success ) {
      wp_redirect( add_query_arg( array( 'page' => $this->page . '&instagram_token=' . time() ), admin_url( 'admin.php' ) ) );
    }
  }

  public function save( $params = array() ) {
    $this->save_db();
    $this->display( $params );
  }

  public function save_db() {
    $row = new WD_BWG_Options();
    if (isset($_POST['old_images_directory'])) {
      $row->old_images_directory = esc_html(stripslashes($_POST['old_images_directory']));
    }
    if (isset($_POST['images_directory'])) {
      $row->images_directory = esc_html(stripslashes($_POST['images_directory']));
      if (!is_dir(ABSPATH . $row->images_directory) || (is_dir(ABSPATH . $row->images_directory . '/photo-gallery') && $row->old_images_directory && $row->old_images_directory != $row->images_directory)) {
        if (!is_dir(ABSPATH . $row->images_directory)) {
          echo WDWLibrary::message_id(0, __('Uploads directory doesn\'t exist. Old value is restored.', BWG()->prefix), 'error');
        }
        else {
          echo WDWLibrary::message_id(0, __('Warning: "photo-gallery" folder already exists in uploads directory. Old value is restored.', BWG()->prefix), 'error');
        }
        if ($row->old_images_directory) {
          $row->images_directory = $row->old_images_directory;
        }
        else {
          $upload_dir = wp_upload_dir();
          if (!is_dir($upload_dir['basedir'] . '/photo-gallery')) {
            mkdir($upload_dir['basedir'] . '/photo-gallery', 0777);
          }
          $row->images_directory = str_replace(ABSPATH, '', $upload_dir['basedir']);
        }
      }
    }
    else {
      $upload_dir = wp_upload_dir();
      if (!is_dir($upload_dir['basedir'] . '/photo-gallery')) {
        mkdir($upload_dir['basedir'] . '/photo-gallery', 0777);
      }
      $row->images_directory = str_replace(ABSPATH, '', $upload_dir['basedir']);
    }

    foreach ($row as $name => $value) {
      if ($name == 'autoupdate_interval') {
        $autoupdate_interval = (isset($_POST['autoupdate_interval_hour']) && isset($_POST['autoupdate_interval_min']) ? ((int) $_POST['autoupdate_interval_hour'] * 60 + (int) $_POST['autoupdate_interval_min']) : null);
        /*minimum autoupdate interval is 1 min*/
        $row->autoupdate_interval = isset($autoupdate_interval) && $autoupdate_interval >= 1 ? $autoupdate_interval : 1;
      }
      else if ($name != 'images_directory' && isset($_POST[$name])) {
        $row->$name = esc_html(stripslashes($_POST[$name]));
      }
    }

    $save = update_option('wd_bwg_options', json_encode($row), 'no');
    if (isset($_POST['watermark']) && $_POST['watermark'] == "image_set_watermark") {
      $this->image_set_watermark();
    }
    if (isset($_POST['recreate']) && $_POST['recreate'] == "resize_image_thumb") {
      $this->resize_image_thumb();
      echo WDWLibrary::message_id(0, __('All thumbnails are successfully recreated.', BWG()->prefix));
    }

    if ($save) {
      if ($row->old_images_directory && $row->old_images_directory != $row->images_directory) {
        rename(ABSPATH . $row->old_images_directory . '/photo-gallery', ABSPATH . $row->images_directory . '/photo-gallery');
      }
      if (!is_dir(ABSPATH . $row->images_directory . '/photo-gallery')) {
        mkdir(ABSPATH . $row->images_directory . '/photo-gallery', 0777);
      }
      else {
        echo WDWLibrary::message_id(0, __('Item Succesfully Saved.', BWG()->prefix));
      }

      /*clear hook for scheduled events,
        refresh filter according to new time interval,
        then add new schedule with the same hook name
      */
      wp_clear_scheduled_hook( 'bwg_schedule_event_hook' );
      remove_filter( 'cron_schedules', array(BWG(), 'autoupdate_interval') );
      add_filter( 'cron_schedules', array(BWG(), 'autoupdate_interval') );
      wp_schedule_event( time(), 'bwg_autoupdate_interval', 'bwg_schedule_event_hook' );
    }
  }

  public function image_set_watermark() {
    WDWLibrary::bwg_image_set_watermark(0);
    echo WDWLibrary::message_id(0, __('All images are successfully watermarked.', BWG()->prefix), 'updated');
  }

  public function image_recover_all($params) {
    WDWLibrary::bwg_image_recover_all(0);
    echo WDWLibrary::message_id(0, __('All images are successfully reset.', BWG()->prefix), 'updated');
    $this->display($params);
  }

  public function resize_image_thumb() {
    global $wpdb;
    
    $img_ids = $wpdb->get_results('SELECT id, thumb_url, filetype FROM ' . $wpdb->prefix . 'bwg_image');
    foreach ($img_ids as $img_id) {
      if ( preg_match('/EMBED/', $img_id->filetype) == 1 ) {
        continue;
      }
      $file_path = str_replace("thumb", ".original", htmlspecialchars_decode(ABSPATH . BWG()->upload_dir . $img_id->thumb_url, ENT_COMPAT | ENT_QUOTES));
      $new_file_path = htmlspecialchars_decode(ABSPATH . BWG()->upload_dir . $img_id->thumb_url, ENT_COMPAT | ENT_QUOTES);
      $image = wp_get_image_editor( $file_path );
      if ( ! is_wp_error( $image ) ) {
        $image_size = $image->get_size();
        $img_width = $image_size['width'];
        $img_height = $image_size['height'];
      }
      if (!$img_width || !$img_height) {
        continue;
      }
      $max_width = BWG()->options->upload_thumb_width;
      $max_height = BWG()->options->upload_thumb_height;
      $scale = min(
          $max_width / $img_width,
          $max_height / $img_height
      );
      $new_width = $img_width * $scale;
      $new_height = $img_height * $scale;

      $image->set_quality(BWG()->options->image_quality);
      $image->resize($new_width, $new_height, false);
      $image->save($new_file_path);
    }
  }
}