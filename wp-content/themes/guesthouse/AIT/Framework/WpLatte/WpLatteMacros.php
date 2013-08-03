<?php

/**
 * AIT WordPress Framework
 *
 * Copyright (c) 2011, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */

/**
 * The WpLatte Macros
 *
 * Single macros
 * * `{dynamicSidebar $args}` Alias for "dynamic_sidebar()":http://codex.wordpress.org/Function_Reference/dynamic_sidebar
 * * `{head}` Alias for wp_head()
 * * `{footer}` Alias for wp_footer()
 * * `{menu $args}` Alias for "wp_nav_menu()":http://codex.wordpress.org/Function_Reference/wp_nav_menu
 * * `{title}` Title in <title> element
 * * `{editPostLink $id}`  Alias for "edit_post_link()":http://codex.wordpress.org/Function_Reference/edit_post_link
 * * `{editCommentLink $commentId}` Own implementation of "edit_comment_link()":http://codex.wordpress.org/Function_Reference/edit_comment_link
 * * `{postContentPager $label = 'Pages:', $before = '<div class="page-link">', $after = '</div>'}` More simple "wp_link_pages()":http://codex.wordpress.org/Function_Reference/wp_link_pages
 * * `{commentForm $args}` Alias for "comment_form()":http://codex.wordpress.org/Function_Reference/comment_form
 * * `{prevCommentsLink $label = '« Older Comments'}` Alias for "previous_comments_link()":http://codex.wordpress.org/Template_Tags/previous_comments_link
 * * `{nextCommentsLink $label = 'Newer Comments »'}` Alias for "next_comments_link()":http://codex.wordpress.org/Template_Tags/next_comments_link. Second parameter `max_pages` has default value `0`.
 * * `{prevPostsLink}`
 * * `{prevPostLink}`
 * * `{nextPostsLink}`
 * * `{nextPostLink}`
 * * `{commentTitle $postTitle, $postCommentsCount, 'One thought on', 'thoughts on'}`
 * * `{commentReplyLink 'Reply <span>&darr;</span>', $comment->args, $comment->depth, $comment->id}` All parameters are required.
 * * `{less}` Converts LESS to CSS and returns URL to generated style.css file with timestamp parameter
 * * `{generatedCss}` Converts LESS to CSS and returns URL to generated css file in cache with timestamp parameter
 * * `{breadcrumbs home => 'Home', delimiter => '&raquo;'}` Generates breadcrumbs.
 * * `{cufon fonts, fancyFont, "$themeUrl/design/js/libs/cufon.js", THEME_FONTS_URL . "/{$themeOptions->fonts->fancyFont->file}", $themeOptions->fonts->fancyFont->font, $themeOptions->general->displayThemebox}`
 * * `{dayLink $date}` Alias for "get_day_link()":http://codex.wordpress.org/Function_Reference/get_day_link. `$date` parameter is in format accepted by strtotime().
 * * `{bodyClasses class1, class2, ...}` Prints get_body_class() + "class1 class2 ..."
 * * `{doShortcode $content}` Alias for "do_shortcode($content)":http://codex.wordpress.org/Function_Reference/do_shortcode
 * * `{stripTag $inputText}` Alias for strip_tags() PHP function
 *
 * Pair macros
 * * `{willPaginate}{/willPaginate}` If $wp_query->max_num_pages > 1
 * * `{listComments comments => $commentsEntities}{/listComments}`
 * * `{isActiveSidebar "sidebar-name"}{/isActiveSidebar}` Alias for `if(is_active_sidebar("...")): .... endif;`
 */
class WpLatteMacros extends NMacroSet
{



	/**
	 * Install all macros
	 *
	 * @param NParser $parser
	 */
	public static function install(NParser $parser)
	{
		$me = new self($parser);

		$me->addMacro('_', array($me, 'macroTranslate'), array($me, 'macroTranslate'));

		$me->addMacro('dynamicSidebar', 'dynamic_sidebar(%node.args);');
		$me->addMacro('widgetArea', 'dynamic_sidebar(%node.args);');

		$me->addMacro('isActiveSidebar', 'if(is_active_sidebar(%node.word)):', 'endif;');
		$me->addMacro('isNotActiveSidebar', 'if(!is_active_sidebar(%node.word)):', 'endif;');

		$me->addMacro('isActiveWidgetArea', 'if(is_active_sidebar(%node.word)):', 'endif;');
		$me->addMacro('isNotActiveWidgetArea', 'if(!is_active_sidebar(%node.word)):', 'endif;');

		$me->addMacro('head', 'if(is_singular() && get_option("thread_comments")){wp_enqueue_script("comment-reply");}wp_head();');

		$me->addMacro('footer', 'wp_footer();');

		$me->addMacro('mobileDetectionScript', " ?><script type='text/javascript'>var ua = navigator.userAgent; var meta = document.createElement('meta');if((ua.toLowerCase().indexOf('android') > -1 && ua.toLowerCase().indexOf('mobile')) || ((ua.match(/iPhone/i)) || (ua.match(/iPad/i)))){ meta.name = 'viewport';	meta.content = 'target-densitydpi=device-dpi, width=device-width'; }var m = document.getElementsByTagName('meta')[0]; m.parentNode.insertBefore(meta,m);</script> <?php ");

		$me->addMacro('menu', 'wp_nav_menu(%node.array);');

		$me->addMacro('title', 'echo WpLatteFunctions::getTitle()');

		$me->addMacro('editPostLink', 'edit_post_link(__("Edit", "ait"), "<span class=\"edit-link\">", "</span>", %node.word);');

		$me->addMacro('editCommentLink', 'WpLatteFunctions::editCommentLink(__("Edit", "ait"), %node.word, "<span class=\"edit-link\">", "</span>");');

		$me->addMacro('postContentPager', '
			$a = %node.array;
			if(empty($a)){
				wp_link_pages(array(
					"before" => "<div class=\"page-link\"><span>" . __("Pages:", "ait") . "</span>",
					"after" => "</div>"
				));
			}else{
				wp_link_pages(array(
					"before" => $a[1] . "<span>" . $a[0] . "</span>",
					"after" => $a[2]
				));
			}
			unset($a);
			'
		);

		$me->addMacro('commentForm', 'comment_form(%node.array);');

		$me->addMacro('prevCommentsLink', 'previous_comments_link(%node.word)');

		$me->addMacro('nextCommentsLink', 'next_comments_link(%node.word)');

		$me->addMacro('prevPostsLink', 'previous_posts_link(%node.word)');
		$me->addMacro('nextPostsLink', 'next_posts_link(%node.word)');

		$me->addMacro('prevPostLink', 'previous_post_link("%link", %node.word)');
		$me->addMacro('nextPostLink', 'next_post_link("%link", %node.word)');

		$me->addMacro('commentTitle', '
				$a = %node.array;
				$a[0] = %escape($a[0]);
				printf(_n(
						"$a[2] &ldquo;%2\$s&rdquo;",
						"%1\$s $a[3] &ldquo;%2\$s&rdquo;",
						$a[1],
						"ait"
					),
					number_format_i18n($a[1]),
					"<span>$a[0]</span>"
				);unset($a);'
		);


		$me->addMacro('commentReplyLink', '
				$a = %node.array;
				comment_reply_link(array_merge(
					$a[1],
					array(
						"reply_text" => $a[0],
						"depth" => $a[2],
						"max_depth" => $a[1]["max_depth"]
					)
				), $a[3]); unset($a);'
		);


		// oh my fucking god, if there was $node->tokenizer->fetchArray(), so life would be better
		$me->addMacro('listComments',
			'
			$a = %node.array;
			$depth = 1;
			if(isset($a["begin"]))
				echo $a["begin"];
			else
				echo "<ol class=\"commentlist\">";

			if(isset($a["childrenClass"]))
				$children = " class=\"$a[childrenClass]\"";
			else
				$children = " class=\"children\"";

			$iterations = 0; foreach ($iterator = $_l->its[] = new NSmartCachingIterator($a["comments"]) as $comment):
				if ($comment->depth > $depth){
					echo "<ul{$children}>";
					$depth = $comment->depth;
				}elseif($comment->depth == $depth and !$iterator->isFirst()){
					echo "</li>";
				}elseif($comment->depth < $depth){
					echo "</li>";
					echo str_repeat("</ul></li>", $depth - $comment->depth);
					$depth = $comment->depth;
				}
			',
			'
			$iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its);
			echo "</li>";
			echo str_repeat("</ul></li>", $depth - 1);
			if(isset($a["end"]))
				echo $a["end"];
			else
				echo "</ol>";
			'
		);


		$me->addMacro('less', 'echo WpLatteFunctions::lessify()');

		$me->addMacro('generatedCss', 'echo WpLatteFunctions::generatedCss()');

		$me->addMacro('breadcrumbs', 'echo WpLatteFunctions::breadcrumbs(%node.array)');

		$me->addMacro('linkTo', 'echo WpLatteFunctions::linkTo(%node.args)');

		$me->addMacro('lang', 'echo do_shortcode(%node.args)');

		$me->addMacro('cufon', "
			\$__cufon = %node.array; ?>
			<script id=\"ait-cufon-script\" src=\"<?php echo \$__cufon[2] ?>\"></script>
			<?php
			\$__tbCookie = @strstr(\$_COOKIE['aitThemeBox-' .THEME_CODE_NAME], 'Type\\\":\\\"google\\\"');
			if(\$__tbCookie === false and substr(\$__cufon[3], -3, 3) == '.js'): ?>
			<script id=\"ait-cufon-font-script\" src=\"<?php echo \$__cufon[3] ?>\"></script>
			<?php endif; ?>

			<script id=\"ait-cufon-font-replace\">
				<?php if(\$__cufon[5]): ?>
				var isCookie = false;
				try{
					var type = Cookies.get('<?php echo \$__cufon[0] . ucfirst(\$__cufon[1]) . 'Type'?>');
					if(type == undefined || (type != undefined && type == 'cufon'))
						isCookie = true;
				}catch(e){
					isCookie = true;
				}

				if(isCookie != false){
				<?php endif; ?>
					<?php \$__font = WpLatteFunctions::getCssFontSelectors(\$__cufon[1]);?>
					Cufon.now();
					<?php foreach(\$__font as \$selectors => \$values): ?>
					Cufon.replace('<?php echo \$selectors ?>', {
						fontFamily: \"<?php echo \$__cufon[4]?>\".replace(/\+/g, ' ')
						<?php if(isset(\$values['text-shadow'])): ?>, textShadow: '<?php echo \$values['text-shadow'] ?>'<?php endif; ?>
						<?php if(isset(\$values['hover'])): ?>, hover: {<?php if(isset(\$values['hover']['color'])): ?>color: '<?php echo \$values['hover']['color'] ?>'<?php endif; ?><?php if(isset(\$values['hover']['text-shadow'])): ?>,textShadow: '<?php echo \$values['hover']['text-shadow'] ?>'<?php endif; ?>}
						<?php endif; ?>
					});
					<?php endforeach; ?>
				<?php if(\$__cufon[5]): ?>}<?php endif; ?>
			</script><?php
		");

		$me->addMacro('dayLink', 'echo WpLatteFunctions::getDayLink(%node.args)');

		$me->addMacro('bodyClasses', "echo join(' ', get_body_class()) . ' ' . join(' ', %node.array)");

		$me->addMacro('willPaginate', 'if($GLOBALS["wp_query"]->max_num_pages > 1):', 'endif;');

		$me->addMacro('doShortcode', 'echo do_shortcode(%node.args)');

		$me->addMacro('paginateLinks', 'echo WpLatteFunctions::paginateLinks(%node.args)');

		$me->addMacro('stripTag', 'echo WpLatteFunctions::stripTag(%node.args);');

		$me->addMacro('getHeader', 'get_header(%node.word)');

		$me->addMacro('getFooter', 'get_footer(%node.word)');

		$me->addMacro('getSidebar', 'get_sidebar(%node.word)');

		$me->addMacro('timthumb', 'echo TIMTHUMB_URL . "?" . http_build_query(%node.array, "", "&amp;")');
		$me->addMacro('thumbnailResize', 'echo AitImageResizer::resize(%node.word, %node.array)');

		$me->addMacro('widget', 'the_widget(%node.word, %node.array)');

		$me->addMacro('googleAnalyticsCode', array($me, 'googleAnalyticsCodeMacro'));

		$me->addMacro('includePart', array($me, 'macroIncludePart'));
	}



	public function macroTranslate(NMacroNode $node, $writer)
	{
		if($node->closing){
			return $writer->write("echo  %modify(__(ob_get_clean(), 'ait'))");
		}elseif ($node->isEmpty = ($node->args !== '')){

			$name = $node->tokenizer->fetchWord();
			$args = $writer->formatArgs();
			// back compatibility with {_string} macro
			if(!in_array($name, array('_', 'n', 'x', 'nx'))){
				if($writer->canQuote($node->tokenizer))
					$name = "'$name'";

				// case: {__'smth'} - without space after underscores
				if($name[0] == '_')
					$name = ltrim($name, '_');

				$_ = '__(';
				return $writer->write("echo %modify(" . $_ . $name . ", 'ait'))");
			}

			return $writer->write("echo %modify(_{$name}({$args}, 'ait'))");

		}else{
			return 'ob_start()';
		}
	}



	public function googleAnalyticsCodeMacro($node, $writer)
	{
		$code = '?>
		<?php if(!empty(%node.word)): ?>
	<script>
		var _gaq=[["_setAccount","<?php echo %node.word ?>"],["_trackPageview"]];
		(function(d,t){ var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
		g.src="//www.google-analytics.com/ga.js";
		s.parentNode.insertBefore(g,s) }(document,"script"));
	</script>
	<?php endif;
		';
		return $writer->write($code);
	}



	/**
	 * {includePart "file", name, params}
	 * Alias for get_template_part()
	 */
	public function macroIncludePart(NMacroNode $node, NPhpWriter $writer)
	{
		$slug = $node->tokenizer->fetchWord();
		$params = self::prepareIncludePartParams($writer->formatArray());

		$slug = $writer->formatWord($slug);
		$name = $writer->formatWord($params['name']);

		return $writer->write('NCoreMacros::includeTemplate(' . __CLASS__ . '::getTemplatePart(' . $slug . ', ' . $name . '), ' . $params['params'] . ' + get_defined_vars(), $_l->templates[%var])->render()', $this->parser->templateId);
	}




	/**
	 * Load a template part into a template
	 * Alias for get_template_part()
	 *
	 * @param string $slug The slug name for the generic template.
	 * @param string $name The name of the specialised template.
	 */
	public static function getTemplatePart($slug, $name = null)
	{
		do_action("get_template_part_{$slug}", $slug, $name);

		$templates = array();

		if($name)
			$templates[] = "Templates/{$slug}-{$name}.php"; // hardcoded Templates dir :(

		$templates[] = "Templates/{$slug}.php";

		$x = locate_template($templates, false, false);
		return $x;
	}



	/**
	 * Helper for macroIncludePart
	 * @param  string $params
	 * @return array
	 */
	private static function prepareIncludePartParams($params)
	{
		$return = array('name' => false, 'params' => 'array()');

		$p = explode(',', substr($params, 6, -1));

		if(count($p) >= 1 and strpos($p[0], '=>') === FALSE){
			$p = is_string($p) ? trim($p) : $p;
			if(!empty($p)){
				$return['name'] = !NStrings::startsWith($p[0], '$') ? substr($p[0], 1, -1) : $p[0];
				unset($p[0]);
				$return['params'] = implode(',', $p);
			}
		}elseif(count($p) >= 1 and strpos($p[0], '=>') !== FALSE){
			if(!empty($p)){
				$return['name'] = false;
				$return['params'] = implode(',', $p);
			}
		}

		$return['params'] = 'array(' . $return['params'] . ')';

		return $return;
	}

}
