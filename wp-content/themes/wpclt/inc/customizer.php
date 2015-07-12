<?php
/**
 * wpclt Theme Customizer
 *
 * @package wpclt
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function wpclt_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	$wp_customize->add_setting(
		'wpclt_logo',
		array(
			'default'     => '',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'custom_logo',
			array(
				'label'      => esc_html__( 'Your Logo', 'wpclt' ),
				'section'    => 'title_tagline',
				'settings'   => 'wpclt_logo',
				'context'    => 'wpclt-custom-logo'
			)
		)
	);
}
add_action( 'customize_register', 'wpclt_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function wpclt_customize_preview_js() {
	wp_enqueue_script( 'wpclt_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'wpclt_customize_preview_js' );
