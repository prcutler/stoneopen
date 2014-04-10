<?php
require '../../../../../../wp-load.php';


$shortcode = $_REQUEST['plugin'];
$ver = $GLOBALS['aitThemeShortcodes'][$shortcode];

if($ver == 1)
	$ver = '';

$configFile = dirname(__FILE__) . "/$shortcode/config{$ver}.neon";

if(!file_exists($configFile))
	$configFile = dirname(__FILE__) . "/$shortcode/config.neon";

$data = NNeon::decode(file_get_contents($configFile, true));

if ( isset($data['width']) ) $width = $data['width']; else $width = '640';
if ( isset($data['height']) ) $height = $data['height']; else $height = '700';

if ( isset($data['title']) ) $title = $data['title']; else $title = $shortcode . ' shortcodes';

wp_enqueue_script('jquery');
wp_enqueue_script('thickbox');
wp_enqueue_style('thickbox');

@header("Content-Type: text/javascript", true, 200);
?>

(function() {

	var disableInPosts = '<?php if (isset($data['disableInPosts'])) echo $data['disableInPosts']; ?>';
	var type = '<?php if (isset($data['type'])) echo $data['type']; ?>';
	var from = '<?php echo $_REQUEST['from']; ?>';

	if(!(disableInPosts == '1' && from.indexOf("post.php") != -1)){

	if(type == 'button' || type == ''){

		tinymce.create('tinymce.plugins.ait_shortcodes_<?php echo $shortcode; ?>', {
			init : function(ed, url) {
				// Register commands
				ed.addCommand('ait_shortcodes_<?php echo $shortcode; ?>', function() {
					<?php $lang = isset($_GET['lang']) ? '&lang=' . $_GET['lang'] : ''; ?>
					tb_show('<?php echo $title; ?>', '<?php echo THEME_URL; ?>/AIT/Framework/Shortcodes/popupWindow.php?plugin=<?php echo $shortcode; ?>&width=<?php echo $width; ?>&height=<?php echo $height; ?><?php echo $lang; ?>');

					/*
					tinyMCE.activeEditor.windowManager.open({
						file : '<?php echo THEME_URL; ?>/AIT/Framework/Shortcodes/popupWindow.php?plugin=<?php echo $shortcode; ?>', // file that contains HTML for our modal window
						width : <?php echo $width; ?> + parseInt(ed.getLang('button.delta_width', 0)), // size of our window
						height : <?php echo $height; ?> + parseInt(ed.getLang('button.delta_height', 0)), // size of our window
						inline : 1
					}, {
						plugin : '<?php echo $shortcode; ?>'
					});
					*/

				});

				// Register buttons
				ed.addButton('ait_shortcodes_<?php echo $shortcode; ?>', {title : '<?php echo $title; ?>', cmd : 'ait_shortcodes_<?php echo $shortcode; ?>', image: '<?php echo THEME_URL; ?>/AIT/Framework/Shortcodes/<?php echo $shortcode; ?>/icon.png' });
			}
		});
	} else if (type == 'dropdown'){
		// Creates a new plugin class and a custom listbox
	    tinymce.create('tinymce.plugins.ait_shortcodes_<?php echo $shortcode; ?>', {
	        createControl: function(n, cm) {
	            switch (n) {
	                case 'ait_shortcodes_<?php echo $shortcode; ?>':
	                var c = cm.createSplitButton('ait_shortcodes_<?php echo $shortcode; ?>', {
	                    title : '<?php echo $title; ?>',
	                    image : '<?php echo THEME_URL; ?>/AIT/Framework/Shortcodes/<?php echo $shortcode; ?>/icon.png',
	                    onclick : function() {
	                    }
	                });

	                c.onRenderMenu.add(function(c, m) {
	                    <?php if ( isset($data['dropdownOptions']) ){

	                    foreach ($data['dropdownOptions'] as $key => $value) {

						if($value['type']=='header'){ ?>

						m.add({title : '<?php echo $value['label']; ?>', 'class' : 'mceMenuItemTitle'}).setDisabled(1);

						<?php } elseif($value['type']=='option') { ?>

	                    m.add({title : '<?php echo $value['label']; ?>', onclick : function() {

	                    	var selectedContent = tinyMCE.activeEditor.selection.getContent();
	                    	var content = '<?php echo $value['value']; ?>';

	                    	if(content.indexOf("$content") != -1){
	                    		content = content.replace('$content',selectedContent);
	                    	}

	                        tinyMCE.activeEditor.execCommand( 'mceInsertContent', false, content );
	                    }});

	                    <?php } } } ?>
	                });

	                // Return the new splitbutton instance
	                return c;

	            }
	            return null;
	        }
	    });
	}
	// Register plugin
	// first parameter is the button ID and must match ID elsewhere
	// second parameter must match the first parameter of the tinymce.create() function above
	tinymce.PluginManager.add('ait_shortcodes_<?php echo $shortcode; ?>', tinymce.plugins.ait_shortcodes_<?php echo $shortcode; ?>);

	}
})();