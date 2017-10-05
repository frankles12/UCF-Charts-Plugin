<?php
/*
Plugin Name: UCF Charts Plugin
Version: 0.0.1
Author: UCF Web Communications
Description: Provides shortcode for creating chart.js charts.
Plugin URL: https://github.com/UCF/UCF-Charts-Plugin/
Tags: chart.js,shortcodes
*/
if ( ! defined( 'WPINC' ) ) {
    die;
}


define( 'UCF_CHARTS__PLUGIN_FILE', __FILE__ );
define( 'UCF_CHARTS__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'UCF_CHARTS__PLUGIN_URL', plugins_url( basename( dirname( __FILE__ ) ) ) );
define( 'UCF_CHARTS__STATIC_URL', UCF_CHARTS__PLUGIN_URL . '/static' );

include_once 'admin/ucf-charts-config.php';
include_once 'includes/ucf-charts-posttype.php';

if ( ! function_exists( 'ucf_charts_plugin_activation' ) ) {
	function ucf_charts_plugin_activation() {
		UCF_Chart_PostType::register();
		UCF_Chart_Config::add_options();
		flush_rewrite_rules();
	}

	register_activation_hook( UCF_CHARTS__PLUGIN_FILE, 'ucf_charts_plugin_activation' );
}

if  ( ! function_exists( 'ucf_charts_plugin_deactivation' ) ) {
	function ucf_charts_plugin_deactivation() {
		UCF_Chart_Config::delete_options();
	}

	register_deactivation_hook( UCF_CHARTS__PLUGIN_FILE, 'ucf_charts_plugin_deactivation' );
}

if ( ! function_exists( 'ucf_charts_init' ) ) {
	function ucf_charts_init() {
		/* Register settings */
		add_action( 'admin_init', array( 'UCF_Chart_Config', 'settings_init' ) );
		add_action( 'admin_menu', array( 'UCF_Chart_Config', 'add_options_page' ) );
		/* Register post type on init */
		add_action( 'init', array( 'UCF_Chart_PostType', 'register' ), 10, 0 );
	}

	add_action( 'plugins_loaded', 'ucf_charts_init' );
}
