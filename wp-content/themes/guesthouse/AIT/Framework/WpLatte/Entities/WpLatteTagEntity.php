<?php

/**
 * AIT WordPress Framework
 *
 * Copyright (c) 2011, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */


/**
 * The Tag Entity
 *
 * Similar to Category entity, but uses `post_tag` terms
 */
class WpLatteTagEntity extends WpLatteCategoryEntity
{

	public function __construct($tag)
	{
		if(is_int($tag)){
			$tag = get_term($tag, 'post_tag');
		}

		parent::__construct($tag);
	}
}