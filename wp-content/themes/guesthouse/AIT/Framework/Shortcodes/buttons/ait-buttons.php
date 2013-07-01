<?php
/* **********************************************************
 * BUTTONS
 * **********************************************************/
function theme_button( $params, $content = null) {
    extract( shortcode_atts( array(
        'title' => '',
        'link' => '',
        'description' => '',
        'position' => '',
        'bgcolor' => '',
        'color' => '',
        'version' => '',
        'width' => '',
        'modalname' => '',
        'picture' => '',
        'height' => '',
        'ver' => $GLOBALS['aitThemeShortcodes']['buttons'] 
    ), $params ) );
	
	if($position == "left"){
		$alignPos = " alignleft";
	} elseif($position == "right"){
		$alignPos = " alignright";
	} elseif($position == "center"){
		$alignPos = " aligncenter";	
	} else {
		$alignPos = "";
	}
	
	if($width == ""){
		$widthStyle = "";
	} else {
		$widthStyle = 'width: '.$width.';';
	}
	
	if($height == ""){
		$heightStyle = "";
	} else {
		$heightStyle = 'height: '.$height.';';
	}
	
  if($bgcolor == ""){
		$bgcolorStyle = "";
	} else {
	  if($ver=='2')
    {
      //if($buttonPicture != ""){
		    $bgcolorStyle = 'background: url(\''.THEME_URL.'/'.$picture.'\') no-repeat scroll center center '.$bgcolor.';';
		  //} else {
        //$bgcolorStyle = 'background: url(\'xxx\') no-repeat scroll center center;';
      //}
		}
		else
    {
      $bgcolorStyle = 'background-color: '.$bgcolor.'; border-color: '.$bgcolor.';';
    }
	}
	
  if($color == ""){
		$colorStyle = "";
	} else {
		$colorStyle = 'color: '.$color.';';
	}
	// call modal window
	if($modalname != ""){
		$link = '#sc-modal-window-'.$modalname;
		$modalClass = " sc-modal-link";
	} else {
		$modalClass = "";
	}
	
	if($ver=='2'){
	  if($picture != ""){
		    return '<a href="' . $link . '" class="sc-button'. $modalClass . $alignPos . ' ' . $version.'" style="'.$bgcolorStyle.' '.$widthStyle.' '.$heightStyle.'"><span class="border"><span class="wrap"><span class="title" style="'.$colorStyle.'">' . $title . '</span><span class="description">' . $description . '</span></span></span></a>';
		  } else {
        return '<a href="' . $link . '" class="sc-button'. $modalClass . $alignPos . ' ' . $version.'" style="'.$bgcolorStyle.' '.$widthStyle.' '.$heightStyle.'"><span class="border"><span class="wrap"><span class="title" style="'.$colorStyle.'">' . $title . '</span><span class="description">' . $description . '</span></span></span></a>';
      }	  
  } else {
    return '<a href="' . $link . '" class="sc-button'. $modalClass . $alignPos . ' ' . $version.'" style="'.$bgcolorStyle.' '.$widthStyle.' '.$heightStyle.'"><span class="border"><span class="wrap"><span class="title" style="'.$colorStyle.'">' . $title . '</span><span class="description">' . $description . '</span></span></span></a>';
 	}
}
add_shortcode( 'button', 'theme_button' );

function theme_button_modal( $params, $content = null) {
    extract( shortcode_atts( array(
        'title' => '',
        'link' => '',
        'description' => '',
        'position' => '',
        'bgcolor' => '',
        'color' => '',
        'version' => '',
        'width' => '',
        'modalname' => ''
    ), $params ) );
	
	if($position == "left"){
		$alignPos = " alignleft";
	} elseif($position == "right"){
		$alignPos = " alignright";
	} elseif($position == "center"){
		$alignPos = " aligncenter";	
	} else {
		$alignPos = "";
	}
	
	if($width == ""){
		$widthStyle = "";
	} else {
		$widthStyle = 'width: '.$width.';';
	}
	if($bgcolor == ""){
		$bgcolorStyle = "";
	} else {
		$bgcolorStyle = 'background-color: '.$bgcolor.'; border-color: '.$bgcolor.';';
	}
	if($color == ""){
		$colorStyle = "";
	} else {
		$colorStyle = 'color: '.$color.';';
	}
	// call modal window
	if($modalname != ""){
		$link = '#sc-modal-window-'.$modalname;
		$modalClass = " sc-modal-link";
	} else {
		$modalClass = "";
	}
	
  	return '<a href="' . $link . '" class="sc-button'. $modalClass . $alignPos . ' ' . $version.'" style="'.$bgcolorStyle.' '.$widthStyle.'"><span class="border"><span class="wrap"><span class="title" style="'.$colorStyle.'">' . $title . '</span><span class="description">' . $description . '</span></span></span></a>';
}
add_shortcode( 'buttonModal', 'theme_button_modal' );