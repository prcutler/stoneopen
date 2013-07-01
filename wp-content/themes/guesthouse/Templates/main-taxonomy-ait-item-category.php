{extends 'main-layout.php'}
{block content}

<!-- SUBPAGE -->
<div id="container" class="subpage defaultContentWidth subpage-line clear">
	<!-- MAINBAR -->
	<div id="content" class="mainbar entry-content">
		<div id="content-wrapper">
			{if $posts}

				{include general-content-nav.php location => 'nav-above'}

				{include snippet-item-loop.php posts => $posts}

				{include general-content-nav.php location => 'nav-below'}

			{else}

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h2 class="entry-title">Nothing Found</h2>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p>Sorry, but nothing matched your search criteria. Please try again with some different keywords.</p>
						{include 'general-search-form.php'}
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			{/if}

		</div><!-- end of content-wrapper -->
	</div><!-- end of mainbar -->

	<!-- SIDEBAR -->
	<div class="sidebar">
		  {dynamicSidebar "blog-widgets-area"}
	</div><!-- end of sidebar -->

</div><!-- end of container -->
