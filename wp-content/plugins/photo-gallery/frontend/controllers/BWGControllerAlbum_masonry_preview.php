<?php
class BWGControllerAlbum_masonry_preview {

  public function __construct() {
  }

  public function execute($params = array(), $from_shortcode = 0, $bwg = 0) {
    $this->display($params, $from_shortcode, $bwg);
  }

  public function display($params, $from_shortcode = 0, $bwg = 0) {
    require_once BWG()->plugin_dir . "/frontend/models/BWGModelAlbum_masonry_preview.php";
    $model = new BWGModelAlbum_masonry_preview();

    require_once BWG()->plugin_dir . "/frontend/views/BWGViewAlbum_masonry_preview.php";
    $view = new BWGViewAlbum_masonry_preview($model);

    $view->display($params, $from_shortcode, $bwg);
  }
}