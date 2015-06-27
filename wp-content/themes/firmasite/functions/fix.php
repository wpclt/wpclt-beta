<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


// - - - - - - - - - - - - - - - - - - - - 
// We are adding javascript default caller
// - - - - - - - - - - - - - - - - - - - - 
if (FIRMASITE_COMBINE_JS) {
	add_action( 'firmasite_combine_js', "firmasite_bootstrapjs_init_combine",2);
} else {
	add_action( 'wp_footer', "firmasite_bootstrapjs_init" ,900);
}
function firmasite_bootstrapjs_init() {
  ?>
  <script type="text/javascript">	
  (function ($) {
	  <?php echo firmasite_bootstrapjs_init_combine(); ?>
  })(jQuery);
  </script>
  <?php
}
function firmasite_bootstrapjs_init_combine() {
  ?>
	var $carousels = $('[data-rel=carousel]');
	function firmasite_edits(){
        $inputs = $("input");
        $inputs.filter("[type='submit'], [type='button']").addClass("btn btn-default");
        $inputs.filter("[type='text'], :not([type]), [type='password'], [type='search'], [type='email']").addClass("form-control"); //not([type]) included as browser defaults to text when attribute not present
		$("textarea").addClass("form-control");
		$("select").addClass("form-control");
		$("table").addClass("table");
    	$("dl").addClass("dl-horizontal");

		$("li.selected").addClass("active");//current
		$("li.current").addClass("active");//current
        $("ul.page-numbers").addClass("pagination pagination-lg");
        $(".pager").find("ul.pagination").removeClass("pagination pagination-lg");
		$('[data-toggle=tooltip]').tooltip();
		$('[data-toggle=popover]').popover();
	}
	$(document).ready(function() {
		firmasite_edits();
		$('.widget').find("ul").addClass("list-unstyled");
		$carousels.carousel({interval: 6000});
	});
	$(document).on("DOMNodeInserted", throttle(function(){
    	firmasite_edits();
    }, 250));
    $('[data-toggle=dropdown]').on('click.bs.dropdown', function () {
		<?php // if small device, make sure dropdowns always stay inside of screen ?>
        if (visible_xs() || visible_sm()) { 
            var menu = $(this).parent().find("ul:first");
            var menupos = $(this).offset();

            if($(this).parent().hasClass("pull-right")){
                menupos_right = $(window).width() - (menupos.left + $(this).outerWidth());
                if (menupos_right + menu.width() > $(window).width()) {
                    var newpos = -(menupos_right + menu.width() - $(window).width());
                    menu.css({ right: newpos });    
                }
            } else {
                 if (menupos.left + menu.width() > $(window).width()) {
                    var newpos = -(menupos.left + menu.width() - $(window).width());
                    menu.css({ left: newpos });    
                }
            }
		}	
    });   
    //Stack menu when collapsed
    $('.simple-menu-collapse').on('show.bs.collapse', function() {
        $('.nav-pills').addClass('nav-stacked');
    });
    
    //Unstack menu when not collapsed
    $('.simple-menu-collapse').on('hide.bs.collapse', function() {
        $('.nav-pills').removeClass('nav-stacked');
    });     
	

  <?php
}

// - - - - - - - - - - - - - - - - - - - - 
// We are adding last triggers
// - - - - - - - - - - - - - - - - - - - - 
add_action( 'wp_footer', "firmasite_js_last_triggers_container" ,999);
function firmasite_js_last_triggers_container() {
  ?>
  <script type="text/javascript">	
  (function ($) {
	$(window).load().trigger("resize");
  })(jQuery);
  </script>
  <?php
}

// - - - - - - - - - - - - - - - - - - - - 
// We are adding navigation and comment system to bottom of content
// - - - - - - - - - - - - - - - - - - - - 
add_action('close_content', "firmasite_navigation_bottom" ,900);
function firmasite_navigation_bottom() {
	global $firmasite_settings;

	if(!isset($firmasite_settings["comments"]) || $firmasite_settings["comments"] != true ) {
		// If comments are open or we have at least one comment, load up the comment template
		if ( comments_open() || '0' != get_comments_number() ) 
			comments_template( '', true );		
	}
	
	ob_start();
	// Adding breadcrumb
	if ( function_exists('yoast_breadcrumb') ) {
		yoast_breadcrumb('<li class="active"><i class="icon-home"></i> ','</li>');
	}
		
	
	$breadcrumbs_bottom = ob_get_contents();
	ob_end_clean();
	
	if (!empty($breadcrumbs_bottom)) { ?>	
	<ul id="breadcrumbs-bottom" class="breadcrumb">
		<?php echo $breadcrumbs_bottom; ?>	
	</ul>
	<?php } 
   	ob_start();
	firmasite_content_nav( 'nav-below' );
	$nav_below = ob_get_contents();
	ob_end_clean();
	$nav_below = apply_filters("firmasite_nav_below", $nav_below);
	
	if (!empty($nav_below)) { ?>	
	<div id="pagination-bottom" class="pager lead">
		<?php echo $nav_below; ?>	
	</div>
	<?php 
	} 
}


// - - - - - - - - - - - - - - - - - - - - 
// We are adding navigation to top of content
// - - - - - - - - - - - - - - - - - - - - 
add_action('open_content', "firmasite_navigation_top");
function firmasite_navigation_top() {
	ob_start();
	// Adding breadcrumb
	if ( function_exists('yoast_breadcrumb') ) {
		yoast_breadcrumb('<li class="active"><i class="icon-home"></i> ','</li>');
	}
		
	$breadcrumbs = ob_get_contents();
	ob_end_clean();
	if (!empty($breadcrumbs)) {
?>	
	<ul id="breadcrumbs" class="breadcrumb">
		<?php echo $breadcrumbs; ?>	
	</ul>	
<?php
	}
}


/* 
 * We are adding archive specific header & content to top
 */ 
add_action('open_loop', "firmasite_loop_archives", 0);
function firmasite_loop_archives() {
	if ( is_archive() && !is_post_type_archive() ){ 
	?>
        <header class="archive-header">
            <h1 class="page-header page-title archive-title"><?php
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                if ( is_day() ) {
					echo "<strong>";
                    printf( __( 'Daily Archives: %s', 'firmasite' ), get_the_date() );
					echo "</strong>";
					if(1 < $paged) echo "<small> - " . sprintf(__("%s. page", 'firmasite'), $paged) . "</small>";
				} elseif ( is_month() ) {
					echo "<strong>";
                    printf( __( 'Monthly Archives: %s', 'firmasite' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'firmasite' ) ) );
					echo "</strong>";
					if(1 < $paged) echo "<small> - " . sprintf(__("%s. page", 'firmasite'), $paged) . "</small>";
				} elseif ( is_year() ) {
					echo "<strong>";
                    printf( __( 'Yearly Archives: %s', 'firmasite' ), get_the_date( _x( 'Y', 'yearly archives date format', 'firmasite' ) ) );
					echo "</strong>";
					if(1 < $paged) echo "<small> - " . sprintf(__("%s. page", 'firmasite'), $paged) . "</small>";
				}elseif ( is_author() ) {
					$author = get_queried_object();
					printf( '<strong>' . __( 'All posts by %s', 'firmasite' ) . '</strong>', '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( $author->ID ) ) . '" title="' . esc_attr( $author->display_name ) . '" rel="me">' . esc_attr( $author->display_name ) . '</a></span>' ); 
					if(1 < $paged) echo "<small> - " . sprintf(__("%s. page", 'firmasite'), $paged) . "</small>";
				   echo '<small><p>' . get_the_author_meta('description',$author->ID) . '</p></small>';
				} elseif ( is_tax() || is_category() || is_tag() ) {
					echo "<strong>";
               		single_term_title();
					echo "</strong>";
					if(1 < $paged) echo "<small> - " . sprintf(__("%s. page", 'firmasite'), $paged) . "</small>";
					echo '<small>' . term_description('', false) . '</small>';
				} 
            ?></h1>
        </header><!-- .archive-header -->
	<?php
    }

}



// Shorter excerpt is better for promotion bar
add_filter( 'excerpt_length', 'firmasite_excerpt_length' );
function firmasite_excerpt_length( $length ) {
	return 30;
}


// Tell the TinyMCE editor to use a custom stylesheet
add_filter('the_editor_content', "firmasite_tinymce_style");
function firmasite_tinymce_style($content) {
	global $firmasite_settings,$wp_version;
	add_editor_style('style.css');
	add_editor_style('assets/css/wpeditor.php');
	
	// This is for front-end tinymce customization
	if ( ! is_admin() ) {
		global $editor_styles;
		
		$editor_styles = (array) $editor_styles;
		$stylesheet    = array();
		
		$stylesheet[] = 'style.css';
		$stylesheet[] = 'assets/css/wpeditor.php';		

		$editor_styles = array_merge( $editor_styles, $stylesheet );
		
	}
	return $content;
}


// - - - - - - - - - - - - - - - - - - - - 
// We are adding tinymce to comments
// http://www.techytalk.info/add-tinymce-quicktags-visual-editors-wordpress-comments-form/
// - - - - - - - - - - - - - - - - - - - - 
function firmasite_wp_editor($content = '', $editor_id = 'comment', $textarea_name = "", $settings = array()){
    ob_start();
	if (empty($textarea_name)) { $textarea_name = $editor_id; }
	$defaults = apply_filters( 'firmasite_front_editor', array(
        'media_buttons' => false,
		'textarea_name' => $textarea_name,
        'textarea_rows' => '3',
        'tinymce' => array(
            'toolbar1' => 'bold, italic, underline, strikethrough',
            'toolbar2' => '',
			'body_class' => "panel panel-default"
            ),
        'quicktags' => array('buttons' => 'strong,em,block,del,code,close')
        ));
	$settings = wp_parse_args($settings, $defaults);
	
    wp_editor( $content, $editor_id, $settings );
 
    return ob_get_clean();
}

add_filter('comment_form_defaults', "firmasite_comment_editor");
function firmasite_comment_editor($args) {
    $args['comment_field'] = firmasite_wp_editor();
    return $args;
}


// - - - - - - - - - - - - - - - - - - - - 
// We are adding btn class to more link
// - - - - - - - - - - - - - - - - - - - -
add_filter( 'the_content_more_link', 'firmasite_more_link_scroll' );
function firmasite_more_link_scroll( $link ) {
	$link = str_replace( 'class="more-link"', 'class="more-link btn btn-default btn-xs"', $link );
	return $link;
}


// http://wordpress.stackexchange.com/questions/54700/why-time-functions-show-invalid-time-zone-when-using-c-time-format
add_filter( 'date_i18n', 'firmasite_fix_c_time_format', 10, 4 );
function firmasite_fix_c_time_format( $date, $format, $timestamp, $gmt ) {
    if ( 'c' == $format )
        $date = date_i18n( DATE_ISO8601, $timestamp, $gmt );
    return $date;
}


// Fix for custom menu widget
// http://wordpress.stackexchange.com/questions/53950/add-a-custom-walkter-to-a-menu-created-in-a-widget
// http://wpsmith.net/2011/tutorials/how-to-add-menu-descriptions-featured-images-to-wordpress-menu-items/
add_filter( 'wp_nav_menu_args', "firmasite_fix_widget_custommenu" );
function firmasite_fix_widget_custommenu( $args ) {
	global $firmasite_settings;
	if(!isset($firmasite_settings['menu_locations'])){
		$menu_locations = get_registered_nav_menus();
		foreach ($menu_locations as $menu_id => $menu_name) {
			$firmasite_settings['menu_locations'][] = $menu_id;
		}
	}
	if ( !in_array($args['theme_location'], $firmasite_settings['menu_locations']) ) {
		return array_merge( $args, array(
			// we dont want to effect main-menu
			'items_wrap' => '<div class="panel panel-default"><ul id="%1$s" class="%2$s nav nav-pills nav-stacked panel-body">%3$s</ul></div>',
			// another setting go here ... 
		) );
	} else {
		return $args;
	}
}


// Adding class="thumbnail" to <a> for bootstrap
// http://codex.wordpress.org/Function_Reference/the_post_thumbnail
add_filter( 'post_thumbnail_html', "firmasite_fix_thumbnail_html" , 900, 3 );
function firmasite_fix_thumbnail_html( $html, $post_id, $post_image_id ) {
	if ( ! is_admin() )
		$html = '<a href="' . get_permalink( $post_id ) . '" title="' . esc_attr( get_post_field( 'post_title', $post_id ) ) . '" class="thumbnail">' . $html . '</a>';
  return $html;

}


/*
 * This function makes pagination links compatible with bootstrap
 */
add_filter( 'wp_link_pages_link', 'firmasite_fix_wp_link_pages_link', 10, 2);
function firmasite_fix_wp_link_pages_link($link, $i ){
	return '<li>' . $link . '</li>';
}


/*
 * This function adds a clearfix after content
 */
add_action( 'close_content', 'firmasite_fix_close_loop',1);
function firmasite_fix_close_loop(){
	?><div class="clearfix remove-for-infinite"></div><?php
}

/*
 * This function adds custom icon fonts to list
 */
add_action( 'firmasite_icons_charmap', 'firmasite_add_custom_icon_font');
function firmasite_add_custom_icon_font(){
	?>
	<script>
	iconscharmap.unshift(["icon-try"]);
	iconscharmap.unshift(["icon-wordpress"]);
	</script>
	<?php
}


/*
 * This function fix some settings
 */
add_action( 'after_setup_theme', "firmasite_settings_fix_unregister_theme_style", 11);
// firmasite_settings_close action already fired normally but this one is for customizer preview in admin panel
add_action( 'firmasite_settings_close', "firmasite_settings_fix_unregister_theme_style", 11);
function firmasite_settings_fix_unregister_theme_style(){
	global $firmasite_settings;
	/*
	 * Child Themes can add/remove custom bootstrap styles. 
	 * This function is using last option from style list when selected bootstrap style does not registered in active theme
	 */
	if (!array_key_exists($firmasite_settings["style"], $firmasite_settings["styles"])) {
		if(0 < count($firmasite_settings["styles"])) {
			// last option from style list
			$array = array_keys($firmasite_settings["styles"]);
			$firmasite_settings["style"] = array_pop($array);			
		} else {
			// style list is empty.. fallback to united
			$firmasite_settings["style"] = $firmasite_settings["default_style"];
		}
	}
	/*
	 * loop_tile row count increase by 1 for site-content-long
	 */
	if ("only-content-long" == $firmasite_settings["layout"]) 
		$firmasite_settings["loop_tile_row"]++;	
}

