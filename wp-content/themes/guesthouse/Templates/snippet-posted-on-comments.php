<span class="entry-date-comments">
	<a href="{dayLink $post->date}">
	<span class="date-box">
		<span class="date">{$post->date|date:'%d'}</span>
		<span class="month">{$post->date|date:'%b'}</span>
	</span>
	</a>
	<span class="comments-box">
		{if $post->hasOpenComments and !$post->isPasswordRequired}
		<div class="comments-link">
			<a href="{$post->permalink}#comments">{$post->commentsCount}</a>
		</div>
		{/if}
	</span>
</span>
