<?php
class BWGControllerThumbnails {

  public function __construct() {
  }

  public function execute($params = array(), $from_shortcode = 0, $bwg = 0) {
	$this->display($params, $from_shortcode, $bwg);
  }

  public function display($params, $from_shortcode = 0, $bwg = 0) {
    require_once BWG()->plugin_dir . "/frontend/models/BWGModelThumbnails.php";
    $model = new BWGModelThumbnails();

    require_once BWG()->plugin_dir . "/frontend/views/BWGViewThumbnails.php";
    $view = new BWGViewThumbnails($model);
    $view->display($params, $from_shortcode, $bwg);
  }
}