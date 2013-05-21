/**
 * AIT WordPress Framework
 *
 * Copyright (c) 2011, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */

jQuery(function(){

	initThemeBox();

	setBackgrounds();

	initColorPickers();

	setFonts();

	saveOptions();

	resetThemeBox();

	buyAitThemes();
});



var selectorCache = [];



function $s(property, option)
{
	var key = property + option;

	if(selectorCache[key] == undefined){
		selectorCache[key] = jQuery(aitThemeBoxSelectors[property][option]);
		return selectorCache[key];
	}else{
		return selectorCache[key];
	}
}



function isThere(property, option)
{
	return (aitThemeBoxSelectors[property] != undefined && aitThemeBoxSelectors[property][option] != undefined);
}



function initThemeBox()
{
	var $themeBox = jQuery('#ait-themebox');
	var $toggler = jQuery('#ait-themebox-toggler');
	var left = -($themeBox.width() + 10) + 'px';
	var htmlHeight = jQuery(window).height();
	var top = 50;
	var themeBoxHeight;

	var $themesToggler = jQuery('#ait-themebox-themes-toggler').toggleClass('open');
	var $themes = jQuery('#ait-themebox-ait-themes');
	var $themesList = jQuery('#ait-themebox-themes-list');
	var themesListWidth = $themesList.width() - 20; // = width - padding
	var themesListHeight; // = width - padding
	$themesList.find('.preview').css('left', themesListWidth + 20);

	if(Cookies.get('themeBoxOpened') == null){
		Cookies.set('themeBoxOpened', false);
	}

	if(Cookies.get('themeBoxOpened')){
		$themeBox.css({'left': 0});
		$toggler.removeClass('closed').addClass('open');
	}

	var $header = jQuery('#ait-themebox-options .ait-themebox-options-header').addClass('closed');

	jQuery('#ait-themebox-options .ait-themebox-options-content')
		.filter(function(index){
			var i;
			i = jQuery(this).find('input').length;

			if(i <= 8 && index == 0){
				jQuery(this).css('display', 'block').prev().toggleClass('open');
				return;
			}
		});

	themeBoxHeight = $themeBox.outerHeight() + top;

	if(themeBoxHeight > htmlHeight){
		$themeBox.css('position', 'absolute');
	}

	$header.click(function(){

		jQuery(this).toggleClass('open').next().slideToggle('fast', function(){
			themeBoxHeight = $themeBox.outerHeight()  + top;

			if(themeBoxHeight > htmlHeight){
				$themeBox.css('position', 'absolute');
			}else{
				$themeBox.css('position', 'fixed');
			}
		});

		return false;
	}).next();


    $toggler.click(function () {
        if(jQuery(this).hasClass('open')){
            $themeBox.animate({
                'left': left
            }, 300, function () {
                $toggler.removeClass('open').addClass('closed');
                Cookies.set('themeBoxOpened', false);
            });
        }

        if(jQuery(this).hasClass('closed')){
            $themeBox.animate({
                'left': '0'
            }, 300, function () {
                $toggler.removeClass('closed').addClass('open');
                Cookies.set('themeBoxOpened', true);
            });
        }
    });

	var themesOpen = function($this){
		$this.width(themesListWidth);
		$themesList.slideDown('fast', function(){
			$themesList.css('overflow', '');
			themeBoxHeight = $themeBox.outerHeight()  + top + $themesList.height();

			if(themeBoxHeight > htmlHeight){
				$themeBox.css('position', 'absolute');
			}else{
				$themeBox.css('position', 'fixed');
			}
		});
		$this.css({'box-shadow': '0 10px 10px rgba(0, 0, 0, 0.5)', 'border-radius': '3px 3px 0 0'});
	};

	var themesClose = function($this){
		$themesList.slideUp('fast', function(){
			$this.width('auto');
			$this.css({'box-shadow': 'none', 'border-radius': '3px'});
			themeBoxHeight = $themeBox.outerHeight()  + top

			if(themeBoxHeight > htmlHeight){
				$themeBox.css('position', 'absolute');
			}else{
				$themeBox.css('position', 'fixed');
			}
		});
	};

    $themesToggler.click(function(){
		var $this =  jQuery(this);

		if(!$this.hasClass('closed')){
			themesOpen($this);
		}else{
			themesClose($this);
		}
		$this.toggleClass('closed');

		return false;
    });

    $themes.mouseleave(function(){
		setTimeout(function(){
			var $this =  $themesToggler;

			if($this.hasClass('closed')){
				themesClose($this);
				$this.toggleClass('closed');
			}

		}, 750);
    });
}



function initColorPickers()
{
	jQuery.fn.cp = function(options){

		var switchColors = function(option, color){

			if(isThere('color', option)){
				$s('color', option).css('color', color);
				try{
					Cufon.refresh();
				}catch(e){}
			}

			if(isThere('background-color', option)){
				$s('background-color', option).css('background-color', color);
			}

			var sides = ['top', 'right', 'bottom', 'left'];
			var sideColor;
			for(var i = 0; i < sides.length; i++){
				if(isThere('border-' + sides[i], option)){
					sideColor = 'border-' + sides[i] + '-color';
					$s('border-' + sides[i], option).css(sideColor, color);
					break;
				}
				if(isThere('border-' + sides[i] + '-color', option)){
					sideColor = 'border-' + sides[i] + '-color';
					$s('border-' + sides[i] + '-color', option).css(sideColor, color);
					break;
				}
			}
		};

		return this.each(function(){
			var $input = jQuery(this);
			var myColor = $input.val();
			var inputData = $input.data('ait-themebox-option');

			if(inputData !== undefined){
				var optionKey = makeKey(inputData.section, inputData.option);


				if(options[optionKey] != undefined){
					myColor = options[optionKey];
					$input.val(myColor);
					switchColors(inputData.option, myColor);
				}

				$input.css({'border-left-width': '15px'});
				$input.css({'border-left-color': myColor});

				$input.ColorPicker({
					color: myColor,
					onSubmit: function(hsb, hex, rgb, el) {
						jQuery(el).val( '#' + hex);
						jQuery(el).ColorPickerHide();
						Cookies.set(optionKey, '#' + hex);

						switchColors(inputData.option, '#' + hex);
					},
					onBeforeShow: function () {
						jQuery(this).ColorPickerSetColor(this.value);
						$input.css({'border-left-color': this.value});
					},
					onChange: function (hsb, hex, rgb, alpha){
						if(alpha != 0){
              $input.val('#' + hex);
              // $input.val('rgba(' + rgb.r +','+rgb.g+','+rgb.b+ ','+parseFloat(alpha/100)+')');
              var style = 'rgba(' + rgb.r +','+rgb.g+','+rgb.b+','+parseFloat(alpha/100)+')';
						  $input.css({'border-left-color': style});
						  Cookies.set(optionKey, style);
              //Cookies.set(optionKey, 'rgba(' + rgb.r +','+rgb.g+','+rgb.b+','+parseFloat(alpha/100)+')');
              switchColors(inputData.option, style);
            } else {
              $input.val('#' + hex);
						  $input.css({'border-left-color': '#' + hex});
						  Cookies.set(optionKey, '#' + hex);
						  switchColors(inputData.option, '#' + hex);
						}

						//switchColors(inputData.option, '#' + hex);
					}
				}).bind('keyup', function(){
					jQuery(this).ColorPickerSetColor('#' + this.value);
				});
			}
		});
	}
	jQuery('.ait-themebox-colorpicker').cp(Cookies.load());
}



function setBackgrounds()
{

	var $groups = jQuery('#ait-themebox-image-url ul');
	var options = Cookies.load();

	var switchBg = function(option, img, color, repeat, x, y, attach){
		if(isThere('background-image', option)){
			var css = {};
			var $colorpickerInput = jQuery('.ait-themebox-colorpicker.option-' + option + 'Color');

			if($colorpickerInput.length){
				$colorpickerInput.val(color);
				$colorpickerInput.css('border-left-color', color);
			}


			$s('background-image', option).css('background-image', 'url(' + img + ')');

			if(color !== ''){
				css['background-color'] = color;
			}
			if(!repeat)
				repeat = 'repeat';
			if(!x && !y){
				x = 0;y = 0;
			}
			if(attach){
				attach = 'scroll';
			}

			css['background-repeat'] = repeat;
			css['background-position'] = x + ' ' + y;
			css['background-attachment'] = attach;

			$s('background-image', option).css(css);
		}
	};

	$groups.each(function(){
		var data = jQuery(this).data('ait-themebox-option');
		var optionKey = makeKey(data.section, data.option);
		var hiddenId = 'ait-themebox-' + data.section + '-' + data.option + '-';

		var $links = jQuery(this).find('a');
		var $hiddens = jQuery(this).find('input[type=hidden]');
		var $inputImg, $inputRepeat, $inputX, $inputY, $inputAttach;
		var img, color, repeat, x, y, attach;

		$hiddens.each(function(){
			var id = jQuery(this).attr('id');

			if(id == hiddenId + 'img'){
				$inputImg = jQuery(this);
				img = $inputImg.val();
			}
			if(id == hiddenId + 'repeat'){
				$inputRepeat = jQuery(this);
				repeat = $inputRepeat.val();
			}
			if(id == hiddenId + 'x'){
				$inputX = jQuery(this);
				x = $inputX.val();
			}
			if(id == hiddenId + 'y'){
				$inputY = jQuery(this);
				y = $inputY.val();
			}
			if(id == hiddenId + 'attach'){
				$inputAttach = jQuery(this);
				attach = $inputAttach.val();
			}
		});


		if(options[optionKey + 'Img'] != undefined){
			img = options[optionKey + 'Img'];
			$inputImg.val(img);

			repeat = options[optionKey + 'Repeat'];
			$inputRepeat.val(repeat);

			x = options[optionKey + 'X'];
			$inputX.val(x);

			y = options[optionKey + 'Y'];
			$inputY.val(y);

			attach = options[optionKey + 'Attach'];
			$inputAttach.val(attach);

			color = options[optionKey + 'Color'];

			switchBg(data.option, img, color, repeat, x, y, attach);
		}

		$links.each(function(){
			var $link = jQuery(this);

			$link.click(function(e){
				var d = $link.data('ait-themebox-img-data');
				$inputImg.val(d.img);
				options[optionKey + 'Img'] = d.img;

				$inputRepeat.val(d.repeat);
				options[optionKey + 'Repeat'] = d.repeat;

				$inputX.val(d.x);
				options[optionKey + 'X'] = d.x;

				$inputY.val(d.y);
				options[optionKey + 'Y'] = d.y

				$inputAttach.val(d.attach);
				options[optionKey + 'Attach'] = d.attach;

				options[optionKey + 'Color'] = d.color;

				Cookies.save(options);
				switchBg(data.option, d.img, d.color, d.repeat, d.x, d.y, d.attach);

				e.preventDefault();
			});
		});
	});
}



function setFonts()
{

	var $combos = jQuery('#ait-themebox-font select');
	var $cufonScript = jQuery('#ait-cufon-script');
	var $styleLink = jQuery('#ait-style');
	var cufonFontScriptId = 'ait-cufon-font-script';
	var cufonFontReplacetId = 'ait-cufon-font-replace';
	var googleFontScriptId = 'ait-google-font-script';
	var options = Cookies.load();

	var switchFont = function(key, font, type, file, option){
		if(isThere('font-family', option)){

			if(type == 'cufon'){
				var scriptNode = document.createElement('script');
				scriptNode.type = 'text/javascript';
				scriptNode.id = cufonFontScriptId;
				scriptNode.src = aitCufonFontsUrl + '/' + file;
				jQuery('#' + googleFontScriptId).remove()

				if(jQuery('#' + cufonFontScriptId).length){
					jQuery('#' + cufonFontScriptId).replaceWith(scriptNode);
				}else{
					$cufonScript.after(scriptNode);
				}
				Cufon.replace($s('font-family', option), {
					fontFamily: font.replace(/\+/g, ' ')
				});
			}else{
				WebFontConfig = {
					google: {families: [ font ]}
				};

				var wf = document.createElement('script');
				wf.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
				wf.type = 'text/javascript';
				wf.async = 'true';
				wf.id = googleFontScriptId;


				jQuery('#' + cufonFontScriptId).remove()
				jQuery('#' + cufonFontReplacetId).remove()

				if(jQuery('#' + googleFontScriptId).length){
					jQuery('#' + googleFontScriptId).replaceWith(wf);
				}else{
					$styleLink.before(wf);
				}

				$s('font-family', option).css({'font-family': font.replace(/\+/g, ' ')});
			}
		}
	};



	$combos.each(function(){
		var $combo = jQuery(this);
		var comboData = $combo.data('ait-themebox-option');
		var optionKey = makeKey(comboData.section, comboData.option);
		var font, type, file;

		// cookies stuff, set the properties from the cookies
		if(options[optionKey] != undefined){
			font = options[optionKey];
			type = options[optionKey + 'Type'];
			file = options[optionKey + 'File'];
			$combo.val(font);
			switchFont(optionKey, font, type, file, comboData.option);
		}

		$combo.change(function(){
			var $selected = $combo.find('option:selected');
			var type = $selected.parent().attr('class');
			var file = $selected.attr('class');
			font = $selected.val();

			options[optionKey] = font;
			options[optionKey + 'Type'] = type;
			options[optionKey + 'File'] = file;
			Cookies.save(options);

			switchFont(optionKey, font, type, file, comboData.option);

			if(type == 'google'){
				if(options[optionKey + 'Index'] != 1){
					options[optionKey + 'Index'] = 1;
					Cookies.save(options);
					window.location.reload();
				}
			}else{
				Cookies.set(optionKey + 'Index', 0);
			}

		});
	});
}



function saveOptions()
{
	var $form = jQuery('#ait-themebox-form');
	var $button = $form.find('#ait-themebox-save');
	var $flashMsg = jQuery('<div>', {id: 'ait-themebox-flash-msg'}).css('display', 'none');
	$button.after($flashMsg);

	var ajaxUrl = $form.attr('action');

	$button.click(function(){
		if(confirm('Are you sure?')){
			jQuery.post(ajaxUrl, $form.serialize(), function(data){

				$flashMsg.fadeIn(200).prepend(data.msg);

				setTimeout(function(){
					$flashMsg.fadeOut(400, function(){
						$flashMsg.empty();
					});
				}, 4000);

			}, "json");

			Cookies.reset();
		}else{
			return false;
		}

		return false;
	});
}



function buyAitThemes()
{
	jQuery("#ait-themebox-best-sellers").change(function(){
		window.location.href = jQuery(this).val();
	});
}



function resetThemeBox()
{
	jQuery('#ait-themebox-reset').click(function(){
		Cookies.reset();
		window.location.reload();
		return false;
     });
}



function makeKey(key1, key2)
{
	return key1 + key2.replace(/\w/, function(firstLetter){
		return firstLetter.toUpperCase();
	});
}