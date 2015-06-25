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
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page">
	<div class="navbar-wrapper">
		<div class="container">
			<div id="nav_wpclt" class="navbar navbar-default navbar-fixed-top" role="navigation">
				<div class="navbar-wplogin"></div>
				<div class="container container_main">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="/"><img src="<?php echo get_template_directory_uri(); ?>/images/wordpress-charlotte-logo.png" alt="WordPress Charlotte, NC" /></a>
					</div>
					<div class="navbar-collapse collapse">
						<?php
							$menuSettings = array(
								'theme_location' => 'primary',
								'menu_id' => 'primary-menu',
								'menu_class' => 'nav navbar-nav'
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
