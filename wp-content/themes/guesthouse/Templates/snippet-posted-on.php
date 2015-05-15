<div class="entry-meta">
	<span class="sep">Posted on </span>
	<a href="{$post->permalink}" title="{$post->date|date:$site->dateFormat}" rel="bookmark">
		<time class="entry-date" datetime="{$post->date|date:'c'}" pubdate>{$post->date|date:$site->dateFormat}</time>
	</a>
	<span class="by-author">
		<span class="sep"> by </span>
		<span class="author vcard">
			<a class="url fn n" href="{$post->author->postsUrl}" title="View all posts by {$post->author->name}" rel="author">{$post->author->name}</a>
		</span>
	</span>
</div><!-- .entry-meta -->
