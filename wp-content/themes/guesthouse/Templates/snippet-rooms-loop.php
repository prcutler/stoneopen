				<section id="rooms">
  				{foreach $posts as $post}
    				<div class="item clearfix">
            {if $post->thumbnailSrc}
            <article id="room-{$post->id}" class="{$post->htmlClasses} thumbnail pf-page">
            {else}
            <article id="room-{$post->id}" class="{$post->htmlClasses} no-thumbnail pf-page">
            {/if}


      					<header class="entry-header">
                  {if $post->thumbnailSrc}
      						  <div class="entry-thumbnail">
      								<div class="entry-thumb-img">
      									<a href="{$post->permalink}"><img src="{$timthumbUrl}?src={$post->thumbnailSrc}&w=309&h=120" alt=""/></a>
      								</div>
      							</div>
      						{/if}
      					</header><!-- .entry-header -->

                {if $post->thumbnailSrc}
                <h2 class="entry-title"><a href="{$post->permalink}" title="Permalink to {$post->title}" rel="bookmark">{$post->title}</a></h2>
                {else}
                <h2 class="entry-title no-thumbnail"><a href="{$post->permalink}" title="Permalink to {$post->title}" rel="bookmark">{$post->title}</a></h2>
                {/if}

      					{if $site->isSearch}
        					<div class="entry-summary">
        						{$post->excerpt}
        					</div><!-- .entry-summary -->
      					{else}
      					 {if $post->thumbnailSrc}
      					   <div class="entry-content thumbnail">
      					 {else}
      					   <div class="entry-content no-thumbnail">
      					 {/if}
      						 {*!$post->content*}
      						<!-- HERE -->
      							{*? d($post)*}
      						<!-- HERE -->
                   {postContentPager}
      					   </div><!-- .entry-content -->
      					{/if}
                    <!-- /.entry-meta -->

    				</article><!-- /#post-{$post->id} -->
    				</div>
  				{/foreach}
				</section>
