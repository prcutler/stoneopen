<?php

/**
 * AIT WordPress Framework
 *
 * Copyright (c) 2011, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */

/**
 * The Comment Author Entity
 *
 * Used by WpLatteCommentEntity class
 */
class WpLatteCommentAuthorEntity extends WpLatteBaseEntity
{

	/**
	 * Comment user ID
	 * @var int
	 */
	protected $id;

	/**
	 * Comment ID
	 * @var int
	 */
	protected $commentId;

	/**
	 * Comment user name
	 * @var string
	 */
	protected $name;

	/**
	 * Comment user email
	 * @var string
	 */
	protected $email;

	/**
	 * Comment user website url
	 * @var string
	 */
	protected $url;

	/**
	 * Comment user IP
	 * @var string
	 */
	protected $ip;

	/**
	 * Comment user name as clickable HTML link
	 * @var string
	 */
	protected $nameWithLink;

	/**
	 * <img> tag for the user's avatar
	 * @var string
	 */
	protected $avatar;

	/** @internal */
	private $commentParent;



	public function __construct($comment)
	{
		$this->id = (int) $comment->user_id;
		$this->commentId = (int) $comment->comment_ID;
		$this->name = $comment->comment_author;
		$this->email = $comment->comment_author_email;
		$this->url = $comment->comment_author_url;
		$this->ip = $comment->comment_author_IP;
		$this->commentParent = (int) $comment->comment_parent;
	}



	/**
	 * Name with URL of comment author
	 * @return string Comment Author name or HTML link for author's URL
	 */
	protected function getNameWithLink()
	{
		return get_comment_author_link($this->commentId);
	}



	/**
	 * Gets avatar
	 * @return string <img> tag for the user's avatar
	 */
	protected function getAvatar()
	{
		return $this->avatar();
	}



	/**
	 * Gets avatar
	 * @param int $size Width of avatar icon
	 * @return string <img> tag for the user's avatar
	 */
	public function avatar($size = null)
	{
		$id = $this->commentParent;
		if(is_null($size)){
			$size = 68;
			if($id != 0)
				$size = 39;
		}

		return get_avatar($this->email, $size);
	}
}