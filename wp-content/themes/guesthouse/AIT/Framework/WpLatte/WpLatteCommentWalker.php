<?php

/**
 * AIT WordPress Framework
 *
 * Copyright (c) 2011, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */



/**
 * Extended Walker_Comment class for use in WpLatte
 */
class WpLatteCommentWalker extends Walker_Comment
{

	public function start_lvl(&$output, $depth = 0, $args = array())
	{
		$GLOBALS['comment_depth'] = $depth + 1;
	}



	public function end_lvl(&$output, $depth = 0, $args = array())
	{
		$GLOBALS['comment_depth'] = $depth + 1;
	}



	public function end_el(&$output, $comment, $depth = 0, $args = array())
	{
		if ( !empty($args['end-callback']) ) {
			call_user_func($args['end-callback'], $comment, $args, $depth);
			return;
		}
	}

}