<?php

/**
 * AIT WordPress Framework
 *
 * Copyright (c) 2011, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */

/**
 * The Comment Entity
 */
class WpLatteCommentEntity extends WpLatteBaseEntity
{

	/**
	 * The comment ID
	 * @var int
	 */
	protected $id;

	/**
	 * The ID of the post/page that this comment responds to
	 * @var int
	 */
	protected $postId;

	/**
	 * The parent comment's ID for nested comments
	 * @var WpLatteCommentEntity
	 */
	protected $parent;

	/**
	 * The datetime of the comment (YYYY-MM-DD HH:MM:SS)
	 * @var string
	 */
	protected $date;

	/**
	 * The GMT datetime of the comment (YYYY-MM-DD HH:MM:SS)
	 * @var string
	 */
	protected $dateGmt;

	/**
	 * The comment's content
	 * @var string
	 */
	protected $content;

	/**
	 * The comment's karma
	 * @var int
	 */
	protected $karma;

	/**
	 * The comment approval level
	 * @var bool
	 */
	protected $approved;

	/**
	 * The commenter's user agent (browser, operating system, etc.
	 * @var string
	 */
	protected $browser;

	/**
	 * The comment's type if meaningfull (pingback|trackback), empty for normal comments
	 * @var string
	 */
	protected $type;

	/**
	 * The comment author's ID if s/he is registered (0 otherwise)
	 * @var type
	 */
	protected $userId;

	/**
	 * The comment author's name
	 * @var string
	 */
	protected $author;

	/**
	 * The comment's classes. Uses get_comment_class()
	 * @var String
	 */
	protected $classes;

	/**
	 * Args for {commentReplyLink} macro
	 * @var array
	 */
	protected $args;

	/**
	 * The comment's depth
	 * @var int
	 */
	protected $depth;

	/**
	 * The permalink to the current comment
	 * @var string
	 */
	protected $url;



	public function __construct($comment, $args, $depth)
	{
		$this->id = (int) $comment->comment_ID;
		$this->postId = (int) $comment->comment_post_ID;
		$this->parent = (int) $comment->comment_parent;

		$this->date = $comment->comment_date;
		$this->dateGmt = $comment->comment_date_gmt;

		$this->content = apply_filters('comment_text', $comment->comment_content); // htmlfy!

		$this->karma = (int) $comment->comment_karma;
		$this->approved = (bool) $comment->comment_approved;
		$this->browser = $comment->comment_agent;
		$this->type = $comment->comment_type;

		$this->userId = (int) $comment->user_id;

		$this->author = new WpLatteCommentAuthorEntity($comment);

		$this->classes = implode(' ', get_comment_class());

		$this->args = $args;
		$this->depth = $depth;

		$this->url = null;

		unset($comment);
	}



	/**
	 * The permalink to the current comment
	 * @return string
	 */
	protected function getUrl()
	{
		return get_comment_link($this->id);
	}



	/**
	 * Gets parent of comment
	 * @return WpLatteCommentEntity
	 */
	protected function getParent()
	{
		if(is_int($this->parent)){
			return $this->parent = new self(get_comment($this->parent));
		}else{
			return $this->parent;
		}
	}
}