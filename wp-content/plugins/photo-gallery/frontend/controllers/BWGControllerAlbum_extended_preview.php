<?php

class BWGControllerAlbum_extended_preview {

  public function __construct() { }

  public function execute($params = array(), $from_shortcode = 0, $bwg = 0) {
    $this->display($params, $from_shortcode, $bwg);
  }

  public function display($params, $from_shortcode = 0, $bwg = 0) {
    require_once BWG()->plugin_dir . "/frontend/models/BWGModelAlbum_extended_preview.php";
    $model = new BWGModelAlbum_extended_preview();

    if( $params['show_gallery_description'] ) {
        if (isset($_POST['type_' . $bwg]) && isset($_POST['album_gallery_id_' . $bwg])) {
            $params['description'] = $model->get_album_gallery_description($_POST['type_' . $bwg], $_POST['album_gallery_id_' . $bwg]);
        } else {
            $params['description'] = $model->get_album_gallery_description('album', $params['album_id']);
        }
    }
    $params['album_title'] = $model->get_album_title($params['album_id']);
    require_once BWG()->plugin_dir . "/frontend/views/BWGViewAlbum_extended_preview.php";
    $view = new BWGViewAlbum_extended_preview($model);
    $view->display($params, $from_shortcode, $bwg);
  }
}