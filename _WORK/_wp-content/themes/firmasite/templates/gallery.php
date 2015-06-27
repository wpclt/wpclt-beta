<?php
/**
 * @package firmasite
 */
 ?>
<?php
/* 
 * This is default gallery for every post types. So we calling templates/post/gallery manually.
 * If you want custom design for your custom post type, lets say "example" named custom post type, just create a gallery-example.php file.
 * This file gets called from functions/shortcodes.php file.
*/
global $firmasite_post_gallery;
switch($firmasite_post_gallery['fs_gallery_style']){
	case 'gallery_slider':
		get_template_part( 'templates/post/gallery/gallery_slider' );
		break;				
	case 'gallery_tile':
		get_template_part( 'templates/post/gallery/gallery_tile' );
		break;				
	default:	
	case 'gallery_grid':
		get_template_part( 'templates/post/gallery/gallery_grid' );
		break;
}