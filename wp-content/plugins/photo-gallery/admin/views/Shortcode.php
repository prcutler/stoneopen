<?php

class ShortcodeView_bwg extends AdminView_bwg {
  public function display( $params ) {
    $from_menu = $params['from_menu'];
    if ( !$from_menu ) {
      BWG()->register_admin_scripts();
      wp_print_scripts('jquery-ui-tooltip');
      wp_print_scripts(BWG()->prefix . '_shortcode');
      wp_print_scripts(BWG()->prefix . '_jscolor');
      wp_print_styles(BWG()->prefix . '_shortcode');
      wp_print_styles(BWG()->prefix . '_tables');
      wp_print_scripts('jquery-ui-tabs');
    }
    else {
      wp_enqueue_script(BWG()->prefix . '_shortcode');
      wp_enqueue_style(BWG()->prefix . '_shortcode');
      wp_enqueue_script(BWG()->prefix . '_jscolor');
      wp_enqueue_script('jquery-ui-tabs');
    }
    // Close shortcode editor after insert.
    if ( isset($_POST['tagtext']) ) {
      echo '<script>if (top.tinyMCE.activeEditor) {top.tinyMCE.activeEditor.windowManager.close(window);}top.tb_remove();</script>';
      wp_die();
    }
    ob_start();
    echo $this->body($params);
    // Pass the content to form.
    $form_attr = array(
      'id' => BWG()->prefix . '_shortcode_form',
      'name' => BWG()->prefix . '_shortcode_form',
      'class' => BWG()->prefix . '_shortcode_form wd-form wp-core-ui js hidden',
      'action' => '#',
    );
    echo $this->form(ob_get_clean(), $form_attr);
    echo $this->generate_script($params);
    if ( !$from_menu ) {
      wp_die();
    }
  }

  public function body( $params ) {
    $gallery_rows = $params['gallery_rows'];
    $album_rows = $params['album_rows'];
    $theme_rows = $params['theme_rows'];
    $from_menu = $params['from_menu'];
    $tag_rows = $params['tag_rows'];
    $effects = $params['effects'];
    $watermark_fonts = $params['watermark_fonts'];
    $gallery_types_name = $params['gallery_types_name'];
    $album_types_name = $params['album_types_name'];
    $shortcodes = $params['shortcodes'];
    ?>
    <input type="hidden" id="tagtext" name="tagtext" value="" />
    <input type="hidden" id="currrent_id" name="currrent_id" value="" />
    <input type="hidden" id="title" name="title" value="" />
    <input type="hidden" id="bwg_insert" name="bwg_insert" value="" />
    <div>
      <div class="bwg_tabs meta-box-sortables">
        <ul class="bwg-tabs">
          <li class="tabs">
            <a href="#bwg_tab_galleries_content" class="bwg-tablink"><?php _e('Gallery', BWG()->prefix); ?></a>
          </li>
          <li class="tabs">
            <a href="#bwg_tab_albums_content" class="bwg-tablink"><?php _e('Gallery group', BWG()->prefix); ?></a>
          </li>
        </ul>
        <div id="bwg_tab_galleries_content" class="bwg-section bwg-no-bottom-border wd-box-content">
          <div class="bwg_change_gallery_type">
            <span class="gallery_type" onClick="bwg_gallery_type('thumbnails')">
              <div><label for="thumbnails"><img id="display_thumb" src="<?php echo BWG()->plugin_url . '/images/thumbnails.jpg'; ?>" /></label></div>
              <div class="gallery_type_div">
                <input type="radio" class="gallery_type_radio" id="thumbnails" name="gallery_type" value="thumbnails" /><label class="gallery_type_label" for="thumbnails"><?php echo __('Thumbnails', BWG()->prefix); ?></label>
              </div>
            </span>
            <span class="gallery_type" onClick="bwg_gallery_type('thumbnails_masonry')">
              <div>
                <label for="thumbnails_masonry"><img src="<?php echo BWG()->plugin_url . '/images/thumbnails_masonry.jpg'; ?>" /></label>
              </div>
              <div class="gallery_type_div">
                <input type="radio" class="gallery_type_radio" id="thumbnails_masonry" name="gallery_type" value="thumbnails_masonry" /><label class="gallery_type_label" for="thumbnails_masonry"><?php echo __('Masonry', BWG()->prefix); ?></label>
                <?php if ( !BWG()->is_pro ) { ?>
                  <span class="pro_btn">Paid</span>
                <?php } ?>
              </div>
            </span>
            <span class="gallery_type" onClick="bwg_gallery_type('thumbnails_mosaic')">
              <div><label for="thumbnails_mosaic"><img src="<?php echo BWG()->plugin_url . '/images/thumbnails_mosaic.jpg'; ?>" /></label></div>
              <div class="gallery_type_div">
                <input type="radio" class="gallery_type_radio" id="thumbnails_mosaic" name="gallery_type" value="thumbnails_mosaic" /><label class="gallery_type_label" for="thumbnails_mosaic"><?php echo __('Mosaic', BWG()->prefix); ?></label>
                <?php if ( !BWG()->is_pro ) { ?>
                  <span class="pro_btn">Paid</span>
                <?php } ?>
              </div>
            </span>
            <span class="gallery_type" onClick="bwg_gallery_type('slideshow')">
              <div><label for="slideshow"><img src="<?php echo BWG()->plugin_url . '/images/slideshow.jpg'; ?>" /></label></div>
              <div class="gallery_type_div">
                <input type="radio" class="gallery_type_radio" id="slideshow" name="gallery_type" value="slideshow" /><label class="gallery_type_label" for="slideshow"><?php echo __('Slideshow', BWG()->prefix); ?></label>
              </div>
            </span>
            <span class="gallery_type" onClick="bwg_gallery_type('image_browser')">
              <div><label for="image_browser"><img src="<?php echo BWG()->plugin_url . '/images/image_browser.jpg'; ?>" /></label></div>
              <div class="gallery_type_div">
                <input type="radio" class="gallery_type_radio" id="image_browser" name="gallery_type" value="image_browser" /><label class="gallery_type_label" for="image_browser"><?php echo __('Image Browser', BWG()->prefix); ?></label>
              </div>
            </span>
            <span class="gallery_type" onClick="bwg_gallery_type('blog_style')">
              <div><label for="blog_style"><img src="<?php echo BWG()->plugin_url . '/images/blog_style.jpg'; ?>" /></label></div>
              <div class="gallery_type_div">
                <input type="radio" class="gallery_type_radio" id="blog_style" name="gallery_type" value="blog_style" /><label class="gallery_type_label" for="blog_style"><?php echo __('Blog Style', BWG()->prefix); ?></label>
                <?php if ( !BWG()->is_pro ) { ?>
                  <span class="pro_btn">Paid</span>
                <?php } ?>
              </div>
            </span>
            <span class="gallery_type" onClick="bwg_gallery_type('carousel')">
              <div><label for="carousel"><img src="<?php echo BWG()->plugin_url . '/images/Carousel.png'; ?>" /></label></div>
              <div class="gallery_type_div">
                <input class="gallery_type_radio" type="radio" id="carousel" name="gallery_type" value="carousel" /><label class="gallery_type_label" for="carousel"><?php echo __('Carousel', BWG()->prefix); ?></label>
                <?php if ( !BWG()->is_pro ) { ?>
                  <span class="pro_btn">Paid</span>
                <?php } ?>
              </div>
            </span>
          </div>
          <div class="bwg_select_gallery_type" style="display:none;">
            <label class="wd-label" for="gallery_types_name"><?php _e('View type', BWG()->prefix); ?></label>
            <select name="gallery_types_name" id="gallery_types_name" onchange="bwg_gallery_type(jQuery(this).val());">
              <?php
              foreach ( $gallery_types_name as $key => $album_type_name ) {
                ?>
                <option <?php echo selected($album_type_name, TRUE); ?> value="<?php echo $key; ?>"><?php echo $album_type_name; ?></option>
                <?php
              }
              ?>
            </select>
          </div>
        </div>
        <div id="bwg_tab_albums_content" class="bwg-section bwg-no-bottom-border wd-box-content">
          <div class="bwg_change_gallery_type">
            <span class="gallery_type" onClick="bwg_gallery_type('album_compact_preview')">
              <div><label for="album_compact_preview"><img src="<?php echo BWG()->plugin_url . '/images/album_compact_preview.jpg'; ?>" /></label></div>
              <div class="album_type_div">
                <input type="radio" class="album_type_radio" id="album_compact_preview" name="gallery_type" value="album_compact_preview" /><label class="album_type_label" for="album_compact_preview"><?php echo __('Compact', BWG()->prefix); ?></label>
              </div>
            </span>
            <span class="gallery_type" onClick="bwg_gallery_type('album_masonry_preview')">
              <div><label for="album_masonry_preview"><img src="<?php echo BWG()->plugin_url . '/images/thumbnails_masonry.jpg'; ?>" /></label></div>
              <div class="album_type_div">
                <input type="radio" class="album_type_radio" id="album_masonry_preview" name="gallery_type" value="album_masonry_preview" /><label class="album_type_label" for="album_masonry_preview"><?php echo __('Masonry', BWG()->prefix); ?></label>
                <?php if ( !BWG()->is_pro ) { ?>
                  <span class="pro_btn">Paid</span>
                <?php } ?>
              </div>
            </span>
            <span class="gallery_type" onClick="bwg_gallery_type('album_extended_preview')">
              <div><label for="album_extended_preview"><img src="<?php echo BWG()->plugin_url . '/images/album_extended_preview.jpg'; ?>" /></label></div>
              <div class="album_type_div">
                <input type="radio" class="album_type_radio" id="album_extended_preview" name="gallery_type" value="album_extended_preview" /><label class="album_type_label" for="album_extended_preview"><?php echo __('Extended', BWG()->prefix); ?></label>
              </div>
            </span>
          </div>
          <div class="bwg_select_gallery_type" style="display:none;">
            <label class="wd-label" for="gallery_types_name"><?php _e('View type', BWG()->prefix); ?></label>
            <select name="gallery_types_name" id="gallery_types_name" onchange="bwg_gallery_type(jQuery(this).val());">
              <?php
              foreach ( $album_types_name as $key => $album_type_name ) {
                ?>
                <option <?php echo selected($album_type_name, TRUE); ?> value="<?php echo $key; ?>"><?php echo $album_type_name; ?></option>
                <?php
              }
              ?>
            </select>
          </div>
        </div>
        <?php
        if ( !BWG()->is_pro ) {
          ?>
          <div class="wd-box-content wd-width-100 wd-free-msg">
            <?php
            echo WDWLibrary::message_id(0, __('This view is not available in free version.', BWG()->prefix), 'error');
            ?>
          </div>
          <?php
        }
        ?>
        <div class="bwg-pro-views bwg-section bwg-no-top-border bwg-flex-wrap">
          <div class="wd-box-content wd-width-33">
            <div class="wd-group" id="tr_gallery">
              <label class="wd-label" for="gallery"><?php _e('Gallery', BWG()->prefix); ?></label>
              <div>
                <select name="gallery" id="gallery">
                  <option value="0"><?php _e('All images', BWG()->prefix); ?></option>
                  <?php
                  foreach ( $gallery_rows as $gallery_row ) {
                    ?>
                    <option value="<?php echo $gallery_row->id; ?>"><?php echo $gallery_row->name; ?></option>
                    <?php
                  }
                  ?>
                </select>
              </div>
              <p class="description"><?php _e('Select the gallery to display.', BWG()->prefix) ?></p>
            </div>
            <div class="wd-group" id="tr_album">
              <label class="wd-label" for="album"><?php _e('Gallery Group', BWG()->prefix); ?></label>
              <div>
                <select name="album" id="album">
                  <option value="0" selected="selected"><?php _e('All Galleries', BWG()->prefix); ?></option>
                  <?php
                  foreach ( $album_rows as $album_row ) {
                    ?>
                    <option value="<?php echo $album_row->id; ?>"><?php echo $album_row->name; ?></option>
                    <?php
                  }
                  ?>
                </select>
                <p class="description"><?php _e('Select the gallery group to display.', BWG()->prefix); ?></p>
              </div>
            </div>
          </div>
          <div class="wd-box-content wd-width-33" id="tr_tag">
            <div class="wd-group">
              <label class="wd-label" for="tag"><?php _e('Tag', BWG()->prefix); ?></label>
              <div>
                <select name="tag" id="tag">
                  <option value="0"><?php _e('All Tags', BWG()->prefix); ?></option>
                  <?php
                  foreach ( $tag_rows as $tag_row ) {
                    ?>
                    <option value="<?php echo $tag_row->term_id; ?>"><?php echo $tag_row->name; ?></option>
                    <?php
                  }
                  ?>
                </select>
              </div>
              <p class="description"><?php _e('Filter gallery images by this tag.', BWG()->prefix) ?></p>
            </div>
          </div>
          <div class="wd-box-content wd-width-33">
            <div class="wd-group" id="tr_theme">
              <label class="wd-label" for="theme"><?php _e('Theme', BWG()->prefix); ?></label>
              <div>
                <select name="theme" id="theme">
                  <?php
                  foreach ( $theme_rows as $theme_row ) {
                    ?>
                    <option value="<?php echo $theme_row->id; ?>"><?php echo $theme_row->name; ?></option>
                    <?php
                  }
                  ?>
                </select>
              </div>
              <p class="description"><?php _e('Choose the theme for your gallery.', BWG()->prefix) ?></p>
            </div>
          </div>
          <div class="wd-box-content wd-width-100">
            <div class="wd-group">
              <input id="use_option_defaults" class="wd-radio" value="1" name="use_option_defaults" type="checkbox" checked="checked" />
              <label class="wd-label-radio" for="use_option_defaults"><?php _e('Use default options', BWG()->prefix); ?></label>
              <p class="description"><?php _e('Mark this option to use default settings configured in Photo Gallery Options.', BWG()->prefix) ?><br><?php echo sprintf(__('You can change the default options %s.', BWG()->prefix), '<a id="options_link" data-href="' . admin_url('admin.php?page=options_' . BWG()->prefix) . '" href="' . admin_url('admin.php?page=options_' . BWG()->prefix) . '" target="_blank">' . __('here', BWG()->prefix) . '</a>'); ?></p>
            </div>
          </div>
          <div id="custom_options_conainer" class="wd-box-content wd-width-100">
            <div class="postbox closed">
              <button class="button-link handlediv" type="button" aria-expanded="true">
                <span class="screen-reader-text"><?php _e('Toggle panel:', BWG()->prefix); ?></span>
                <span class="toggle-indicator" aria-hidden="false"></span>
              </button>
              <h2 class="hndle">
                <span id="bwg_basic_metabox_title" data-title-gallery="<?php _e('Gallery', BWG()->prefix); ?>" data-title-album="<?php _e('Gallery group', BWG()->prefix); ?>"></span>
              </h2>
              <div class="inside bwg-flex-wrap">
                <div id="thumbnails_options" class="gallery_options wd-box-content wd-width-100 bwg-flex-wrap">
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="thumb_width"><?php _e('Thumbnail dimensions', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="thumb_width" id="thumb_width" value="<?php echo BWG()->options->thumb_width; ?>" min="0" /><span>x</span>
                          <input type="number" name="thumb_height" id="thumb_height" value="<?php echo BWG()->options->thumb_height; ?>" min="0" /><span>px</span>
                        </div>
                        <p class="description"><?php _e('The default dimensions of thumbnails which will display on published galleries.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="image_column_number"><?php _e('Number of image columns', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="image_column_number" id="image_column_number" value="<?php echo BWG()->options->image_column_number; ?>" min="0" />
                        </div>
                        <p class="description"><?php _e('Set the maximum number of image columns in galleries. Note, that the parent container needs to be large enough to display all columns.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Pagination', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="image_enable_page" id="image_enable_page_0" value="0" <?php if (BWG()->options->image_enable_page == '0') echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_load_more_image_count', 'image_enable_page_0'); bwg_pagination_description(this);" /><label for="image_enable_page_0" class="wd-radio-label"><?php _e('None', BWG()->prefix); ?></label>
                          <input type="radio" name="image_enable_page" id="image_enable_page_1" value="1" <?php if (BWG()->options->image_enable_page == '1') echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_load_more_image_count', 'image_enable_page_1'); bwg_pagination_description(this);" /><label for="image_enable_page_1" class="wd-radio-label"><?php _e('Simple', BWG()->prefix); ?></label>
                          <input type="radio" name="image_enable_page" id="image_enable_page_2" value="2" <?php if (BWG()->options->image_enable_page == '2') echo 'checked="checked"'; ?> onClick="bwg_enable_disable('', 'tr_load_more_image_count', 'image_enable_page_2'); bwg_pagination_description(this);" /><label for="image_enable_page_2" class="wd-radio-label"><?php _e('Load More', BWG()->prefix); ?></label>
                          <input type="radio" name="image_enable_page" id="image_enable_page_3" value="3" <?php if (BWG()->options->image_enable_page == '3') echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_load_more_image_count', 'image_enable_page_3'); bwg_pagination_description(this);" /><label for="image_enable_page_3" class="wd-radio-label"><?php _e('Scroll Load', BWG()->prefix); ?></label>
                        </div>
                        <p class="description" id="image_enable_page_0_description"><?php _e('This option removes all types of pagination from your galleries.', BWG()->prefix); ?></p>
                        <p class="description" id="image_enable_page_1_description"><?php _e('Activating this option will add page numbers and next/previous buttons to your galleries.', BWG()->prefix); ?></p>
                        <p class="description" id="image_enable_page_2_description"><?php _e('Adding a Load More button, you can let users display a new set of images from your galleries.', BWG()->prefix); ?></p>
                        <p class="description" id="image_enable_page_3_description"><?php _e('With this option, users can load new images of your galleries simply by scrolling down.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_images_per_page">
                      <div class="wd-group">
                        <label class="wd-label" for="images_per_page"><?php _e('Images per page', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="images_per_page" id="images_per_page" value="<?php echo BWG()->options->images_per_page; ?>" min="0" />
                        </div>
                        <p class="description"><?php _e('Specify the number of images to display per page on galleries. Setting this option to 0 shows all items.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_load_more_image_count">
                      <div class="wd-group">
                        <label class="wd-label" for="load_more_image_count"><?php _e('Images per load', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="load_more_image_count" id="load_more_image_count" value="<?php echo BWG()->options->load_more_image_count; ?>" min="0" />
                        </div>
                        <p class="description"><?php _e('Specify the number of images to display per load on galleries.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="sort_by"><?php _e('Order by', BWG()->prefix); ?></label>
                        <select name="sort_by" id="sort_by">
                          <option value="order" <?php if (BWG()->options->sort_by == 'order') echo 'selected="selected"'; ?>><?php _e('Order', BWG()->prefix); ?></option>
                          <option value="alt" <?php if (BWG()->options->sort_by == 'alt') echo 'selected="selected"'; ?>><?php _e('Title', BWG()->prefix); ?></option>
                          <option value="date" <?php if (BWG()->options->sort_by == 'date') echo 'selected="selected"'; ?>><?php _e('Date', BWG()->prefix); ?></option>
                          <option value="filename" <?php if (BWG()->options->sort_by == 'filename') echo 'selected="selected"'; ?>><?php _e('Filename', BWG()->prefix); ?></option>
                          <option value="size" <?php if (BWG()->options->sort_by == 'size') echo 'selected="selected"'; ?>><?php _e('Size', BWG()->prefix); ?></option>
                          <option value="filetype" <?php if (BWG()->options->sort_by == 'filetype') echo 'selected="selected"'; ?>><?php _e('Type', BWG()->prefix); ?></option>
                          <option value="resolution" <?php if (BWG()->options->sort_by == 'resolution') echo 'selected="selected"'; ?>><?php _e('Resolution', BWG()->prefix); ?></option>
                          <option value="random" <?php if (BWG()->options->sort_by == 'random') echo 'selected="selected"'; ?>><?php _e('Random', BWG()->prefix); ?></option>
                        </select>
                        <p class="description"><?php _e("Select the parameter based on which the gallery images will sorted, e.g. Title.", BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Order direction', BWG()->prefix); ?></label>
                        <input type="radio" name="order_by" id="order_by_1" value="asc" <?php if (BWG()->options->order_by == 'asc') echo 'checked="checked"'; ?> /><label for="order_by_1" class="wd-radio-label"><?php _e('Ascending', BWG()->prefix); ?></label>
                        <input type="radio" name="order_by" id="order_by_0" value="desc" <?php if (BWG()->options->order_by == 'desc') echo 'checked="checked"'; ?> /><label for="order_by_0" class="wd-radio-label"><?php _e('Descending', BWG()->prefix); ?></label>
                        <p class="description"><?php _e("Set the ordering direction for gallery images, ascending or descending.", BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show search box', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="show_search_box" id="show_search_box_1" value="1" <?php if (BWG()->options->show_search_box) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('', 'tr_search_box_width', 'show_search_box_1'); bwg_enable_disable('', 'tr_search_box_placeholder', 'show_search_box_1')" /><label for="show_search_box_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="show_search_box" id="show_search_box_0" value="0" <?php if (!BWG()->options->show_search_box) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_search_box_width', 'show_search_box_0'); bwg_enable_disable('none', 'tr_search_box_placeholder', 'show_search_box_0')" /><label for="show_search_box_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Enable this option to display a search box with your gallery or gallery group.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_search_box_placeholder">
                      <div class="wd-group">
                        <label class="wd-label" for="placeholder"><?php _e('Add placeholder to search', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="text" name="placeholder" id="placeholder" value="<?php echo BWG()->options->placeholder; ?>"  />
                        </div>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_search_box_width">
                      <div class="wd-group">
                        <label class="wd-label" for="search_box_width"><?php _e('Search box width', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="search_box_width" id="search_box_width" value="<?php echo BWG()->options->search_box_width; ?>" min="0" /><span>px</span>
                        </div>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show "Order by" dropdown list', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="show_sort_images" id="show_sort_images_1" value="1" <?php if (BWG()->options->show_sort_images) echo 'checked="checked"'; ?> /><label for="show_sort_images_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="show_sort_images" id="show_sort_images_0" value="0" <?php if (!BWG()->options->show_sort_images) echo 'checked="checked"'; ?> /><label for="show_sort_images_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Activate this dropdown box to let users browse your gallery images with different ordering options.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show tag box', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="show_tag_box" id="show_tag_box_1" value="1" <?php if (BWG()->options->show_tag_box) echo 'checked="checked"'; ?> /><label for="show_tag_box_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="show_tag_box" id="show_tag_box_0" value="0" <?php if (!BWG()->options->show_tag_box) echo 'checked="checked"'; ?> /><label for="show_tag_box_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Enable Tag Box to allow users to filter the gallery images by their tags.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show gallery title', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="showthumbs_name" id="thumb_name_yes" value="1" <?php if (BWG()->options->showthumbs_name) echo 'checked="checked"'; ?> /><label for="thumb_name_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="showthumbs_name" id="thumb_name_no" value="0"  <?php if (!BWG()->options->showthumbs_name) echo 'checked="checked"'; ?> /><label for="thumb_name_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Allow users to see the titles of your galleries by enabling this setting.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show gallery description', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="show_gallery_description" id="show_gallery_description_1" value="1" <?php if (BWG()->options->show_gallery_description) echo 'checked="checked"'; ?> /><label for="show_gallery_description_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="show_gallery_description" id="show_gallery_description_0" value="0" <?php if (!BWG()->options->show_gallery_description) echo 'checked="checked"'; ?> /><label for="show_gallery_description_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Display the descriptions of your galleries by activating this option.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show image title', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="image_title_show_hover" id="image_title_show_hover_1" value="hover" <?php if (BWG()->options->image_title_show_hover == "hover") echo 'checked="checked"'; ?> /><label for="image_title_show_hover_1" class="wd-radio-label"><?php _e('Show on hover', BWG()->prefix); ?></label>
                          <input type="radio" name="image_title_show_hover" id="image_title_show_hover_0" value="show" <?php if (BWG()->options->image_title_show_hover == "show") echo 'checked="checked"'; ?> /><label for="image_title_show_hover_0" class="wd-radio-label"><?php _e('Always show', BWG()->prefix); ?></label>
                          <input type="radio" name="image_title_show_hover" id="image_title_show_hover_2" value="none" <?php if (BWG()->options->image_title_show_hover == "none") echo 'checked="checked"'; ?> /><label for="image_title_show_hover_2" class="wd-radio-label"><?php _e("Don't show", BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Choose to show/hide titles of images, or display them on hover.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show Play icon on video thumbnails', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="play_icon" id="play_icon_yes" value="1" <?php if (BWG()->options->play_icon) echo 'checked="checked"'; ?> /><label for="play_icon_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="play_icon" id="play_icon_no" value="0" <?php if (!BWG()->options->play_icon) echo 'checked="checked"'; ?> /><label for="play_icon_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Activate this option to add a Play button on thumbnails of videos.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Enable bulk download button', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="gallery_download" id="gallery_download_1" value="1" <?php if (BWG()->options->gallery_download) echo 'checked="checked"'; ?> /><label for="gallery_download_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="gallery_download" id="gallery_download_0" value="0" <?php if (!BWG()->options->gallery_download) echo 'checked="checked"'; ?> /><label for="gallery_download_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Activate this setting to let users download all images of your gallery with a click.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <?php
                    if (function_exists('BWGEC')) {
                      ?>
                      <div class="wd-box-content wd-width-100">
                        <div class="wd-group">
                          <label class="wd-label"><?php _e('Show ecommerce icon', BWG()->prefix); ?></label>
                          <div class="bwg-flex">
                            <input type="radio" name="ecommerce_icon_show_hover" id="ecommerce_icon_show_hover_1" value="hover" <?php if (BWG()->options->ecommerce_icon_show_hover == "hover") echo 'checked="checked"'; ?> /><label for="ecommerce_icon_show_hover_1" class="wd-radio-label"><?php _e('Show on hover', BWG()->prefix); ?></label>
                            <input type="radio" name="ecommerce_icon_show_hover" id="ecommerce_icon_show_hover_0" value="show" <?php if (BWG()->options->ecommerce_icon_show_hover == "show") echo 'checked="checked"'; ?> /><label for="ecommerce_icon_show_hover_0" class="wd-radio-label"><?php _e('Always show', BWG()->prefix); ?></label>
                            <input type="radio" name="ecommerce_icon_show_hover" id="ecommerce_icon_show_hover_2" value="none" <?php if (BWG()->options->ecommerce_icon_show_hover == "none") echo 'checked="checked"'; ?> /><label for="ecommerce_icon_show_hover_2" class="wd-radio-label"><?php _e("Don't show", BWG()->prefix); ?></label>
                          </div>
                          <p class="description"><?php _e('Choose to show/hide ecommerce icon, or display them on hover.', BWG()->prefix); ?></p>
                        </div>
                      </div>
                      <?php
                    }
                    ?>
                  </div>
                </div>
                <div id="thumbnails_masonry_options" class="gallery_options wd-box-content wd-width-100 bwg-flex-wrap">
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Masonry type', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="masonry" id="masonry_0" value="vertical" <?php echo  (BWG()->options->masonry == "vertical") ? 'checked="checked"' : ''; ?> onClick="bwg_enable_disable('', 'tr_show_masonry_thumb_description', 'masonry_0');" /><label for="masonry_0" class="wd-radio-label"><?php _e('Vertical', BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="masonry" id="masonry_1" value="horizontal" <?php echo (BWG()->options->masonry == "horizontal") ? 'checked="checked"': ''; ?> onClick="bwg_enable_disable('none', 'tr_show_masonry_thumb_description', 'masonry_1');" /><label for="masonry_1" class="wd-radio-label"><?php _e('Horizontal', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Select the type of Masonry galleries, Vertical or Horizontal.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="masonry_thumb_size"><?php _e('Thumbnail size', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="masonry_thumb_size" id="masonry_thumb_size" value="<?php echo BWG()->options->masonry_thumb_size; ?>" min="0" /><span>px</span>
                        </div>
                        <p class="description"><?php _e('The default size of thumbnails which will display on published galleries.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label masonry_col_num" style="<?php echo (BWG()->options->masonry == "vertical") ? '' : 'display:none'; ?>" for="masonry_image_column_number"><?php _e('Number of image columns', BWG()->prefix); ?></label>
                        <label class="wd-label masonry_row_num" style="<?php echo (BWG()->options->masonry == "vertical") ? 'display:none' : ''; ?>" for="masonry_image_column_number"><?php _e('Number of image rows', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="masonry_image_column_number" id="masonry_image_column_number" value="<?php echo BWG()->options->masonry_image_column_number; ?>" min="0" />
                        </div>
                        <p class="description"><?php _e('Set the maximum number of image columns (or rows) in galleries. Note, that the parent container needs to be large enough to display all columns.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Pagination', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="masonry_image_enable_page" id="masonry_image_enable_page_0" value="0" <?php if (BWG()->options->masonry_image_enable_page == '0') echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_masonry_load_more_image_count', 'masonry_image_enable_page_0'); bwg_pagination_description(this);" /><label for="masonry_image_enable_page_0" class="wd-radio-label"><?php _e('None', BWG()->prefix); ?></label>
                          <input type="radio" name="masonry_image_enable_page" id="masonry_image_enable_page_1" value="1" <?php if (BWG()->options->masonry_image_enable_page == '1') echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_masonry_load_more_image_count', 'masonry_image_enable_page_1'); bwg_pagination_description(this);" /><label for="masonry_image_enable_page_1" class="wd-radio-label"><?php _e('Simple', BWG()->prefix); ?></label>
                          <input type="radio" name="masonry_image_enable_page" id="masonry_image_enable_page_2" value="2" <?php if (BWG()->options->masonry_image_enable_page == '2') echo 'checked="checked"'; ?> onClick="bwg_enable_disable('', 'tr_masonry_load_more_image_count', 'masonry_image_enable_page_2'); bwg_pagination_description(this);" /><label for="masonry_image_enable_page_2" class="wd-radio-label"><?php _e('Load More', BWG()->prefix); ?></label>
                          <input type="radio" name="masonry_image_enable_page" id="masonry_image_enable_page_3" value="3" <?php if (BWG()->options->masonry_image_enable_page == '3') echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_masonry_load_more_image_count', 'masonry_image_enable_page_3'); bwg_pagination_description(this);" /><label for="masonry_image_enable_page_3" class="wd-radio-label"><?php _e('Scroll Load', BWG()->prefix); ?></label>
                        </div>
                        <p class="description" id="masonry_image_enable_page_0_description"><?php _e('This option removes all types of pagination from your galleries.', BWG()->prefix); ?></p>
                        <p class="description" id="masonry_image_enable_page_1_description"><?php _e('Activating this option will add page numbers and next/previous buttons to your galleries.', BWG()->prefix); ?></p>
                        <p class="description" id="masonry_image_enable_page_2_description"><?php _e('Adding a Load More button, you can let users display a new set of images from your galleries.', BWG()->prefix); ?></p>
                        <p class="description" id="masonry_image_enable_page_3_description"><?php _e('With this option, users can load new images of your galleries simply by scrolling down.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_masonry_images_per_page">
                      <div class="wd-group">
                        <label class="wd-label" for="masonry_images_per_page"><?php _e('Images per page', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="masonry_images_per_page" id="masonry_images_per_page" value="<?php echo BWG()->options->masonry_images_per_page; ?>" min="0" />
                        </div>
                        <p class="description"><?php _e('Specify the number of images to display per page on galleries. Setting this option to 0 shows all items.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_masonry_load_more_image_count">
                      <div class="wd-group">
                        <label class="wd-label" for="masonry_load_more_image_count"><?php _e('Images per load', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="masonry_load_more_image_count" id="masonry_load_more_image_count" value="<?php echo BWG()->options->masonry_load_more_image_count; ?>" min="0" />
                        </div>
                        <p class="description"><?php _e('Specify the number of images to display per load on galleries.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="masonry_sort_by"><?php _e('Order by', BWG()->prefix); ?></label>
                        <select name="masonry_sort_by" id="masonry_sort_by">
                          <option value="order" <?php if (BWG()->options->masonry_sort_by == 'order') echo 'selected="selected"'; ?>><?php _e('Order', BWG()->prefix); ?></option>
                          <option value="alt" <?php if (BWG()->options->masonry_sort_by == 'alt') echo 'selected="selected"'; ?>><?php _e('Title', BWG()->prefix); ?></option>
                          <option value="date" <?php if (BWG()->options->masonry_sort_by == 'date') echo 'selected="selected"'; ?>><?php _e('Date', BWG()->prefix); ?></option>
                          <option value="filename" <?php if (BWG()->options->masonry_sort_by == 'filename') echo 'selected="selected"'; ?>><?php _e('Filename', BWG()->prefix); ?></option>
                          <option value="size" <?php if (BWG()->options->masonry_sort_by == 'size') echo 'selected="selected"'; ?>><?php _e('Size', BWG()->prefix); ?></option>
                          <option value="filetype" <?php if (BWG()->options->masonry_sort_by == 'filetype') echo 'selected="selected"'; ?>><?php _e('Type', BWG()->prefix); ?></option>
                          <option value="resolution" <?php if (BWG()->options->masonry_sort_by == 'resolution') echo 'selected="selected"'; ?>><?php _e('Resolution', BWG()->prefix); ?></option>
                          <option value="random" <?php if (BWG()->options->masonry_sort_by == 'random') echo 'selected="selected"'; ?>><?php _e('Random', BWG()->prefix); ?></option>
                        </select>
                        <p class="description"><?php _e("Select the parameter based on which the gallery images will sorted, e.g. Title.", BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Order direction', BWG()->prefix); ?></label>
                        <input type="radio" name="masonry_order_by" id="masonry_order_by_1" value="asc" <?php if (BWG()->options->masonry_order_by == 'asc') echo 'checked="checked"'; ?> /><label for="masonry_order_by_1" class="wd-radio-label"><?php _e('Ascending', BWG()->prefix); ?></label>
                        <input type="radio" name="masonry_order_by" id="masonry_order_by_0" value="desc" <?php if (BWG()->options->masonry_order_by == 'desc') echo 'checked="checked"'; ?> /><label for="masonry_order_by_0" class="wd-radio-label"><?php _e('Descending', BWG()->prefix); ?></label>
                        <p class="description"><?php _e("Set the ordering direction for gallery images, ascending or descending.", BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show search box', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="masonry_show_search_box" id="masonry_show_search_box_1" value="1" <?php if (BWG()->options->masonry_show_search_box) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('', 'tr_masonry_search_box_width', 'masonry_show_search_box_1'); bwg_enable_disable('', 'tr_masonry_search_box_placeholder', 'masonry_show_search_box_1')" /><label for="masonry_show_search_box_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="masonry_show_search_box" id="masonry_show_search_box_0" value="0" <?php if (!BWG()->options->masonry_show_search_box) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_masonry_search_box_width', 'masonry_show_search_box_0'); bwg_enable_disable('none', 'tr_masonry_search_box_placeholder', 'masonry_show_search_box_0')" /><label for="masonry_show_search_box_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Enable this option to display a search box with your gallery or gallery group.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_masonry_search_box_placeholder">
                      <div class="wd-group">
                        <label class="wd-label" for="placeholder"><?php _e('Add placeholder to search', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="text" name="masonry_placeholder" id="masonry_placeholder" value="<?php echo BWG()->options->masonry_placeholder; ?>"  />
                        </div>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_masonry_search_box_width">
                      <div class="wd-group">
                        <label class="wd-label" for="masonry_search_box_width"><?php _e('Search box width', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="masonry_search_box_width" id="masonry_search_box_width" value="<?php echo BWG()->options->masonry_search_box_width; ?>" min="0" /><span>px</span>
                        </div>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show "Order by" dropdown list', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="masonry_show_sort_images" id="masonry_show_sort_images_1" value="1" <?php if (BWG()->options->masonry_show_sort_images) echo 'checked="checked"'; ?> /><label for="masonry_show_sort_images_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="masonry_show_sort_images" id="masonry_show_sort_images_0" value="0" <?php if (!BWG()->options->masonry_show_sort_images) echo 'checked="checked"'; ?> /><label for="masonry_show_sort_images_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Activate this dropdown box to let users browse your gallery images with different ordering options.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show tag box', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="masonry_show_tag_box" id="masonry_show_tag_box_1" value="1" <?php if (BWG()->options->masonry_show_tag_box) echo 'checked="checked"'; ?> /><label for="masonry_show_tag_box_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="masonry_show_tag_box" id="masonry_show_tag_box_0" value="0" <?php if (!BWG()->options->masonry_show_tag_box) echo 'checked="checked"'; ?> /><label for="masonry_show_tag_box_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Enable Tag Box to allow users to filter the gallery images by their tags.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show gallery title', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="masonry_show_gallery_title" id="masonry_thumb_name_yes" value="1" <?php if (BWG()->options->masonry_show_gallery_title) echo 'checked="checked"'; ?> /><label for="masonry_thumb_name_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="masonry_show_gallery_title" id="masonry_thumb_name_no" value="0"  <?php if (!BWG()->options->masonry_show_gallery_title) echo 'checked="checked"'; ?> /><label for="masonry_thumb_name_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Allow users to see the titles of your galleries by enabling this setting.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show gallery description', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="masonry_show_gallery_description" id="masonry_show_gallery_description_1" value="1" <?php if (BWG()->options->masonry_show_gallery_description) echo 'checked="checked"'; ?> /><label for="masonry_show_gallery_description_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="masonry_show_gallery_description" id="masonry_show_gallery_description_0" value="0" <?php if (!BWG()->options->masonry_show_gallery_description) echo 'checked="checked"'; ?> /><label for="masonry_show_gallery_description_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Display the descriptions of your galleries by activating this option.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_show_masonry_thumb_description">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show image descriptions in Vertical Masonry', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="show_masonry_thumb_description" id="masonry_thumb_desc_1" value="1" <?php if (BWG()->options->show_masonry_thumb_description) echo 'checked="checked"'; ?> /><label for="masonry_thumb_desc_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="show_masonry_thumb_description" id="masonry_thumb_desc_0" value="0" <?php if (!BWG()->options->show_masonry_thumb_description) echo 'checked="checked"'; ?> /><label for="masonry_thumb_desc_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Enable this setting to display descriptions under images in Vertical Masonry view.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show Play icon on video thumbnails', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="masonry_play_icon" id="masonry_play_icon_yes" value="1" <?php if (BWG()->options->masonry_play_icon) echo 'checked="checked"'; ?> /><label for="masonry_play_icon_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="masonry_play_icon" id="masonry_play_icon_no" value="0" <?php if (!BWG()->options->masonry_play_icon) echo 'checked="checked"'; ?> /><label for="masonry_play_icon_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Activate this option to add a Play button on thumbnails of videos.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Enable bulk download button', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="masonry_gallery_download" id="masonry_gallery_download_1" value="1" <?php if (BWG()->options->masonry_gallery_download) echo 'checked="checked"'; ?> /><label for="masonry_gallery_download_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="masonry_gallery_download" id="masonry_gallery_download_0" value="0" <?php if (!BWG()->options->masonry_gallery_download) echo 'checked="checked"'; ?> /><label for="masonry_gallery_download_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Activate this setting to let users download all images of your gallery with a click.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <?php
                    if (function_exists('BWGEC')) {
                      ?>
                      <div class="wd-box-content wd-width-100">
                        <div class="wd-group">
                          <label class="wd-label"><?php _e('Show ecommerce icon', BWG()->prefix); ?></label>
                          <div class="bwg-flex">
                            <input type="radio" name="masonry_ecommerce_icon_show_hover" id="masonry_ecommerce_icon_show_hover_1" value="hover" <?php if (BWG()->options->masonry_ecommerce_icon_show_hover == "hover") echo 'checked="checked"'; ?> /><label for="masonry_ecommerce_icon_show_hover_1" class="wd-radio-label"><?php _e('Show on hover', BWG()->prefix); ?></label>
                            <input type="radio" name="masonry_ecommerce_icon_show_hover" id="masonry_ecommerce_icon_show_hover_2" value="none" <?php if (BWG()->options->masonry_ecommerce_icon_show_hover == "none") echo 'checked="checked"'; ?> /><label for="masonry_ecommerce_icon_show_hover_2" class="wd-radio-label"><?php _e("Don't show", BWG()->prefix); ?></label>
                          </div>
                          <p class="description"><?php _e('Choose to show/hide ecommerce icon, or display them on hover.', BWG()->prefix); ?></p>
                        </div>
                      </div>
                      <?php
                    }
                    ?>
                  </div>
                </div>
                <div id="thumbnails_mosaic_options" class="gallery_options wd-box-content wd-width-100 bwg-flex-wrap">
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Mosaic gallery type', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="mosaic" id="mosaic_0" value="vertical" <?php if (BWG()->options->mosaic == "vertical") echo 'checked="checked"'; ?> /><label for="mosaic_0" class="wd-radio-label"><?php _e('Vertical', BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="mosaic" id="mosaic_1" value="horizontal" <?php if (BWG()->options->mosaic == "horizontal") echo 'checked="checked"'; ?> /><label for="mosaic_1" class="wd-radio-label"><?php _e('Horizontal', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Select the type of Mosaic galleries, Vertical or Horizontal.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Resizable mosaic', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="resizable_mosaic" id="resizable_mosaic_1" value="1" <?php if (BWG()->options->resizable_mosaic == "1") echo 'checked="checked"'; ?> /><label for="resizable_mosaic_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="resizable_mosaic" id="resizable_mosaic_0" value="0" <?php if (BWG()->options->resizable_mosaic == "0") echo 'checked="checked"'; ?> /><label for="resizable_mosaic_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('If this setting is enabled, Photo Gallery resizes all thumbnail images on Mosaic galleries, without modifying their initial display.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="mosaic_total_width"><?php _e('Width of mosaic galleries', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="number" name="mosaic_total_width" id="mosaic_total_width" value="<?php echo BWG()->options->mosaic_total_width; ?>" min="0" /><span>%</span>
                        </div>
                        <p class="description"><?php _e('The total width of mosaic galleries as a percentage of container\'s width.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="mosaic_thumb_size"><?php _e('Thumbnail size', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="mosaic_thumb_size" id="mosaic_thumb_size" value="<?php echo BWG()->options->mosaic_thumb_size; ?>" min="0" /><span>px</span>
                        </div>
                        <p class="description"><?php _e('The default size of thumbnails which will display on published galleries.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Pagination', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="mosaic_image_enable_page" id="mosaic_image_enable_page_0" value="0" <?php if (BWG()->options->mosaic_image_enable_page == '0') echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_mosaic_load_more_image_count', 'mosaic_image_enable_page_0'); bwg_pagination_description(this);" /><label for="mosaic_image_enable_page_0" class="wd-radio-label"><?php _e('None', BWG()->prefix); ?></label>
                          <input type="radio" name="mosaic_image_enable_page" id="mosaic_image_enable_page_1" value="1" <?php if (BWG()->options->mosaic_image_enable_page == '1') echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_mosaic_load_more_image_count', 'mosaic_image_enable_page_1'); bwg_pagination_description(this);" /><label for="mosaic_image_enable_page_1" class="wd-radio-label"><?php _e('Simple', BWG()->prefix); ?></label>
                          <input type="radio" name="mosaic_image_enable_page" id="mosaic_image_enable_page_2" value="2" <?php if (BWG()->options->mosaic_image_enable_page == '2') echo 'checked="checked"'; ?> onClick="bwg_enable_disable('', 'tr_mosaic_load_more_image_count', 'mosaic_image_enable_page_2'); bwg_pagination_description(this);" /><label for="mosaic_image_enable_page_2" class="wd-radio-label"><?php _e('Load More', BWG()->prefix); ?></label>
                          <input type="radio" name="mosaic_image_enable_page" id="mosaic_image_enable_page_3" value="3" <?php if (BWG()->options->mosaic_image_enable_page == '3') echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_mosaic_load_more_image_count', 'mosaic_image_enable_page_3'); bwg_pagination_description(this);" /><label for="mosaic_image_enable_page_3" class="wd-radio-label"><?php _e('Scroll Load', BWG()->prefix); ?></label>
                        </div>
                        <p class="description" id="mosaic_image_enable_page_0_description"><?php _e('This option removes all types of pagination from your galleries.', BWG()->prefix); ?></p>
                        <p class="description" id="mosaic_image_enable_page_1_description"><?php _e('Activating this option will add page numbers and next/previous buttons to your galleries.', BWG()->prefix); ?></p>
                        <p class="description" id="mosaic_image_enable_page_2_description"><?php _e('Adding a Load More button, you can let users display a new set of images from your galleries.', BWG()->prefix); ?></p>
                        <p class="description" id="mosaic_image_enable_page_3_description"><?php _e('With this option, users can load new images of your galleries simply by scrolling down.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_mosaic_images_per_page">
                      <div class="wd-group">
                        <label class="wd-label" for="mosaic_images_per_page"><?php _e('Images per page', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="mosaic_images_per_page" id="mosaic_images_per_page" value="<?php echo BWG()->options->mosaic_images_per_page; ?>" min="0" />
                        </div>
                        <p class="description"><?php _e('Specify the number of images to display per page on galleries. Setting this option to 0 shows all items.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_mosaic_load_more_image_count">
                      <div class="wd-group">
                        <label class="wd-label" for="mosaic_load_more_image_count"><?php _e('Images per load', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="mosaic_load_more_image_count" id="mosaic_load_more_image_count" value="<?php echo BWG()->options->mosaic_load_more_image_count; ?>" min="0" />
                        </div>
                        <p class="description"><?php _e('Specify the number of images to display per load on galleries.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="mosaic_sort_by"><?php _e('Order by', BWG()->prefix); ?></label>
                        <select name="mosaic_sort_by" id="mosaic_sort_by">
                          <option value="order" <?php if (BWG()->options->mosaic_sort_by == 'order') echo 'selected="selected"'; ?>><?php _e('Order', BWG()->prefix); ?></option>
                          <option value="alt" <?php if (BWG()->options->mosaic_sort_by == 'alt') echo 'selected="selected"'; ?>><?php _e('Title', BWG()->prefix); ?></option>
                          <option value="date" <?php if (BWG()->options->mosaic_sort_by == 'date') echo 'selected="selected"'; ?>><?php _e('Date', BWG()->prefix); ?></option>
                          <option value="filename" <?php if (BWG()->options->mosaic_sort_by == 'filename') echo 'selected="selected"'; ?>><?php _e('Filename', BWG()->prefix); ?></option>
                          <option value="size" <?php if (BWG()->options->mosaic_sort_by == 'size') echo 'selected="selected"'; ?>><?php _e('Size', BWG()->prefix); ?></option>
                          <option value="filetype" <?php if (BWG()->options->mosaic_sort_by == 'filetype') echo 'selected="selected"'; ?>><?php _e('Type', BWG()->prefix); ?></option>
                          <option value="resolution" <?php if (BWG()->options->mosaic_sort_by == 'resolution') echo 'selected="selected"'; ?>><?php _e('Resolution', BWG()->prefix); ?></option>
                          <option value="random" <?php if (BWG()->options->mosaic_sort_by == 'random') echo 'selected="selected"'; ?>><?php _e('Random', BWG()->prefix); ?></option>
                        </select>
                        <p class="description"><?php _e("Select the parameter based on which the gallery images will sorted, e.g. Title.", BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Order direction', BWG()->prefix); ?></label>
                        <input type="radio" name="mosaic_order_by" id="mosaic_order_by_1" value="asc" <?php if (BWG()->options->mosaic_order_by == 'asc') echo 'checked="checked"'; ?> /><label for="mosaic_order_by_1" class="wd-radio-label"><?php _e('Ascending', BWG()->prefix); ?></label>
                        <input type="radio" name="mosaic_order_by" id="mosaic_order_by_0" value="desc" <?php if (BWG()->options->mosaic_order_by == 'desc') echo 'checked="checked"'; ?> /><label for="mosaic_order_by_0" class="wd-radio-label"><?php _e('Descending', BWG()->prefix); ?></label>
                        <p class="description"><?php _e("Set the ordering direction for gallery images, ascending or descending.", BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show search box', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="mosaic_show_search_box" id="mosaic_show_search_box_1" value="1" <?php if (BWG()->options->mosaic_show_search_box) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('', 'tr_mosaic_search_box_width', 'mosaic_show_search_box_1'); bwg_enable_disable('', 'tr_mosaic_search_box_placeholder', 'mosaic_show_search_box_1')" /><label for="mosaic_show_search_box_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="mosaic_show_search_box" id="mosaic_show_search_box_0" value="0" <?php if (!BWG()->options->mosaic_show_search_box) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_mosaic_search_box_width', 'mosaic_show_search_box_0'); bwg_enable_disable('none', 'tr_mosaic_search_box_placeholder', 'mosaic_show_search_box_0')" /><label for="mosaic_show_search_box_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Enable this option to display a search box with your gallery or gallery group.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_mosaic_search_box_placeholder">
                      <div class="wd-group">
                        <label class="wd-label" for="mosaic_placeholder"><?php _e('Add placeholder to search', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="text" name="mosaic_placeholder" id="mosaic_placeholder" value="<?php echo BWG()->options->mosaic_placeholder; ?>"  />
                        </div>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_mosaic_search_box_width">
                      <div class="wd-group">
                        <label class="wd-label" for="mosaic_search_box_width"><?php _e('Search box width', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="mosaic_search_box_width" id="mosaic_search_box_width" value="<?php echo BWG()->options->mosaic_search_box_width; ?>" min="0" /><span>px</span>
                        </div>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show "Order by" dropdown list', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="mosaic_show_sort_images" id="mosaic_show_sort_images_1" value="1" <?php if (BWG()->options->mosaic_show_sort_images) echo 'checked="checked"'; ?> /><label for="mosaic_show_sort_images_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="mosaic_show_sort_images" id="mosaic_show_sort_images_0" value="0" <?php if (!BWG()->options->mosaic_show_sort_images) echo 'checked="checked"'; ?> /><label for="mosaic_show_sort_images_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Activate this dropdown box to let users browse your gallery images with different ordering options.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show tag box', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="mosaic_show_tag_box" id="mosaic_show_tag_box_1" value="1" <?php if (BWG()->options->mosaic_show_tag_box) echo 'checked="checked"'; ?> /><label for="mosaic_show_tag_box_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="mosaic_show_tag_box" id="mosaic_show_tag_box_0" value="0" <?php if (!BWG()->options->mosaic_show_tag_box) echo 'checked="checked"'; ?> /><label for="mosaic_show_tag_box_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Enable Tag Box to allow users to filter the gallery images by their tags.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show gallery title', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="mosaic_show_gallery_title" id="mosaic_thumb_name_yes" value="1" <?php if (BWG()->options->mosaic_show_gallery_title) echo 'checked="checked"'; ?> /><label for="mosaic_thumb_name_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="mosaic_show_gallery_title" id="mosaic_thumb_name_no" value="0"  <?php if (!BWG()->options->mosaic_show_gallery_title) echo 'checked="checked"'; ?> /><label for="mosaic_thumb_name_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Allow users to see the titles of your galleries by enabling this setting.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show gallery description', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="mosaic_show_gallery_description" id="mosaic_show_gallery_description_1" value="1" <?php if (BWG()->options->mosaic_show_gallery_description) echo 'checked="checked"'; ?> /><label for="mosaic_show_gallery_description_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="mosaic_show_gallery_description" id="mosaic_show_gallery_description_0" value="0" <?php if (!BWG()->options->mosaic_show_gallery_description) echo 'checked="checked"'; ?> /><label for="mosaic_show_gallery_description_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Display the descriptions of your galleries by activating this option.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show image title', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="mosaic_image_title_show_hover" id="mosaic_image_title_show_hover_1" value="hover" <?php if (BWG()->options->mosaic_image_title_show_hover == "hover") echo 'checked="checked"'; ?> /><label for="mosaic_image_title_show_hover_1" class="wd-radio-label"><?php _e('Show on hover', BWG()->prefix); ?></label>
                          <input type="radio" name="mosaic_image_title_show_hover" id="mosaic_image_title_show_hover_0" value="show" <?php if (BWG()->options->mosaic_image_title_show_hover == "show") echo 'checked="checked"'; ?> /><label for="mosaic_image_title_show_hover_0" class="wd-radio-label"><?php _e('Always show', BWG()->prefix); ?></label>
                          <input type="radio" name="mosaic_image_title_show_hover" id="mosaic_image_title_show_hover_2" value="none" <?php if (BWG()->options->mosaic_image_title_show_hover == "none") echo 'checked="checked"'; ?> /><label for="mosaic_image_title_show_hover_2" class="wd-radio-label"><?php _e("Don't show", BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Choose to show/hide titles of images, or display them on hover.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show Play icon on video thumbnails', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="mosaic_play_icon" id="mosaic_play_icon_yes" value="1" <?php if (BWG()->options->mosaic_play_icon) echo 'checked="checked"'; ?> /><label for="mosaic_play_icon_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="mosaic_play_icon" id="mosaic_play_icon_no" value="0" <?php if (!BWG()->options->mosaic_play_icon) echo 'checked="checked"'; ?> /><label for="mosaic_play_icon_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Activate this option to add a Play button on thumbnails of videos.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Enable bulk download button', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="mosaic_gallery_download" id="mosaic_gallery_download_1" value="1" <?php if (BWG()->options->mosaic_gallery_download) echo 'checked="checked"'; ?> /><label for="mosaic_gallery_download_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="mosaic_gallery_download" id="mosaic_gallery_download_0" value="0" <?php if (!BWG()->options->mosaic_gallery_download) echo 'checked="checked"'; ?> /><label for="mosaic_gallery_download_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Activate this setting to let users download all images of your gallery with a click.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <?php
                    if (function_exists('BWGEC')) {
                      ?>
                      <div class="wd-box-content wd-width-100">
                        <div class="wd-group">
                          <label class="wd-label"><?php _e('Show ecommerce icon', BWG()->prefix); ?></label>
                          <div class="bwg-flex">
                            <input type="radio" name="mosaic_ecommerce_icon_show_hover" id="mosaic_ecommerce_icon_show_hover_1" value="hover" <?php if (BWG()->options->mosaic_ecommerce_icon_show_hover == "hover") echo 'checked="checked"'; ?> /><label for="mosaic_ecommerce_icon_show_hover_1" class="wd-radio-label"><?php _e('Show on hover', BWG()->prefix); ?></label>
                            <input type="radio" name="mosaic_ecommerce_icon_show_hover" id="mosaic_ecommerce_icon_show_hover_2" value="none" <?php if (BWG()->options->mosaic_ecommerce_icon_show_hover == "none") echo 'checked="checked"'; ?> /><label for="mosaic_ecommerce_icon_show_hover_2" class="wd-radio-label"><?php _e("Don't show", BWG()->prefix); ?></label>
                          </div>
                          <p class="description"><?php _e('Choose to show/hide ecommerce icon, or display them on hover.', BWG()->prefix); ?></p>
                        </div>
                      </div>
                      <?php
                    }
                    ?>
                  </div>
                </div>
                <div id="slideshow_options" class="gallery_options wd-box-content wd-width-100 bwg-flex-wrap">
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="slideshow_type"><?php _e('Slideshow effect', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <select name="slideshow_type" id="slideshow_type">
                            <?php
                            foreach ($effects as $key => $effect) {
                              ?>
                              <option value="<?php echo $key; ?>"
                                <?php echo (!BWG()->is_pro && $key != 'none' && $key != 'fade') ? 'disabled="disabled" title="' . __('This effect is disabled in free version.', BWG()->prefix) . '"' : ''; ?>
                                <?php if (BWG()->options->slideshow_type == $key) echo 'selected="selected"'; ?>><?php echo __($effect, BWG()->prefix); ?></option>
                              <?php
                            }
                            ?>
                          </select>
                        </div>
                        <p class="description"><?php _e('Select the animation effect for your slideshow.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="slideshow_effect_duration"><?php _e('Effect duration', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="slideshow_effect_duration" id="slideshow_effect_duration" value="<?php echo BWG()->options->slideshow_effect_duration; ?>" min="0" step="0.1" /><span>sec.</span>
                        </div>
                        <p class="description"><?php _e('Set the duration of your slideshow animation effect.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="slideshow_interval"><?php _e('Time interval', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="slideshow_interval" id="slideshow_interval" value="<?php echo BWG()->options->slideshow_interval; ?>" min="0" /><span>sec.</span>
                        </div>
                        <p class="description"><?php _e('Specify the time interval between slides in Photo Gallery\'s Slideshow view.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="slideshow_width"><?php _e('Slideshow dimensions', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="slideshow_width" id="slideshow_width" value="<?php echo BWG()->options->slideshow_width; ?>" min="0" /><span>px</span>
                          <input type="number" name="slideshow_height" id="slideshow_height" value="<?php echo BWG()->options->slideshow_height; ?>" min="0" /><span>px</span>
                        </div>
                        <p class="description"><?php _e('Set the default dimensions of your slideshow galleries.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="slideshow_sort_by"><?php _e('Order by', BWG()->prefix); ?></label>
                        <select name="slideshow_sort_by" id="slideshow_sort_by">
                          <option value="order" <?php if (BWG()->options->slideshow_sort_by == 'order') echo 'selected="selected"'; ?>><?php _e('Order', BWG()->prefix); ?></option>
                          <option value="alt" <?php if (BWG()->options->slideshow_sort_by == 'alt') echo 'selected="selected"'; ?>><?php _e('Title', BWG()->prefix); ?></option>
                          <option value="date" <?php if (BWG()->options->slideshow_sort_by == 'date') echo 'selected="selected"'; ?>><?php _e('Date', BWG()->prefix); ?></option>
                          <option value="filename" <?php if (BWG()->options->slideshow_sort_by == 'filename') echo 'selected="selected"'; ?>><?php _e('Filename', BWG()->prefix); ?></option>
                          <option value="size" <?php if (BWG()->options->slideshow_sort_by == 'size') echo 'selected="selected"'; ?>><?php _e('Size', BWG()->prefix); ?></option>
                          <option value="filetype" <?php if (BWG()->options->slideshow_sort_by == 'filetype') echo 'selected="selected"'; ?>><?php _e('Type', BWG()->prefix); ?></option>
                          <option value="resolution" <?php if (BWG()->options->slideshow_sort_by == 'resolution') echo 'selected="selected"'; ?>><?php _e('Resolution', BWG()->prefix); ?></option>
                          <option value="random" <?php if (BWG()->options->slideshow_sort_by == 'random') echo 'selected="selected"'; ?>><?php _e('Random', BWG()->prefix); ?></option>
                        </select>
                        <p class="description"><?php _e("Select the parameter based on which the gallery images will sorted, e.g. Title.", BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Order direction', BWG()->prefix); ?></label>
                        <input type="radio" name="slideshow_order_by" id="slideshow_order_by_1" value="asc" <?php if (BWG()->options->slideshow_order_by == 'asc') echo 'checked="checked"'; ?> /><label for="slideshow_order_by_1" class="wd-radio-label"><?php _e('Ascending', BWG()->prefix); ?></label>
                        <input type="radio" name="slideshow_order_by" id="slideshow_order_by_0" value="desc" <?php if (BWG()->options->slideshow_order_by == 'desc') echo 'checked="checked"'; ?> /><label for="slideshow_order_by_0" class="wd-radio-label"><?php _e('Descending', BWG()->prefix); ?></label>
                        <p class="description"><?php _e("Set the ordering direction for gallery images, ascending or descending.", BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Enable autoplay', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="slideshow_enable_autoplay" id="slideshow_enable_autoplay_yes" value="1" <?php if (BWG()->options->slideshow_enable_autoplay) echo 'checked="checked"'; ?> /><label for="slideshow_enable_autoplay_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="slideshow_enable_autoplay" id="slideshow_enable_autoplay_no" value="0" <?php if (!BWG()->options->slideshow_enable_autoplay) echo 'checked="checked"'; ?> /><label for="slideshow_enable_autoplay_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Activate this option to autoplay slideshow galleries.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Enable shuffle', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="slideshow_enable_shuffle" id="slideshow_enable_shuffle_yes" value="1" <?php if (BWG()->options->slideshow_enable_shuffle) echo 'checked="checked"'; ?> /><label for="slideshow_enable_shuffle_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="slideshow_enable_shuffle" id="slideshow_enable_shuffle_no" value="0" <?php if (!BWG()->options->slideshow_enable_shuffle) echo 'checked="checked"'; ?> /><label for="slideshow_enable_shuffle_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('The slideshow images will be shuffled in case this setting is enabled.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Enable control buttons', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="slideshow_enable_ctrl" id="slideshow_enable_ctrl_yes" value="1" <?php if (BWG()->options->slideshow_enable_ctrl) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('', 'tr_autohide_slideshow_navigation', 'slideshow_enable_ctrl_yes');" /><label for="slideshow_enable_ctrl_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="slideshow_enable_ctrl" id="slideshow_enable_ctrl_no" value="0" <?php if (!BWG()->options->slideshow_enable_ctrl) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_autohide_slideshow_navigation', 'slideshow_enable_ctrl_no');" /><label for="slideshow_enable_ctrl_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Enable this option to show control buttons on your slideshow galleries.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_autohide_slideshow_navigation">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show Next / Previous buttons', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="autohide_slideshow_navigation" id="autohide_slideshow_navigation_1" value="1" <?php if (BWG()->options->autohide_slideshow_navigation) echo 'checked="checked"'; ?> /><label for="autohide_slideshow_navigation_1" class="wd-radio-label"><?php _e('On hover', BWG()->prefix); ?></label>
                          <input type="radio" name="autohide_slideshow_navigation" id="autohide_slideshow_navigation_0" value="0" <?php if (!BWG()->options->autohide_slideshow_navigation) echo 'checked="checked"'; ?> /><label for="autohide_slideshow_navigation_0" class="wd-radio-label"><?php _e('Always', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Display Next/Previous buttons on your slideshow galleries activating this setting.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Enable slideshow filmstrip', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="slideshow_enable_filmstrip" id="slideshow_enable_filmstrip_yes" value="1" <?php if (BWG()->options->slideshow_enable_filmstrip) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('', 'tr_slideshow_filmstrip_height', 'slideshow_enable_filmstrip_yes')" /><label for="slideshow_enable_filmstrip_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="slideshow_enable_filmstrip" id="slideshow_enable_filmstrip_no" value="0" <?php if (!BWG()->options->slideshow_enable_filmstrip) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_slideshow_filmstrip_height', 'slideshow_enable_filmstrip_no')" /><label for="slideshow_enable_filmstrip_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Add a filmstrip with image thumbnails to your slideshow galleries by enabling this option.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_slideshow_filmstrip_height">
                      <div class="wd-group">
                        <label class="wd-label" for="slideshow_filmstrip_height"><?php _e('Slideshow filmstrip size', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="number" name="slideshow_filmstrip_height" id="slideshow_filmstrip_height" value="<?php echo BWG()->options->slideshow_filmstrip_height; ?>" min="0" /><span>px</span>
                        </div>
                        <p class="description"><?php _e('Set the size of your filmstrip. If the filmstrip is horizontal, this indicates its height, whereas for vertical filmstrips it sets the width.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show image title', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="slideshow_enable_title" id="slideshow_enable_title_yes" value="1" <?php if (BWG()->options->slideshow_enable_title) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('', 'tr_slideshow_title_position', 'slideshow_enable_title_yes')" /><label for="slideshow_enable_title_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="slideshow_enable_title" id="slideshow_enable_title_no" value="0" <?php if (!BWG()->options->slideshow_enable_title) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_slideshow_title_position', 'slideshow_enable_title_no')" /><label for="slideshow_enable_title_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Enable this setting to display titles of images in Slideshow view.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_slideshow_title_position">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Title position', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <table class="bwg_position_table">
                            <tbody>
                            <tr>
                              <td><input type="radio" value="top-left" id="slideshow_title_topLeft" name="slideshow_title_position" <?php if (BWG()->options->slideshow_title_position == "top-left") echo 'checked="checked"'; ?>></td>
                              <td><input type="radio" value="top-center" id="slideshow_title_topCenter" name="slideshow_title_position" <?php if (BWG()->options->slideshow_title_position == "top-center") echo 'checked="checked"'; ?>></td>
                              <td><input type="radio" value="top-right" id="slideshow_title_topRight" name="slideshow_title_position" <?php if (BWG()->options->slideshow_title_position == "top-right") echo 'checked="checked"'; ?>></td>
                            </tr>
                            <tr>
                              <td><input type="radio" value="middle-left" id="slideshow_title_midLeft" name="slideshow_title_position" <?php if (BWG()->options->slideshow_title_position == "middle-left") echo 'checked="checked"'; ?>></td>
                              <td><input type="radio" value="middle-center" id="slideshow_title_midCenter" name="slideshow_title_position" <?php if (BWG()->options->slideshow_title_position == "middle-center") echo 'checked="checked"'; ?>></td>
                              <td><input type="radio" value="middle-right" id="slideshow_title_midRight" name="slideshow_title_position" <?php if (BWG()->options->slideshow_title_position == "middle-right") echo 'checked="checked"'; ?>></td>
                            </tr>
                            <tr>
                              <td><input type="radio" value="bottom-left" id="slideshow_title_botLeft" name="slideshow_title_position" <?php if (BWG()->options->slideshow_title_position == "bottom-left") echo 'checked="checked"'; ?>></td>
                              <td><input type="radio" value="bottom-center" id="slideshow_title_botCenter" name="slideshow_title_position" <?php if (BWG()->options->slideshow_title_position == "bottom-center") echo 'checked="checked"'; ?>></td>
                              <td><input type="radio" value="bottom-right" id="slideshow_title_botRight" name="slideshow_title_position" <?php if (BWG()->options->slideshow_title_position == "bottom-right") echo 'checked="checked"'; ?>></td>
                            </tr>
                            </tbody>
                          </table>
                        </div>
                        <p class="description"><?php _e('Set the position of image titles in Slideshow view.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_slideshow_full_width_title">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Full width title', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="slideshow_title_full_width" id="slideshow_title_full_width_1" value="1" <?php if (BWG()->options->slideshow_title_full_width) echo 'checked="checked"'; ?>  /><label for="slideshow_title_full_width_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="slideshow_title_full_width" id="slideshow_title_full_width_0" value="0" <?php if (!BWG()->options->slideshow_title_full_width) echo 'checked="checked"'; ?>  /><label for="slideshow_title_full_width_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Display image title based on the slideshow dimensions.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show image description', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="slideshow_enable_description" id="slideshow_enable_description_yes" value="1" <?php if (BWG()->options->slideshow_enable_description) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('', 'tr_slideshow_description_position', 'slideshow_enable_description_yes')" /><label for="slideshow_enable_description_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="slideshow_enable_description" id="slideshow_enable_description_no" value="0" <?php if (!BWG()->options->slideshow_enable_description) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_slideshow_description_position', 'slideshow_enable_description_no')" /><label for="slideshow_enable_description_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Enable this setting to show descriptions of images in Slideshow view.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_slideshow_description_position">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Description position', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <table class="bwg_position_table">
                            <tbody>
                            <tr>
                              <td><input type="radio" value="top-left" id="slideshow_description_topLeft" name="slideshow_description_position" <?php if (BWG()->options->slideshow_description_position == "top-left") echo 'checked="checked"'; ?>></td>
                              <td><input type="radio" value="top-center" id="slideshow_description_topCenter" name="slideshow_description_position" <?php if (BWG()->options->slideshow_description_position == "top-center") echo 'checked="checked"'; ?>></td>
                              <td><input type="radio" value="top-right" id="slideshow_description_topRight" name="slideshow_description_position" <?php if (BWG()->options->slideshow_description_position == "top-right") echo 'checked="checked"'; ?>></td>
                            </tr>
                            <tr>
                              <td><input type="radio" value="middle-left" id="slideshow_description_midLeft" name="slideshow_description_position" <?php if (BWG()->options->slideshow_description_position == "middle-left") echo 'checked="checked"'; ?>></td>
                              <td><input type="radio" value="middle-center" id="slideshow_description_midCenter" name="slideshow_description_position" <?php if (BWG()->options->slideshow_description_position == "middle-center") echo 'checked="checked"'; ?>></td>
                              <td><input type="radio" value="middle-right" id="slideshow_description_midRight" name="slideshow_description_position" <?php if (BWG()->options->slideshow_description_position == "middle-right") echo 'checked="checked"'; ?>></td>
                            </tr>
                            <tr>
                              <td><input type="radio" value="bottom-left" id="slideshow_description_botLeft" name="slideshow_description_position" <?php if (BWG()->options->slideshow_description_position == "bottom-left") echo 'checked="checked"'; ?>></td>
                              <td><input type="radio" value="bottom-center" id="slideshow_description_botCenter" name="slideshow_description_position" <?php if (BWG()->options->slideshow_description_position == "bottom-center") echo 'checked="checked"'; ?>></td>
                              <td><input type="radio" value="bottom-right" id="slideshow_description_botRight" name="slideshow_description_position" <?php if (BWG()->options->slideshow_description_position == "bottom-right") echo 'checked="checked"'; ?>></td>
                            </tr>
                            </tbody>
                          </table>
                        </div>
                        <p class="description"><?php _e('Set the position of image descriptions in Slideshow view.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Enable slideshow Music', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="slideshow_enable_music" id="slideshow_enable_music_yes" value="1" <?php if (BWG()->options->slideshow_enable_music) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('', 'tr_slideshow_music_url', 'slideshow_enable_music_yes')" /><label for="slideshow_enable_music_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="slideshow_enable_music" id="slideshow_enable_music_no" value="0" <?php if (!BWG()->options->slideshow_enable_music) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_slideshow_music_url', 'slideshow_enable_music_no')"  /><label for="slideshow_enable_music_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Enabling this option, you can have music playing along with your slideshow.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_slideshow_music_url">
                      <div class="wd-group">
                        <label class="wd-label" for="slideshow_audio_url"><?php _e('Audio URL', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="text" id="slideshow_audio_url" name="slideshow_audio_url" value="<?php echo BWG()->options->slideshow_audio_url; ?>" />
                        </div>
                        <p class="description"><?php _e('Provide the absolute URL of the audio file you would like to play with your slideshow.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Enable bulk download button', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="slideshow_gallery_download" id="slideshow_gallery_download_1" value="1" <?php if (BWG()->options->slideshow_gallery_download) echo 'checked="checked"'; ?> /><label for="slideshow_gallery_download_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="slideshow_gallery_download" id="slideshow_gallery_download_0" value="0" <?php if (!BWG()->options->slideshow_gallery_download) echo 'checked="checked"'; ?> /><label for="slideshow_gallery_download_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Activate this setting to let users download all images of your gallery with a click.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                  </div>
                </div>
                <div id="image_browser_options" class="gallery_options wd-box-content wd-width-100 bwg-flex-wrap">
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="image_browser_width"><?php _e('Image width', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="image_browser_width" id="image_browser_width" value="<?php echo BWG()->options->image_browser_width; ?>" min="0" /><span>px</span>
                        </div>
                        <p class="description"><?php _e('Specify the default width of images in Image Browser view.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show image title', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="image_browser_title_enable" id="image_browser_title_enable_1" value="1" <?php if (BWG()->options->image_browser_title_enable) echo 'checked="checked"'; ?> /><label for="image_browser_title_enable_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="image_browser_title_enable" id="image_browser_title_enable_0" value="0" <?php if (!BWG()->options->image_browser_title_enable) echo 'checked="checked"'; ?> /><label for="image_browser_title_enable_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show image description', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="image_browser_description_enable" id="image_browser_description_enable_1" value="1" <?php if (BWG()->options->image_browser_description_enable) echo 'checked="checked"'; ?> /><label for="image_browser_description_enable_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="image_browser_description_enable" id="image_browser_description_enable_0" value="0" <?php if (!BWG()->options->image_browser_description_enable) echo 'checked="checked"'; ?> /><label for="image_browser_description_enable_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Enable this setting to display titles of images in Image Browser view.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="image_browser_sort_by"><?php _e('Order by', BWG()->prefix); ?></label>
                        <select name="image_browser_sort_by" id="image_browser_sort_by">
                          <option value="order" <?php if (BWG()->options->image_browser_sort_by == 'order') echo 'selected="selected"'; ?>><?php _e('Order', BWG()->prefix); ?></option>
                          <option value="alt" <?php if (BWG()->options->image_browser_sort_by == 'alt') echo 'selected="selected"'; ?>><?php _e('Title', BWG()->prefix); ?></option>
                          <option value="date" <?php if (BWG()->options->image_browser_sort_by == 'date') echo 'selected="selected"'; ?>><?php _e('Date', BWG()->prefix); ?></option>
                          <option value="filename" <?php if (BWG()->options->image_browser_sort_by == 'filename') echo 'selected="selected"'; ?>><?php _e('Filename', BWG()->prefix); ?></option>
                          <option value="size" <?php if (BWG()->options->image_browser_sort_by == 'size') echo 'selected="selected"'; ?>><?php _e('Size', BWG()->prefix); ?></option>
                          <option value="filetype" <?php if (BWG()->options->image_browser_sort_by == 'filetype') echo 'selected="selected"'; ?>><?php _e('Type', BWG()->prefix); ?></option>
                          <option value="resolution" <?php if (BWG()->options->image_browser_sort_by == 'resolution') echo 'selected="selected"'; ?>><?php _e('Resolution', BWG()->prefix); ?></option>
                          <option value="random" <?php if (BWG()->options->image_browser_sort_by == 'random') echo 'selected="selected"'; ?>><?php _e('Random', BWG()->prefix); ?></option>
                        </select>
                        <p class="description"><?php _e("Select the parameter based on which the gallery images will sorted, e.g. Title.", BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Order direction', BWG()->prefix); ?></label>
                        <input type="radio" name="image_browser_order_by" id="image_browser_order_by_1" value="asc" <?php if (BWG()->options->image_browser_order_by == 'asc') echo 'checked="checked"'; ?> /><label for="image_browser_order_by_1" class="wd-radio-label"><?php _e('Ascending', BWG()->prefix); ?></label>
                        <input type="radio" name="image_browser_order_by" id="image_browser_order_by_0" value="desc" <?php if (BWG()->options->image_browser_order_by == 'desc') echo 'checked="checked"'; ?> /><label for="image_browser_order_by_0" class="wd-radio-label"><?php _e('Descending', BWG()->prefix); ?></label>
                        <p class="description"><?php _e("Set the ordering direction for gallery images, ascending or descending.", BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show search box', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="image_browser_show_search_box" id="image_browser_show_search_box_1" value="1" <?php if (BWG()->options->image_browser_show_search_box) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('', 'tr_image_browser_search_box_width', 'image_browser_show_search_box_1'); bwg_enable_disable('', 'tr_image_browser_search_box_placeholder', 'image_browser_show_search_box_1')" /><label for="image_browser_show_search_box_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="image_browser_show_search_box" id="image_browser_show_search_box_0" value="0" <?php if (!BWG()->options->image_browser_show_search_box) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_image_browser_search_box_width', 'image_browser_show_search_box_0'); bwg_enable_disable('none', 'tr_image_browser_search_box_placeholder', 'image_browser_show_search_box_0')" /><label for="image_browser_show_search_box_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Enable this option to display a search box with your gallery or gallery group.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_image_browser_search_box_placeholder">
                      <div class="wd-group">
                        <label class="wd-label" for="image_browser_placeholder"><?php _e('Add placeholder to search', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="text" name="image_browser_placeholder" id="image_browser_placeholder" value="<?php echo BWG()->options->image_browser_placeholder; ?>"  />
                        </div>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_image_browser_search_box_width">
                      <div class="wd-group">
                        <label class="wd-label" for="image_browser_search_box_width"><?php _e('Search box width', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="image_browser_search_box_width" id="image_browser_search_box_width" value="<?php echo BWG()->options->image_browser_search_box_width; ?>" min="0" /><span>px</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show gallery title', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="image_browser_show_gallery_title" id="image_browser_thumb_name_yes" value="1" <?php if (BWG()->options->image_browser_show_gallery_title) echo 'checked="checked"'; ?> /><label for="image_browser_thumb_name_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="image_browser_show_gallery_title" id="image_browser_thumb_name_no" value="0"  <?php if (!BWG()->options->image_browser_show_gallery_title) echo 'checked="checked"'; ?> /><label for="image_browser_thumb_name_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Allow users to see the titles of your galleries by enabling this setting.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show gallery description', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="image_browser_show_gallery_description" id="image_browser_show_gallery_description_1" value="1" <?php if (BWG()->options->image_browser_show_gallery_description) echo 'checked="checked"'; ?> /><label for="image_browser_show_gallery_description_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="image_browser_show_gallery_description" id="image_browser_show_gallery_description_0" value="0" <?php if (!BWG()->options->image_browser_show_gallery_description) echo 'checked="checked"'; ?> /><label for="image_browser_show_gallery_description_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Display the descriptions of your galleries by activating this option.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Enable bulk download button', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="image_browser_gallery_download" id="image_browser_gallery_download_1" value="1" <?php if (BWG()->options->image_browser_gallery_download) echo 'checked="checked"'; ?> /><label for="image_browser_gallery_download_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="image_browser_gallery_download" id="image_browser_gallery_download_0" value="0" <?php if (!BWG()->options->image_browser_gallery_download) echo 'checked="checked"'; ?> /><label for="image_browser_gallery_download_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Activate this setting to let users download all images of your gallery with a click.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                  </div>
                </div>
                <div id="blog_style_options" class="gallery_options wd-box-content wd-width-100 bwg-flex-wrap">
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="blog_style_width"><?php _e('Image width', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="number" name="blog_style_width" id="blog_style_width" value="<?php echo BWG()->options->blog_style_width; ?>" min="0" /><span>px</span>
                        </div>
                        <p class="description"><?php _e('Specify the default width of images in Blog Style view.', BWG()->prefix); ?></p>
                      </div>
                      <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Pagination', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="blog_style_enable_page" id="blog_style_enable_page_0" value="0" <?php if (BWG()->options->blog_style_enable_page == '0') echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_blog_style_load_more_image_count', 'blog_style_enable_page_0'); bwg_pagination_description(this);" /><label for="blog_style_enable_page_0" class="wd-radio-label"><?php _e('None', BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="blog_style_enable_page" id="blog_style_enable_page_1" value="1" <?php if (BWG()->options->blog_style_enable_page == '1') echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_blog_style_load_more_image_count', 'blog_style_enable_page_1'); bwg_pagination_description(this);" /><label for="blog_style_enable_page_1" class="wd-radio-label"><?php _e('Simple', BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="blog_style_enable_page" id="blog_style_enable_page_2" value="2" <?php if (BWG()->options->blog_style_enable_page == '2') echo 'checked="checked"'; ?> onClick="bwg_enable_disable('', 'tr_blog_style_load_more_image_count', 'blog_style_enable_page_2'); bwg_pagination_description(this);" /><label for="blog_style_enable_page_2" class="wd-radio-label"><?php _e('Load More', BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="blog_style_enable_page" id="blog_style_enable_page_3" value="3" <?php if (BWG()->options->blog_style_enable_page == '3') echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_blog_style_load_more_image_count', 'blog_style_enable_page_3'); bwg_pagination_description(this);" /><label for="blog_style_enable_page_3" class="wd-radio-label"><?php _e('Scroll Load', BWG()->prefix); ?></label>
                        </div>
                        <p class="description" id="blog_style_enable_page_0_description"><?php _e('This option removes all types of pagination from your galleries.', BWG()->prefix); ?></p>
                        <p class="description" id="blog_style_enable_page_1_description"><?php _e('Activating this option will add page numbers and next/previous buttons to your galleries.', BWG()->prefix); ?></p>
                        <p class="description" id="blog_style_enable_page_2_description"><?php _e('Adding a Load More button, you can let users display a new set of images from your galleries.', BWG()->prefix); ?></p>
                        <p class="description" id="blog_style_enable_page_3_description"><?php _e('With this option, users can load new images of your galleries simply by scrolling down.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_blog_style_images_per_page">
                      <div class="wd-group">
                        <label class="wd-label" for="blog_style_images_per_page"><?php _e('Images per page', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="number" name="blog_style_images_per_page" id="blog_style_images_per_page" value="<?php echo BWG()->options->blog_style_images_per_page; ?>" min="0" />
                        </div>
                        <p class="description"><?php _e('Select the number of images displayed per page in Blog Style view.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_blog_style_load_more_image_count">
                      <div class="wd-group">
                        <label class="wd-label" for="blog_style_load_more_image_count"><?php _e('Images per load', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="blog_style_load_more_image_count" id="blog_style_load_more_image_count" value="<?php echo BWG()->options->blog_style_load_more_image_count; ?>" min="0" />
                        </div>
                        <p class="description"><?php _e('Specify the number of images to display per load on galleries.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="blog_style_sort_by"><?php _e('Order by', BWG()->prefix); ?></label>
                        <select name="blog_style_sort_by" id="blog_style_sort_by">
                          <option value="order" <?php if (BWG()->options->blog_style_sort_by == 'order') echo 'selected="selected"'; ?>><?php _e('Order', BWG()->prefix); ?></option>
                          <option value="alt" <?php if (BWG()->options->blog_style_sort_by == 'alt') echo 'selected="selected"'; ?>><?php _e('Title', BWG()->prefix); ?></option>
                          <option value="date" <?php if (BWG()->options->blog_style_sort_by == 'date') echo 'selected="selected"'; ?>><?php _e('Date', BWG()->prefix); ?></option>
                          <option value="filename" <?php if (BWG()->options->blog_style_sort_by == 'filename') echo 'selected="selected"'; ?>><?php _e('Filename', BWG()->prefix); ?></option>
                          <option value="size" <?php if (BWG()->options->blog_style_sort_by == 'size') echo 'selected="selected"'; ?>><?php _e('Size', BWG()->prefix); ?></option>
                          <option value="filetype" <?php if (BWG()->options->blog_style_sort_by == 'filetype') echo 'selected="selected"'; ?>><?php _e('Type', BWG()->prefix); ?></option>
                          <option value="resolution" <?php if (BWG()->options->blog_style_sort_by == 'resolution') echo 'selected="selected"'; ?>><?php _e('Resolution', BWG()->prefix); ?></option>
                          <option value="random" <?php if (BWG()->options->blog_style_sort_by == 'random') echo 'selected="selected"'; ?>><?php _e('Random', BWG()->prefix); ?></option>
                        </select>
                        <p class="description"><?php _e("Select the parameter based on which the gallery images will sorted, e.g. Title.", BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Order direction', BWG()->prefix); ?></label>
                        <input type="radio" name="blog_style_order_by" id="blog_style_order_by_1" value="asc" <?php if (BWG()->options->blog_style_order_by == 'asc') echo 'checked="checked"'; ?> /><label for="blog_style_order_by_1" class="wd-radio-label"><?php _e('Ascending', BWG()->prefix); ?></label>
                        <input type="radio" name="blog_style_order_by" id="blog_style_order_by_0" value="desc" <?php if (BWG()->options->blog_style_order_by == 'desc') echo 'checked="checked"'; ?> /><label for="blog_style_order_by_0" class="wd-radio-label"><?php _e('Descending', BWG()->prefix); ?></label>
                        <p class="description"><?php _e("Set the ordering direction for gallery images, ascending or descending.", BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show search box', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="blog_style_show_search_box" id="blog_style_show_search_box_1" value="1" <?php if (BWG()->options->blog_style_show_search_box) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('', 'tr_blog_style_search_box_width', 'blog_style_show_search_box_1'); bwg_enable_disable('', 'tr_blog_style_search_box_placeholder', 'blog_style_show_search_box_1')" /><label for="blog_style_show_search_box_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="blog_style_show_search_box" id="blog_style_show_search_box_0" value="0" <?php if (!BWG()->options->blog_style_show_search_box) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_blog_style_search_box_width', 'blog_style_show_search_box_0'); bwg_enable_disable('none', 'tr_blog_style_search_box_placeholder', 'blog_style_show_search_box_0')" /><label for="blog_style_show_search_box_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Enable this option to display a search box with your gallery or gallery group.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_blog_style_search_box_placeholder">
                      <div class="wd-group">
                        <label class="wd-label" for="blog_style_placeholder"><?php _e('Add placeholder to search', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="text" name="blog_style_placeholder" id="blog_style_placeholder" value="<?php echo BWG()->options->blog_style_placeholder; ?>"  />
                        </div>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_blog_style_search_box_width">
                      <div class="wd-group">
                        <label class="wd-label" for="blog_style_search_box_width"><?php _e('Search box width', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="blog_style_search_box_width" id="blog_style_search_box_width" value="<?php echo BWG()->options->blog_style_search_box_width; ?>" min="0" /><span>px</span>
                        </div>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show "Order by" dropdown list', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="blog_style_show_sort_images" id="blog_style_show_sort_images_1" value="1" <?php if (BWG()->options->blog_style_show_sort_images) echo 'checked="checked"'; ?> /><label for="blog_style_show_sort_images_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="blog_style_show_sort_images" id="blog_style_show_sort_images_0" value="0" <?php if (!BWG()->options->blog_style_show_sort_images) echo 'checked="checked"'; ?> /><label for="blog_style_show_sort_images_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Activate this dropdown box to let users browse your gallery images with different ordering options.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show tag box', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="blog_style_show_tag_box" id="blog_style_show_tag_box_1" value="1" <?php if (BWG()->options->blog_style_show_tag_box) echo 'checked="checked"'; ?> /><label for="blog_style_show_tag_box_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="blog_style_show_tag_box" id="blog_style_show_tag_box_0" value="0" <?php if (!BWG()->options->blog_style_show_tag_box) echo 'checked="checked"'; ?> /><label for="blog_style_show_tag_box_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Enable Tag Box to allow users to filter the gallery images by their tags.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show gallery title', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="blog_style_show_gallery_title" id="blog_style_thumb_name_yes" value="1" <?php if (BWG()->options->blog_style_show_gallery_title) echo 'checked="checked"'; ?> /><label for="blog_style_thumb_name_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="blog_style_show_gallery_title" id="blog_style_thumb_name_no" value="0"  <?php if (!BWG()->options->blog_style_show_gallery_title) echo 'checked="checked"'; ?> /><label for="blog_style_thumb_name_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Allow users to see the titles of your galleries by enabling this setting.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show gallery description', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="blog_style_show_gallery_description" id="blog_style_show_gallery_description_1" value="1" <?php if (BWG()->options->blog_style_show_gallery_description) echo 'checked="checked"'; ?> /><label for="blog_style_show_gallery_description_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="blog_style_show_gallery_description" id="blog_style_show_gallery_description_0" value="0" <?php if (!BWG()->options->blog_style_show_gallery_description) echo 'checked="checked"'; ?> /><label for="blog_style_show_gallery_description_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Display the descriptions of your galleries by activating this option.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show image title', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="blog_style_title_enable" id="blog_style_title_enable_1" value="1" <?php if (BWG()->options->blog_style_title_enable) echo 'checked="checked"'; ?> /><label for="blog_style_title_enable_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="blog_style_title_enable" id="blog_style_title_enable_0" value="0" <?php if (!BWG()->options->blog_style_title_enable) echo 'checked="checked"'; ?> /><label for="blog_style_title_enable_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Enable this setting to display titles of images in Blog Style view.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show image description', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="blog_style_description_enable" id="blog_style_description_enable_1" value="1" <?php if (BWG()->options->blog_style_description_enable) echo 'checked="checked"'; ?> /><label for="blog_style_description_enable_1" class="wd-radio-label"><?php echo _e('Yes', BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="blog_style_description_enable" id="blog_style_description_enable_0" value="0" <?php if (!BWG()->options->blog_style_description_enable) echo 'checked="checked"'; ?> /><label for="blog_style_description_enable_0" class="wd-radio-label"><?php echo _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Enable this setting to show descriptions of images in Blog Style view.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Enable bulk download button', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="blog_style_gallery_download" id="blog_style_gallery_download_1" value="1" <?php if (BWG()->options->blog_style_gallery_download) echo 'checked="checked"'; ?> /><label for="blog_style_gallery_download_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="blog_style_gallery_download" id="blog_style_gallery_download_0" value="0" <?php if (!BWG()->options->blog_style_gallery_download) echo 'checked="checked"'; ?> /><label for="blog_style_gallery_download_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Activate this setting to let users download all images of your gallery with a click.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                  </div>
                </div>
                <div id="carousel_options" class="gallery_options wd-box-content wd-width-100 bwg-flex-wrap">
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="carousel_image_column_number"><?php _e('Max. number of images', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="number" name="carousel_image_column_number" id="carousel_image_column_number" value="<?php echo BWG()->options->carousel_image_column_number; ?>" min="0" /><span>sec.</span>
                        </div>
                        <p class="description"><?php _e('Set the maximum number of images that are shown with Carousel display.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="carousel_width"><?php _e('Image dimensions', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="number" name="carousel_width" id="carousel_width" value="<?php echo BWG()->options->carousel_width; ?>" min="0" /><span>x</span>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="number" name="carousel_height" id="carousel_height" value="<?php echo BWG()->options->carousel_height; ?>" min="0" /><span>px</span>
                        </div>
                        <p class="description"><?php _e('Specify the dimensions of carousel images in pixels.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="carousel_image_par"><?php _e('Carousel image ratio', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="number" name="carousel_image_par" id="carousel_image_par" value="<?php echo BWG()->options->carousel_image_par; ?>" min="0" max="1" step="0.01" />
                        </div>
                        <p class="description"><?php _e('This option defines the dimensions ratio of images in Carousel view. E.g. for square images set ratio to 1.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="carousel_r_width"><?php _e('Fixed width', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="number" name="carousel_r_width" id="carousel_r_width" value="<?php echo BWG()->options->carousel_r_width; ?>" min="0" /><span>px</span>
                        </div>
                        <p class="description"><?php _e('Specify the fixed width of Carousel gallery container.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="carousel_sort_by"><?php _e('Order by', BWG()->prefix); ?></label>
                        <select name="carousel_sort_by" id="carousel_sort_by">
                          <option value="order" <?php if (BWG()->options->carousel_sort_by == 'order') echo 'selected="selected"'; ?>><?php _e('Order', BWG()->prefix); ?></option>
                          <option value="alt" <?php if (BWG()->options->carousel_sort_by == 'alt') echo 'selected="selected"'; ?>><?php _e('Title', BWG()->prefix); ?></option>
                          <option value="date" <?php if (BWG()->options->carousel_sort_by == 'date') echo 'selected="selected"'; ?>><?php _e('Date', BWG()->prefix); ?></option>
                          <option value="filename" <?php if (BWG()->options->carousel_sort_by == 'filename') echo 'selected="selected"'; ?>><?php _e('Filename', BWG()->prefix); ?></option>
                          <option value="size" <?php if (BWG()->options->carousel_sort_by == 'size') echo 'selected="selected"'; ?>><?php _e('Size', BWG()->prefix); ?></option>
                          <option value="filetype" <?php if (BWG()->options->carousel_sort_by == 'filetype') echo 'selected="selected"'; ?>><?php _e('Type', BWG()->prefix); ?></option>
                          <option value="resolution" <?php if (BWG()->options->carousel_sort_by == 'resolution') echo 'selected="selected"'; ?>><?php _e('Resolution', BWG()->prefix); ?></option>
                          <option value="random" <?php if (BWG()->options->carousel_sort_by == 'random') echo 'selected="selected"'; ?>><?php _e('Random', BWG()->prefix); ?></option>
                        </select>
                        <p class="description"><?php _e("Select the parameter based on which the gallery images will sorted, e.g. Title.", BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Order direction', BWG()->prefix); ?></label>
                        <input type="radio" name="carousel_order_by" id="carousel_order_by_1" value="asc" <?php if (BWG()->options->carousel_order_by == 'asc') echo 'checked="checked"'; ?> /><label for="carousel_order_by_1" class="wd-radio-label"><?php _e('Ascending', BWG()->prefix); ?></label>
                        <input type="radio" name="carousel_order_by" id="carousel_order_by_0" value="desc" <?php if (BWG()->options->carousel_order_by == 'desc') echo 'checked="checked"'; ?> /><label for="carousel_order_by_0" class="wd-radio-label"><?php _e('Descending', BWG()->prefix); ?></label>
                        <p class="description"><?php _e("Set the ordering direction for gallery images, ascending or descending.", BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Enable autoplay', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="carousel_enable_autoplay" id="carousel_enable_autoplay_yes" value="1" <?php if (BWG()->options->carousel_enable_autoplay) echo 'checked="checked"'; ?> /><label for="carousel_enable_autoplay_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="carousel_enable_autoplay" id="carousel_enable_autoplay_no" value="0" <?php if (!BWG()->options->carousel_enable_autoplay) echo 'checked="checked"'; ?> /><label for="carousel_enable_autoplay_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Activate this option to autoplay Carousel galleries.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="carousel_interval"><?php _e('Time interval', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="number" name="carousel_interval" id="carousel_interval" value="<?php echo BWG()->options->carousel_interval; ?>" min="0" step="0.1" /><span>sec.</span>
                        </div>
                        <p class="description"><?php _e('Specify the time interval between rotations in Photo Gallery\'s Carousel view.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show image title', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="carousel_enable_title" id="carousel_enable_title_yes" value="1" <?php if (BWG()->options->carousel_enable_title) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('', 'tr_carousel_title_position', 'carousel_enable_title_yes')" /><label for="carousel_enable_title_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="carousel_enable_title" id="carousel_enable_title_no" value="0" <?php if (!BWG()->options->carousel_enable_title) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_carousel_title_position', 'carousel_enable_title_no')" /><label for="carousel_enable_title_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Display image titles in Photo Gallery Carousel view by activating this option.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Container fit', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="carousel_fit_containerWidth" id="carousel_fit_containerWidth_yes" value="1" <?php if (BWG()->options->carousel_fit_containerWidth) echo 'checked="checked"'; ?> /><label for="carousel_fit_containerWidth_yes" class="wd-radio-label"><?php _e("Yes", BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="carousel_fit_containerWidth" id="carousel_fit_containerWidth_no" value="0" <?php if (!BWG()->options->carousel_fit_containerWidth) echo 'checked="checked"'; ?> /><label for="carousel_fit_containerWidth_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Enabling this setting fits the images inside their container on Carousel galleries with fixed width.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Next/Previous buttons', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="carousel_prev_next_butt" id="carousel_prev_next_butt_yes" value="1" <?php if (BWG()->options->carousel_prev_next_butt) echo 'checked="checked"'; ?> /><label for="carousel_prev_next_butt_yes" class="wd-radio-label"><?php _e("Yes", BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="carousel_prev_next_butt" id="carousel_prev_next_butt_no" value="0" <?php if (!BWG()->options->carousel_prev_next_butt) echo 'checked="checked"'; ?> /><label for="carousel_prev_next_butt_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Enable this setting to display Next/Previous buttons on your galleries with Carousel view.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Play/Pause buttons', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="carousel_play_pause_butt" id="carousel_play_pause_butt_yes" value="1" <?php if (BWG()->options->carousel_play_pause_butt) echo 'checked="checked"'; ?> /><label for="carousel_play_pause_butt_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="carousel_play_pause_butt" id="carousel_play_pause_butt_no" value="0" <?php if (!BWG()->options->carousel_play_pause_butt) echo 'checked="checked"'; ?> /><label for="carousel_play_pause_butt_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Activate this to show Play/Pause buttons on your Carousel galleries.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Enable bulk download button', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="carousel_gallery_download" id="carousel_gallery_download_1" value="1" <?php if (BWG()->options->carousel_gallery_download) echo 'checked="checked"'; ?> /><label for="carousel_gallery_download_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="carousel_gallery_download" id="carousel_gallery_download_0" value="0" <?php if (!BWG()->options->carousel_gallery_download) echo 'checked="checked"'; ?> /><label for="carousel_gallery_download_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Activate this setting to let users download all images of your gallery with a click.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                  </div>
                </div>
                <div id="album_compact_preview_options" class="gallery_options wd-box-content wd-width-100 bwg-flex-wrap">
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="album_column_number"><?php _e('Number of gallery group columns', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="album_column_number" id="album_column_number" value="<?php echo BWG()->options->album_column_number; ?>" min="0" />
                        </div>
                        <p class="description"><?php _e('Set the maximum number of columns in gallery groups. Note, that the parent container needs to be large enough to display all columns.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="album_thumb_width"><?php _e('Gallery group thumbnail dimensions', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="album_thumb_width" id="album_thumb_width" value="<?php echo BWG()->options->album_thumb_width; ?>" min="0" /><span>x</span>
                          <input type="number" name="album_thumb_height" id="album_thumb_height" value="<?php echo BWG()->options->album_thumb_height; ?>" min="0" /><span>px</span>
                        </div>
                        <p class="description"><?php _e('Specify the dimensions of thumbnails in gallery groups.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="album_image_column_number"><?php _e('Number of image columns', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="album_image_column_number" id="album_image_column_number" value="<?php echo BWG()->options->album_image_column_number; ?>" min="0" />
                        </div>
                        <p class="description"><?php _e('Set the maximum number of image columns in galleries. Note, that the parent container needs to be large enough to display all columns.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="album_image_thumb_width"><?php _e('Thumbnail dimensions', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="album_image_thumb_width" id="album_image_thumb_width" value="<?php echo BWG()->options->album_image_thumb_width; ?>" min="0" /><span>x</span>
                          <input type="number" name="album_image_thumb_height" id="album_image_thumb_height" value="<?php echo BWG()->options->album_image_thumb_height; ?>" min="0" /><span>px</span>
                        </div>
                        <p class="description"><?php _e('The default dimensions of thumbnails which will display on published galleries.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Pagination', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="album_enable_page" id="album_enable_page_0" value="0" <?php if (BWG()->options->album_enable_page == '0') echo 'checked="checked"'; ?> onClick="bwg_pagination_description(this);" /><label for="album_enable_page_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                          <input type="radio" name="album_enable_page" id="album_enable_page_1" value="1" <?php if (BWG()->options->album_enable_page == '1') echo 'checked="checked"'; ?> onClick="bwg_pagination_description(this);" /><label for="album_enable_page_1" class="wd-radio-label"><?php _e('Simple', BWG()->prefix); ?></label>
                          <input type="radio" name="album_enable_page" id="album_enable_page_2" value="2" <?php if (BWG()->options->album_enable_page == '2') echo 'checked="checked"'; ?> onClick="bwg_pagination_description(this);" /><label for="album_enable_page_2" class="wd-radio-label"><?php _e('Load More', BWG()->prefix); ?></label>
                          <input type="radio" name="album_enable_page" id="album_enable_page_3" value="3" <?php if (BWG()->options->album_enable_page == '3') echo 'checked="checked"'; ?> onClick="bwg_pagination_description(this);" /><label for="album_enable_page_3" class="wd-radio-label"><?php _e('Scroll Load', BWG()->prefix); ?></label>
                        </div>
                        <p class="description" id="album_enable_page_0_description"><?php _e('This option removes all types of pagination from your galleries.', BWG()->prefix); ?></p>
                        <p class="description" id="album_enable_page_1_description"><?php _e('Activating this option will add page numbers and next/previous buttons to your galleries.', BWG()->prefix); ?></p>
                        <p class="description" id="album_enable_page_2_description"><?php _e('Adding a Load More button, you can let users display a new set of images from your galleries.', BWG()->prefix); ?></p>
                        <p class="description" id="album_enable_page_3_description"><?php _e('With this option, users can load new images of your galleries simply by scrolling down.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_albums_per_page">
                      <div class="wd-group">
                        <label class="wd-label" for="albums_per_page"><?php _e('Gallery groups per page', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="albums_per_page" id="albums_per_page" value="<?php echo BWG()->options->albums_per_page; ?>" min="0" />
                        </div>
                        <p class="description"><?php _e('Specify the number of galleries/gallery groups to display per page. Setting this option to 0 shows all items.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_album_images_per_page">
                      <div class="wd-group">
                        <label class="wd-label" for="album_images_per_page"><?php _e('Images per page', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="album_images_per_page" id="album_images_per_page" value="<?php echo BWG()->options->album_images_per_page; ?>" min="0" />
                        </div>
                        <p class="description"><?php _e('Specify the number of images to display per page on galleries. Setting this option to 0 shows all items.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="album_sort_by"><?php _e('Order by', BWG()->prefix); ?></label>
                        <select name="album_sort_by" id="album_sort_by">
                          <option value="order" <?php if (BWG()->options->album_sort_by == 'order') echo 'selected="selected"'; ?>><?php _e('Order', BWG()->prefix); ?></option>
                          <option value="alt" <?php if (BWG()->options->album_sort_by == 'alt') echo 'selected="selected"'; ?>><?php _e('Title', BWG()->prefix); ?></option>
                          <option value="date" <?php if (BWG()->options->album_sort_by == 'date') echo 'selected="selected"'; ?>><?php _e('Date', BWG()->prefix); ?></option>
                          <option value="filename" <?php if (BWG()->options->album_sort_by == 'filename') echo 'selected="selected"'; ?>><?php _e('Filename', BWG()->prefix); ?></option>
                          <option value="size" <?php if (BWG()->options->album_sort_by == 'size') echo 'selected="selected"'; ?>><?php _e('Size', BWG()->prefix); ?></option>
                          <option value="filetype" <?php if (BWG()->options->album_sort_by == 'filetype') echo 'selected="selected"'; ?>><?php _e('Type', BWG()->prefix); ?></option>
                          <option value="resolution" <?php if (BWG()->options->album_sort_by == 'resolution') echo 'selected="selected"'; ?>><?php _e('Resolution', BWG()->prefix); ?></option>
                          <option value="random" <?php if (BWG()->options->album_sort_by == 'random') echo 'selected="selected"'; ?>><?php _e('Random', BWG()->prefix); ?></option>
                        </select>
                        <p class="description"><?php _e("Select the parameter based on which the gallery images will sorted, e.g. Title.", BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Order direction', BWG()->prefix); ?></label>
                        <input type="radio" name="album_order_by" id="album_order_by_1" value="asc" <?php if (BWG()->options->album_order_by == 'asc') echo 'checked="checked"'; ?> /><label for="album_order_by_1" class="wd-radio-label"><?php _e('Ascending', BWG()->prefix); ?></label>
                        <input type="radio" name="album_order_by" id="album_order_by_0" value="desc" <?php if (BWG()->options->album_order_by == 'desc') echo 'checked="checked"'; ?> /><label for="album_order_by_0" class="wd-radio-label"><?php _e('Descending', BWG()->prefix); ?></label>
                        <p class="description"><?php _e("Set the ordering direction for gallery images, ascending or descending.", BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show search box', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="album_show_search_box" id="album_show_search_box_1" value="1" <?php if (BWG()->options->album_show_search_box) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('', 'tr_album_search_box_width', 'album_show_search_box_1'); bwg_enable_disable('', 'tr_album_search_box_placeholder', 'album_show_search_box_1')" /><label for="album_show_search_box_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="album_show_search_box" id="album_show_search_box_0" value="0" <?php if (!BWG()->options->album_show_search_box) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_album_search_box_width', 'album_show_search_box_0'); bwg_enable_disable('none', 'tr_album_search_box_placeholder', 'album_show_search_box_0')" /><label for="album_show_search_box_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Enable this option to display a search box with your gallery or gallery group.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_album_search_box_placeholder">
                      <div class="wd-group">
                        <label class="wd-label" for="album_placeholder"><?php _e('Add placeholder to search', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="text" name="album_placeholder" id="album_placeholder" value="<?php echo BWG()->options->album_placeholder; ?>"  />
                        </div>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_album_search_box_width">
                      <div class="wd-group">
                        <label class="wd-label" for="album_search_box_width"><?php _e('Search box width', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="album_search_box_width" id="album_search_box_width" value="<?php echo BWG()->options->album_search_box_width; ?>" min="0" /><span>px</span>
                        </div>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show "Order by" dropdown list', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="album_show_sort_images" id="album_show_sort_images_1" value="1" <?php if (BWG()->options->album_show_sort_images) echo 'checked="checked"'; ?> /><label for="album_show_sort_images_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="album_show_sort_images" id="album_show_sort_images_0" value="0" <?php if (!BWG()->options->album_show_sort_images) echo 'checked="checked"'; ?> /><label for="album_show_sort_images_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Activate this dropdown box to let users browse your gallery images with different ordering options.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show tag box', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="album_show_tag_box" id="album_show_tag_box_1" value="1" <?php if (BWG()->options->album_show_tag_box) echo 'checked="checked"'; ?> /><label for="album_show_tag_box_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="album_show_tag_box" id="album_show_tag_box_0" value="0" <?php if (!BWG()->options->album_show_tag_box) echo 'checked="checked"'; ?> /><label for="album_show_tag_box_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Enable Tag Box to allow users to filter the gallery images by their tags.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show gallery group or gallery title', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="show_album_name" id="show_album_name_enable_1" value="1" <?php if (BWG()->options->show_album_name) echo 'checked="checked"'; ?> /><label for="show_album_name_enable_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="show_album_name" id="show_album_name_enable_0" value="0" <?php if (!BWG()->options->show_album_name) echo 'checked="checked"'; ?> /><label for="show_album_name_enable_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Display the title of displayed gallery or gallery group by enabling this setting.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show gallery group or gallery description', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="album_show_gallery_description" id="album_show_gallery_description_1" value="1" <?php if (BWG()->options->album_show_gallery_description) echo 'checked="checked"'; ?> /><label for="album_show_gallery_description_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="album_show_gallery_description" id="album_show_gallery_description_0" value="0" <?php if (!BWG()->options->album_show_gallery_description) echo 'checked="checked"'; ?> /><label for="album_show_gallery_description_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Display the description of displayed gallery or gallery group by enabling this setting.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show gallery title', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="album_title_show_hover" id="album_title_show_hover_1" value="hover" <?php if (BWG()->options->album_title_show_hover == "hover") echo 'checked="checked"'; ?> /><label for="album_title_show_hover_1" class="wd-radio-label"><?php _e('Show on hover', BWG()->prefix); ?></label>
                          <input type="radio" name="album_title_show_hover" id="album_title_show_hover_0" value="show" <?php if (BWG()->options->album_title_show_hover == "show") echo 'checked="checked"'; ?> /><label for="album_title_show_hover_0" class="wd-radio-label"><?php _e('Always show', BWG()->prefix); ?></label>
                          <input type="radio" name="album_title_show_hover" id="album_title_show_hover_2" value="none" <?php if (BWG()->options->album_title_show_hover == "none") echo 'checked="checked"'; ?> /><label for="album_title_show_hover_2" class="wd-radio-label"><?php _e("Don't show", BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Choose to show/hide titles of galleries/gallery groups, or display them on hover.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Gallery view type', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="album_view_type" id="album_view_type_1" value="thumbnail" <?php if (BWG()->options->album_view_type == "thumbnail") echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_album_mosaic', 'album_view_type_1'); bwg_enable_disable('none', 'tr_album_resizable_mosaic', 'album_view_type_1'); bwg_enable_disable('none', 'tr_album_mosaic_total_width', 'album_view_type_1');" /><label for="album_view_type_1" class="wd-radio-label"><?php _e('Thumbnail', BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="album_view_type" id="album_view_type_0" value="masonry" <?php if (BWG()->options->album_view_type == "masonry") echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_album_mosaic', 'album_view_type_0'); bwg_enable_disable('none', 'tr_album_resizable_mosaic', 'album_view_type_0'); bwg_enable_disable('none', 'tr_album_mosaic_total_width', 'album_view_type_0');" /><label for="album_view_type_0" class="wd-radio-label"><?php _e('Masonry', BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="album_view_type" id="album_view_type_2" value="mosaic" <?php if (BWG()->options->album_view_type == "mosaic") echo 'checked="checked"'; ?> onClick="bwg_enable_disable('', 'tr_album_mosaic', 'album_view_type_2'); bwg_enable_disable('', 'tr_album_resizable_mosaic', 'album_view_type_2'); bwg_enable_disable('', 'tr_album_mosaic_total_width', 'album_view_type_2');" /><label for="album_view_type_2" class="wd-radio-label"><?php _e('Mosaic', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Choose the display type for gallery groups, Thumbnails, Masonry or Mosaic.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_album_mosaic">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Mosaic gallery type', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="album_mosaic" id="album_mosaic_0" value="vertical" <?php if (BWG()->options->album_mosaic == "vertical") echo 'checked="checked"'; ?> /><label for="album_mosaic_0" class="wd-radio-label"><?php _e('Vertical', BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="album_mosaic" id="album_mosaic_1" value="horizontal" <?php if (BWG()->options->album_mosaic == "horizontal") echo 'checked="checked"'; ?> /><label for="album_mosaic_1" class="wd-radio-label"><?php _e('Horizontal', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Select the type of Mosaic galleries, Vertical or Horizontal.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_album_resizable_mosaic">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Resizable mosaic', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="album_resizable_mosaic" id="album_resizable_mosaic_1" value="1" <?php if (BWG()->options->album_resizable_mosaic == "1") echo 'checked="checked"'; ?> /><label for="album_resizable_mosaic_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="album_resizable_mosaic" id="album_resizable_mosaic_0" value="0" <?php if (BWG()->options->album_resizable_mosaic == "0") echo 'checked="checked"'; ?> /><label for="album_resizable_mosaic_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('If this setting is enabled, Photo Gallery resizes all thumbnail images on Mosaic galleries, without modifying their initial display.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_album_mosaic_total_width">
                      <div class="wd-group">
                        <label class="wd-label" for="album_mosaic_total_width"><?php _e('Width of mosaic galleries', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="number" name="album_mosaic_total_width" id="album_mosaic_total_width" value="<?php echo BWG()->options->album_mosaic_total_width; ?>" min="0" /><span>%</span>
                        </div>
                        <p class="description"><?php _e('The total width of mosaic galleries as a percentage of container\'s width.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show image title', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="album_image_title_show_hover" id="album_image_title_show_hover_1" value="hover" <?php if (BWG()->options->album_image_title_show_hover == "hover") echo 'checked="checked"'; ?> /><label for="album_image_title_show_hover_1" class="wd-radio-label"><?php _e('Show on hover', BWG()->prefix); ?></label>
                          <input type="radio" name="album_image_title_show_hover" id="album_image_title_show_hover_0" value="show" <?php if (BWG()->options->album_image_title_show_hover == "show") echo 'checked="checked"'; ?> /><label for="album_image_title_show_hover_0" class="wd-radio-label"><?php _e('Always show', BWG()->prefix); ?></label>
                          <input type="radio" name="album_image_title_show_hover" id="album_image_title_show_hover_2" value="none" <?php if (BWG()->options->album_image_title_show_hover == "none") echo 'checked="checked"'; ?> /><label for="album_image_title_show_hover_2" class="wd-radio-label"><?php _e("Don't show", BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Choose to show/hide titles of images, or display them on hover.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show Play icon on video thumbnails', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="album_play_icon" id="album_play_icon_yes" value="1" <?php if (BWG()->options->album_play_icon) echo 'checked="checked"'; ?> /><label for="album_play_icon_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="album_play_icon" id="album_play_icon_no" value="0" <?php if (!BWG()->options->album_play_icon) echo 'checked="checked"'; ?> /><label for="album_play_icon_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Activate this option to add a Play button on thumbnails of videos.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Enable bulk download button', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="album_gallery_download" id="album_gallery_download_1" value="1" <?php if (BWG()->options->album_gallery_download) echo 'checked="checked"'; ?> /><label for="album_gallery_download_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="album_gallery_download" id="album_gallery_download_0" value="0" <?php if (!BWG()->options->album_gallery_download) echo 'checked="checked"'; ?> /><label for="album_gallery_download_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Activate this setting to let users download all images of your gallery with a click.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <?php
                    if (function_exists('BWGEC')) {
                      ?>
                      <div class="wd-box-content wd-width-100">
                        <div class="wd-group">
                          <label class="wd-label"><?php _e('Show ecommerce icon', BWG()->prefix); ?></label>
                          <div class="bwg-flex">
                            <input type="radio" name="album_ecommerce_icon_show_hover" id="album_ecommerce_icon_show_hover_1" value="hover" <?php if (BWG()->options->album_ecommerce_icon_show_hover == "hover") echo 'checked="checked"'; ?> /><label for="album_ecommerce_icon_show_hover_1" class="wd-radio-label"><?php _e('Show on hover', BWG()->prefix); ?></label>
                            <input type="radio" name="album_ecommerce_icon_show_hover" id="album_ecommerce_icon_show_hover_0" value="show" <?php if (BWG()->options->album_ecommerce_icon_show_hover == "show") echo 'checked="checked"'; ?> /><label for="album_ecommerce_icon_show_hover_0" class="wd-radio-label"><?php _e('Always show', BWG()->prefix); ?></label>
                            <input type="radio" name="album_ecommerce_icon_show_hover" id="album_ecommerce_icon_show_hover_2" value="none" <?php if (BWG()->options->album_ecommerce_icon_show_hover == "none") echo 'checked="checked"'; ?> /><label for="album_ecommerce_icon_show_hover_2" class="wd-radio-label"><?php _e("Don't show", BWG()->prefix); ?></label>
                          </div>
                          <p class="description"><?php _e('Choose to show/hide ecommerce icon, or display them on hover.', BWG()->prefix); ?></p>
                        </div>
                      </div>
                      <?php
                    }
                    ?>
                  </div>
                </div>
                <div id="album_masonry_preview_options" class="gallery_options wd-box-content wd-width-100 bwg-flex-wrap">
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="album_masonry_column_number"><?php _e('Number of gallery group columns', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="album_masonry_column_number" id="album_masonry_column_number" value="<?php echo BWG()->options->album_masonry_column_number; ?>" min="0" />
                        </div>
                        <p class="description"><?php _e('Set the maximum number of columns in gallery groups. Note, that the parent container needs to be large enough to display all columns.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="album_masonry_thumb_width"><?php _e('Gallery group thumbnail width', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="album_masonry_thumb_width" id="album_masonry_thumb_width" value="<?php echo BWG()->options->album_masonry_thumb_width; ?>" min="0" /><span>px</span>
                        </div>
                        <p class="description"><?php _e('Specify the dimensions of thumbnails in gallery groups.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="album_masonry_image_column_number"><?php _e('Number of image columns', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="album_masonry_image_column_number" id="album_masonry_image_column_number" value="<?php echo BWG()->options->album_masonry_image_column_number; ?>" min="0" />
                        </div>
                        <p class="description"><?php _e('Set the maximum number of image columns in galleries. Note, that the parent container needs to be large enough to display all columns.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="album_masonry_image_thumb_width"><?php _e('Thumbnail width', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="album_masonry_image_thumb_width" id="album_masonry_image_thumb_width" value="<?php echo BWG()->options->album_masonry_image_thumb_width; ?>" min="0" /><span>px</span>
                        </div>
                        <p class="description"><?php _e('The default dimensions of thumbnails which will display on published galleries.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Pagination', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="album_masonry_enable_page" id="album_masonry_enable_page_0" value="0" <?php if (BWG()->options->album_masonry_enable_page == '0') echo 'checked="checked"'; ?> onClick="bwg_pagination_description(this);" /><label for="album_masonry_enable_page_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                          <input type="radio" name="album_masonry_enable_page" id="album_masonry_enable_page_1" value="1" <?php if (BWG()->options->album_masonry_enable_page == '1') echo 'checked="checked"'; ?> onClick="bwg_pagination_description(this);" /><label for="album_masonry_enable_page_1" class="wd-radio-label"><?php _e('Simple', BWG()->prefix); ?></label>
                          <input type="radio" name="album_masonry_enable_page" id="album_masonry_enable_page_2" value="2" <?php if (BWG()->options->album_masonry_enable_page == '2') echo 'checked="checked"'; ?> onClick="bwg_pagination_description(this);" /><label for="album_masonry_enable_page_2" class="wd-radio-label"><?php _e('Load More', BWG()->prefix); ?></label>
                          <input type="radio" name="album_masonry_enable_page" id="album_masonry_enable_page_3" value="3" <?php if (BWG()->options->album_masonry_enable_page == '3') echo 'checked="checked"'; ?> onClick="bwg_pagination_description(this);" /><label for="album_masonry_enable_page_3" class="wd-radio-label"><?php _e('Scroll Load', BWG()->prefix); ?></label>
                        </div>
                        <p class="description" id="album_masonry_enable_page_0_description"><?php _e('This option removes all types of pagination from your galleries.', BWG()->prefix); ?></p>
                        <p class="description" id="album_masonry_enable_page_1_description"><?php _e('Activating this option will add page numbers and next/previous buttons to your galleries.', BWG()->prefix); ?></p>
                        <p class="description" id="album_masonry_enable_page_2_description"><?php _e('Adding a Load More button, you can let users display a new set of images from your galleries.', BWG()->prefix); ?></p>
                        <p class="description" id="album_masonry_enable_page_3_description"><?php _e('With this option, users can load new images of your galleries simply by scrolling down.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_albums_masonry_per_page">
                      <div class="wd-group">
                        <label class="wd-label" for="albums_masonry_per_page"><?php _e('Gallery groups per page', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="albums_masonry_per_page" id="albums_masonry_per_page" value="<?php echo BWG()->options->albums_masonry_per_page; ?>" min="0" />
                        </div>
                        <p class="description"><?php _e('Specify the number of galleries/gallery groups to display per page. Setting this option to 0 shows all items.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_album_masonry_images_per_page">
                      <div class="wd-group">
                        <label class="wd-label" for="album_masonry_images_per_page"><?php _e('Images per page', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="album_masonry_images_per_page" id="album_masonry_images_per_page" value="<?php echo BWG()->options->album_masonry_images_per_page; ?>" min="0" />
                        </div>
                        <p class="description"><?php _e('Specify the number of images to display per page on galleries. Setting this option to 0 shows all items.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="album_masonry_sort_by"><?php _e('Order by', BWG()->prefix); ?></label>
                        <select name="album_masonry_sort_by" id="album_masonry_sort_by">
                          <option value="order" <?php if (BWG()->options->album_masonry_sort_by == 'order') echo 'selected="selected"'; ?>><?php _e('Order', BWG()->prefix); ?></option>
                          <option value="alt" <?php if (BWG()->options->album_masonry_sort_by == 'alt') echo 'selected="selected"'; ?>><?php _e('Title', BWG()->prefix); ?></option>
                          <option value="date" <?php if (BWG()->options->album_masonry_sort_by == 'date') echo 'selected="selected"'; ?>><?php _e('Date', BWG()->prefix); ?></option>
                          <option value="filename" <?php if (BWG()->options->album_masonry_sort_by == 'filename') echo 'selected="selected"'; ?>><?php _e('Filename', BWG()->prefix); ?></option>
                          <option value="size" <?php if (BWG()->options->album_masonry_sort_by == 'size') echo 'selected="selected"'; ?>><?php _e('Size', BWG()->prefix); ?></option>
                          <option value="filetype" <?php if (BWG()->options->album_masonry_sort_by == 'filetype') echo 'selected="selected"'; ?>><?php _e('Type', BWG()->prefix); ?></option>
                          <option value="resolution" <?php if (BWG()->options->album_masonry_sort_by == 'resolution') echo 'selected="selected"'; ?>><?php _e('Resolution', BWG()->prefix); ?></option>
                          <option value="random" <?php if (BWG()->options->album_masonry_sort_by == 'random') echo 'selected="selected"'; ?>><?php _e('Random', BWG()->prefix); ?></option>
                        </select>
                        <p class="description"><?php _e("Select the parameter based on which the gallery images will sorted, e.g. Title.", BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Order direction', BWG()->prefix); ?></label>
                        <input type="radio" name="album_masonry_order_by" id="album_masonry_order_by_1" value="asc" <?php if (BWG()->options->album_masonry_order_by == 'asc') echo 'checked="checked"'; ?> /><label for="album_masonry_order_by_1" class="wd-radio-label"><?php _e('Ascending', BWG()->prefix); ?></label>
                        <input type="radio" name="album_masonry_order_by" id="album_masonry_order_by_0" value="desc" <?php if (BWG()->options->album_masonry_order_by == 'desc') echo 'checked="checked"'; ?> /><label for="album_masonry_order_by_0" class="wd-radio-label"><?php _e('Descending', BWG()->prefix); ?></label>
                        <p class="description"><?php _e("Set the ordering direction for gallery images, ascending or descending.", BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show search box', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="album_masonry_show_search_box" id="album_masonry_show_search_box_1" value="1" <?php if (BWG()->options->album_masonry_show_search_box) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('', 'tr_album_masonry_search_box_width', 'album_masonry_show_search_box_1'); bwg_enable_disable('', 'tr_album_masonry_search_box_placeholder', 'album_masonry_show_search_box_1')" /><label for="album_masonry_show_search_box_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="album_masonry_show_search_box" id="album_masonry_show_search_box_0" value="0" <?php if (!BWG()->options->album_masonry_show_search_box) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_album_masonry_search_box_width', 'album_masonry_show_search_box_0'); bwg_enable_disable('none', 'tr_album_masonry_search_box_placeholder', 'album_masonry_show_search_box_0')" /><label for="album_masonry_show_search_box_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Enable this option to display a search box with your gallery or gallery group.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_album_masonry_search_box_placeholder">
                      <div class="wd-group">
                        <label class="wd-label" for="album_masonry_placeholder"><?php _e('Add placeholder to search', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="text" name="album_masonry_placeholder" id="album_masonry_placeholder" value="<?php echo BWG()->options->album_masonry_placeholder; ?>"  />
                        </div>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_album_masonry_search_box_width">
                      <div class="wd-group">
                        <label class="wd-label" for="album_masonry_search_box_width"><?php _e('Search box width', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="album_masonry_search_box_width" id="album_masonry_search_box_width" value="<?php echo BWG()->options->album_masonry_search_box_width; ?>" min="0" /><span>px</span>
                        </div>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show "Order by" dropdown list', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="album_masonry_show_sort_images" id="album_masonry_show_sort_images_1" value="1" <?php if (BWG()->options->album_masonry_show_sort_images) echo 'checked="checked"'; ?> /><label for="album_masonry_show_sort_images_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="album_masonry_show_sort_images" id="album_masonry_show_sort_images_0" value="0" <?php if (!BWG()->options->album_masonry_show_sort_images) echo 'checked="checked"'; ?> /><label for="album_masonry_show_sort_images_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Activate this dropdown box to let users browse your gallery images with different ordering options.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show tag box', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="album_masonry_show_tag_box" id="album_masonry_show_tag_box_1" value="1" <?php if (BWG()->options->album_masonry_show_tag_box) echo 'checked="checked"'; ?> /><label for="album_masonry_show_tag_box_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="album_masonry_show_tag_box" id="album_masonry_show_tag_box_0" value="0" <?php if (!BWG()->options->album_masonry_show_tag_box) echo 'checked="checked"'; ?> /><label for="album_masonry_show_tag_box_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Enable Tag Box to allow users to filter the gallery images by their tags.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show gallery group or gallery title', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="show_album_masonry_name" id="show_album_masonry_name_enable_1" value="1" <?php if (BWG()->options->show_album_masonry_name) echo 'checked="checked"'; ?> /><label for="show_album_masonry_name_enable_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="show_album_masonry_name" id="show_album_masonry_name_enable_0" value="0" <?php if (!BWG()->options->show_album_masonry_name) echo 'checked="checked"'; ?> /><label for="show_album_masonry_name_enable_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Display the title of displayed gallery or gallery group by enabling this setting.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show gallery group or gallery description', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="album_masonry_show_gallery_description" id="album_masonry_show_gallery_description_1" value="1" <?php if (BWG()->options->album_masonry_show_gallery_description) echo 'checked="checked"'; ?> /><label for="album_masonry_show_gallery_description_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="album_masonry_show_gallery_description" id="album_masonry_show_gallery_description_0" value="0" <?php if (!BWG()->options->album_masonry_show_gallery_description) echo 'checked="checked"'; ?> /><label for="album_masonry_show_gallery_description_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Display the description of displayed gallery or gallery group by enabling this setting.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Enable bulk download button', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="album_masonry_gallery_download" id="album_masonry_gallery_download_1" value="1" <?php if (BWG()->options->album_masonry_gallery_download) echo 'checked="checked"'; ?> /><label for="album_masonry_gallery_download_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="album_masonry_gallery_download" id="album_masonry_gallery_download_0" value="0" <?php if (!BWG()->options->album_masonry_gallery_download) echo 'checked="checked"'; ?> /><label for="album_masonry_gallery_download_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Activate this setting to let users download all images of your gallery with a click.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <?php
                    if (function_exists('BWGEC')) {
                      ?>
                      <div class="wd-box-content wd-width-100">
                        <div class="wd-group">
                          <label class="wd-label"><?php _e('Show ecommerce icon', BWG()->prefix); ?></label>
                          <div class="bwg-flex">
                            <input type="radio" name="album_masonry_ecommerce_icon_show_hover" id="album_masonry_ecommerce_icon_show_hover_1" value="hover" <?php if (BWG()->options->album_masonry_ecommerce_icon_show_hover == "hover") echo 'checked="checked"'; ?> /><label for="album_masonry_ecommerce_icon_show_hover_1" class="wd-radio-label"><?php _e('Show on hover', BWG()->prefix); ?></label>
                            <input type="radio" name="album_masonry_ecommerce_icon_show_hover" id="album_masonry_ecommerce_icon_show_hover_2" value="none" <?php if (BWG()->options->album_masonry_ecommerce_icon_show_hover == "none") echo 'checked="checked"'; ?> /><label for="album_masonry_ecommerce_icon_show_hover_2" class="wd-radio-label"><?php _e("Don't show", BWG()->prefix); ?></label>
                          </div>
                          <p class="description"><?php _e('Choose to show/hide ecommerce icon, or display them on hover.', BWG()->prefix); ?></p>
                        </div>
                      </div>
                      <?php
                    }
                    ?>
                  </div>
                </div>
                <div id="album_extended_preview_options" class="gallery_options wd-box-content wd-width-100 bwg-flex-wrap">
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="extended_album_height"><?php _e('Extended gallery group height', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="extended_album_height" id="extended_album_height" value="<?php echo BWG()->options->extended_album_height; ?>" min="0" /><span>px</span>
                        </div>
                        <p class="description"><?php _e('Set the height of blocks in Extended gallery groups.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="album_extended_thumb_width"><?php _e('Gallery group thumbnail dimensions', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="album_extended_thumb_width" id="album_extended_thumb_width" value="<?php echo BWG()->options->album_extended_thumb_width; ?>" min="0" /><span>x</span>
                          <input type="number" name="album_extended_thumb_height" id="album_extended_thumb_height" value="<?php echo BWG()->options->album_extended_thumb_height; ?>" min="0" /><span>px</span>
                        </div>
                        <p class="description"><?php _e('Specify the dimensions of thumbnails in gallery groups.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="album_extended_image_column_number"><?php _e('Number of image columns', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="album_extended_image_column_number" id="album_extended_image_column_number" value="<?php echo BWG()->options->album_extended_image_column_number; ?>" min="0" />
                        </div>
                        <p class="description"><?php _e('Set the maximum number of image columns in galleries. Note, that the parent container needs to be large enough to display all columns.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="album_extended_image_thumb_width"><?php _e('Thumbnail dimensions', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="album_extended_image_thumb_width" id="album_extended_image_thumb_width" value="<?php echo BWG()->options->album_extended_image_thumb_width; ?>" min="0" /><span>x</span>
                          <input type="number" name="album_extended_image_thumb_height" id="album_extended_image_thumb_height" value="<?php echo BWG()->options->album_extended_image_thumb_height; ?>" min="0" /><span>px</span>
                        </div>
                        <p class="description"><?php _e('The default dimensions of thumbnails which will display on published galleries.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Pagination', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="album_extended_enable_page" id="album_extended_enable_page_0" value="0" <?php if (BWG()->options->album_extended_enable_page == '0') echo 'checked="checked"'; ?> onClick="bwg_pagination_description(this);" /><label for="album_extended_enable_page_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                          <input type="radio" name="album_extended_enable_page" id="album_extended_enable_page_1" value="1" <?php if (BWG()->options->album_extended_enable_page == '1') echo 'checked="checked"'; ?> onClick="bwg_pagination_description(this);" /><label for="album_extended_enable_page_1" class="wd-radio-label"><?php _e('Simple', BWG()->prefix); ?></label>
                          <input type="radio" name="album_extended_enable_page" id="album_extended_enable_page_2" value="2" <?php if (BWG()->options->album_extended_enable_page == '2') echo 'checked="checked"'; ?> onClick="bwg_pagination_description(this);" /><label for="album_extended_enable_page_2" class="wd-radio-label"><?php _e('Load More', BWG()->prefix); ?></label>
                          <input type="radio" name="album_extended_enable_page" id="album_extended_enable_page_3" value="3" <?php if (BWG()->options->album_extended_enable_page == '3') echo 'checked="checked"'; ?> onClick="bwg_pagination_description(this);" /><label for="album_extended_enable_page_3" class="wd-radio-label"><?php _e('Scroll Load', BWG()->prefix); ?></label>
                        </div>
                        <p class="description" id="album_extended_enable_page_0_description"><?php _e('This option removes all types of pagination from your galleries.', BWG()->prefix); ?></p>
                        <p class="description" id="album_extended_enable_page_1_description"><?php _e('Activating this option will add page numbers and next/previous buttons to your galleries.', BWG()->prefix); ?></p>
                        <p class="description" id="album_extended_enable_page_2_description"><?php _e('Adding a Load More button, you can let users display a new set of images from your galleries.', BWG()->prefix); ?></p>
                        <p class="description" id="album_extended_enable_page_3_description"><?php _e('With this option, users can load new images of your galleries simply by scrolling down.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_albums_extended_per_page">
                      <div class="wd-group">
                        <label class="wd-label" for="albums_extended_per_page"><?php _e('Gallery groups per page', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="albums_extended_per_page" id="albums_extended_per_page" value="<?php echo BWG()->options->albums_extended_per_page; ?>" min="0" />
                        </div>
                        <p class="description"><?php _e('Specify the number of galleries/gallery groups to display per page. Setting this option to 0 shows all items.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_album_extended_images_per_page">
                      <div class="wd-group">
                        <label class="wd-label" for="album_extended_images_per_page"><?php _e('Images per page', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="album_extended_images_per_page" id="album_extended_images_per_page" value="<?php echo BWG()->options->album_extended_images_per_page; ?>" min="0" />
                        </div>
                        <p class="description"><?php _e('Specify the number of images to display per page on galleries. Setting this option to 0 shows all items.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label" for="album_extended_sort_by"><?php _e('Order by', BWG()->prefix); ?></label>
                        <select name="album_extended_sort_by" id="album_extended_sort_by">
                          <option value="order" <?php if (BWG()->options->album_extended_sort_by == 'order') echo 'selected="selected"'; ?>><?php _e('Order', BWG()->prefix); ?></option>
                          <option value="alt" <?php if (BWG()->options->album_extended_sort_by == 'alt') echo 'selected="selected"'; ?>><?php _e('Title', BWG()->prefix); ?></option>
                          <option value="date" <?php if (BWG()->options->album_extended_sort_by == 'date') echo 'selected="selected"'; ?>><?php _e('Date', BWG()->prefix); ?></option>
                          <option value="filename" <?php if (BWG()->options->album_extended_sort_by == 'filename') echo 'selected="selected"'; ?>><?php _e('Filename', BWG()->prefix); ?></option>
                          <option value="size" <?php if (BWG()->options->album_extended_sort_by == 'size') echo 'selected="selected"'; ?>><?php _e('Size', BWG()->prefix); ?></option>
                          <option value="filetype" <?php if (BWG()->options->album_extended_sort_by == 'filetype') echo 'selected="selected"'; ?>><?php _e('Type', BWG()->prefix); ?></option>
                          <option value="resolution" <?php if (BWG()->options->album_extended_sort_by == 'resolution') echo 'selected="selected"'; ?>><?php _e('Resolution', BWG()->prefix); ?></option>
                          <option value="random" <?php if (BWG()->options->album_extended_sort_by == 'random') echo 'selected="selected"'; ?>><?php _e('Random', BWG()->prefix); ?></option>
                        </select>
                        <p class="description"><?php _e("Select the parameter based on which the gallery images will sorted, e.g. Title.", BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Order direction', BWG()->prefix); ?></label>
                        <input type="radio" name="album_extended_order_by" id="album_extended_order_by_1" value="asc" <?php if (BWG()->options->album_extended_order_by == 'asc') echo 'checked="checked"'; ?> /><label for="album_extended_order_by_1" class="wd-radio-label"><?php _e('Ascending', BWG()->prefix); ?></label>
                        <input type="radio" name="album_extended_order_by" id="album_extended_order_by_0" value="desc" <?php if (BWG()->options->album_extended_order_by == 'desc') echo 'checked="checked"'; ?> /><label for="album_extended_order_by_0" class="wd-radio-label"><?php _e('Descending', BWG()->prefix); ?></label>
                        <p class="description"><?php _e("Set the ordering direction for gallery images, ascending or descending.", BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show search box', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="album_extended_show_search_box" id="album_extended_show_search_box_1" value="1" <?php if (BWG()->options->album_extended_show_search_box) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('', 'tr_album_extended_search_box_width', 'album_extended_show_search_box_1'); bwg_enable_disable('', 'tr_album_extended_search_box_placeholder', 'album_extended_show_search_box_1')" /><label for="album_extended_show_search_box_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="album_extended_show_search_box" id="album_extended_show_search_box_0" value="0" <?php if (!BWG()->options->album_extended_show_search_box) echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_album_extended_search_box_width', 'album_extended_show_search_box_0'); bwg_enable_disable('none', 'tr_album_extended_search_box_placeholder', 'album_extended_show_search_box_0')" /><label for="album_extended_show_search_box_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Enable this option to display a search box with your gallery or gallery group.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_album_extended_search_box_placeholder">
                      <div class="wd-group">
                        <label class="wd-label" for="album_extended_placeholder"><?php _e('Add placeholder to search', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="text" name="album_extended_placeholder" id="album_extended_placeholder" value="<?php echo BWG()->options->album_extended_placeholder; ?>"  />
                        </div>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_album_extended_search_box_width">
                      <div class="wd-group">
                        <label class="wd-label" for="album_extended_search_box_width"><?php _e('Search box width', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="number" name="album_extended_search_box_width" id="album_extended_search_box_width" value="<?php echo BWG()->options->album_extended_search_box_width; ?>" min="0" /><span>px</span>
                        </div>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show "Order by" dropdown list', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="album_extended_show_sort_images" id="album_extended_show_sort_images_1" value="1" <?php if (BWG()->options->album_extended_show_sort_images) echo 'checked="checked"'; ?> /><label for="album_extended_show_sort_images_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="album_extended_show_sort_images" id="album_extended_show_sort_images_0" value="0" <?php if (!BWG()->options->album_extended_show_sort_images) echo 'checked="checked"'; ?> /><label for="album_extended_show_sort_images_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Activate this dropdown box to let users browse your gallery images with different ordering options.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show tag box', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="album_extended_show_tag_box" id="album_extended_show_tag_box_1" value="1" <?php if (BWG()->options->album_extended_show_tag_box) echo 'checked="checked"'; ?> /><label for="album_extended_show_tag_box_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="album_extended_show_tag_box" id="album_extended_show_tag_box_0" value="0" <?php if (!BWG()->options->album_extended_show_tag_box) echo 'checked="checked"'; ?> /><label for="album_extended_show_tag_box_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Enable Tag Box to allow users to filter the gallery images by their tags.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show gallery group or gallery title', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="show_album_extended_name" id="show_album_extended_name_enable_1" value="1" <?php if (BWG()->options->show_album_extended_name) echo 'checked="checked"'; ?> /><label for="show_album_extended_name_enable_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="show_album_extended_name" id="show_album_extended_name_enable_0" value="0" <?php if (!BWG()->options->show_album_extended_name) echo 'checked="checked"'; ?> /><label for="show_album_extended_name_enable_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Display the title of displayed gallery or gallery group by enabling this setting.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show extended gallery group description', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="extended_album_description_enable" id="extended_album_description_enable_1" value="1" <?php if (BWG()->options->extended_album_description_enable) echo 'checked="checked"'; ?> /><label for="extended_album_description_enable_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="extended_album_description_enable" id="extended_album_description_enable_0" value="0" <?php if (!BWG()->options->extended_album_description_enable) echo 'checked="checked"'; ?> /><label for="extended_album_description_enable_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Enable this option to show descriptions of galleries/gallery groups in Extended view.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show gallery group or gallery description', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="album_extended_show_gallery_description" id="album_extended_show_gallery_description_1" value="1" <?php if (BWG()->options->album_extended_show_gallery_description) echo 'checked="checked"'; ?> /><label for="album_extended_show_gallery_description_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="album_extended_show_gallery_description" id="album_extended_show_gallery_description_0" value="0" <?php if (!BWG()->options->album_extended_show_gallery_description) echo 'checked="checked"'; ?> /><label for="album_extended_show_gallery_description_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Display the description of displayed gallery or gallery group by enabling this setting.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Gallery view type', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="album_extended_view_type" id="album_extended_view_type_1" value="thumbnail" <?php if (BWG()->options->album_extended_view_type == "thumbnail") echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_album_extended_mosaic', 'album_extended_view_type_1'); bwg_enable_disable('none', 'tr_album_extended_resizable_mosaic', 'album_extended_view_type_1'); bwg_enable_disable('none', 'tr_album_extended_mosaic_total_width', 'album_extended_view_type_1');" /><label for="album_extended_view_type_1" class="wd-radio-label"><?php _e('Thumbnail', BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="album_extended_view_type" id="album_extended_view_type_0" value="masonry" <?php if (BWG()->options->album_extended_view_type == "masonry") echo 'checked="checked"'; ?> onClick="bwg_enable_disable('none', 'tr_album_extended_mosaic', 'album_extended_view_type_0'); bwg_enable_disable('none', 'tr_album_extended_resizable_mosaic', 'album_extended_view_type_0'); bwg_enable_disable('none', 'tr_album_extended_mosaic_total_width', 'album_extended_view_type_0');" /><label for="album_extended_view_type_0" class="wd-radio-label"><?php _e('Masonry', BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="album_extended_view_type" id="album_extended_view_type_2" value="mosaic" <?php if (BWG()->options->album_extended_view_type == "mosaic") echo 'checked="checked"'; ?> onClick="bwg_enable_disable('', 'tr_album_extended_mosaic', 'album_extended_view_type_2'); bwg_enable_disable('', 'tr_album_extended_resizable_mosaic', 'album_extended_view_type_2'); bwg_enable_disable('', 'tr_album_extended_mosaic_total_width', 'album_extended_view_type_2');" /><label for="album_extended_view_type_2" class="wd-radio-label"><?php _e('Mosaic', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Choose the display type for gallery groups, Thumbnails, Masonry or Mosaic.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_album_extended_mosaic">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Mosaic gallery type', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="album_extended_mosaic" id="album_extended_mosaic_0" value="vertical" <?php if (BWG()->options->album_extended_mosaic == "vertical") echo 'checked="checked"'; ?> /><label for="album_extended_mosaic_0" class="wd-radio-label"><?php _e('Vertical', BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="album_extended_mosaic" id="album_extended_mosaic_1" value="horizontal" <?php if (BWG()->options->album_extended_mosaic == "horizontal") echo 'checked="checked"'; ?> /><label for="album_extended_mosaic_1" class="wd-radio-label"><?php _e('Horizontal', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Select the type of Mosaic galleries, Vertical or Horizontal.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_album_extended_resizable_mosaic">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Resizable mosaic', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="album_extended_resizable_mosaic" id="album_extended_resizable_mosaic_1" value="1" <?php if (BWG()->options->album_extended_resizable_mosaic == "1") echo 'checked="checked"'; ?> /><label for="album_extended_resizable_mosaic_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="album_extended_resizable_mosaic" id="album_extended_resizable_mosaic_0" value="0" <?php if (BWG()->options->album_extended_resizable_mosaic == "0") echo 'checked="checked"'; ?> /><label for="album_extended_resizable_mosaic_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('If this setting is enabled, Photo Gallery resizes all thumbnail images on Mosaic galleries, without modifying their initial display.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_album_extended_mosaic_total_width">
                      <div class="wd-group">
                        <label class="wd-label" for="album_extended_mosaic_total_width"><?php _e('Width of mosaic galleries', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="number" name="album_extended_mosaic_total_width" id="album_extended_mosaic_total_width" value="<?php echo BWG()->options->album_extended_mosaic_total_width; ?>" min="0" /><span>%</span>
                        </div>
                        <p class="description"><?php _e('The total width of mosaic galleries as a percentage of container\'s width.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show image title', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="album_extended_image_title_show_hover" id="album_extended_image_title_show_hover_1" value="hover" <?php if (BWG()->options->album_extended_image_title_show_hover == "hover") echo 'checked="checked"'; ?> /><label for="album_extended_image_title_show_hover_1" class="wd-radio-label"><?php _e('Show on hover', BWG()->prefix); ?></label>
                          <input type="radio" name="album_extended_image_title_show_hover" id="album_extended_image_title_show_hover_0" value="show" <?php if (BWG()->options->album_extended_image_title_show_hover == "show") echo 'checked="checked"'; ?> /><label for="album_extended_image_title_show_hover_0" class="wd-radio-label"><?php _e('Always show', BWG()->prefix); ?></label>
                          <input type="radio" name="album_extended_image_title_show_hover" id="album_extended_image_title_show_hover_2" value="none" <?php if (BWG()->options->album_extended_image_title_show_hover == "none") echo 'checked="checked"'; ?> /><label for="album_extended_image_title_show_hover_2" class="wd-radio-label"><?php _e("Don't show", BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Choose to show/hide titles of images, or display them on hover.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Show Play icon on video thumbnails', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="radio" name="album_extended_play_icon" id="album_extended_play_icon_yes" value="1" <?php if (BWG()->options->album_extended_play_icon) echo 'checked="checked"'; ?> /><label for="album_extended_play_icon_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="album_extended_play_icon" id="album_extended_play_icon_no" value="0" <?php if (!BWG()->options->album_extended_play_icon) echo 'checked="checked"'; ?> /><label for="album_extended_play_icon_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Activate this option to add a Play button on thumbnails of videos.', BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Enable bulk download button', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="album_extended_gallery_download" id="album_extended_gallery_download_1" value="1" <?php if (BWG()->options->album_extended_gallery_download) echo 'checked="checked"'; ?> /><label for="album_extended_gallery_download_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input <?php echo BWG()->is_pro ? '' : 'disabled="disabled"'; ?> type="radio" name="album_extended_gallery_download" id="album_extended_gallery_download_0" value="0" <?php if (!BWG()->options->album_extended_gallery_download) echo 'checked="checked"'; ?> /><label for="album_extended_gallery_download_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        </div>
                        <p class="description"><?php _e('Activate this setting to let users download all images of your gallery with a click.', BWG()->prefix); ?></p>
                        <?php if ( !BWG()->is_pro ) { ?><p class="description spider_free_version"><?php echo BWG()->free_msg; ?></p><?php } ?>
                      </div>
                    </div>
                    <?php
                    if (function_exists('BWGEC')) {
                      ?>
                      <div class="wd-box-content wd-width-100">
                        <div class="wd-group">
                          <label class="wd-label"><?php _e('Show ecommerce icon', BWG()->prefix); ?></label>
                          <div class="bwg-flex">
                            <input type="radio" name="album_extended_ecommerce_icon_show_hover" id="album_extended_ecommerce_icon_show_hover_1" value="hover" <?php if (BWG()->options->album_extended_ecommerce_icon_show_hover == "hover") echo 'checked="checked"'; ?> /><label for="album_extended_ecommerce_icon_show_hover_1" class="wd-radio-label"><?php _e('Show on hover', BWG()->prefix); ?></label>
                            <input type="radio" name="album_extended_ecommerce_icon_show_hover" id="album_extended_ecommerce_icon_show_hover_0" value="show" <?php if (BWG()->options->album_extended_ecommerce_icon_show_hover == "show") echo 'checked="checked"'; ?> /><label for="album_extended_ecommerce_icon_show_hover_0" class="wd-radio-label"><?php _e('Always show', BWG()->prefix); ?></label>
                            <input type="radio" name="album_extended_ecommerce_icon_show_hover" id="album_extended_ecommerce_icon_show_hover_2" value="none" <?php if (BWG()->options->album_extended_ecommerce_icon_show_hover == "none") echo 'checked="checked"'; ?> /><label for="album_extended_ecommerce_icon_show_hover_2" class="wd-radio-label"><?php _e("Don't show", BWG()->prefix); ?></label>
                          </div>
                          <p class="description"><?php _e('Choose to show/hide ecommerce icon, or display them on hover.', BWG()->prefix); ?></p>
                        </div>
                      </div>
                      <?php
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="postbox closed">
              <button class="button-link handlediv" type="button" aria-expanded="true">
                <span class="screen-reader-text"><?php _e('Toggle panel:', BWG()->prefix); ?></span>
                <span class="toggle-indicator" aria-hidden="false"></span>
              </button>
              <h2 class="hndle">
                <span><?php _e('Lightbox', BWG()->prefix); ?></span>
              </h2>
              <div class="inside">
                <div class="wd-box-content wd-width-100" id="tr_watermark_type">
                  <div class="wd-group">
                    <label class="wd-label"><?php _e('Image click action', BWG()->prefix); ?></label>
                    <input type="radio" name="thumb_click_action" id="thumb_click_action_1" value="open_lightbox" <?php if (BWG()->options->thumb_click_action == 'open_lightbox') echo 'checked="checked"'; ?> onchange="bwg_thumb_click_action()" /><label for="thumb_click_action_1" class="wd-radio-label"><?php _e('Open lightbox', BWG()->prefix); ?></label>
                    <input type="radio" name="thumb_click_action" id="thumb_click_action_2" value="redirect_to_url" <?php if (BWG()->options->thumb_click_action == 'redirect_to_url') echo 'checked="checked"'; ?> onchange="bwg_thumb_click_action()" /><label for="thumb_click_action_2" class="wd-radio-label"><?php _e('Redirect to url', BWG()->prefix); ?></label>
                    <input type="radio" name="thumb_click_action" id="thumb_click_action_3" value="do_nothing" <?php if (BWG()->options->thumb_click_action == 'do_nothing') echo 'checked="checked"'; ?> onchange="bwg_thumb_click_action()" /><label for="thumb_click_action_3" class="wd-radio-label"><?php _e('Do Nothing', BWG()->prefix); ?></label>
                    <p class="description">Select the action which runs after clicking on gallery thumbnails.</p>                  </div>
                </div>
                <div class="wd-box-content wd-width-100" id="tr_thumb_link_target">
                  <div class="wd-group">
                    <label class="wd-label"><?php _e('Open in new window', BWG()->prefix); ?></label>
                    <input type="radio" name="thumb_link_target" id="thumb_link_target_yes" value="1" <?php if (BWG()->options->thumb_link_target) echo 'checked="checked"'; ?> /><label for="thumb_link_target_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                    <input type="radio" name="thumb_link_target" id="thumb_link_target_no" value="0" <?php if (!BWG()->options->thumb_link_target) echo 'checked="checked"'; ?> /><label for="thumb_link_target_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                    <p class="description"><?php _e("Open new window when redirecting.", BWG()->prefix); ?></p>
                  </div>
                </div>
                <div class="wd-box-content wd-width-100 bwg-flex-wrap" id="tbody_popup">
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100" id="tr_popup_fullscreen">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Full-width lightbox', BWG()->prefix); ?></label>
                        <input type="radio" name="popup_fullscreen" id="popup_fullscreen_1" value="1" <?php if (BWG()->options->popup_fullscreen) echo 'checked="checked"'; ?> onchange="bwg_popup_fullscreen()" /><label for="popup_fullscreen_1" class="wd-radio-label"><?php echo __('Yes', BWG()->prefix); ?></label>
                        <input type="radio" name="popup_fullscreen" id="popup_fullscreen_0" value="0" <?php if (!BWG()->options->popup_fullscreen) echo 'checked="checked"'; ?> onchange="bwg_popup_fullscreen()" /><label for="popup_fullscreen_0" class="wd-radio-label"><?php echo __('No', BWG()->prefix); ?></label>
                        <p class="description"><?php _e("Image lightbox will appear full-width if this setting is activated.", BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_popup_width_height">
                      <div class="wd-group">
                        <label class="wd-label" for="popup_width"><?php _e('Lightbox dimensions', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="text" name="popup_width" id="popup_width" value="<?php echo BWG()->options->popup_width; ?>" class="spider_int_input" /><span>x</span>
                          <input type="text" name="popup_height" id="popup_height" value="<?php echo BWG()->options->popup_height; ?>" class="spider_int_input" /><span>px</span>
                        </div>
                        <p class="description"><?php _e("Set the dimensions of image lightbox.", BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_popup_effect">
                      <div class="wd-group">
                        <label class="wd-label" for="popup_effect"><?php _e('Lightbox effect', BWG()->prefix); ?></label>
                        <select name="popup_effect" id="popup_effect">
                          <?php
                          foreach ( $effects as $key => $effect ) {
                            ?>
                            <option value="<?php echo $key; ?>" <?php echo (BWG()->options->popup_type == $key) ? 'selected' : ''; ?>><?php echo $effect; ?></option>
                            <?php
                          }
                          ?>
                        </select>
                        <p class="description"><?php _e("Select the animation effect for image lightbox.", BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_popup_effect_duration">
                      <div class="wd-group">
                        <label class="wd-label" for="popup_effect_duration"><?php _e('Effect duration', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="text" name="popup_effect_duration" id="popup_effect_duration" value="<?php echo BWG()->options->popup_effect_duration; ?>" class="spider_int_input" /> sec.
                        </div>
                        <p class="description"><?php _e("Set the duration of lightbox animation effect.", BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_popup_autoplay">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Lightbox autoplay', BWG()->prefix); ?></label>
                        <input type="radio" name="popup_autoplay" id="popup_autoplay_1" value="1" <?php if (BWG()->options->popup_autoplay) echo 'checked="checked"'; ?>  /><label for="popup_autoplay_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                        <input type="radio" name="popup_autoplay" id="popup_autoplay_0" value="0" <?php if (!BWG()->options->popup_autoplay) echo 'checked="checked"'; ?>  /><label for="popup_autoplay_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        <p class="description"><?php _e("Activate this option to autoplay images in gallery lightbox.", BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_popup_interval">
                      <div class="wd-group">
                        <label class="wd-label" for="popup_interval"><?php _e('Time interval', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="text" name="popup_interval" id="popup_interval" value="<?php echo BWG()->options->popup_interval; ?>" class="spider_int_input" /> sec.
                        </div>
                        <p class="description"><?php _e("Specify the time interval of autoplay in Photo Gallery lightbox.", BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_popup_enable_filmstrip">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Enable filmstrip in lightbox', BWG()->prefix); ?></label>
                        <input type="radio" name="popup_enable_filmstrip" id="popup_filmstrip_yes" value="1" onClick="bwg_enable_disable('', 'tr_popup_filmstrip_height', 'popup_filmstrip_yes')" <?php echo (BWG()->options->popup_enable_filmstrip) ? 'checked' : ''; ?> /><label for="popup_filmstrip_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                        <input type="radio" name="popup_enable_filmstrip" id="popup_filmstrip_no" value="0" onClick="bwg_enable_disable('none', 'tr_popup_filmstrip_height', 'popup_filmstrip_no')" <?php echo (BWG()->options->popup_enable_filmstrip) ? '' : 'checked'; ?> /><label for="popup_filmstrip_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        <p class="description"><?php _e("Add a filmstrip with image thumbnails to the lightbox of your galleries.", BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_popup_filmstrip_height">
                      <div class="wd-group">
                        <label class="wd-label" for="popup_filmstrip_height"><?php _e('Filmstrip size', BWG()->prefix); ?></label>
                        <div class="bwg-flex">
                          <input type="text" name="popup_filmstrip_height" id="popup_filmstrip_height" value="<?php echo BWG()->options->popup_filmstrip_height; ?>" class="spider_int_input" /><span>px</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100" id="tr_popup_hit_counter">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Display views counter', BWG()->prefix); ?></label>
                        <input type="radio" name="popup_hit_counter" id="popup_hit_counter_yes" value="1" <?php echo (BWG()->options->popup_hit_counter) ? 'checked' : ''; ?> /><label for="popup_hit_counter_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                        <input type="radio" name="popup_hit_counter" id="popup_hit_counter_no" value="0" <?php echo (BWG()->options->popup_hit_counter) ? '' : 'checked'; ?> /><label for="popup_hit_counter_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        <p class="description"><?php _e("Show the number of views, when a gallery image was opened in lightbox.", BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tr_popup_enable_ctrl_btn">
                      <div class="wd-group">
                        <label class="wd-label"><?php _e('Enable control buttons', BWG()->prefix); ?></label>
                        <input type="radio" name="popup_enable_ctrl_btn" id="popup_ctrl_btn_yes" value="1" onClick="bwg_enable_disable('', 'tbody_popup_ctrl_btn1', 'popup_ctrl_btn_yes');bwg_enable_disable('', 'tbody_popup_ctrl_btn2', 'popup_ctrl_btn_yes');" <?php echo (BWG()->options->popup_enable_ctrl_btn) ? 'checked' : ''; ?> /><label for="popup_ctrl_btn_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                        <input type="radio" name="popup_enable_ctrl_btn" id="popup_ctrl_btn_no" value="0" onClick="bwg_enable_disable('none', 'tbody_popup_ctrl_btn1', 'popup_ctrl_btn_no');bwg_enable_disable('none', 'tbody_popup_ctrl_btn2', 'popup_ctrl_btn_no');" <?php echo (BWG()->options->popup_enable_ctrl_btn) ? '' : 'checked'; ?> /><label for="popup_ctrl_btn_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                        <p class="description"><?php _e("Enable this option to show control buttons on Photo Gallery lightbox.", BWG()->prefix); ?></p>
                      </div>
                    </div>
                    <div class="wd-box-content wd-width-100" id="tbody_popup_ctrl_btn1">
                      <div class="wd-box-content wd-width-100" id="tr_popup_enable_fullscreen">
                        <div class="wd-group">
                          <label class="wd-label"><?php _e('Enable fullscreen button', BWG()->prefix); ?></label>
                          <input type="radio" name="popup_enable_fullscreen" id="popup_fullscreen_yes" value="1" <?php echo (BWG()->options->popup_enable_fullscreen) ? 'checked' : ''; ?> /><label for="popup_fullscreen_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="popup_enable_fullscreen" id="popup_fullscreen_no" value="0" <?php echo (BWG()->options->popup_enable_fullscreen) ? '' : 'checked'; ?> /><label for="popup_fullscreen_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                          <p class="description"><?php _e("Activate this setting to add Fullscreen button to lightbox control buttons.", BWG()->prefix); ?></p>
                        </div>
                      </div>
                      <div class="wd-box-content wd-width-100" id="tr_popup_enable_info">
                        <div class="wd-group">
                          <label class="wd-label"><?php _e('Show image info', BWG()->prefix); ?></label>
                          <input type="radio" name="popup_enable_info" id="popup_info_yes" value="1" <?php echo (BWG()->options->popup_enable_info) ? 'checked="checked"' : ''; ?> /><label for="popup_info_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="popup_enable_info" id="popup_info_no" value="0" <?php echo (BWG()->options->popup_enable_info) ? '' : 'checked="checked"'; ?> /><label for="popup_info_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                          <p class="description"><?php _e("Activate this setting to show Info button among lightbox control buttons.", BWG()->prefix); ?></p>
                        </div>
                      </div>
                      <div class="wd-box-content wd-width-100" id="tr_popup_info_always_show">
                        <div class="wd-group">
                          <label class="wd-label"><?php _e('Display info by default', BWG()->prefix); ?></label>
                          <input type="radio" name="popup_info_always_show" id="popup_info_always_show_yes" value="1" <?php echo (BWG()->options->popup_info_always_show) ? 'checked="checked"' : ''; ?> /><label for="popup_info_always_show_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="popup_info_always_show" id="popup_info_always_show_no" value="0" <?php echo (BWG()->options->popup_info_always_show) ? '' : 'checked="checked"'; ?> /><label for="popup_info_always_show_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                          <p class="description"><?php _e("Enabling this option will let you show image title and description on lightbox by default.", BWG()->prefix); ?></p>
                        </div>
                      </div>
                      <div class="wd-box-content wd-width-100" id="tr_popup_info_full_width">
                        <div class="wd-group">
                          <label class="wd-label"><?php _e('Full width info', BWG()->prefix); ?></label>
                          <input type="radio" name="popup_info_full_width" id="popup_info_full_width_1" value="1" <?php if (BWG()->options->popup_info_full_width) echo 'checked="checked"'; ?>  /><label for="popup_info_full_width_1" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="popup_info_full_width" id="popup_info_full_width_0" value="0" <?php if (!BWG()->options->popup_info_full_width) echo 'checked="checked"'; ?>  /><label for="popup_info_full_width_0" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                          <p class="description"><?php _e("Display info box with the full width of the lightbox by enabling this option.", BWG()->prefix); ?></p>
                        </div>
                      </div>
                      <div class="wd-box-content wd-width-100" id="tr_popup_enable_rate">
                        <div class="wd-group">
                          <label class="wd-label"><?php _e('Enable rating', BWG()->prefix); ?></label>
                          <input type="radio" name="popup_enable_rate" id="popup_rate_yes" value="1" <?php echo (BWG()->options->popup_enable_rate) ? 'checked="checked"' : ''; ?> /><label for="popup_rate_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="popup_enable_rate" id="popup_rate_no" value="0" <?php echo (BWG()->options->popup_enable_rate) ? '' : 'checked="checked"'; ?> /><label for="popup_rate_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                          <p class="description"><?php _e("Allow users to rate your images by adding rating feature to Photo Gallery lightbox.", BWG()->prefix); ?></p>
                        </div>
                      </div>
                      <div class="wd-box-content wd-width-100" id="tr_popup_enable_comment">
                        <div class="wd-group">
                          <label class="wd-label"><?php _e('Enable comments', BWG()->prefix); ?></label>
                          <input type="radio" name="popup_enable_comment" id="popup_comment_yes" value="1" <?php echo (BWG()->options->popup_enable_comment) ? 'checked' : ''; ?> /><label for="popup_comment_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="popup_enable_comment" id="popup_comment_no" value="0" <?php echo (BWG()->options->popup_enable_comment) ? '' : 'checked'; ?> /><label for="popup_comment_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                          <p class="description"><?php _e("Let users to leave comments on images by enabling comments section of lightbox.", BWG()->prefix); ?></p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-33">
                    <div class="wd-box-content wd-width-100" id="tbody_popup_ctrl_btn2">
                      <div class="wd-box-content wd-width-100" id="tr_popup_enable_facebook">
                        <div class="wd-group">
                          <label class="wd-label"><?php _e('Show Facebook button', BWG()->prefix); ?></label>
                          <input type="radio" name="popup_enable_facebook" id="popup_facebook_yes" value="1" <?php echo (BWG()->options->popup_enable_facebook) ? 'checked' : ''; ?> /><label for="popup_facebook_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="popup_enable_facebook" id="popup_facebook_no" value="0" <?php echo (BWG()->options->popup_enable_facebook) ? '' : 'checked'; ?> /><label for="popup_facebook_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                          <p class="description"><?php _e("Enabling this setting will add Facebook sharing button to Photo Gallery lightbox.", BWG()->prefix); ?></p>
                        </div>
                      </div>
                      <div class="wd-box-content wd-width-100" id="tr_popup_enable_twitter">
                        <div class="wd-group">
                          <label class="wd-label"><?php _e('Show Twitter button', BWG()->prefix); ?></label>
                          <input type="radio" name="popup_enable_twitter" id="popup_twitter_yes" value="1" <?php echo (BWG()->options->popup_enable_twitter) ? 'checked' : ''; ?> /><label for="popup_twitter_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="popup_enable_twitter" id="popup_twitter_no" value="0" <?php echo (BWG()->options->popup_enable_twitter) ? '' : 'checked'; ?> /><label for="popup_twitter_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                          <p class="description"><?php _e("Enable this setting to add Tweet button to Photo Gallery lightbox.", BWG()->prefix); ?></p>
                        </div>
                      </div>
                      <div class="wd-box-content wd-width-100" id="tr_popup_enable_google">
                        <div class="wd-group">
                          <label class="wd-label"><?php _e('Show Google+ button', BWG()->prefix); ?></label>
                          <input type="radio" name="popup_enable_google" id="popup_google_yes" value="1" <?php echo (BWG()->options->popup_enable_google) ? 'checked' : ''; ?> /><label for="popup_google_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="popup_enable_google" id="popup_google_no" value="0" <?php echo (BWG()->options->popup_enable_google) ? '' : 'checked'; ?> /><label for="popup_google_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                          <p class="description"><?php _e("Add Google+ sharing button to Photo Gallery lightbox by activating this option.", BWG()->prefix); ?></p>
                        </div>
                      </div>
                      <div class="wd-box-content wd-width-100" id="tr_popup_enable_pinterest">
                        <div class="wd-group">
                          <label class="wd-label"><?php _e('Show Pinterest button', BWG()->prefix); ?></label>
                          <input type="radio" name="popup_enable_pinterest" id="popup_pinterest_yes" value="1" <?php echo (BWG()->options->popup_enable_pinterest) ? 'checked' : ''; ?> /><label for="popup_pinterest_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="popup_enable_pinterest" id="popup_pinterest_no" value="0" <?php echo (BWG()->options->popup_enable_pinterest) ? '' : 'checked'; ?> /><label for="popup_pinterest_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                          <p class="description"><?php _e("Activate Pin button of Photo Gallery lightbox by enabling this setting.", BWG()->prefix); ?></p>
                        </div>
                      </div>
                      <div class="wd-box-content wd-width-100" id="tr_popup_enable_tumblr">
                        <div class="wd-group">
                          <label class="wd-label"><?php _e('Show Tumblr button', BWG()->prefix); ?></label>
                          <input type="radio" name="popup_enable_tumblr" id="popup_tumblr_yes" value="1" <?php echo (BWG()->options->popup_enable_tumblr) ? 'checked' : ''; ?> /><label for="popup_tumblr_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                          <input type="radio" name="popup_enable_tumblr" id="popup_tumblr_no" value="0" <?php echo (BWG()->options->popup_enable_tumblr) ? '' : 'checked'; ?> /><label for="popup_tumblr_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                          <p class="description"><?php _e("Allow users to share images on Tumblr from Photo Gallery lightbox by activating this setting.", BWG()->prefix); ?></p>
                        </div>
                      </div>
                      <?php
                      if ( function_exists('BWGEC') ) {
                        ?>
                        <div class="wd-box-content wd-width-100" id="tr_popup_enable_ecommerce">
                          <div class="wd-group">
                            <label class="wd-label"><?php _e('Show Ecommerce button', BWG()->prefix); ?></label>
                            <input type="radio" name="popup_enable_ecommerce" id="popup_ecommerce_yes" value="1" <?php echo (BWG()->options->popup_enable_ecommerce) ? 'checked' : ''; ?> /><label for="popup_ecommerce_yes" class="wd-radio-label"><?php _e('Yes', BWG()->prefix); ?></label>
                            <input type="radio" name="popup_enable_ecommerce" id="popup_ecommerce_no" value="0" <?php echo (BWG()->options->popup_enable_ecommerce) ? '' : 'checked'; ?> /><label for="popup_ecommerce_no" class="wd-radio-label"><?php _e('No', BWG()->prefix); ?></label>
                            <p class="description"><?php _e("Enable this option to display ecommerce icon on Photo Gallery lightbox.", BWG()->prefix); ?></p>
                          </div>
                        </div>
                        <?php
                      }
                      ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="postbox closed">
              <button class="button-link handlediv" type="button" aria-expanded="true">
                <span class="screen-reader-text"><?php _e('Toggle panel', BWG()->prefix); ?></span>
                <span class="toggle-indicator" aria-hidden="false"></span>
              </button>
              <h2 class="hndle">
                <span><?php _e('Advanced', BWG()->prefix); ?></span>
              </h2>
              <div class="inside bwg-flex-wrap">
                <div class="wd-box-content wd-width-100" id="tr_watermark_type">
                  <div class="wd-group">
                    <label class="wd-label"><?php _e('Advertisement type', BWG()->prefix); ?></label>
                    <input type="radio" class="wd-radio" name="watermark_type" id="watermark_type_none" value="none" onClick="bwg_watermark('watermark_type_none')" <?php echo (BWG()->options->watermark_type == 'none') ? 'checked' : ''; ?> /><label for="watermark_type_none" class="wd-radio-label"><?php _e('None', BWG()->prefix); ?></label>
                    <input type="radio" class="wd-radio" name="watermark_type" id="watermark_type_text" value="text" onClick="bwg_watermark('watermark_type_text')" <?php echo (BWG()->options->watermark_type == 'text') ? 'checked' : ''; ?> /><label for="watermark_type_text" class="wd-radio-label"><?php _e('Text', BWG()->prefix); ?></label>
                    <input type="radio" class="wd-radio" name="watermark_type" id="watermark_type_image" value="image" onClick="bwg_watermark('watermark_type_image')" <?php echo (BWG()->options->watermark_type == 'image') ? 'checked' : ''; ?> /><label for="watermark_type_image" class="wd-radio-label"><?php _e('Image', BWG()->prefix); ?></label>
                    <p class="description"><?php _e("Add Text or Image advertisement to your images with this option.", BWG()->prefix); ?></p>
                  </div>
                </div>
                <div class="wd-box-content wd-width-33">
                  <div class="wd-box-content wd-width-100" id="tr_watermark_link">
                    <div class="wd-group">
                      <label class="wd-label" for="watermark_link"><?php _e('Advertisement link', BWG()->prefix); ?></label>
                      <input type="text" id="watermark_link" name="watermark_link" value="<?php echo BWG()->options->watermark_link; ?>" />
                      <p class="description"><?php _e("Provide the link to be added to advertisement on images.", BWG()->prefix); ?>, e.g. http://www.example.com</p>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-100" id="tr_watermark_url">
                    <div class="wd-group">
                      <label class="wd-label" for="watermark_url"><?php _e('Advertisement URL', BWG()->prefix); ?></label>
                      <input type="text" id="watermark_url" name="watermark_url" value="<?php echo BWG()->options->watermark_url; ?>" />
                      <p class="description"><?php _e("Provide the absolute URL of the image you would like to use as advertisement.", BWG()->prefix); ?></p>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-100" id="tr_watermark_text">
                    <div class="wd-group">
                      <label class="wd-label" for="watermark_text"><?php _e('Advertisement text', BWG()->prefix); ?></label>
                      <input type="text" name="watermark_text" id="watermark_text" value="<?php echo BWG()->options->watermark_text; ?>" />
                      <p class="description"><?php _e("Write the text to add to images as advertisement.", BWG()->prefix); ?></p>
                    </div>
                  </div>
                </div>
                <div class="wd-box-content wd-width-33">
                  <div class="wd-box-content wd-width-100" id="tr_watermark_font">
                    <div class="wd-group">
                      <label class="wd-label" for="watermark_font"><?php _e('Advertisement font style', BWG()->prefix); ?></label>
                      <select name="watermark_font" id="watermark_font">
                        <?php
                        $google_fonts = WDWLibrary::get_google_fonts();
                        $is_google_fonts = (in_array(BWG()->options->watermark_font, $google_fonts)) ? TRUE : FALSE;
                        $watermark_font_families = $is_google_fonts ? $google_fonts : $watermark_fonts;
                        foreach ( $watermark_font_families as $key => $watermark_font ) {
                          ?>
                          <option value="<?php echo $watermark_font; ?>" <?php echo (BWG()->options->watermark_font == $watermark_font) ? 'selected="selected"' : ''; ?>><?php echo $watermark_font; ?></option>
                          <?php
                        }
                        ?>
                      </select>
                      <input type="radio" class="wd-radio" name="watermark_google_fonts" id="watermark_google_fonts1" onchange="bwg_change_fonts('watermark_font', jQuery(this).attr('id'))" value="1" <?php if ($is_google_fonts) echo 'checked="checked"'; ?> />
                      <label for="watermark_google_fonts1" id="watermark_google_fonts1_lbl" class="wd-radio-label"><?php _e('Google fonts', BWG()->prefix); ?></label>
                      <input type="radio" class="wd-radio" name="watermark_google_fonts" id="watermark_google_fonts0" onchange="bwg_change_fonts('watermark_font', '')" value="0" <?php if (!$is_google_fonts) echo 'checked="checked"'; ?> />
                      <label for="watermark_google_fonts0" id="watermark_google_fonts0_lbl" class="wd-radio-label"><?php _e('Default', BWG()->prefix); ?></label>
                      <p class="description"><?php _e("Select the font family of the advertisement text.", BWG()->prefix); ?></p>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-100" id="tr_watermark_font_size">
                    <div class="wd-group">
                      <label class="wd-label" for="watermark_font_size"><?php _e('Advertisement font size', BWG()->prefix); ?></label>
                      <div class="bwg-flex">
                        <input type="text" name="watermark_font_size" id="watermark_font_size" value="<?php echo BWG()->options->watermark_font_size; ?>" class="spider_int_input" /><span>px</span>
                      </div>
                      <p class="description"><?php _e("Specify the font size of the advertisement text.", BWG()->prefix); ?></p>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-100" id="tr_watermark_width_height">
                    <div class="wd-group">
                      <label class="wd-label" for="watermark_width"><?php _e('Advertisement dimensions', BWG()->prefix); ?></label>
                      <div class="bwg-flex">
                        <input type="text" name="watermark_width" id="watermark_width" value="<?php echo BWG()->options->watermark_width; ?>" class="spider_int_input" /><span>x</span>
                        <input type="text" name="watermark_height" id="watermark_height" value="<?php echo BWG()->options->watermark_height; ?>" class="spider_int_input" /><span>px</span>
                      </div>
                      <p class="description"><?php _e("Select the dimensions of the advertisement image.", BWG()->prefix); ?></p>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-100" id="tr_watermark_color">
                    <div class="wd-group">
                      <label class="wd-label" for="watermark_color"><?php _e('Advertisement color', BWG()->prefix); ?></label>
                      <input type="text" name="watermark_color" id="watermark_color" value="<?php echo BWG()->options->watermark_color; ?>" class="color" />
                      <p class="description"><?php _e("Choose the color for the advertisement text on images.", BWG()->prefix); ?></p>
                    </div>
                  </div>
                </div>
                <div class="wd-box-content wd-width-33">
                  <div class="wd-box-content wd-width-100" id="tr_watermark_opacity">
                    <div class="wd-group">
                      <label class="wd-label" for="watermark_opacity"><?php _e('Advertisement opacity', BWG()->prefix); ?></label>
                      <div class="bwg-flex">
                        <input type="text" name="watermark_opacity" id="watermark_opacity" value="<?php echo BWG()->options->watermark_opacity; ?>" class="spider_int_input" /><span>%</span>
                      </div>
                      <p class="description"><?php echo __("Specify the opacity of the advertisement. The value must be between 0 to 100.", BWG()->prefix); ?></p>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-100" id="tr_watermark_position">
                    <div class="wd-group">
                      <label class="wd-label"><?php _e('Advertisement position', BWG()->prefix); ?></label>
                      <table class="bws_position_table">
                        <tbody>
                        <tr>
                          <td><input type="radio" class="wd-radio" value="top-left" id="watermark_top-left" name="watermark_position" <?php echo (BWG()->options->watermark_position == 'top-left') ? 'checked' : ''; ?>></td>
                          <td><input type="radio" class="wd-radio" value="top-center" id="watermark_top-center" name="watermark_position" <?php echo (BWG()->options->watermark_position == 'top-center') ? 'checked' : ''; ?>></td>
                          <td><input type="radio" class="wd-radio" value="top-right" id="watermark_top-right" name="watermark_position" <?php echo (BWG()->options->watermark_position == 'top-right') ? 'checked' : ''; ?>></td>
                        </tr>
                        <tr>
                          <td><input type="radio" class="wd-radio" value="middle-left" id="watermark_middle-left" name="watermark_position" <?php echo (BWG()->options->watermark_position == 'middle-left') ? 'checked' : ''; ?>></td>
                          <td><input type="radio" class="wd-radio" value="middle-center" id="watermark_middle-center" name="watermark_position" <?php echo (BWG()->options->watermark_position == 'middle-center') ? 'checked' : ''; ?>></td>
                          <td><input type="radio" class="wd-radio" value="middle-right" id="watermark_middle-right" name="watermark_position" <?php echo (BWG()->options->watermark_position == 'middle-right') ? 'checked' : ''; ?>></td>
                        </tr>
                        <tr>
                          <td><input type="radio" class="wd-radio" value="bottom-left" id="watermark_bottom-left" name="watermark_position" <?php echo (BWG()->options->watermark_position == 'bottom-left') ? 'checked' : ''; ?>></td>
                          <td><input type="radio" class="wd-radio" value="bottom-center" id="watermark_bottom-center" name="watermark_position" <?php echo (BWG()->options->watermark_position == 'bottom-center') ? 'checked' : ''; ?>></td>
                          <td><input type="radio" class="wd-radio" value="bottom-right" id="watermark_bottom-right" name="watermark_position" <?php echo (BWG()->options->watermark_position == 'bottom-right') ? 'checked' : ''; ?>></td>
                        </tr>
                        </tbody>
                      </table>
                      <p class="description"><?php echo __("Mark the position where the advertisement should appear on images.", BWG()->prefix); ?></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php
      if ( !$from_menu ) {
        ?>
        <div class="media-frame-toolbar">
          <div class="media-toolbar">
            <div class="media-toolbar-primary search-form">
              <button class="button media-button button-primary button-large media-button-insert button-hero" type="button" id="insert" name="insert" onClick="bwg_insert_shortcode('');"><?php _e('Insert into post', BWG()->prefix); ?></button>
            </div>
            <?php if ( !BWG()->is_pro ) { ?>
              <div class="media-toolbar-primary search-form" style="float: left;">
            <span class="media-button button-large">
              <a id="bwg_pro_version_link" class="bwg_link_shortcode" target="_blank" href="http://wpdemo.web-dorado.com/thumbnails-view-2/">Please see <span id="bwg_pro_version">Thumbnails</span> View in Premium version</a>
            </span>
              </div>
            <?php } ?>
          </div>
        </div>
        <?php
      }
      else {
        $tagtext = '';
        $tagfunction = '';
        if ( isset($_POST['currrent_id']) ) {
          $currrent_id = stripslashes($_POST['currrent_id']);
          $title = ((isset($_POST['title'])) ? stripslashes($_POST['title']) : '');
          $tagtext = '[Best_Wordpress_Gallery id="' . $currrent_id . '"' . $title . ']';
          $tagfunction = "<?php echo photo_gallery(" . $currrent_id . "); ?>";
        }
        ?>
        <hr />
        <div id="generate_button" class="wd-box-content wd-width-100">
          <div class="wd-box-content wd-width-50 bwg-flex">
            <select name="shortcode" id="shortcode" onchange="bwg_update_shortcode()">
              <option value=""><?php _e('New shortcode', BWG()->prefix); ?></option>
              <?php
              foreach ( $shortcodes as $shortcode ) {
                ?>
                <option value="<?php echo $shortcode->id; ?>">[Best_Wordpress_Gallery id="<?php echo $shortcode->id; ?>"]</option>
                <?php
              }
              ?>
            </select>
            <button class="button media-button button-primary button-large media-button-insert" type="button" id="insert" name="insert" onClick="jQuery('#loading_div').show(); bwg_insert_shortcode('');"><?php _e('Generate', BWG()->prefix); ?></button>
          </div>
          <p class="description"><?php _e('If you would like to edit an existing shortcode, use this dropdown box to select it.', BWG()->prefix) ?></p>
          <div class="wd-box-content wd-width-100 bwg-flex-wrap">
            <div class="wd-box-content wd-width-50">
              <div class="wd-group">
                <label class="wd-label" for="bwg_shortcode"><?php _e('Shortcode', BWG()->prefix); ?></label>
                <input type="text" id="bwg_shortcode" name="bwg_shortcode" value='<?php echo $tagtext; ?>' onclick="spider_select_value(this)" readonly="readonly" />
                <p class="description"><?php _e('Add the selected gallery or gallery group to any WordPress page or post. Simply copy the generated shortcode and paste it in the content of page/post editor.', BWG()->prefix) ?></p>
              </div>
            </div>
            <div class="wd-box-content wd-width-50">
              <div class="wd-group">
                <label class="wd-label" for="bwg_function"><?php _e('PHP function', BWG()->prefix); ?></label>
                <input type="text" id="bwg_function" name="bwg_function" value="<?php echo $tagfunction; ?>" onclick="spider_select_value(this)" readonly="readonly" />
                <p class="description"><?php _e('Use generated PHP function to call the selected gallery or gallery group on a custom PHP template.', BWG()->prefix) ?></p>
              </div>
            </div>
          </div>
        </div>
        <?php
      }
      ?>
    </div>
    <div id="loading_div" <?php echo ( $from_menu ) ? 'class="bwg_show"' : ''; ?>></div>
	<?php
  }

  public function generate_script( $params ) {
    $from_menu = $params['from_menu'];
    $shortcodes = $params['shortcodes'];
    $shortcode_max_id = $params['shortcode_max_id'];
    ob_start();
    ?>
    <script type="text/javascript">
      var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
      var shortcodes = [];
      var shortcode_id = 1;
      var temp_shortcode_id = 0;
      <?php
      foreach ($shortcodes as $shortcode) {
      ?>
      shortcodes[<?php echo $shortcode->id; ?>] = '<?php echo addslashes($shortcode->tagtext); ?>';
      <?php
      }
      ?>
      shortcode_id = <?php echo $shortcode_max_id + 1; ?>;
      var params = get_params("Best_Wordpress_Gallery");
      var bwg_insert = 1;
      <?php
      if ($params['gutenberg_callback']) {
        if ($params['gutenberg_id'] == 0) {
      ?>
      var content = '';
      <?php
      }
      else {
      ?>
      var content = '[Best_Wordpress_Gallery id="<?php echo $params['gutenberg_id']; ?>"]';
      <?php
      }
      }
      elseif (!$from_menu) { ?>
      var content;
      if (top.tinyMCE.activeEditor) {
        content = top.tinyMCE.activeEditor.selection.getContent();
      }
      else {
        content = bwg_get_textarea_selection(top.wpActiveEditor);
      }
      <?php } else { ?>
      var content = jQuery("#bwg_shortcode").val();
      <?php } ?>
      function bwg_update_shortcode() {
        params = get_params("Best_Wordpress_Gallery");
        if (!params) { // Insert.
          <?php if ($from_menu) { ?>
          jQuery('#insert').text('<?php _e('Generate', BWG()->prefix); ?>');
          bwg_insert = 1;
          if (temp_shortcode_id !== 0) {
            shortcode_id = temp_shortcode_id;
          }
          <?php
          }
          ?>
          jQuery("#use_option_defaults").prop('checked', true).trigger('change');
          jQuery("#bwg_shortcode").val('');
          jQuery("#bwg_function").val('');
          jQuery(".bwg_tabs").tabs({active: 0});
          bwg_gallery_type('thumbnails');
        }
        else { // Update.
          if (params['id']) {
            shortcode_id = params['id'];
            if (typeof shortcodes[shortcode_id] === 'undefined') {
              alert("<?php echo addslashes(__('There is no shortcode with such ID!', BWG()->prefix)); ?>");
              bwg_gallery_type('thumbnails');
              return 0;
            }
            var short_code = get_short_params(shortcodes[shortcode_id]);
            bwg_insert = 0;
            jQuery("#bwg_shortcode").val('[Best_Wordpress_Gallery id="' + shortcode_id + '"]');
            var str = "&#60;?php echo photo_gallery(" + shortcode_id + "); ?&#62;";
            jQuery("#bwg_function").val(str.replace("&#60;", '<').replace("&#62;", '>'));
          }
          else {
            var short_code = get_params("Best_Wordpress_Gallery");
          }
          jQuery('#insert').text('<?php _e('Update', BWG()->prefix); ?>');
          <?php if ($from_menu) { ?>
          content = jQuery("#bwg_shortcode").val();
          <?php } ?>
          jQuery('#insert').attr('onclick', "jQuery('#loading_div').show(); bwg_insert_shortcode(content);");
          jQuery("select[id=theme] option[value='" + short_code['theme_id'] + "']").attr('selected', 'selected');
          jQuery("select[id=gallery_types_name] option[value='" + short_code['gallery_type'] + "']").attr('selected', 'selected');
          jQuery("#use_option_defaults").prop('checked', true).trigger('change');
          if (short_code['type'] == 'album' || short_code['gallery_type'] == 'album_compact_preview' || short_code['gallery_type'] == 'album_masonry_preview' || short_code['gallery_type'] == 'album_extended_preview') {
            short_code['type'] = 'album';
            jQuery(".bwg_tabs").tabs({active: 1});
          }
          else {
            short_code['type'] = 'gallery';
            jQuery(".bwg_tabs").tabs({active: 0});
          }
          jQuery("select[id=gallery] option[value='" + short_code['gallery_id'] + "']").attr('selected', 'selected');
          jQuery("select[id=album] option[value='" + short_code['album_id'] + "']").attr('selected', 'selected');
          jQuery("select[id=tag] option[value='" + short_code['tag'] + "']").attr('selected', 'selected');
          bwg_gallery_type(short_code['gallery_type']);
          if (short_code['use_option_defaults'] == 1) {
            return false;
          }
          jQuery("#use_option_defaults").prop('checked', false).trigger('change');
          switch (short_code['gallery_type']) {
            case 'thumbnails': {
              jQuery("#thumb_width").val(short_code['thumb_width']);
              jQuery("#thumb_height").val(short_code['thumb_height']);
              jQuery("#image_column_number").val(short_code['image_column_number']);
              if (short_code['image_enable_page'] == 1) {
                jQuery("#image_enable_page_1").attr('checked', 'checked');
              }
              else if (short_code['image_enable_page'] == 0) {
                jQuery("#image_enable_page_0").attr('checked', 'checked');
              }
              else if (short_code['image_enable_page'] == 2) {
                jQuery("#image_enable_page_2").attr('checked', 'checked');
              }
              else if (short_code['image_enable_page'] == 3) {
                jQuery("#image_enable_page_3").attr('checked', 'checked');
              }
              jQuery("#images_per_page").val(short_code['images_per_page']);
              jQuery("#load_more_image_count").val(short_code['load_more_image_count']);
              jQuery("select[id=sort_by] option[value='" + short_code['sort_by'] + "']").attr('selected', 'selected');
              if (short_code['order_by'] == 'asc') {
                jQuery("#order_by_1").attr('checked', 'checked');
              }
              else {
                jQuery("#order_by_0").attr('checked', 'checked');
              }
              if (short_code['show_search_box'] == 1) {
                jQuery("#show_search_box_1").attr('checked', 'checked');
              }
              else {
                jQuery("#show_search_box_0").attr('checked', 'checked');
              }
              if (short_code['placeholder']) {
                jQuery("#placeholder").val(short_code['placeholder']);
              }
              if (short_code['search_box_width']) {
                jQuery("#search_box_width").val(short_code['search_box_width']);
              }
              if (short_code['show_sort_images'] == 1) {
                jQuery("#show_sort_images_1").attr('checked', 'checked');
              }
              else {
                jQuery("#show_sort_images_0").attr('checked', 'checked');
              }
              if (short_code['show_tag_box'] == 1) {
                jQuery("#show_tag_box_1").attr('checked', 'checked');
              }
              else {
                jQuery("#show_tag_box_0").attr('checked', 'checked');
              }
              if (short_code['showthumbs_name'] == 1) {
                jQuery("#thumb_name_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#thumb_name_no").attr('checked', 'checked');
              }
              if (short_code['show_gallery_description'] == 1) {
                jQuery("#show_gallery_description_1").attr('checked', 'checked');
              }
              else {
                jQuery("#show_gallery_description_0").attr('checked', 'checked');
              }
              if (short_code['image_title'] == 'hover') {
                jQuery("#image_title_show_hover_1").attr('checked', 'checked');
              }
              else if (short_code['image_title'] == 'show') {
                jQuery("#image_title_show_hover_0").attr('checked', 'checked');
              }
              else {
                jQuery("#image_title_show_hover_2").attr('checked', 'checked');
              }
              if (short_code['play_icon'] == 1) {
                jQuery("#play_icon_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#play_icon_no").attr('checked', 'checked');
              }
              if (short_code['gallery_download'] == 1) {
                jQuery("#gallery_download_1").attr('checked', 'checked');
              }
              else {
                jQuery("#gallery_download_0").attr('checked', 'checked');
              }
              if (short_code['ecommerce_icon'] == 'hover') {
                jQuery("#ecommerce_icon_show_hover_1").attr('checked', 'checked');
              }
              else if (short_code['ecommerce_icon'] == 'show') {
                jQuery("#ecommerce_icon_show_hover_0").attr('checked', 'checked');
              }
              else {
                jQuery("#ecommerce_icon_show_hover_2").attr('checked', 'checked');
              }
              break;
            }
            case 'thumbnails_masonry': {
              if (short_code['masonry_hor_ver'] == 'horizontal') {
                jQuery("#masonry_0").prop('checked', false).removeAttr('checked');
                jQuery("#masonry_1").attr('checked', 'checked');
                jQuery("#masonry_thumb_size").val(short_code['thumb_height']);
                jQuery('.masonry_col_num').hide();
                jQuery('.masonry_row_num').show();
              }
              else {
                jQuery("#masonry_0").attr('checked', 'checked');
                jQuery("#masonry_thumb_size").val(short_code['thumb_width']);
                jQuery('.masonry_row_num').hide();
                jQuery('.masonry_col_num').show();
              }
              if (short_code['show_masonry_thumb_description'] == 1) {
                jQuery("#masonry_thumb_desc_1").attr('checked', 'checked');
              }
              else {
                jQuery("#masonry_thumb_desc_0").attr('checked', 'checked');
              }
              jQuery("#masonry_image_column_number").val(short_code['image_column_number']);
              if (short_code['image_enable_page'] == 1) {
                jQuery("#masonry_image_enable_page_1").attr('checked', 'checked');
              }
              else if (short_code['image_enable_page'] == 0) {
                jQuery("#masonry_image_enable_page_0").attr('checked', 'checked');
              }
              else if (short_code['image_enable_page'] == 2) {
                jQuery("#masonry_image_enable_page_2").attr('checked', 'checked');
              }
              else if (short_code['image_enable_page'] == 3) {
                jQuery("#masonry_image_enable_page_3").attr('checked', 'checked');
              }
              jQuery("#masonry_images_per_page").val(short_code['images_per_page']);
              jQuery("#masonry_load_more_image_count").val(short_code['load_more_image_count']);
              jQuery("select[id=masonry_sort_by] option[value='" + short_code['sort_by'] + "']").attr('selected', 'selected');
              if (short_code['order_by'] == 'asc') {
                jQuery("#masonry_order_by_1").attr('checked', 'checked');
              }
              else {
                jQuery("#masonry_order_by_0").attr('checked', 'checked');
              }
              if (short_code['show_search_box'] == 1) {
                jQuery("#masonry_show_search_box_1").attr('checked', 'checked');
              }
              else {
                jQuery("#masonry_show_search_box_0").attr('checked', 'checked');
              }
              if (short_code['placeholder']) {
                jQuery("#masonry_placeholder").val(short_code['placeholder']);
              }
              if (short_code['search_box_width']) {
                jQuery("#masonry_search_box_width").val(short_code['search_box_width']);
              }
              else if (short_code['image_enable_page'] == 2) {
                jQuery("#masonry_image_page_loadmore").attr('checked', 'checked');
              }
              if (short_code['show_sort_images'] == 1) {
                jQuery("#masonry_show_sort_images_1").attr('checked', 'checked');
              }
              else {
                jQuery("#masonry_show_sort_images_0").attr('checked', 'checked');
              }
              if (short_code['show_tag_box'] == 1) {
                jQuery("#masonry_show_tag_box_1").attr('checked', 'checked');
              }
              else {
                jQuery("#masonry_show_tag_box_0").attr('checked', 'checked');
              }
              if (short_code['showthumbs_name'] == 1) {
                jQuery("#masonry_thumb_name_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#masonry_thumb_name_no").attr('checked', 'checked');
              }
              if (short_code['show_gallery_description'] == 1) {
                jQuery("#masonry_show_gallery_description_1").attr('checked', 'checked');
              }
              else {
                jQuery("#masonry_show_gallery_description_0").attr('checked', 'checked');
              }
              if (short_code['play_icon'] == 1) {
                jQuery("#masonry_play_icon_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#masonry_play_icon_no").attr('checked', 'checked');
              }
              if (short_code['gallery_download'] == 1) {
                jQuery("#masonry_gallery_download_1").attr('checked', 'checked');
              }
              else {
                jQuery("#masonry_gallery_download_0").attr('checked', 'checked');
              }
              if (short_code['ecommerce_icon'] == 'hover') {
                jQuery("#masonry_ecommerce_icon_show_hover_1").attr('checked', 'checked');
              }
              else {
                jQuery("#masonry_ecommerce_icon_show_hover_2").attr('checked', 'checked');
              }
              break;
            }
            case 'thumbnails_mosaic': {
              if (short_code['mosaic_hor_ver'] == 'horizontal') {
                jQuery("#mosaic_1").attr('checked', 'checked');
                jQuery("#mosaic_thumb_size").val(short_code['thumb_height']);
              }
              else {
                jQuery("#mosaic_0").attr('checked', 'checked');
                jQuery("#mosaic_thumb_size").val(short_code['thumb_width']);
              }
              if (short_code['resizable_mosaic'] == 1) {
                jQuery("#resizable_mosaic_1").attr('checked', 'checked');
              }
              else {
                jQuery("#resizable_mosaic_0").attr('checked', 'checked');
              }
              jQuery("#mosaic_total_width").val(short_code['mosaic_total_width']);
              if (short_code['image_enable_page'] == 1) {
                jQuery("#mosaic_image_enable_page_1").attr('checked', 'checked');
              }
              else if (short_code['image_enable_page'] == 0) {
                jQuery("#mosaic_image_enable_page_0").attr('checked', 'checked');
              }
              else if (short_code['image_enable_page'] == 2) {
                jQuery("#mosaic_image_enable_page_2").attr('checked', 'checked');
              }
              else if (short_code['image_enable_page'] == 3) {
                jQuery("#mosaic_image_enable_page_3").attr('checked', 'checked');
              }
              jQuery("#mosaic_images_per_page").val(short_code['images_per_page']);
              jQuery("#mosaic_load_more_image_count").val(short_code['load_more_image_count']);
              jQuery("select[id=mosaic_sort_by] option[value='" + short_code['sort_by'] + "']").attr('selected', 'selected');
              if (short_code['order_by'] == 'asc') {
                jQuery("#mosaic_order_by_1").attr('checked', 'checked');
              }
              else {
                jQuery("#mosaic_order_by_0").attr('checked', 'checked');
              }
              if (short_code['show_search_box'] == 1) {
                jQuery("#mosaic_show_search_box_1").attr('checked', 'checked');
              }
              else {
                jQuery("#mosaic_show_search_box_0").attr('checked', 'checked');
              }
              if (short_code['placeholder']) {
                jQuery("#mosaic_placeholder").val(short_code['placeholder']);
              }
              if (short_code['search_box_width']) {
                jQuery("#mosaic_search_box_width").val(short_code['search_box_width']);
              }
              if (short_code['show_sort_images'] == 1) {
                jQuery("#mosaic_show_sort_images_1").attr('checked', 'checked');
              }
              else {
                jQuery("#mosaic_show_sort_images_0").attr('checked', 'checked');
              }
              if (short_code['show_tag_box'] == 1) {
                jQuery("#mosaic_show_tag_box_1").attr('checked', 'checked');
              }
              else {
                jQuery("#mosaic_show_tag_box_0").attr('checked', 'checked');
              }
              if (short_code['showthumbs_name'] == 1) {
                jQuery("#mosaic_thumb_name_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#mosaic_thumb_name_no").attr('checked', 'checked');
              }
              if (short_code['show_gallery_description'] == 1) {
                jQuery("#mosaic_show_gallery_description_1").attr('checked', 'checked');
              }
              else {
                jQuery("#mosaic_show_gallery_description_0").attr('checked', 'checked');
              }
              if (short_code['image_title'] == 'hover') {
                jQuery("#mosaic_image_title_show_hover_1").attr('checked', 'checked');
              }
              else if (short_code['image_title'] == 'show') {
                jQuery("#mosaic_image_title_show_hover_0").attr('checked', 'checked');
              }
              else {
                jQuery("#mosaic_image_title_show_hover_2").attr('checked', 'checked');
              }
              if (short_code['play_icon'] == 1) {
                jQuery("#mosaic_play_icon_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#mosaic_play_icon_no").attr('checked', 'checked');
              }
              if (short_code['gallery_download'] == 1) {
                jQuery("#mosaic_gallery_download_1").attr('checked', 'checked');
              }
              else {
                jQuery("#mosaic_gallery_download_0").attr('checked', 'checked');
              }
              if (short_code['ecommerce_icon'] == 'hover') {
                jQuery("#mosaic_ecommerce_icon_show_hover_1").attr('checked', 'checked');
              }
              else {
                jQuery("#mosaic_ecommerce_icon_show_hover_2").attr('checked', 'checked');
              }
              break;
            }
            case 'slideshow': {
              jQuery("select[id=slideshow_type] option[value='" + short_code['slideshow_effect'] + "']").attr('selected', 'selected');
              jQuery("#slideshow_interval").val(short_code['slideshow_interval']);
              jQuery("#slideshow_width").val(short_code['slideshow_width']);
              jQuery("#slideshow_height").val(short_code['slideshow_height']);
              jQuery("select[id=slideshow_sort_by] option[value='" + short_code['sort_by'] + "']").attr('selected', 'selected');
              if (short_code['order_by'] == 'asc') {
                jQuery("#slideshow_order_by_1").attr('checked', 'checked');
              }
              else {
                jQuery("#slideshow_order_by_0").attr('checked', 'checked');
              }
              if (short_code['enable_slideshow_autoplay'] == 1) {
                jQuery("#slideshow_enable_autoplay_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#slideshow_enable_autoplay_no").attr('checked', 'checked');
              }
              if (short_code['enable_slideshow_shuffle'] == 1) {
                jQuery("#slideshow_enable_shuffle_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#slideshow_enable_shuffle_no").attr('checked', 'checked');
              }
              if (short_code['enable_slideshow_ctrl'] == 1) {
                jQuery("#slideshow_enable_ctrl_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#slideshow_enable_ctrl_no").attr('checked', 'checked');
              }
              if (short_code['autohide_slideshow_navigation'] == 1) {
                jQuery("#autohide_slideshow_navigation_1").attr('checked', 'checked');
              }
              else {
                jQuery("#autohide_slideshow_navigation_0").attr('checked', 'checked');
              }
              if (short_code['enable_slideshow_filmstrip'] == 1) {
                jQuery("#slideshow_enable_filmstrip_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#slideshow_enable_filmstrip_no").attr('checked', 'checked');
              }
              if (short_code['slideshow_filmstrip_height']) {
                jQuery( "#slideshow_filmstrip_height" ).val( short_code['slideshow_filmstrip_height'] );
              }
              if (short_code['slideshow_enable_title'] == 1) {
                jQuery("#slideshow_enable_title_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#slideshow_enable_title_no").attr('checked', 'checked');
              }
              if (short_code['slideshow_title_position']) {
                jQuery( "input[name=slideshow_title_position][value=" + short_code['slideshow_title_position'] + "]" ).attr( 'checked', 'checked' );
              }
              if (short_code['slideshow_title_full_width']) {
                jQuery( "#slideshow_title_full_width_" + short_code['slideshow_title_full_width'] ).attr( 'checked', 'checked' );
              }
              if (short_code['slideshow_enable_description'] == 1) {
                jQuery("#slideshow_enable_description_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#slideshow_enable_description_no").attr('checked', 'checked');
              }
              if (short_code['slideshow_description_position']) {
                jQuery("input[name=slideshow_description_position][value=" + short_code['slideshow_description_position'] + "]").attr('checked', 'checked');
              }
              if (short_code['enable_slideshow_music'] == 1) {
                jQuery("#slideshow_enable_music_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#slideshow_enable_music_no").attr('checked', 'checked');
              }
              if (short_code['slideshow_music_url']) {
                jQuery("#slideshow_audio_url").val(short_code['slideshow_music_url']);
              }
              jQuery("#slideshow_effect_duration").val(short_code['slideshow_effect_duration']);
              if (short_code['gallery_download'] == 1) {
                jQuery("#slideshow_gallery_download_1").attr('checked', 'checked');
              }
              else {
                jQuery("#slideshow_gallery_download_0").attr('checked', 'checked');
              }
              break;
            }
            case 'image_browser': {
              jQuery("#image_browser_width").val(short_code['image_browser_width']);
              if (short_code['image_browser_title_enable']) {
                jQuery("#image_browser_title_enable_" + short_code['image_browser_title_enable']).attr('checked', 'checked');
              }
              if (short_code['image_browser_description_enable']) {
                jQuery("#image_browser_description_enable_" + short_code['image_browser_description_enable']).attr('checked', 'checked');
              }
              jQuery("select[id=image_browser_sort_by] option[value='" + short_code['sort_by'] + "']").attr('selected', 'selected');
              if (short_code['order_by'] == 'asc') {
                jQuery("#image_browser_order_by_1").attr('checked', 'checked');
              }
              else {
                jQuery("#image_browser_order_by_0").attr('checked', 'checked');
              }
              if (short_code['showthumbs_name'] == 1) {
                jQuery("#image_browser_thumb_name_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#image_browser_thumb_name_no").attr('checked', 'checked');
              }
              if (short_code['show_gallery_description']) {
                jQuery("#image_browser_show_gallery_description_" + short_code['show_gallery_description']).attr('checked', 'checked');
              }
              if (short_code['show_search_box']) {
                jQuery("#image_browser_show_search_box_" + short_code['show_search_box']).attr('checked', 'checked');
              }
              if (short_code['placeholder']) {
                jQuery("#image_browser_placeholder").val(short_code['placeholder']);
              }
              if (short_code['search_box_width']) {
                jQuery("#image_browser_search_box_width").val(short_code['search_box_width']);
              }
              if (short_code['gallery_download'] == 1) {
                jQuery("#image_browser_gallery_download_1").attr('checked', 'checked');
              }
              else {
                jQuery("#image_browser_gallery_download_0").attr('checked', 'checked');
              }
              break;
            }
            case 'blog_style': {
              jQuery("#blog_style_width").val(short_code['blog_style_width']);
              if (short_code['blog_style_title_enable'] == 1) {
                jQuery("#blog_style_title_enable_1").attr('checked', 'checked');
              }
              else {
                jQuery("#blog_style_title_enable_0").attr('checked', 'checked');
              }
              jQuery("#blog_style_images_per_page").val(short_code['blog_style_images_per_page']);
              jQuery("#blog_style_load_more_image_count").val(short_code['blog_style_load_more_image_count']);
              if (short_code['blog_style_enable_page'] == 1) {
                jQuery("#blog_style_enable_page_1").attr('checked', 'checked');
              }
              else if (short_code['blog_style_enable_page'] == 0) {
                jQuery("#blog_style_enable_page_0").attr('checked', 'checked');
              }
              else if (short_code['blog_style_enable_page'] == 2) {
                jQuery("#blog_style_enable_page_2").attr('checked', 'checked');
              }
              else if (short_code['blog_style_enable_page'] == 3) {
                jQuery("#blog_style_enable_page_3").attr('checked', 'checked');
              }
              if (short_code['blog_style_description_enable'] == 1) {
                jQuery("#blog_style_description_enable_1").attr('checked', 'checked');
              }
              else {
                jQuery("#blog_style_description_enable_0").attr('checked', 'checked');
              }
              jQuery("select[id=blog_style_sort_by] option[value='" + short_code['sort_by'] + "']").attr('selected', 'selected');
              if (short_code['order_by'] == 'asc') {
                jQuery("#blog_style_order_by_1").attr('checked', 'checked');
              }
              else {
                jQuery("#blog_style_order_by_0").attr('checked', 'checked');
              }
              if (short_code['showthumbs_name'] == 1) {
                jQuery("#blog_style_thumb_name_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#blog_style_thumb_name_no").attr('checked', 'checked');
              }
              if (short_code['show_gallery_description'] == 1) {
                jQuery("#blog_style_show_gallery_description_1").attr('checked', 'checked');
              }
              else {
                jQuery("#blog_style_show_gallery_description_0").attr('checked', 'checked');
              }
              if (short_code['show_search_box'] == 1) {
                jQuery("#blog_style_show_search_box_1").attr('checked', 'checked');
              }
              else {
                jQuery("#blog_style_show_search_box_0").attr('checked', 'checked');
              }
              if (short_code['placeholder']) {
                jQuery("#blog_style_placeholder").val(short_code['placeholder']);
              }
              if (short_code['search_box_width']) {
                jQuery("#blog_style_search_box_width").val(short_code['search_box_width']);
              }
              if (short_code['show_sort_images'] == 1) {
                jQuery("#blog_style_show_sort_images_1").attr('checked', 'checked');
              }
              else {
                jQuery("#blog_style_show_sort_images_0").attr('checked', 'checked');
              }
              if (short_code['show_tag_box'] == 1) {
                jQuery("#blog_style_show_tag_box_1").attr('checked', 'checked');
              }
              else {
                jQuery("#blog_style_show_tag_box_0").attr('checked', 'checked');
              }
              if (short_code['gallery_download'] == 1) {
                jQuery("#blog_style_gallery_download_1").attr('checked', 'checked');
              }
              else {
                jQuery("#blog_style_gallery_download_0").attr('checked', 'checked');
              }
              break;
            }
            case 'carousel': {
              jQuery("#carousel_interval").val(short_code['carousel_interval']);
              jQuery("#carousel_width").val(short_code['carousel_width']);
              jQuery("#carousel_height").val(short_code['carousel_height']);
              jQuery("#carousel_image_column_number").val(short_code['carousel_image_column_number']);
              jQuery("#carousel_image_par").val(short_code['carousel_image_par']);
              if (short_code['enable_carousel_title'] == 1) {
                jQuery("#carousel_enable_title_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#carousel_enable_title_no").attr('checked', 'checked');
              }
              if (short_code['enable_carousel_autoplay'] == 1) {
                jQuery("#carousel_enable_autoplay_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#carousel_enable_autoplay_no").attr('checked', 'checked');
              }
              jQuery("#carousel_r_width").val(short_code['carousel_r_width']);
              if (short_code['carousel_fit_containerWidth'] == 1) {
                jQuery("#carousel_fit_containerWidth_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#carousel_fit_containerWidth_no").attr('checked', 'checked');
              }
              if (short_code['carousel_prev_next_butt'] == 1) {
                jQuery("#carousel_prev_next_butt_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#carousel_prev_next_butt_no").attr('checked', 'checked');
              }
              if (short_code['carousel_play_pause_butt'] == 1) {
                jQuery("#carousel_play_pause_butt_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#carousel_play_pause_butt_no").attr('checked', 'checked');
              }
              jQuery("select[id=carousel_sort_by] option[value='" + short_code['sort_by'] + "']").attr('selected', 'selected');
              if (short_code['order_by'] == 'asc') {
                jQuery("#carousel_order_by_1").attr('checked', 'checked');
              }
              else {
                jQuery("#carousel_order_by_0").attr('checked', 'checked');
              }
              if (short_code['gallery_download'] == 1) {
                jQuery("#carousel_gallery_download_1").attr('checked', 'checked');
              }
              else {
                jQuery("#carousel_gallery_download_0").attr('checked', 'checked');
              }
              break;
            }
            case 'album_compact_preview': {
              jQuery("#album_column_number").val(short_code['compuct_album_column_number']);
              jQuery("#album_thumb_width").val(short_code['compuct_album_thumb_width']);
              jQuery("#album_thumb_height").val(short_code['compuct_album_thumb_height']);
              jQuery("#album_image_column_number").val(short_code['compuct_album_image_column_number']);
              jQuery("#album_image_thumb_width").val(short_code['compuct_album_image_thumb_width']);
              jQuery("#album_image_thumb_height").val(short_code['compuct_album_image_thumb_height']);
              if (short_code['compuct_album_enable_page']) {
                jQuery("#album_enable_page_" + short_code['compuct_album_enable_page']).attr('checked', 'checked');
              }
              jQuery("#albums_per_page").val(short_code['compuct_albums_per_page']);
              jQuery("#album_images_per_page").val(short_code['compuct_album_images_per_page']);
              jQuery("select[id=album_sort_by] option[value='" + short_code['sort_by'] + "']").attr('selected', 'selected');
              if (short_code['order_by'] == 'asc') {
                jQuery("#album_order_by_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_order_by_0").attr('checked', 'checked');
              }
              if (short_code['show_search_box'] == 1) {
                jQuery("#album_show_search_box_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_show_search_box_0").attr('checked', 'checked');
              }
              if (short_code['placeholder']) {
                jQuery("#album_placeholder").val(short_code['placeholder']);
              }
              if (short_code['search_box_width']) {
                jQuery("#album_search_box_width").val(short_code['search_box_width']);
              }
              if (short_code['show_sort_images'] == 1) {
                jQuery("#album_show_sort_images_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_show_sort_images_0").attr('checked', 'checked');
              }
              if (short_code['show_tag_box'] == 1) {
                jQuery("#album_show_tag_box_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_show_tag_box_0").attr('checked', 'checked');
              }
              if (short_code['show_album_name'] == 1) {
                jQuery("#show_album_name_enable_1").attr('checked', 'checked');
              }
              else {
                jQuery("#show_album_name_enable_0").attr('checked', 'checked');
              }
              if (short_code['show_gallery_description'] == 1) {
                jQuery("#album_show_gallery_description_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_show_gallery_description_0").attr('checked', 'checked');
              }
              jQuery("input[name=album_title_show_hover][value=" + short_code['compuct_album_title'] + "]").attr('checked', 'checked');
              if (short_code['compuct_album_view_type'] == 'thumbnail') {
                jQuery("#album_view_type_1").attr('checked', 'checked');
              }
              else if (short_code['compuct_album_view_type'] == 'masonry') {
                jQuery("#album_view_type_0").attr('checked', 'checked');
              }
              else {
                jQuery("#album_view_type_2").attr('checked', 'checked');
              }
              jQuery("input[name=album_image_title_show_hover][value=" + short_code['compuct_album_image_title'] + "]").attr('checked', 'checked');
              if (short_code['compuct_album_mosaic_hor_ver'] == "vertical") {
                jQuery("#album_mosaic_0").attr('checked', 'checked');
              }
              else {
                jQuery("#album_mosaic_1").attr('checked', 'checked');
              }
              if (short_code['compuct_album_resizable_mosaic'] == 1) {
                jQuery("#album_resizable_mosaic_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_resizable_mosaic_0").attr('checked', 'checked');
              }
              jQuery("#album_mosaic_total_width").val(short_code['compuct_album_mosaic_total_width']);
              if (short_code['play_icon'] == 1) {
                jQuery("#album_play_icon_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#album_play_icon_no").attr('checked', 'checked');
              }
              if (short_code['gallery_download'] == 1) {
                jQuery("#album_gallery_download_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_gallery_download_0").attr('checked', 'checked');
              }
              if (short_code['ecommerce_icon'] == 'hover') {
                jQuery("#album_ecommerce_icon_show_hover_1").attr('checked', 'checked');
              }
              else if (short_code['ecommerce_icon'] == 'show') {
                jQuery("#album_ecommerce_icon_show_hover_0").attr('checked', 'checked');
              }
              else {
                jQuery("#album_ecommerce_icon_show_hover_2").attr('checked', 'checked');
              }
              break;
            }
            case 'album_masonry_preview': {
              jQuery("#album_masonry_column_number").val(short_code['masonry_album_column_number']);
              jQuery("#album_masonry_thumb_width").val(short_code['masonry_album_thumb_width']);
              jQuery("#album_masonry_image_column_number").val(short_code['masonry_album_image_column_number']);
              jQuery("#album_masonry_image_thumb_width").val(short_code['masonry_album_image_thumb_width']);
              if (short_code['masonry_album_enable_page']) {
                jQuery("#album_masonry_enable_page_" + short_code['masonry_album_enable_page']).attr('checked', 'checked');
              }
              jQuery("#albums_masonry_per_page").val(short_code['masonry_albums_per_page']);
              jQuery("#album_masonry_images_per_page").val(short_code['masonry_album_images_per_page']);
              jQuery("select[id=album_masonry_sort_by] option[value='" + short_code['sort_by'] + "']").attr('selected', 'selected');
              if (short_code['order_by'] == 'asc') {
                jQuery("#album_masonry_order_by_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_masonry_order_by_0").attr('checked', 'checked');
              }
              if (short_code['show_search_box'] == 1) {
                jQuery("#album_masonry_show_search_box_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_masonry_show_search_box_0").attr('checked', 'checked');
              }
              if (short_code['placeholder']) {
                jQuery("#album_masonry_placeholder").val(short_code['placeholder']);
              }
              if (short_code['search_box_width']) {
                jQuery("#album_masonry_search_box_width").val(short_code['search_box_width']);
              }
              if (short_code['show_sort_images'] == 1) {
                jQuery("#album_masonry_show_sort_images_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_masonry_show_sort_images_0").attr('checked', 'checked');
              }
              if (short_code['show_tag_box'] == 1) {
                jQuery("#album_masonry_show_tag_box_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_masonry_show_tag_box_0").attr('checked', 'checked');
              }
              if (short_code['show_album_name'] == 1) {
                jQuery("#show_album_masonry_name_enable_1").attr('checked', 'checked');
              }
              else {
                jQuery("#show_album_masonry_name_enable_0").attr('checked', 'checked');
              }
              if (short_code['show_gallery_description'] == 1) {
                jQuery("#album_masonry_show_gallery_description_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_masonry_show_gallery_description_0").attr('checked', 'checked');
              }
              if (short_code['gallery_download'] == 1) {
                jQuery("#album_masonry_gallery_download_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_masonry_gallery_download_0").attr('checked', 'checked');
              }
              if (short_code['ecommerce_icon'] == 'hover') {
                jQuery("#album_masonry_ecommerce_icon_show_hover_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_masonry_ecommerce_icon_show_hover_2").attr('checked', 'checked');
              }
              break;
            }
            case 'album_extended_preview': {
              jQuery("#extended_album_height").val(short_code['extended_album_height']);
              jQuery("#album_extended_thumb_width").val(short_code['extended_album_thumb_width']);
              jQuery("#album_extended_thumb_height").val(short_code['extended_album_thumb_height']);
              jQuery("#album_extended_image_column_number").val(short_code['extended_album_image_column_number']);
              jQuery("#album_extended_image_thumb_width").val(short_code['extended_album_image_thumb_width']);
              jQuery("#album_extended_image_thumb_height").val(short_code['extended_album_image_thumb_height']);
              if (short_code['extended_album_enable_page']) {
                jQuery("#album_extended_enable_page_" + short_code['extended_album_enable_page']).attr('checked', 'checked');
              }
              jQuery("#albums_extended_per_page").val(short_code['extended_albums_per_page']);
              jQuery("#album_extended_images_per_page").val(short_code['extended_album_images_per_page']);
              jQuery("select[id=album_extended_sort_by] option[value='" + short_code['sort_by'] + "']").attr('selected', 'selected');
              if (short_code['order_by'] == 'asc') {
                jQuery("#album_extended_order_by_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_extended_order_by_0").attr('checked', 'checked');
              }
              if (short_code['show_search_box'] == 1) {
                jQuery("#album_extended_show_search_box_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_extended_show_search_box_0").attr('checked', 'checked');
              }
              if (short_code['placeholder']) {
                jQuery("#album_extended_placeholder").val(short_code['placeholder']);
              }
              if (short_code['search_box_width']) {
                jQuery("#album_extended_search_box_width").val(short_code['search_box_width']);
              }
              if (short_code['show_sort_images'] == 1) {
                jQuery("#album_extended_show_sort_images_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_extended_show_sort_images_0").attr('checked', 'checked');
              }
              if (short_code['show_tag_box'] == 1) {
                jQuery("#album_extended_show_tag_box_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_extended_show_tag_box_0").attr('checked', 'checked');
              }
              if (short_code['show_album_name'] == 1) {
                jQuery("#show_album_extended_name_enable_1").attr('checked', 'checked');
              }
              else {
                jQuery("#show_album_extended_name_enable_0").attr('checked', 'checked');
              }
              if (short_code['extended_album_description_enable'] == 1) {
                jQuery("#extended_album_description_enable_1").attr('checked', 'checked');
              }
              else {
                jQuery("#extended_album_description_enable_0").attr('checked', 'checked');
              }
              if (short_code['show_gallery_description'] == 1) {
                jQuery("#album_extended_show_gallery_description_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_extended_show_gallery_description_0").attr('checked', 'checked');
              }
              if (short_code['extended_album_view_type'] == 'thumbnail') {
                jQuery("#album_extended_view_type_1").attr('checked', 'checked');
              }
              else if (short_code['extended_album_view_type'] == 'masonry') {
                jQuery("#album_extended_view_type_0").attr('checked', 'checked');
              }
              else {
                jQuery("#album_extended_view_type_2").attr('checked', 'checked');
              }
              jQuery("#album_extended_image_title_show_hover_" + short_code['extended_album_image_title']).attr('checked', 'checked');
              if (short_code['extended_album_mosaic_hor_ver'] == "vertical") {
                jQuery("#album_extended_mosaic_0").attr('checked', 'checked');
              }
              else {
                jQuery("#album_extended_mosaic_1").attr('checked', 'checked');
              }
              if (short_code['extended_album_resizable_mosaic'] == 1) {
                jQuery("#album_extended_resizable_mosaic_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_extended_resizable_mosaic_0").attr('checked', 'checked');
              }
              jQuery("#album_extended_mosaic_total_width").val(short_code['extended_album_mosaic_total_width']);
              if (short_code['play_icon'] == 1) {
                jQuery("#album_extended_play_icon_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#album_extended_play_icon_no").attr('checked', 'checked');
              }
              if (short_code['gallery_download'] == 1) {
                jQuery("#album_extended_gallery_download_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_extended_gallery_download_0").attr('checked', 'checked');
              }
              if (short_code['ecommerce_icon'] == 'hover') {
                jQuery("#album_extended_ecommerce_icon_show_hover_1").attr('checked', 'checked');
              }
              else if (short_code['ecommerce_icon'] == 'show') {
                jQuery("#album_extended_ecommerce_icon_show_hover_0").attr('checked', 'checked');
              }
              else {
                jQuery("#album_extended_ecommerce_icon_show_hover_2").attr('checked', 'checked');
              }
              break;
            }
          }
          // Lightbox.
          if (short_code['popup_width'] != undefined) {
            jQuery("#popup_width").val(short_code['popup_width']);
          }
          if (short_code['popup_height'] != undefined) {
            jQuery("#popup_height").val(short_code['popup_height']);
          }
          if (short_code['popup_effect'] != undefined) {
            jQuery("select[id=popup_effect] option[value='" + short_code['popup_effect'] + "']").attr('selected', 'selected');
          }
          if (short_code['popup_interval'] != undefined) {
            jQuery("#popup_interval").val(short_code['popup_interval']);
          }
          if (short_code['popup_effect_duration'] != undefined) {
            jQuery("#popup_effect_duration").val(short_code['popup_effect_duration']);
          }
          if (short_code['popup_fullscreen'] != undefined) {
            if (short_code['popup_fullscreen'] == 1) {
              jQuery("#popup_fullscreen_1").attr('checked', 'checked');
              jQuery("#tr_popup_width_height").css('display', 'none');
            }
            else {
              jQuery("#popup_fullscreen_0").attr('checked', 'checked');
              jQuery("#tr_popup_width_height").css('display', '');
            }
          }
          if (short_code['popup_autoplay'] != undefined) {
            if (short_code['popup_autoplay'] == 1) {
              jQuery("#popup_autoplay_1").attr('checked', 'checked');
            }
            else {
              jQuery("#popup_autoplay_0").attr('checked', 'checked');
            }
          }
          if (short_code['popup_enable_filmstrip'] != undefined) {
            if (short_code['popup_enable_filmstrip'] == 1) {
              jQuery("#popup_filmstrip_yes").attr('checked', 'checked');
              jQuery("#popup_filmstrip_height").val(short_code['popup_filmstrip_height']);
              bwg_enable_disable('', 'tr_popup_filmstrip_height', 'popup_filmstrip_yes');
            }
            else {
              jQuery("#popup_filmstrip_no").attr('checked', 'checked');
            }
          }
          if (short_code['popup_enable_ctrl_btn'] != undefined) {
            if (short_code['popup_enable_ctrl_btn'] == 1) {
              jQuery("#popup_ctrl_btn_yes").attr('checked', 'checked');
              bwg_enable_disable('', 'tbody_popup_ctrl_btn1', 'popup_ctrl_btn_yes');
              bwg_enable_disable('', 'tbody_popup_ctrl_btn2', 'popup_ctrl_btn_yes');
              if (short_code['popup_enable_fullscreen'] == 1) {
                jQuery("#popup_fullscreen_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#popup_fullscreen_no").attr('checked', 'checked');
              }
              if (short_code['popup_enable_info'] == 1 || !short_code['popup_enable_info']) {
                jQuery("#popup_info_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#popup_info_no").attr('checked', 'checked');
              }
              if (short_code['show_tag_box'] == 0 || !short_code['show_tag_box']) {
                jQuery("#show_tag_box_0").attr('checked', 'checked');
              }
              else {
                jQuery("#show_tag_box_1").attr('checked', 'checked');
              }
              if (short_code['popup_info_full_width'] == 1) {
                jQuery("#popup_info_full_width_1").attr('checked', 'checked');
              }
              else {
                jQuery("#popup_info_full_width_0").attr('checked', 'checked');
              }
              if (short_code['popup_info_always_show'] == 1 && short_code['popup_info_always_show']) {
                jQuery("#popup_info_always_show_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#popup_info_always_show_no").attr('checked', 'checked');
              }
              if (short_code['popup_enable_rate'] == 1 && short_code['popup_enable_rate']) {
                jQuery("#popup_rate_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#popup_rate_no").attr('checked', 'checked');
              }
              if (short_code['popup_enable_comment'] == 1) {
                jQuery("#popup_comment_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#popup_comment_no").attr('checked', 'checked');
              }
              if (short_code['popup_hit_counter'] == 1 && short_code['popup_hit_counter']) {
                jQuery("#popup_hit_counter_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#popup_hit_counter_no").attr('checked', 'checked');
              }
              if (short_code['popup_enable_facebook'] == 1) {
                jQuery("#popup_facebook_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#popup_facebook_no").attr('checked', 'checked');
              }
              if (short_code['popup_enable_twitter'] == 1) {
                jQuery("#popup_twitter_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#popup_twitter_no").attr('checked', 'checked');
              }
              if (short_code['popup_enable_google'] == 1) {
                jQuery("#popup_google_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#popup_google_no").attr('checked', 'checked');
              }
              if (short_code['popup_enable_ecommerce'] == 1) {
                jQuery("#popup_ecommerce_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#popup_ecommerce_no").attr('checked', 'checked');
              }
              if (short_code['popup_enable_pinterest'] == 1) {
                jQuery("#popup_pinterest_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#popup_pinterest_no").attr('checked', 'checked');
              }
              if (short_code['popup_enable_tumblr'] == 1) {
                jQuery("#popup_tumblr_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#popup_tumblr_no").attr('checked', 'checked');
              }
            }
            else {
              jQuery("#popup_ctrl_btn_no").attr('checked', 'checked');
            }
          }
          if (!short_code['thumb_click_action'] || short_code['thumb_click_action'] == 'undefined' || short_code['thumb_click_action'] == 'do_nothing') {
            jQuery("#thumb_click_action_3").attr('checked', 'checked');
          }
          else if (short_code['thumb_click_action'] == 'redirect_to_url') {
            jQuery("#thumb_click_action_2").attr('checked', 'checked');
          }
          else if (short_code['thumb_click_action'] == 'open_lightbox') {
            jQuery("#thumb_click_action_1").attr('checked', 'checked');
          }
          if (short_code['thumb_link_target'] == 1 || !short_code['thumb_link_target'] || short_code['thumb_link_target'] == 'undefined') {
            jQuery("#thumb_link_target_yes").attr('checked', 'checked');
          }
          else {
            jQuery("#thumb_link_target_no").attr('checked', 'checked');
          }
          bwg_thumb_click_action();
          // Watermark.
          if (short_code['watermark_type'] == 'text') {
            jQuery("#watermark_type_text").attr('checked', 'checked');
            jQuery("#watermark_link").val(decodeURIComponent(short_code['watermark_link']));
            jQuery("#watermark_text").val(short_code['watermark_text']);
            jQuery("#watermark_font_size").val(short_code['watermark_font_size']);
            if (in_array(short_code['watermark_font'], bwg_objectGGF)) {
              jQuery("#watermark_google_fonts1").attr('checked', 'checked');
              bwg_change_fonts('watermark_font', 'watermark_google_fonts1');
            }
            else {
              jQuery("#watermark_google_fonts0").attr('checked', 'checked');
              bwg_change_fonts('watermark_font', '');
            }
            jQuery("select[id=watermark_font] option[value='" + short_code['watermark_font'] + "']").attr('selected', 'selected');
            jQuery("#watermark_color").val(short_code['watermark_color']);
            jQuery("#watermark_opacity").val(short_code['watermark_opacity']);
            jQuery("#watermark_type_text").attr('checked', 'checked');
            jQuery("#watermark_" + short_code['watermark_position']).attr('checked', 'checked');
          }
          else if (short_code['watermark_type'] == 'image') {
            jQuery("#watermark_type_image").attr('checked', 'checked');
            jQuery("#watermark_link").val(decodeURIComponent(short_code['watermark_link']));
            jQuery("#watermark_url").val(short_code['watermark_url']);
            jQuery("#watermark_width").val(short_code['watermark_width']);
            jQuery("#watermark_height").val(short_code['watermark_height']);
            jQuery("#watermark_opacity").val(short_code['watermark_opacity']);
            jQuery("#watermark_type_image").attr('checked', 'checked');
            jQuery("#watermark_" + short_code['watermark_position']).attr('checked', 'checked');
          }
          else {
            jQuery("#watermark_type_none").attr('checked', 'checked');
          }
          bwg_watermark('watermark_type_' + short_code['watermark_type']);
        }
      }

      // in_array
      function in_array(what, where) {
        var t = false;
        for (var i in where) {
          if (what == where[i]) {
            t = true;
            break;
          }
        }
        if (t == true) {
          return true;
        }
        else {
          return false;
        }
      }

      // Get shortcodes attributes.
      function get_params(module_name) {
        <?php if ($params['gutenberg_callback']) {
        if ( $params['gutenberg_id'] == 0) {
        ?>
        return false;
        <?php
        }
        ?>
        var short_code_attr = new Array();
        short_code_attr['id'] = <?php echo (int) $params['gutenberg_id']; ?>;
        return short_code_attr;
        <?php
        }
        elseif (!$from_menu) { ?>
        var selected_text;
        if (top.tinyMCE.activeEditor) {
          selected_text = top.tinyMCE.activeEditor.selection.getContent();
        }
        else {
          selected_text = bwg_get_textarea_selection(top.wpActiveEditor);
        }
        <?php } else { ?>
        var shortcode_val = jQuery("#shortcode").val();
        var selected_text = shortcode_val ? '[Best_Wordpress_Gallery id="' + shortcode_val + '"]' : '';
        <?php } ?>
        var module_start_index = selected_text.indexOf("[" + module_name);
        var module_end_index = selected_text.indexOf("]", module_start_index);
        var module_str = "";
        if ((module_start_index >= 0) && (module_end_index >= 0)) {
          module_str = selected_text.substring(module_start_index + 1, module_end_index);
        }
        else {
          return false;
        }
        var params_str = module_str.substring(module_str.indexOf(" ") + 1);
        var key_values = params_str.split('" ');
        var short_code_attr = new Array();
        for (var key in key_values) {
          var short_code_index = key_values[key].split('=')[0];
          var short_code_value = key_values[key].split('=')[1];
          short_code_value = short_code_value.replace(/\"/g, '');
          short_code_attr[short_code_index] = short_code_value;
        }
        return short_code_attr;
      }

      function get_short_params(tagtext) {
        var params_str = tagtext.substring(tagtext.indexOf(" ") + 1);
        var key_values = params_str.split('" ');
        var short_code_attr = new Array();
        for (var key in key_values) {
          var short_code_index = key_values[key].split('=')[0];
          var short_code_value = key_values[key].split('=')[1];
          short_code_value = short_code_value.replace(/\"/g, '');
          short_code_attr[short_code_index] = short_code_value;
        }
        return short_code_attr;
      }

      function bwg_insert_shortcode(content) {
        window.parent.window.jQuery(window.parent.document).trigger("onOpenShortcode");
        var gallery_type = jQuery("input[name=gallery_type]:checked").val();
        var theme = jQuery("#theme").val();
        var use_options_defaults = jQuery("#use_option_defaults").prop('checked') ? 1 : 0;
        var title = "";
        var short_code = '[Best_Wordpress_Gallery';
        var tagtext = ' gallery_type="' + gallery_type + '" theme_id="' + theme + '"';
        tagtext += ' use_option_defaults="' + use_options_defaults + '"';
        switch (gallery_type) {
          case 'thumbnails': {
            title = ' gal_title="' + jQuery.trim(jQuery('#gallery option:selected').text().replace("'", "").replace('"', '')) + '"';
            tagtext += ' gallery_id="' + jQuery("#gallery").val() + '"';
            tagtext += ' tag="' + jQuery("#tag").val() + '"';

            tagtext += ' thumb_width="' + jQuery("#thumb_width").val() + '"';
            tagtext += ' thumb_height="' + jQuery("#thumb_height").val() + '"';
            tagtext += ' image_column_number="' + jQuery("#image_column_number").val() + '"';
            tagtext += ' image_enable_page="' + jQuery("input[name=image_enable_page]:checked").val() + '"';
            tagtext += ' images_per_page="' + jQuery("#images_per_page").val() + '"';
            tagtext += ' load_more_image_count="' + jQuery("#load_more_image_count").val() + '"';
            tagtext += ' sort_by="' + jQuery("#sort_by").val() + '"';
            tagtext += ' order_by="' + jQuery("input[name=order_by]:checked").val() + '"';
            tagtext += ' show_search_box="' + jQuery("input[name=show_search_box]:checked").val() + '"';
            tagtext += ' placeholder="' + jQuery("#placeholder").val() + '"';
            tagtext += ' search_box_width="' + jQuery("#search_box_width").val() + '"';
            tagtext += ' show_sort_images="' + jQuery("input[name=show_sort_images]:checked").val() + '"';
            tagtext += ' show_tag_box="' + jQuery("input[name=show_tag_box]:checked").val() + '"';
            tagtext += ' showthumbs_name="' + jQuery("input[name=showthumbs_name]:checked").val() + '"';
            tagtext += ' show_gallery_description="' + jQuery("input[name=show_gallery_description]:checked").val() + '"';
            tagtext += ' image_title="' + jQuery("input[name=image_title_show_hover]:checked").val() + '"';
            tagtext += ' play_icon="' + jQuery("input[name=play_icon]:checked").val() + '"';
            tagtext += ' gallery_download="' + jQuery("input[name=gallery_download]:checked").val() + '"';
            tagtext += ' ecommerce_icon="' + jQuery("input[name=ecommerce_icon_show_hover]:checked").val() + '"';
            break;
          }
          case 'thumbnails_masonry': {
            title = ' gal_title="' + jQuery.trim(jQuery('#gallery option:selected').text().replace("'", "").replace('"', '')) + '"';
            tagtext += ' gallery_id="' + jQuery("#gallery").val() + '"';
            tagtext += ' tag="' + jQuery("#tag").val() + '"';

            tagtext += ' masonry_hor_ver="' + jQuery("input[name=masonry]:checked").val() + '"';
            tagtext += ' show_masonry_thumb_description="' + jQuery("input[name=show_masonry_thumb_description]:checked").val() + '"';
            tagtext += ' thumb_width="' + jQuery("#masonry_thumb_size").val() + '"';
            tagtext += ' thumb_height="' + jQuery("#masonry_thumb_size").val() + '"';
            tagtext += ' image_column_number="' + jQuery("#masonry_image_column_number").val() + '"';
            tagtext += ' image_enable_page="' + jQuery("input[name=masonry_image_enable_page]:checked").val() + '"';
            tagtext += ' images_per_page="' + jQuery("#masonry_images_per_page").val() + '"';
            tagtext += ' load_more_image_count="' + jQuery("#masonry_load_more_image_count").val() + '"';
            tagtext += ' sort_by="' + jQuery("#masonry_sort_by").val() + '"';
            tagtext += ' order_by="' + jQuery("input[name=masonry_order_by]:checked").val() + '"';
            tagtext += ' show_search_box="' + jQuery("input[name=masonry_show_search_box]:checked").val() + '"';
            tagtext += ' placeholder="' + jQuery("#masonry_placeholder").val() + '"';
            tagtext += ' search_box_width="' + jQuery("#masonry_search_box_width").val() + '"';
            tagtext += ' show_sort_images="' + jQuery("input[name=masonry_show_sort_images]:checked").val() + '"';
            tagtext += ' show_tag_box="' + jQuery("input[name=masonry_show_tag_box]:checked").val() + '"';
            tagtext += ' showthumbs_name="' + jQuery("input[name=masonry_show_gallery_title]:checked").val() + '"';
            tagtext += ' show_gallery_description="' + jQuery("input[name=masonry_show_gallery_description]:checked").val() + '"';
            tagtext += ' play_icon="' + jQuery("input[name=masonry_play_icon]:checked").val() + '"';
            tagtext += ' gallery_download="' + jQuery("input[name=masonry_gallery_download]:checked").val() + '"';
            tagtext += ' ecommerce_icon="' + jQuery("input[name=masonry_ecommerce_icon_show_hover]:checked").val() + '"';
            break;
          }
          case 'thumbnails_mosaic': {
            title = ' gal_title="' + jQuery.trim(jQuery('#gallery option:selected').text().replace("'", "").replace('"', '')) + '"';
            tagtext += ' gallery_id="' + jQuery("#gallery").val() + '"';
            tagtext += ' tag="' + jQuery("#tag").val() + '"';

            tagtext += ' mosaic_hor_ver="' + jQuery("input[name=mosaic]:checked").val() + '"';
            tagtext += ' resizable_mosaic="' + jQuery("input[name=resizable_mosaic]:checked").val() + '"';
            tagtext += ' mosaic_total_width="' + jQuery("#mosaic_total_width").val() + '"';
            tagtext += ' thumb_width="' + jQuery("#mosaic_thumb_size").val() + '"';
            tagtext += ' thumb_height="' + jQuery("#mosaic_thumb_size").val() + '"';
            tagtext += ' image_enable_page="' + jQuery("input[name=mosaic_image_enable_page]:checked").val() + '"';
            tagtext += ' images_per_page="' + jQuery("#mosaic_images_per_page").val() + '"';
            tagtext += ' load_more_image_count="' + jQuery("#mosaic_load_more_image_count").val() + '"';
            tagtext += ' sort_by="' + jQuery("#mosaic_sort_by").val() + '"';
            tagtext += ' order_by="' + jQuery("input[name=mosaic_order_by]:checked").val() + '"';
            tagtext += ' show_search_box="' + jQuery("input[name=mosaic_show_search_box]:checked").val() + '"';
            tagtext += ' placeholder="' + jQuery("#mosaic_placeholder").val() + '"';
            tagtext += ' search_box_width="' + jQuery("#mosaic_search_box_width").val() + '"';
            tagtext += ' show_sort_images="' + jQuery("input[name=mosaic_show_sort_images]:checked").val() + '"';
            tagtext += ' show_tag_box="' + jQuery("input[name=mosaic_show_tag_box]:checked").val() + '"';
            tagtext += ' showthumbs_name="' + jQuery("input[name=mosaic_show_gallery_title]:checked").val() + '"';
            tagtext += ' show_gallery_description="' + jQuery("input[name=mosaic_show_gallery_description]:checked").val() + '"';
            tagtext += ' image_title="' + jQuery("input[name=mosaic_image_title_show_hover]:checked").val() + '"';
            tagtext += ' play_icon="' + jQuery("input[name=mosaic_play_icon]:checked").val() + '"';
            tagtext += ' gallery_download="' + jQuery("input[name=mosaic_gallery_download]:checked").val() + '"';
            tagtext += ' ecommerce_icon="' + jQuery("input[name=mosaic_ecommerce_icon_show_hover]:checked").val() + '"';
            break;
          }
          case 'slideshow': {
            title = ' gal_title="' + jQuery.trim(jQuery('#gallery option:selected').text().replace("'", "").replace('"', '')) + '"';
            tagtext += ' gallery_id="' + jQuery("#gallery").val() + '"';
            tagtext += ' tag="' + jQuery("#tag").val() + '"';

            tagtext += ' slideshow_effect="' + jQuery("#slideshow_type").val() + '"';
            tagtext += ' slideshow_interval="' + jQuery("#slideshow_interval").val() + '"';
            tagtext += ' slideshow_width="' + jQuery("#slideshow_width").val() + '"';
            tagtext += ' slideshow_height="' + jQuery("#slideshow_height").val() + '"';
            tagtext += ' sort_by="' + jQuery("#slideshow_sort_by").val() + '"';
            tagtext += ' order_by="' + jQuery("input[name=slideshow_order_by]:checked").val() + '"';
            tagtext += ' enable_slideshow_autoplay="' + jQuery("input[name=slideshow_enable_autoplay]:checked").val() + '"';
            tagtext += ' enable_slideshow_shuffle="' + jQuery("input[name=slideshow_enable_shuffle]:checked").val() + '"';
            tagtext += ' enable_slideshow_ctrl="' + jQuery("input[name=slideshow_enable_ctrl]:checked").val() + '"';
            tagtext += ' autohide_slideshow_navigation="' + jQuery("input[name=autohide_slideshow_navigation]:checked").val() + '"';
            tagtext += ' enable_slideshow_filmstrip="' + jQuery("input[name=slideshow_enable_filmstrip]:checked").val() + '"';
            tagtext += ' slideshow_filmstrip_height="' + jQuery("#slideshow_filmstrip_height").val() + '"';
            tagtext += ' slideshow_enable_title="' + jQuery("input[name=slideshow_enable_title]:checked").val() + '"';
            tagtext += ' slideshow_title_position="' + jQuery("input[name=slideshow_title_position]:checked").val() + '"';
            tagtext += ' slideshow_title_full_width="' + jQuery("input[name=slideshow_title_full_width]:checked").val() + '"';
            tagtext += ' slideshow_enable_description="' + jQuery("input[name=slideshow_enable_description]:checked").val() + '"';
            tagtext += ' slideshow_description_position="' + jQuery("input[name=slideshow_description_position]:checked").val() + '"';
            tagtext += ' enable_slideshow_music="' + jQuery("input[name=slideshow_enable_music]:checked").val() + '"';
            tagtext += ' slideshow_music_url="' + jQuery("#slideshow_audio_url").val() + '"';
            tagtext += ' slideshow_effect_duration="' + jQuery("#slideshow_effect_duration").val() + '"';
            tagtext += ' gallery_download="' + jQuery("input[name=slideshow_gallery_download]:checked").val() + '"';
            break;
          }
          case 'image_browser': {
            title = ' gal_title="' + jQuery.trim(jQuery('#gallery option:selected').text().replace("'", "").replace('"', '')) + '"';
            tagtext += ' gallery_id="' + jQuery("#gallery").val() + '"';
            tagtext += ' tag="' + jQuery("#tag").val() + '"';

            tagtext += ' image_browser_width="' + jQuery("#image_browser_width").val() + '"';
            tagtext += ' image_browser_title_enable="' + jQuery("input[name=image_browser_title_enable]:checked").val() + '"';
            tagtext += ' image_browser_description_enable="' + jQuery("input[name=image_browser_description_enable]:checked").val() + '"';
            tagtext += ' sort_by="' + jQuery("#image_browser_sort_by").val() + '"';
            tagtext += ' order_by="' + jQuery("input[name=image_browser_order_by]:checked").val() + '"';
            tagtext += ' showthumbs_name="' + jQuery("input[name=image_browser_show_gallery_title]:checked").val() + '"';
            tagtext += ' show_gallery_description="' + jQuery("input[name=image_browser_show_gallery_description]:checked").val() + '"';
            tagtext += ' show_search_box="' + jQuery("input[name=image_browser_show_search_box]:checked").val() + '"';
            tagtext += ' placeholder="' + jQuery("#image_browser_placeholder").val() + '"';
            tagtext += ' search_box_width="' + jQuery("#image_browser_search_box_width").val() + '"';
            tagtext += ' gallery_download="' + jQuery("input[name=image_browser_gallery_download]:checked").val() + '"';
            break;
          }
          case 'blog_style': {
            title = ' gal_title="' + jQuery.trim(jQuery('#gallery option:selected').text().replace("'", "").replace('"', '')) + '"';
            tagtext += ' gallery_id="' + jQuery("#gallery").val() + '"';
            tagtext += ' tag="' + jQuery("#tag").val() + '"';

            tagtext += ' blog_style_width="' + jQuery("#blog_style_width").val() + '"';
            tagtext += ' blog_style_title_enable="' + jQuery("input[name=blog_style_title_enable]:checked").val() + '"';
            tagtext += ' blog_style_images_per_page="' + jQuery("#blog_style_images_per_page").val() + '"';
            tagtext += ' blog_style_load_more_image_count="' + jQuery("#blog_style_load_more_image_count").val() + '"';
            tagtext += ' blog_style_enable_page="' + jQuery("input[name=blog_style_enable_page]:checked").val() + '"';
            tagtext += ' blog_style_description_enable="' + jQuery("input[name=blog_style_description_enable]:checked").val() + '"';
            tagtext += ' sort_by="' + jQuery("#blog_style_sort_by").val() + '"';
            tagtext += ' order_by="' + jQuery("input[name=blog_style_order_by]:checked").val() + '"';
            tagtext += ' showthumbs_name="' + jQuery("input[name=blog_style_show_gallery_title]:checked").val() + '"';
            tagtext += ' show_gallery_description="' + jQuery("input[name=blog_style_show_gallery_description]:checked").val() + '"';
            tagtext += ' show_search_box="' + jQuery("input[name=blog_style_show_search_box]:checked").val() + '"';
            tagtext += ' placeholder="' + jQuery("#blog_style_placeholder").val() + '"';
            tagtext += ' search_box_width="' + jQuery("#blog_style_search_box_width").val() + '"';
            tagtext += ' show_sort_images="' + jQuery("input[name=blog_style_show_sort_images]:checked").val() + '"';
            tagtext += ' show_tag_box="' + jQuery("input[name=blog_style_show_tag_box]:checked").val() + '"';
            tagtext += ' gallery_download="' + jQuery("input[name=blog_style_gallery_download]:checked").val() + '"';
            break;
          }
          case 'carousel': {
            title = ' gal_title="' + jQuery.trim(jQuery('#gallery option:selected').text().replace("'", "").replace('"', '')) + '"';
            tagtext += ' gallery_id="' + jQuery("#gallery").val() + '"';
            tagtext += ' tag="' + jQuery("#tag").val() + '"';

            tagtext += ' carousel_interval="' + jQuery("#carousel_interval").val() + '"';
            tagtext += ' carousel_width="' + jQuery("#carousel_width").val() + '"';
            tagtext += ' carousel_height="' + jQuery("#carousel_height").val() + '"';
            tagtext += ' carousel_image_column_number="' + jQuery("#carousel_image_column_number").val() + '"';
            tagtext += ' carousel_image_par="' + jQuery("#carousel_image_par").val() + '"';
            tagtext += ' enable_carousel_title="' + jQuery("input[name=carousel_enable_title]:checked").val() + '"';
            tagtext += ' enable_carousel_autoplay="' + jQuery("input[name=carousel_enable_autoplay]:checked").val() + '"';
            tagtext += ' carousel_r_width="' + jQuery("#carousel_r_width").val() + '"';
            tagtext += ' carousel_fit_containerWidth="' + jQuery("input[name=carousel_fit_containerWidth]:checked").val() + '"';
            tagtext += ' carousel_prev_next_butt="' + jQuery("input[name=carousel_prev_next_butt]:checked").val() + '"';
            tagtext += ' carousel_play_pause_butt="' + jQuery("input[name=carousel_play_pause_butt]:checked").val() + '"';
            tagtext += ' sort_by="' + jQuery("#carousel_sort_by").val() + '"';
            tagtext += ' order_by="' + jQuery("input[name=carousel_order_by]:checked").val() + '"';
            tagtext += ' gallery_download="' + jQuery("input[name=carousel_gallery_download]:checked").val() + '"';
            break;
          }
          case 'album_compact_preview': {
            title = ' gal_title="' + jQuery.trim(jQuery('#album option:selected').text().replace("'", "").replace('"', '')) + '"';
            tagtext += ' album_id="' + jQuery("#album").val() + '"';

            tagtext += ' compuct_album_column_number="' + jQuery("#album_column_number").val() + '"';
            tagtext += ' compuct_album_thumb_width="' + jQuery("#album_thumb_width").val() + '"';
            tagtext += ' compuct_album_thumb_height="' + jQuery("#album_thumb_height").val() + '"';
            tagtext += ' compuct_album_image_column_number="' + jQuery("#album_image_column_number").val() + '"';
            tagtext += ' compuct_album_image_thumb_width="' + jQuery("#album_image_thumb_width").val() + '"';
            tagtext += ' compuct_album_image_thumb_height="' + jQuery("#album_image_thumb_height").val() + '"';
            tagtext += ' compuct_album_enable_page="' + jQuery("input[name=album_enable_page]:checked").val() + '"';
            tagtext += ' compuct_albums_per_page="' + jQuery("#albums_per_page").val() + '"';
            tagtext += ' compuct_album_images_per_page="' + jQuery("#album_images_per_page").val() + '"';
            tagtext += ' sort_by="' + jQuery("#album_sort_by").val() + '"';
            tagtext += ' order_by="' + jQuery("input[name=album_order_by]:checked").val() + '"';
            tagtext += ' show_search_box="' + jQuery("input[name=album_show_search_box]:checked").val() + '"';
            tagtext += ' placeholder="' + jQuery("#album_placeholder").val() + '"';
            tagtext += ' search_box_width="' + jQuery("#album_search_box_width").val() + '"';
            tagtext += ' show_sort_images="' + jQuery("input[name=album_show_sort_images]:checked").val() + '"';
            tagtext += ' show_tag_box="' + jQuery("input[name=album_show_tag_box]:checked").val() + '"';
            tagtext += ' show_album_name="' + jQuery("input[name=show_album_name]:checked").val() + '"';
            tagtext += ' show_gallery_description="' + jQuery("input[name=album_show_gallery_description]:checked").val() + '"';
            tagtext += ' compuct_album_title="' + jQuery("input[name=album_title_show_hover]:checked").val() + '"';
            tagtext += ' compuct_album_view_type="' + jQuery("input[name=album_view_type]:checked").val() + '"';
            tagtext += ' compuct_album_image_title="' + jQuery("input[name=album_image_title_show_hover]:checked").val() + '"';
            tagtext += ' compuct_album_mosaic_hor_ver="' + jQuery("input[name=album_mosaic]:checked").val() + '"';
            tagtext += ' compuct_album_resizable_mosaic="' + jQuery("input[name=album_resizable_mosaic]:checked").val() + '"';
            tagtext += ' compuct_album_mosaic_total_width="' + jQuery("#album_mosaic_total_width").val() + '"';
            tagtext += ' play_icon="' + jQuery("input[name=album_play_icon]:checked").val() + '"';
            tagtext += ' gallery_download="' + jQuery("input[name=album_gallery_download]:checked").val() + '"';
            tagtext += ' ecommerce_icon="' + jQuery("input[name=album_ecommerce_icon_show_hover]:checked").val() + '"';
            break;
          }
          case 'album_masonry_preview' : {
            title = ' gal_title="' + jQuery.trim(jQuery('#album option:selected').text().replace("'", "").replace('"', '')) + '"';
            tagtext += ' album_id="' + jQuery("#album").val() + '"';

            tagtext += ' masonry_album_column_number="' + jQuery("#album_masonry_column_number").val() + '"';
            tagtext += ' masonry_album_thumb_width="' + jQuery("#album_masonry_thumb_width").val() + '"';
            tagtext += ' masonry_album_image_column_number="' + jQuery("#album_masonry_image_column_number").val() + '"';
            tagtext += ' masonry_album_image_thumb_width="' + jQuery("#album_masonry_image_thumb_width").val() + '"';
            tagtext += ' masonry_album_enable_page="' + jQuery("input[name=album_masonry_enable_page]:checked").val() + '"';
            tagtext += ' masonry_albums_per_page="' + jQuery("#albums_masonry_per_page").val() + '"';
            tagtext += ' masonry_album_images_per_page="' + jQuery("#album_masonry_images_per_page").val() + '"';
            tagtext += ' sort_by="' + jQuery("#album_masonry_sort_by").val() + '"';
            tagtext += ' order_by="' + jQuery("input[name=album_masonry_order_by]:checked").val() + '"';
            tagtext += ' show_search_box="' + jQuery("input[name=album_masonry_show_search_box]:checked").val() + '"';
            tagtext += ' placeholder="' + jQuery("#album_masonry_placeholder").val() + '"';
            tagtext += ' search_box_width="' + jQuery("#album_masonry_search_box_width").val() + '"';
            tagtext += ' show_sort_images="' + jQuery("input[name=album_masonry_show_sort_images]:checked").val() + '"';
            tagtext += ' show_tag_box="' + jQuery("input[name=album_masonry_show_tag_box]:checked").val() + '"';
            tagtext += ' show_album_name="' + jQuery("input[name=show_album_masonry_name]:checked").val() + '"';
            tagtext += ' show_gallery_description="' + jQuery("input[name=album_masonry_show_gallery_description]:checked").val() + '"';
            tagtext += ' gallery_download="' + jQuery("input[name=album_masonry_gallery_download]:checked").val() + '"';
            tagtext += ' ecommerce_icon="' + jQuery("input[name=album_masonry_ecommerce_icon_show_hover]:checked").val() + '"';
            break;
          }
          case 'album_extended_preview': {
            title = ' gal_title="' + jQuery.trim(jQuery('#album option:selected').text().replace("'", "").replace('"', '')) + '"';
            tagtext += ' album_id="' + jQuery("#album").val() + '"';

            tagtext += ' extended_album_height="' + jQuery("#extended_album_height").val() + '"';
            tagtext += ' extended_album_thumb_width="' + jQuery("#album_extended_thumb_width").val() + '"';
            tagtext += ' extended_album_thumb_height="' + jQuery("#album_extended_thumb_height").val() + '"';
            tagtext += ' extended_album_image_column_number="' + jQuery("#album_extended_image_column_number").val() + '"';
            tagtext += ' extended_album_image_thumb_width="' + jQuery("#album_extended_image_thumb_width").val() + '"';
            tagtext += ' extended_album_image_thumb_height="' + jQuery("#album_extended_image_thumb_height").val() + '"';
            tagtext += ' extended_album_enable_page="' + jQuery("input[name=album_extended_enable_page]:checked").val() + '"';
            tagtext += ' extended_albums_per_page="' + jQuery("#albums_extended_per_page").val() + '"';
            tagtext += ' extended_album_images_per_page="' + jQuery("#album_extended_images_per_page").val() + '"';
            tagtext += ' sort_by="' + jQuery("#album_extended_sort_by").val() + '"';
            tagtext += ' order_by="' + jQuery("input[name=album_extended_order_by]:checked").val() + '"';
            tagtext += ' show_search_box="' + jQuery("input[name=album_extended_show_search_box]:checked").val() + '"';
            tagtext += ' placeholder="' + jQuery("#album_extended_placeholder").val() + '"';
            tagtext += ' search_box_width="' + jQuery("#album_extended_search_box_width").val() + '"';
            tagtext += ' show_sort_images="' + jQuery("input[name=album_extended_show_sort_images]:checked").val() + '"';
            tagtext += ' show_tag_box="' + jQuery("input[name=album_extended_show_tag_box]:checked").val() + '"';
            tagtext += ' show_album_name="' + jQuery("input[name=show_album_extended_name]:checked").val() + '"';
            tagtext += ' extended_album_description_enable="' + jQuery("input[name=extended_album_description_enable]:checked").val() + '"';
            tagtext += ' show_gallery_description="' + jQuery("input[name=album_extended_show_gallery_description]:checked").val() + '"';
            tagtext += ' extended_album_view_type="' + jQuery("input[name=album_extended_view_type]:checked").val() + '"';
            tagtext += ' extended_album_image_title="' + jQuery("input[name=album_extended_image_title_show_hover]:checked").val() + '"';
            tagtext += ' extended_album_mosaic_hor_ver="' + jQuery("input[name=album_extended_mosaic]:checked").val() + '"';
            tagtext += ' extended_album_resizable_mosaic="' + jQuery("input[name=album_extended_resizable_mosaic]:checked").val() + '"';
            tagtext += ' extended_album_mosaic_total_width="' + jQuery("#album_extended_mosaic_total_width").val() + '"';
            tagtext += ' play_icon="' + jQuery("input[name=album_extended_play_icon]:checked").val() + '"';
            tagtext += ' gallery_download="' + jQuery("input[name=album_extended_gallery_download]:checked").val() + '"';
            tagtext += ' ecommerce_icon="' + jQuery("input[name=album_extended_ecommerce_icon_show_hover]:checked").val() + '"';
            break;
          }
        }
        // Lightbox paramteres.
        tagtext += ' thumb_click_action="' + jQuery("input[name=thumb_click_action]:checked").val() + '"';
        tagtext += ' thumb_link_target="' + jQuery("input[name=thumb_link_target]:checked").val() + '"';
        tagtext += ' popup_fullscreen="' + jQuery("input[name=popup_fullscreen]:checked").val() + '"';
        tagtext += ' popup_autoplay="' + jQuery("input[name=popup_autoplay]:checked").val() + '"';
        tagtext += ' popup_width="' + jQuery("#popup_width").val() + '"';
        tagtext += ' popup_height="' + jQuery("#popup_height").val() + '"';
        tagtext += ' popup_effect="' + jQuery("#popup_effect").val() + '"';
        tagtext += ' popup_interval="' + jQuery("#popup_interval").val() + '"';
        tagtext += ' popup_enable_filmstrip="' + jQuery("input[name=popup_enable_filmstrip]:checked").val() + '"';
        tagtext += ' popup_filmstrip_height="' + jQuery("#popup_filmstrip_height").val() + '"';
        tagtext += ' popup_enable_ctrl_btn="' + jQuery("input[name=popup_enable_ctrl_btn]:checked").val() + '"';
        tagtext += ' popup_enable_fullscreen="' + jQuery("input[name=popup_enable_fullscreen]:checked").val() + '"';
        tagtext += ' popup_enable_info="' + jQuery("input[name=popup_enable_info]:checked").val() + '"';
        tagtext += ' popup_info_always_show="' + jQuery("input[name=popup_info_always_show]:checked").val() + '"';
        tagtext += ' popup_info_full_width="' + jQuery("input[name=popup_info_full_width]:checked").val() + '"';
        tagtext += ' popup_enable_rate="' + jQuery("input[name=popup_enable_rate]:checked").val() + '"';
        tagtext += ' popup_enable_comment="' + jQuery("input[name=popup_enable_comment]:checked").val() + '"';
        tagtext += ' popup_hit_counter="' + jQuery("input[name=popup_hit_counter]:checked").val() + '"';
        tagtext += ' popup_enable_facebook="' + jQuery("input[name=popup_enable_facebook]:checked").val() + '"';
        tagtext += ' popup_enable_twitter="' + jQuery("input[name=popup_enable_twitter]:checked").val() + '"';
        tagtext += ' popup_enable_google="' + jQuery("input[name=popup_enable_google]:checked").val() + '"';
        tagtext += ' popup_enable_ecommerce="' + jQuery("input[name=popup_enable_ecommerce]:checked").val() + '"';
        tagtext += ' popup_enable_pinterest="' + jQuery("input[name=popup_enable_pinterest]:checked").val() + '"';
        tagtext += ' popup_enable_tumblr="' + jQuery("input[name=popup_enable_tumblr]:checked").val() + '"';
        tagtext += ' popup_effect_duration="' + jQuery("#popup_effect_duration").val() + '"';
        // Watermark parameters.
        tagtext += ' watermark_type="' + jQuery("input[name=watermark_type]:checked").val() + '"';
        tagtext += ' watermark_link="' + (jQuery("#watermark_link").val()) + '"';
        if (jQuery("input[name=watermark_type]:checked").val() == 'text') {
          tagtext += ' watermark_text="' + jQuery("#watermark_text").val() + '"';
          tagtext += ' watermark_font_size="' + jQuery("#watermark_font_size").val() + '"';
          tagtext += ' watermark_font="' + jQuery("#watermark_font").val() + '"';
          tagtext += ' watermark_color="' + jQuery("#watermark_color").val() + '"';
          tagtext += ' watermark_opacity="' + jQuery("#watermark_opacity").val() + '"';
          tagtext += ' watermark_position="' + jQuery("input[name=watermark_position]:checked").val() + '"';
        }
        else if (jQuery("input[name=watermark_type]:checked").val() == 'image') {
          tagtext += ' watermark_url="' + jQuery("#watermark_url").val() + '"';
          tagtext += ' watermark_width="' + jQuery("#watermark_width").val() + '"';
          tagtext += ' watermark_height="' + jQuery("#watermark_height").val() + '"';
          tagtext += ' watermark_opacity="' + jQuery("#watermark_opacity").val() + '"';
          tagtext += ' watermark_position="' + jQuery("input[name=watermark_position]:checked").val() + '"';
        }
        short_code += ' id="' + shortcode_id + '"' + title + ']';
        var short_id = ' id="' + shortcode_id + '"' + title;

        <?php if (!$from_menu && !$params['gutenberg_callback']) { ?>
        if (top.tinyMCE.activeEditor) {
          short_code = short_code.replace(/\[Best_Wordpress_Gallery([^\]]*)\]/g, function (d, c) {
            return "<img src='<?php echo BWG()->plugin_url; ?>/images/icons/gallery-icon.png' class='bwg_shortcode mceItem' title='Best_Wordpress_Gallery" + short_id + "' />";
          });
        }
        //jQuery("#task").val("save");
        //jQuery("#tagtext").val(tagtext);
        //jQuery("#currrent_id").val(shortcode_id);
        //jQuery("#title").val(title);
        //jQuery("#bwg_insert").val((content && !bwg_insert) ? 0 : 1);
        //jQuery("#bwg_shortcode_form").submit();
        var post_data = {};
        var url = '<?php echo add_query_arg(array( 'action' => 'shortcode_bwg' ), admin_url('admin-ajax.php')); ?>';
        post_data['bwg_nonce'] = jQuery("#bwg_nonce").val();
        post_data['task'] = "save";
        post_data['tagtext'] = tagtext;
        post_data['currrent_id'] = shortcode_id;
        post_data['title'] = title;
        post_data['bwg_insert'] = (content && !bwg_insert) ? 0 : 1;
        var use_options_defaults = jQuery("#use_option_defaults").prop('checked') ? 1 : 0;
        post_data['use_option_defaults'] = use_options_defaults;
        jQuery.post(
          url,
          post_data
        ).success(function (data, textStatus, errorThrown) {
          if (top.tinymce.isIE && content) {
            // IE and Update.
            var all_content = top.tinyMCE.activeEditor.getContent();
            all_content = all_content.replace('<p></p><p>[Best_Wordpress_Gallery', '<p>[Best_Wordpress_Gallery');
            top.tinyMCE.activeEditor.setContent(all_content.replace(content, '[Best_Wordpress_Gallery id="' + shortcode_id + '"' + title + ']'));
          }
          else {
            top.send_to_editor(short_code);
          }
          top.tinyMCE.execCommand('mceRepaint');
        });
        <?php } else { ?>
        var post_data = {};
        var url = '<?php echo add_query_arg(array( 'action' => 'shortcode_bwg' ), admin_url('admin-ajax.php')); ?>';
        post_data['bwg_nonce'] = jQuery("#bwg_nonce").val();
        post_data['task'] = "save";
        post_data['tagtext'] = tagtext;
        post_data['currrent_id'] = shortcode_id;
        post_data['title'] = title;
        post_data['bwg_insert'] = (content && !bwg_insert) ? 0 : 1;
        var use_options_defaults = jQuery("#use_option_defaults").prop('checked') ? 1 : 0;
        post_data['use_option_defaults'] = use_options_defaults;
        jQuery.post(
          url,
          post_data
        ).success(function (data, textStatus, errorThrown) {
          content = '[Best_Wordpress_Gallery id="' + shortcode_id + '"' + title + ']';
          <?php
          if ( $params['gutenberg_callback'] ) {
          ?>
          window.parent['<?php echo $params['gutenberg_callback']; ?>'](content, shortcode_id);
          return;
          <?php
          }
          ?>
          if (bwg_insert) {
            jQuery('#shortcode').append('<option value="' + shortcode_id + '">[Best_Wordpress_Gallery id="' + shortcode_id + '"]</option>').val(shortcode_id);
          }
          jQuery('#insert').text('<?php _e('Update', BWG()->prefix); ?>');
          jQuery('#insert').attr('onclick', 'jQuery("#loading_div").show(); bwg_insert_shortcode(content);');
          jQuery("#bwg_shortcode").val(content);
          var str = "&#60;?php echo photo_gallery(" + shortcode_id + "); ?&#62;";
          jQuery("#bwg_function").val(str.replace("&#60;", '<').replace("&#62;", '>'));
          shortcodes[shortcode_id] = tagtext;
          temp_shortcode_id = ++shortcode_id;
          bwg_update_shortcode();
          jQuery('#loading_div').hide();
        });
        <?php } ?>
        return;
      }

      jQuery(document).ready(function () {
        bwg_shortcode_hide_show_params();
        bwg_change_tab();
      });
      jQuery(window).resize(function () {
        bwg_change_tab();
      });
      var bwg_image_thumb = '<?php echo addslashes(__('Thumbnail dimensions', BWG()->prefix)); ?>';
      var bwg_image_thumb_width = '<?php echo addslashes(__('Image thumbnail width ', BWG()->prefix)); ?>';
      var bwg_max_column = '<?php echo addslashes(__('Number of image columns', BWG()->prefix)); ?>';
      var bwg_image_thumb_height = '<?php echo addslashes(__('Image thumbnail height', BWG()->prefix)); ?>';
      var bwg_number_of_image_rows = '<?php echo addslashes(__('Number of image rows', BWG()->prefix)); ?>';
    </script>
    <?php
    return ob_get_clean();
  }
}