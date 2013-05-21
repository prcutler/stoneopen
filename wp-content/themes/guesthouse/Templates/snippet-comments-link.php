{if $post->hasOpenComments and !$post->isPasswordRequired}
	<span class="sep"> | </span>
	<span class="comments-link">
		{if $post->commentsCount == 0}
		<a href="{$post->permalink}#respond">
			<span class="leave-reply">
				Leave a reply
			</span>
		</a>
		{else}
		<a href="{$post->permalink}#comments">
			{if $post->commentsCount == 1}
			<b>1</b> Reply
			{else}
			<b>{$post->commentsCount}</b> Replies
			{/if}
		</a>
		{/if}
	</span>
{/if}
