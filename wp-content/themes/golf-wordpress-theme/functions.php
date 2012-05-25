<?php
if ( function_exists('register_sidebar') ){

    register_sidebar(array(
                        'name'=>'Main Sidebar',
                        'before_widget' => '<div class="sbbox">',
                        'after_widget' => '</div>',
                    ));

    register_sidebar(array(
                        'name'=>'Blog Sidebar',
                        'before_widget' => '<div class="sbbox">',
                        'after_widget' => '</div>',
                    ));

}
if ( ! isset( $content_width ) )
	$content_width = 875;


function twentyten_setup() {
	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'twentyten' ),
	) );
}

function twentyten_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'twentyten_page_menu_args' );

// Theme Options

$themename = "Golf WordPress Theme";
$shortname = "enery_transport";

$et_pages_obj = get_pages();
$et_pages = array();

foreach ($et_pages_obj as $et_page) {
	$et_pages[$et_page->ID] = $et_page->post_title;
}

$pages_tmp = array_unshift($et_pages, "Select a Page:");


$options = array (
                 array(	"name" => "What We Offer Widget",
						"type" => "heading"),


                array(	"name" => "Title",
						"desc" => "Enter title of this Widget. Deafault is <strong>What We Offer<strong>",
			    		"id" => $shortname."_offer_title",
			    		"std" => "What We Offer",
			    		"type" => "text"),
						
                array(	"name" => "Content",
						"desc" => "Content of this widget",
			    		"id" => $shortname."_offer_content",
			    		"std" => "What We Offer",
			    		"type" => "textarea"),

                array(	"name" => "Link 'Read More' to ",
						"desc" => "Select the page which will be linked to <strong>Read More</strong> ",
					    "id" => $shortname."_offer_link",
					    "std" => "Select a Page",
					    "type" => "select",
					    "options" => $et_pages),
					    
					    
					    
                array(	"name" => "What Others are saying about us",
						"type" => "heading"),


                array(	"name" => "Title",
						"desc" => "Enter title of this Widget. Deafault is <strong>What Others are saying about us<strong>",
			    		"id" => $shortname."_quote_title",
			    		"std" => "What Others are saying about us",
			    		"type" => "text"),

                array(	"name" => "Quote",
						"desc" => "Quote",
			    		"id" => $shortname."_quote_content",
			    		"std" => "What We Offer",
			    		"type" => "textarea"),

                array(	"name" => "Quote By",
						"desc" => "Quote By",
			    		"id" => $shortname."_quote_by",
			    		"std" => "Quote By",
			    		"type" => "text"),




                /*array(	"name" => "Map Widget",
						"type" => "heading"),


                array(	"name" => "Title",
						"desc" => "Enter title of this Widget. Deafault is <strong>Map<strong>",
			    		"id" => $shortname."_map_title",
			    		"std" => "Map",
			    		"type" => "text"),

                array(	"name" => "Map Content",
						"desc" => "Map Content",
			    		"id" => $shortname."_map_content",
			    		"std" => "Map Content",
			    		"type" => "textarea"),*/



		  );

function mytheme_add_admin() {

    global $themename, $shortname, $options;

    if ( $_GET['page'] == basename(__FILE__) ) {

        if ( 'save' == $_REQUEST['action'] ) {

                foreach ($options as $value) {
					if($value['type'] != 'multicheck'){
                    	update_option( $value['id'], $_REQUEST[ $value['id'] ] );
					}else{
						foreach($value['options'] as $mc_key => $mc_value){
							$up_opt = $value['id'].'_'.$mc_key;
							update_option($up_opt, $_REQUEST[$up_opt] );
						}
					}
				}

                foreach ($options as $value) {
					if($value['type'] != 'multicheck'){
                    	if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); }
					}else{
						foreach($value['options'] as $mc_key => $mc_value){
							$up_opt = $value['id'].'_'.$mc_key;
							if( isset( $_REQUEST[ $up_opt ] ) ) { update_option( $up_opt, $_REQUEST[ $up_opt ]  ); } else { delete_option( $up_opt ); }
						}
					}
				}
                header("Location: themes.php?page=functions.php&saved=true");
                die;

        } else if( 'reset' == $_REQUEST['action'] ) {

            foreach ($options as $value) {
				if($value['type'] != 'multicheck'){
                	delete_option( $value['id'] );
				}else{
					foreach($value['options'] as $mc_key => $mc_value){
						$del_opt = $value['id'].'_'.$mc_key;
						delete_option($del_opt);
					}
				}
			}
            header("Location: themes.php?page=functions.php&reset=true");
            die;

        }
    }

    add_theme_page($themename." Options", "$themename Options", 'edit_themes', basename(__FILE__), 'mytheme_admin');

}

function mytheme_admin() {

    global $themename, $shortname, $options;

    if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings saved.</strong></p></div>';
    if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings reset.</strong></p></div>';

?>
<div class="wrap">
<h2><?php echo $themename; ?> settings</h2>

<form method="post">

<table class="optiontable">

<?php foreach ($options as $value) {

	switch ( $value['type'] ) {
		case 'text':
		option_wrapper_header($value);
		?>
		        <input style="width:400px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_option( $value['id'] ) != "") { echo get_option( $value['id'] ); } else { echo $value['std']; } ?>" />
		<?php
		option_wrapper_footer($value);
		break;

		case 'select':
		option_wrapper_header($value);
		?>
	            <select style="width:240px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
	                <?php foreach ($value['options'] as $option) { ?>
	                <option<?php if ( get_option( $value['id'] ) == $option) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option>
	                <?php } ?>
	            </select>
		<?php
		option_wrapper_footer($value);
		break;

		case 'textarea':
		$ta_options = $value['options'];
		option_wrapper_header($value);
		?>
				<textarea name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" cols="50" rows="5"><?php
				if( get_option($value['id']) != "") {
						echo stripslashes(get_option($value['id']));
					}else{
						echo stripslashes($value['std']);
				}?></textarea>
		<?php
		option_wrapper_footer($value);
		break;

		case "radio":
		option_wrapper_header($value);

 		foreach ($value['options'] as $key=>$option) {
				$radio_setting = get_option($value['id']);
				if($radio_setting != ''){
		    		if ($key == get_option($value['id']) ) {
						$checked = "checked=\"checked\"";
						} else {
							$checked = "";
						}
				}else{
					if($key == $value['std']){
						$checked = "checked=\"checked\"";
					}else{
						$checked = "";
					}
				}?>
	            <input type="radio" name="<?php echo $value['id']; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?> /><?php echo $option; ?><br />
		<?php
		}

		option_wrapper_footer($value);
		break;

		case "checkbox":
		option_wrapper_header($value);
						if(get_option($value['id'])){
							$checked = "checked=\"checked\"";
						}else{
							$checked = "";
						}
					?>
		            <input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
		<?php
		option_wrapper_footer($value);
		break;

		case "multicheck":
		option_wrapper_header($value);

 		foreach ($value['options'] as $key=>$option) {
	 			$pn_key = $value['id'] . '_' . $key;
				$checkbox_setting = get_option($pn_key);
				if($checkbox_setting != ''){
		    		if (get_option($pn_key) ) {
						$checked = "checked=\"checked\"";
						} else {
							$checked = "";
						}
				}else{
					if($key == $value['std']){
						$checked = "checked=\"checked\"";
					}else{
						$checked = "";
					}
				}?>
	            <input type="checkbox" name="<?php echo $pn_key; ?>" id="<?php echo $pn_key; ?>" value="true" <?php echo $checked; ?> /><label for="<?php echo $pn_key; ?>"><?php echo $option; ?></label><br />
		<?php
		}

		option_wrapper_footer($value);
		break;

		case "heading":
		?>
		<tr valign="top">
		    <td colspan="2" ><h3 style="padding-bottom:5px; margin-bottom:15px;border-bottom:1px solid #666;"><?php echo $value['name']; ?></h3></td>
		</tr>
		<?php
		break;

		default:

		break;
	}
}
?>

</table>

<p class="submit">
<input name="save" type="submit" value="Save changes" />
<input type="hidden" name="action" value="save" />
</p>
</form>
<form method="post">
<p class="submit">
<input name="reset" type="submit" value="Reset" />
<input type="hidden" name="action" value="reset" />
</p>
</form>

<?php
}



function display_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard">
			<?php echo get_avatar( $comment, 40 ); ?>
			<?php printf( __( '%s <span class="says">schreef:</span>', 'twentyten' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
		</div><!-- .comment-author .vcard -->
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em><?php _e( 'uw reactie wacht op goedkeuring.' ); ?></em>
			<br />
		<?php endif; ?>

		<div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
			<?php
				/* translators: 1: date, 2: time */
				printf( __( '%1$s at %2$s', 'twentyten' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', 'twentyten' ), ' ' );
			?>
		</div><!-- .comment-meta .commentmetadata -->

		<div class="comment-body"><?php comment_text(); ?></div>

		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div><!-- .reply -->
	</div><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'twentyten' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'twentyten'), ' ' ); ?></p>
	<?php
	break;
	endswitch;
	?>
	<?php
}
add_filter( 'comments_template', 'legacy_comments' );

function legacy_comments( $file ) {
    if ( !function_exists('wp_list_comments') )
        $file = TEMPLATEPATH . '/legacy.comments.php';
    return $file;

}

function option_wrapper_header($values){
	?>
	<tr valign="top">
	    <th scope="row"><?php echo $values['name']; ?>:</th>
	    <td>
	<?php
}

function option_wrapper_footer($values){
	?>
	    </td>
	</tr>
	<tr valign="top">
		<td>&nbsp;</td><td><small><?php echo $values['desc']; ?></small></td>
	</tr>
	<?php
}

function mytheme_wp_head() {
	$stylesheet = get_option('premiumnewstheme_alt_stylesheet');
	if($stylesheet != ''){?>
		<link href="<?php bloginfo('template_directory'); ?>/styles/<?php echo $stylesheet; ?>" rel="stylesheet" type="text/css" />
<?php }
}

add_action('wp_head', 'mytheme_wp_head');
add_action('admin_menu', 'mytheme_add_admin');



// Offer Widget Outputs
$offer_link_page=get_option('enery_transport_offer_link');
$offer_link = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_title = '".$offer_link_page."'");
$offer_title=get_option('enery_transport_offer_title');
$offer_content=get_option('enery_transport_offer_content');
$offer_content=stripslashes($offer_content);


// Quote Widget Outputs
$quote_by=get_option('enery_transport_quote_by');
$quote_title=get_option('enery_transport_quote_title');
$quote_content=get_option('enery_transport_quote_content');
$quote_content=stripslashes($quote_content);

// Map Outputs
$map_title=get_option('enery_transport_map_title');
$map_content=get_option('enery_transport_map_content');
$map_content=stripslashes($map_content);

?>