<?php
/*
 Plugin Name: Live Stream Badger
 Plugin URI: http://wordpress.org/extend/plugins/live-stream-badger/
 Description: Display status of Twitch.tv live streams
 Version: 1.4-dev
 Author: Tadas Krivickas
 Author URI: http://profiles.wordpress.org/tkrivickas
 Author email: tadas.krivickas@gmail.com
 License: GPLv3
 License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( !defined( 'LSB_PLUGIN_BASE' ) ) {
	define( 'LSB_PLUGIN_BASE', plugin_dir_path( __FILE__ ) );
}

// Includes: admin
include_once LSB_PLUGIN_BASE . 'admin/admin-settings.php';
include_once LSB_PLUGIN_BASE . 'admin/diagnostics.php';
include_once LSB_PLUGIN_BASE . 'admin/installer.php';
// Includes: other
include_once LSB_PLUGIN_BASE . 'apis/class-api-core.php';
include_once LSB_PLUGIN_BASE . 'stream-status-widget.php';
include_once LSB_PLUGIN_BASE . 'shortcode/class-embedded-stream.php';
include_once LSB_PLUGIN_BASE . 'scheduler/class-api-sync.php';

// Register styles
add_action( 'wp_enqueue_scripts', 'lsb_register_styles' );
function lsb_register_styles() {
	wp_register_style( 'lsb-style', plugins_url( 'style.css', __FILE__ ) );
	wp_enqueue_style( 'lsb-style' );
}

// Register shortcode
$embedded_stream_sc = new LSB_Embedded_Stream();
add_shortcode( 'livestream', array( $embedded_stream_sc, 'do_shortcode' ) );

new LSB_API_Sync();

$installer = new LSB_Installer();
register_activation_hook( __FILE__, array( $installer, 'install' ) );
register_deactivation_hook( __FILE__, array( $installer, 'uninstall' ) );

new LSB_Admin_Settings(
    new LSB_Diagnostics()
);
