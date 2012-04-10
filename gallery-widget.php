<?php
/* 
Plugin Name: Goat Tree Lightbox Gallery Launcher
Description: Adds a sidebar widget that uses the posts's featured image as a link to open a lightbox gallery.
Version: 1.0
Author: Andy Shaw
Author URI: http://goat-tree.co.uk/gt-pop-up-image-gallery-widget

Copyright 2012  Andy Shaw  (email : contact@goat-tree.co.uk)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
	
	class GoatTreeGalleyWidget extends WP_Widget
	{
	  function GoatTreeGalleyWidget()
	  {
	    $widget_ops = array('classname' => 'GoatTreeGalleyWidget', 'description' => 'Opens a lightbox gallery from the post\'s featured image' );
	    $this->WP_Widget('GoatTreeGalleyWidget', 'Lightbox Gallery Launcher', $widget_ops);
	  }
	 
	  function form($instance)
	  {
	    $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'size' => 'medium', 'open_gallery_message' => 'View image gallery' ) );
	    $title = $instance['title'];
	    $size = $instance['size'];
	    $open_gallery_message = $instance['open_gallery_message'];
	?>
	  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
	  <p><label for="<?php echo $this->get_field_id('size'); ?>">Size: <input class="widefat" id="<?php echo $this->get_field_id('size'); ?>" name="<?php echo $this->get_field_name('size'); ?>" type="text" value="<?php echo attribute_escape($size); ?>" /></label></p>
	  <p><label for="<?php echo $this->get_field_id('open_gallery_message'); ?>">Text for open galley link: <input class="widefat" id="<?php echo $this->get_field_id('open_gallery_message'); ?>" name="<?php echo $this->get_field_name('open_gallery_message'); ?>" type="text" value="<?php echo attribute_escape($open_gallery_message); ?>" /></label></p>
	<?php
	  }
	 
	  function update($new_instance, $old_instance)
	  {
	    $instance = $old_instance;
	    $instance['title'] = $new_instance['title'];
	    $instance['size'] = $new_instance['size'];
	    $instance['open_gallery_message'] = $new_instance['open_gallery_message'];
	    return $instance;
	  }
	 
	  function widget($args, $instance)
	  {
	    extract($args, EXTR_SKIP);
	 
	    echo $before_widget;
	    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
	 
	    if (!empty($title))
	      echo $before_title . $title . $after_title;;
	 
	    // WIDGET CODE GOES HERE<!-- image gallery widget begin -->
		$post = $GLOBALS['post'];
		
		echo '<ul class="image-gallery">';
		if(has_post_thumbnail()) {
			
			$img_args = array( 
							'post_type' => 'attachment', 
							'numberposts' => -1, 
							'post_status' => null, 
							'post_parent' => $post->ID, 
							'post_mime_type' => 'image' ); 
			$imgs = get_posts($img_args);
		
			$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large' );
			echo '<li class="image">';
			echo '<a href="'.$thumbnail_src[0].'" rel="lightbox">';
			echo get_the_post_thumbnail($post->id, $instance['size']); 
			if(strlen($instance['open_gallery_message']) > 0) {
				echo '<br>'.$instance['open_gallery_message'];
			}
			echo '</a>';
			echo '</li>';
			if ($imgs) {
				foreach ( $imgs as $img ) {
					$img_src = wp_get_attachment_image_src( $img->ID, 'large' );
					if($img_src[0] != $thumbnail_src[0]) {
						echo '<a href="'.$img_src[0].'" rel="lightbox"></a>';
					}
				}
			}
		}
		echo '</ul>';
		echo '<!-- image gallery widget end -->';	 
	    echo $after_widget;
	  }
 
	}
	add_action( 'widgets_init', create_function('', 'return register_widget("GoatTreeGalleyWidget");') );
		
?>
