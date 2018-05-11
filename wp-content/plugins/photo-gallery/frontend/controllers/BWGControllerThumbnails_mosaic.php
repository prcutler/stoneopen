<?php
class BWGControllerThumbnails_mosaic {

  public function __construct() {
  }

  public function execute($params = array(), $from_shortcode = 0, $bwg = 0) {
    $this->display($params, $from_shortcode, $bwg);
  }

  public function display($params, $from_shortcode = 0, $bwg = 0) {
    require_once BWG()->plugin_dir . "/frontend/models/BWGModelThumbnails_mosaic.php";
    $model = new BWGModelThumbnails_mosaic();

    require_once BWG()->plugin_dir . "/frontend/views/BWGViewThumbnails_mosaic.php";
    $view = new BWGViewThumbnails_mosaic($model);

    $view->display($params, $from_shortcode, $bwg);
  }
}