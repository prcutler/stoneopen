{extends 'main-layout.php'}
{block content}

<!-- SUBPAGE -->
<div id="container" class="subpage defaultContentWidth subpage-line clear">
	<!-- MAINBAR -->
	<div id="content" class="mainbar entry-content">
		<div id="content-wrapper">

            {if $posts}

				<header class="page-header">
					<h1 class="page-title">
						{__ 'Category Archives:'}<span>{$category->title}</span>
					</h1>

					{if !empty($category->description)}
						<div class="category-archive-meta">{!$category->description}</div>
					{/if}
				</header>

				{include general-content-nav.php location => 'nav-above'}

				{include snippet-content-loop.php posts => $posts}

				{include general-content-nav.php location => 'nav-below'}

			{else}

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title">{__ 'Nothing Found'}</h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p>{__ 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post'}</p>
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
{/block}