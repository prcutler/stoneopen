/**
 * AIT WordPress Framework
 *
 * Copyright (c) 2011, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */

jQuery(function(){

	// Colorpickers
	jQuery.fn.cp = function(){
		return this.each(function(){
			var $input = jQuery(this),
				myColor = $input.val();

			$input.css({'border-left-width': '15px'});
			$input.css({'border-left-color': myColor});

			$input.ColorPicker({
				color: myColor,
				onSubmit: function(hsb, hex, rgb, el) {
					jQuery(el).val( '#' + hex);
					jQuery(el).ColorPickerHide();
				},
				onBeforeShow: function () {
					jQuery(this).ColorPickerSetColor(this.value);
					$input.css({'border-left-color': this.value});
				},
				onChange: function (hsb, hex, rgb){
					$input.val('#' + hex);
					$input.css({'border-left-color': '#' + hex});
				}
			}).bind('keyup', function(){
				jQuery(this).ColorPickerSetColor('#' + this.value);
			});
		});
	}

	jQuery('.ait-colorpicker').cp();



	// Help tooltip
	jQuery('.ait-form-table-help-label').hover(function(){
			jQuery(this).find('.ait-form-table-help-tooltip').stop(true).fadeIn(150);
		},
		function(){
			jQuery(this).find('.ait-form-table-help-tooltip').fadeOut(150);
	}).click(function(e){e.preventDefault();});


	// Forum iframe height
	jQuery('#ait-dashboard-page').find('#ait-support-forum').height(jQuery('body').height() - 175);

	jQuery(window).resize(function() {
		jQuery('#ait-dashboard-page').find('#ait-support-forum').height(jQuery('body').height() - 175);
	});



	// Media select button
	var mediaUpload = '';

	var $mediaSelect = jQuery('input[type="button"].media-select');

	if($mediaSelect.length){

		var formfield=null;
		$mediaSelect.click(function(){
			var buttonID = jQuery(this).attr("id").toString();
			var inputID = buttonID.replace("_selectMedia", "");
			mediaUpload = inputID;
			formfield = inputID;
			tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
			jQuery('#TB_overlay,#TB_closeWindowButton').bind("click",function(){formfield=null;});
			return false;
		});

		formfield=null;
		window.original_send_to_editor = window.send_to_editor;
		window.send_to_editor = function(html) {
			if (formfield) {
				var imgUrl = jQuery('img', html).attr('src');
				jQuery('#'+mediaUpload).val(imgUrl);
				tb_remove();
			} else {
				window.original_send_to_editor(html);
			}
			formfield=null;
		};
	}

	jQuery("#ait-theme-doc-single a[href$='gif']").colorbox({maxHeight:"95%"});
	jQuery("#ait-theme-doc-single a[href$='jpg']").colorbox({maxHeight:"95%"});
	jQuery("#ait-theme-doc-single a[href$='png']").colorbox({maxHeight:"95%"});



	// Ajax functionality for marking one news as read
	jQuery(".ait-news-permalink").click(function(){
		var $this = jQuery(this);
		var id = $this.data('ait-news-id');
		var $div = jQuery('#ait-news-' + id);
		var $countsOnBar = jQuery('.ait-ab-updates');
		var $countsOnBarLabel = $countsOnBar.find('.ab-label');
		var $countsInMenuLabel = jQuery('#toplevel_page_ait-admin').find('.update-count');
		var number = parseInt($countsOnBarLabel.text(), 10);

		if($div.hasClass('ait-new-news')){
			--number;
			$div.removeClass('ait-new-news');
		}

		if(number === 0){
			$countsOnBar.remove();
			$countsInMenuLabel.remove();
		}else{
			$countsOnBarLabel.text(number);
			$countsInMenuLabel.text(number);
		}

		jQuery.post(ajaxurl, {action: 'markNewsAsRead', "id": id});
	});



	// Ajax functionality for marking all news as read
	jQuery(".ait-mark-all-as-read").click(function(){
		var $this = jQuery(this);
		var $divs = jQuery('.ait-new-news');
		var $countsOnBar = jQuery('.ait-ab-updates');
		var $countsOnBarLabel = $countsOnBar.find('.ab-label');
		var $countsInMenuLabel = jQuery('#toplevel_page_ait-admin').find('.update-count');
		var number = parseInt($countsOnBarLabel.text(), 10);

		$divs.removeClass('ait-new-news');
		number = number - $divs.length;

		if(number <= 0 || !$divs.length){
			$countsOnBar.remove();
			$countsInMenuLabel.remove();
			$this.remove();
		}else{
			$countsOnBarLabel.text(number);
			$countsInMenuLabel.text(number);
		}

		jQuery.post(ajaxurl, {action: 'markAllNewsAsRead'});

		return false;
	});



	// Ajax functionality for disabling theme update notification
	var $divs = jQuery('#ait-ait-theme-updates').find('.ait-version');

	var $updateMsg = jQuery('<span>', {
		"class": "ait-disable-msg",
		"text": " Setting saved."
	}).css({'display': 'none', 'color': 'green', 'padding-left': '10px'});

	var $label = jQuery("#disableUpdatesNotifications").siblings();
	$label.append($updateMsg);

	jQuery("#disableUpdatesNotifications").change(function(){
		var $this = jQuery(this);
		var checked = $this.is(":checked") ? 1 : 0;
		var $countsOnBar = jQuery('.ait-ab-updates');
		var $countsOnBarLabel = $countsOnBar.find('.ab-label');
		var $countsInMenuLabel = jQuery('#toplevel_page_ait-admin').find('.update-count');
		var number = parseInt($countsOnBarLabel.text(), 10);
		var isUpdateAvailable = $this.data('ait-is-update-available');

		var $div = $divs.filter('.ait-new-version');

		if(checked && $div.length){
			$div.removeClass('ait-new-version');
			--number;
		}else{
			if(isUpdateAvailable){
				$div = $divs.first();
				$div.addClass('ait-new-version');
				++number;
			}
		}

		if(number === 0){
			$countsOnBar.css('display', 'none');
			$countsInMenuLabel.css('display', 'none');
		}else{
			$countsOnBar.css('display', 'list-item');
			$countsInMenuLabel.css('display', 'block');
		}

		$countsOnBarLabel.text(number);
		$countsInMenuLabel.text(number);

		jQuery.post(ajaxurl, {action: "disableThemeUpdates", disabled: checked}, function(response){
			$updateMsg.fadeIn();
			setTimeout(function(){
				$updateMsg.fadeOut('fast');
			}, 1500);
		});
	});
});