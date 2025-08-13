<?php
/**
* Google Fonts Implementation
*
* @package Purpose
* @since Purpose 1.0
*
*/

/**
* Register Google Fonts
*
* @since Purpose 1.0
*/
function organic_register_fonts() {
	$protocol = is_ssl() ? 'https' : 'http';
	wp_register_style( 'purpose_open_sans', "$protocol://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800,800italic,700italic,600italic,400italic,300italic" );
	wp_register_style( 'purpose_raleway', "$protocol://fonts.googleapis.com/css?family=Raleway:400,200,300,800,700,500,600,900,100" );
	wp_register_style( 'purpose_montserrat', "$protocol://fonts.googleapis.com/css?family=Montserrat:400,700" );
	wp_register_style( 'purpose_droid_serif', "$protocol://fonts.googleapis.com/css?family=Droid+Serif:400,400italic,700,700italic" );
}
add_action( 'init', 'organic_register_fonts' );

/**
* Enqueue Google Fonts on Front End
*
* @since Purpose 1.0
*/

function organic_fonts() {
	wp_enqueue_style( 'purpose_open_sans' );
	wp_enqueue_style( 'purpose_raleway' );
	wp_enqueue_style( 'purpose_montserrat' );
	wp_enqueue_style( 'purpose_droid_serif' );
}
add_action( 'wp_enqueue_scripts', 'organic_fonts' );

/**
* Enqueue Google Fonts on Custom Header Page
*
* @since Purpose 1.0
*/
function organic_admin_fonts( $hook_suffix ) {
	if ( 'appearance_page_custom-header' != $hook_suffix )
	return;
	
	wp_enqueue_style( 'purpose_open_sans' );
	wp_enqueue_style( 'purpose_raleway' );
	wp_enqueue_style( 'purpose_montserrat' );
	wp_enqueue_style( 'purpose_droid_serif' );
}
add_action( 'admin_enqueue_scripts', 'organic_admin_fonts' );