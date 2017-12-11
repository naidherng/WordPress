<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "body-content-wrapper" div.
 *
 * @subpackage fCorpo
 * @author tishonator
 * @since fCorpo 1.0.0
 *
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo('charset'); ?>" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<meta name="viewport" content="width=device-width" />
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<div id="body-content-wrapper">
			
			<header id="header-main-fixed">

				<div id="header-top">
					<div id="header-top-content">
						<nav id="top-menu">
							<?php wp_nav_menu( array( 'container'      => false,
													  'theme_location' => 'top',
													  'depth'		   => 1,
													  'items_wrap'     => '%3$s',
								) ); ?>
						</nav>

						<?php fcorpo_show_header_phone(); ?>

						<?php fcorpo_show_header_email(); ?>

						<?php fcorpo_display_social_sites(); ?>
					</div>
				</div>

				<div id="header-content-wrapper" class="header-content-spacing">

					<div class="clear">
					</div>

					<div id="header-logo">
						<?php fcorpo_show_website_logo_image_and_title(); ?>
					</div><!-- #header-logo -->

					<nav id="navmain">
						<?php wp_nav_menu( array( 'theme_location' => 'primary',
												  'fallback_cb'    => 'wp_page_menu',
												  
												  ) ); ?>
					</nav><!-- #navmain -->
					
					<div class="clear">
					</div><!-- .clear -->

				</div><!-- #header-content-wrapper -->

			</header><!-- #header-main-fixed -->
			<div id="header-spacer">
				&nbsp;
			</div><!-- #header-spacer -->