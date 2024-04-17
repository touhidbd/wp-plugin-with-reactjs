<?php
/*
Plugin Name: WP Plugin With React JS
Plugin URI: https://tcoderbd.com
Description: WordPress Plugin with React JS
Version: 1.0.0
Requires at least: 5.8
Requires PHP: 5.6.20
Author: Touhidul Sadeek
Author URI: https://tcoderbd.com
License: GPLv2 or later
Text Domain: wp-plugin-with-react
*/

if( ! defined('ABSPATH') ) { exit(); }

/*
 * Define Plugin Contents
 * */
define ( 'WPWR_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define ( 'WPWR_URL', trailingslashit( plugins_url( '/', __FILE__ ) ) );

/*
 * Load Scripts
 * */
function load_scripts(){
	wp_enqueue_style('wp-react-plugin', WPWR_URL .'index.css', array(), '1.0.0', );
	wp_enqueue_script( 'wp-react-plugin', WPWR_URL . 'dist/bundle.js', [ 'jquery', 'wp-element' ], wp_rand(), true );
	wp_localize_script( 'wp-react-plugin', 'appLocalizer', [
		'apiUrl' => home_url('/wp-json'),
		'nonce' => wp_create_nonce('wp_rest')
	] );
}
add_action('admin_enqueue_scripts','load_scripts');

function new_dashboard_setup(){
	wp_add_dashboard_widget(
		'new_dashboard_widget',
		'New Graph Widget',
		'new_dashboard_widget_callback'
	);
}
add_action('wp_dashboard_setup', 'new_dashboard_setup');

function new_dashboard_widget_callback() {
	echo '<div id="new-dashboard-widget"></div>';
}

// Create new table
function table_init() {
	global $wpdb;
	$table_name = $wpdb->prefix. 'charttable';
	$sql = "CREATE TABLE {$table_name} (id INT NOT NULL AUTO_INCREMENT, `name` VARCHAR(250), uv INT, pv INT, amt INT, dateT DATE, PRIMARY KEY (id));";

	require_once ABSPATH . "wp-admin/includes/upgrade.php";
	dbDelta ($sql);

	$insert_query = "INSERT into ".$table_name."(name, uv, pv, amt, dateT) VALUES ('Page A', 4000, 2000, 200, '2024-03-01'), ('Page B', 2000, 4000, 3000, '2024-03-13'), ('Page C', 6000, 3000, 2000, '2024-02-6'), ('Page D', 1000, 2000, 5000, '2024-03–1'), ('Page E', 6000, 1000, 4000, '2024-02-16')";

	$wpdb->query($insert_query);
}
register_activation_hook( __FILE__ , 'table_init');

require_once WPWR_PATH . 'classes/rest-api-create.php';