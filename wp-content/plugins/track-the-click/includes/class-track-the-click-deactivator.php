<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      0.0.1
 *
 * @package    Track_The_Click
 * @subpackage Track_The_Click/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      0.0.1
 * @package    Track_The_Click
 * @subpackage Track_The_Click/includes
 * @author     Daniel Foster <daniel@34sp.com>
 */
class Track_The_Click_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    0.0.1
	 */
	public static function deactivate() {
		$timestamp = wp_next_scheduled( 'track_the_click_delete_old_data' );
		if ( $timestamp !== false ) {
			wp_unschedule_event( $timestamp, 'track_the_click_delete_old_data' );
		}
	}

}
