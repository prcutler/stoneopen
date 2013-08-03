<?php

/**
 * AIT WordPress Framework
 *
 * Copyright (c) 2011, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */

/**
 * Generates form with theme options
 * This code is big mess, but works good.
 *
 * @param string $optionKey Key of registered settings by register_setting()
 * @param string $key Key of section from config
 * @param array $page Specific section under the given key
 * @return void
 */
function aitRenderThemeOptionsForm($optionKey, $key, $page)
{
	$opt = $optionKey;

	if(isset($_POST['createSettingsForLang']) and ICL_LANGUAGE_CODE != 'all'){
		$c = aitGetThemeDefaultOptions($GLOBALS['aitThemeConfig']);
		update_option($opt, $c);
		?>
		<div id="setting-error-settings_updated" class="updated settings-error">
			<p><strong>Settings for <?php echo ICL_LANGUAGE_NAME ?> language was created.</strong></p>
		</div>
		<?php
	}

	$o = get_option($opt);
	if($o === false)
		$o = get_option(AIT_DEFAULT_OPTIONS_KEY);

	global $aitThemeConfig;

	if($o !== false)
		$allOptions = $o;
	else
		$allOptions = aitGetThemeDefaultOptions($aitThemeConfig);


	if($opt == AIT_OPTIONS_KEY and $allOptions === false and defined('ICL_LANGUAGE_CODE')){
		 if(ICL_LANGUAGE_CODE != 'all'){
		?><p><?php
		_e('For this langauge there is no settings. Please create new settings.', 'ait');
		?></p>

		<form method="post">
			<p>
				<input type="submit" class="button-primary" name="createSettingsForLang" title="Create settings for this language." value="Create settings for <?php echo ICL_LANGUAGE_NAME; ?>">
			</p>
		</form>

		<?php
		}else{
		?><div class="error"><p><?php
		_e('For "All languages" creating settings is not allowed.', 'ait');
		?></p></div><?php
		}
		return;
	}

	if($opt == AIT_BRANDING_OPTIONS_KEY and $allOptions === false){
		?><div class="updated"><p><?php
		_e('These options are not saved in database yet. Save these default values or change it on whatever you want.', 'ait');
		?></p></div><?php
		$allOptions = array();
	}

	$sortable = false;

	if(isset($page['tabs'])){
		if(!isset($_GET['tab'])) {
			$current_tab = '';
		} else {
			// !!!!! HOSTING FIX
			if($_GET['tab'] == "ait-layout"){
				$current_tab = "globals";
			} else {
				$current_tab = $_GET['tab'];
			}
		}

		if(empty($current_tab)){
			$current_key = reset(array_keys($page['tabs']));
		}elseif(isset($page['tabs'][$current_tab])){
			$current_key = $current_tab;
		}else{
			wp_die('No options for this page', 'No options for this page', array('response' => 404, 'back_link' => true));
		}

		$config = $page['tabs'][$current_key]['options'];
		$sortable = isset($page['tabs'][$current_key]['sortable']) ? true : false;
	}else{
		$current_key = $key;
		$config = $page['options'];
		$sortable = isset($page['sortable']) ? true : false;
	}

	$options = isset($allOptions[$current_key]) ? $allOptions[$current_key] : array();

	$sections_count = 0;
	$structure = array();
	foreach($config as $k => $v){
		if(is_string($v) and substr($v, 0, 7) == 'section'){
			$sections_count++;
			$structure[] = $v;
		}else{
			$structure[] = '';
		}
	}

	if($sections_count === 0){
		$no_sections = true;
	}else{
		$no_sections = false;
	}
	$sections = 0;
	$cycles = 0;
	unset($k, $v);
?>


<?php if(isset($_GET['reseted']) and $_GET['reseted'] == 'true'): ?>
<div id="ait-options-reseted" class="updated settings-error">
	<p><strong><?php _e('Options reseted.', 'ait'); ?></strong></p>
</div>
<?php endif; ?>

<?php if(isset($_GET['resetedOrder']) and $_GET['resetedOrder'] == 'true'): ?>
<div id="ait-options-reseted" class="updated settings-error">
	<p><strong><?php _e('Section order reseted!', 'ait'); ?></strong></p>
</div>
<?php endif; ?>

<style type="text/css">.postbox h3{cursor:default;}</style>

<script>
	jQuery(function() {
		var $r = jQuery('#ait-options-reseted');

		if($r.length){
			setTimeout(function(){
				$r.fadeOut(400, function(){
					jQuery(this).remove();
				});
			}, 4000);
		}

		var $form = jQuery('#ait-settings-form');
		var $button = $form.find('.ait-save-options');
		var $flashMsg = jQuery('<div>', {id: 'ait-options-flash-msg'}).css('display', 'none');
		$form.before($flashMsg);

		var ajaxUrl = $form.attr('action');
		$button.click(function(){
			var $this = jQuery(this);
			var $loading = jQuery('<img style="padding-left:20px;" src="<?php echo AIT_ADMIN_URL;?>/gui/img/loading.gif" id="loading">');
			$this.after($loading);

			try{
				jQuery.each(tinyMCE.editors, function(i, ed){
					if(!ed.isHidden())
						ed.save();
				});
			}catch(e){}

			jQuery.post(ajaxUrl, $form.serialize(), function(data){
				$loading.remove();
				$flashMsg.html(data).fadeIn(200);

				setTimeout(function(){
					$flashMsg.fadeOut(400, function(){
						$flashMsg.empty();
					});
				}, 4000);
			});
			return false;
		});


		jQuery('#ait-reset-options').click(function(){
			if(confirm('<?php _e('Are you sure you want to reset these settings?', 'ait') ?>')){
				jQuery.post(window.location.href, {'resetOptions': 'true', 'key': '<?php echo base64_encode($opt); ?>'}, function(data){
					window.location.href = '<?php echo $_SERVER['REQUEST_URI'] ?>' + '&reseted=true';
				});
			}
			return false;

		});


		jQuery('#ait-reset-order').click(function(){

			var orderName = jQuery(this).data('ordername');
			var metaName = jQuery(this).data('metaname');

			if(confirm('<?php _e('Are you sure you want to reset this order?', 'ait') ?>')){
				jQuery.post(window.location.href, {'resetOrder':'true', 'key': '<?php echo base64_encode($opt); ?>', 'orderKey': orderName, 'metaKey': metaName }, function(data){
					console.log(data);
					window.location.href = '<?php echo $_SERVER['REQUEST_URI'] ?>' + '&resetedOrder=true';
				});
			}
			return false;

		});

	<?php
	// get_option('date_format');
	?>
	jQuery('.datepicker').datepicker({ dateFormat: "yy-mm-dd" });

	});
</script>

<form action="options.php" method="post" id="ait-settings-form">
<?php settings_fields($opt); ?>

<p class="submit">
	<input type="submit" class="button-primary ait-save-options" value="<?php _e('Save options', 'ait') ?>" />

	<span class="ait-form-actions">
		<?php if($opt == AIT_OPTIONS_KEY): ?> <a href="<?php echo admin_url('admin.php?page=ait-admin-skins&tab=create-new-skin'); ?>" id="ait-new-skin-link" title="<?php _e('Make new skin from these options.', 'ait') ?>"><?php _e('Make new skin', 'ait') ?></a><?php endif; ?>
		<a href="#" id="ait-reset-options" title="<?php _e('Reset options to default values from config file.', 'ait') ?>"><?php _e('Reset Options', 'ait') ?></a>
	</span>
</p>

<?php aitSortableSections($sortable, $options, $config, $current_key, $opt); ?>


<?php if($no_sections == true or $structure[0] == ''): /* doesn't have any section, print normal form table */ ?>
	<table class="form-table">
<?php else: ?>
	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">
<?php endif; ?>


<?php foreach($config as $key => $value):
	if($structure[$cycles] != ''):
		$sections++;
		if($structure[$cycles] != ''){
			$index = $cycles;
		}

		if($structure[0] == '' and $sections == 1):
			?>
</table>

<div id="dashboard-widgets-wrap">
	<div id="dashboard-widgets" class="metabox-holder">
<?php endif; ?>
		<div class="postbox-container" style="width:100%;">
			<div  class="meta-box-sortables">
				<div class="postbox">
					<h3><span><?php echo $key ?></span></h3>
					<div class="inside">
						<table class="form-table">
	<?php else: ?>
		<tr valign="top" id="ait-<?php echo $key; ?>-option">
<?php



/** ***** Label ***** */
?>
			<?php if(isset($value['label'])): ?>
			<th scope="row" width="25%" class="ait-form-table-label">
				<?php if($value['type'] == 'checkbox' || $value['type'] == 'radio' || $value['type'] == 'clone'): ?>
				<?php echo esc_html($value['label']) ?>
				<?php else: ?>
				<label for="ait-<?php echo $key ?>"><?php echo esc_html($value['label']) ?></label>
					<?php if(isset($value['help'])): ?>
					<a href="#" class="ait-form-table-help-label">(?)
						<span class="ait-form-table-help-tooltip">
							<?php echo esc_html($value['help']) ?>
						</span>
					</a>
					<?php endif; ?>
				<?php endif; ?>
			</th>
			<?php endif; ?>
<?php



/* ***** Textarea ***** */
?>
			<?php if($value['type'] == 'textarea'): ?>
			<td>
				<textarea id="ait-<?php echo $key; ?>" name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}]"); ?>" rows="7" cols="50"><?php echo isset($options[$key]) ? esc_textarea($options[$key]) : $config[$key]['default']; ?></textarea>
			</td>
			<?php endif; ?>
<?php



/* ***** Custom CSS textarea ***** */
?>
			<?php if($value['type'] == 'custom-css'): ?>
			<td>
				<textarea id="ait-<?php echo $key; ?>" name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}]"); ?>" rows="20" cols="80" style="font-family:Consolas, 'Courier New', monospace; width:99.5%"><?php echo isset($options[$key]) ? esc_textarea($options[$key]) : $config[$key]['default']; ?></textarea>
			</td>
			<?php endif; ?>
<?php

/* ***** Wysiwyg ***** */
?>
			<?php if($value['type'] == 'wysiwyg' or $value['type'] == 'editor'): // 'editor' is an alias
				if(function_exists('wp_editor')){ // WP 3.3

					$wpEditorContent = isset($options[$key]) ? $options[$key] : $config[$key]['default'];
					$wpEditorArgs = array(
						'textarea_name' => esc_attr("{$opt}[{$current_key}][{$key}]"),
						'textarea_rows' => '5',
						'wpautop' => false,
					);
					wp_editor($wpEditorContent, "ait-{$key}", $wpEditorArgs);

				}else{
					add_action( 'admin_print_footer_scripts', 'wp_tiny_mce', 25 );
					add_action('admin_print_footer_scripts', 'aitMultipleWysiwygs', 99);

			?>
			<td>
				<div class="ait-form-table-wysiwyg">

					<textarea id="ait-<?php echo $key; ?>" name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}]"); ?>" rows="7" cols="50"><?php echo isset($options[$key]) ? esc_textarea($options[$key]) : $config[$key]['default']; ?></textarea>
				</div>
			</td>
			<?php } endif; ?>
<?php


/* ***** Input: Color picker ***** */
?>
			<?php if($value['type'] == 'colorpicker'): ?>
			<td>
				<input type="text" class="ait-colorpicker" id="ait-<?php echo $key; ?>" name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}]"); ?>" value="<?php echo isset($options[$key]) ? esc_attr($options[$key]) : $config[$key]['default']; ?>" class="regular-text">
      </td>
			<?php endif; ?>
<?php

/* ***** Input: Transparent Color picker ***** */
?>
			<?php if($value['type'] == 'transparent'): ?>
			<td>
			 <label for="ait-<?php echo $key; ?>">Color: </label>
       <input type="text" class="ait-colorpicker color" id="ait-<?php echo $key; ?>" name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}][color]"); ?>" value="<?php echo isset($options[$key]['color']) ? esc_attr($options[$key]['color']) : $config[$key]['color']; ?>" class="regular-text">
       <label for="ait-<?php echo $key; ?>-opacity"> Alpha: </label>

       <select name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}][opacity]") ?>" id="ait-<?php echo $key; ?>-opacity">
       <?php
       $i=0;
       $comp = 0;
       if(isset($options[$key]['opacity'])){
        $comp = esc_attr($options[$key]['opacity']);
       } else {
        $comp = $config[$key]['opacity'];
       }

       while($i<=100){
        if($i == 100*$comp){
          echo('<option id="ait-'.$key.'-opacity" value="'.($i/100).'" selected="selected">'.$i.' %</option>');
        } else {
          echo('<option id="ait-'.$key.'-opacity" value="'.($i/100).'">'.$i.' %</option>');
        }
        $i++;
       }
       ?>
       </select>
			</td>
			<?php endif; ?>
<?php

/* ***** Input: text ***** */
?>
			<?php if($value['type'] == 'text'): ?>
			<td>
				<input type="text" id="ait-<?php echo $key; ?>" name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}]"); ?>" value="<?php echo isset($options[$key]) ? esc_attr($options[$key]) : $config[$key]['default']; ?>" class="regular-text">
			</td>
			<?php endif; ?>
<?php



/* ***** Input: text ***** */
?>
			<?php if($value['type'] == 'date'): ?>
			<td>
				<input type="text" id="ait-<?php echo $key; ?>" name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}]"); ?>" value="<?php echo isset($options[$key]) ? esc_attr($options[$key]) : $config[$key]['default']; ?>" class="datepicker">
			</td>
			<?php endif; ?>
<?php



/* ***** Input: select-image ***** */
?>
			<?php if($value['type'] == 'image-url'): ?>
			<td>
				<input type="text" id="ait-<?php echo $key; ?>" name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}]"); ?>" value="<?php echo isset($options[$key]) ? esc_textarea($options[$key]) : $config[$key]['default']; ?>" class="regular-text">
				<input type="button" value="Select Image" class="button-secondary media-select" id="ait-<?php echo $key; ?>_selectMedia" name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}]"); ?>">
			</td>
			<?php endif; ?>

<?php



/* ***** Input: order ***** */
?>
			<?php if($value['type'] == 'order'): ?>
			<td>
				<?php
				$orderKeys = isset($options[$key]) ? $options[$key] : array();
				$orderSections = $config[$key]['default'];
				aitRenderOrderType($orderKeys, $orderSections, $current_key, $key, $opt, isset($value['meta']) ? $value['meta'] : '');
				?>
			</td>
			<?php endif; ?>
<?php


/* ***** Select ***** */
?>
			<?php if($value['type'] == 'select'): ?>
			<td>
				<select name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}]"); ?>" id="ait-<?php echo $key; ?>">
					<?php foreach($config[$key]['default'] as $k => $v): ?>
					<option value="<?php echo esc_attr($k); ?>" <?php if(isset($options[$key])): selected($options[$key], $k); endif; ?>><?php echo esc_html($v['label']); ?></option>
					<?php endforeach; ?>
				</select>
			</td>
			<?php endif; ?>
<?php


/* ***** Checkbox ***** */
?>
			<?php if($value['type'] == 'checkbox'): ?>
			<td>
				<?php foreach($config[$key]['default'] as $k => $v): ?>
				<label for="ait-<?php echo "{$key}-{$k}"; ?>">
					<input type="checkbox" id="ait-<?php echo "{$key}-{$k}"; ?>" name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}][{$k}]"); ?>" value="<?php echo esc_attr($k); ?>" <?php  if(isset($options[$key][$k])):checked($options[$key][$k], $k); endif;?> style="margin-right:10px;">
					<?php echo esc_html($v['label']); ?>
				</label>
				<br>
				<?php endforeach; ?>
			</td>
			<?php endif; ?>
<?php



/* ***** Radio ***** */
?>
			<?php if($value['type'] == 'radio'): ?>
			<td>
				<?php foreach($config[$key]['default'] as $k => $v): ?>
				<label for="ait-<?php echo "{$key}-{$k}"; ?>">
					<input type="radio" id="ait-<?php echo "{$key}-{$k}"; ?>" name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}]"); ?>" value="<?php echo esc_attr($k); ?>" <?php  if(isset($options[$key])): checked($options[$key], $k); endif;?> style="margin-right:10px;">
					<?php echo esc_html($v['label']); ?>
				</label>
				<br>
				<?php endforeach; ?>
			</td>
			<?php endif; ?>
<?php



/* ***** Categories dropdown menu ***** */
?>
			<?php if($value['type'] == 'dropdown-categories'): ?>
			<td>
				<?php wp_dropdown_categories(array(
					'name' => esc_attr("{$opt}[{$current_key}][{$key}]"),
					'id' => 'ait-' . esc_attr($key),
					'taxonomy' => 'ait-' . $config[$key]['default'] . '-category',
					'walker' => new AitCategoryDropdownWalker,
					'selected' => $options[$key],
					'show_option_all' => __('All', 'ait'),
					'show_option_none' => __('None', 'ait'),
					'hide_empty' => 0,
					'show_count' => 1
				)); ?>
			</td>
			<?php endif; ?>
<?php


/* ***** Categories posts dropdown menu ***** */
?>
			<?php if($value['type'] == 'dropdown-categories-posts'): ?>
			<td>
				<?php wp_dropdown_categories(array(
					'name' => esc_attr("{$opt}[{$current_key}][{$key}]"),
					'id' => 'ait-' . esc_attr($key),
					'taxonomy' => 'category',
					'walker' => new AitCategoryDropdownWalker,
					'selected' => $options[$key],
					'show_option_all' => __('All', 'ait'),
					'show_option_none' => __('None', 'ait'),
					'hide_empty' => 0,
					'show_count' => 1
				)); ?>
			</td>
			<?php endif; ?>
<?php


/* ***** Fonts dropdown menu ***** */
?>
			<?php if($value['type'] == 'font'): ?>
			<td>
				<?php echo aitFontsDropdown(
						esc_attr("{$opt}[{$current_key}][{$key}]"),
						'ait-' . esc_attr($key),
						$options[$key]['font']
				);
			?>
			</td>
			<?php endif; ?>
<?php



/* ***** js-animations ***** */
?>
			<?php if($value['type'] == 'js-animations'):
				$animations = array('linear', 'easeInSine', 'easeOutSine', 'easeInOutSine', 'easeInQuad', 'easeOutQuad', 'easeInOutQuad', 'easeInCubic', 'easeOutCubic', 'easeInOutCubic', 'easeInQuart', 'easeOutQuart', 'easeInOutQuart', 'easeInQuint', 'easeOutQuint', 'easeInOutQuint', 'easeInExpo', 'easeOutExpo', 'easeInOutExpo', 'easeInCirc', 'easeOutCirc', 'easeInOutCirc', 'easeInElastic', 'easeOutElastic', 'easeInOutElastic', 'easeInBack', 'easeOutBack', 'easeInOutBack', 'easeInBounce', 'easeOutBounce', 'easeInOutBounce');

				$out = array();
				foreach($animations as $a){
					$s = selected($a, $options[$key], false);
					$out[] = "<option value='{$a}' {$s}>{$a}</option>";
				}
			?>
			<td>
				<select name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}]"); ?>" id="ait-<?php echo $key; ?>">
					<?php echo implode('', $out); ?>
				</select>
			</td>
			<?php endif; ?>

<?php
/* ***** Sliders dropdown menu ***** */
?>
			<?php if($value['type'] == 'dropdown-sliders'):
				$sliders = array('anything' => 'Anything slider', 'revolution' => 'Revolution slider');
				$out = array();
				foreach($sliders as $k => $a){
					$s = selected($k, $options[$key], false);
					$out[] = "<option value='{$k}' {$s}>{$a}</option>";
				}
			?>
			<td>
				<select name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}]"); ?>" id="ait-<?php echo $key; ?>">
					<?php echo implode('', $out); ?>
				</select>
			</td>
			<?php endif; ?>


<?php
/* ***** Sliders dropdown menu ***** */
?>
			<?php if($value['type'] == 'dropdown-slider-aliases'):
				global $wpdb;
				$aliases = array();	// tu natiahnut aliasy

				$aliasDb = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."revslider_sliders" );
				foreach ($aliasDb as $alias) {
					array_push($aliases, $alias->alias);
				}

				$out = array();
				foreach($aliases as $a){
					$s = selected($a, $options[$key], false);
					$out[] = "<option value='{$a}' {$s}>{$a}</option>";
				}
			?>
			<td>
				<?php if(!empty($out)){ ?>
				<select name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}]"); ?>" id="ait-<?php echo $key; ?>">
					<?php echo implode('', $out); ?>
				</select>
				<?php } else { ?>
					<select name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}]"); ?>" id="ait-<?php echo $key; ?>">
						<?php $s = selected('null', $options[$key], false);
						echo("<option value='null' {$s}>No sliders found</option>"); ?>
					</select>
				<?php } ?>
			</td>
			<?php endif; ?>

<?php
/* ***** ICarousel aliases ***** */
?>
			<?php if($value['type'] == 'icarousel-aliases'):
				$out = array();
				if(function_exists('icarousel_meta_slideshow')){
					$aliases = icarousel_get_option( 'icarousel_added_slideshows' );
					foreach($aliases as $a){
						$b = sanitize_title($a);
						$s = selected($b, $options[$key], false);
						$out[] = "<option value='{$b}' {$s}>{$a}</option>";
					}
				}
			?>
			<td>
				<?php if(!empty($out)){ ?>
				<select name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}]"); ?>" id="ait-<?php echo $key; ?>">
					<?php echo implode('', $out); ?>
				</select>
				<?php } else { ?>
					<select name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}]"); ?>" id="ait-<?php echo $key; ?>">
						<?php $s = selected('null', $options[$key], false);
						echo("<option value='null' {$s}>No sliders found</option>"); ?>
					</select>
				<?php } ?>
			</td>
			<?php endif; ?>

<?php
/* ***** Multiple categories select ***** */
?>
			<?php if($value['type'] == 'multiple-category-select'):
				$category = 'category';
				$cats = get_categories( array( 'taxonomy' => $category, 'orderby' => 'menu_order', 'order' => 'ASC', 'exclude' => 1));
			?>
			<td>
				<?php if(!empty($cats)){
					if(!isset($options[$key])){
						$options[$key] = array();
					}
				?>

	    		<ul class="cat-checklist <?php echo($category);?>-checklist" style="border-color: #DFDFDF; height: 8em">
	    	   	<?php foreach($cats as $cat){ ?>
	          		<li id="<?php echo($category);?>-<?php echo($cat->term_id);?>" class="popular-category" style="margin-bottom: 0px">
	            		<?php if(in_array($cat->term_id, $options[$key])){ ?>
	            		<label class="selectit"><input value="<?php echo($cat->term_id);?>" type="checkbox" name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}][]"); ?>" id="ait-<?php echo($key);?>" checked="yes"> <?php echo($cat->name);?></label>
	            		<?php } else { ?>
	            		<label class="selectit"><input value="<?php echo($cat->term_id);?>" type="checkbox" name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}][]"); ?>" id="ait-<?php echo($key);?>"> <?php echo($cat->name);?></label>
	            		<?php } ?>
	          		</li>
	         	<?php } ?>
	       		</ul>
	       		<?php } else { ?>
					No categories found, please create some
				<?php } ?>
	      	</td>
			<?php endif; ?>

<?php


/**
 ***** Cloning *****
 */
?>
			<?php if($value['type'] == 'clone' or $value['type'] == 'custom-css-vars'): ?>
			<td colspan="2" widt="75%">
				<script type="text/javascript" src="<?php echo AIT_ADMIN_URL; ?>/gui/jquery.sheepItPlugin.js"></script>
				<script type="text/javascript">
					jQuery(function(){
						var aitCloneForm = jQuery('#ait-clone-form').sheepIt({
							separator: '',
							allowRemoveLast: false,
							allowRemoveCurrent: true,
							allowRemoveAll: false,
							allowAdd: true,
							allowAddN: false,
							removeLastConfirmation: true,
							removeCurrentConfirmation: true,
							maxFormsCount: 0,
							minFormsCount: 0,
							iniFormsCount: 0,
							pregeneratedForms: [<?php
							if(isset($options[$key])):
								foreach($options[$key] as $i => $j):
									echo "'ait-clone-form-pregenerated-{$i}', ";
								endforeach;
							unset($i, $j); endif; ?>]
						});

						var $checkboxItems = jQuery('#ait-clone-form').find('.pregenerated-checkboxes');

						$checkboxItems.each(function(){
							var $that = jQuery(this);
							var $inputs = $that.find('input[type=checkbox]');
							var $hidden = $that.find('input[type=hidden]');
							var $checked = $that.find('input[type=checkbox]:checked');
							if($checked.length > 0){
								$hidden.remove();
							}else{
								$hidden.appendTo($that);
							}
							$inputs.click(function(){
								$checked = $that.find('input[type=checkbox]:checked');
								if($checked.length > 0){
									$hidden.remove();
								}else{
									$hidden.appendTo($that);
								}
							});

						});
					});
				</script>

				<div id="ait-clone-form">
<?php




/* ***** Pregenerated items ***** */
?>
				<?php if(isset($options[$key])): /* is there any values or user deleted all? */ ?>

				<?php $c = $config[$key]['default'][0]; /* use only sub array that we need */ ?>

				<div id="ait-clone-form-pregenerated">
				<?php foreach($options[$key] as $clone_key => $clone_val): ?>
				<table id="ait-clone-form-pregenerated-<?php echo $clone_key; ?>" class="ait-clone-form-table">

	<?php /* ***** Counter label ***** */ ?>
					<?php if(isset($value['label-counter'])): ?>
					<tr>
						<th class="ait-clone-form-table-counter-label" colspan="3">
							<h3><?php echo esc_html($value['label-counter']) ?><span id="ait-clone-form_label"></span></h3>
						</th>
					</tr>
					<?php endif; ?>

					<?php if($value['type'] == 'custom-css-vars'): ?>
						<tr>
						<?php foreach($clone_val as $k => $v): ?>
							<?php /* ***** Input: text ***** */ ?>
							<?php if($c[$k]['type'] == 'text'): ?>
								<td>
									<?php if(isset($c[$k]['label'])): ?><label for="ait-clone-<?php echo "{$clone_key}-{$k}"; ?>"><?php echo esc_html($c[$k]['label']) ?></label><?php endif; ?>
									<input type="text" id="ait-clone-<?php echo "{$clone_key}-{$k}"; ?>" name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}][{$clone_key}][{$k}]"); ?>" value="<?php echo esc_attr($options[$key][$clone_key][$k]); ?>" style="width:20em;font-weight:bold;font-family:Consolas, 'Courier New', monospace;" class="regular-text">
								</td>
							<?php endif; ?>
							<?php /* ***** Input: image-url ***** */ ?>
							<?php if($c[$k]['type'] == 'image-url'): ?>
								<td>
									<?php if(isset($c[$k]['label'])): ?><label for="ait-clone-<?php echo "{$clone_key}-{$k}"; ?>"><?php echo esc_html($c[$k]['label']) ?></label></label><?php endif; ?>
									<input type="text" id="ait-clone-<?php echo "{$clone_key}-{$k}"; ?>" name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}][{$clone_key}][{$k}]"); ?>" value="<?php echo esc_attr($options[$key][$clone_key][$k]); ?>" class="regular-text" style="width:35em;">
									<input type="button" value="Select Image" class="media-select" id="ait-clone-<?php echo "{$clone_key}-{$k}"; ?>_selectMedia">
								</td>
							<?php endif; ?>
						<?php endforeach;?>
						<td colspan="2" class="ait-clone-form-table-delete">
							<a id="ait-clone-form_remove_current" class="button-secondary"><?php _e('Delete', 'ait') ?></a>
						</td>
						</tr>
					<?php else:?>


				<?php foreach($clone_val as $k => $v): ?>
					<tr>
						<?php /* ***** Label ***** */ ?>
						<?php if(isset($c[$k]['label'])): ?>
						<th scope="row">
							<?php if($c[$k]['type'] == 'checkbox' || $c[$k]['type'] == 'radio'): ?>
							<?php esc_html($c[$k]['label']) ?>
							<?php else: ?>
							<label for="ait-clone-<?php echo "{$clone_key}-{$k}"; ?>"><?php echo esc_html($c[$k]['label']) ?></label>
								<?php if(isset($c[$k]['help'])): ?>
								<a href="#" class="ait-form-table-help-label">(?)
									<span class="ait-form-table-help-tooltip">
										<?php echo esc_html($c[$k]['help']) ?>
									</span>
								</a>
								<?php endif; ?>
							<?php endif;?>
						</th>
						<?php endif; ?>

						<?php /* ***** Textarea ***** */ ?>
						<?php if($c[$k]['type'] == 'textarea'): ?>
						<td>
							<textarea id="ait-clone-<?php echo "{$clone_key}-{$k}"; ?>" name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}][{$clone_key}][{$k}]"); ?>" rows="7" cols="50"><?php echo esc_textarea($options[$key][$clone_key][$k]); ?></textarea>
						</td>
						<?php endif; ?>

						<?php /* ***** Input: text ***** */ ?>
						<?php if($c[$k]['type'] == 'text'): ?>
						<td>
							<input type="text" id="ait-clone-<?php echo "{$clone_key}-{$k}"; ?>" name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}][{$clone_key}][{$k}]"); ?>" value="<?php echo esc_attr($options[$key][$clone_key][$k]); ?>" class="regular-text">
						</td>
						<?php endif; ?>

						<?php /* ***** Input: image-url ***** */ ?>
						<?php if($c[$k]['type'] == 'image-url'): ?>
						<td>
							<input type="text" id="ait-clone-<?php echo "{$clone_key}-{$k}"; ?>" name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}][{$clone_key}][{$k}]"); ?>" value="<?php echo esc_attr($options[$key][$clone_key][$k]); ?>" class="regular-text">
							<input type="button" value="Select Image" class="media-select" id="ait-clone-<?php echo "{$clone_key}-{$k}"; ?>_selectMedia">
						</td>
						<?php endif; ?>

						<?php /* ***** Select ***** */ ?>
						<?php if($c[$k]['type'] == 'select'): ?>
						<td>
							<select name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}][{$clone_key}][{$k}]"); ?>" id="ait-clone-<?php echo "{$clone_key}-{$k}"; ?>">
								<?php foreach($c[$k]['default'] as $x => $y): ?>
								<option value="<?php echo esc_attr($x); ?>" <?php selected($options[$key][$clone_key][$k], $x); ?>><?php echo esc_html($y['label']); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
						<?php endif; ?>

						<?php /* ***** Checkbox ***** */ ?>
						<?php if($c[$k]['type'] == 'checkbox'): ?>
						<td class="pregenerated-checkboxes">
							<?php foreach($c[$k]['default'] as $x => $y): ?>
							<label for="ait-clone-<?php echo "{$key}-{$clone_key}-{$k}-{$x}"; ?>">
								<input type="checkbox" id="ait-clone-<?php echo "{$key}-{$clone_key}-{$k}-{$x}"; ?>" name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}][{$clone_key}][{$k}][{$x}]"); ?>" value="<?php echo esc_attr($x); ?>" <?php  if(isset($options[$key][$clone_key][$k][$x])): checked($options[$key][$clone_key][$k][$x], $x); endif;?> style="margin-right:10px;">
								<?php echo esc_html($y['label']); ?>
							</label>
							<br>
							<?php endforeach; ?>
							<input type="hidden" name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}][{$clone_key}][{$k}]"); ?>">
						</td>
						<?php endif; ?>

						<?php /* ***** Radio ***** */ ?>
						<?php if($c[$k]['type'] == 'radio'): ?>
						<td>
							<?php foreach($c[$k]['default'] as $x => $y): ?>
							<label for="ait-clone-<?php echo "{$key}-{$clone_key}-{$k}-{$x}"; ?>">
								<input type="radio" id="ait-clone-<?php echo "{$key}-{$clone_key}-{$k}-{$x}"; ?>" name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}][{$clone_key}][{$k}]"); ?>" value="<?php echo esc_attr($x); ?>" <?php  checked($options[$key][$clone_key][$k], $x); ?> style="margin-right:10px;">
								<?php echo esc_html($y['label']); ?>
							</label>
							<br>
							<?php endforeach; ?>
						</td>
						<?php endif; ?>

						<?php /* ***** Categories dropdown ***** */ ?>
						<?php if($c[$k]['type'] == 'dropdown-categories'): ?>
						<td>
							<?php wp_dropdown_categories(array(
								'name' => esc_attr("{$opt}[{$current_key}][{$key}][{$clone_key}][{$k}]"),
								'id' => 'ait-' . esc_attr("{$clone_key}-{$k}"),
								'taxonomy' => 'ait-' . $c[$k]['default'] . '-category',
								'walker' => new AitCategoryDropdownWalker,
								'selected' => $options[$key][$clone_key][$k],
								'hide_empty' => 0,
								'show_count' => 1
							)); ?>
						</td>
						<?php endif; ?>

						<?php /* ***** Categories posts dropdown ***** */ ?>
						<?php if($c[$k]['type'] == 'dropdown-categories-posts'): ?>
						<td>
							<?php wp_dropdown_categories(array(
								'name' => esc_attr("{$opt}[{$current_key}][{$key}][{$clone_key}][{$k}]"),
								'id' => 'ait-' . esc_attr("{$clone_key}-{$k}"),
								'taxonomy' => 'category',
								'walker' => new AitCategoryDropdownWalker,
								'selected' => $options[$key][$clone_key][$k],
								'hide_empty' => 0,
								'show_count' => 1
							)); ?>
						</td>
						<?php endif; ?>

						<?php /* ***** Fonts dropdown ***** */ ?>
						<?php if($c[$k]['type'] == 'font'): ?>
						<td>
							<?php echo aitFontsDropdown(
									esc_attr("{$opt}[{$current_key}][{$key}][{$clone_key}][{$k}]"),
									'ait-' . esc_attr("{$clone_key}-{$k}"),
									$options[$key][$clone_key][$k]
							);
						?>
						</td>
						<?php endif; ?>
					</tr>
					<?php  endforeach; ?>
					<?php endif; ?>

					<?php if($value['type'] != 'custom-css-vars'): ?>
					<?php /* ***** Delete button ***** */ ?>
					<tr>
						<td colspan="2" class="ait-clone-form-table-delete">
							<a id="ait-clone-form_remove_current" class="button-secondary">Delete</a>
						</td>
					</tr>
					<?php endif; ?>

				</table><!-- /#ait-clone-form-pregenerated-#index# -->
				<?php endforeach; ?>
				</div> <!-- /ait-clone-form-pregenerated -->
				<?php unset($c); endif; /* enf of isset($options[$key])) */ ?>
<?php








/* ***** Clone template */
?>
				<?php foreach($config[$key]['default'] as $clone_key => $clone_val): ?>
				<table id="ait-clone-form_template" class="ait-clone-form-table">
					<?php if(isset($value['label-counter'])): ?>
					<tr>
						<th class="ait-clone-form-table-counter-label" colspan="2">
							<h3><?php echo esc_html($value['label-counter']) ?><span id="ait-clone-form_label"></span></h3>
						</th>
					</tr>
					<?php endif; ?>



					<?php if($value['type'] == 'custom-css-vars'): ?>
						<tr>
						<?php foreach($clone_val as $k => $v): ?>
							<?php /* ***** Input: text ***** */ ?>
							<?php if($v['type'] == 'text'): ?>
								<td>
									<?php if(isset($v['label'])): ?><label for="ait-clone-<?php echo "#index#-{$k}"; ?>"><?php echo esc_html($v['label']) ?></label><?php endif; ?>
									<input type="text" id="ait-clone-<?php echo "#index#-{$k}"; ?>" name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}][#index#][{$k}]"); ?>" value="" style="width:20em;font-weight:bold;font-family:Consolas, 'Courier New', monospace;" class="regular-text">
								</td>
							<?php endif; ?>
							<?php /* ***** Input: image-url ***** */ ?>
							<?php if($v['type'] == 'image-url'): ?>
								<td>
									<?php if(isset($v['label'])): ?><label for="ait-clone-<?php echo "#index#-{$k}"; ?>"><?php echo esc_html($v['label']) ?></label><?php endif; ?>
									<input type="text" id="ait-clone-<?php echo "#index#-{$k}"; ?>" name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}][#index#][{$k}]"); ?>" value="" class="regular-text" style="width:35em;">
									<input type="button" value="Select Image" class="media-select" id="ait-clone-<?php echo "#index#-{$k}"; ?>_selectMedia">
								</td>
							<?php endif; ?>
						<?php endforeach;?>
						<td class="ait-clone-form-table-delete">
							<a id="ait-clone-form_remove_current" class="button-secondary"><?php _e('Delete', 'ait') ?></a>
						</td>
						</tr>
					<?php else:?>



					<?php foreach($clone_val as $k => $v): ?>
					<tr>
						<?php /* ***** Label ***** */ ?>
						<th scope="row">
							<?php if($v['type'] == 'checkbox' || $v['type'] == 'radio'): ?>
							<?php esc_html($v['label']) ?>
							<?php else: ?>
							<label for="ait-clone-<?php echo "#index#-{$k}"; ?>"><?php echo esc_html($v['label']) ?></label>
							<?php endif; ?>
						</th>

						<?php /* ***** Textarea ***** */ ?>
						<?php if($v['type'] == 'textarea'): ?>
						<td>
							<textarea id="ait-clone-<?php echo "#index#-{$k}"; ?>" name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}][#index#][{$k}]"); ?>" rows="7" cols="50"></textarea>
						</td>
						<?php endif; ?>

						<?php /* ***** Input: text ***** */ ?>
						<?php if($v['type'] == 'text'): ?>
						<td>
							<input type="text" id="ait-clone-<?php echo "#index#-{$k}"; ?>" name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}][#index#][{$k}]"); ?>" value="" class="regular-text">
						</td>
						<?php endif; ?>

						<?php /* ***** Input: image-url ***** */ ?>
						<?php if($v['type'] == 'image-url'): ?>
						<td>
							<input type="text" id="ait-clone-<?php echo "#index#-{$k}"; ?>" name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}][#index#][{$k}]"); ?>" value="" class="regular-text">
							<input type="button" value="Select Image" class="media-select" id="ait-clone-<?php echo "#index#-{$k}"; ?>_selectMedia">
						</td>
						<?php endif; ?>

						<?php /* ***** Select ***** */ ?>
						<?php if($v['type'] == 'select'): ?>
						<td>
							<select name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}][#index#][{$k}]"); ?>" id="ait-clone-<?php echo "#index#-{$k}"; ?>">
								<?php foreach($v['default'] as $x => $y): ?>
								<option value="<?php echo esc_attr($x); ?>"><?php echo esc_html($y['label']); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
						<?php endif; ?>

						<?php /* ***** Checkbox ***** */ ?>
						<?php if($v['type'] == 'checkbox'): ?>
						<td>
							<?php foreach($v['default'] as $x => $y): ?>
							<label for="ait-clone-<?php echo "{$key}-#index#-{$k}-{$x}"; ?>">
								<input type="checkbox" id="ait-clone-<?php echo "{$key}-#index#-{$k}-{$x}"; ?>" name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}][#index#][{$k}][{$x}]"); ?>" <?php if(isset($y['checked'])): echo 'checked'; endif; ?> value="<?php echo esc_attr($x); ?>" style="margin-right:10px;">
								<?php echo esc_html($y['label']); ?>
							</label>
							<br>
							<?php endforeach; ?>
						</td>
						<?php endif; ?>

						<?php /* ***** Radio ***** */ ?>
						<?php if($v['type'] == 'radio'): ?>
						<td>
							<?php foreach($v['default'] as $x => $y): ?>
							<label for="ait-clone-<?php echo "{$key}-#index#-{$k}-{$x}"; ?>">
								<input type="radio" id="ait-clone-<?php echo "{$key}-#index#-{$k}-{$x}"; ?>" name="<?php echo esc_attr("{$opt}[{$current_key}][{$key}][#index#][{$k}]"); ?>" <?php if(isset($y['checked'])): echo 'checked'; endif; ?> value="<?php echo esc_attr($x); ?>" style="margin-right:10px;">
								<?php echo esc_html($y['label']); ?>
							</label>
							<br>
							<?php endforeach; ?>
						</td>
						<?php endif; ?>

						<?php /* ***** Categories dropdown ***** */ ?>
						<?php if($v['type'] == 'dropdown-categories'): ?>
						<td>
							<?php wp_dropdown_categories(array(
								'name' => esc_attr("{$opt}[{$current_key}][{$key}][#index#][{$k}]"),
								'id' => 'ait-' . esc_attr("#index#-{$k}"),
								'taxonomy' => 'ait-' . $v['default'] . '-category',
								'walker' => new AitCategoryDropdownWalker,
								'hide_empty' => 0,
								'show_count' => 1
							)); ?>
						</td>
						<?php endif; ?>

						<?php /* ***** Categories posts dropdown ***** */ ?>
						<?php if($v['type'] == 'dropdown-categories-posts'): ?>
						<td>
							<?php wp_dropdown_categories(array(
								'name' => esc_attr("{$opt}[{$current_key}][{$key}][#index#][{$k}]"),
								'id' => 'ait-' . esc_attr("#index#-{$k}"),
								'taxonomy' => 'category',
								'walker' => new AitCategoryDropdownWalker,
								'hide_empty' => 0,
								'show_count' => 1
							)); ?>
						</td>
						<?php endif; ?>

						<?php /* ***** Fonts dropdown ***** */ ?>
						<?php if($v['type'] == 'font'): ?>
						<td>
							<?php echo aitFontsDropdown(
									esc_attr("{$opt}[{$current_key}][{$key}][#index#][{$k}]"),
									'ait-' . esc_attr("#index#-{$k}")
							);
						?>
						</td>
					<?php endif; ?>
					</tr>
					<?php endforeach; ?>
				<?php endif; /* if custom-css-vars */ ?>

				<?php if($value['type'] != 'custom-css-vars'): ?>
					<?php /* ***** Delete button ***** */ ?>
					<tr>
						<td colspan="2" class="ait-clone-form-table-delete">
							<a id="ait-clone-form_remove_current" class="button-secondary"><?php _e('Delete', 'ait') ?></a>
						</td>
					</tr>
				<?php endif; ?>
				</table><!-- /#ait-clone-form_template -->
				<?php break; /* break - generate only once */ endforeach; ?>

					<!-- No forms template -->
					<div id="ait-clone-form_noforms_template"><?php _e('No options.', 'ait') ?></div>
					<!-- /No forms template-->

				<!-- Controls -->
				<div id="ait-clone-form_controls">
					<a id="ait-clone-form_add" class="button-primary"><?php _e('Add item', 'ait') ?></a>
					<a id="ait-clone-form_remove_last" class="button-secondary"><?php _e('Remove last item', 'ait') ?></a>
					<a id="ait-clone-form_remove_all" class="button-secondary"><?php _e('Remove all items', 'ait') ?></a>
					<span id="ait-clone-form_add_n">
						<input id="ait-clone-form_add_n_input" type="text" size="4">
						<a id="ait-clone-form_add_n_button" class="button-primary"><?php _e('Add', 'ait') ?></a>
					</span>
				</div>
				<!-- /Controls -->
			</div> <!-- /#ait-clone-form -->
			</td>
			<?php endif; /* end of $value['type'] == 'clone') */ ?>
		</tr>

	<?php endif; ?>

	<?php if(((isset($structure[$cycles + 1]) and $structure[$cycles + 1] != '') and $sections != 0) or ($sections == $sections_count and !isset($structure[$cycles + 1])) and $sections_count != 0): ?>
						</table>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php $cycles++; ?>
<?php endforeach; ?>

<?php if($no_sections == true): ?>
	</table>
<?php else: ?>
			</div> <!-- /#dashboard-widgets -->
		<div class="clear"></div>
	</div> <!-- /#dashboard-widgets-wrap -->
<?php endif; ?>


<?php
	unset($allOptions[$current_key]);

	foreach($allOptions as $k => $v){
		foreach($v as $id => $o){
			if(!is_array($o)){ // cloned items
				?>

<input type="hidden" name="<?php echo esc_attr("{$opt}[{$k}][{$id}]"); ?>" value="<?php echo esc_attr($o); ?>"><?php
			}else{
				foreach($o as $x => $y){
					if(!is_array($y)){
						?>

<input type="hidden" name="<?php echo esc_attr("{$opt}[{$k}][{$id}][{$x}]"); ?>" value="<?php echo esc_attr($y); ?>"><?php
					}else{
						foreach($y as $u => $z){
							if(!is_array($z)){
								?>

<input type="hidden" name="<?php echo esc_attr("{$opt}[{$k}][{$id}][{$x}][{$u}]"); ?>" value="<?php echo esc_attr($z); ?>"><?php
							}else{
								foreach($z as $i => $j){
									?>

<input type="hidden" name="<?php echo esc_attr("{$opt}[{$k}][{$id}][{$x}][{$u}][{$i}]"); ?>" value="<?php echo esc_attr($j); ?>"><?php
								}
							}
						}
					}
				}
			}
		}
	}
	unset($v, $id, $o, $x, $y, $u, $z); // for sure
?>

	<p class="submit">
		<input type="submit" class="button-primary ait-save-options" value="<?php _e('Save options', 'ait') ?>" />
	</p>
</form>

<?php
}



/**
 * Enables mulitmile TinyMCE editors on one page
 * @return void
 */
function aitMultipleWysiwygs()
{
	?>
	<script type="text/javascript">
		jQuery(function($){
			var i=1;
			$('.ait-form-table-wysiwyg textarea').each(function(e){
				var id = $(this).attr('id');
				tinyMCE.execCommand('mceAddControl', false, id);
			});
		});
	</script><?php
}



/**
 *
 * @param bool $sortable If options on given option page are sortable
 * @param array $options Theme options from DB
 * @param array $config Config options from config file
 * @param type $currentKey Key of current section
 * @param type $opt Option key, constant: AIT_OPTIONS_KEY
 */
function aitSortableSections($sortable, $options, $config, $currentKey, $opt)
{
	if($sortable and isset($options['sectionsOrder'])): ?>
	<?php if(count($options['sectionsOrder']) > 1):
		?>
	<script>
		jQuery(function() {
			var $flashMsg = jQuery('<span/>', {'class': 'ait-sortable-updated', text: '<?php _e('Order saved', 'ait'); ?>'}).css('display', 'none');
			var $sortable = jQuery('#ait-sortable-elements');
			var $title = $sortable.parentsUntil('.meta-box-sortables').find('h3');
			$title.append($flashMsg);

			$sortable.sortable({
				placeholder: "ait-sortable-placeholder",
				update: function(e, u){
					var $f = jQuery('#ait-settings-form');
					$flashMsg.fadeIn();
					setTimeout(function(){
						$flashMsg.fadeOut('fast');
					}, 1500);
					jQuery.post($f.attr('action'), $f.serialize());
				}
			});
		});
	</script>
	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">
			<div class="postbox-container" style="width:100%;">
				<div  class="meta-box-sortables">
					<div class="postbox">
						<h3><span><?php _e('Order of these sections', 'ait'); ?></span></h3>
						<div class="inside">
							<table class="form-table">
								<tr valign="top" id="ait-order-elements">
									<td>
										<?php
											$titles = array();
											foreach($config as $title => $namedSection){
												if(is_string($namedSection) and substr($namedSection, 0, 7) == 'section'){
													$namedSection = trim(strstr($namedSection, ' '));
													$titles[$namedSection] = $title;
												}
											}
											$i = 0;
											?>
											<div id="ait-sortable-elements">
											<?php
											foreach($options['sectionsOrder'] as $namedSection):
											?>
												<dl class="menu-item-bar">
													<dt class="menu-item-handle">
														<span class="item-title"><?php echo esc_html($titles[$namedSection]) ?></span>
														<input type="hidden" id="<?php echo esc_attr("{$namedSection}_{$i}"); $i++; ?>" name="<?php echo $opt;?>[<?php echo esc_attr($currentKey); ?>][sectionsOrder][]" value="<?php echo esc_attr($namedSection); ?>">
													</dt>
												</dl>
													<?php
											endforeach;
										?>
											</div>

									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php else: ?>
		<div class="error"><p><?php _e('There must be at least two named section in config file for ordering.', 'ait') ?></p></div>
	<?php endif; endif;
}



/**
 *
 * @param bool $order Keys of order elements
 * @param array $sections Order sections as key => title
  * @param type $optionCurrentKey Key of current section
  * @param type $optionKey Key of current option item
 * @param type $opt Option key, constant: AIT_OPTIONS_KEY
 */
function aitRenderOrderType($order, $sections, $optionCurrentKey, $optionKey, $opt, $metaName)
{
	if(count($order) > 1): ?>
	<script>
		jQuery(function() {
			var $flashMsg = jQuery('<span/>', {'class': 'ait-order-updated', text: '<?php _e('Order saved', 'ait'); ?>'}).css('display', 'none');
			var $order = jQuery('.ait-order-elements');
			var $title = $order.parentsUntil('.meta-box-sortables').find('h3');
			$title.append($flashMsg);

			$order.each(function(){
				jQuery(this).sortable({
					placeholder: "ait-order-placeholder",
					update: function(e, u){
						var $f = jQuery('#ait-settings-form');
						$flashMsg.fadeIn();
						setTimeout(function(){
							$flashMsg.fadeOut('fast');
						}, 1500);
						jQuery.post($f.attr('action'), $f.serialize());
					}
				});
			});
		});
	</script>
	<?php
	?>
		<a href="#" id="ait-reset-order" data-ordername="<?php echo $optionKey; ?>" data-metaname="<?php echo $metaName; ?>">Reset order</a>

		<div class="ait-order-elements">
		<?php
		$i = 0;
		foreach($order as $key):
		?>
			<dl class="menu-item-bar">
				<dt class="menu-item-handle">
					<span class="item-title"><?php echo esc_html($sections[$key]) ?></span>
					<input type="hidden" id="<?php echo esc_attr("{$key}_{$i}"); $i++; ?>" name="<?php echo "{$opt}[{$optionCurrentKey}][{$optionKey}][]";?>" value="<?php echo esc_attr($key); ?>">
				</dt>
			</dl>
				<?php
		endforeach;
	?>
		</div>
		<?php else: ?>
		<div class="error"><p><?php _e('There must be at least two section in config file for ordering.', 'ait') ?></p></div>
	<?php endif;
}