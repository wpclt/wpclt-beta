<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


global $firmasite_settings;
if (isset($firmasite_settings["showcase-remove"]) && true == $firmasite_settings["showcase-remove"] ) {
	/* maybe we need this section */
	do_action("firmasite_showcase_removed");
} else {
  add_action('after_setup_theme', "firmasite_showcase_init" );
  function firmasite_showcase_init() {
	global $firmasite_showcase, $firmasite_settings;
	$firmasite_showcase_posts = new WP_Query( array( 
		'post_type' => apply_filters( 'firmasite_pre_get_posts_ekle', array( 'post', 'page' )), 
		'showposts' => apply_filters( 'firmasite_showcase_count', 9),
		'ignore_sticky_posts' => 1,
		'meta_query' => array(
			array(
				'key' => '_jsFeaturedPost',
				'value' => 'yes',				
			)		
		),
	));
	$firmasite_showcase_media = new WP_Query( array( 
		'post_type' => 'attachment', 
		'showposts' => apply_filters( 'firmasite_showcase_count', 9),
		'ignore_sticky_posts' => 1,
		'post_status' => 'inherit',
		'meta_query' => array(
			array(
				'key' => '_jsFeaturedPost',
				'value' => 'yes',				
			)		
		),
	));
	if ( $firmasite_showcase_media->have_posts() ) {
		$mergedposts = array_merge( $firmasite_showcase_posts->posts, $firmasite_showcase_media->posts );
		$postids = array();
		foreach( $mergedposts as $item ) {
			$postids[]=$item->ID; //create a new query only of the post ids
		}
		$uniqueposts = array_unique($postids); //remove duplicate post ids
	
		$firmasite_showcase = new WP_Query( array( 
			'post__in' => $uniqueposts, 
			'post_type' => 'any', 
			'showposts' => apply_filters( 'firmasite_showcase_count', 9),
			'post_status' => 'any',
			'ignore_sticky_posts' => 1,
			'meta_query' => array(
				array(
					'key' => '_jsFeaturedPost',
					'value' => 'yes',					
				)		
			),
		));
	} else {
		$firmasite_showcase = $firmasite_showcase_posts;
	}
	
	
	if ( $firmasite_showcase->have_posts() ) {
		foreach ($firmasite_showcase->posts as $firmasite_showcase_post) {
			global $f_posts;
			$f_posts[] = $firmasite_showcase_post->ID;
		}
		
		// No "showcase" on the homepage loop
		if (false == FIRMASITE_SHOWCASE_POST)
		add_action('pre_get_posts', "firmasite_showcase_remove");	
		function firmasite_showcase_remove($query) {
		  if($query->is_front_page() && $query->is_main_query()) {
			global $f_posts;
			$query->set('post__not_in', $f_posts);
		  }
		}
		
		// Adding showcase style to page class
		add_action( 'wp_head', "firmasite_showcase_style_init");
		function firmasite_showcase_style_init(){
			global $firmasite_settings;
			if( is_front_page() )
				$firmasite_settings["layout_page_class"] = $firmasite_settings["layout_page_class"] . ' showcase-style-' . $firmasite_settings["showcase-style"];
		}
		
		add_action( 'after_header', "firmasite_showcase_content");
		function firmasite_showcase_content(){
			if( is_front_page() ) {
			global $firmasite_showcase,$firmasite_settings;
			?>
            <div id="firmasite-showcase-container" class="<?php echo $firmasite_settings["layout_container_class"]; ?>">
                <div class="firmasite-showcase carousel <?php if ($firmasite_showcase->post_count > 1) echo " slide"; ?>" id="firmasite-showcase" <?php if ($firmasite_showcase->post_count > 1) echo 'data-rel="carousel"'; ?> data-interval="6000">
                    <?php if ($firmasite_showcase->post_count > 1){ ?>
                          <ol class="carousel-indicators">                
                               <?php 
							   $i = 0;
							   $firmasite_showcase_slide_active = "active";
							   foreach ($firmasite_showcase->posts as $firmasite_showcase_post) {  ?>
                            		<li data-target="#firmasite-showcase" data-slide-to="<?php echo $i; ?>" class="<?php echo $firmasite_showcase_slide_active; ?>"></li>
                               <?php
							   $i++;
							   $firmasite_showcase_slide_active = "";
							   }?>
                          </ol>
                    <?php } ?>
                    <div class="<?php if ($firmasite_showcase->post_count > 1) echo 'carousel-inner'; ?>">
                   		<?php
                        $firmasite_showcase_slide_start = true;
                        $firmasite_showcase_slide_active = " active";
                        while ( $firmasite_showcase->have_posts() ) {
                            $firmasite_showcase->the_post();
							global $post,$more;
							$more = 0;
							?>
                            <div class="item post-<?php echo $post->ID;  echo $firmasite_showcase_slide_active; $firmasite_showcase_slide_active = ""; ?>"> 
                           		<?php get_template_part( 'templates/showcase', $post->post_type );?>
                            </div>
                        <?php } ?>
                    </div>
					<?php if ($firmasite_showcase->post_count > 1) { ?>
                    <a data-slide="prev" href="#firmasite-showcase" class="left carousel-control"><span class="icon-prev"></span></a>
                    <a data-slide="next" href="#firmasite-showcase" class="right carousel-control"><span class="icon-next"></span></a>
                    <?php } ?>
                </div>
            </div>
			<?php
			/* Restore original Post Data */
			wp_reset_postdata();
			}
		} 
	} 
  } // firmasite_showcase_init
	
	
	// add fetured colum to posts
	function firmasite_add_js_showcase_colum( $columns ){
		$columns['showcase_js_posts'] = __( 'Add to ShowCase', 'firmasite' ); return $columns;
	} add_filter('manage_posts_columns', 'firmasite_add_js_showcase_colum', 99);
	add_filter('manage_pages_columns', 'firmasite_add_js_showcase_colum', 99);
	add_filter('manage_media_columns', 'firmasite_add_js_showcase_colum', 99);
	
	// add the content to our new colum
	function firmasite_add_js_showcase_post_column_content( $col, $id ){
		
		if ( $col == 'showcase_js_posts' ){
			$class = '';
			$jsFeaturedPost = get_post_meta( $id, '_jsFeaturedPost', true );
			if ( !empty( $jsFeaturedPost ) ){ $class = ' selected'; }
			wp_nonce_field('custom-jsshowcase', 'jsshowcase-nonce');
			echo  '<a id="postFeatured_'.$id.'" class="showcase_posts_star'.$class.'" title="'. __( 'Add to ShowCase', 'firmasite' ). '"></a>';            
		}
		
	} add_action('manage_posts_custom_column', 'firmasite_add_js_showcase_post_column_content', 10, 2);
	add_action('manage_pages_custom_column', 'firmasite_add_js_showcase_post_column_content', 10, 2);
	add_action('manage_media_custom_column', 'firmasite_add_js_showcase_post_column_content', 10, 2);
	
	// get of this this themes cpts and loop through them to create the correct action and filters
	function firmasite_js_showcase_posts_get_and_loop_through_post_types(){
		
		$firmasite_showcase_post_types = apply_filters( 'firmasite_pre_get_posts_ekle', array());
		foreach ($firmasite_showcase_post_types as $post_type ) {            
			add_filter('manage_edit-'.$post_type.'_columns', 'firmasite_add_js_showcase_colum', 99);
			// it shows double time if we dont remove this
			//add_action('manage_'.$post_type.'_posts_custom_column', 'firmasite_add_js_showcase_post_column_content', 10, 2);            
		}
		
	} add_action('admin_init','firmasite_js_showcase_posts_get_and_loop_through_post_types');
	
	// change the width of our colum
	function firmasite_js_showcase_posts_colum_width(){
	 ?>
		<style>
			#showcase_js_posts, .column-showcase_js_posts{ width:100px; text-align: center !important; }
			.showcase_posts_star{ display:block; height:24px; width:24px; margin:8px auto 0 auto; border:none; 
				background: transparent url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAwCAMAAAA8VkqRAAAAA3NCSVQICAjb4U/gAAABXFBMVEX/////sxP/myT/fCv/rxT/qxn/piD/fCv/sxP/qxn/pRr/fCv/dij/qx//qxn/pRr/fCv/fh//nhn/fCv/cyD/vRT/tB//piD/myT/cyD/pRr/fCv/dij/vRT/qxn/pRr/fCv/cyD/sxP/pRr/fCv/cyD/rxT/pRr/fCv/pRr/iCL/fCv/cyD/qx//piD/pRr/myT/fCv/tB//fCv/aCL////39/fv7+/m5ub/5Une3t7/30vW1tb/1kv/zlD/zkrMzMz/xUz/w0PFxcX/vkz/u2P/vVL/wCT/vT3/vEH/tGP/uTq9vb3/tkL/s1H/tB//rmL/rVz/rVH/sxP/sDK1tbX/rCj/rxT/plT/qx//qxn/pFz/pT7/piD/pRr/n1mtra3/nFX/nE7/nhn/myT/lU+kpKT/lEH/jzP/kSH/jjeenp7/iz//hzD/fCv/fS7/fh//cyD/bSj/aCJTTgtJAAAAdHRSTlMAERERIiIiIjMzMzMzRERERERVVVVmZmZmd4iIiJmZmZmZqqqqqru7u8zMzMzd3d3d3e7u7v///////////////////////////////////////////////////////////////////////////////////yAMqI8AAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAcdEVYdFNvZnR3YXJlAEFkb2JlIEZpcmV3b3JrcyBDUzVxteM2AAABz0lEQVQ4jZWR61fTMByGI8hFvM0LioJOboJyEcVcCGmXkYE6qqDYsXEpLZubNwqD//8c0vYsyWH5wvth7Z6nb5NfCoDK8IsbwJbBnZ0Jq3j9aWX3poXf2ltd3XppEZNbjuPs9XXxoUByp5a/gvvuz9QKMk5wT3WGnk++CYJ6xS0UiwW3Ug+CdzP5h1J8qFc2XdctZpF37uefjeAJAAu1YilBpTTZ3VrtLgD9i4elK/ly+DRZpH+xsfHNzNeMA9Cz9MfkB42RzsZ63v7+oXM2osd40Nb8ZNqY71m7qtKaN8T4qRbVtiHm/0dRVF1f/55c2ne0+PgrOir/jZfj8n4UXTxWfOD8aLsVz97uHV0+Le+fjymRi4/juVz6yKv4pDWlRO/s3CPVnnqf6/pY1w7lyMoxxdwqOIQUWwsQIluFIwgh664kBUsFYZYUICRE7QwzxijBGZcdQinjFALgkw7TwZwBEHYLhIVcy/MIQoaTfzAP5e6Q55sm4yTdUWZUOlwaYRrMOlwanxrCJ3o+YQrPGNwzFxHQEDh7OP3l+iChh2WIPInkypg+WS4xD32vKaSiehEqJA45RsyXioVKkH/NUKRvTlRTNyAXeiYmbB/eyCVEzJn10KjKYAAAAABJRU5ErkJggg==) 0 -24px no-repeat; cursor:pointer; }
			.showcase_posts_star.selected, .showcase_posts_star:active{ background-position:0 0; }
		</style>
		<?php
	
	} add_action('admin_head','firmasite_js_showcase_posts_colum_width');
	
	// add jquery function to admin head to save
	function firmasite_js_showcase_posts_add_jquery_to_head(){
		
		if ( current_user_can("manage_options") ){ ?>					
			
			<script type="text/javascript" language="javascript">                
				jQuery(document).ready(function(){                
					// when the checkbox is clicked save the meta option for this post
	
					jQuery('.showcase_posts_star').click(function() {
						var selected = 'yes';
						if ( jQuery(this).hasClass( 'selected' ) ){ 
							jQuery(this).removeClass( 'selected' );
							selected = 'no'; 
						} else { jQuery(this).addClass( 'selected' ); }                        
						// get id
						var tempID = jQuery(this).attr( 'id' );
							tempID = tempID.split( '_' ); 
						var jsshowcasenonce = jQuery("input[name='jsshowcase-nonce']:hidden").val();          
						jQuery.post( ajaxurl, 'action=jsshowcase_posts&post='+tempID[1]+'&jsshowcase-nonce='+jsshowcasenonce+'&jsFeaturedPost='+selected ); 
							
					}); 
						
				}); 
			
			</script> <?php
	
		}
	
	} add_action( 'admin_head', 'firmasite_js_showcase_posts_add_jquery_to_head' );
	
	// add ajax call to wp in order to save the remove delete post link
	function firmasite_js_showcase_posts_link_add_ajax_call_to_wp(){	
		
		check_ajax_referer('custom-jsshowcase', 'jsshowcase-nonce');
		/*  found this example in the dont-break-the-code-example */			
		$jsFeaturedPost = $_POST['jsFeaturedPost'];
		$currentJSPostID = (int)$_POST["post"];
		if( !empty( $currentJSPostID ) && $jsFeaturedPost !== NULL ) {
			if ( $jsFeaturedPost == 'no' ){ delete_post_meta( $currentJSPostID, "_jsFeaturedPost" ); }
			else { add_post_meta( $currentJSPostID, "_jsFeaturedPost", 'yes' ); }
		} exit;
	
	} add_action('wp_ajax_jsshowcase_posts', 'firmasite_js_showcase_posts_link_add_ajax_call_to_wp');
}



add_action( 'after_setup_theme', "firmasite_showcase_setup");
function firmasite_showcase_setup(){
	add_action( 'customize_register', "firmasite_showcase_register");
	 function firmasite_showcase_register($wp_customize) {
		 global $firmasite_settings_desc, $firmasite_settings;
		
		// ShowCase
		$wp_customize->add_section( 'showcase-settings', array(
				'title' => __( 'ShowCase', 'firmasite' ), // The title of section
				'description' => __( 'These settings will activate when you save changes and refresh this page.', 'firmasite' ), // The description of section
				'priority' => '99',
		) );
			// explain
			/*$wp_customize->add_setting( 'firmasite_settings[showcase-explain]', array(
				'type'              => 'option',
				'sanitize_callback' => 'esc_attr'
			) );
			$wp_customize->add_control( new Firmasite_Customize_Explain_Control( $wp_customize, 'firmasite_settings[showcase-explain]', array(
				'label'    => __( 'These settings will activate when you save changes and refresh this page.', 'firmasite' ),
				'type' => 'explain',
				'section'  => 'showcase-settings',
				'priority' => '1',
			) ) );*/			

			 // ShowCase Style
			$wp_customize->add_setting( 'firmasite_settings[showcase-style]', array(
				'default' => '1',
				'type' => 'option',
				'sanitize_callback' => 'esc_attr'
			) );			 
			$wp_customize->add_control( 'firmasite_settings[showcase-style]', array(
				'label' => __( 'ShowCase Style', 'firmasite' ),
				'section' => 'showcase-settings',
				'type' => 'radio',
				'choices' => array(
					'1' => esc_attr__( 'Default', 'firmasite' ),
					'2' => esc_attr__( 'Bigger', 'firmasite' ),
					'3' => esc_attr__( 'Full Width', 'firmasite' ),
				),
			) );						

			// showcase-calltoaction
			$wp_customize->add_setting( 'firmasite_settings[showcase-calltoaction]', array(
				'type'              => 'option',
				'sanitize_callback' => 'esc_attr'
			) );
			$wp_customize->add_control( 'firmasite_settings[showcase-calltoaction]', array(
				'label'    => __( 'Call-to-Action Text', 'firmasite' ),
				'type' => 'text',
				'section'  => 'showcase-settings',
				'priority' => '90',
			) );			
				// Adding explanation for setting
				$firmasite_settings_desc["showcase-calltoaction"]['content'] = "<b>" . __( 'Current Call-to-Action text: ', 'firmasite' ) . " </b>"; 
							if (isset($firmasite_settings["showcase-calltoaction"]) && !empty($firmasite_settings["showcase-calltoaction"])) {
								$firmasite_settings_desc["showcase-calltoaction"]['content'] .= esc_attr(strip_tags( $firmasite_settings["showcase-calltoaction"] )); 
							} else {
								$firmasite_settings_desc["showcase-calltoaction"]['content'] .= esc_attr(strip_tags( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'firmasite' ))); 
							}
							$firmasite_settings_desc["showcase-calltoaction"]['content'] .= '<br /><br />';
							$firmasite_settings_desc["showcase-calltoaction"]['content'] .= esc_attr(strip_tags( __( 'If you are planning to use multiple ShowCase posts, try to choose a common text', 'firmasite' )));
							$firmasite_settings_desc["showcase-calltoaction"]['content'] .= esc_attr( "<br /><b>" . __( 'For example:', 'firmasite' ) . "</b><br />");
							$firmasite_settings_desc["showcase-calltoaction"]['content'] .= esc_attr( __( 'Click for more details', 'firmasite' )); 


			// showcase-remove
			$wp_customize->add_setting( 'firmasite_settings[showcase-remove]', array(
				'type'              => 'option',
				'sanitize_callback' => 'esc_attr'
			) );
			$wp_customize->add_control( 'firmasite_settings[showcase-remove]', array(
				'label'    => __( 'Disable ShowCase feature', 'firmasite' ),
				'type' => 'checkbox',
				'section'  => 'showcase-settings',
				'priority' => '91',
			) );
				
			// explain 2
			$wp_customize->add_setting( 'firmasite_settings[showcase-explain2]', array(
				'type'              => 'option',
				'sanitize_callback' => 'esc_attr'
			) );
			$wp_customize->add_control( new Firmasite_Customize_Explain_Control( $wp_customize, 'firmasite_settings[showcase-explain2]', array(
				'label'    => __( 'ShowCase is displaying in your home page only.', 'firmasite' ),
				'type' => 'explain',
				'section'  => 'showcase-settings',
				'priority' => '99',
			) ) );			

	}
}


if (FIRMASITE_COMBINE_JS) {
	add_action( 'firmasite_combine_js', "firmasite_showcase_animation_combine", 12);
} else {
	add_action( 'wp_footer', "firmasite_showcase_animation",902);
}
function firmasite_showcase_animation(){
	// http://stackoverflow.com/a/14222916/698046 
	// ShowCase animation ?>
	<script type="text/javascript">
		( function ( $ ) {
			var $showcases = $('.firmasite-showcase');
			<?php echo firmasite_showcase_animation_combine(); ?>
		} ( jQuery ) );
	</script>
<?php
}
function firmasite_showcase_animation_combine(){ ?>
	var $showcases = $('.firmasite-showcase');
	function firmasite_showcase_resize(){
        $showcases.each(function(){
            var $showcase_items = $(this).find('.item');
            var $showcase_items_inside = $showcase_items.find(".firmasite-showcase-content");
            $showcase_items.css({position: 'absolute', visibility:'hidden', display:'block'});
            $showcase_items_inside.css({height: ''});
            $(this).parent().css({height: ''});
            $item_heights = $showcase_items.map(function (){
                return $(this).height();
            }).get();
            $item_maxHeight = Math.max.apply(null, $item_heights);
                
            $showcase_items.css({position: '', visibility:'', display:''})
            $showcase_items_inside.css('height', $item_maxHeight + 30);
            $(this).parent().css('height', $item_maxHeight + 30);
        });
	}
	$(window).resize(throttle(function(){
		firmasite_showcase_resize();          
	},250));

	$(document).ready(function() {
    	var $showcase_item = $showcases.find('.item'),
			$showcasesTitle = $showcase_item.find('.hero-title'),
			$showcasesContent = $showcase_item.find('.hero-content'),
			$showcasesLinks = $showcase_item.find('.hero-link');
	
		anim = {'margin-left':0, opacity:1};
		anim_out = {'margin-left':100, opacity:0};
		anim2 = {'margin-left':0, opacity:1};
		anim2_out = {'margin-left':-100, opacity:0};
		
		$showcases.on('slid.bs.carousel', function () {
			var $item = $showcases.find('.item.active');

			$item.find('.hero-title').clearQueue().delay(200).animate(anim2, { duration: 600, easing: 'easeOutQuint' });
			$item.find('.hero-content').clearQueue().delay(400).animate(anim, { duration: 600, easing: 'easeOutQuint' });
			$item.find('.hero-link').clearQueue().delay(600).animate(anim2, { duration: 600, easing: 'easeOutQuint' });
		}).on('slide.bs.carousel', function () {
			// Reset styles
			$showcasesTitle.css(anim2_out).clearQueue();
			$showcasesContent.css(anim_out).clearQueue();
			$showcasesLinks.css(anim2_out).clearQueue();
		}).hover(
			function(){ $showcases.carousel("pause"); },
			function(){ $showcases.carousel("cycle");  }
		);
	});

		
<?php
}

