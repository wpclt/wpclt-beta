<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/*
Theme settings 
*/

global $firmasite_settings;
$firmasite_settings = get_option( "firmasite_settings" ); // site options

do_action("firmasite_settings_open");

$defaults = array(
	"style" => "simplex",
	"default_style" => "simplex",
	"font" => "Ubuntu Condensed",
	"layout" => "content-sidebar",
	"loop-style" => "loop-list",
	"color-logo-text" => "info",
	"color-tax" => "info",
	"menu-style" => "default",
	"footer-menu-style" => "default",
	"loop_tile_row" => 3,
	
	"promotionbar-location" => "open_sidebar",	
	"promotionbar-where" => "everywhere",
		
	"showcase-style" => "1",

	"header-style" => "1",
	"footer-style" => "1",

	"subsets" => FIRMASITE_SUBSETS,
	"poweredby" => FIRMASITE_POWEREDBY,
	"designer" => FIRMASITE_DESIGNER
);

$firmasite_settings = wp_parse_args($firmasite_settings, $defaults);
$firmasite_settings["protocol"] = is_ssl() ? 'https' : 'http';

$lang = explode("-", get_bloginfo('language'));
$firmasite_settings["site_language"] = esc_attr($lang[0]);
$firmasite_settings["site_region"] = esc_attr($lang[1]);

switch ($firmasite_settings["layout"]) {
    case "sidebar-content":
		$firmasite_settings["layout_primary_class"] = "col-xs-12 col-md-8 pull-right";
 		$firmasite_settings["layout_primary_fullwidth_class"] = "col-xs-12 col-md-12";
		$firmasite_settings["layout_secondary_class"] = "col-xs-12 col-md-4";		
		$firmasite_settings["layout_container_class"] = "container";		
 		$firmasite_settings["layout_page_class"] = "site-sidebar-content";		
      break;
    case "only-content":
 		$firmasite_settings["layout_primary_class"] = "col-xs-12 col-md-12";
 		$firmasite_settings["layout_primary_fullwidth_class"] = "col-xs-12 col-md-12";
		$firmasite_settings["layout_secondary_class"] = "hide";		
		$firmasite_settings["layout_container_class"] = "container";		
 		$firmasite_settings["layout_page_class"] = "site-only-content";		
      break;
    case "only-content-long":
 		$firmasite_settings["layout_primary_class"] = "col-xs-12 col-md-12";
 		$firmasite_settings["layout_primary_fullwidth_class"] = "col-xs-12 col-md-12";
		$firmasite_settings["layout_secondary_class"] = "hide";		
		$firmasite_settings["layout_container_class"] = "container";		
 		$firmasite_settings["layout_page_class"] = "site-only-content-long";		
      break;
	default:
    case "content-sidebar":
 		$firmasite_settings["layout_primary_class"] = "col-xs-12 col-md-8";
 		$firmasite_settings["layout_primary_fullwidth_class"] = "col-xs-12 col-md-12";
		$firmasite_settings["layout_secondary_class"] = "col-xs-12 col-md-4";		
		$firmasite_settings["layout_container_class"] = "container";		
 		$firmasite_settings["layout_page_class"] = "site-content-sidebar";		
       break;	
}

$firmasite_settings["styles"] = apply_filters( 'firmasite_theme_styles', array(
	"cerulean" => esc_attr__( 'Cerulean', 'firmasite' ),	
	"cosmo" => esc_attr__( 'Cosmo', 'firmasite' ),			
	"cyborg" => esc_attr__( 'Cyborg', 'firmasite' ),		
	"darkly" => esc_attr__( 'Darkly', 'firmasite' ),		
	"default" => esc_attr__( 'Default', 'firmasite' ),		
	"flatly" => esc_attr__( 'Flatly', 'firmasite' ),		
	"journal" => esc_attr__( 'Journal', 'firmasite' ),		
	"lumen" => esc_attr__( 'Lumen', 'firmasite' ),		
	"paper" => esc_attr__( 'Paper', 'firmasite' ),		
	"readable" => esc_attr__( 'Readable', 'firmasite' ),	
	"sandstone" => esc_attr__( 'Sandstone', 'firmasite' ),	
	"simplex" => esc_attr__( 'Simplex', 'firmasite' ),		
	"slate" => esc_attr__( 'Slate', 'firmasite' ),			
	"spacelab" => esc_attr__( 'Spacelab', 'firmasite' ),	
	"superhero" => esc_attr__( 'Superhero', 'firmasite' ),	
	"united" => esc_attr__( 'United', 'firmasite' ),		
	"yeti" => esc_attr__( 'Yeti', 'firmasite' ),		
));
	
$firmasite_settings["dark_styles"] = apply_filters( 'firmasite_theme_dark_styles', array(
	"cyborg",
	"darkly",
	"slate",
	"superhero",
));
		
$firmasite_styles_url_default = get_template_directory_uri() . '/assets/themes/';
$firmasite_settings["styles_url"] = apply_filters( 'firmasite_theme_styles_url', array(		
	"cerulean" => $firmasite_styles_url_default. "cerulean",	
	"cosmo" => $firmasite_styles_url_default. "cosmo",			
	"cyborg" => $firmasite_styles_url_default. "cyborg",		
	"darkly" => $firmasite_styles_url_default. "darkly",		
	"default" => $firmasite_styles_url_default. "default",		
	"flatly" => $firmasite_styles_url_default. "flatly",		
	"journal" => $firmasite_styles_url_default. "journal",		
	"lumen" => $firmasite_styles_url_default. "lumen",		
	"paper" => $firmasite_styles_url_default. "paper",		
	"readable" => $firmasite_styles_url_default. "readable",	
	"sandstone" => $firmasite_styles_url_default. "sandstone",	
	"simplex" => $firmasite_styles_url_default. "simplex",		
	"slate" => $firmasite_styles_url_default. "slate",			
	"spacelab" => $firmasite_styles_url_default. "spacelab",	
	"superhero" => $firmasite_styles_url_default. "superhero",	
	"united" => $firmasite_styles_url_default. "united",		
	"yeti" => $firmasite_styles_url_default. "yeti",		
));



do_action("firmasite_settings_close");


