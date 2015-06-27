<?php
/*
 * This file using for loading styles in wp-editor
 */
define('WP_USE_THEMES', false);
if (file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'wp-blog-header.php')) {
	/** Loads the WordPress Environment and Template */
	require($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'wp-blog-header.php');
} else {
	//Climb dirs till we find wp-blog-header (Varies depending on wordpress install)
	while (! file_exists('wp-blog-header.php') )
	chdir('..'); 

	/** Loads the WordPress Environment and Template */
	require ("wp-blog-header.php");
}
// Exit if accessed directly without wp-blog-header.php
defined( 'ABSPATH' ) || exit;

// http://wordpress.org/support/topic/integrating-wp-in-external-php-pages?replies=22#post-1568366
status_header(200);
header("Content-type: text/css");

global $firmasite_settings;  // site options
?>
	@import url(<?php echo $firmasite_settings["styles_url"][$firmasite_settings["style"]] . '/bootstrap.min.css'; ?>);
<?php
if (isset($firmasite_settings["font"]) && !empty($firmasite_settings["font"])) { ?>
    @import url(//fonts.googleapis.com/css?family=<?php echo urlencode($firmasite_settings["font"]); ?>&subset=latin,latin-ext);
    body { font-family: <?php echo $firmasite_settings["font"]; ?>,sans-serif !important;}
<?php
}

/*firmasite_custom_background_cb();
// got from wp-includes/theme.php  
// _custom_background_cb
function firmasite_custom_background_cb() {
	// $background is the saved custom image, or the default image.
	$background = set_url_scheme( get_background_image() );

	// $color is the saved custom color.
	// A default has to be specified in style.css. It will not be printed here.
	$color = get_theme_mod( 'background_color' );

	if ( ! $background && ! $color )
		return;

	$style = $color ? "background-color: #$color;" : '';

	if ( $background ) {
		$image = " background-image: url('$background');";

		$repeat = get_theme_mod( 'background_repeat', 'repeat' );
		if ( ! in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) )
			$repeat = 'repeat';
		$repeat = " background-repeat: $repeat;";

		$position = get_theme_mod( 'background_position_x', 'left' );
		if ( ! in_array( $position, array( 'center', 'right', 'left' ) ) )
			$position = 'left';
		$position = " background-position: top $position;";

		$attachment = get_theme_mod( 'background_attachment', 'scroll' );
		if ( ! in_array( $attachment, array( 'fixed', 'scroll' ) ) )
			$attachment = 'scroll';
		$attachment = " background-attachment: $attachment;";

		$style .= $image . $repeat . $position . $attachment;
	}*/
// We are putting this background to html (not body) for wysiwyg so we can use body class for customization. 
// Tinymce is not supporting to add root element so we must use body as root element

?>
	html { <?php echo trim( $style ); ?> }
<?php
//}

do_action("firmasite_wpeditor_style");

