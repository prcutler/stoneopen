<?php

/**
 * AIT WordPress Framework
 *
 * Copyright (c) 2011, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */

/**
 * The Post Author Entity 
 */
class WpLattePostAuthorEntity extends WpLatteBaseEntity
{

	/**
	 * The Author ID
	 * @var int
	 */
	protected $id;

	/**
	 * The Author's login
	 * @var string
	 */
	protected $login;

	/**
	 * The Author's nicename
	 * @var string
	 */
	protected $niceName;

	/**
	 * The Author's display name
	 * @var string
	 */
	protected $name;

	/**
	 * The Author's first name
	 * @var string
	 */
	protected $firstName;

	/**
	 * The Author's last name
	 * @var string
	 */
	protected $lastName;

	/**
	 * The Author's nickname
	 * @var string
	 */
	protected $nickname;

	/**
	 * The Author's email
	 * @var string
	 */
	protected $email;

	/**
	 * The Author's url
	 * @var string
	 */
	protected $url;

	/**
	 * The Author's status
	 * @var string
	 */
	protected $status;

	/**
	 * The Author's bio description in raw form
	 * @var string
	 */
	protected $rawDescription;

	/**
	 * The Author's bio description in HTMLfyied form
	 * @var string
	 */
	protected $bio;

	/**
	 * Gets the URL of the author page for the author
	 * @var string
	 */
	protected $postsUrl;

	/**
	 * <img> tag for the authors's avatar
	 * @var string HTML <img> tag
	 */
	protected $avatar;



	public function __construct($author)
	{
		if(is_int($author)){
			$author = get_userdata($author);
		}

		$this->id = (int) $author->ID;
		$this->login = $author->user_login;
		$this->niceName = $author->user_nicename;
		$this->name = $author->display_name;
		$this->firstName = $author->first_name;
		$this->lastName = $author->last_name;
		$this->nickname = $author->nickname;
		$this->email = $author->user_email;
		$this->url = $author->user_url;
		$this->status =  $author->user_status;
		$this->rawDescription = $author->description;
	}



	/**
	 * Gets URL of authors posts
	 * @return string
	 */
	protected function getPostsUrl()
	{
		return get_author_posts_url($this->id);
	}



	/**
	 * Gets description of author with HTML
	 * @return string
	 */
	protected function getBio()
	{
		return apply_filters('the_author_description', $this->rawDescription, $this->id);
	}



	/**
	 * <img> tag for the authors's avatar
	 * @return type
	 */
	protected function getAvatar()
	{
		return $this->avatar();
	}



	/**
	 * Gets author's avatar
	 * @param int $size Size of avatar
	 * @return string
	 */
	public function avatar($size = null)
	{
		if(is_null($this->avatar)){
			if(is_null($size)){
				$size = 68;
			}
			$this->avatar = get_avatar($this->email, $size);
		}
		return $this->avatar;
	}
}