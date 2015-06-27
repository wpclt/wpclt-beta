<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


// http://wordpress.org/support/topic/how-to-make-use-of-the-post-format-link
// Extract first occurance of text from a string
if( !function_exists ('firmasite_extract_from_string') ) :
function firmasite_extract_from_string($start, $end, $tring) {
	$tring = stristr($tring, $start);
	$trimmed = stristr($tring, $end);
	return substr($tring, strlen($start), -strlen($trimmed));
}
endif;


// its not loading on every post but you can call it if you want
add_filter('the_content', 'firmasite_replace_image_links',5);
function firmasite_replace_image_links($content){
	global $firmasite_settings, $post, $total_images_out;
	
	if ("image" != get_post_format()) return $content;
	
		$caption_shortcode = firmasite_extract_from_string('[caption', ']', $content);	
		if($caption_shortcode) {
			return $content;
		}

		$gallery_shortcode = firmasite_extract_from_string('[gallery', ']', $content);	
		if(!$gallery_shortcode) {
			if(is_single())
				$content = "[gallery]".$content;
		}

		// clearing inserted attachments
		$content = preg_replace("/<a[^>]+\><img[^>]+\><\/a>/", "", $content);


	 return $content;
}


add_filter('the_content', 'firmasite_replace_gallery_links',5);
function firmasite_replace_gallery_links($content){
	global $firmasite_settings, $post;
	
	if ("gallery" != get_post_format()) return $content;
	
		$gallery_shortcode = firmasite_extract_from_string('[gallery', ']', $content);	
		if(!$gallery_shortcode) {
			if(is_single())
				$content = "[gallery]".$content;
		}

		// clearing inserted attachments
		$content = preg_replace("/<a[^>]+\><img[^>]+\><\/a>/", "", $content);


	 return $content;
}


function firmasite_gallery_count($post_id,$classes = "", $gallery_count = "") {
	
	if (!isset($gallery_count) || empty($gallery_count)) {
	// Check if its file link in our own wordpress upload
		$gallery_uploads = new WP_Query( array(
			'post_parent' => $post_id,
			'post_status' => 'inherit',
			'post_type' => 'attachment',
			'post_mime_type' => 'image',
			'order' => 'ASC',
			'orderby' => 'menu_order ID',
			'posts_per_page' => -1,
			'update_post_term_cache' => false,
		) );
		if ($gallery_uploads->posts) {
			/* foreach ($gallery_uploads->posts as $gallery_upload) {} */		
			$total_images = count( $gallery_uploads->posts );
		}
	} else {
		$total_images = $gallery_count;
	}

	$total_images_out = "";
	if(isset($total_images)) {
		$total_images_out = sprintf( _n( 'This gallery contains %1$s photo.', 'This gallery contains %1$s photos.', $total_images, 'firmasite' ),								
								number_format_i18n( $total_images )
							); 		
		$total_images_out  = '<span class="gallery-count '.$classes.'"><i class="icon-picture"></i> ' . $total_images_out . '</span> ';
	
	}
	
	return $total_images_out;

}


add_filter( 'post_gallery', 'firmasite_post_gallery', 10, 2 );
function firmasite_post_gallery( $output, $attr) {
    global $post, $wp_locale;

    static $instance = 0;
    $instance++;
	
	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	$attr = apply_filters("firmasite_gallery_attr", $attr);
	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => 'dl',
		'icontag'    => 'dt',
		'captiontag' => 'h4',
		'columns'    => 3,
		'size'       => 'large',
		'include'    => '',
		'exclude'    => '',
		'link'  	 => 'post',
		'fs_gallery_style' => 'gallery_grid',
	), $attr));

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty($include) ) {
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}

	$itemtag = tag_escape($itemtag);
	$captiontag = tag_escape($captiontag);
	$columns = intval($columns);
/*	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	$float = is_rtl() ? 'right' : 'left';
*/
	$selector = "gallery_{$post->ID}_{$instance}";
		
	global $firmasite_post_gallery;
	$firmasite_post_gallery = array();
	$firmasite_post_gallery["attachments"] = $attachments;
	$firmasite_post_gallery["instance"] = $instance;
	$firmasite_post_gallery["selector"] = $selector;
	$firmasite_post_gallery["columns"] = $columns;
	$firmasite_post_gallery["link"] = $link;
	$firmasite_post_gallery["size"] = $size;
	$firmasite_post_gallery["fs_gallery_style"] = $fs_gallery_style;
	ob_start();
	get_template_part( 'templates/gallery', $post->post_type );
	$output = ob_get_contents(); 
	ob_end_clean();
	
    return $output;
}




add_action('print_media_templates', 'firmasite_gallery_style_setting_init');
function firmasite_gallery_style_setting_init(){

  // define your backbone template;
  // the "tmpl-" prefix is required,
  // and your input field should have a data-setting attribute
  // matching the shortcode name
  ?>
  <script type="text/html" id="tmpl-fs-gallery-style">
    <label class="setting">
      <span><?php _e('Gallery Style', 'firmasite'); ?></span>
      <select data-setting="fs_gallery_style">
		<?php
		$sizes = apply_filters( 'firmasite_gallery_styles', array(
			'gallery_grid' => __( 'Grid', 'firmasite' ),
			'gallery_tile'    => __( 'Tiles', 'firmasite' ),
			'gallery_slider'     => __( 'Slider', 'firmasite' ),
		) );

		foreach ( $sizes as $value => $name ) { ?>
			<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, 'gallery_slider' ); ?>>
				<?php echo esc_html( $name ); ?>
			</option>
		<?php } ?>	  
      </select>
    </label>
  </script>

  <script>

    jQuery(document).ready(function(){

      // add your shortcode attribute and its default value to the
      // gallery settings list; $.extend should work as well...
      _.extend(wp.media.gallery.defaults, {
        fs_gallery_style: 'gallery_grid'
      });

      // merge default gallery settings template with yours
      wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
        template: function(view){
          return wp.media.template('gallery-settings')(view)
		   + wp.media.template('fs-gallery-style')(view);
        }
      });

    });

  </script>
  <?php

}










