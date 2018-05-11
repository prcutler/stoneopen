<?php

class BWGControllerDownload_gallery {
  public function execute() {
    $this->display();
  }

  public function display() {
    require_once BWG()->plugin_dir . "/frontend/views/BWGViewDownload_gallery.php";
    $view = new BWGViewDownload_gallery();
    $view->display();
  }
}