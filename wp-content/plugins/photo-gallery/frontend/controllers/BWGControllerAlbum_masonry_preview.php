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

    if( isset($params[ 'show_gallery_description' ]) && $params[ 'show_gallery_description' ] ) {
      if ( isset( $_POST[ 'type_' . $bwg ] ) && isset( $_POST[ 'album_gallery_id_' . $bwg ] ) ) {
          $description = WDWLibrary::get_album_gallery_title_description( $_POST[ 'type_' . $bwg ], $_POST[ 'album_gallery_id_' . $bwg ] );
          $params[ 'description' ] = $description->description;
      } else {
          if( $params['album_id'] != 0 ) {
              $description = WDWLibrary::get_album_gallery_title_description('album', $params['album_id']);
              $params[ 'description' ] = $description->description;
          } else $params['description'] = '';
      }
    }

      $view->display($params, $from_shortcode, $bwg);
  }
}