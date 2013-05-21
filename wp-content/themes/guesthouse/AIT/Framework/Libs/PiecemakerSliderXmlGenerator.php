<?php

/**
 * AIT WordPress Framework
 *
 * Copyright (c) 2011, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */

class PiecemakerSliderXmlGenerator
{

	protected $slides;
	protected $options;

	protected $dom;
	protected $piecemaker;



	public function __construct($slides, $options)
	{
		$this->slides = $slides;
		$this->options = $options;
		$this->dom = new DOMDocument('1.0', 'utf-8');
		$this->dom->preserveWhiteSpace = false;
		$this->piecemaker = $this->dom->appendChild(new DOMElement('Piecemaker'));
	}



	public function createContents()
	{
		$contents = $this->piecemaker->appendChild(new DOMElement('Contents'));

		foreach($this->slides as $slide){
            switch($slide->options->advancedItemType)
            {
				case "image":
					$element = $contents->appendChild($this->dom->createElement('Image'));
					$element->setAttribute('Source', $slide->options->advancedImageSource);
					$element->setAttribute('Title', $slide->options->advancedImageTitle);

					$text = $element->appendChild($this->dom->createElement('Text'));
					$text->appendChild($this->dom->createElement('h1', $slide->options->advancedImageTitle));
					$t = isset($slide->options->advancedImageText) ? $slide->options->advancedImageText : "";
					$text->appendChild($this->dom->createElement('p', $t));

					$url = $element->appendChild($this->dom->createElement('Hyperlink'));
					$url->setAttribute('URL', $slide->options->advancedImageLink);
					$url->setAttribute('Target', $slide->options->advancedImageLinkTarget);
				break;

				case "video":
					$element = $contents->appendChild($this->dom->createElement('Video'));
					$element->setAttribute('Source', $slide->options->advancedVideoSource);
					$element->setAttribute('Title', $slide->options->advancedVideoTitle);
					$element->setAttribute('Width', $slide->options->advancedVideoWidth);
					$element->setAttribute('Height', $slide->options->advancedVideoHeight);

					$image = $element->appendChild($this->dom->createElement('Image'));
					$image->setAttribute('Source', $slide->options->advancedVideoPreview);
				break;

				case "flash":
					$element = $contents->appendChild($this->dom->createElement('Flash'));
					$element->setAttribute('Source', $slide->options->advancedSwfSource);
					$element->setAttribute('Title', $slide->options->advancedSwfTitle);

					$image = $element->appendChild($this->dom->createElement('Image'));
					$image->setAttribute('Source',  $slide->options->advancedSwfPreview);
				break;
            }

			$contents->appendChild($element);
		} // foreach
	}



	public function createTransitions()
	{
		$transitions = $this->piecemaker->appendChild(new DOMElement('Transitions'));
		foreach($this->slides as $slide){
			$element = $transitions->appendChild($this->dom->createElement('Transition'));
			$element->setAttribute('Pieces', $slide->options->advancedTransitionPieces);
			$element->setAttribute('Time', $slide->options->advancedTransitionTime);
			$element->setAttribute('Transition', $slide->options->advancedTransitionType);
			$element->setAttribute('Delay', $slide->options->advancedTransitionDelay);
			$element->setAttribute('DepthOffset', $slide->options->advancedTransitionDepthOffset);
			$element->setAttribute('CubeDistance', $slide->options->advancedTransitionCubeDistance);
		}
		$transitions->appendChild($element);
	}



	public function createSettings()
	{
		$settingsElement = $this->piecemaker->appendChild(new DOMElement('Settings'));
		$options = $this->options;

		$settings = array(
			'ImageWidth', 'ImageHeight', 'LoaderColor', 'InnerSideColor', 'SideShadowAlpha',
			'DropShadowAlpha', 'DropShadowDistance', 'DropShadowScale', 'DropShadowBlurX', 'DropShadowBlurY',
			'MenuDistanceX', 'MenuDistanceY', 'MenuColor1', 'MenuColor2', 'MenuColor3',
			'ControlSize', 'ControlDistance', 'ControlColor1', 'ControlColor2', 'ControlAlpha', 'ControlAlphaOver', 'ControlsX', 'ControlsY', 'ControlsAlign',
			'TooltipHeight', 'TooltipColor', 'TooltipTextY', 'TooltipTextStyle', 'TooltipTextColor', 'TooltipMarginLeft', 'TooltipMarginRight', 'TooltipTextSharpness', 'TooltipTextThickness',
			'InfoWidth', 'InfoBackground', 'InfoBackgroundAlpha', 'InfoMargin', 'InfoSharpness', 'InfoThickness', 'Autoplay', 'FieldOfView',
		);

		$colors = array('LoaderColor', 'InnerSideColor', 'MenuColor1', 'MenuColor2', 'MenuColor3', 'ControlColor1', 'ControlColor2', 'TooltipColor', 'TooltipTextColor','InfoBackground');

		foreach($settings as $key){
			$t = $key;
			$t[0] = strtolower($t[0]);
			if(isset($options->$t)){
				if(in_array($key, $colors) and substr(trim($options->$t), 0, 1) == '#'){
					$settingsElement->setAttribute($key, str_replace('#', '0x', $options->$t));
				}else{
					$settingsElement->setAttribute($key, $options->$t);
				}
			}
		}
	}



	public function render()
	{

		$this->createContents();
		$this->createTransitions();
		$this->createSettings();

		return $this->dom->saveXML();
	}
}