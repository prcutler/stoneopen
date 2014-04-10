				<section>
				{foreach $posts as $post}
				{if $post->thumbnailSrc}
        <article id="post-{$post->id}" class="{$post->htmlClasses} thumbnail">
        {else}
        <article id="post-{$post->id}" class="{$post->htmlClasses} no-thumbnail">
        {/if}
					<header class="entry-header">

						{if $post->thumbnailSrc}
						<h2 class="entry-title thumbnail"><a href="{$post->permalink}" title="Permalink to {$post->title}" rel="bookmark">{$post->title}</a></h2>
            	<div class="entry-thumbnail">

								<div class="entry-thumb-img">
								{if $site->isSearch}
									<a href="{$post->permalink}"><img src="{$timthumbUrl}?src={$post->thumbnailSrc}&w=50&h=50" alt=""/></a>
								{else}
								  <a href="{$post->permalink}"><img src="{$timthumbUrl}?src={$post->thumbnailSrc}&w=433&h=198" alt=""/></a>
								{/if}
								</div>
							</div>


						{else}

								<h2 class="entry-title no-thumbnail"><a href="{$post->permalink}" title="Permalink to {$post->title}" rel="bookmark">{$post->title}</a></h2>

						{/if}

            <div class="info-box">
			{editPostLink $post->id}
              <a href="#" rel="prettySociable"><img class="share" src="{$themeUrl}/design/img/share.png" /></a>
              <div class="info-box-inside">
                <h3>{$post->date|date:"j M"}</h3>
                <small>{__ 'posted by '}<a class="url fn n" href="{$post->author->postsUrl}" title="View all posts by {$post->author->name}" rel="author">{$post->author->name}</a></small>
                <br><br>
                {if $post->type == 'post'}
    					    {if $post->categories}
                <span><b>{__ 'Categories: '}</b>{!$post->categories}</span>
                <br><br>
                  {/if}
                  {if $post->tags}
                <span><b>{__ 'Tags: '}</b>{!$post->tags}</span>
                <br><br>
                  {/if}
                {/if}
                <span><b>{__ 'Comments: '}</b>{$post->commentsCount}</span>


              </div>
            </div>

					</header><!-- .entry-header -->

					{if $site->isSearch}
					<div class="entry-summary">
						{!$post->excerpt}
					</div><!-- .entry-summary -->
					{else}
					 {if $post->thumbnailSrc}
					<div class="entry-content thumbnail">
					 {else}
					<div class="entry-content no-thumbnail">
					 {/if}
						{!$post->content}
						{postContentPager}
					</div><!-- .entry-content -->
					{/if}
              <!-- /.entry-meta -->
				</article><!-- /#post-{$post->id} -->
				{/foreach}
				</section>
