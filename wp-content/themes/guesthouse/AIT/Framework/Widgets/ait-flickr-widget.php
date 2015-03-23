<?php
/**
 * Creates widget with flickr images
 */

class Flickr_Widget extends WP_Widget {

/**
 * Widget constructor
 *
 * @desc sets default options and controls for widget
 */
	function Flickr_Widget () {
		/* Widget settings */
		$widget_ops = array (
			'classname' => 'widget_flickr',
			'description' => __('Display flickr images', 'ait')
		);

		/* Create the widget */
		$this->WP_Widget('flickr-widget', __('Theme &rarr; Flickr', 'ait'), $widget_ops);
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
		/* Before widget(defined by theme) http://api.flickr.com/services/feeds/photos_public.gne?id=41068918@N05&lang=de-de&format=rss_200*/
		echo $before_widget;

		$photos = $this->getFlickrPhotosRss( $instance['rss'], $instance['number_of_images'], 0 );
		?>

		<?php if ( !empty( $instance['title'] ) ) : ?>
		<?php echo $before_title; ?><?php echo do_shortcode($instance['title']); ?><span class="wd-icon"></span><?php echo $after_title; ?>
		<?php endif; ?>
		<ul>
		<?php

    foreach ($photos as $photo) {
      ?>
      <li class="thumb">
        <a href="<?php echo $photo['url_big']; ?>" title="<?php echo $photo['title']; ?>" rel="prettyPhoto[flickr-widget]">
          <img src="<?php echo $photo['url']; ?>" alt="<?php //echo $photo['alt']; ?>" width="<?php echo $instance['thumbnail_width']; ?>" height="<?php echo $instance['thumbnail_height']; ?>" />
        </a>
      </li>
      <?php
    }
    ?>
    </ul>
    <?php
		/* After widget(defined by theme)*/
		echo $after_widget;
	}

/**
 * Get images from flickr
 *
 * @param string RSS channel
 * @param int limit
 */
  function getFlickrPhotosRss($rss, $limit) {
  	$aryPhotos = array();
	include_once(ABSPATH . WPINC . '/feed.php');

	$aryRss = fetch_feed($rss);

	if(!is_wp_error($aryRss)){
		// Figure out how many total items there are, but limit it to 5.
		$maxitems = $aryRss->get_item_quantity($limit);

		// Build an array of all the items, starting with element 0 (first element).
		$rss_items = $aryRss->get_items(0, $maxitems);
	}

	$ns = 'http://search.yahoo.com/mrss/';

	foreach($rss_items as $i => $item){
		$thumbnail = $item->get_item_tags($ns, 'thumbnail');
		$aryPhotos[$i]['url'] = $thumbnail[0]['attribs']['']['url'];

		$image = $item->get_item_tags($ns, 'content');
		$aryPhotos[$i]['url_big'] = $image[0]['attribs']['']['url'];

		$aryPhotos[$i]['alt'] = esc_html($item->get_title());
		$aryPhotos[$i]['title'] = esc_html($item->get_title());
		$aryPhotos[$i]['link'] = esc_html($item->get_link());
		$aryPhotos[$i]['morelink'] = $aryRss->get_link();
		$aryPhotos[$i]['description'] = esc_html($item->get_description());
	}
  	return $aryPhotos;
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
		$old_instance['number_of_images'] = $new_instance['number_of_images'];
		$old_instance['rss'] = $new_instance['rss'];
		$old_instance['thumbnail_width'] = $new_instance['thumbnail_width'];
		$old_instance['thumbnail_height'] = $new_instance['thumbnail_height'];

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
			'rss' => '',
			'number_of_images' => 9,
			'thumbnail_width' => 50,
			'thumbnail_height' => 50
		) );
	?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo __( 'Title', 'ait' ); ?>:</label>
		<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" style="width:100%;" />
    </p>

	<p>
		<label for="<?php echo $this->get_field_id( 'rss' ); ?>"><?php echo __( 'RSS', 'ait' ); ?>:</label>
		<input type="text" id="<?php echo $this->get_field_id( 'rss' ); ?>" name="<?php echo $this->get_field_name( 'rss' ); ?>" value="<?php echo $instance['rss']; ?>" class="widefat" style="width:100%;" />
    </p>

	<p>
		<label for="<?php echo $this->get_field_id( 'number_of_images' ); ?>"><?php echo __( 'Number of posts', 'ait' ); ?>:</label>
		<input type="text" id="<?php echo $this->get_field_id( 'number_of_images' ); ?>" name="<?php echo $this->get_field_name( 'number_of_images' ); ?>" value="<?php echo $instance['number_of_images']?>" size="2" />
    </p>

	<p>
		<label for="<?php echo $this->get_field_id( 'thumbnail_width' ); ?>"><?php echo __( 'Thumbnail width', 'ait' ); ?>:</label>
		<input type="text" id="<?php echo $this->get_field_id( 'thumbnail_width' ); ?>" name="<?php echo $this->get_field_name( 'thumbnail_width' ); ?>" value="<?php echo $instance['thumbnail_width']; ?>" size="3" />px
    </p>

	<p>
		<label for="<?php echo $this->get_field_id( 'thumbnail_height' ); ?>"><?php echo __( 'Thumbnail height', 'ait' ); ?>:</label>
		<input type="text" id="<?php echo $this->get_field_id( 'thumbnail_height' ); ?>" name="<?php echo $this->get_field_name( 'thumbnail_height' ); ?>" value="<?php echo $instance['thumbnail_height']; ?>" size="3"/>px
    </p>
		<?php
	}

}

register_widget( 'Flickr_Widget' );
