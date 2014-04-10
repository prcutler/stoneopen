<?php
/* **********************************************************
 * jQuery UI Tabs
 * **********************************************************/
function theme_tabs( $params, $content = null) {
    extract( shortcode_atts( array(
    	'id' => rand(100,1000),
        'ver' => $GLOBALS['aitThemeShortcodes']['tabs'],
        'animation' => 'no',
    ), $params ) );

	$scontent = do_shortcode($content);

	$fx = '';

	if($animation == 'yes'){
		$fx = '
				{
					fx: {
						opacity: "toggle",
					}
				}
		';
	}

	$output = '<div class="ait-tabs" id="ait-tabs-'.$id.'"><ul></ul>';
	$output .= $scontent;
	$output .= '</div>';
	$output .= '
	<script type="text/javascript">
		(function($){

			$(function(){

				var $tabs = $("#ait-tabs-'.$id.'" ),
					$tabsList = $tabs.find("> ul"),
					$tabDivs = $tabs.find(".ait-tab.tab-content"),
					tabsCount = $tabDivs.length;

				$tabs.find("> p, > br").remove();

				var tabId = 0;
				$tabDivs.each(function(){
					tabId++;
					var tabName = "tab-'.$id.'-"+tabId;
					var sharp = "#";
					$(this).attr("id",tabName);
					var tabTitle = $(this).data("ait-tab-title");
					$(\'<li><a class="tab-link" href="\'+sharp+tabName+\'">\'+tabTitle+\'</a></li>\').appendTo($tabsList);
				});

				$tabs.tabs(' . $fx . ');

				if(typeof Cufon !== "undefined")
					Cufon.refresh();
			});

		})(jQuery);
	</script>';

	return $output;

}
add_shortcode( 'tabs', 'theme_tabs' );

function theme_tab( $params, $content = null) {
    extract( shortcode_atts( array(
        'title' => 'title'
    ), $params ) );

	return '<div class="ait-tab tab-content" data-ait-tab-title="'.esc_attr($title).'">'.do_shortcode($content).'</div>';

}
add_shortcode( 'tab', 'theme_tab' );
