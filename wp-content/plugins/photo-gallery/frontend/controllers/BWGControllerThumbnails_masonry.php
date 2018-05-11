<?php

class BWGControllerThumbnails_masonry {

  public function __construct() {
  }

  public function execute($params = array(), $from_shortcode = 0, $bwg = 0) {
    $this->display($params, $from_shortcode, $bwg);
  }

  public function display($params, $from_shortcode = 0, $bwg = 0) {
    require_once BWG()->plugin_dir . "/frontend/models/BWGModelThumbnails_masonry.php";
    $model = new BWGModelThumbnails_masonry();

    require_once BWG()->plugin_dir . "/frontend/views/BWGViewThumbnails_masonry.php";
    $view = new BWGViewThumbnails_masonry($model);

    $view->display($params, $from_shortcode, $bwg);
  }
}