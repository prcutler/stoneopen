<?php
/**
 * Creates widget with posts
 */

class Posts_Widget extends WP_Widget
{

/**
 * Widget constructor
 *
 * @desc sets default options and controls for widget
 */
	function Posts_Widget () {
		/* Widget settings */
		$widget_ops = array (
			'classname' => 'widget_posts',
			'description' => __('Customize displaying posts', 'ait')
		);

		/* Create the widget */
		$this->WP_Widget('posts-widget', __('Theme &rarr; Posts', 'ait'), $widget_ops);
	}

/**
 * Displaying the widget
 *
 * Handle the display of the widget
 * @param array
 * @param array
 */
	function widget ( $args, $instance ) {
		extract ($args);
		global $wp_query;
		/* Before widget(defined by theme)*/
		echo $before_widget;

		$query = array();

		if ( !empty( $instance['number_of_posts'] ))
            $query['showposts'] = $instance['number_of_posts'];
        else
            $query['showposts'] = 1;

		$formats = get_post_format_slugs();
		foreach ((array) $formats as $i => $format ) {
			$formats[$i] = 'post-format-' . $format;
		}

		$query['tax_query'] = array(
			array(
				'taxonomy' => 'post_format',
				'field' => 'slug',
				'terms' => $formats,
				'operator' => 'NOT IN'
			)
		);

		/* START Widget body */
        //if ( isset( $instance['title'] ) ) echo $before_title . $instance['title'] . $after_title;
        if ( !empty( $instance['title'] ) )
        {
            echo $before_title; echo do_shortcode($instance['title']); echo $after_title;
        }

        $i = 1;
        $num_posts = sizeof( query_posts( $query ) );

        if (have_posts()) : ?>
        <div class="postitems-wrapper">
         <?php while (have_posts()): the_post();
            if (!empty($instance['excerpt_length']))
				if(function_exists('iconv')){
					$text = iconv_substr(strip_tags( get_the_content() ), 0, $instance['excerpt_length'], 'UTF-8');
				}else{
					$text = substr( strip_tags( get_the_content() ), 0, $instance['excerpt_length'] );
				}
            else
                $text = get_the_excerpt();

            $thumbnail_id = get_post_thumbnail_id( get_the_ID () );
            $thumbnail_args = wp_get_attachment_image_src( $thumbnail_id, 'single-post-thumbnail' );

            switch ( $instance['thumbnail_position'] ) {
                case 'left': $thumbnail_class = 'fl'; break;
                case 'right': $thumbnail_class = 'fr'; break;
                default: $thumbnail_class = ''; break;
            }

            $post_class = '';
            // Is last post
            if ($i == $num_posts) $post_class = ' last';
            // Has thumbnail
            if ( has_post_thumbnail ( get_the_ID() ) && $instance['show_thumbnails'] ) $post_class .= ' with-thumbnail'
            ?>
                <div class="postitem clearfix <?php echo $post_class; ?>">
                <?php if ( has_post_thumbnail ( get_the_ID() ) && $instance['show_thumbnails'] ) : ?>

                    <div class="thumb-wrap <?php echo $thumbnail_class; ?>">

                        <a href="<?php the_permalink(); ?>" class="greyscale">

							<?php if(TIMTHUMB_URL != ''): ?>
							<img class="thumb" src="<?php echo TIMTHUMB_URL ?>?src=<?php echo getRealThumbnailUrl($thumbnail_args['0']); ?>&amp;w=<?php echo $instance['thumbnail_width']; ?>&amp;h=<?php echo $instance['thumbnail_height']; ?>" alt="" />
                            <?php else: ?>
                            <img class="thumb" src="<?php echo AitImageResizer::resize($thumbnail_args['0'], array('w' => $instance['thumbnail_width'], 'h' => $instance['thumbnail_height'])) ?>" alt="" />
                            <?php endif; ?>
                        </a>
                    </div><!-- /.thumb-wrap -->
                <?php endif; ?>

                <h3><a href="<?php the_permalink(); ?>"><?php the_title_attribute(); ?></a></h3>

                <p><small><?php echo $text; ?></small></p>

                <?php if ( !empty( $instance['show_read_more'] ) ) : ?>
                <div class="read-more">
                    <small class="fl">
                        <strong><?php echo get_the_date(); ?></strong>
                    </small>

                    <small class="fr">
                        <a href="<?php the_permalink(); ?>"><?php echo __('read more', 'ait'); ?></a>
                    </small>
                </div>
                <?php endif; ?>
            </div><!-- /.item -->
            <?php
            $i++;
        endwhile; ?></div><?php else: ?>
        	<div class="postitems-wrapper">
           		<?php echo __('<div class="no-content">No posts</div>'); ?>
           </div>
        <?php endif;


		/* After widget(defined by theme)*/
		echo $after_widget;
		wp_reset_query();
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
		$old_instance['number_of_posts'] = $new_instance['number_of_posts'];
		$old_instance['show_thumbnails'] = $new_instance['show_thumbnails'];
		$old_instance['show_read_more'] = $new_instance['show_read_more'];
		$old_instance['excerpt_length'] = $new_instance['excerpt_length'];
		$old_instance['thumbnail_width'] = $new_instance['thumbnail_width'];
		$old_instance['thumbnail_height'] = $new_instance['thumbnail_height'];
		$old_instance['thumbnail_position'] = $new_instance['thumbnail_position'];

		return $old_instance;
	}

/**
 * Creates widget controls or settings
 *
 * @param array Return widget options form
 */
	function form ( $instance ) {
		$instance = wp_parse_args( (array) $instance, array(
        	'title' => '',
        	'number_of_posts' => 5,
        	'excerpt_length' => 50,
        	'show_read_more' => true,
        	'show_thumbnails' => true,
        	'thumbnail_width' => 50,
        	'thumbnail_height' => 50,
        	'thumbnail_position' => 'left'
        ) );
	?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo __( 'Title', 'ait' ); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>"class="widefat" style="width:100%;" />
        </p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number_of_posts' ); ?>"><?php echo __( 'Number of posts', 'ait' ); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'number_of_posts' ); ?>" name="<?php echo $this->get_field_name( 'number_of_posts' ); ?>" value="<?php echo $instance['number_of_posts']?>" size="2" />
        </p>

		<p>
			<label for="<?php echo $this->get_field_id( 'excerpt_length' ); ?>"><?php echo __( 'Excerpt length', 'ait' ); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'excerpt_length' ); ?>" name="<?php echo $this->get_field_name( 'excerpt_length' ); ?>" value="<?php echo $instance['excerpt_length']?>" size="2" />
        </p>

        <p>
            <?php $checked = ''; if ( $instance['show_read_more'] ) $checked = 'checked="checked"'; ?>
			<input type="checkbox" <?php echo $checked; ?> id="<?php echo $this->get_field_id( 'show_read_more' ); ?>" name="<?php echo $this->get_field_name( 'show_read_more' ); ?>" class="checkbox" />
			<label for="<?php echo $this->get_field_id( 'show_read_more' ); ?>"><?php echo __( 'Show read more', 'ait' ); ?></label>
        </p>

        <p>
            <?php $checked = ''; if ( $instance['show_thumbnails'] ) $checked = 'checked="checked"'; else $checked = ''; ?>
			<input type="checkbox" <?php echo $checked; ?> id="<?php echo $this->get_field_id( 'show_thumbnails' ); ?>" name="<?php echo $this->get_field_name( 'show_thumbnails' ); ?>" class="checkbox" />
			<label for="<?php echo $this->get_field_id( 'show_thumbnails' ); ?>"><?php echo __( 'Show thumbnails', 'ait' ); ?></label>
        </p>

		<p>
			<label for="<?php echo $this->get_field_id( 'thumbnail_width' ); ?>"><?php echo __( 'Thumbnail width', 'ait' ); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'thumbnail_width' ); ?>" name="<?php echo $this->get_field_name( 'thumbnail_width' ); ?>" value="<?php echo $instance['thumbnail_width']; ?>" size="3" />px
        </p>

		<p>
			<label for="<?php echo $this->get_field_id( 'thumbnail_height' ); ?>"><?php echo __( 'Thumbnail height', 'ait' ); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'thumbnail_height' ); ?>" name="<?php echo $this->get_field_name( 'thumbnail_height' ); ?>" value="<?php echo $instance['thumbnail_height']; ?>" size="3"/>px
        </p>

		<p>
			<label for="<?php echo $this->get_field_id( 'thumbnail_position' ); ?>"><?php echo __( 'Thumbnail position', 'ait' ); ?>:</label>
			<select id="<?php echo $this->get_field_id( 'thumbnail_position' ); ?>" name="<?php echo $this->get_field_name( 'thumbnail_position' ); ?>">
				<option <?php if ( 'left' == $instance['thumbnail_position'] ) echo 'selected="selected"'; ?> value="left">Left</option>
				<option <?php if ( 'right' == $instance['thumbnail_position'] ) echo 'selected="selected"'; ?> value="right">Right</option>
				<option <?php if ( 'top' == $instance['thumbnail_position'] ) echo 'selected="selected"'; ?> value="top">Top</option>
			</select>
		</p>
		<?php
	}
}
register_widget( 'Posts_Widget' );