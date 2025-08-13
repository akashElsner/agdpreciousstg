<?php

/*

Plugin Name: Tabs & Accordian Shortcodes

Description: Plugin for ACCORDION & TAB feature inside a post or page.

Author: Arun Gopal

Version: 1.0.0

*/

define( 'TAB_ACCORDION', '1.0.0' );

define( 'TAB_ACCORDION__MINIMUM_WP_VERSION', '3.9' );

define( 'TAB_ACCORDION__PLUGIN_URL', plugin_dir_url( __FILE__ ) );

define( 'TAB_ACCORDION__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

define( 'TAB_ACCORDION_DELETE_LIMIT', 100000 );





add_action('admin_init', 'editor_ta_button');

function editor_ta_button() {

	add_filter("mce_external_plugins", "add_ta_buttons");

	add_filter('mce_buttons', 'register_ta_buttons');

}

function add_ta_buttons($plugin_array) {

    $plugin_array['editor_ta_buttons'] = plugins_url( 'js/mce-button.js', __FILE__ ); // CHANGE THE BUTTON SCRIPT HERE

    return $plugin_array;

}

function register_ta_buttons($buttons) {

   array_push($buttons, "accorion_button","tabs_button");

   return $buttons;

}



/*javascript load from wordpress directroy*/

function TAB_ACCORDION_script_latest() {
	wp_enqueue_script( 'jquery');

}

add_action( 'init', 'TAB_ACCORDION_script_latest' );



function TAB_ACCORDION_script() {

	wp_enqueue_script( 'TAB_ACCORDION_script', plugins_url('/js/scripts.js', __FILE__));

}

add_action('wp_head', 'TAB_ACCORDION_script');



/* Adding Plugin custm CSS file */

function add_TAB_ACCORDION_stylesheet() 

{

    wp_enqueue_style( 'TAB_ACCORDION-plugin-style', plugins_url('/css/tab_accordion_style.css', __FILE__ ) );

}



add_action('wp_head', 'add_TAB_ACCORDION_stylesheet');



/* Generates Accordion Shortcode */

function accordion_main($atts, $content = null) {

	return ('<div class="TA_accordion">'.do_shortcode($content).'</div>');

}

add_shortcode ("accordion_wrap", "accordion_main");



function accordion_panels($atts, $content = null) {

	extract(shortcode_atts(array(

		'heading'	=> 'h6',

        'title'     => ''

    ), $atts ));

	static $id = 1;

	$return = ('<'.$heading.' class="TA_accordion_title" id="accordion-'.$id.'">'.$title. '</'.$heading.'> <div class="TA_accordion_content" id="accordion-'.$id.'-panel"><div class="TA_accordion_inner">' .$content. '</div></div>');

	$id++;

	return $return;

}

add_shortcode ("accordion_panel", "accordion_panels");





/* Generates Tabs Shortcode */

function tabs_main($atts, $content = null) {

	return ('<div class="TA_tabs"><ul class="TA_tabmenu"></ul>'.do_shortcode($content).'</div>');

}

add_shortcode ("tabs_wrap", "tabs_main");



function tab_panels($atts, $content = null) {

		extract(shortcode_atts(array(

			'heading'	=> 'h6',

			'title'     => ''

		), $atts));

		static $tabid = 1;

		$return = ('<'.$heading.' class="TA_tab_title_responsive" id="tab-'.$tabid.'">'.$title.'</'.$heading.'> <div class="TA_tab_content" id="tab-'.$tabid.'-panel"><div class="TA_tab_inner">' .$content. '</div></div>');

		$tabid++;

		return $return;

}

add_shortcode ("tab_panel", "tab_panels");



?>