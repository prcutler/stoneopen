{extends 'main-layout.php'}
{block content}

<!-- SUBPAGE -->
<div class="subpage-title-container defaultContentWidth">
  <h1>{__ "This is somewhat embarrassing, isn't it?"}</h1>
</div>

<div class="separator-line-new"></div>
<div id="container" class="subpage defaultContentWidth subpage-line clear">
  <!-- MAINBAR -->
	<div id="content" class="mainbar entry-content">
		<div id="content-wrapper">

			<p>{__ 'Apologies, but the page you requested could not be found. Perhaps searching will help.'}</p>

			{include general-search-form.php}

		</div><!-- end of content-wrapper -->
	</div><!-- end of mainbar -->

	<!-- SIDEBAR -->
	<div class="sidebar">

      {dynamicSidebar "subpages-sidebar"}

	</div><!-- end of sidebar -->

</div><!-- end of container -->
{/block}
