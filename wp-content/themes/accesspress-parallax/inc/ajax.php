<?php	
	
	// Parallax Defaults
	$parallax_defaults = NULL;

	// Pull all the pages into an array
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');

	$options_categories_obj = get_categories();

	$countsettings = isset($_REQUEST['count_section']) ? sanitize_text_field(wp_unslash($_REQUEST['count_section'])) : '';
?>	

<div class="sub-option clearfix" data-id="<?php echo esc_attr($countsettings); ?>">
<h3 class="title"><?php esc_html_e('Page Title:', 'accesspress-parallax') ?> <span></span><div class="section-toggle"><i class="fa fa-chevron-down"></i></div></h3>
<div class="sub-option-inner">

<div class="inline-label">
<label><?php esc_html_e('Page', 'accesspress-parallax') ?></label>
<select class="parallax_section_page" name="accesspress_parallax[parallax_section][<?php echo esc_attr($countsettings); ?>][page]" class="of-input">
<option value=""><?php esc_html_e('Select a page:', 'accesspress-parallax') ?></option>
<?php foreach ($options_pages_obj as $page) { ?>
	<option value="<?php echo absint($page->ID); ?>"><?php echo esc_html($page->post_title); ?></option>
<?php } ?>
</select>
</div>

<div class="color-picker inline-label">
<label><?php esc_html_e('Font Color', 'accesspress-parallax') ?></label>
<input name="accesspress_parallax[parallax_section][<?php echo esc_attr($countsettings); ?>][font_color]" class="of-color" type="text">
</div>

<div class="color-picker inline-label">
<label><?php esc_html_e('Background Color', 'accesspress-parallax') ?></label>
<input name="accesspress_parallax[parallax_section][<?php echo esc_attr($countsettings); ?>][color]" class="of-color" type="text">
</div>

<div class="inline-label">
<label><?php esc_html_e('Layout', 'accesspress-parallax') ?></label>
<select class="of-section of-section-layout" name="accesspress_parallax[parallax_section][<?php echo esc_attr($countsettings); ?>][layout]">
	<option value="default_template"><?php esc_html_e('Default Section', 'accesspress-parallax') ?></option>
	<option value="service_template"><?php esc_html_e('Service Section', 'accesspress-parallax') ?></option>
	<option value="team_template"><?php esc_html_e('Team Section', 'accesspress-parallax') ?></option>
	<option value="portfolio_template"><?php esc_html_e('Portfolio Section', 'accesspress-parallax') ?></option>
	<option value="testimonial_template"><?php esc_html_e('Testimonial Section', 'accesspress-parallax') ?></option>
	<option value="blog_template"><?php esc_html_e('Blog Section', 'accesspress-parallax') ?></option>
	<option value="action_template"><?php esc_html_e('Call to Action Section', 'accesspress-parallax') ?></option>
	<option value="googlemap_template"><?php esc_html_e('Google Map Section', 'accesspress-parallax') ?></option>
	<option value="blank_template"><?php esc_html_e('Blank Section', 'accesspress-parallax') ?></option>
</select>
</div>

<div class="inline-label toggle-category" style="display:none">
<label class=""><?php esc_html_e('Category', 'accesspress-parallax') ?></label>
<select name="accesspress_parallax[parallax_section][<?php echo esc_attr($countsettings); ?>][category]" class="of-input">
	<option value=""><?php esc_html_e('Select a Category:', 'accesspress-parallax') ?></option>
<?php foreach ($options_categories_obj as $category) { ?>
	<option value="<?php echo absint( $category->cat_ID ); ?>"><?php echo esc_html( $category->cat_name ); ?></option>
<?php } ?>
</select>
</div>

<div class="inline-label">
<label class=""><?php esc_html_e('Background Image', 'accesspress-parallax') ?></label>
<input type="text" placeholder="No file chosen" value="" name="accesspress_parallax[parallax_section][<?php echo esc_attr($countsettings); ?>][image]" class="upload" id="parallax_section">
<input type="button" value="<?php esc_html_e('Upload', 'accesspress-parallax') ?>" class="upload-button button" id="upload-parallax_section">
<div id="parallax_section-image" class="screenshot"></div>
</div>


<div class="of-background-properties hide inline-label">
<label><?php esc_html_e('Background Settings', 'accesspress-parallax') ?></label>

<div class="background-settings">
<div class="clearfix">
<select id="parallax_section_repeat" name="accesspress_parallax[parallax_section][<?php echo esc_attr($countsettings); ?>][repeat]" class="of-background of-background-repeat">
	<option value="no-repeat"><?php esc_html_e('No Repeat', 'accesspress-parallax') ?></option>
	<option value="repeat-x"><?php esc_html_e('Repeat Horizontally', 'accesspress-parallax') ?></option>
	<option value="repeat-y"><?php esc_html_e('Repeat Vertically', 'accesspress-parallax') ?></option>
	<option value="repeat"><?php esc_html_e('Repeat All', 'accesspress-parallax') ?></option>
</select>

<select id="parallax_section_position" name="accesspress_parallax[parallax_section][<?php echo esc_attr($countsettings); ?>][position]" class="of-background of-background-position">
<option value="top left"><?php esc_html_e('Top Left', 'accesspress-parallax') ?></option>
<option value="top center"><?php esc_html_e('Top Center', 'accesspress-parallax') ?></option>
<option value="top right"><?php esc_html_e('Top Right', 'accesspress-parallax') ?></option>
<option value="center left"><?php esc_html_e('Middle Left', 'accesspress-parallax') ?></option>
<option value="center center"><?php esc_html_e('Middle Center', 'accesspress-parallax') ?></option>
<option value="center right"><?php esc_html_e('Middle Right', 'accesspress-parallax') ?></option>
<option value="bottom left"><?php esc_html_e('Bottom Left', 'accesspress-parallax') ?></option>
<option value="bottom center"><?php esc_html_e('Bottom Center', 'accesspress-parallax') ?></option>
<option value="bottom right"><?php esc_html_e('Bottom Right', 'accesspress-parallax') ?></option>
</select>

<select id="parallax_section_attachment" name="accesspress_parallax[parallax_section][<?php echo esc_attr($countsettings); ?>][attachment]" class="of-background of-background-attachment">
<option value="scroll"><?php esc_html_e('Scroll Normally', 'accesspress-parallax') ?></option>
<option value="fixed"><?php esc_html_e('Fixed in Place', 'accesspress-parallax') ?></option>
</select>

<select id="parallax_section_size" name="accesspress_parallax[parallax_section][<?php echo esc_attr($countsettings); ?>][size]" class="of-background of-background-size">
<option value="auto"><?php esc_html_e('Auto', 'accesspress-parallax') ?></option>
<option value="cover"><?php esc_html_e('Cover', 'accesspress-parallax') ?></option>
<option value="contain"><?php esc_html_e('Contain', 'accesspress-parallax') ?></option>
</select>
</div>
</div>

<div class="inline-label">
<label><?php esc_html_e('Overlay', 'accesspress-parallax') ?></label>
<select id="parallax_section_overlay" class="of-background of-background-overlay" name="accesspress_parallax[parallax_section][<?php echo esc_attr($countsettings); ?>][overlay]">
<option value="overlay0"><?php esc_html_e('No Overlay', 'accesspress-parallax') ?></option>
<option value="overlay1"><?php esc_html_e('Small Dotted', 'accesspress-parallax') ?></option>
<option value="overlay2"><?php esc_html_e('Large Dotted', 'accesspress-parallax') ?></option>
<option value="overlay3"><?php esc_html_e('Light Black', 'accesspress-parallax') ?></option>
<option value="overlay4"><?php esc_html_e('Black Dotted', 'accesspress-parallax') ?></option>
</select>
</div>
</div>
<div class="remove-parallax button-primary"><?php esc_html_e('Remove', 'accesspress-parallax') ?></div>
</div>
</div>

