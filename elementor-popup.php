<?php
/**
* Plugin Name: Elementor Popups
* Description: Popup element for Elementor Page Builder
* Version: 0.3.0
* Author: Avi Bashari
* Author URI: https://facebook.com/bashari10
* Text Domain: lm-popup
* Domain Path: /languages
* License: GPLv3
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Load the plugin after Elementor (and other plugins) are loaded
add_action( 'plugins_loaded', function() {
	// Load localization file
	load_plugin_textdomain( 'lm-popup', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );

	// Notice if the Elementor is not active
	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'hello_world_fail_load' );
		return;
	}

	// Check version required
	$elementor_version_required = '1.0.0';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
		add_action( 'admin_notices', 'hello_world_fail_load_out_of_date' );
		return;
	}

	// Require the main plugin file
	require_once( __DIR__ . '/plugin.php' );
} );




add_action( 'wp_enqueue_scripts', 'elementor_popup_register_popup_style' );
function elementor_popup_register_popup_style() {
	if( ( ! wp_style_is( 'bootstrap', 'enqueued' ) ) && ( ! wp_style_is( 'bootstrap', 'done' ) ) ) {
		wp_enqueue_style( 'bootstrap-modal', plugin_dir_url( __FILE__ ) . 'css/bootstrap.css' );
		wp_enqueue_style( 'lm-popup', plugin_dir_url( __FILE__ ) . 'css/popup.css', array( 'bootstrap-modal' ) );
	} else {
		wp_enqueue_style( 'lm-popup', plugin_dir_url( __FILE__ ) . 'css/popup.css', array( 'bootstrap' ) );
	}
	
	if ( is_rtl() ) {
		wp_enqueue_style(
			'lm-popup-rtl',
			plugin_dir_url( __FILE__ ) . 'css/rtl.popup.css',
			array ( 'lm-popup' )
		);
	}
	
	if( ( ! wp_script_is( 'bootstrap', 'enqueued' ) ) && ( ! wp_script_is( 'bootstrap', 'done' ) ) ) {
		wp_enqueue_script( 'bootstrap-modal', plugin_dir_url( __FILE__ ) . 'js/bootstrap.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'lm-popup-js', plugin_dir_url( __FILE__ ) . 'js/popup.js', array( 'jquery', 'bootstrap-modal' ), null, true );
	} else {
		wp_enqueue_script( 'lm-popup-js', plugin_dir_url( __FILE__ ) . 'js/popup.js', array( 'jquery', 'bootstrap' ), null, true );
	}
	
}

/* create new custom post type named popup */
add_action( 'init', 'elementor_popup_create_popup_post_type' );

function elementor_popup_create_popup_post_type() {
  register_post_type( 'popup',
    array(
        'labels' => array(
        'name' => __( 'Popups', 'lm-popup'),
        'singular_name' => __( 'Popup', 'lm-popup'),
		'all_items' => __( 'All Popups', 'lm-popup'),
		'add_new_item' => __( 'Add New Popup', 'lm-popup'),
		'new_item' => __( 'Add New Popup', 'lm-popup'),
		'add_new' => __( 'Add New Popup', 'lm-popup'),
		'edit_item' => __( 'Edit Popup', 'lm-popup'),
      ),
      'has_archive' => false,
      'rewrite' => array('slug' => 'popup'),
	  'public' => true,
	  'exclude_from_search' => true,
    )
  );
  add_post_type_support( 'popup', 'elementor' );
}