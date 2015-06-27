<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 *
 * @package firmasite
 * 
 */

if ( ! function_exists( 'firmasite_content_nav' ) ) :
/**
 * Display navigation to next/previous pages when applicable
 *
 */
function firmasite_content_nav( $nav_id ) {
	global $wp_query, $post;

	// Don't print empty markup on single pages if there's nowhere to navigate.
	if ( is_single() ) {
		$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
		$next = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous )
			return;
	}

	// Don't print empty markup in archives if there's only one page.
	if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) )
		return;

	$nav_class = 'site-navigation paging-navigation';
	if ( is_single() )
		$nav_class = 'site-navigation post-navigation';

	/*<nav role="navigation" id="<?php echo $nav_id; ?>" class="<?php echo $nav_class; ?>">*/
	?>

	<?php if ( is_single() ) : // navigation links for single posts ?>

		<?php next_post_link( '<li class="'.$nav_id.' nav-next pull-right">%link</li>', '%title <span class="meta-nav">' . _x( '<i class="icon-arrow-right"></i>', 'Next post link', 'firmasite' ) . '</span>' ); ?>
		<?php previous_post_link( '<li class="'.$nav_id.' nav-previous pull-left">%link</li>', '<span class="meta-nav">' . _x( '<i class="icon-arrow-left"></i>', 'Previous post link', 'firmasite' ) . '</span> %title' ); ?>

	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>
		<?php 
		// http://codex.wordpress.org/Function_Reference/paginate_links
		global $wp_query;
		$big = 999999999; // need an unlikely integer
		
		echo paginate_links( array(
			'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			//'prev_next'    => false,
			'format' => '?paged=%#%',
			'type'         => 'list',
			'current' => max( 1, get_query_var('paged') ),
			'total' => $wp_query->max_num_pages
		) );
		/*
		?>
		<?php if ( get_next_posts_link() ) : ?>
		<li class="<?php echo $nav_id; ?> nav-previous previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'firmasite' ) ); ?></li>
		<?php endif; ?>

		<?php if ( get_previous_posts_link() ) : ?>
		<li class="<?php echo $nav_id; ?> nav-next next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'firmasite' ) ); ?></li>
		<?php endif;*/ ?>
	<?php endif; ?>

	<?php 
	/*</nav><!-- #<?php echo $nav_id;?>  -->*/
}
endif; // firmasite_content_nav

if ( ! function_exists( 'firmasite_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 */
function firmasite_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'firmasite' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '<span class="edit-link"><span class="icon-edit"></span> Edit</span>', 'firmasite' ), ' ' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class("media well well-sm"); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment media">
			<footer class="media-meta pull-left">
				<div class="vcard thumbnail">
					<?php echo get_avatar( $comment, 64 ); ?>
				</div><!-- .comment-author .vcard -->
				<small class="comment-meta commentmetadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>" data-toggle="popover" data-rel="popover" data-placement="right" data-trigger="hover" data-content="
					<?php
						/* translators: 1: date, 2: time */
						printf( __( '%1$s at %2$s', 'firmasite' ), get_comment_date(), get_comment_time() ); ?>
					"><i class="icon-time"></i></a>
					<?php edit_comment_link( __( '<span class="edit-link"><span class="icon-edit"></span> Edit</span>', 'firmasite' ), ' ' );
					?>
				</small><!-- .comment-meta .commentmetadata -->
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php _e( 'Your comment is awaiting moderation.', 'firmasite' ); ?></em>
					<br />
				<?php endif; ?>
			</footer>
 			<div class="media-body">
				<small class="comment-author media-heading text-muted">
					<?php printf( __( '%s <span class="says">says:</span>', 'firmasite' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
				</small><!-- .comment-meta .commentmetadata -->
				<div class="comment-content"><?php comment_text(); ?></div>
			</div>
			<small class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'before' => '<span class="icon-comment"></span> ',  'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</small><!-- .reply -->
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // ends check for firmasite_comment()



add_action( 'comment_form_before_fields', "firmasite_comment_form_before_fields" );
function firmasite_comment_form_before_fields(){
	?>
    <div class="row">
		<div class="col-xs-12 col-sm-4 col-md-4 comment-fields">
        	<br class="hidden-xs" /><br class="hidden-xs" />
    <?php
	add_action( 'comment_form_after_fields', "firmasite_comment_form_after_fields" );
}
function firmasite_comment_form_after_fields(){
	?>
		</div>
    <?php
}

add_filter( 'comment_form_field_comment', "firmasite_comment_form_field_comment",900  );
function firmasite_comment_form_field_comment($comment_field){
	if(!is_user_logged_in()) {
	return '<div class="col-xs-12 col-sm-8 col-md-8">' 
				. $comment_field	
			. '</div>'
			. '</div><p class="clearfix"></p>';
	} else {
		return $comment_field;
	}
}

add_filter( 'comment_form_default_fields', "firmasite_comment_form_default_fields" );
function firmasite_comment_form_default_fields($fields){
	$commenter = wp_get_current_commenter();
	$req      = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$fields   =  array(
		'author' => '<div class="form-group"><div class="input-group comment-form-author">' . '<span class="input-group-addon"><i class="icon-user"></i></span>' .
		            '<input id="author" class="form-control" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '"' . $aria_req . ' placeholder="' . __( 'Name', 'firmasite' ) . ( $req ? ' *' : '' ) . '" /></div></div>',
		'email'  => '<div class="form-group"><div class="input-group comment-form-email">' . '<span class="input-group-addon"><i class="icon-envelope"></i></span>' .
		            '<input id="email" class="form-control" name="email" type="email" value="' . esc_attr(  $commenter['comment_author_email'] ) . '"' . $aria_req . ' placeholder="' . __( 'Email', 'firmasite' ) . ( $req ? ' *' : '' ) . '" /></div></div>',
		'url'    => '<div class="form-group"><div class="input-group comment-form-url">' . '<span class="input-group-addon"><i class="icon-globe"></i></span>' .
		            '<input id="url" class="form-control" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" placeholder="' . __( 'Website', 'firmasite' ) . '" /></div></div>',
	);
	return $fields;
}










