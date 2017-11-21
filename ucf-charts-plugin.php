<?php
/*
Plugin Name: UCF Charts Plugin
Version: 1.0.0
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
define( 'UCF_CHARTS__JS_URL', UCF_CHARTS__STATIC_URL . '/js' );

define( 'UCF_CHARTS__VENDOR_JS_URL', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js' );

include_once 'admin/ucf-charts-config.php';
include_once 'admin/ucf-charts-admin.php';
include_once 'includes/ucf-charts-common.php';
include_once 'includes/ucf-charts-posttype.php';
include_once 'shortcodes/ucf-charts-shortcode.php';

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
		add_action( 'admin_enqueue_scripts', array( 'UCF_Chart_PostType', 'admin_enqueue_scripts' ), 10, 1 );
		/* Register post type on init */
		add_action( 'init', array( 'UCF_Chart_PostType', 'register' ), 10, 0 );
		/* Add custom column */
		add_action( 'manage_ucf_chart_posts_columns', array( 'UCF_Chart_Admin', 'ucf_chart_custom_columns' ), 10, 1 );
		add_action( 'manage_posts_custom_column', array( 'UCF_Chart_Admin', 'ucf_chart_shortcode_column' ), 10, 2 );
		/* Enqueue frontend assets */
		add_action( 'wp_enqueue_scripts', array( 'UCF_Chart_Common', 'enqueue_frontend_assets' ), 10, 0 );
		/* Add shortcode */
        add_action( 'init', array( 'UCF_Chart_Shortcode', 'register' ), 10, 0 );
        /* Add hook to allow for json uploads */
        add_filter( 'upload_mimes', array( 'UCF_Chart_Common', 'custom_mimes' ), 10, 1 );
	}

	add_action( 'plugins_loaded', 'ucf_charts_init' );
}


// Hi, I am here
// I am also here
