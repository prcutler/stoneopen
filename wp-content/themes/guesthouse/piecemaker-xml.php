<?php

$wpLoad = '../../../wp-load.php';
require_once realpath($wpLoad);

require_once AIT_FRAMEWORK_DIR . '/Libs/PiecemakerSliderXmlGenerator.php';

if(isset($_GET['t'])){
	$t = strip_tags($_GET['t']);

	$slides = WpLatte::createCustomPostEntity('slider-creator', $t);

	$piecemaker = new PiecemakerSliderXmlGenerator($slides, $GLOBALS['aitThemeOptions']->header);

	header("Content-type: application/xml");
	echo str_replace("\n", '', $piecemaker->render());
}