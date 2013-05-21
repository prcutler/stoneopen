<!doctype html>
<html class="no-js" lang="{$site->language}">
<head>
	<meta charset="{$site->charset}">
	<script type="text/javascript">var ua = navigator.userAgent; var meta = document.createElement('meta');if((ua.toLowerCase().indexOf("android") > -1 && ua.toLowerCase().indexOf("mobile")) || ((ua.match(/iPhone/i)) || (ua.match(/iPod/i)))){ meta.name = 'viewport';	meta.content = 'target-densitydpi=device-dpi, width=480'; }var m = document.getElementsByTagName('meta')[0]; m.parentNode.insertBefore(meta,m);</script>
	<title>{title}</title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="{$site->pingbackUrl}">

	{if $themeOptions->fonts->fancyFont->type == 'google'}
	<link href="http://fonts.googleapis.com/css?family={$themeOptions->fonts->fancyFont->font}" rel="stylesheet" type="text/css">
	{/if}

	<link id="ait-style" rel="stylesheet" type="text/css" media="all" href="{less $site->stylesheetUrl}">



	<script src="http://maps.google.com/maps/api/js?v=3.2&amp;sensor=false"></script>
	{head}

    {if isset($themeOptions->general->useResponsive)}
   <link id="responsive-style" rel="stylesheet" type="text/css" media="all" href="{$themeUrl}/responsive.css" />
  {/if}

  	{if $post}
	<!-- facebook open graph -->
	<meta property="og:description" content="{$post->excerpt}" />
	<meta property="og:image" content="{$post->thumbnailSrc}" />
	{/if}

</head>
<body class="{bodyClasses $bodyClasses, ait-guesthouse} {if $themeOptions->fonts->fancyFont->type == 'cufon'}has-cuffon{/if}" data-themeurl="{$themeUrl}">
<!-- DROPDOWN CONTACT -->
{if $themeOptions->globals->dropdownPanelText != ""}
<div id="dropdown-panel" class="dropdown-panel clear" style="display: none">
  <div id="dropdown-panel-speed" style="display: none">{!$themeOptions->globals->dropdownPanelSpeed}</div>
  <div class="dropdown-panel-content">
      <!-- DROPDOWN WIDGET COLUMN 1 :: START -->
        <div id="1" class="dropdown-panel-widget-col widget-col-1" style="width: {!$themeOptions->globals->dropdownWidthFirst}">
          {dynamicSidebar "dropdown-panel-widget-col-1"}
        </div>
      <!-- DROPDOWN WIDGET COLUMN 1 :: END -->

      <!-- DROPDOWN WIDGET COLUMN 2 :: START -->
        <div id="2" class="dropdown-panel-widget-col widget-col-2" style="width: {!$themeOptions->globals->dropdownWidthSecond}">
          {dynamicSidebar "dropdown-panel-widget-col-2"}
        </div>
      <!-- DROPDOWN WIDGET COLUMN 2 :: END -->

      <!-- DROPDOWN WIDGET COLUMN 3:: START -->
        <div id="3" class="dropdown-panel-widget-col widget-col-3" style="width: {!$themeOptions->globals->dropdownWidthThird}; margin-right: 0px">
          {dynamicSidebar "dropdown-panel-widget-col-3"}
        </div>
      <!-- DROPDOWN WIDGET COLUMN 3 :: END -->
  </div>
</div>
{/if}
<!-- DROPDOWN CONTACT -->


<div class="mainpage">
  <!-- HEADER -->
	{if $themeOptions->general->layoutStyle == 'wide'}
  <header class="header">
  {else}
  <header class="header defaultPageWidth">
  {/if}
    <div class="header-layout">
      {if $themeOptions->general->layoutStyle == 'wide'}

        <div class="pattern">
        <div class="background">
      {else}

        <div class="pattern defaultPageWidth">
        <div class="background defaultPageWidth">
      {/if}

        <div class="header-container">
          <div class="header-content defaultContentWidth">
            <div class="logo">
              <!--<img src="design/img/logo.png" />-->
              {if !empty($themeOptions->general->logo_img)}
    				    <a href="{$homeUrl}"><img src="{$themeOptions->general->logo_img}" alt="logo" /></a>
    				  {else}
                <a href="{$homeUrl}"><span>{$themeOptions->general->logo_text}</span></a>
              {/if}
            </div>
            <div id="mainmenu-dropdown-duration" style="display: none;">{$themeOptions->globals->mainmenu_dropdown_time}</div>
			      <div id="mainmenu-dropdown-easing" style="display: none;">{$themeOptions->globals->mainmenu_dropdown_animation}</div>
            {menu 'theme_location' => 'primary-menu', 'fallback_cb' => 'default_menu', 'container' => 'nav', 'container_class' => 'mainmenu', 'menu_class' => 'menu clear'}

            <!-- WPML plugin required -->
            {if function_exists(icl_get_languages)}
              {if icl_get_languages('skip_missing=0')}
          	  <ul class="flags">
              	{foreach icl_get_languages('skip_missing=0') as $lang}
                  	<li><a href="{!$lang['url']}" class="{if $lang['active'] == 1}active{/if}"><img src="{$lang['country_flag_url']}" alt="{$lang['translated_name']}" /></a></li>
                {/foreach}
              </ul>
              {/if}
            {/if}
      	    <!-- WPML plugin required -->

            <!-- DROPDOWN CONTACT CONTROLS -->
            {if isset($themeOptions->globals->dropdownPanelShow)}
            <div class="dropdown-panel-control">
              <div class="dropdown-content">
               <div class="dropdown-panel-control-button">
                  {if $themeOptions->globals->dropdownPanelText != ""}<h5 class="dropdown-panel-control-button-content btn-drop">{!$themeOptions->globals->dropdownPanelText}</h5>{/if}
               </div>
               <div class="dropdown-panel-control-content">
                  {if $themeOptions->globals->dropdownPanelImage != ""}<a href="{!$themeOptions->globals->dropdownPanelAction}"><img src="{!$themeOptions->globals->dropdownPanelImage}"/></a>{/if}
               </div>
              </div>
              <div class="dropdown-panel-teeth-container">
                 <div class="dropdown-panel-teeth">
                   <div class="tooth"></div>
                   <div class="tooth"></div>
                   <div class="tooth"></div>
                   <div class="tooth"></div>
                   <div class="tooth"></div>
                   <div class="tooth"></div>
                   <div class="tooth"></div>
                   <div class="tooth"></div>
                   <div class="tooth"></div>
                   <div class="tooth"></div>
                   <div class="tooth"></div>
                   <div class="tooth"></div>
                   <div class="tooth"></div>
                 </div>
               </div>
            </div>
            {/if}
            <!-- DROPDOWN CONTACT CONTROLS -->
          </div>

        </div>

        {block sectionHeader}
        {if $themeOptions->globals->headerType == 'roomViewer'}
				  {include snippet-custom-room-viewer.php, headerOptions => $themeOptions->globals, options => $themeOptions->globals, reservationOptions => $themeOptions->searchConfig, rooms => $site->create('room', $themeOptions->globals->roomViewerCat)}
				{elseif $themeOptions->globals->headerType == 'slider'}
				  {include snippet-custom-home-slider.php, headerOptions => $themeOptions->globals, options => $themeOptions->globals, reservationOptions => $themeOptions->searchConfig, slides => $site->create('slider-creator', $themeOptions->globals->sliderCat), items => $site->create('item', $themeOptions->searchConfig->sliderFormCat), rooms => $site->create('room', $themeOptions->globals->roomViewerCat)}
		    {else}
		      <div class="slider-content no-slider">
            {if $themeOptions->general->layoutStyle == 'wide'}
              <div class="slider">
            {else}
              <div class="slider defaultPageWidth">
            {/if}
                <!-- TOOLBAR -->
                <div class="toolbar">
                  <div class="defaultContentWidth">
                    <div id="breadcrumb">{__ 'You are here: '}{breadcrumbs}</div>
                  </div>
                </div>
                <!-- TOOLBAR -->
                <div id="no-slider" class="slider-container subpage-slider-container" style="margin-bottom: 0px"></div>
              </div>
            {if $site->isHomepage}
              <div class="white-space" style="background: none; height: 30px"></div>
            {else}
              <div class="white-space-sub" style="background: none; height: 30px"></div>
            {/if}
            </div>
          </div>
        {/if}
			{/block}

    </div>
    </div>
	</header>

  {if $themeOptions->general->layoutStyle == 'wide'}
  <div class="content">
	{else}
	<div class="content defaultPageWidth">
	{/if}

	{define sectionA}
    <!-- CONTENT -->
		{include #content}
		<!-- end of page-content -->
	{/define}

	{define sectionB}
		<!-- SERVICES -->
		{block service-boxes}
			 {include snippet-custom-services-boxes.php, boxes => $site->create('service-box', $themeOptions->globals->globalServiceBoxes)}
		{/block}
	{/define}

	{define sectionC}
		{block staticText}
		<div class="tooltip-icons">
			<div class="text defaultContentWidth clear">
				<div class="text-inside">
				{doShortcode $themeOptions->globals->staticText}
				</div>
			</div>
		</div>
		{/block}
	{/define}

	{if !isset($sectionsOrder)}
		{var $sectionsOrder = $themeOptions->globals->sectionsOrder}
	{/if}

	{foreach $sectionsOrder as $section}
		{if $section == 'content'}
			{include #sectionA}
		{elseif $section == 'serviceBoxes'}
			{include #sectionB}
		{elseif $section == 'staticText'}
			{include #sectionC}
		{/if}
	{/foreach}
	</div>

	<!-- FOOTER -->
	{if $themeOptions->general->layoutStyle == 'wide'}
	<footer class="footer">
	{else}
	<footer class="footer defaultPageWidth">
  {/if}

		<div class="footer-widgets clear">
		  <style type="text/css" scoped="scoped">
		  .footer-widgets .col-1 { width: {!$themeOptions->globals->footerWidthFirst} }
		  .footer-widgets .col-2 { width: {!$themeOptions->globals->footerWidthSecond} }
		  .footer-widgets .col-3 { width: {!$themeOptions->globals->footerWidthThird} }
		  .footer-widgets .col-4 { width: {!$themeOptions->globals->footerWidthFourth} }
		  .footer-widgets .col-5 { width: {!$themeOptions->globals->footerWidthFifth} }
		  .footer-widgets .col-6 { width: {!$themeOptions->globals->footerWidthSixth} }
		  </style>
      <div class="footer-widgets-container">
			{dynamicSidebar "footer-widgets-area"}
			</div>
		</div>
		<div class="footer-links clear">
      <div class="copyright">{doShortcode $themeOptions->general->footer_text}</div>
			<div class="links">
               {menu 'theme_location' => 'footer-menu','fallback_cb' => 'default_footer_menu', 'container' => 'nav', 'container_class' => 'footer-menu', 'menu_class' => 'menu clear', 'depth' => 1 }
			</div>
		</div>
	</footer><!-- end of footer -->
</div><!-- end of mainpage -->

{ifset $themeOptions->general->displayThemebox}
	{include "$themeboxDir/ThemeBoxTemplate.php"}
{/ifset}

{footer}

{if $themeOptions->fonts->fancyFont->type == 'cufon' or $themeOptions->general->displayThemebox}
	{cufon
		fonts,
		fancyFont,
		"$themeUrl/design/js/libs/cufon.js",
		THEME_FONTS_URL . "/{$themeOptions->fonts->fancyFont->file}",
		$themeOptions->fonts->fancyFont->font,
		$themeOptions->general->displayThemebox
	}
{/if}


{if isset($themeOptions->general->ga_code) && ($themeOptions->general->ga_code!="")}
<script type="text/javascript">
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', {$themeOptions->general->ga_code}]);
	_gaq.push(['_trackPageview']);

	(function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
</script>
{/if}

<script type="text/javascript" src="{$themeJsUrl}/libs/cluster.js"></script>
</body>

</html>
