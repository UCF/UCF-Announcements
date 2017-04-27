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
define( 'UCF_ANNOUNCEMENTS__TEMPLATE_PATH', UCF_ANNOUNCEMENTS__PLUGIN_PATH . '/templates' );
define( 'UCF_ANNOUNCEMENTS__PLUGIN_URL', plugins_url( basename( dirname( __FILE__ ) ) ) );
define( 'UCF_ANNOUNCEMENTS__STATIC_URL', UCF_ANNOUNCEMENTS__PLUGIN_URL . '/static' );
define( 'UCF_ANNOUNCEMENTS__STYLES_URL', UCF_ANNOUNCEMENTS__STATIC_URL . '/css' );
define( 'UCF_ANNOUNCEMENTS__SCRIPT_URL', UCF_ANNOUNCEMENTS__STATIC_URL . '/js' );

include_once 'includes/ucf-announcements-config.php';
include_once 'includes/ucf-announcements-auth.php';
include_once 'includes/ucf-announcements-common.php';
include_once 'includes/ucf-announcements-forms.php';
include_once 'includes/ucf-announcements-post-type.php';

if ( ! function_exists( 'ucf_announcements_plugin_activate' ) ) {
	function ucf_announcements_plugin_activate() {
		UCF_Announcements_Common::activate();
	}

	register_activation_hook( UCF_ANNOUNCEMENTS__PLUGIN_FILE, 'ucf_announcements_plugin_activate' );
}

if ( ! function_exists( 'ucf_announcements_plugin_deactivate' ) ) {
	function ucf_announcements_plugin_deactivate() {
		UCF_Announcements_Common::activate();
	}

	register_deactivation_hook( UCF_ANNOUNCEMENTS__PLUGIN_FILE, 'ucf_announcements_plugin_deactivate' );
}

if ( ! function_exists( 'ucf_announcements_init' ) ) {
	function ucf_announcements_init() {
		// Settings
		add_action( 'admin_init', array( 'UCF_Announcements_Config', 'settings_init' ) );
		add_action( 'admin_menu', array( 'UCF_Announcements_Config', 'add_options_page' ) );

		// Set page templates
		add_filter( 'page_template', array( 'UCF_Announcements_Common', 'page_templates' ), 10, 1 );
		// Form actions
		add_action( 'gform_post_data', array( 'UCF_Announcements_Common', 'announcement_save' ), 10, 3 );
		// Register Custom post type
		add_action( 'init', array( 'UCF_Announcements_PostType', 'register' ), 10, 0 );
	}

	add_action( 'plugins_loaded', 'ucf_announcements_init' );
}
