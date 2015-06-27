<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


/**
 * Comment Honeypot
 */
add_action( 'comment_form_top', 'firmasite_honeypot_comment_add', 99 );
function firmasite_honeypot_comment_add() {
	 echo '<input type="text" class="hidden" name="name-comment" value="">';
}
add_filter( 'pre_comment_approved', 'firmasite_honeypot_comment_check' );
function firmasite_honeypot_comment_check( $approved ) {
	if ( isset( $_POST['name-comment'] ) && !empty( $_POST['name-comment'] ) )
		wp_die( __( 'You filled out a form field that was created to stop spammers.', 'firmasite' ) );
    return $approved;
}


/**
 * Registration Honeypot (without BuddyPress)
 */
add_action( 'register_form', 'firmasite_honeypot_registration_add', 99 );
function firmasite_honeypot_registration_add() {
	 echo '<input type="text" class="hidden" name="name-comment" value="" style="display: none !important; visibility: hidden !important;">';
}
add_action( 'register_post', 'firmasite_honeypot_registration_check' );
function firmasite_honeypot_registration_check( $approved ) {
	if ( isset( $_POST['name-comment'] ) && !empty( $_POST['name-comment'] ) )
		wp_die( __( 'You filled out a form field that was created to stop spammers.', 'firmasite' ) );
}



/**
 * BuddyPress Registration Honeypot
 */
add_action( 'bp_before_registration_submit_buttons', 'firmasite_honeypot_buddypress_registration_add', 99 );
function firmasite_honeypot_buddypress_registration_add() {
	 echo '<input type="text" class="hidden" name="name-comment" value="">';
}
add_filter( 'bp_core_validate_user_signup', 'firmasite_honeypot_buddypress_registration_check' );
function firmasite_honeypot_buddypress_registration_check( $result ) {
	if ( isset( $_POST['name-comment'] ) && !empty( $_POST['name-comment'] ) )
		wp_die( __( 'You filled out a form field that was created to stop spammers.', 'firmasite' ) );
	return $result;
}
 
 
/**
 * bbPress topic, forum & reply Honeypot
 */
add_action( 'bbp_theme_before_topic_form', 'firmasite_honeypot_bbpress_add', 99 );
add_action( 'bbp_theme_before_forum_form', 'firmasite_honeypot_bbpress_add', 99 );
add_action( 'bbp_theme_before_reply_form', 'firmasite_honeypot_bbpress_add', 99 );
function firmasite_honeypot_bbpress_add() {
	 echo '<input type="text" class="hidden" name="name-comment" value="">';
}
add_action( 'bbp_new_topic_pre_extras', 'firmasite_honeypot_bbpress_check' );
add_action( 'bbp_new_forum_pre_extras', 'firmasite_honeypot_bbpress_check' );
add_action( 'bbp_new_reply_pre_extras', 'firmasite_honeypot_bbpress_check' );
function firmasite_honeypot_bbpress_check( $result ) {
	if ( isset( $_POST['name-comment'] ) && !empty( $_POST['name-comment'] ) )
		wp_die( __( 'You filled out a form field that was created to stop spammers.', 'firmasite' ) );
}
 
 