<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package wpclt
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/wpclt.ico" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<div class="navbar-wrapper">
		<div class="container">
			<div id="nav_obriens" class="navbar navbar-default navbar-fixed-top" role="navigation">
				<div class="container container_main">
					<div class="navbar-header greenBorder">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="/"><img src="<?php echo get_template_directory_uri(); ?>/images/wordpress-charlotte-logo.png" alt="WordPress Charlotte, NC" /></a>
						<?php //<h2 class="site-description"><?php bloginfo( 'description' ); ? ></h2> ?>
					</div>
					<div class="navbar-collapse collapse redBorder">
						<?php
							$menuSettings = array(
								'theme_location' => 'primary',
								'menu_id' => 'primary-menu',
								'menu_class' => 'main-navigation'
							);
							wp_nav_menu( $menuSettings );
						?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row" style="height: 75px; clear: both;"></div>
