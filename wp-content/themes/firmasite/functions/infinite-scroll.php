<?php 

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


/**
 * FirmaSite_Infinite_Scroll Customizer
 */
add_action('after_setup_theme', "FirmaSite_Infinite_Scroll_setup",99 );
function FirmaSite_Infinite_Scroll_setup() {
	global $firmasite_settings;
	
	add_action( 'customize_register', "firmasite_infinite_scroll_customizer_register");
	function firmasite_infinite_scroll_customizer_register($wp_customize) {
			global $firmasite_settings, $firmasite_settings_desc;
			
			// No responsive
			$wp_customize->add_setting( 'firmasite_settings[infinite-scroll]', array(
				'type'      => 'option',
				'default' 	=> false,
				'sanitize_callback' => 'esc_attr'
			) );
			$wp_customize->add_control( 'firmasite_settings[infinite-scroll]', array(
				'label'    => esc_attr__( 'Activate Infinite Scroll', 'firmasite' ),
				'type' => 'checkbox',
				'section'  => 'theme-settings',
				'priority' => '991',
			) );	
				// Adding explanation for setting
				$firmasite_settings_desc["infinite-scroll"]['content'] = esc_attr__( 'Instead of having to click a link to get to the next set of posts, infinite scrolling pulls the next posts automatically into view when the reader approaches the bottom of the page.', 'firmasite' ); 
	}
	
	if( isset($firmasite_settings["infinite-scroll"]) && true == $firmasite_settings["infinite-scroll"]){
		add_theme_support( 'firmasite-infinite-scroll', array(
			'container' => 'primary',
			'render'    => 'firmasite_infinite_scroll_render',
			'wrapper'   => false,
			'footer'	=> false,
			'posts_per_page' => $firmasite_settings["loop_tile_row"] * 3,
		));
		add_action( 'wp_enqueue_scripts', 'FirmaSite_Infinite_Scroll_scripts' );
		function FirmaSite_Infinite_Scroll_scripts() {
			wp_enqueue_script( 'firmasite-infinite-scroll', get_template_directory_uri() .'/assets/js/jquery.infinitescroll.min.js', array( 'jquery' ), false, true );
		}
		
		add_action( 'wp_footer', "firmasite_infinite_scroll_settings_init", 900);
		function firmasite_infinite_scroll_settings_init() {
			global $firmasite_settings;
			if(isset($firmasite_settings["main_loop_id"])) {
				$main_container = '#' . $firmasite_settings["main_loop_id"];
			} else {
				$main_container = "#primary";
			}
			if(isset($firmasite_settings["main_loop_element"])) {
				$main_loop_element = $firmasite_settings["main_loop_element"];
			} else {
				$main_loop_element = "#primary>[id^=post-]";
			}
			if(isset($firmasite_settings["main_loop_style"])) {
				$main_loop_style = $firmasite_settings["main_loop_style"];
			} else {
				$main_loop_style = $firmasite_settings["loop-style"];
			}
			
			?>
			<script type="text/javascript">
				(function ($) {
				  $('<?php echo $main_container; ?>').infinitescroll({
					navSelector  : "#pagination-bottom",            
					nextSelector : "#pagination-bottom a.next.page-numbers:first",    
					itemSelector : "<?php echo $main_loop_element; ?>",
					bufferPx     : 450,
					animate: false, 
					pixelsFromNavToBottom: 300, 
					loading: {
						finished: undefined,
						finishedMsg: "<div class='label label-warning'><?php esc_attr_e( 'All content loaded.', 'firmasite' ); ?></div>",
						msg: null,
						msgText: "<div class='label label-info'><?php esc_attr_e( 'Loading...', 'firmasite' ); ?></div>",
						img: '<?php echo get_template_directory_uri() .'/assets/img/ajax-loader.gif'; ?>',
						selector: null,
						speed: 'slow',
						start: undefined
					  },
				  },function(newElements){
						<?php if ("loop-tile" == $main_loop_style) { ?>
						  var $newElems = $(newElements).hide(); // hide to begin with
						  var $maincontainer =  $(this);
						  // ensure that images load before adding to masonry layout
						  $newElems.imagesLoaded(function(){
							$newElems.fadeIn(); // fade in when ready
							$maincontainer.masonry('appended',$newElems);
						  });
						<?php } ?>
						$(window).trigger("resize");
				  });
								  
				})(jQuery);
			</script>
			<?php
		}		

	}

}


