<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       http://example.com
 * @since      0.0.1
 *
 * @package    Track_The_Click
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option('track_the_click_version');
delete_option('track_the_click_local');
delete_option('track_the_click_ga_options');
delete_option('track_the_click_remove_noreferrer');
delete_option('track_the_click_click_counts');
delete_option('track_the_click_track_license');
delete_option('track_the_click_track_license_status');
delete_option('track_the_click_track_admins');
delete_option('track_the_click_track_users');
delete_option('track_the_click_exclude_addresses');
delete_option('track_the_click_patterns_1');
delete_option('track_the_click_retain_days');
$timestamp = wp_next_scheduled( 'track_the_click_delete_old_data' );
if ( $timestamp !== false ) {
	wp_unschedule_event( $timestamp, 'track_the_click_delete_old_data' );
}

global $wpdb;

$link_table_name = $wpdb->prefix . 'track_the_click_link';
$click_table_name = $wpdb->prefix . 'track_the_click_hit';
$group_table_name = $wpdb->prefix . 'track_the_click_group';
$link_group_table_name = $wpdb->prefix . 'track_the_click_link_group';

$wpdb->query( "DROP TABLE IF EXISTS $link_table_name" );
$wpdb->query( "DROP TABLE IF EXISTS $click_table_name" );
$wpdb->query( "DROP TABLE IF EXISTS $group_table_name" );
$wpdb->query( "DROP TABLE IF EXISTS $link_group_table_name" );
