<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * @package firmasite
 */
 

// You can translate theme description with this
__("Free responsive WordPress theme with Buddypress and bbPress supports. Have 4 different layout: content-sidebar,sidebar-content, full content (long), full content(short). 13 different theme styles, Google Fonts, logo upload abilities. Unique 2 feature builtin: Promotion Bar and ShowCase. All options are using WordPress Theme Customizer feature so you can watch changes live! Designers: This theme built on Twitter Bootstrap, have 0 custom css code and using template_part system so you can easily use it as parent theme! You can find detailed information, showcase, live demo, tips and tricks about theme in: http://theme.firmasite.com/", 'firmasite');

// This is not using but need for theme review
if ( ! isset( $content_width ) ) $content_width = 900;


add_action('after_setup_theme', "firmasite_setup" );
function firmasite_setup() {

	// Make theme available for translation
	load_theme_textdomain( 'firmasite', get_template_directory() . '/languages');

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	 // http://codex.wordpress.org/Title_Tag
	add_theme_support( 'title-tag' );

	// Register wp_nav_menu() menus (http://codex.wordpress.org/Function_Reference/register_nav_menus)
	register_nav_menus(array(
		'main_menu' => esc_attr__('Main Menu', 'firmasite'),
	));
	
	register_nav_menus(array(
		'footer_menu' => esc_attr__('Footer Menu', 'firmasite'),
	));
  
	add_theme_support( "buddypress" );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );
  
	// Add post thumbnails (http://codex.wordpress.org/Post_thumbnails)
	add_theme_support('post-thumbnails');
	
	// Enable Bootstrap
	add_theme_support('firmasite-bootstrap');  
	
	// Enable Bootstrap's fixed navbar
	add_theme_support('firmasite-bootstrap-top-navbar'); 
	
	// Feed Links
	add_theme_support( 'automatic-feed-links' );
	
	// Custom Background
	add_theme_support( 'custom-background' );
	
	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat'
	) );
  
}



// Sidebars
add_action( 'widgets_init', "firmasite_init" );
function firmasite_init(){
		register_sidebar( array(
			'id' => 'site-sidebar',
			'name' => esc_attr__( 'Sidebar', 'firmasite' ),
			'description' => esc_attr__( 'Widgets that shows in sidebar', 'firmasite' ),
			'before_widget' => '<article id="%1$s" class="widget clearfix %2$s">',
			'after_widget' => '</article>',
			'before_title' => '<h4>',
			'after_title' => '</h4>'
		));
		register_sidebar( array(
			'id' => 'footer-middle',
			'name' => esc_attr__( 'Footer', 'firmasite' ),
			'description' => esc_attr__( 'Widgets that shows in footer', 'firmasite' ),
			'before_widget' => '<article id="%1$s" class="widget clearfix col-xs-12 col-md-4 %2$s dropup">',
			'after_widget' => '</article>',
			'before_title' => '<h4>',
			'after_title' => '</h4>'
		));
}


add_action('wp_enqueue_scripts', "firmasite_enqueue_script" );
function firmasite_enqueue_script() {
	
	global $firmasite_settings;
	
	// Deregister WordPress comment-reply script
    wp_deregister_script('comment-reply');
    // Register our own comment-reply script for wysiwyg support
    wp_register_script('comment-reply', get_template_directory_uri() .'/assets/js/comment-reply.min.js');

	// Deregister WordPress masonry script because its outdated
    wp_deregister_script('jquery-masonry');
    // Register our own masonry script
    wp_register_script('jquery-masonry', get_template_directory_uri() .'/assets/js/masonry.pkgd.min.js', array('jquery'), false, true);


	// Comment
	if(!isset($firmasite_settings["comments"]) || $firmasite_settings["comments"] != true )
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Google Font
	$firmasite_settings["fonts"] = "";
	if (isset($firmasite_settings["font"]) && !empty($firmasite_settings["font"])){
		$firmasite_settings["fonts"] .= $firmasite_settings["font"] . ':300,400,700';
		$firmasite_settings["fonts"] .= "|";
	}
	if (isset($firmasite_settings["headerfont"]) && !empty($firmasite_settings["headerfont"])){
		$firmasite_settings["fonts"] .= $firmasite_settings["headerfont"] . ':300,400,700';
	}
	if (isset($firmasite_settings["fonts"]) && !empty($firmasite_settings["fonts"])){
		$firmasite_data_subsets = $firmasite_settings["subsets"];
		if (!empty($firmasite_data_subsets)) $firmasite_data_subsets = "," . $firmasite_data_subsets;
		wp_enqueue_style( 'google-webfonts', $firmasite_settings["protocol"] . '://fonts.googleapis.com/css?family=' . $firmasite_settings["fonts"] . '&amp;subset=latin'. $firmasite_data_subsets );
		add_action ("wp_head", "firmasite_customcss_googlefont",8);
		function firmasite_customcss_googlefont() {
			global $firmasite_settings;
			?>
			<style type="text/css" media="screen">
			<?php if (isset($firmasite_settings["font"]) && !empty($firmasite_settings["font"])){ ?>
				body, h1, h2, h3, h4, h5, h6, .btn, .navbar { font-family: <?php echo $firmasite_settings["font"]; ?>,sans-serif !important;}
			<?php } ?>
			<?php if (isset($firmasite_settings["headerfont"]) && !empty($firmasite_settings["headerfont"])){ ?>
				h1, h2, h3, h4, h5, h6, #logo .logo-text, .hero-title { font-family: <?php echo $firmasite_settings["headerfont"]; ?>,sans-serif !important;}
 			<?php } ?>
           </style>
			<?php
		}
	}

	// Make menus clickable
	if(!isset($firmasite_settings["hover-nav"]) || $firmasite_settings["hover-nav"] != true )
		add_action("wp_footer", "firmasite_hover_nav",900);	
		function firmasite_hover_nav() {
			// adding an empty block for visible checking
			?>
			<script type="text/javascript">
				(function ($) {
					<?php if (!FIRMASITE_COMBINE_JS) echo firmasite_hover_nav_combine(); ?>
				})(jQuery);
			</script>
		<?php
		}
	
	// add ie conditional html5 shim to header
	global $is_IE;
	if ($is_IE) {
		// Combined to html5.js below for internal sites
		//wp_register_script ('html5shim', $firmasite_settings["protocol"] . "://html5shiv.googlecode.com/svn/trunk/html5.js");
		//wp_enqueue_script ('html5shim');
		
		wp_register_script ('html5', get_template_directory_uri() . '/assets/js/html5.js');
		wp_enqueue_script ('html5');
		wp_register_script ('respond-js', get_template_directory_uri() . '/assets/js/respond.min.js');
		wp_enqueue_script ('respond-js');
		
		$firmasite_settings["layout_page_class"] = $firmasite_settings["layout_page_class"]. " browser_ie";
	}

	// bootstrap
	wp_register_style( 'bootstrap', $firmasite_settings["styles_url"][$firmasite_settings["style"]] . '/bootstrap.min.css' );
	wp_enqueue_style( 'bootstrap' );

 	/*if (isset($firmasite_settings["no-responsive"]) && !empty($firmasite_settings["no-responsive"])) {
	} */
	
	// style
	wp_register_style( 'firmasite-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'firmasite-style' );
	
	// bootstrap-js
	wp_register_script(
		'bootstrap',
		get_template_directory_uri() . '/assets/js/bootstrap.min.js',
		array('jquery'),
		false, // $ver
		true // $in_footer
	);
	wp_enqueue_script( 'bootstrap' );
 
	// No responsive solution
	if (isset($firmasite_settings["no-responsive"]) && !empty($firmasite_settings["no-responsive"])){
		wp_enqueue_style( 'no-responsive', get_template_directory_uri() . '/assets/css/no-responsive.css' );	
	}

	// Conditional check blocks for javascript. Usage:
	// if (visible_lg())
	add_action ("wp_footer", "firmasite_init_footer",1);
	function firmasite_init_footer() {
		?>
		<div id="visible-lg" class="visible-lg"></div>
		<div id="visible-md" class="visible-md"></div>
		<div id="visible-sm" class="visible-sm"></div>
		<div id="visible-xs" class="visible-xs"></div>
		<?php
	}

	//throttle
	add_action ("wp_head", "firmasite_init_head",11);
	function firmasite_init_head() {
		?>
		<script type="text/javascript">
			function visible_lg(){ return (jQuery("#visible-lg").css("display") === "block") ? true : false; }
			function visible_md(){ return (jQuery("#visible-md").css("display") === "block") ? true : false; }
			function visible_sm(){ return (jQuery("#visible-sm").css("display") === "block") ? true : false; }
			function visible_xs(){ return (jQuery("#visible-xs").css("display") === "block") ? true : false; }
			
			// http://remysharp.com/2010/07/21/throttling-function-calls/
			function throttle(d,a,h){a||(a=250);var b,e;return function(){var f=h||this,c=+new Date,g=arguments;b&&c<b+a?(clearTimeout(e),e=setTimeout(function(){b=c;d.apply(f,g)},a)):(b=c,d.apply(f,g))}};
        </script>
		<?php
	}


	// Combine js
	if (FIRMASITE_COMBINE_JS){
		$firmasite_js_version = get_transient("firmasite_js_version");
		if (false == $firmasite_js_version) {
			 $firmasite_js_version = time();
			 set_transient("firmasite_js_version", $firmasite_js_version, 60*60*12);
		}
		
		$js_file = add_query_arg( 'firmasite_combine_js', $firmasite_js_version, home_url("/") );
		wp_register_script('firmasite-combine-js', $js_file, array('jquery'), null, true);
		wp_enqueue_script( 'firmasite-combine-js' );
	}
}

function firmasite_masonry_implement($loop_tile = null,$masonry_id = null) {
	global $firmasite_settings, $firmasite_loop_tile_count, $firmasite_masonry_implement;
	//For each masonry container, you have to call:
	// echo firmasite_masonry_implement(); 
	// after loop
	if (!isset($masonry_id))
		$masonry_id = $firmasite_loop_tile_count;
	if (!isset($loop_tile))
		$loop_tile = "firmasite_loop_tile" . $masonry_id;
	$loop_item = 'loop_tile_' .$masonry_id. '_item';
	$loop_grid = 'loop_tile_' .$masonry_id. '_grid';
	ob_start();
	?>
	<script type="text/javascript">
		(function ($) {
			var <?php echo $loop_tile; ?> = $("#<?php echo $loop_tile; ?>");
			$(window).resize(throttle(function(){
				<?php if($firmasite_settings["loop_tile_row"] > 3){ ?>
					if(<?php echo $loop_tile; ?>.width() < 940){
						<?php echo $loop_tile; ?>.find(".<?php echo $loop_item; ?>.col-md-<?php echo round(12 / $firmasite_settings["loop_tile_row"]); ?>,.<?php echo $loop_grid; ?>.col-md-<?php echo round(12 / $firmasite_settings["loop_tile_row"]); ?>").each(function(){
							$(this).removeClass("col-md-<?php echo round(12 / $firmasite_settings["loop_tile_row"]); ?>").addClass("col-md-4");
						});	
					}
					if(<?php echo $loop_tile; ?>.width() < 720){
						<?php echo $loop_tile; ?>.find(".<?php echo $loop_item; ?>.col-md-4,.<?php echo $loop_grid; ?>.col-md-4").each(function(){
							$(this).attr('data-loop_tile<?php echo $masonry_id; ?>-class-removed', "col-md-4").removeClass("col-md-4").addClass("col-md-6");
						});	
					} else if(<?php echo $loop_tile; ?>.width() >= 720){
						$('[data-loop_tile<?php echo $masonry_id; ?>-class-removed]').each(function(){
							$(this).removeClass("col-md-6").addClass($(this).attr('data-loop_tile<?php echo $masonry_id; ?>-class-removed')).removeAttr('data-loop_tile<?php echo $masonry_id; ?>-class-removed');
						});											
					} else {
						<?php echo $loop_tile; ?>.find(".<?php echo $loop_item; ?>,.<?php echo $loop_grid; ?>").each(function(){
							$(this).removeClass("col-md-4").addClass("col-md-<?php echo round(12 / $firmasite_settings["loop_tile_row"]); ?>");
						});	
					}
				<?php } else { ?>
					if(<?php echo $loop_tile; ?>.width() < 720){
						<?php echo $loop_tile; ?>.find(".<?php echo $loop_item; ?>.col-md-<?php echo round(12 / $firmasite_settings["loop_tile_row"]); ?>,.<?php echo $loop_grid; ?>.col-md-<?php echo round(12 / $firmasite_settings["loop_tile_row"]); ?>").each(function(){
							$(this).attr('data-loop_tile<?php echo $masonry_id; ?>-class-removed', "col-md-<?php echo round(12 / $firmasite_settings["loop_tile_row"]); ?>").removeClass("col-md-<?php echo round(12 / $firmasite_settings["loop_tile_row"]); ?>").addClass("col-md-6");
						});	
					} else {
						$('[data-loop_tile<?php echo $masonry_id; ?>-class-removed]').each(function(){
							$(this).removeClass("col-md-6").addClass($(this).attr('data-loop_tile<?php echo $masonry_id; ?>-class-removed')).removeAttr('data-loop_tile<?php echo $masonry_id; ?>-class-removed');
						});											
					}					
				<?php } 
				// for no animation, use:
				// transitionDuration: 0
				?>
				<?php echo $loop_tile; ?>.masonry({ columnWidth: ".<?php echo $loop_grid; ?>", itemSelector: '.<?php echo $loop_item; ?>', isAnimated : true});
			},250));
		})(jQuery);
	</script>
	<?php
	$firmasite_masonry_implement .= ob_get_clean();
	add_action( 'wp_footer', "firmasite_masonry_implement_print", 901);
}
function firmasite_masonry_implement_print(){
	global $firmasite_masonry_implement; 
	echo $firmasite_masonry_implement;
	?>
	<script type="text/javascript">
	jQuery('#page').imagesLoaded( throttle(function() {
		jQuery(window).trigger("resize");
	}, 150));
	</script>
	<?php
}

/**
 * Update Js version on every event that possible changed included js
 */
add_action( 'after_switch_theme',  'firmasite_update_js_version'); // Switch theme
add_action( 'customize_save_after', 'firmasite_update_js_version'); // Save settings in Theme Customizer
add_action( 'activated_plugin', 'firmasite_update_js_version'); // After activated a plugin
add_action( 'deactivated_plugin', 'firmasite_update_js_version'); // After deactivated a plugin
function firmasite_update_js_version() {
	$firmasite_js_version = time();
	set_transient("firmasite_js_version", $firmasite_js_version, 60*60*12);
}

if (FIRMASITE_COMBINE_JS) {
	add_filter('query_vars','firmasite_combine_js_add_trigger');
	function firmasite_combine_js_add_trigger($vars) {
		$vars[] = 'firmasite_combine_js';
		return $vars;
	}
	 
	add_action('template_redirect', 'firmasite_combine_js_trigger_check');
	function firmasite_combine_js_trigger_check() {
		$js_file = intval(get_query_var('firmasite_combine_js'));
		if(is_front_page() && $js_file) { 

			header( 'Content-Type: application/javascript' );
			
			//get a unique hash of this file (etag)
			$etagFile = md5_file(__FILE__);
			
			//get the HTTP_IF_NONE_MATCH header if set (etag: unique file hash)
			$etagHeader=(isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);
			
			//set etag-header
			header("Etag: $etagFile");
			
			//set last-modified header
			$firmasite_js_version = get_transient("firmasite_js_version");
			header("Last-Modified: ".gmdate('D, d M Y H:i:s \G\M\T', $firmasite_js_version)." GMT");
			
			//make sure caching is turned on
			header('Cache-Control: public');
			header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 60*60*12));
			
			//check if page has changed. If not, send 304 and exit
			if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'])==$firmasite_js_version || $etagHeader == $etagFile){
			   status_header(304);
			   //exit;
			} else {
			   status_header(200);
			}
			?>
			<?php do_action("firmasite_combine_js_before"); ?>
            
             (function ($) {
                <?php do_action("firmasite_combine_js"); ?>
             } ( jQuery ) );
            
             <?php do_action("firmasite_combine_js_after"); ?>

		<?php exit;
		}
	}	
	
	add_action( 'firmasite_combine_js', "firmasite_hover_nav_combine");
}
function firmasite_hover_nav_combine(){ 
	global $firmasite_settings;
	if(!isset($firmasite_settings["hover-nav"]) || $firmasite_settings["hover-nav"] != true ) {?>
		function firmasite_hover_nav() {
			var $hover_nav_style = "<style id='hover-nav' type='text/css'> ul.nav li.dropdown:hover > .dropdown-menu{ display: block; } .nav-tabs .dropdown-menu, .nav-pills .dropdown-menu, .navbar .dropdown-menu { margin-top: 0; margin-bottom: 0; } <?php global $is_IE; if ($is_IE) { ?>.browser_ie ul.nav li.dropdown:hover > .dropdown-menu{ display: block; } .browser_ie .nav-tabs .dropdown-menu, .browser_ie .nav-pills .dropdown-menu, .browser_ie .navbar .dropdown-menu { margin-top: 0; margin-bottom: 0; }<?php } ?></style>";
			var $hover_style_inserted = $("style#hover-nav");
			var $bootstrap_css = $("link#bootstrap-css");
            if (visible_md() || visible_lg()){
				if(!$hover_style_inserted.length) {
                	if($bootstrap_css.length) {
                    	$bootstrap_css.after($hover_nav_style);
                    } else {
                    	$("head").append($hover_nav_style);
                    }
                    $('a.dropdown-toggle').each(function(){
                        var data_toggle = $(this).attr('data-toggle');
                        $(this).attr('data-toggle-removed',data_toggle).removeAttr('data-toggle');
                    });
                }						
			} else {
				$hover_style_inserted.remove();
				$('[data-toggle-removed]').each(function(){
					var data_toggle_removed = $(this).attr('data-toggle-removed');
					$(this).attr('data-toggle',data_toggle_removed).removeAttr('data-toggle-removed');
				});						
			}
		}
		$(window).resize(throttle(function(){
        	firmasite_hover_nav();
		},250));
<?php }
}



/**
 * Functions
 */
require_once ( get_template_directory() . '/functions/nav.php');					// Custom nav modifications
require_once ( get_template_directory() . '/functions/customizer.php');				// Customizer
require_once ( get_template_directory() . '/functions/template-tags.php');			// 

require_once ( get_template_directory() . '/functions/fix.php');					// Little fix Functions
require_once ( get_template_directory() . '/functions/showcase.php');				// ShowCase
require_once ( get_template_directory() . '/functions/promotionbar.php');			// PromotionBar

require_once ( get_template_directory() . '/functions/infinite-scroll.php'); // infinite-scroll
require_once ( get_template_directory() . '/functions/honeypot.php');			// Honeypot

// Sadly we cant include csstidy. WordPress Theme Directory's automatic code checking system is not accepting it.
// You have 2 option for including css checker:
// 1: install jetpack and activate custom css or
// 2: install firmasite theme enhancer plugin
// You should remove "if ( class_exists('safecss') )" from file below when you copy files
require_once ( get_template_directory() . '/functions/custom-custom-css.php');	// Custom Css.		
require_once ( get_template_directory() . '/functions/shortcodes.php');			// Shortcodes
require_once ( get_template_directory() . '/functions/plugins.php');			// Buddypress + bbPress



/**
 * Include the TGM_Plugin_Activation class.
 */
require_once ( get_template_directory() . '/functions/class-tgm-plugin-activation.php');
add_action( 'tgmpa_register', 'firmasite_register_required_plugins' );

/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function firmasite_register_required_plugins() {

	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = apply_filters("firmasite_required_plugins", array(

		// This is an example of how to include a plugin from the WordPress Plugin Repository
		array(
			'name' 		=> __('FirmaSite Theme Enhancer', 'firmasite' ),
			'slug' 		=> 'firmasite-theme-enhancer',
			'required' 	=> false,
			'version' 	=> '1.5.0',
		),
	));

	// Change this to your theme text domain, used for internationalising strings
	$theme_text_domain = 'firmasite';

	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$config = apply_filters("firmasite_required_plugins_config", array(
		'domain'       		=> $theme_text_domain,         	// Text domain - likely want to be the same as your theme.
		'default_path' 		=> '',                         	// Default absolute path to pre-packaged plugins
		'parent_menu_slug' 	=> 'themes.php', 				// Default parent menu slug
		'parent_url_slug' 	=> 'themes.php', 				// Default parent URL slug
		'menu'         		=> 'install-required-plugins', 	// Menu slug
		'has_notices'      	=> true,                       	// Show admin notices or not
		'is_automatic'    	=> false,					   	// Automatically activate plugins after installation or not
		'message' 			=> '',							// Message to output right before the plugins table
		'strings'      		=> array(
			'page_title'                       			=> __( 'Install Required Plugins', $theme_text_domain ),
			'menu_title'                       			=> __( 'Install Plugins', $theme_text_domain ),
			'installing'                       			=> __( 'Installing Plugin: %s', $theme_text_domain ), // %1$s = plugin name
			'oops'                             			=> __( 'Something went wrong with the plugin API.', $theme_text_domain ),
			'notice_can_install_required'     			=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s)
			'notice_can_install_recommended'			=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_install'  					=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
			'notice_can_activate_required'    			=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_activate' 					=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
			'notice_ask_to_update' 						=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_update' 						=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
			'install_link' 					  			=> _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
			'activate_link' 				  			=> _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
			'return'                           			=> __( 'Return to Required Plugins Installer', $theme_text_domain ),
			'plugin_activated'                 			=> __( 'Plugin activated successfully.', $theme_text_domain ),
			'complete' 									=> __( 'All plugins installed and activated successfully. %s', $theme_text_domain ), // %1$s = dashboard link
			'nag_type'									=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
		)
	));

	tgmpa( $plugins, $config );

}
