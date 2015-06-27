<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

// removing default plugin css.. bootstrap is enough :)
add_action( 'bbp_theme_compat_actions', 'firmasite_social_remove_default_style' );
function firmasite_social_remove_default_style( $BBP_Default ) {
    remove_action( 'bbp_enqueue_scripts', array( $BBP_Default, 'enqueue_styles'  ) );
}


// We are adding thumbnail support for forums
add_action('init', 'firmasite_social_bbpress_init');
function firmasite_social_bbpress_init() {
	add_post_type_support( 'forum', 'thumbnail' );

}


add_action('wp_enqueue_scripts', "firmasite_bbpress_enqueue_script" );
function firmasite_bbpress_enqueue_script() {
	// Deregister WordPress comment-reply script
    wp_deregister_script('bbpress-reply');
    // Register our own comment-reply script for wysiwyg support
    wp_register_script('bbpress-reply', get_template_directory_uri() .'/assets/js/bbpress-reply.js');
}


// We are adding bbpress support for theme special systems
/*add_filter('firmasite_pre_get_posts_ekle', 'firmasite_bbpress_pre_get_posts_ekle');
function firmasite_bbpress_pre_get_posts_ekle($array) {
	$array[] = "forum";
	$array[] = "topic";
	return $array;
}*/


// bootstrapped bbPress breadcrumbs 
add_filter( 'bbp_before_get_breadcrumb_parse_args', "firmasite_bbp_get_breadcrumb", 10, 1);
function firmasite_bbp_get_breadcrumb($r){
	
	// HTML
	$r['before'] = '<ul class="bbp-breadcrumb breadcrumb">';
	$r['after' ] = '</ul>';
	
	// Separator
	$r['sep_before'] = '<span class="hide">';
	$r['sep_after'] = '</span>';
	
	// Crumbs
	$r['crumb_before'] = '<li>';
	$r['crumb_after'] = '</li>';

	// Current
	$r['current_before'] = '<span class="bbp-breadcrumb-current text-muted">';
	$r['current_after'] = '</span>';

	return $r;
}



add_filter ( 'bbp_get_topic_class', 'firmasite_social_bbp_get_topic_class', 10, 2);
function firmasite_social_bbp_get_topic_class ($classes, $topic_id) {
	$bbp       = bbpress();
	$count     = isset( $bbp->topic_query->current_post ) ? $bbp->topic_query->current_post : 1;
	if(bbp_is_topic_sticky( $topic_id, false )) {
		$classes[] = 'alert alert-warning panel-body';		
	} else if(bbp_is_topic_super_sticky( $topic_id  )) {
		$classes[] = 'alert alert-danger panel-body';	
	} else if(bbp_is_search()) {
		$classes[] = 'panel-body';
	} else {
		$classes[] = ( (int) $count % 2 ) ? 'panel-footer' : 'panel-body';
	}
	$classes[] = "clearfix media no-margin";
	return $classes;
}


add_filter( 'bbp_get_forum_class', "firmasite_social_bbp_get_forum_class" );
function firmasite_social_bbp_get_forum_class ($classes) {
	$classes[] = " modal firmasite-modal-static";
	return $classes;
}


add_filter( 'bbp_replies_pagination', "firmasite_social_bbp_replies_pagination");
add_filter( 'bbp_topic_pagination', "firmasite_social_bbp_replies_pagination");
function firmasite_social_bbp_replies_pagination($array){
	$array['type'] = 'list';
	
	return $array;
}


function firmasite_social_bbp_get_reply_class_modal() {
	global $firmasite_bbpress_count;
	$class = 	( (int) $firmasite_bbpress_count % 2 ) ? 'panel-footer' : 'panel-body';
	$firmasite_bbpress_count++;
	
	return $class;
}


// freshness link hover
add_filter( 'bbp_get_topic_freshness_link', 'firmasite_social_bbp_get_topic_freshness_link', 10, 5 );
function firmasite_social_bbp_get_topic_freshness_link( $anchor, $topic_id, $time_since, $link_url, $title ) {
	return	 '<a href="' . esc_url( $link_url ) . '" data-toggle="popover" data-rel="popover" data-placement="left" data-trigger="hover" data-html="true" data-original-title="'. __( 'Freshness', 'firmasite' ) . '" data-content="' . esc_attr( $time_since ) . '" title="' . esc_attr( $title ) . '"><i class="icon-time"></i> <span class="visible-xs-inline visible-sm-inline">' . esc_attr( $time_since ) . '</span></a>';
}


// freshness link hover
add_filter( 'bbp_get_forum_freshness_link', 'firmasite_social_bbp_get_forum_freshness_link', 10, 6 );
function firmasite_social_bbp_get_forum_freshness_link( $anchor, $forum_id, $time_since, $link_url, $title, $active_id ) {
	return	 '<a href="' . esc_url( $link_url ) . '" data-toggle="popover" data-rel="popover" data-placement="left" data-trigger="hover" data-html="true" data-original-title="'. __( 'Freshness', 'firmasite' ) .'" data-content="' . esc_attr( $time_since ) . '"><i class="icon-time"></i> <span class="visible-xs-inline visible-sm-inline">' . esc_attr( $time_since ) . '</span></a>&nbsp;'. __( 'Freshness', 'firmasite' ) . ':';
}


add_action( 'bbp_template_notices', 'firmasite_social_bbp_template_notices', 1 );
function firmasite_social_bbp_template_notices(){
	remove_action( 'bbp_template_notices', 'bbp_template_notices' );
	remove_action( 'bbp_template_notices', 'bbp_notice_edit_user_success'           );
	remove_action( 'bbp_template_notices', 'bbp_notice_edit_user_is_super_admin', 2 );
	
	ob_start();
	bbp_template_notices();
	bbp_notice_edit_user_success();
	bbp_notice_edit_user_is_super_admin();
	$bbp_template_notices = ob_get_contents(); 
    ob_get_clean();
	if (!empty($bbp_template_notices)) {
		?>
		<div class="alert alert-warning">
			<?php echo $bbp_template_notices; ?> 
		</div>  
		<?php
	}
}


// Sadly we needed to re-create bbp_get_topic_pagination just because to add 'type' => 'list' to $pagination args -.-'
function firmasite_social_bbp_get_topic_pagination( $args = '' ) {
	global $wp_rewrite;

	$defaults = array(
		'topic_id' => bbp_get_topic_id(),
		'before'   => '<div class="makeit-pag-small">',
		'after'    => '</div>',
	);
	$r = bbp_parse_args( $args, $defaults, 'get_topic_pagination' );
	extract( $r );

	// If pretty permalinks are enabled, make our pagination pretty
	if ( $wp_rewrite->using_permalinks() )
		$base = trailingslashit( get_permalink( $topic_id ) ) . user_trailingslashit( $wp_rewrite->pagination_base . '/%#%/' );
	else
		$base = add_query_arg( 'paged', '%#%', get_permalink( $topic_id ) );

	// Get total and add 1 if topic is included in the reply loop
	$total = bbp_get_topic_reply_count( $topic_id, true );

	// Bump if topic is in loop
	if ( !bbp_show_lead_topic() )
		$total++;

	// Pagination settings
	$pagination = array(
		'type'      => 'list', // yes.. this little bastard is reason to re-create that function
		'base'      => $base,
		'format'    => '',
		'total'     => ceil( (int) $total / (int) bbp_get_replies_per_page() ),
		'current'   => 0,
		'prev_next' => false,
		'mid_size'  => 2,
		'end_size'  => 3,
		'add_args'  => ( bbp_get_view_all() ) ? array( 'view' => 'all' ) : false
	);

	// Add pagination to query object
	$pagination_links = paginate_links( $pagination );
	if ( !empty( $pagination_links ) ) {

		// Remove first page from pagination
		if ( $wp_rewrite->using_permalinks() ) {
			$pagination_links = str_replace( $wp_rewrite->pagination_base . '/1/', '', $pagination_links );
		} else {
			$pagination_links = str_replace( '&#038;paged=1', '', $pagination_links );
		}

		// Add before and after to pagination links
		$pagination_links = $before . $pagination_links . $after;
	}

	return apply_filters( 'bbp_get_topic_pagination', $pagination_links, $args );
}


add_filter( 'bbp_get_user_subscribe_link', 'firmasite_bbp_get_user_subscribe_link' );
function firmasite_bbp_get_user_subscribe_link($html) {
	$html = str_replace('class="subscription-toggle"', 'class="label label-info subscription-toggle"', $html);
	return $html;
}

add_filter( 'bbp_get_user_favorites_link', 'firmasite_bbp_get_user_favorites_link' );
function firmasite_bbp_get_user_favorites_link($html) {
	$html = str_replace('class="favorite-toggle"', 'class="label label-info favorite-toggle"', $html);
	return $html;
}


add_filter( 'bbp_before_get_user_subscribe_link_parse_args', 'firmasite_bbp_before_get_subscribe_link_parse_args' );
add_filter( 'bbp_before_get_topic_subscribe_link_parse_args', 'firmasite_bbp_before_get_subscribe_link_parse_args' );
add_filter( 'bbp_before_get_forum_subscribe_link_parse_args', 'firmasite_bbp_before_get_subscribe_link_parse_args' );
function firmasite_bbp_before_get_subscribe_link_parse_args( $r ) {
	$r['before'] = '<span class="pull-right margin-bot">&nbsp;';
	$r['after'] = ' &nbsp;</span>';
	$r['subscribe'] = '<span class="glyphicon glyphicon-envelope"></span> ' . __( 'Subscribe',   'firmasite' );
	$r['unsubscribe'] = '<span class="glyphicon glyphicon-remove"></span> ' . __( 'Unsubscribe', 'firmasite' );
	return $r;
}

add_filter( 'bbp_before_get_forum_favorite_link_parse_args', 'firmasite_bbp_before_get_favorite_link_parse_args' );
add_filter( 'bbp_before_get_user_favorites_link_parse_args', 'firmasite_bbp_before_get_favorite_link_parse_args' );
function firmasite_bbp_before_get_favorite_link_parse_args( $r ) {
	$r['before'] = '<span class="pull-right margin-bot">&nbsp;';
	$r['after'] = ' &nbsp;</span>';
	$r['favorite'] = '<span class="glyphicon glyphicon-heart"></span> ' . __( 'Favorite',   'firmasite' );
	$r['favorited'] = '<span class="glyphicon glyphicon-remove"></span> ' . __( 'Unfavorite', 'firmasite' );
	return $r;
}

add_filter( 'bbp_before_get_topic_tag_list_parse_args', 'firmasite_bbp_before_get_topic_tag_list_parse_args' );
function firmasite_bbp_before_get_topic_tag_list_parse_args( $r ) {
	$r['before'] = '<div class="bbp-topic-tags"><p><span class="glyphicon glyphicon-tags"></span> ' . esc_html__( 'Tagged:', 'firmasite' ) . '&nbsp;';
	$r['after'] = '</p></div>';
	return $r;
}

// http://bbpress.org/forums/topic/make-notification-of-new-replies-auto-checked/
add_filter( 'bbp_get_form_topic_subscribed', 'firmasite_auto_check_subscribe', 10, 2 );
function firmasite_auto_check_subscribe( $checked, $topic_subscribed  ) {
    if( $topic_subscribed == 0 )
        $topic_subscribed = true;

    return checked( $topic_subscribed, true, false );
}

// https://bbpress.trac.wordpress.org/ticket/2424
add_action( 'bbp_template_redirect', 'firmasite_bbp_single_reply_redirect' );
function firmasite_bbp_single_reply_redirect() {
	if( bbp_is_single_reply() && ! bbp_is_reply_edit() ) {
		wp_redirect( bbp_get_reply_url() ); exit;
	}
}


/**
 * List replies
 *
 * @since bbPress (r4944)
 */
function firmasite_bbp_list_replies( $args = array() ) {

	// Reset the reply depth
	bbpress()->reply_query->reply_depth = 0;

	// In reply loop
	bbpress()->reply_query->in_the_loop = true;

	$r = bbp_parse_args( $args, array(
		'walker'       => null,
		'max_depth'    => bbp_thread_replies_depth(),
		'style'        => 'ul',
		'callback'     => null,
		'end_callback' => null,
		'page'         => 1,
		'per_page'     => -1
	), 'list_replies' );

	// Get replies to loop through in $_replies
	$walker = new FirmaSite_BBP_Walker_Reply;
	$walker->paged_walk( bbpress()->reply_query->posts, $r['max_depth'], $r['page'], $r['per_page'], $r );

	bbpress()->max_num_pages            = $walker->max_pages;
	bbpress()->reply_query->in_the_loop = false;
}


/**
 * Create hierarchical list of bbPress replies.
 *
 * @package bbPress
 * @subpackage Classes
 *
 * @since bbPress (r4944)
 */
if ( class_exists( 'BBP_Walker_Reply' ) ) {
	class FirmaSite_BBP_Walker_Reply extends BBP_Walker_Reply {

		/**
		 * @see Walker:start_el()
		 *
		 * @since bbPress (r4944)
		 */
		public function start_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 ) {

			// Set up reply
			$depth++;
			bbpress()->reply_query->reply_depth = $depth;
			bbpress()->reply_query->post        = $object;
			bbpress()->current_reply_id         = $object->ID;

			// Check for a callback and use it if specified
			if ( !empty( $args['callback'] ) ) {
				call_user_func( $args['callback'], $object, $args, $depth );
				return;
			}

			// Style for div or list element
			if ( 'div' === $args['style'] ) {
				$tag = 'div';
			} else if ($depth>1)  {
				$tag = 'li class="list-unstyled panel panel-default"';
			} else {
				$tag = 'li class="list-unstyled"';
			}?>

			<<?php echo $tag ?>>
				<?php if ($depth>1) { ?>
				<?php bbp_get_template_part( 'loop', 'single-reply-threaded' ); ?>
				<?php } else { ?>
				<?php bbp_get_template_part( 'loop', 'single-reply' ); ?>
				<?php } ?>

			</<?php echo $tag ?>>

			<?php
		}

	}
}







