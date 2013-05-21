{extends 'main-layout.php'}

{var $sectionsOrder = isset($post->options('sectionsOrder')->overrideGlobalOrder) ? $post->options('sectionsOrder')->order : null}

{block content}

<!-- SUBPAGE -->
<div class="subpage-title-container defaultContentWidth">
  <h1>{$post->title}</h1>
</div>

<div class="separator-line-new"></div>


<div id="container" class="subpage defaultContentWidth subpage-line clear">
  <!-- MAINBAR -->
	<div id="content" class="mainbar entry-content">
		<div id="content-wrapper">
			{if $post->thumbnailSrc != false }
			<div class="entry-thumbnail">
				<img src="{$timthumbUrl}?src={$post->thumbnailSrc}&w=640&h=200" alt="" />
			</div>
			{/if}

			{!$post->content}

			{*include snippet-comments.php*}

		</div><!-- end of content-wrapper -->
	</div><!-- end of mainbar -->

	<!-- SIDEBAR -->
	<div class="sidebar">

{dynamicSidebar "subpages-sidebar"}

	</div><!-- end of sidebar -->

</div><!-- end of container -->

{/block}

{? isset($post->options('page_header')->overrideGlobalHead) ? $localHeader = 'sectionHeader' : $localHeader = 'xr'}
{define $localHeader}
  {if $post->options('page_header')->headerType == 'roomViewer'}
	 {include snippet-custom-room-viewer.php, headerOptions => $post->options('page_header'), options => $post->options('page_room'), reservationOptions => $post->options('page_room'), rooms => $site->create('room', $post->options('page_room')->roomViewerCat)}
	{elseif $post->options('page_header')->headerType == 'slider'}
	 {include snippet-custom-home-slider.php, headerOptions => $post->options('page_header'), options => $post->options('page_slider'), reservationOptions => $post->options('page_slider'), slides => $site->create('slider-creator', $post->options('page_slider')->sliderCat), items => $site->create('item', $post->options('page_slider')->sliderFormCat), rooms => $site->create('room', $post->options('page_room')->roomViewerCat)}
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
              <div id="breadcrumb">{__ 'You are here:'}{breadcrumbs}</div>
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
{/define}

{? isset($post->options('page_service_boxes')->overrideGlobalServiceBox) ? $localService = 'service-boxes' : $localService = 'xs'}
{define $localService}
	{include snippet-custom-services-boxes.php, boxes => $site->create('service-box',$post->options('page_service_boxes')->serviceBoxCategory)}
{/define}

{? !empty($post->options('page_static_text')->staticText) ? $localStaticText = 'staticText' : $localStaticText = 'xt'}
{define $localStaticText}
      <div class="text defaultContentWidth clear">
        {doShortcode $post->options('page_static_text')->staticText}
      </div>
{/define}
