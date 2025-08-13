<?php

/**

* The Header for our theme.

* Displays all of the <head> section and everything up till <div id="wrap">

*

* @package Purpose

* @since Purpose 1.0

*

*/

?>
<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->

<!--[if lt IE 7]> <html class="no-js ie6 oldie" <?php language_attributes(); ?>> <![endif]-->

<!--[if IE 7]>    <html class="no-js ie7 oldie" <?php language_attributes(); ?>> <![endif]-->

<!--[if IE 8]>    <html class="no-js ie8 oldie" <?php language_attributes(); ?>> <![endif]-->

<!-- Consider adding an manifest.appcache: h5bp.com/d/Offline -->

<!--[if gt IE 8]><!-->
<html class="no-js" <?php language_attributes(); ?>>
<!--<![endif]-->

<head>

	<meta charset="<?php bloginfo('charset'); ?>">

	<!-- Mobile View -->

	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<title>
		<?php wp_title( '|', true, 'right' ); ?>
	</title>

	<link rel="Shortcut Icon" href="<?php echo get_template_directory_uri(); ?>/images/favicon.png" type="image/x-icon">

	<link href="https://fonts.googleapis.com/css?family=Playfair+Display:400i,700" rel="stylesheet">

	<link href="<?php echo get_template_directory_uri(); ?>/webfonts/webfonts.css" rel="stylesheet">

	<link rel="profile" href="//gmpg.org/xfn/11">

	<link rel="pingback" href="<?php echo esc_url( bloginfo('pingback_url') ); ?>">

	<?php wp_head(); ?>

	<!--Slick Slider-->

	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/slick/slick.css">

	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/slick/slick-theme.css">

	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/slick/slick.js"></script>

</head>

<body <?php body_class(); ?>>

	<?php //get_template_part( 'content/preloader'); ?>

	<!-- BEGIN #wrap -->

	<div id="wrap">

		<!-- BEGIN .container -->

		<div class="container">

			<!-- BEGIN #header -->

			<div id="header">

				<!-- BEGIN .row -->

				<div class="content wide">

					<div class="header_table">

						<div class="header_table_cell header_left">

							<?php if ( has_nav_menu( 'header-menu' ) ) {

							wp_nav_menu( array(

								'theme_location' => 'header-menu',

								'title_li' => '',

								'depth' => 1,

								'container_class' => '',

								'container_id' => 'header_menu',

								'menu_class'      => 'menu'

								)

							);

						} ?>

							<?php if(of_get_option('number_call') || of_get_option('number_call_2')){?>
							<div class="header_call">
                            	<?php if(of_get_option('number_call')) { ?>
								<a href="tel:<?php echo of_get_option('number_call'); ?>"><i class="fa fa-volume-control-phone"></i> <?php echo of_get_option('number_display'); ?></a>
                                <?php } ?>
                                <?php if(of_get_option('number_call_2')) { ?>
								<a href="tel:<?php echo of_get_option('number_call_2'); ?>"><i class="fa fa-volume-control-phone"></i> <?php echo of_get_option('number_display_2'); ?></a>
                                <?php } ?>
							</div>
							<?php } ?> 



						</div>

						<div class="header_table_cell header_mid">

							<a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>" class="logo"><img src="<?php echo of_get_option('logo_img'); ?>" /></a>

						</div>

						<div class="header_table_cell header_right">

							<div class="header_right_menu">

								<span class="mobileMenu">
									<a id="menu-toggle" class="menu-toggle">
										
										<span></span>
										<span></span>
										<span></span>
										
									</a></span>

								<?php /*?><span><a href="<?php echo of_get_option('cta_link'); ?>"><?php echo of_get_option('cta_text'); ?></a></span><?php */?>

							</div>

						</div>

					</div>

				</div>

				<!-- END #header -->

			</div>



			<nav id="navigation" class="navigation-main" role="navigation">

				<?php if ( has_nav_menu( 'main-menu' ) ) {

        wp_nav_menu( array(

            'theme_location' => 'main-menu',

            'title_li' => '',

            'depth' => 4,

            'container_class' => '',

            'container_id' => 'main_menu',

            'menu_class'      => 'menu'

            )

        );

		} ?>

				<!-- END #navigation -->

			</nav>