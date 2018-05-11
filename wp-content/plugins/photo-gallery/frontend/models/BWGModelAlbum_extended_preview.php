<?php
class BWGModelAlbum_extended_preview {

    /**
     * Get album title.
     *
     * @param string $album_id
     *
     * @return string
     */
    public function get_album_title( $album_id ) {
        global $wpdb;
        $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwg_album WHERE id="%d"', $album_id));
        if($row) {
            return $row->name;
        }
    }

    /**
     * Get description from gallery or album tables.
     *
     * @param string $type
     * @param int $id
     *
     * @return string
     */
    public function get_album_gallery_description( $type, $id ) {
        global $wpdb;
        $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwg_'.$type.' WHERE id="%d"', $id));
        if($row) {
            return $row->description;
        }
    }
}