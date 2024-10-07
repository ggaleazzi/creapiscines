<?php

/**
 * Fired during plugin activation
 *
 * @link       https://trackthe.click
 * @since      0.0.1
 *
 * @package    Track_The_Click
 * @subpackage Track_The_Click/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      0.0.1
 * @package    Track_The_Click
 * @subpackage Track_The_Click/includes
 * @author     Daniel Foster <daniel@34sp.com>
 */
class Track_The_Click_Activator {

	/**
	 * @since    0.0.1
	 */
	public static function activate() {

		add_option('track_the_click_local', '1');
		Track_The_Click_Activator::create_update_db();

	}

	/**
	 * Create or update database tables.
	 *
	 * Create database tables to track links and hits for local tracking
	 * Updated to include anchor text for local reporting
	 *
	 * @since    0.0.5
	 */

	public static function create_update_db() {

		global $wpdb;
		$version = get_option( 'track_the_click_version', '0.0.1' );
		$new_version = $version;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$charset_collate = $wpdb->get_charset_collate();
		$link_table_name = $wpdb->prefix . 'track_the_click_link';
		$click_table_name = $wpdb->prefix . 'track_the_click_hit';
		$group_table_name = $wpdb->prefix . 'track_the_click_group';
		$link_group_table_name = $wpdb->prefix . 'track_the_click_link_group';

		$hit_table_revision = '0.2.19';
		if ( version_compare( $version, $hit_table_revision ) < 0 ) {
			$sql = "CREATE TABLE $click_table_name (
							id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
							link_id BIGINT(20) UNSIGNED NOT NULL,
							timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
							UNIQUE KEY id (id)
			) $charset_collate;";
			dbDelta( $sql );

			if ( version_compare( $new_version, $hit_table_revision ) < 0
					 && version_compare( $hit_table_revision, $new_version ) > 0 ) {
				$new_version = $hit_table_revision;
			}
		}

		$link_table_revision = '0.2.1';
		if ( version_compare( $version, $link_table_revision ) < 0 ) {
			$sql = "CREATE TABLE $link_table_name (
							id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
							post_id BIGINT(20) UNSIGNED NOT NULL,
							page_url VARCHAR(4096) DEFAULT NULL,
							url VARCHAR(4096) NOT NULL,
							anchor VARCHAR(255) DEFAULT NULL,
							UNIQUE KEY id (id)
			) $charset_collate;";
			dbDelta( $sql );

			if ( version_compare( $new_version, $link_table_revision ) < 0
					 && version_compare( $link_table_revision, $new_version ) > 0 ) {
				$new_version = $link_table_revision;
			}
		}

		$group_table_revision = '0.2.16';
		if ( version_compare( $version, $group_table_revision ) < 0 ) {
			$sql = "CREATE TABLE $group_table_name (
							id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
							name VARCHAR(4096) NOT NULL,
							UNIQUE KEY id (id)
			) $charset_collate;";
			dbDelta( $sql );

			if ( version_compare( $new_version, $group_table_revision ) < 0
					 && version_compare( $group_table_revision, $new_version ) > 0 ) {
				$new_version = $group_table_revision;
			}
		}

		$link_group_table_revision = '0.2.16';
		if ( version_compare( $version, $link_group_table_revision ) < 0 ) {
			$sql = "CREATE TABLE $link_group_table_name (
							id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
							link_id BIGINT(20) UNSIGNED NOT NULL,
							group_id BIGINT(20) UNSIGNED NOT NULL,
							UNIQUE KEY id (id)
			) $charset_collate;";
			dbDelta( $sql );

			if ( version_compare( $new_version, $link_group_table_revision ) < 0
					 && version_compare( $link_group_table_revision, $new_version ) > 0 ) {
				$new_version = $link_group_table_revision;
			}
		}

		if (version_compare( $version, $new_version ) < 0 ) {
			update_option( 'track_the_click_version', $new_version );
		}
	}

}
