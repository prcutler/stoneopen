<?php
function frame( $params, $content = null ) {
	extract(shortcode_atts(array(
	      'bgcolor' => 'none',
	      'version' => '',
	      'closed' => '',
	      'closetext' => 'Close',
	      'opentext' => 'Open',
	      'ver' => $GLOBALS['aitThemeShortcodes']['boxesFrames']
     ), $params));
	
	$result = "";
	
	if($bgcolor == "none") {
		$bg = '';
	} else {
		if($ver=='1'){
			$bg = 'background: '.$bgcolor.';';
		} elseif($ver=='2') {
			$bg = 'background: '.$bgcolor.'; border-color: '.$bgcolor.';';
		}
	}
	
	if($closed != "") {
		$cl = '<div class="frame-close closed"><span class="open text">'.$opentext.'</span><span class="close text" style="display: none;">'.$closetext.'</span></div>';
		$closedStyle = ' display: none;';
		$frameStyle = 'openable';
		
		$result .= "<script type='text/javascript'>
		jQuery(function() {
			jQuery('.frame .frame-close.closed .close.text').hide();
			jQuery('.frame .frame-close.closed .open.text').show();
			
			jQuery('.frame .frame-close').click(function(){
				if(jQuery(this).hasClass('closed')){
					var butt = jQuery(this);
					jQuery(this).parent().find('.frame-wrap').slideDown('slow',function(){
						butt.removeClass('closed');
						butt.find('.close.text').show();
						butt.find('.open.text').hide();
					});
				} else {
					var butt = jQuery(this);
					jQuery(this).parent().find('.frame-wrap').slideUp('slow',function(){
						butt.addClass('closed');
						butt.find('.close.text').hide();
						butt.find('.open.text').show();
					});
				}
			});
		});
		</script>";
		
	} else {
		$cl = '';
		$closedStyle = '';
		$frameStyle = '';
	}
	
	if($ver=='1'){
		$result .= '<div class="frame '.$version.' '.$frameStyle.'"'.$bg.'>'.$cl.'<div class="frame-wrap" style="'.$closedStyle.'"><div class="frame-inner">';
	} elseif($ver=='2') {
		$result .= '<div class="frame '.$version.' '.$frameStyle.'">'.$cl.'<div class="frame-wrap" style="'.$bg.$closedStyle.'"><div class="frame-inner">';
	}
    $result .=  do_shortcode( $content );
    $result .= '</div></div></div>';
    
    return force_balance_tags( $result );
}
add_shortcode( "frame", "frame" );
