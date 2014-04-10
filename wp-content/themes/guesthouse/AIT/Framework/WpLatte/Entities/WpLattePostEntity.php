<?php

/**
 * AIT WordPress Framework
 *
 * Copyright (c) 2011, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */

/**
 * The Post Entity
 */
class WpLattePostEntity extends WpLatteBaseEntity
{

	/**
	 * The post ID
	 * @var int
	 */
	protected $id;

	/**
	 * The ID of parent
	 * @var int
	 */
	protected $parent;

	/**
	 * The post's title
	 * @var string
	 */
	protected $title;

	/**
	 * The post's excerpt
	 * @var string
	 */
	protected $excerpt;

	/**
	 * The post's contents
	 * @var string HTML content
	 */
	protected $content;

	/**
	 * The post author's ID
	 * @var WpLattePostAuthorEntity
	 */
	protected $author;

	/**
	 * Gets permalink as URL string
	 * @var string
	 */
	protected $permalink;

	/**
	 * The post status (publish|pending|draft|private|static|object|attachment|inherit|future|trash)
	 * @var string
	 */
	protected $status;

	/**
	 * Gets post categories
	 *
	 * Can be used as method, if we need specify separator
	 * {$post->categories(', ')}
	 *
	 * @var string List of categories as HTML
	 */
	protected $categories;

	/**
	 * Gets tags
	 *
	 * Can be used as method, if we need specify separator
	 * {$post->tags(', ')}
	 *
	 * @var string List of tags as HTML
	 */
	protected $tags;

	/**
	 * The datetime of the post (YYYY-MM-DD HH:MM:SS)
	 * @var string
	 */
	protected $date;

	/**
	 *  A link to the post. Note: One cannot rely upon the GUID to be the permalink
	 * (as it previously was in pre-2.5), Nor can you expect it to be a valid link to the post.
	 * It's merely a unique identifier, which so happens to be a link to the post at present
	 * @var string
	 */
	protected $guid;

	/**
	 * Post type (post|page|attachment)
	 * @var string
	 */
	protected $type;

	/**
	 * The format of the post, or false if no format is set.
	 * @var string
	 */
	protected $format;

	/**
	 * Gets comments for current post
	 * @var array Array of WpLatteCommentEntity entities
	 */
	protected $comments;

	/**
	 * Number of comments
	 * @var int
	 */
	protected $commentsCount;

	/**
	 * Are comments allowed for this post?
	 * @var bool
	 */
	protected $hasOpenComments;

	/**
	 * Finds out whether the comments need pagination
	 * @var bool
	 */
	protected $willCommentsPaginate;

	/**
	 * False if a password is not required or the correct password cookie is present, true otherwise.
	 * @var bool
	 */
	protected $isPasswordRequired;

	/**
	 * Gets meta options for Post
	 * @var stdClass
	 */
	protected $options;

	protected $thumbnail;

	protected $thumbnailSrc;

	protected $thumbnailUrl;

	protected $isSingle;

	protected $isSticky;

	protected $isMultiAuthor;

	protected $htmlClasses;

	/** @internal */
	private static $commentEntities = array();

	/** @internal */
	private $meta;

	/** @internal */
	private $metaCache;

	/** @internal */
	private $isCustomType = false;

	/** @internal */
	protected $rawContent;

	/** @internal */
	protected $rawExcerpt;

	public $rawPost;

	/** @internal */
	protected $featuredGallery;


	/**
	 * New Post entity
	 * @param stdClass $post Post object
	 * @param array
	 */
	public function __construct($post, $meta = null)
	{
		if(isset($meta['meta'])){
			$this->meta = $meta['meta'];
		}

		if(isset($meta['isCustomType'])){
			$this->isCustomType = (bool) $meta['isCustomType'];
		}

		if(isset($meta['author']) and $meta['author'] instanceof WpLattePostAuthorEntity){
			$this->author = $meta['author'];
		}else{
			$this->author = (int) $post->post_author;
		}

		$this->rawPost = $post;

		$this->parent = $post->post_parent;

		$this->id = $post->ID;

		$this->title = $post->post_title;

		$this->rawExcerpt = $post->post_excerpt;

		$this->rawContent = $post->post_content;

		$this->status = $post->post_status;
		$this->date = $post->post_date;

		$this->guid = $post->guid;

		$this->type = $post->post_type;
		$this->format = get_post_format($post->ID);

		$this->htmlClasses = join(' ', get_post_class('', $this->id));


		$this->commentsCount = $post->comment_count;

		$this->hasOpenComments = ($post->comment_status == 'open');

		$this->isPasswordRequired = post_password_required($post->ID);

		$this->categories = $this->categories();

		$this->permalink = $this->getPermalink();
	}



	/**
	 * The post's excerpt
	 * @return string
	 */
	protected function getExcerpt()
	{
		if($this->isPasswordRequired){
			return  __('There is no excerpt because this is a protected post.', 'ait');
		}

		setup_postdata($this->rawPost);

		return apply_filters('the_excerpt', apply_filters('get_the_excerpt', $this->rawExcerpt));
	}




	/**
	 * Gets content of Post
	 * @param string $more "More" text
	 * @param type $stripTeaser int 0, 1 whether to strip teaser or not
	 * @return HTML content of Post
	 */
	public function content($more = null, $stripTeaser = 0)
	{
		return $this->getContent($more, $stripTeaser);
	}



	/**
	 * Gets content of Post
	 * @param string $more "More" text
	 * @param type $stripTeaser int 0, 1 whether to strip teaser or not
	 * @return HTML content of Post
	 */
	public function remainingContent($more = null, $stripTeaser = false)
	{

		//global $wp_version;

 		//if(version_compare($wp_version, "3.6beta", ">=")){
		//	$this->setupData();

		//	$extra = get_the_remaining_content($more, $stripTeaser );

		//	remove_filter('the_content', 'post_formats_compat', 7);
		//	$content = apply_filters('the_content', $extra);
		//	add_filter('the_content', 'post_formats_compat', 7);

		//	wp_reset_postdata();
		//	return str_replace(']]>', ']]&gt;', $content);
		//}else{
			return $this->content($more, $stripTeaser);
		//}

	}



	/**
	 * Gets content of Post
	 * @param string $more "More" text
	 * @param type $stripTeaser int 0, 1 whether to strip teaser or not
	 * @return HTML content of Post
	 */
	protected function getContent($more = null, $stripTeaser = 0)
	{
		global $wp_query;

		if(is_bool($this->isCustomType) and $this->isCustomType ===  true){
			return apply_filters('the_content', $this->rawContent);
		}else{


			if($wp_query->current_post == -1) // loop has just started
				do_action_ref_array('loop_start', array(&$wp_query));

			$this->setupData();

			$content = get_the_content($more, $stripTeaser);
			$content = apply_filters('the_content', $content);
			$content = str_replace(']]>', ']]&gt;', $content);
			return $content;

		}
	}



	/**
	 * Gets the author of post
	 * @return int|WpLattePostAuthorEntity
	 */
	protected function getAuthor()
	{
		if(is_int($this->author)){
			return $this->author = new WpLattePostAuthorEntity($this->author);
		}else{
			return $this->author;
		}
	}



	/**
	 * Gets permalink as URL string
	 * @return string
	 */
	protected function getPermalink()
	{
		return get_permalink($this->id);
	}


	/**
	 * Alias for has_post_format();
	 */
	public function hasFormat($format)
	{
		return has_post_format($format, $this->id);
	}



	public function setupData()
	{
		global $post;
		$post = $this->rawPost;
		setup_postdata($post);
	}



	/**
	 * the_post_format_*() alias
	 */
	public function format($format, $params = null)
	{
		$output = '';

		$this->setupData();

		$null = null; // variable pass by reference

		global $wp_version;

		/*
 		if(version_compare($wp_version, "3.6beta", ">=")){
			// wp-includes/post-formats.php:599
			if($format == 'chat'){
				$output  = '<dl class="chat">';
				$stanzas = get_the_post_format_chat();

				foreach ( $stanzas as $stanza ) {
					foreach ( $stanza as $row ) {
						$time = '';
						if ( ! empty( $row['time'] ) )
							$time = sprintf( '<time class="chat-timestamp">%s</time>', esc_html( $row['time'] ) );

						$output .= sprintf(
							'<dt class="chat-author chat-author-%1$s vcard">%2$s <cite class="fn">%3$s</cite>: </dt>
								<dd class="chat-text">%4$s</dd>
							',
							esc_attr( sanitize_title_with_dashes( $row['author'] ) ), // Slug.
							$time,
							esc_html( $row['author'] ),
							$row['message']
						);
					}
				}

				$output .= '</dl><!-- .chat -->';

			}elseif($format == 'video'){

				$output = get_the_post_format_media('video', $null, 1);

			}elseif($format == 'audio'){

				$output = get_the_post_format_media('audio', $null, 1);

			}elseif($format == 'image'){

				$output = get_the_post_format_image($params,  $null);

			}elseif($format == 'gallery'){

				$output = get_post_gallery();

			}elseif($format == 'link'){
				$has_url = get_the_post_format_url();

				return ( $has_url ) ? $has_url : apply_filters( 'the_permalink', get_permalink() );
			}
		}else{
		*/
			$output = $this->content();

		//}
		return $output;
	}




	public function date($d = '')
	{
		// we need cop&paste from get_the_date()
		// becacuse of passing parent id to get_post
		$post = get_post($this->id);
		$the_date = '';

		if ( '' == $d )
			$the_date .= mysql2date(get_option('date_format'), $post->post_date);
		else
			$the_date .= mysql2date($d, $post->post_date);

		return apply_filters('get_the_date', $the_date, $d);
	}



	/**
	 * Gets categories divided by separator
	 * @param String $sep Seperator between categories
	 * @return string List of categories in HTML
	 */
	public function categories($sep = ', ', $html = true )
	{

		if(!substr_compare($this->type, "ait-", 0,4)){

			$cats = '';
			if(!$html)
				$cats = array();

			if($html){
				$cats = get_the_term_list( $this->id, $this->type."-category", "", $sep, "" );
			} else {
				$cats = get_the_terms( $this->id, $this->type."-category" );
			}

		} else {

			if($html){
				$cats = get_the_category_list($sep, '', $this->id);
			} else {
				$cats = get_the_terms( $this->id, 'post' );
			}

		}

		if($cats === false)
			$cats = array();

		return $cats;
	}

	/**
	 * Gets terms array
	 * @return array of terms
	 */
	public function terms()
	{

		if(!substr_compare($this->type, "ait-", 0,4)){
			if(is_null($this->terms)){
				$this->terms = get_terms( $this->id, $this->type."-category", "", $sep, "" );
			}
		} else {
			if(is_null($this->terms)){
				$this->terms = get_the_category_list($sep, '', $this->id);
			}
		}

		return $this->terms;
	}

	/**
	 * Gets categories divided by separator
	 * @return string List of categories in HTML
	 */
	protected function getCategories()
	{
		return $this->categories();
	}



	/**
	 * Gets tags divided by separator
	 * @param strin $sep
	 * @return string List of categories in HTML
	 */
	public function tags($sep = ', ')
	{
		if(is_null($this->tags)){
			$this->tags = apply_filters('the_tags', get_the_term_list($this->id, 'post_tag', '' , $sep, ''), '', $sep, '');

		}
		return $this->tags;
	}



	/**
	 * Gets tags divided by separator
	 * @param strin $sep
	 * @return string List of categories in HTML
	 */
	protected function getTags()
	{
		return $this->tags();
	}



	/**
	 * Gets comments
	 * @return array of WpLatteCommentEntity entities
	 */
	protected function getComments()
	{
		// copy&paste from comments_template() function

		global $wp_query, $post, $wpdb, $id, $comment, $user_login, $user_ID, $user_identity, $overridden_cpage;


		//if (!(is_single() || is_page()))
		//	return;

		$req = get_option('require_name_email');

		$commenter = wp_get_current_commenter();
		$comment_author = $commenter['comment_author']; // Escaped by sanitize_comment_cookies()
		$comment_author_email = $commenter['comment_author_email'];  // Escaped by sanitize_comment_cookies()
		$comment_author_url = esc_url($commenter['comment_author_url']);

		if($user_ID){
			$comments = $wpdb->get_results($wpdb->prepare(
				"SELECT * FROM $wpdb->comments
				WHERE
					comment_post_ID = %d AND (comment_approved = '1' OR (user_id = %d AND comment_approved = '0'))
				ORDER BY
					comment_date_gmt",
				$this->id,
				$user_ID
			));
		}elseif(empty($comment_author)){
			$comments = get_comments( array('post_id' => $post->ID, 'status' => 'approve', 'order' => 'ASC'));
		}else{
			$comments = $wpdb->get_results($wpdb->prepare(
			"SELECT * FROM $wpdb->comments
			WHERE
				comment_post_ID = %d AND (comment_approved = '1' OR (comment_author = %s AND comment_author_email = %s AND comment_approved = '0'))
			ORDER BY
				comment_date_gmt",
			$post->ID,
			wp_specialchars_decode($comment_author, ENT_QUOTES),
			$comment_author_email
		));
		}

		$wp_query->comments = apply_filters( 'comments_array', $comments, $post->ID );
		$comments = &$wp_query->comments;
		$wp_query->comment_count = count($wp_query->comments);
		update_comment_cache($wp_query->comments);

		$separate_comments = false; //  $separate_comments Optional, whether to separate the comments by comment type. Default is false.

		if($separate_comments){
			$wp_query->comments_by_type = &separate_comments($comments);
			$comments_by_type = &$wp_query->comments_by_type;
		}

		$overridden_cpage = FALSE;
		if(get_query_var('cpage') == '' && get_option('page_comments')){
			set_query_var('cpage', get_option('default_comments_page') == 'newest' ? get_comment_pages_count() : 1);
			$overridden_cpage = TRUE;
		}
		// end of copy&paste code

		wp_list_comments(array(
			'callback' => 'WpLattePostEntity::createCommentEntity',
			'walker' => new WpLatteCommentWalker,
		));


		$this->comments = self::$commentEntities;

		self::$commentEntities = array(); // unset

		return $this->comments;
	}



	/**
	 * Finds out whether the comments need pagination
	 * @return bool
	 */
	protected function getWillCommentsPaginate()
	{
		return (get_comment_pages_count() > 1 and ((bool) get_option('page_comments')));
	}



	/**
	 * Is post multiauthor?
	 * @return bool
	 */
	protected function getIsMultiAuthor()
	{
		return is_multi_author();
	}




	/**
	 * Is post multiauthor?
	 * @return bool
	 */
	protected function getIsMulti()
	{
		return is_multi_author();
	}


	/**
	 * Gets meta options for Post
	 * @param string $key
	 * @return stdClass
	 */
	public function options($key)
	{
		if(is_array($this->meta) and isset($this->meta[$key])){
			if(!isset($this->metaCache[$key]) or is_null($this->metaCache[$key])){
				$m = $this->meta[$key]->the_meta($this->id);
				if($m == ''){
					$this->metaCache[$key] = $this->meta[$key]->configDefaults;
				}else{
					$this->metaCache[$key] = (object) $m;
				}
				return $this->metaCache[$key];
			}else{
				return $this->metaCache[$key];
			}
		}else{
			if(defined('AIT_DEVELOPMENT') && AIT_DEVELOPMENT){
				throw new WpLatteEntityException('The key "' . htmlspecialchars($key, ENT_QUOTES) . '" does not exists.');
			}else{
				return new stdClass;
			}
		}
	}



	/**
	 * Gets meta options for Post
	 * @return stdClass
	 */
	protected function getOptions()
	{
		if(!is_array($this->meta)){
			if(is_null($this->metaCache)){
				$this->metaCache = (object) $this->meta->the_meta($this->id);
				return $this->metaCache;
			}else{
				return $this->metaCache;
			}
		}else{
			if(defined('AIT_DEVELOPMENT') && AIT_DEVELOPMENT){
				throw new WpLatteEntityException('Options are in array. Use method $post->options($key) and $key "' . htmlspecialchars($key, ENT_QUOTES) . '"');
			}else{
				return new stdClass;
			}
		}
	}



	/**
	 * Gets the thumbnail
	 * @return string
	 */
	protected function getThumbnail()
	{
		return get_the_post_thumbnail($this->id, 'post-thumbnail');
	}



	/**
	 * Gets the thumbnail source
	 * @return string
	 */
	protected function getThumbnailSrc($size = 'full')
	{
		$id = get_post_thumbnail_id($this->id);
        $args = wp_get_attachment_image_src($id, $size);

		global $blog_id;

		if($args !== false){
			$url = $args[0];
			if(is_multisite()){
				if(strstr($url, '/files/') !== false){
					if(isset($blog_id) && $blog_id > 0){
						$a = explode('/files/', $url);
						if(isset($a[1])){
							$u = parse_url($a[0]);
							$a0 = (!is_subdomain_install() and !is_main_site() and isset($u['path'])) ? dirname($a[0]) : $a[0];
							$url = $a0 . '/wp-content/blogs.dir/' . $blog_id . '/files/' . $a[1];
						}
					}
				}else{
					$a = explode('/wp-content/', $url);
					//removes site address part from url in multisite install
					$u = parse_url($a[0]);
					$a0 = (!is_subdomain_install() and !is_main_site() and isset($u['path'])) ? dirname($a[0]) : $a[0];
					$url = $a0 . '/wp-content/' . $a[1];
				}
			}
			return $url;
		}
		return '';
	}



	/**
	 * Just alias for getThumbnailSrc()
	 * @return string
	 */
	protected function getThumbnailUrl($size = 'full')
	{
		return $this->getThumbnailSrc($size);
	}




	/**
	 * Is post single page?
	 * @return bool
	 */
	protected function getIsSingle()
	{
		return is_single($this->id);
	}



	/**
	 * Is post sticky?
	 * @return bool
	 */
	protected function getIsSticky()
	{
		return is_sticky($this->id);
	}



	/**
	 * Checks a post type's support for a given feature.
	 * @param string $feature
	 * @return bool
	 */
	public function hasSupportFor($feature)
	{
		return post_type_supports($this->type, $feature);
	}



	/**
	 * Creats collection of Comment Entities
	 * @param stdClass $comment
	 * @param array $args
	 * @param int $depth
	 * @internal
	 */
	public static function createCommentEntity($comment, $args, $depth)
	{
		$GLOBALS['comment'] = $comment;
		self::$commentEntities[] = new WpLatteCommentEntity($comment, $args, $depth);
	}

}