		{if $post->willCommentsPaginate}
		<nav id="comment-nav-{$location}">
			<div class="nav-previous">{prevCommentsLink '&larr; Older Comments'}</div>
			<div class="nav-next">{nextCommentsLink 'Newer Comments &rarr;'}</div>
		</nav>
		{/if}
