<?php
/**
 * Creates widget with submenu
 */
class Submenu_Widget extends WP_Widget {
	/**
	 * Widget constructor
     *
	 * @desc sets default options and controls for widget
	 */
	function SubMenu_Widget () {
		/* Widget settings */
		$widget_ops = array (
			'classname' => 'widget_submenu',
			'description' => __( 'Show submenu', 'ait')
		);

		/* Create the widget */
		$this->WP_Widget( 'submenu-widget', __( 'Theme &rarr; Submenu', 'ait'), $widget_ops );
	}

	/**
	 * Displaying the widget
	 *
	 * Handle the display of the widget
	 * @param array
	 * @param array
	 */
	function widget( $args, $instance ) {

		global $post;
		if(isset($post)){
			$children=wp_list_pages( 'echo=0&child_of=' . $post->ID . '&title_li=' );
			if ($children) {
				$parent = $post->ID;
			}else{
				$parent = $post->post_parent;
				if(!$parent){
					$parent = $post->ID;
				}
			}
			$parent_title = get_the_title($parent);
		} else {
			$parent = 0;
			$parent_title = '';
		}


		extract( $args );
		$title = apply_filters('widget_title', empty($instance['title']) ? $parent_title : do_shortcode($instance['title']), $instance, $this->id_base);

		$output = wp_list_pages( array('title_li' => '', 'echo' => 0, 'child_of' =>$parent, 'sort_column' => 'menu_order', 'depth' => 1) );

		if(empty( $output )){
			$output = wp_list_pages( array('title_li' => '', 'echo' => 0, 'sort_column' => 'menu_order', 'depth' => 1) );
		}

		//if ( !empty( $output ) ) {
			echo $before_widget;
			if ( $title)
				echo $before_title . $title . $after_title;
		?>
		<ul>
			<?php echo $output; ?>
		</ul>
		<?php
			echo $after_widget;
		//}

	}

	/**
	 * Update and save widget
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array New widget values
	 */
	function update ( $new_instance, $old_instance ) {
		$old_instance['title'] = strip_tags( $new_instance['title'] );

		return $old_instance;
	}

	/**
	 * Creates widget controls or settings
	 *
	 * @param array Return widget options form
	 */
	function form ( $instance ) {
		$instance = wp_parse_args( (array) $instance, array(
        	'title' => ''
        ) );
	?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo __( 'Title', 'ait' ); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>"class="widefat" style="width:100%;" />
        </p>

        <?php
	}
}
register_widget( 'Submenu_Widget' );