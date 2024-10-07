<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://trackthe.click
 * @since             0.0.1
 * @package           Track_The_Click
 *
 * @wordpress-plugin
 * Plugin Name:       Track The Click
 * Description:       Track how many clicks your links get.
 * Version:           0.4.0
 * Author:            Track The Click
 * Author URI:        https://trackthe.click/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       track-the-click
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 */
define( 'TRACK_THE_CLICK_VERSION', '0.4.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-track-the-click-activator.php
 */
function activate_track_the_click() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-track-the-click-activator.php';
	Track_The_Click_Activator::activate();
}

function ensure_database_updated() {
	$db_version = get_option( "track_the_click_version", false );
	if ( $db_version !== false ) {
		if ( version_compare( $db_version, TRACK_THE_CLICK_VERSION ) < 0 ) {
			// Database version is older than our version, so this is the first run since an upgrade
			require_once plugin_dir_path( __FILE__ ) . 'includes/class-track-the-click-activator.php';
			Track_The_Click_Activator::create_update_db();

			if ( version_compare( $db_version, '0.2.14' ) < 0 ) {
				$exclusions = trim( get_home_url() . "\r\n" . get_option( 'track_the_click_exclude_addresses', '' ) );
				update_option( 'track_the_click_exclude_addresses', $exclusions );
			}

			if ( version_compare( $db_version, '0.2.16' ) < 0 ) {
				global $wpdb;
				$group_table_name = $wpdb->prefix . 'track_the_click_group';

				$wpdb->insert(
					$group_table_name,
					array( 'id' => 1, 'name' => 'Money' )
				);
			}

			if ( version_compare( $db_version, '0.4.0' ) < 0 ) {
				delete_option('track_the_click_ga_options');
			}

			update_option( 'track_the_click_version', TRACK_THE_CLICK_VERSION );
		}
	}
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-track-the-click-deactivator.php
 */
function deactivate_track_the_click() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-track-the-click-deactivator.php';
	Track_The_Click_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_track_the_click' );
add_action( 'plugins_loaded', 'ensure_database_updated' );
register_deactivation_hook( __FILE__, 'deactivate_track_the_click' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-track-the-click.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.0.1
 */
function run_track_the_click() {

	$plugin = new Track_The_Click(__FILE__);
	$plugin->run();

}
run_track_the_click();
