var bwg_param;

jQuery(document).ready(function () {
  bwg_document_ready();
  jQuery(".bwg-thumbnails").each(function () {
    bwg_all_thumnails_loaded(this);
  });
});

jQuery(window).on("resize", function () {
  jQuery(".bwg-thumbnails").each(function () {
    bwg_all_thumnails_loaded(this);
  });
});

function bwg_all_thumnails_loaded(that) {
  var thumbnails_count = 0;
  var thumbnails_loaded = jQuery(that).find("img").length;
  jQuery(that).find("img").each(function () {
    var fakeSrc = jQuery(this).attr("src");
    jQuery("<img/>").attr("src", fakeSrc).on("load error", function() {
      if ( ++thumbnails_count >= thumbnails_loaded ) {
        bwg_thumbnail(that);
      }
    });
  });
}

function bwg_container_loaded(bwg) {
  jQuery('#gal_front_form_' + bwg).removeClass('bwg-hidden');
  jQuery('#ajax_loading_' + bwg).addClass('bwg-hidden');
}

function bwg_thumbnail(that) {
  var container_width = jQuery(that).width();
  var thumb_width = jQuery(that).data("thumbnail-width");
  var max_count = jQuery(that).data("max-count");
  var column_count = parseInt(container_width / thumb_width) + 1;
  if (column_count > max_count) {
    column_count = max_count;
  }
  /*var flex = 1 / column_count;*/
  var min_width = 100 / column_count;
  var bwg_item = jQuery(that).find(".bwg-item");
  bwg_item.css({
    /*flexGrow: flex,*/
    width: min_width + "%"
  });
  jQuery(that).children(".bwg-item").each(function () {
    var image = jQuery(this).find("img");
    var item2 = jQuery(this).find(".bwg-item2");
    if ( (item2.width() / item2.height()) > (image.width() / image.height()) ) {
      if ( item2.width() > image.width() ) {
        image.css({width: "100%"});
      }
      else {
        image.css({maxWidth: "100%"});
      }
    }
    else {
      if ( item2.height() > image.height() ) {
        image.css({height: "100%"});
      }
      else {
        image.css({maxHeight: "100%"});
      }
    }
    jQuery(this).find(".bwg-item2").css({
      marginLeft: (item2.width() - image.width()) / 2,
      marginTop: (item2.height() - image.height()) / 2
    });
  });
  bwg_container_loaded(jQuery(that).data('bwg'));
}

function bwg_document_ready() {
  var bwg_touch_flag = false;
  jQuery( ".bwg_lightbox .bwg-item0, .bwg_lightbox .bwg_slide" ).on( "click", function ( event ) {
    event.stopPropagation();
    event.preventDefault();
    var that = jQuery(this).parent();
    if ( !bwg_touch_flag ) {
      bwg_touch_flag = true;
      setTimeout( function () {
        bwg_touch_flag = false;
      }, 100 );
      bwg_gallery_box( jQuery( that ).attr( "data-image-id" ), jQuery( that ).closest( '.bwg_container' ) );
      return false;
    }
  } );

  jQuery( ".bwg_lightbox .bwg_ecommerce" ).on( "click", function ( event ) {
    event.stopPropagation();
    if ( !bwg_touch_flag ) {
      bwg_touch_flag = true;
      setTimeout( function () {
        bwg_touch_flag = false;
      }, 100 );
      var image_id = jQuery( this ).closest( ".bwg_lightbox" ).attr( "data-image-id" );
      bwg_gallery_box( image_id, jQuery( this ).closest( '.bwg_container' ), true );
      return false;
    }
  } );
  jQuery( 'div[id^="bwg_container"]' ).each( function () {
    var bwg_container = jQuery( this );
    if ( bwg_container.data( 'right-click-protection' ) ) {
      /* Disable right click.*/
      bwg_container.bind( "contextmenu", function () {
        return false;
      } );
      bwg_container.css( 'webkitTouchCallout', 'none' );
    }

    var search_tags = bwg_container.find('.search_tags');
    if (search_tags.length) {
      search_tags.SumoSelect( {
        placeholder: bwg_objectsL10n.bwg_select_tag,
        search: 1,
        searchText: bwg_objectsL10n.bwg_search,
        forceCustomRendering: true
      } );
    }
  } );

  var bwg_hash = window.location.hash.substring( 1 );
  if ( bwg_hash ) {
    if ( bwg_hash.indexOf( "bwg" ) != "-1" ) {
      bwg_hash_array = bwg_hash.replace( "bwg", "" ).split( "/" );
      var bwg_container = jQuery( '.bwg_container[data-gallery-id=' + bwg_hash_array[0] + ']' );
      if ( bwg_container ) {
        bwg_gallery_box( bwg_hash_array[1], bwg_container );
      }
    }
  }
}

function bwg_clear_search_input (current_view) {
  jQuery("#bwg_search_input_" + current_view).val('');
}

function bwg_check_search_input_enter(that, e) {
  var key_code = e.which || e.keyCode;
  if (key_code == 13) {
    jQuery(that).closest('.bwg_search_container_1').find('.bwg_search').trigger('click');
    return false;
  }
  return true;
}

function bwg_gallery_box(image_id, bwg_container, openEcommerce) {
  if(typeof openEcommerce == undefined){
    openEcommerce = false;
  }
  var bwg = bwg_container.data('bwg');
  var bwg_lightbox_url = bwg_container.data('lightbox-url');
  var filterTags = jQuery("#bwg_tags_id_bwg_standart_thumbnails_" + bwg ).val();
  filterTags = filterTags ? filterTags : 0;
  var ecommerce = openEcommerce == true ? "&open_ecommerce=1" : "";
  var filtersearchname = jQuery("#bwg_search_input_" + bwg ).val();
  filtersearchname = filtersearchname ? filtersearchname : '';
  spider_createpopup(bwg_lightbox_url + '&image_id=' + image_id + "&filter_tag=" +  filterTags + ecommerce + '&filter_search_name=' + filtersearchname, bwg, bwg_container.data('popup-width'), bwg_container.data('popup-height'), 1, 'testpopup', 5, bwg_container.data('buttons-position'));
}

function spider_frontend_ajax(form_id, current_view, id, album_gallery_id, cur_album_id, type, srch_btn, title, sortByParam, load_more, description) {
  jQuery(window).off("scroll");
  var masonry_loaded = 0;
  var mosaic_loaded = 0;
  if (typeof load_more == "undefined") {
    var load_more = false;
  }
  var page_number = jQuery("#page_number_" + current_view).val();
  var bwg_load_more = jQuery("#bwg_load_more_" + current_view).val();
  var bwg_previous_album_ids = jQuery('#bwg_previous_album_id_' + current_view).val();
  var bwg_previous_album_page_numbers = jQuery('#bwg_previous_album_page_number_' + current_view).val();
  var post_data = {};
  if (album_gallery_id == 'back') { // Back from album.
    var bwg_previous_album_id = bwg_previous_album_ids.split(",");
    album_gallery_id = bwg_previous_album_id[1];
    jQuery('#bwg_previous_album_id_' + current_view).val(bwg_previous_album_ids.replace(bwg_previous_album_id[0] + ',', ''));
    var bwg_previous_album_page_number = bwg_previous_album_page_numbers.split(",");
    page_number = bwg_previous_album_page_number[0];
    jQuery('#bwg_previous_album_page_number_' + current_view).val(bwg_previous_album_page_numbers.replace(bwg_previous_album_page_number[0] + ',', ''));
    post_data["action_" + current_view] = 'back';
  }
  else if (cur_album_id != '') { // Enter album (not change the page).
    jQuery('#bwg_previous_album_id_' + current_view).val(album_gallery_id + ',' + bwg_previous_album_ids);
    if (page_number) {
      jQuery('#bwg_previous_album_page_number_' + current_view).val(page_number + ',' + bwg_previous_album_page_numbers);
    }
    page_number = 1;
  }
  if (srch_btn) { // Start search.
    page_number = 1; 
  }
  if (typeof title == "undefined" || title == '') {
    var title = "";
  }
  if (typeof description == "undefined" || description == '') {
		var description = "";
  }
  if (typeof sortByParam == "undefined" || sortByParam == '') {
    var sortByParam = jQuery(".bwg_order_" + current_view).val();
  }
  post_data["page_number_" + current_view] = page_number;
  post_data["bwg_load_more_" + current_view] = bwg_load_more;
  post_data["album_gallery_id_" + current_view] = album_gallery_id;
  post_data["bwg_previous_album_id_" + current_view] = jQuery('#bwg_previous_album_id_' + current_view).val();
  post_data["bwg_previous_album_page_number_" + current_view] = jQuery('#bwg_previous_album_page_number_' + current_view).val();
  post_data["type_" + current_view] = type;
  post_data["title_" + current_view] = title;
  post_data["description_" + current_view] = description;
  post_data["sortImagesByValue_" + current_view] = sortByParam;
  if (jQuery("#bwg_search_input_" + current_view).length > 0) { // Search box exists.
    post_data["bwg_search_" + current_view] = jQuery("#bwg_search_input_" + current_view).val();
  }
  post_data["bwg_tag_id_" + id] = jQuery("#bwg_tag_id_" + id).val();
  // Loading.
  if ( id != "bwg_standart_thumbnails_" + current_view ) {
    jQuery("#ajax_loading_" + current_view).css('display', '');
  }
  else {
    jQuery("#ajax_loading_" + current_view).removeClass('bwg-hidden');
  }
  jQuery.ajax({
		type: "POST",
		url: window.location,
		data: post_data,
		success: function (data) {
		  masonry_loaded = jQuery(data).find('#' + form_id).find(".bwg_masonry_thumb_spun_" + current_view + " img").length;
		  mosaic_loaded = jQuery(data).find('#' + form_id).find(".bwg_mosaic_thumb_spun_" + current_view + " img").length;
		  if (load_more) {
			var strr = jQuery(data).find('#' + id).html();
			jQuery('#' + id).append(strr);

			jQuery("div[id^='bwg_container1_'] form").each(function () {
			  if (jQuery(this).data("current") == current_view) {
				var str = jQuery(data).find('.bwg_nav_cont_' + current_view).html();
				jQuery('.bwg_nav_cont_' + current_view).html(str);
			  }
			  else {
				var str = jQuery(this).find('span[class^="bwg_nav_cont_"]').html();
				jQuery(this).find('span[class^="bwg_nav_cont_"]').html(str);
			  }
			});
		  }
		  else {
			var str = jQuery(data).find('#' + form_id).html();
			jQuery('#' + form_id).html(str);
		  }
		  // There are no images.
		  if (jQuery("#bwg_search_input_" + current_view).length > 0 && album_gallery_id == 0) { // Search box exists and not album view.
			var bwg_images_count = jQuery('#bwg_images_count_' + current_view).val();
			if (bwg_images_count == 0) {
			  var cont = jQuery("#" + id).parent().html();
			  var error_msg = '<div style="width:95%"><div class="wd_error"><p><strong>' + bwg_objectL10n.bwg_search_result + '</strong></p></div></div>';
			  jQuery("#" + id).parent().html(error_msg + cont)
			}
		  }
		},
		beforeSend: function(){
		},
		complete:function(){
			jQuery("div[id^='bwg_container1_'] img").each(function () {
			  if (jQuery(this).attr("data-lazy-src") != '') {
				  jQuery(this).attr("src", jQuery(this).attr("data-lazy-src"));
			  }
			  else if (jQuery(this).attr("data-src") != '') {
				  jQuery(this).attr("src", jQuery(this).attr("data-src"));
        }
			});
			jQuery(".blog_style_image_buttons_conteiner_" + current_view).find(jQuery(".bwg_blog_style_img_" + current_view)).on("load", function() {
			  jQuery(".bwg_blog_style_img_" + current_view).closest(jQuery(".blog_style_image_buttons_conteiner_" + current_view)).show();
			});
      if ( id != "bwg_standart_thumbnails_" + current_view ) {
        jQuery("#ajax_loading_" + current_view).css('display', 'none');
        //jQuery("#ajax_loading_" + current_view).addClass('bwg-hidden');
      }
			jQuery("#bwg_tags_id_" + id).val(jQuery("#bwg_tag_id_" + id).val());

			if (jQuery(".pagination-links_" + current_view).length) {
			  jQuery("html, body").animate({scrollTop: jQuery('#' + form_id).offset().top - 150}, 500);
			}
			/* For thumbnails view.*/
			if (id == "bwg_standart_thumbnails_" + current_view) {
        bwg_document_ready();
			  bwg_all_thumnails_loaded(".bwg-container-" + current_view);
			}
      else if ( id.indexOf('album') === -1 && id.indexOf('bwg_masonry_thumbnails') === -1) {
      /* Do not apply to album views. */
        window["bwg_document_ready_" + current_view]();
      }
			/* For masonry view.*/
			if (id == "bwg_masonry_thumbnails_" + current_view || id == "bwg_album_masonry_" + current_view) {
			  window["bwg_masonry_ajax_"+ current_view](masonry_loaded);
			}
			/* For mosaic view.*/
			if (id == "bwg_mosaic_thumbnails_" + current_view) {
			  window["bwg_mosaic_ajax_" + current_view](mosaic_loaded);
			}
			/* For Blog style view.*/
			jQuery(".blog_style_images_conteiner_" + current_view + " .bwg_embed_frame_16x9_" + current_view).each(function (e) {
			  jQuery(this).width(jQuery(this).parent().width());
			  jQuery(this).height(jQuery(this).width() * 0.5625);
			});
			jQuery(".blog_style_images_conteiner_" + current_view + " .bwg_embed_frame_instapost_" + current_view).each(function (e) {
			  jQuery(this).width(jQuery(this).parent().width());
			  /* 16 is 2*padding inside iframe */
			  /* 96 is 2*padding(top) + 1*padding(bottom) + 40(footer) + 32(header) */
			  jQuery(this).height((jQuery(this).width() - 16) * jQuery(this).attr('data-height') / jQuery(this).attr('data-width') + 96);
			});
			/* For Image browser view.*/
			jQuery('#bwg_embed_frame_16x9_' + current_view).width(jQuery('#bwg_embed_frame_16x9_' + current_view).parent().width());
			jQuery('#bwg_embed_frame_16x9_' + current_view).height(jQuery('#bwg_embed_frame_16x9_' + current_view).width() * 0.5625);
			jQuery('#bwg_embed_frame_instapost_' + current_view).width(jQuery('#bwg_embed_frame_16x9_' + current_view).parent().width());
			/* 16 is 2*padding inside iframe */
			/* 96 is 2*padding(top) + 1*padding(bottom) + 40(footer) + 32(header) */
			jQuery('.bwg_embed_frame_instapost_' + current_view).height((jQuery('.bwg_embed_frame_instapost_' + current_view).width() - 16) * jQuery('.bwg_embed_frame_instapost_' + current_view).attr('data-height') / jQuery('.bwg_embed_frame_instapost_' + current_view).attr('data-width') + 96);
		}
	});
	return false;
}

function bwg_select_tag(current_view, form_id, cur_gal_id, album_gallery_id, type, reset) {
  if (reset) {
    jQuery("#bwg_tag_id_" + cur_gal_id).val('');
  }
  spider_frontend_ajax(form_id, current_view, cur_gal_id, album_gallery_id, '', type, 1, '');
}

function bwg_change_image(current_key, key, data, from_effect) {
    bwg = bwg_param['bwg'];
    bwg_current_key = bwg_param['bwg_current_key'];
    // var bwg_image_info_pos = jQuery(".bwg_ctrl_btn_container").height();
    jQuery(".bwg_image_info").css("height","auto");
    setTimeout(function(){
        if(jQuery(".bwg_image_info_container1").height() < (jQuery(".bwg_image_info").height() + jQuery(".bwg_toggle_container").height() + bwg_image_info_pos + 2*(parseInt(bwg_param['lightbox_info_margin'])))) {
            if(bwg_param['lightbox_ctrl_btn_pos'] == 'top') {
                jQuery(".bwg_image_info").css("top",bwg_image_info_pos + "px");
            }
            jQuery(".bwg_image_info").height(jQuery(".bwg_image_info_container1").height() - jQuery(".bwg_toggle_container").height() - bwg_image_info_pos - 2*(parseInt(bwg_param['lightbox_info_margin'])));
        }
    }, 200);
    jQuery("#spider_popup_left").show();
    jQuery("#spider_popup_right").show();
    jQuery(".bwg_image_info").hide();
    if (bwg_param['enable_loop'] == 0) {
        if (key == (parseInt(data.length) - 1)) {
            jQuery("#spider_popup_right").hide();
        }
        if (key == 0) {
            jQuery("#spider_popup_left").hide();
        }
    }
    var ecommerceACtive = bwg_param['ecommerceACtive'];
    if( ecommerceACtive == 1 && bwg_param['enable_image_ecommerce'] == 1 ) {
        if( data[key]["pricelist"] == 0) {
            jQuery(".bwg_ecommerce").hide();
        }
        else {
            jQuery(".bwg_ecommerce").show();
            jQuery(".pge_tabs li").hide();
            jQuery("#downloads").hide();
            jQuery("#manual").hide();
            var pricelistSections = data[key]["pricelist_sections"].split(",");

            if(pricelistSections){
                jQuery("#" + pricelistSections[0]).show();
                jQuery("[name=type]").val(pricelistSections[0]);
                if(pricelistSections.length > 1){
                    jQuery(".pge_tabs").show();
                    for( k=0 ; k<pricelistSections.length; k++ ){
                        jQuery("#" + pricelistSections[k] + "_li").show();
                    }
                }
                else{
                    jQuery(".pge_tabs").hide();
                }
            }
            else{
                jQuery("[name=type]").val("");
            }
        }
    }
    /* Pause videos.*/
    jQuery("#bwg_image_container").find("iframe").each(function () {
        jQuery(this)[0].contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
        jQuery(this)[0].contentWindow.postMessage('{ "method": "pause" }', "*");
        jQuery(this)[0].contentWindow.postMessage('pause', '*');
    });
    jQuery("#bwg_image_container"+bwg).find("video").each(function () {
        jQuery(this).trigger('pause');
    });

    if (typeof data[key] != 'undefined') {
        if (typeof data[current_key] != 'undefined') {
            if (jQuery(".bwg_play_pause").length && !jQuery(".bwg_play_pause").hasClass("fa-play")) {
                bwg_play();
            }
            if (!from_effect) {
                /* Change image key.*/
                jQuery("#bwg_current_image_key").val(key);
                /*if (current_key == '-1') {
                  current_key = jQuery(".bwg_thumb_active").children("img").attr("image_key");
                }*/
            }
            if (bwg_trans_in_progress) {
                event_stack.push(current_key + '-' + key);
                return;
            }
            var direction = 'right';
            if (bwg_current_key > key) {
                var direction = 'left';
            }
            else if (bwg_current_key == key) {
                return;
            }
            /*jQuery("#spider_popup_left").hover().css({"display": "inline"});
            jQuery("#spider_popup_right").hover().css({"display": "inline"});*/
            jQuery(".bwg_image_count").html(data[key]["number"]);
            /* Set filmstrip initial position.*/
            jQuery(".bwg_watermark").css({display: 'none'});
            /* Set active thumbnail position.*/
            if ( bwg_param['width_or_height'] == 'width' ) {
                bwg_current_filmstrip_pos = key * (jQuery(".bwg_filmstrip_thumbnail").width() + 2 + 2 * bwg_param['lightbox_filmstrip_thumb_border_width']);
            } else if ( bwg_param['width_or_height'] == 'height' ) {
                bwg_current_filmstrip_pos = key * (jQuery(".bwg_filmstrip_thumbnail").height() + 2 + 2 * bwg_param['lightbox_filmstrip_thumb_border_width']);            }
            bwg_param['bwg_current_key'] = key;
            /* Change hash.*/
            window.location.hash = "bwg"+bwg_param['gallery_id']+"/" + data[key]["id"];
            /* Change image id for rating.*/
            if (bwg_param['popup_enable_rate']) {
                jQuery("#bwg_rate_form input[name='image_id']").val(data[key]["id"]);
                jQuery("#bwg_star").attr("data-score", data[key]["avg_rating"]);
                jQuery("#bwg_star").removeAttr("title");
                bwg_rating(data[key]["rate"], data[key]["rate_count"], data[key]["avg_rating"], key);
            }
            /* Increase image hit counter.*/
            spider_set_input_value('rate_ajax_task', 'save_hit_count');
            spider_rate_ajax_save('bwg_rate_form');
            jQuery(".bwg_image_hits span").html(++data[key]["hit_count"]);
            /* Change image id.*/
            jQuery("#bwg_popup_image").attr('image_id', data[key]["id"]);
            /* Change image title, description.*/
            jQuery(".bwg_image_title").html(jQuery('<span style="display: block;" />').html(data[key]["alt"]).text());
            jQuery(".bwg_image_description").html(jQuery('<span style="display: block;" />').html(data[key]["description"]).text());
            /*jQuery(".bwg_image_info").removeAttr("style");*/
          /* Set active thumbnail.*/

          jQuery(".bwg_filmstrip_thumbnail").removeClass("bwg_thumb_active").addClass("bwg_thumb_deactive");
          jQuery("#bwg_filmstrip_thumbnail_" + key).removeClass("bwg_thumb_deactive").addClass("bwg_thumb_active");
            if (data[key]["alt"].trim() == "") {
                if (data[key]["description"].trim() == "") {
                    jQuery(".bwg_image_info").css("background", "none");
                }
            }
            if (jQuery(".bwg_image_info_container1").css("display") != 'none') {
                jQuery(".bwg_image_info_container1").css("display", "table-cell");
            }
            else {
                jQuery(".bwg_image_info_container1").css("display", "none");
            }
            /* Change image rating.*/
            if (jQuery(".bwg_image_rate_container1").css("display") != 'none') {
                jQuery(".bwg_image_rate_container1").css("display", "table-cell");
            }
            else {
                jQuery(".bwg_image_rate_container1").css("display", "none");
            }
            var current_image_class = jQuery(".bwg_popup_image_spun").css("zIndex") == 2 ? ".bwg_popup_image_spun" : ".bwg_popup_image_second_spun";
            var next_image_class = current_image_class == ".bwg_popup_image_second_spun" ? ".bwg_popup_image_spun" : ".bwg_popup_image_second_spun";

            var is_embed = data[key]['filetype'].indexOf("EMBED_") > -1 ? true : false;
            var is_embed_instagram_post = data[key]['filetype'].indexOf('INSTAGRAM_POST') > -1 ? true : false;
            var is_embed_instagram_video = data[key]['filetype'].indexOf('INSTAGRAM_VIDEO') > -1 ? true : false;
            var cur_height = jQuery(current_image_class).height();
            var cur_width = jQuery(current_image_class).width();
            var innhtml = '<span class="bwg_popup_image_spun1" style="display: ' + (!is_embed ? 'table' : 'block') + ' ;width: inherit; height: inherit;"><span class="bwg_popup_image_spun2" style="display:' + (!is_embed ? 'table-cell' : 'block') + '; vertical-align: middle;text-align: center;height: 100%;">';
            if (!is_embed) {
              jQuery(".bwg-loading").removeClass("hidden");
                jQuery("#bwg_download").removeClass("hidden");
                innhtml += '<img style="max-height: ' + cur_height + 'px; max-width: ' + cur_width + 'px;" class="bwg_popup_image bwg_popup_watermark" src="' + bwg_param['site_url'] + jQuery('<span style="display: block;" />').html(data[key]["image_url"]).text() + '" alt="' + data[key]["alt"] + '" />';
            }
            else { /*is_embed*/
              /* hide download button if image source is embed */
                jQuery("#bwg_download").addClass("hidden");
                /*innhtml += '<span style="height: ' + cur_height + 'px; width: ' + cur_width + 'px;" class="bwg_popup_embed bwg_popup_watermark">';*/
                innhtml += '<span class="bwg_popup_embed bwg_popup_watermark" style="display: table; table-layout: fixed; height: 100%;">' + (is_embed_instagram_video ? '<div class="bwg_inst_play_btn_cont" onclick="bwg_play_instagram_video(this)" ><div class="bwg_inst_play"></div></div>' : ' ');
                if (is_embed_instagram_post) {
                    var post_width = 0;
                    var post_height = 0;
                    if (cur_height < cur_width + 88) {
                        post_height = cur_height;
                        post_width = post_height - 88;
                    }
                    else {
                        post_width = cur_width;
                        post_height = post_width + 88;
                    }
                    innhtml += spider_display_embed(data[key]['filetype'], data[key]['image_url'], data[key]['filename'], {class:"bwg_embed_frame", 'data-width': data[key]['image_width'], 'data-height': data[key]['image_height'], frameborder: "0", allowfullscreen: "allowfullscreen", style: "width:" + post_width + "px; height:" + post_height + "px; vertical-align:middle; display:inline-block; position:relative;"});
                }
                else {
                   innhtml += spider_display_embed(data[key]['filetype'],data[key]['image_url'], data[key]['filename'], {class:"bwg_embed_frame", frameborder:"0", allowfullscreen:"allowfullscreen", style:"display:table-cell; width:inherit; height:inherit; vertical-align:middle;" });
                }
                innhtml += "</span>";
            }
            innhtml += '</span></span>';
            jQuery(next_image_class).html(innhtml);
            jQuery(next_image_class).find("img").on("load error", function () {
              jQuery(".bwg-loading").addClass("hidden");
            });
            jQuery(".bwg_popup_embed > .bwg_embed_frame > img, .bwg_popup_embed > .bwg_embed_frame > video").css({
                maxWidth: cur_width,
                maxHeight: cur_height,
                height: 'auto',
            });
            function bwg_afterload() {
                if (bwg_param['preload_images']) {
                    bwg_preload_images(key);
                }

                    window['bwg_'+bwg_param['bwg_image_effect']](current_image_class, next_image_class, direction);
                jQuery(current_image_class).find('.bwg_fb_video').each(function () {
                    jQuery(this).attr('src', '');
                });
                if (!is_embed) {
                    jQuery("#bwg_fullsize_image").attr("href", bwg_param['site_url'] + data[key]['image_url']);
                    jQuery("#bwg_download").attr("href", bwg_param['site_url'] + data[key]['thumb_url'].replace('/thumb/', '/.original/'));
                }
                else {
                    jQuery("#bwg_fullsize_image").attr("href", data[key]['image_url']);
                }
                var image_arr = data[key]['image_url'].split("/");
                jQuery("#bwg_download").attr("download", image_arr[image_arr.length - 1]);
                /* Change image social networks urls.*/
                var bwg_share_url = encodeURIComponent(bwg_param['bwg_share_url']) + "=" + data[key]['id'] + encodeURIComponent('#bwg'+bwg_param['gallery_id']+'/') + data[key]['id'];

                if (is_embed) {
                    var bwg_share_image_url = encodeURIComponent(data[key]['thumb_url']);
                }
                else {
                    var bwg_share_image_url = bwg_param['bwg_share_image_url'] + encodeURIComponent(encodeURIComponent(data[key]['image_url']));
                }
                bwg_share_image_url = bwg_share_image_url.replace(/%252F/g, '%2F');
                if (typeof addthis_share != "undefined") {
                    addthis_share.url = bwg_share_url;
                }
                jQuery("#bwg_facebook_a").attr("href", "https://www.facebook.com/sharer/sharer.php?u=" + bwg_share_url);
                jQuery("#bwg_twitter_a").attr("href", "https://twitter.com/share?url=" + bwg_share_url);
                jQuery("#bwg_google_a").attr("href", "https://plus.google.com/share?url=" + bwg_share_url);
                jQuery("#bwg_pinterest_a").attr("href", "http://pinterest.com/pin/create/button/?s=100&url=" + bwg_share_url + "&media=" + bwg_share_image_url + "&description=" + data[key]['alt'] + '%0A' + data[key]['description']);
                jQuery("#bwg_tumblr_a").attr("href", "https://www.tumblr.com/share/photo?source=" + bwg_share_image_url + "&caption=" + data[key]['alt'] + "&clickthru=" + bwg_share_url);
                /* Load comments.*/
                if (jQuery(".bwg_comment_container").hasClass("bwg_open")) {
                    if (data[key]["comment_count"] == 0) {
                        jQuery("#bwg_added_comments").hide();
                    }
                    else {
                        jQuery("#bwg_added_comments").show();
                        spider_set_input_value('ajax_task', 'display');
                        spider_set_input_value('image_id', jQuery('#bwg_popup_image').attr('image_id'));
                        spider_ajax_save('bwg_comment_form');
                    }
                }
                if (jQuery(".bwg_ecommerce_container").hasClass("bwg_open")) {
                    /* Pricelist */
                    if(data[key]["pricelist"] == 0){
                        /* Close ecommerce.*/
                        bwg_popup_sidebar_close(jQuery(".bwg_ecommerce_container"));
                        bwg_animate_image_box_for_hide_sidebar();

                        jQuery(".bwg_ecommerce_container").attr("class", "bwg_ecommerce_container bwg_close");
                        jQuery(".bwg_ecommerce").attr("title", bwg_objectsL10n.bwg_show_ecommerce);
                        jQuery(".spider_popup_close_fullscreen").show();
                    }
                    else{
                        get_ajax_pricelist();
                    }
                }
                /* Update custom scroll.*/
                if (typeof jQuery().mCustomScrollbar !== 'undefined') {
                    if (jQuery.isFunction(jQuery().mCustomScrollbar)) {
                        jQuery(".bwg_comments").mCustomScrollbar({
                            advanced:{
                                updateOnContentResize: true
                            }
                        });
                    }
                }
                jQuery(".bwg_comments .mCSB_scrollTools").hide();
                if (bwg_param['enable_image_filmstrip']) {
                    bwg_move_filmstrip();
                }
                bwg_resize_instagram_post();
            }
            if (!is_embed) {
                var cur_img = jQuery(next_image_class).find('img');
                cur_img.one('load', function() {
                    bwg_afterload();
                }).each(function() {
                    if(this.complete) jQuery(this).load();
                });
            }
            else {
                bwg_afterload();
            }
        }
    }
}

function bwg_preload_images( key ) {
  var count_all = data.length;
  var preloadCount = (bwg_param['preload_images_count'] == 0) ? data.length : (bwg_param['preload_images_count'] > data.length) ? data.length : bwg_param['preload_images_count'];
  var is_embed;
  var indexedImgCount = 0;
  var leftIndex, rightIndex;
  for ( var i = 1; i < count_all; i++ ) {
    leftIndex = key - i;
    rightIndex = key + i;
    if ( key == 0 ) {
      leftIndex = 0;
      rightIndex = 0;
    }
    /* Right from key indexes */
    if ( rightIndex < count_all ) {  /* check if right index is greater than max index */
      is_embed = data[rightIndex]['filetype'].indexOf("EMBED_") > -1 ? true : false;
      if ( typeof data[rightIndex] != "undefined" ) {
        if ( !is_embed ) {
          jQuery("<img/>").attr("src", bwg_param['site_url'] + jQuery('<span style="display: block;" />').html(data[rightIndex]["image_url"]).text());
          indexedImgCount++;
          if ( indexedImgCount >= preloadCount ) {
            break;
          }
        }
      }
    }
    /* Left from key indexes */
	if ( leftIndex > 0 ) {
      is_embed = data[leftIndex]['filetype'].indexOf("EMBED_") > -1 ? true : false;
      if ( typeof data[leftIndex] != "undefined" ) {
        if ( !is_embed ) {
          jQuery("<img/>").attr("src", bwg_param['site_url'] + jQuery('<span style="display: block;" />').html(data[leftIndex]["image_url"]).text());
          indexedImgCount++;
          if ( indexedImgCount >= preloadCount ) {
            break;
          }
        }
      }
    }
  }
}

function bwg_cube(tz, ntx, nty, nrx, nry, wrx, wry, current_image_class, next_image_class, direction, bwg) {
  var type_slideshow = false;
  var bwg_prefix = "";
  var bwg_transition_dur;
  if ( typeof bwg != 'undefined' ) {
    type_slideshow = true;
    bwg_params[bwg]['bwg_trans_in_progress'] = true;
    bwg_prefix = "_"+bwg;
    bwg_transition_dur = bwg_params[bwg]['bwg_transition_duration'];
  } else {
    bwg_transition_dur = bwg_transition_duration;
  }
  /* If browser does not support 3d transforms/CSS transitions.*/
  if (!bwg_testBrowser_cssTransitions( bwg )) {
    return bwg_fallback(current_image_class, next_image_class, direction, bwg);
  }
  if (!bwg_testBrowser_cssTransforms3d( bwg )) {
    return bwg_fallback3d(current_image_class, next_image_class, direction, bwg);
  }
  if( !type_slideshow) { /* from lightbox */
      bwg_trans_in_progress = true;
      /* Set active thumbnail.*/
      jQuery(".bwg_filmstrip_thumbnail").removeClass("bwg_thumb_active").addClass("bwg_thumb_deactive");
      jQuery("#bwg_filmstrip_thumbnail_" + bwg_param['bwg_current_key']).removeClass("bwg_thumb_deactive").addClass("bwg_thumb_active");
      jQuery(".bwg_slide_bg").css('perspective', 1000);
  } else { /* from slideshow */
      /* Set active thumbnail.*/
      jQuery(".bwg_slideshow_filmstrip_thumbnail_"+bwg).removeClass("bwg_slideshow_thumb_active_"+bwg).addClass("bwg_slideshow_thumb_deactive_"+bwg);
      jQuery("#bwg_filmstrip_thumbnail_" + bwg_params[bwg]['bwg_current_key']+ "_"+bwg).removeClass("bwg_slideshow_thumb_deactive_"+bwg).addClass("bwg_slideshow_thumb_active_"+bwg);
      jQuery(".bwg_slideshow_dots_"+bwg).removeClass("bwg_slideshow_dots_active_"+bwg).addClass("bwg_slideshow_dots_deactive_"+bwg);
      jQuery("#bwg_dots_" + bwg_params[bwg]['bwg_current_key']).removeClass("bwg_slideshow_dots_deactive_"+bwg).addClass("bwg_slideshow_dots_active_"+bwg);
      jQuery(".bwg_slide_bg_"+bwg).css('perspective', 1000);
  }
  jQuery(current_image_class).css({
    transform : 'translateZ(' + tz + 'px)',
    backfaceVisibility : 'hidden'
  });
  jQuery(next_image_class).css({
    opacity : 1,
    filter: 'Alpha(opacity=100)',
    backfaceVisibility : 'hidden',
    transform : 'translateY(' + nty + 'px) translateX(' + ntx + 'px) rotateY('+ nry +'deg) rotateX('+ nrx +'deg)'
  });
  jQuery(".bwg_slider"+bwg_prefix).css({
    transform: 'translateZ(-' + tz + 'px)',
    transformStyle: 'preserve-3d'
  });
  /* Execution steps.*/
  setTimeout(function () {
    jQuery(".bwg_slider"+bwg_prefix).css({
      transition: 'all ' + bwg_transition_dur + 'ms ease-in-out',
      transform: 'translateZ(-' + tz + 'px) rotateX('+ wrx +'deg) rotateY('+ wry +'deg)'
    });
  }, 20);
  /* After transition.*/
  jQuery(".bwg_slider"+bwg_prefix).one('webkitTransitionEnd transitionend otransitionend oTransitionEnd mstransitionend', jQuery.proxy(bwg_after_trans));
  function bwg_after_trans() {
    jQuery(current_image_class).removeAttr('style');
    jQuery(next_image_class).removeAttr('style');
    jQuery(".bwg_slider"+bwg_prefix).removeAttr('style');
    jQuery(current_image_class).css({'opacity' : 0, filter: 'Alpha(opacity=0)', 'z-index': 1});
    jQuery(next_image_class).css({'opacity' : 1, filter: 'Alpha(opacity=100)', 'z-index' : 2});
    jQuery(".bwg_image_info").show();
    bwg_trans_in_progress = false;
    jQuery(current_image_class).html('');

    if ( type_slideshow ) {  /*check if cube works from slideshow*/
      window["bwg_change_watermark_container_" + bwg]();
      bwg_params[bwg]['bwg_trans_in_progress'] = false;
    }

    if (typeof event_stack !== 'undefined') {
      if (event_stack.length > 0) {
        key = event_stack[0].split("-");
        event_stack.shift();
        //bwg_change_image(key[0], key[1], data, true);
        window["bwg_change_image"+bwg_prefix](key[0], key[1], data, true);
      }
    }
    bwg_change_watermark_container();
  }
  if (bwg_transition_dur == 0) {
    bwg_after_trans();
  }
}

/* For browsers that does not support transitions.*/
function bwg_fallback(current_image_class, next_image_class, direction, bwg) {
    bwg_fade(current_image_class, next_image_class, direction, bwg);
}
/* For browsers that support transitions, but not 3d transforms (only used if primary transition makes use of 3d-transforms).*/
function bwg_fallback3d(current_image_class, next_image_class, direction, bwg) {
    bwg_sliceV(current_image_class, next_image_class, direction, bwg);
}
function bwg_none(current_image_class, next_image_class, direction, bwg) {
    jQuery(current_image_class).css({'opacity' : 0, 'z-index': 1});
    jQuery(next_image_class).css({'opacity' : 1, 'z-index' : 2});
    jQuery(".bwg_image_info").show();
    bwg_trans_in_progress = false;
    jQuery(current_image_class).html('');
    bwg_change_watermark_container();
}
function bwg_fade(current_image_class, next_image_class, direction, bwg) {
  var type_slideshow = false;
  var bwg_prefix = "";
  var bwg_transition_dur;
  if(typeof bwg != 'undefined') {
      type_slideshow = true;
      bwg_params[bwg]['bwg_trans_in_progress'] = true;
      bwg_prefix = "_"+bwg;
      bwg_transition_dur = bwg_params[bwg]['bwg_transition_duration'];
  } else {
      bwg_transition_dur = bwg_transition_duration;
  }

  if(type_slideshow) {
      /* Set active thumbnail.*/
      jQuery(".bwg_slideshow_filmstrip_thumbnail_"+bwg).removeClass("bwg_slideshow_thumb_active_"+bwg).addClass("bwg_slideshow_thumb_deactive_"+bwg);
      jQuery("#bwg_filmstrip_thumbnail_" + bwg_params[bwg]['bwg_current_key'] + "_"+bwg).removeClass("bwg_slideshow_thumb_deactive_"+bwg).addClass("bwg_slideshow_thumb_active_"+bwg);
      jQuery(".bwg_slideshow_dots_"+bwg).removeClass("bwg_slideshow_dots_active_"+bwg).addClass("bwg_slideshow_dots_deactive_"+bwg);
      jQuery("#bwg_dots_" + bwg_params[bwg]['bwg_current_key'] + "_"+bwg).removeClass("bwg_slideshow_dots_deactive_"+bwg).addClass("bwg_slideshow_dots_active_"+bwg);
  } else {
      /* Set active thumbnail.*/
      jQuery(".bwg_filmstrip_thumbnail").removeClass("bwg_thumb_active").addClass("bwg_thumb_deactive");
      jQuery("#bwg_filmstrip_thumbnail_" + bwg_param['bwg_current_key']).removeClass("bwg_thumb_deactive").addClass("bwg_thumb_active");
  }
    function bwg_after_trans() {
        jQuery(".bwg_image_info").show();
        window['bwg_change_watermark_container' + bwg_prefix]();
        if( type_slideshow ) {
          bwg_params[bwg]['bwg_trans_in_progress'] = false;
        }
    }
    if (bwg_testBrowser_cssTransitions()) {
        jQuery(next_image_class).css('transition', 'opacity ' + bwg_transition_dur + 'ms linear');
        jQuery(current_image_class).css({'opacity' : 0, 'z-index': 1});
        jQuery(next_image_class).css({'opacity' : 1, 'z-index' : 2});
        jQuery(next_image_class).one('webkitTransitionEnd transitionend otransitionend oTransitionEnd mstransitionend', jQuery.proxy(bwg_after_trans));
    }
    else {
        jQuery(current_image_class).animate({'opacity' : 0, 'z-index' : 1}, bwg_transition_dur);
        jQuery(next_image_class).animate({
            'opacity' : 1,
            'z-index': 2
        }, {
            duration: bwg_transition_dur,
            complete: function () {
              bwg_trans_in_progress = false;
              jQuery(current_image_class).html('');
                bwg_after_trans()
            }
        });
        /* For IE.*/
        jQuery(current_image_class).fadeTo(bwg_transition_dur, 0);
        jQuery(next_image_class).fadeTo(bwg_transition_dur, 1);
    }
    if (bwg_transition_dur == 0) {
        bwg_after_trans();
    }
}
function bwg_testBrowser_cssTransitions() {
    return bwg_testDom('Transition');
}
function bwg_testBrowser_cssTransforms3d() {
    return bwg_testDom('Perspective');
}
function bwg_testDom(prop) {
    /* Browser vendor CSS prefixes.*/
    var browserVendors = ['', '-webkit-', '-moz-', '-ms-', '-o-', '-khtml-'];
    /* Browser vendor DOM prefixes.*/
    var domPrefixes = ['', 'Webkit', 'Moz', 'ms', 'O', 'Khtml'];
    var i = domPrefixes.length;
    while (i--) {
        if (typeof document.body.style[domPrefixes[i] + prop] !== 'undefined') {
            return true;
        }
    }
    return false;
}

function bwg_change_watermark_container() {
    jQuery(".bwg_slider").children().each(function() {
        if (jQuery(this).css("zIndex") == 2) {
            /* This may be neither img nor iframe.*/
            var bwg_current_image_span = jQuery(this).find("img");
            if (!bwg_current_image_span.length) {
                bwg_current_image_span = jQuery(this).find("iframe");
            }
            if (!bwg_current_image_span.length) {
                bwg_current_image_span = jQuery(this).find("video");
            }
            /*set timeout for video to get size according to style, and then put watermark*/
            setTimeout(function () {
                var width = bwg_current_image_span.width();
                var height = bwg_current_image_span.height();


                jQuery(".bwg_watermark_spun").width(width);
                jQuery(".bwg_watermark_spun").height(height);
                jQuery(".bwg_watermark").css({display: ''});
                /* Set watermark image size.*/
                var comment_container_width = 0;
                if (jQuery(".bwg_comment_container").hasClass("bwg_open") || jQuery(".bwg_ecommerce_container").hasClass("bwg_open") ) {
                    comment_container_width = bwg_param['lightbox_comment_width'];
                }
                if (width <= (jQuery(window).width() - comment_container_width)) {
                    jQuery(".bwg_watermark_image").css({
                        width: ((jQuery(".spider_popup_wrap").width() - comment_container_width) * bwg_param['watermark_font_size'] / bwg_param['image_width'])
                });
                    jQuery(".bwg_watermark_text, .bwg_watermark_text:hover").css({
                        fontSize: ((jQuery(".spider_popup_wrap").width() - comment_container_width) * bwg_param['watermark_font_size'] / bwg_param['image_width'])
                });
                }
            }, 100);
        }
    });
}

function bwg_set_filmstrip_pos(filmStripWidth) {
    if ( bwg_param['outerWidth_or_outerHeight'] == 'outerWidth' ) {
        var selectedImagePos = -bwg_current_filmstrip_pos - (jQuery(".bwg_filmstrip_thumbnail").outerWidth(true)) / 2;
    } else if ( bwg_param['outerWidth_or_outerHeight'] == 'outerHeight' ) {
        var selectedImagePos = -bwg_current_filmstrip_pos - (jQuery(".bwg_filmstrip_thumbnail").outerHeight(true)) / 2;
    }

    if ( bwg_param['width_or_height'] == 'width' ) {
        var imagesContainerLeft = Math.min(0, Math.max(filmStripWidth - jQuery(".bwg_filmstrip_thumbnails").width(), selectedImagePos + filmStripWidth / 2));
    } else if (bwg_param['width_or_height'] == 'height') {
        var imagesContainerLeft = Math.min(0, Math.max(filmStripWidth - jQuery(".bwg_filmstrip_thumbnails").height(), selectedImagePos + filmStripWidth / 2));
    }
    if ( bwg_param['left_or_top'] == 'left' ) {
        jQuery(".bwg_filmstrip_thumbnails").animate({
            left: imagesContainerLeft
        }, {
            duration: 500,
            complete: function () {
                bwg_filmstrip_arrows();
            }
        });
    } else if ( bwg_param['left_or_top'] == 'top' ) {
        jQuery(".bwg_filmstrip_thumbnails").animate({
            top: imagesContainerLeft
        }, {
            duration: 500,
            complete: function () {
                bwg_filmstrip_arrows();
            }
        });

    }
}

// open  popup sidebar
function bwg_popup_sidebar_open(obj){
    var comment_container_width = bwg_param['lightbox_comment_width'];
    var lightbox_comment_pos = bwg_param['lightbox_comment_pos'];
    if (comment_container_width > jQuery(window).width()) {
        comment_container_width = jQuery(window).width();
        obj.css({
            width: comment_container_width,
        });
        jQuery(".spider_popup_close_fullscreen").hide();
        jQuery(".spider_popup_close").hide();
        if (jQuery(".bwg_ctrl_btn").hasClass("fa-pause")) {
            var isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
            jQuery(".bwg_play_pause").trigger(isMobile ? 'touchend' : 'click');
        }
    }
    else {
        jQuery(".spider_popup_close_fullscreen").show();
    }

    //obj.animate({<?php echo $theme_row->lightbox_comment_pos; ?>: 0}, 100);
    if(lightbox_comment_pos == 'left') {
        obj.animate({left: 0}, 100);
    } else {
        obj.animate({right: 0}, 100);
    }
}

function bwg_popup_sidebar_close(obj){
    var border_width = parseInt(obj.css('borderRightWidth'));
    if (!border_width) {
        border_width = 0;
    }
    if( lightbox_comment_pos == 'left' ) {
        obj.animate({left: -obj.width() - border_width}, 100);
    } else if ( lightbox_comment_pos == 'right' ) {
        obj.animate({right: -obj.width() - border_width}, 100);
    }
}

function bwg_animate_image_box_for_hide_sidebar() {
  if ( lightbox_comment_pos == 'left' ) {
    jQuery( ".bwg_image_wrap" ).animate( {
      left: 0,
      width: jQuery( "#spider_popup_wrap" ).width()
    }, 100 );
  } else if ( lightbox_comment_pos == 'right' ) {
    jQuery( ".bwg_image_wrap" ).animate( {
      right: 0,
      width: jQuery( "#spider_popup_wrap" ).width()
    }, 100 );
  }
  jQuery( ".bwg_image_container" ).animate( {
    width: jQuery( "#spider_popup_wrap" ).width() - ( bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0 )
  }, 100 );
  jQuery( ".bwg_popup_image" ).animate( {
    maxWidth: jQuery( "#spider_popup_wrap" ).width() - ( bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0 )
  }, {
    duration: 100,
    complete: function () {
      bwg_change_watermark_container();
    }
  } );
  jQuery( ".bwg_popup_embed" ).animate( {
    width: jQuery( "#spider_popup_wrap" ).width() - ( bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0 )
  }, {
    duration: 100,
    complete: function () {
      bwg_resize_instagram_post();
      bwg_change_watermark_container();
    }
  } );
  if ( bwg_param['width_or_height'] == 'width' ) {
    jQuery( ".bwg_filmstrip_container" ).animate( { width: jQuery( ".spider_popup_wrap" ).width() }, 100 );
    jQuery( ".bwg_filmstrip" ).animate( { width: jQuery( ".spider_popup_wrap" ).width() - 40 }, 100 );
  } else if ( bwg_param['width_or_height'] == 'height' ) {
    jQuery( ".bwg_filmstrip_container" ).animate( { height: jQuery( ".spider_popup_wrap" ).width() }, 100 );
    jQuery( ".bwg_filmstrip" ).animate( { height: jQuery( ".spider_popup_wrap" ).width() - 40 }, 100 );
  }
  /* Set filmstrip initial position.*/
  bwg_set_filmstrip_pos( jQuery( ".spider_popup_wrap" ).width() - 40 );
  jQuery( ".spider_popup_close_fullscreen" ).show( 100 );
}

function bwg_animate_image_box_for_show_sidebar() {
  var bwg_comment_container = jQuery( ".bwg_comment_container" ).width() || jQuery( ".bwg_ecommerce_container" ).width();
  if ( lightbox_comment_pos == 'left' ) {
    jQuery( ".bwg_image_wrap" ).animate( {
      left: bwg_comment_container,
      width: jQuery( "#spider_popup_wrap" ).width() - bwg_comment_container
    }, 100 );
  } else if ( lightbox_comment_pos == 'right' ) {
    jQuery( ".bwg_image_wrap" ).animate( {
      right: bwg_comment_container,
      width: jQuery( "#spider_popup_wrap" ).width() - bwg_comment_container
    }, 100 );
  }
  jQuery( ".bwg_image_container" ).animate( {
    width: jQuery( "#spider_popup_wrap" ).width() - ( bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0 ) - bwg_comment_container
  }, 100 );
  jQuery( ".bwg_popup_image" ).animate( {
    maxWidth: jQuery( "#spider_popup_wrap" ).width() - bwg_comment_container - ( bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0 )
  }, {
    duration: 100,
    complete: function () {
      bwg_change_watermark_container();
    }
  } );
  jQuery( ".bwg_popup_embed > .bwg_embed_frame > img, .bwg_popup_embed > .bwg_embed_frame > video" ).animate( {
    maxWidth: jQuery( "#spider_popup_wrap" ).width() - bwg_comment_container - ( bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0 )
  }, {
    duration: 100,
    complete: function () {
      bwg_resize_instagram_post();
      bwg_change_watermark_container();
    }
  } );
  if ( bwg_param['width_or_height'] == 'width' ) {
    jQuery( ".bwg_filmstrip_container" ).css( { width: jQuery( "#spider_popup_wrap" ).width() - ( bwg_param['filmstrip_direction'] == 'vertical' ? 0 : bwg_comment_container ) } );
    jQuery( ".bwg_filmstrip" ).animate( { width: jQuery( ".bwg_filmstrip_container" ).width() - 40 }, 100 );
    /* Set filmstrip initial position.*/
    bwg_set_filmstrip_pos( jQuery( ".bwg_filmstrip_container" ).width() - 40 );
  }
}

/* Open/close comments.*/
function bwg_comment() {
    jQuery(".bwg_watermark").css({display: 'none'});
    jQuery(".bwg_ecommerce_wrap").css("z-index","-1");
    jQuery(".bwg_comment_wrap").css("z-index","25");
    if(jQuery(".bwg_ecommerce_container").hasClass("bwg_open") ){
        bwg_popup_sidebar_close(jQuery(".bwg_ecommerce_container"));
        jQuery(".bwg_ecommerce_container").attr("class", "bwg_ecommerce_container bwg_close");
        jQuery(".bwg_ecommerce").attr("title", bwg_objectsL10n.bwg_show_ecommerce);

    }
    if (jQuery(".bwg_comment_container").hasClass("bwg_open") ) {
        /* Close comment.*/
        bwg_popup_sidebar_close(jQuery(".bwg_comment_container"));
        bwg_animate_image_box_for_hide_sidebar();
        jQuery(".bwg_comment_wrap").css("z-index","-1");
        jQuery(".bwg_comment_container").attr("class", "bwg_comment_container bwg_close");
        jQuery(".bwg_comment").attr("title", bwg_objectsL10n.bwg_show_comments);
        jQuery(".spider_popup_close_fullscreen").show();
    }
    else {
        /* Open comment.*/
        bwg_popup_sidebar_open(jQuery(".bwg_comment_container"));
        bwg_animate_image_box_for_show_sidebar();
        jQuery(".bwg_comment_container").attr("class", "bwg_comment_container bwg_open");
        jQuery(".bwg_comment").attr("title", bwg_objectsL10n.bwg_hide_comments);
        /* Load comments.*/
        var cur_image_key = parseInt(jQuery("#bwg_current_image_key").val());
        if (data[cur_image_key]["comment_count"] != 0) {
            jQuery("#bwg_added_comments").show();
            spider_set_input_value('ajax_task', 'display');
            spider_set_input_value('image_id', jQuery('#bwg_popup_image').attr('image_id'));
            spider_ajax_save('bwg_comment_form');
        }
    }
}

/* Open/close ecommerce.*/
function bwg_ecommerce() {
    jQuery(".bwg_watermark").css({display: 'none'});
    jQuery(".bwg_ecommerce_wrap").css("z-index","25");
    jQuery(".bwg_comment_wrap").css("z-index","-1");
    if (jQuery(".bwg_comment_container").hasClass("bwg_open")) {
        bwg_popup_sidebar_close(jQuery(".bwg_comment_container"));
        jQuery(".bwg_comment_container").attr("class", "bwg_comment_container bwg_close");
        // Must be translatable
        jQuery(".bwg_comment").attr("title", bwg_objectsL10n.bwg_how_comments);
    }
    if (jQuery(".bwg_ecommerce_container").hasClass("bwg_open")) {
        /* Close ecommerce.*/
        bwg_popup_sidebar_close(jQuery(".bwg_ecommerce_container"));
        bwg_animate_image_box_for_hide_sidebar();
        jQuery(".bwg_ecommerce_container").attr("class", "bwg_ecommerce_container bwg_close");
        // Must be translatable
        jQuery(".bwg_ecommerce").attr("title", bwg_objectsL10n.bwg_show_ecommerce);
        // jQuery(".spider_popup_close_fullscreen").show();
    }
    else {
        /* Open ecommerce.*/
        bwg_popup_sidebar_open(jQuery(".bwg_ecommerce_container"));
        bwg_animate_image_box_for_show_sidebar();
        jQuery(".bwg_ecommerce_container").attr("class", "bwg_ecommerce_container bwg_open");
        jQuery(".bwg_ecommerce").attr("title", bwg_objectsL10n.bwg_hide_ecommerce);
        get_ajax_pricelist();
    }
}

function bwg_reset_zoom() {
    var isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
    var viewportmeta = document.querySelector('meta[name="viewport"]');
    if (isMobile) {
        if (viewportmeta) {
            viewportmeta.content = 'width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0';
        }
    }
}

function bwg_move_filmstrip() {
    var image_right = (bwg_param['left_or_top'] == 'left' ? jQuery(".bwg_thumb_active").position().left : jQuery(".bwg_thumb_active").position().top) + (bwg_param['outerWidth_or_outerHeight'] == 'outerWidth' ? jQuery(".bwg_thumb_active").outerWidth(true) : jQuery(".bwg_thumb_active").outerHeight(true));
    var bwg_filmstrip_width = (bwg_param['outerWidth_or_outerHeight'] == 'outerWidth' ? jQuery(".bwg_filmstrip").outerWidth(true) : jQuery(".bwg_filmstrip").outerHeight(true));
    var bwg_filmstrip_thumbnails_width = (bwg_param['outerWidth_or_outerHeight'] == 'outerWidth' ? jQuery(".bwg_filmstrip_thumbnails").outerWidth(true) : jQuery(".bwg_filmstrip_thumbnails").outerHeight(true));

    if ( bwg_param['left_or_top'] == 'left' ) {
        var image_left = jQuery(".bwg_thumb_active").position().left;
        var long_filmstrip_cont_left = jQuery(".bwg_filmstrip_thumbnails").position().left;
        var long_filmstrip_cont_right = Math.abs(jQuery(".bwg_filmstrip_thumbnails").position().left) + bwg_filmstrip_width;
    } else if ( bwg_param['left_or_top'] == 'top' ) {
        var image_left = jQuery(".bwg_thumb_active").position().top;
        var long_filmstrip_cont_left = jQuery(".bwg_filmstrip_thumbnails").position().top;
        var long_filmstrip_cont_right = Math.abs(jQuery(".bwg_filmstrip_thumbnails").position().top) + bwg_filmstrip_width;
    }
    if (bwg_filmstrip_width > bwg_filmstrip_thumbnails_width) {
        return;
    }

    if (image_left < Math.abs(long_filmstrip_cont_left)) {
        if ( bwg_param['left_or_top'] == 'left' ) {
            jQuery(".bwg_filmstrip_thumbnails").animate({
                left: -image_left
            }, {
                duration: 500,
                complete: function () {
                    bwg_filmstrip_arrows();
                }
            });
        } else {
            jQuery(".bwg_filmstrip_thumbnails").animate({
                top: -image_left
            }, {
                duration: 500,
                complete: function () {
                    bwg_filmstrip_arrows();
                }
            });
        }
    }
    else if (image_right > long_filmstrip_cont_right) {
        if ( bwg_param['left_or_top'] == 'left' ) {
            jQuery(".bwg_filmstrip_thumbnails").animate({
                left: -(image_right - bwg_filmstrip_width)
            }, {
                duration: 500,
                complete: function () {
                    bwg_filmstrip_arrows();
                }
            });
        } else {
            jQuery(".bwg_filmstrip_thumbnails").animate({
                top: -(image_right - bwg_filmstrip_width)
            }, {
                duration: 500,
                complete: function () {
                    bwg_filmstrip_arrows();
                }
            });
        }
    }
}

/* Show/hide filmstrip arrows.*/
function bwg_filmstrip_arrows() {
    if ( bwg_param['width_or_height'] == 'width' ){
        var condition1 = jQuery(".bwg_filmstrip_thumbnails").width();
        var condition2 = jQuery(".bwg_filmstrip").width()
    } else {
        var condition1 = jQuery(".bwg_filmstrip_thumbnails").height();
        var condition2 = jQuery(".bwg_filmstrip").height()
    }
    if (condition1 < condition2) {
        jQuery(".bwg_filmstrip_left").hide();
        jQuery(".bwg_filmstrip_right").hide();
    } else {
        jQuery(".bwg_filmstrip_left").show();
        jQuery(".bwg_filmstrip_right").show();
    }
}

/* Open with fullscreen.*/
function bwg_open_with_fullscreen() {
    jQuery(".bwg_watermark").css({display: 'none'});
    var comment_container_width = 0;
    if (jQuery(".bwg_comment_container").hasClass("bwg_open") || jQuery(".bwg_ecommerce_container").hasClass("bwg_open")) {
        comment_container_width = jQuery(".bwg_comment_container").width() || jQuery(".bwg_ecommerce_container").width();
    }
    bwg_popup_current_width = jQuery(window).width();
    bwg_popup_current_height = window.innerHeight;
    jQuery("#spider_popup_wrap").css({
        width: jQuery(window).width(),
        height: window.innerHeight,
        left: 0,
        top: 0,
        margin: 0,
        zIndex: 100002
    });
    jQuery(".bwg_image_wrap").css({width: (jQuery(window).width() - comment_container_width)});
    jQuery(".bwg_image_container").css({height: (bwg_popup_current_height - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0)), width: bwg_popup_current_width - comment_container_width - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0)});
    jQuery(".bwg_popup_image").css({
        maxWidth: jQuery(window).width() - comment_container_width - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0),
        maxHeight: window.innerHeight - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0)
    },  {
        complete: function () { bwg_change_watermark_container(); }
    });
    jQuery(".bwg_popup_video").css({
        width: jQuery(window).width() - comment_container_width - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0),
        height: window.innerHeight - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0)
    },  {
        complete: function () { bwg_change_watermark_container(); }
    });
    jQuery(".bwg_popup_embed > .bwg_embed_frame > img, .bwg_popup_embed > .bwg_embed_frame > video").css({
        maxWidth: jQuery(window).width() - comment_container_width - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0),
        maxHeight: window.innerHeight - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0)
    },  {
        complete: function () {
            bwg_resize_instagram_post();
            bwg_change_watermark_container(); }
    });
    if ( bwg_param['width_or_height'] == 'width' ) {
        jQuery(".bwg_filmstrip_container").css({width: jQuery(window).width() - (bwg_param['filmstrip_direction'] == 'horizontal' ? 'comment_container_width' : 0)});
        jQuery(".bwg_filmstrip").css({width: jQuery(window).width() - (bwg_param['filmstrip_direction'] == 'horizontal' ? 'comment_container_width' : 0) - 40});
        /* Set filmstrip initial position.*/
        bwg_set_filmstrip_pos(jQuery(window).width() - (bwg_param['filmstrip_direction'] == 'horizontal' ? 'comment_container_width' : 0) - 40);
    } else {
        jQuery(".bwg_filmstrip_container").css({height: window.innerHeight - (bwg_param['filmstrip_direction'] == 'horizontal' ? 'comment_container_width' : 0)});
        jQuery(".bwg_filmstrip").css({height: window.innerHeight - (bwg_param['filmstrip_direction'] == 'horizontal' ? 'comment_container_width' : 0) - 40});
        /* Set filmstrip initial position.*/
        bwg_set_filmstrip_pos(window.innerHeight - (bwg_param['filmstrip_direction'] == 'horizontal' ? 'comment_container_width' : 0) - 40);
    }
    jQuery(".bwg_resize-full").attr("class", "bwg_ctrl_btn bwg_resize-full fa fa-resize-small");
    jQuery(".bwg_resize-full").attr("title", bwg_objectsL10n.bwg_restore);
    jQuery(".spider_popup_close").attr("class", "bwg_ctrl_btn spider_popup_close_fullscreen");
}

function bwg_resize_instagram_post() {
    if (jQuery('.inner_instagram_iframe_bwg_embed_frame').length) {
        var post_width = jQuery(".bwg_image_container").width();
        var post_height = jQuery(".bwg_image_container").height();
        var FeedbackSocialProofHeight = 132;
        jQuery('.inner_instagram_iframe_bwg_embed_frame').each(function() {
            var parent_container = jQuery(this).parent();
            if (post_height / (parseInt(parent_container.attr('data-height')) + FeedbackSocialProofHeight) < post_width / parseInt(parent_container.attr('data-width'))) {
                parent_container.height(post_height);
                parent_container.width((parent_container.height() - FeedbackSocialProofHeight) * parent_container.attr('data-width') / parent_container.attr('data-height') + 16);
            }
            else {
                parent_container.width(post_width);
                parent_container.height((parent_container.width() - 16) * parent_container.attr('data-height') / parent_container.attr('data-width') + 96);
            }
            parent_container.css({top: 0.5 * (post_height - parent_container.height())});
        });
        bwg_change_watermark_container();
    }
}

function bwg_play() {
    window.clearInterval(bwg_playInterval);
    bwg_playInterval = setInterval(function () {
        if (!data[parseInt(jQuery('#bwg_current_image_key').val()) + 1]) {
            if (bwg_param['enable_loop'] == 1) {
                /* Wrap around.*/
                bwg_change_image(parseInt(jQuery('#bwg_current_image_key').val()), 0, data);
            }
            return;
        }
        bwg_change_image(parseInt(jQuery('#bwg_current_image_key').val()), parseInt(jQuery('#bwg_current_image_key').val()) + 1, data)
    }, bwg_param['slideshow_interval'] * 1000);
}

function bwg_resize_full() {
    jQuery(".bwg_watermark").css({display: 'none'});
    var comment_container_width = 0;
    if (jQuery(".bwg_comment_container").hasClass("bwg_open") || jQuery(".bwg_ecommerce_container").hasClass("bwg_open") ) {
        comment_container_width = jQuery(".bwg_comment_container").width() || jQuery(".bwg_ecommerce_container").width();
    }
    // resize to small from full
    if (jQuery(".bwg_resize-full").hasClass("fa-resize-small")) {
        if (jQuery(window).width() > bwg_param['image_width']) {
            bwg_popup_current_width = bwg_param['image_width'];
        }
        if (window.innerHeight > bwg_param['image_height']) {
            bwg_popup_current_height = bwg_param['image_height'];
        }
        /* Minimize.*/
        jQuery("#spider_popup_wrap").animate({
            width: bwg_popup_current_width,
            height: bwg_popup_current_height,
            left: '50%',
            top: '50%',
            marginLeft: -bwg_popup_current_width / 2,
            marginTop: -bwg_popup_current_height / 2,
            zIndex: 100002
        }, 500);
        jQuery(".bwg_image_wrap").animate({width: bwg_popup_current_width - comment_container_width}, 500);
        jQuery(".bwg_image_container").animate({height: bwg_popup_current_height - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0), width: bwg_popup_current_width - comment_container_width - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0)}, 500);
        jQuery(".bwg_popup_image").animate({
            maxWidth: bwg_popup_current_width - comment_container_width - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0),
        maxHeight: bwg_popup_current_height - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0)
        }, {
            duration: 500,
                complete: function () {
                bwg_change_watermark_container();
                if ((jQuery("#spider_popup_wrap").width() < jQuery(window).width())) {
                    if (jQuery("#spider_popup_wrap").height() < window.innerHeight) {
                        jQuery(".spider_popup_close_fullscreen").attr("class", "spider_popup_close");
                    }
                }
            }
        });
        jQuery(".bwg_popup_embed > .bwg_embed_frame > img, .bwg_popup_embed > .bwg_embed_frame > video").animate({
            maxWidth: bwg_popup_current_width - comment_container_width - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0),
            maxHeight: bwg_popup_current_height - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0)
        }, {
            duration: 500,
                complete: function () {
                bwg_resize_instagram_post();
                bwg_change_watermark_container();
                if (jQuery("#spider_popup_wrap").width() < jQuery(window).width()) {
                    if (jQuery("#spider_popup_wrap").height() < window.innerHeight) {
                        jQuery(".spider_popup_close_fullscreen").attr("class", "spider_popup_close");
                    }
                }
            }
        });
        if ( bwg_param['width_or_height'] == 'width' ) {
            jQuery(".bwg_filmstrip_container").animate({width: bwg_popup_current_width - (bwg_param['filmstrip_direction'] == 'horizontal' ? comment_container_width : 0)}, 500);
            jQuery(".bwg_filmstrip").animate({width: bwg_popup_current_width - (bwg_param['filmstrip_direction'] == 'horizontal' ? comment_container_width : 0) - 40}, 500);
            /* Set filmstrip initial position.*/
            bwg_set_filmstrip_pos(bwg_popup_current_width - 40);
        } else {
            jQuery(".bwg_filmstrip_container").animate({height: bwg_popup_current_height - (bwg_param['filmstrip_direction'] == 'horizontal' ? comment_container_width : 0)}, 500);
            jQuery(".bwg_filmstrip").animate({height: bwg_popup_current_height - (bwg_param['filmstrip_direction'] == 'horizontal' ? comment_container_width : 0) - 40}, 500);
            /* Set filmstrip initial position.*/
            bwg_set_filmstrip_pos(bwg_popup_current_height - 40);
        }
        jQuery(".bwg_resize-full").attr("class", "bwg_ctrl_btn bwg_resize-full fa fa-resize-full");
        jQuery(".bwg_resize-full").attr("title", "<?php echo __('Maximize', BWG()->prefix); ?>");

    }
    else { // resize to full from small
        bwg_popup_current_width = jQuery(window).width();
        bwg_popup_current_height = window.innerHeight;
        /* Maximize.*/
        jQuery("#spider_popup_wrap").animate({
            width: jQuery(window).width(),
            height: window.innerHeight,
            left: 0,
            top: 0,
            margin: 0,
            zIndex: 100002
        }, 500);
        jQuery(".bwg_image_wrap").animate({width: (jQuery(window).width() - comment_container_width)}, 500);
        jQuery(".bwg_image_container").animate({height: (bwg_popup_current_height - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0)), width: bwg_popup_current_width - comment_container_width - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0)}, 500);
        jQuery(".bwg_popup_image").animate({
            maxWidth: jQuery(window).width() - comment_container_width - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0),
            maxHeight: window.innerHeight - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0)
        }, {
            duration: 500,
                complete: function () { bwg_change_watermark_container(); }
        });
        jQuery(".bwg_popup_embed > .bwg_embed_frame > img, .bwg_popup_embed > .bwg_embed_frame > video").animate({
            maxWidth: jQuery(window).width() - comment_container_width - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0),
            maxHeight: window.innerHeight - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0)
        }, {
            duration: 500,
                complete: function () {
                bwg_resize_instagram_post();
                bwg_change_watermark_container(); }
        });
        if ( bwg_param['width_or_height'] == 'width' ) {
          jQuery(".bwg_filmstrip_container").animate({width: jQuery(window).width() - (bwg_param['filmstrip_direction'] == 'horizontal' ? comment_container_width : 0)}, 500);
            jQuery(".bwg_filmstrip").animate({width: jQuery(window).width() - (bwg_param['filmstrip_direction'] == 'horizontal' ? comment_container_width : 0) - 40}, 500);
            /* Set filmstrip initial position.*/
            bwg_set_filmstrip_pos(jQuery(window).width() - (bwg_param['filmstrip_direction'] == 'horizontal' ? comment_container_width : 0) - 40);
        } else {
            jQuery(".bwg_filmstrip_container").animate({height: window.innerHeight - (bwg_param['filmstrip_direction'] == 'horizontal' ? comment_container_width : 0)}, 500);
            jQuery(".bwg_filmstrip").animate({height: window.innerHeight - (bwg_param['filmstrip_direction'] == 'horizontal' ? comment_container_width : 0) - 40}, 500);
            /* Set filmstrip initial position.*/
            bwg_set_filmstrip_pos(window.innerHeight - (bwg_param['filmstrip_direction'] == 'horizontal' ? comment_container_width : 0) - 40);
        }
        jQuery(".bwg_resize-full").attr("class", "bwg_ctrl_btn bwg_resize-full fa fa-resize-small");
        jQuery(".bwg_resize-full").attr("title", "<?php echo __('Restore', BWG()->prefix); ?>");
        jQuery(".spider_popup_close").attr("class", "bwg_ctrl_btn spider_popup_close_fullscreen");
    }

}

function bwg_popup_resize() {
    if (typeof jQuery().fullscreen !== 'undefined') {
        if (jQuery.isFunction(jQuery().fullscreen)) {
            if (!jQuery.fullscreen.isFullScreen()) {
                jQuery(".bwg_resize-full").show();
                if(!jQuery('.bwg_resize-full').hasClass('fa-resize-small')) {
                  jQuery(".bwg_resize-full").attr("class", "bwg_ctrl_btn bwg_resize-full fa fa-resize-full");
                }
                jQuery(".bwg_resize-full").attr("title", bwg_objectsL10n.bwg_maximize);
                jQuery(".bwg_fullscreen").attr("class", "bwg_ctrl_btn bwg_fullscreen fa fa-fullscreen");
                jQuery(".bwg_fullscreen").attr("title", bwg_objectsL10n.fulscreen);
            }
        }
    }
    var comment_container_width = 0;
    if (jQuery(".bwg_comment_container").hasClass("bwg_open") || jQuery(".bwg_ecommerce_container").hasClass("bwg_open")) {
        comment_container_width = bwg_param['lightbox_comment_width'];
    }
    if (comment_container_width > jQuery(window).width()) {
        comment_container_width = jQuery(window).width();
        jQuery(".bwg_comment_container").css({
            width: comment_container_width
        });
        jQuery(".bwg_ecommerce_container").css({
            width: comment_container_width
        });
        jQuery(".spider_popup_close_fullscreen").hide();
    }
    else {
        jQuery(".spider_popup_close_fullscreen").show();
    }
    if (!(!(window.innerHeight > bwg_param['image_height']) || !(bwg_param['open_with_fullscreen'] != 1)) && !jQuery('.bwg_resize-full').hasClass('fa-resize-small')) {
      jQuery("#spider_popup_wrap").css({
            height: bwg_param['image_height'],
            top: '50%',
            marginTop: -bwg_param['image_height'] / 2,
            zIndex: 100002
        });
        jQuery(".bwg_image_container").css({height: (bwg_param['image_height'] - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0))});
        jQuery(".bwg_popup_image").css({
            maxHeight: bwg_param['image_height'] - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0)
        });
        jQuery(".bwg_popup_embed > .bwg_embed_frame > img, .bwg_popup_embed > .bwg_embed_frame > video").css({
            maxHeight: bwg_param['image_height'] - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0)
        });
        if (bwg_param['filmstrip_direction'] == 'vertical') {
            jQuery(".bwg_filmstrip_container").css({height: bwg_param['image_height']});
            jQuery(".bwg_filmstrip").css({height: (bwg_param['image_height'] - 40)})
        }
        bwg_popup_current_height = bwg_param['image_height'];
    }
    else {
        jQuery("#spider_popup_wrap").css({
            height: window.innerHeight,
            top: 0,
            marginTop: 0,
            zIndex: 100002
        });

        jQuery(".bwg_image_container").css({height: (window.innerHeight - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0))});
        jQuery(".bwg_popup_image").css({
            maxHeight: window.innerHeight - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0)
        });
        jQuery(".bwg_popup_embed > .bwg_embed_frame > img, .bwg_popup_embed > .bwg_embed_frame > video").css({
            maxHeight: window.innerHeight - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0)
        });
        if (bwg_param['filmstrip_direction'] == 'vertical') {
            jQuery(".bwg_filmstrip_container").css({height: (window.innerHeight)});
            jQuery(".bwg_filmstrip").css({height: (window.innerHeight - 40)});
        }
        bwg_popup_current_height = window.innerHeight;
    }
    if (!(!(jQuery(window).width() >= bwg_param['image_width']) || !(bwg_param['open_with_fullscreen'] != 1))) {
        jQuery("#spider_popup_wrap").css({
            width: bwg_param['image_width'],
            left: '50%',
            marginLeft: -bwg_param['image_width'] / 2,
            zIndex: 100002
        });
        jQuery(".bwg_image_wrap").css({width: bwg_param['image_width'] - comment_container_width});
        jQuery(".bwg_image_container").css({width: (bwg_param['image_width'] - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0) - comment_container_width)});
        jQuery(".bwg_popup_image").css({
            maxWidth: bwg_param['image_width'] - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0) - comment_container_width
        });
        jQuery(".bwg_popup_embed > .bwg_embed_frame > img, .bwg_popup_embed > .bwg_embed_frame > video").css({
            maxWidth: bwg_param['image_width'] - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0) - comment_container_width
        });
        if (bwg_param['filmstrip_direction'] == 'horizontal') {
            jQuery(".bwg_filmstrip_container").css({width: bwg_param['image_width'] - comment_container_width});
            jQuery(".bwg_filmstrip").css({width: (bwg_param['image_width']  - comment_container_width- 40)});
        }
        bwg_popup_current_width = bwg_param['image_width'];
    }
    else {
        jQuery("#spider_popup_wrap").css({
            width: jQuery(window).width(),
            left: 0,
            marginLeft: 0,
            zIndex: 100002
        });
        jQuery(".bwg_image_wrap").css({width: (jQuery(window).width() - comment_container_width)});
        jQuery(".bwg_image_container").css({width: (jQuery(window).width() - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0) - comment_container_width)});
        jQuery(".bwg_popup_image").css({
            maxWidth: jQuery(window).width() - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0) - comment_container_width
        });
        jQuery(".bwg_popup_embed > .bwg_embed_frame > img, .bwg_popup_embed > .bwg_embed_frame > video").css({
            maxWidth: jQuery(window).width() - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0) - comment_container_width
        });
        if (bwg_param['filmstrip_direction'] == 'horizontal') {
            jQuery(".bwg_filmstrip_container").css({width: (jQuery(window).width() - comment_container_width)});
            jQuery(".bwg_filmstrip").css({width: (jQuery(window).width() - comment_container_width - 40)});
        }
        bwg_popup_current_width = jQuery(window).width();
    }
    /* Set watermark container size.*/
    bwg_resize_instagram_post();
    bwg_change_watermark_container();
    if (!(!(window.innerHeight > bwg_param['image_height'] - 2 * bwg_param['lightbox_close_btn_top']) || !(jQuery(window).width() >= bwg_param['image_width'] - 2 * bwg_param['lightbox_close_btn_right']) || !(bwg_param['open_with_fullscreen'] != 1))) {
        jQuery(".spider_popup_close_fullscreen").attr("class", "spider_popup_close");
    }
    else {
        if (!(!(jQuery("#spider_popup_wrap").width() < jQuery(window).width()) || !(jQuery("#spider_popup_wrap").height() < jQuery(window).height()))) {
            jQuery(".spider_popup_close").attr("class", "bwg_ctrl_btn spider_popup_close_fullscreen");
        }
    }
    if ( bwg_param['lightbox_ctrl_btn_pos'] == 'bottom' ) {
        if (jQuery(".bwg_toggle_container i").hasClass('fa-angle-down')) {
            jQuery(".bwg_toggle_container").css("bottom", jQuery(".bwg_ctrl_btn_container").height() + "px");
        }
    }
    if ( bwg_param['lightbox_ctrl_btn_pos'] == 'top') {
        if (jQuery(".bwg_toggle_container i").hasClass('fa-angle-up')) {
            jQuery(".bwg_toggle_container").css("top", jQuery(".bwg_ctrl_btn_container").height() + "px");
        }
    }
}