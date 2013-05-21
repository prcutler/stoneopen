<?php

/**
 * AIT WordPress Framework
 *
 * Copyright (c) 2011, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */


global $aitDashboardPages;

$aitDashboardPages = array(
	'dashboard' => 	__('Dashboard', THEME_CODE_NAME),
	'docs' => 		__('Documentation', THEME_CODE_NAME),
	'faq' => 		__('FAQ', THEME_CODE_NAME),
	//'videos' => 	__('Videos', THEME_CODE_NAME),
	'support' => 	__('Support Forum', THEME_CODE_NAME),
);

$aitDashboardWidgets = array();



function aitAddDashboardWidgets($widgets)
{

	foreach($widgets as $widget){
		$GLOBALS['aitDashboardWidgets'][] = array(
				'id' => $widget[0],
				'title' => $widget[1],
				'content_callback' => $widget[2],
				'params' => isset($widget[3]) ? $widget[3] : array(),
		);

	}
}



function aitDashboard()
{

	$i = 0;
	$c = count($GLOBALS['aitDashboardWidgets']);
	foreach($GLOBALS['aitDashboardWidgets'] as $widget):
		if($i % 3 == 0 or $c < 4):
	?>
			<div class="postbox-container" style="width:49%;">
				<div  class="meta-box-sortables">
		<?php endif; ?>
					<div id="ait-<?php echo $widget['id']; ?>" class="postbox">
						<h3><span><?php echo $widget['title']; ?></span></h3>
						<div class="inside">
							<?php call_user_func($widget['content_callback'], $widget['params']); ?>
						</div>
					</div>
			<?php if(($i + 1) % 3 == 0 or $i == ($c - 1) or $c < 4): ?>
				</div>
			</div>
	<?php
		endif;
		$i++;
	endforeach;
}



function aitDashboardTabs()
{
	(!isset($_GET['tab'])) ? $current = '' : $current = $_GET['tab'];

	$links = '';
	$i = 0;

	foreach($GLOBALS['aitDashboardPages'] as $tabKey => $tabTitle){
		($i != 0) ? $tabSlug = '&amp;tab=' . $tabKey : $tabSlug = '';


		if($tabKey == $current){
			$active = ' nav-tab-active';
		}else{
			($current == '' and $i == 0) ? $active = ' nav-tab-active' : $active = ''; // activate first item
		}

		if($tabKey == 'support'){
			$links .= '<a class="nav-tab" target="_blank" href="http://support.ait-themes.com/categories/wp-' . THEME_CODE_NAME . '">' . $tabTitle . '</a>';
		}else{
			$links .= '<a class="nav-tab' . $active .'" href="admin.php?page=ait-admin' . $tabSlug .'">' . $tabTitle . '</a>';
		}
		$i++;
	}

	return $links;
}



function aitIsDashboardHome()
{
	if(!isset($_GET['tab']))
		return true;
	else
		return false;
}



function aitDashboardPages()
{

	(!isset($_GET['tab'])) ? $currentTab = '' : $currentTab = $_GET['tab'];

	if($currentTab == ''){
		return; // do nothing
	}elseif(isset($GLOBALS['aitDashboardPages'][$currentTab])){
		$f = dirname(__FILE__) . "/ait-dashboard-$currentTab.php";
		if(is_file($f))
			require_once $f;
		else
			wp_die('This page does not exist', 'This page does not exist', array('response' => 404, 'back_link' => true));
	}else{
		wp_die('This page does not exist', 'This page does not exist', array('response' => 404, 'back_link' => true));
	}
}



/**
 *
 * @param string $url Requested URL
 * @param array $params
 * @return string HTML content
 */
function aitCachedDocsRequest($url, $params)
{
	$cacheTransient = 'doc_' . md5($url);
	$cache = get_transient($cacheTransient);

	if($cache !== false){
		return $cache;
	}else{

		$request = wp_remote_get($url);

		if(!is_wp_error($request)){

			if($request['response']['code'] == 200){
				$payload = $request['body'];

				$payload = str_replace("\r", '', $payload);
				$dom = new DOMDocument;
				$dom->encoding = 'UTF-8';
				@$dom->loadHTML($payload);

				$docUrlLength = strlen($params['docUrl']);

				$div = $dom->getElementById($params['id']);

				foreach($div->getElementsByTagName('a') as $a){

					// do not change href with url to images
					if($a->hasChildNodes()){
						$imgs = $a->getElementsByTagName('img');
						if($imgs->length != 0){
							continue;
						}
					}

					$oldHref = $a->getAttribute('href');
					$href = substr($oldHref, $docUrlLength);
					$pos = strpos($href, '?') - 1;
					$href = substr($href, 0, $pos);

					$a->setAttribute('href', $params['adminDocUrl'] . $href);
				}

				$payload = $dom->saveXML($div);
				set_transient($cacheTransient, $payload, $params['expire']);

				return $payload;
			}else{
				return $request;
			}

		}else{
			return false;
		}
	}
}