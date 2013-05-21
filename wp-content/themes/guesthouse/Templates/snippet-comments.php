<div id="comments">
{if !$post->isPasswordRequired}

{if $post->comments}

		<h2 id="comments-title">

		</h2>

		{include snippet-comments-pagination.php, location => 'above'}

	{listComments comments => $post->comments}
			{if $comment->type == 'pingback' or $comment->type == 'trackback'}
			<li class="post pingback">
				<p>
				Pingback
				{!$comment->author->link}
				{editCommentLink $comment->id}
				</p>
			{else}

			{* this is start tag, but end tag is missing in this template, it is included in {/listComments} macro. Weird. I know. *}
			<li classs="{$comment->classes}">

				<article id="comment-{$comment->id}" class="comment">
					<footer class="comment-meta">
						<div class="comment-author vcard">

							<cite class="fn">{!$comment->author->nameWithLink}</cite>

							<a href="{$comment->url}" class="comment-date">
								<time pubdate datetime="{$comment->date|date:'c'}">
									{$comment->date|date:$site->dateFormat} at {$comment->date|date:$site->timeFormat}
								</time>
							</a>

							<span class="comment-links">
								{editCommentLink $comment->id}
								<div class="reply">
									{commentReplyLink 'Reply <span>&darr;</span>', $comment->args, $comment->depth, $comment->id}
								</div><!-- .reply -->
							</span>

						</div><!-- .comment-author .vcard -->

						{if !$comment->approved}
						<em class="comment-awaiting-moderation">Your comment is awaiting moderation.</em><br>
						{/if}

					</footer>

					<div class="comment-content">

						<span class="comment-avatar">
							{!$comment->author->avatar}
						</span>

						{!$comment->content}
					</div>

				</article><!-- #comment-## -->
			{/if}
		{/listComments}

		{include snippet-comments-pagination.php, location => 'below'}

{elseif !$post->hasOpenComments && $post->type != 'page' && $post->hasSupportFor('comments')}

	<p class="nocomments">Comments are closed.</p>

{/if}

{commentForm}

{else}
	<p class="nopassword">This post is password protected. Enter the password to view any comments.</p>
{/if}
</div><!-- #comments -->
