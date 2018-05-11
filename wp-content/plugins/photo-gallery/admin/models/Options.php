<?php

/**
 * Class OptionsModel_bwg
 */
class OptionsModel_bwg {

    /**
     * Set instagram access token.
     *
     * @param $key
     * @return mixed
     */
	function set_instagram_access_token( $key = '' ) {
		$row = new WD_BWG_Options();
		$row->instagram_access_token = $key;
		$upd = update_option('wd_bwg_options', json_encode($row));
		return $upd;
	}
}