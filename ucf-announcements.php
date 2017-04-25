<?php
/*
Plugin Name: UCF Announcements
Version: 1.0.0
Author: UCF Web Communications
Description: Provides the announcement custom post type and related fields.
Plugin URL: https://github.com/UCF/UCF-Announcements/
Tags: announcement, custom post type
*/
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'UCF_ANNOUNCEMENTS__PLUGIN_FILE', __FILE__ );
define( 'UCF_ANNOUNCEMENTS__PLUGIN_PATH', plugin_dir_path( __FILE ) );
define( 'UCF_ANNOUNCEMENTS__PLUGIN_URL', plugins_url( basename( dirname( __FILE__ ) ) ) );
define( 'UCF_ANNOUNCEMENTS__STATIC_URL', UCF_ANNOUNCEMENTS__PLUGIN_URL . '/static' );
define( 'UCF_ANNOUNCEMENTS__STYLES_URL', UCF_ANNOUNCEMENTS__STATIC_URL . '/css' );
define( 'UCF_ANNOUNCEMENTS__SCRIPT_URL', UCF_ANNOUNCEMENTS__STATIC_URL . '/js' );

if ( ! function_exists( 'ucf_announcements_plugin_activate' ) ) {
	function ucf_announcements_plugin_activate() {
		flush_rewrite_rules( false );
	}

	register_activation_hook( UCF_ANNOUNCEMENTS__PLUGIN_FILE, 'ucf_announcements_plugin_activate' );
}

if ( ! function_exists( 'ucf_announcements_plugin_deactivate' ) ) {
	function ucf_announcements_plugin_deactivate() {
		flush_rewrite_rules( false );
	}

	register_deactivation_hook( UCF_ANNOUNCEMENTS__PLUGIN_FILE, 'ucf_announcements_plugin_deactivate' );
}

if ( ! function_exists( 'ucf_announcements_init' ) ) {
	function ucf_announcements_init() {

	}

	add_action( 'plugins_loaded', 'ucf_announcements_init' );
}
