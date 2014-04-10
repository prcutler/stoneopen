<?php echo is_admin(); ?>

// JavaScript Document
(function() {
    // Creates a new plugin class and a custom listbox
    tinymce.create('tinymce.plugins.ait_shortcodes_items', {
        createControl: function(n, cm) {
            switch (n) {                
                case 'ait_shortcodes_items':
                var c = cm.createSplitButton('ait_shortcodes_items', {
                    title : 'Items shortcodes',
                    image : jQuery('#editor-theme-url').text() + '/AIT/Framework/Shortcodes/items/icon.png',
                    onclick : function() {
                    	
                    	//tinyMCE.activeEditor.windowManager.alert("hello");
                    	
                    	/*
                    	tinyMCE.activeEditor.windowManager.open({
						   url : jQuery('#editor-theme-url').text() + '/AIT/Framework/Shortcodes/items/modal.html',
						   width : 320,
						   height : 240,
						   inline : 1
						}, {
						   custom_param : 1
						});
						*/
						
						/* Displays an confirm box and an alert message will be displayed depending on what you choose in the confirm
						tinyMCE.activeEditor.windowManager.confirm("Do you want to do something", function(s) {
						   if (s)
						      tinyMCE.activeEditor.windowManager.alert("Ok");
						   else
						      tinyMCE.activeEditor.windowManager.alert("Cancel");
						});
						*/
                    }
                });

                c.onRenderMenu.add(function(c, m) {
					// Rooms
					m.add({title : 'Rooms', 'class' : 'mceMenuItemTitle'}).setDisabled(1);
                    m.add({title : 'Most recent rooms', onclick : function() {
                        tinyMCE.activeEditor.execCommand( 'mceInsertContent', false, '[get_items number="3" excerpt="290"]' );
                    }});
                    m.add({title : 'Rooms by category ID', onclick : function() {
                        tinyMCE.activeEditor.execCommand( 'mceInsertContent', false, '[get_item_category category="7" excerpt="290"]' );
                    }});
					m.add({title : 'Custom room by ID', onclick : function() {
                        tinyMCE.activeEditor.execCommand( 'mceInsertContent', false, '[get_item id="55" excerpt="290"]' );
                    }});
                });

                // Return the new splitbutton instance
                return c;
                
            }
            return null;
        }
    });
    tinymce.PluginManager.add('ait_shortcodes_items', tinymce.plugins.ait_shortcodes_items);
})();