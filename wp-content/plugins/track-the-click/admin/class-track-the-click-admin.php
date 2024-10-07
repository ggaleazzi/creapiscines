<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://trackthe.click
 * @since      0.0.1
 *
 * @package    Track_The_Click
 * @subpackage Track_The_Click/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Track_The_Click
 * @subpackage Track_The_Click/admin
 * @author     Daniel Foster <daniel@34sp.com>
 */
class Track_The_Click_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	* The plugin's pro features class.
	*
	* @since 		0.2.16
	* @access		private
	* @var			Track_The_Click_Pro		$pro		Implements pro features
	*/
	private $pro;

	/**
	* The URL for the licensing service
	*
	* @since 		0.3.9
	* @access		private
	* @var			string		$license_url		Implements pro features
	*/
	private $license_url = 'https://trackthe.click';

	/**
	* The base filename of the plugin
	*
	* @since 		0.3.10
	* @access		private
	* @var			string		$basefile	The base filename of the plugin
	*/
	private $basefile;

	/**
	 * The options name to be used in this plugin
	 *
	 * @since  	0.0.1
	 * @access 	private
	 * @var   	string 		$option_name 	Option name of this plugin
	 */
	private $option_name = 'track_the_click';


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.0.1
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 * @param      string    $basefile    The filename od the plugin's main file.
	 */
	public function __construct( $plugin_name, $version, $basefile ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->pro = false;
		$this->basefile = $basefile;

		$this->load_dependencies();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * @since    0.2.16
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		* The class responsible for defining pro features.
		*/
		$pro_include = plugin_dir_path( dirname( __FILE__ ) ) . 'pro/class-track-the-click-pro.php';
		if ( file_exists( $pro_include ) ) {
			include_once $pro_include;
		}

		if ( class_exists( 'Track_The_Click_Pro' ) ) {
			$plugin_pro = new Track_The_Click_Pro( $this->get_plugin_name(), $this->get_version(), $this->basefile );
			$this->pro = $plugin_pro;
		}


	}


	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     0.2.15
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     0.2.15
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    0.0.1
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/track-the-click-admin.css', array(), $this->version, 'all' );
		wp_register_style( 'jquery-ui', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.css' );
		wp_enqueue_style( 'jquery-ui' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    0.0.1
	 */
	public function enqueue_scripts() {

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/track-the-click-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'ajax_var', array(
			'url' => rest_url(),
			'nonce' => wp_create_nonce('wp_rest')
		));
		wp_enqueue_script( $this->plugin_name );

		$format = get_option( 'date_format', 'm/d/Y');
		if ($format == '') {$format = 'm/d/Y';}
		$format = str_replace("d","dd",$format);
		$format = str_replace("j","d",$format);
		$format = str_replace("l","DD",$format);
		$format = str_replace("m","mm",$format);
		$format = str_replace("n","m",$format);
		$format = str_replace("F","MM",$format);
		$format = str_replace("Y","yy",$format);
		wp_add_inline_script( $this->plugin_name, "function getDateFormat() {return \"" . $format . "\";}", 'after' );

		wp_enqueue_script( $this->plugin_name . 'chart', plugin_dir_url( __FILE__ ) . 'js/chart.min.js', array(), $this->version, false );
		wp_enqueue_script( 'jquery-ui-datepicker', '', array('jquery') );

	}

	/**
	 * Add a dashboard widget with a summary of internally tracked link clicks.
	 *
	 * @since    0.0.1
	 */

	public function add_track_the_click_dashboard_widget() {

		wp_add_dashboard_widget("track-the-click", "Track The Click quick stats", array( $this, 'display_track_the_click_dashboard_widget'));

	}

	public function display_track_the_click_dashboard_widget() {

		global $wpdb;
		$link_table_name = $wpdb->prefix . 'track_the_click_link';
		$click_table_name = $wpdb->prefix . 'track_the_click_hit';
		$query = "SELECT L.post_id AS post_id, L.url AS url, L.anchor AS anchor, L.page_url AS page_url, COUNT(H.id) AS hits FROM $link_table_name L JOIN $click_table_name H ON L.id=H.link_id WHERE H.timestamp > DATE_SUB(NOW(), INTERVAL 30 DAY) GROUP BY post_id, url ORDER BY hits DESC LIMIT 10";
		$results = $wpdb->get_results($query);
		echo "<p>Top 10 clicks in the past 30 days:</p><p>";
		foreach ($results as $result) {
			if ( $result->post_id != 0 ) {
				$post = get_post($result->post_id);
				$title = $post->post_title;
				$link = get_permalink($post);
			} else {
				$title = $result->url;
				$link = $result->page_url;
			}
			$anchor = $result->anchor;
			if ( $anchor == '' ) {
				$newanchor = substr( $result->url, 0, 17 );
				if ( strlen( $result->url ) > 17 ) {
					$newanchor .= '...';
				}
				$anchor = $newanchor;
			}
			echo esc_html( $result->hits ) . " clicks on <a href=\"" . esc_url( $result->url )  ."\">" . esc_html( $anchor ) . "</a>" .
				" in <a href=\"" . esc_url( $link ) . "\">" . esc_html( $title ) . "</a><br>";
		}
		echo "</p>";
		$morelink = admin_url( 'tools.php?page=' . $this->plugin_name );
		echo "<p style=\"text-align:right\"><a href=\"" . esc_url( $morelink ) . "\">More...</a></p>";

	}


	/**
	 * Register the menu page.
	 *
	 * @since 0.0.22
	 */
	function track_the_click_register_menu(){

	    add_menu_page(
	        __( 'Track The Click', 'track-the-click' ),
	        'Track The Click',
	        'manage_options',
	        'track-the-click',
	        array( $this, 'track_the_click_menu_page'),
	        plugin_dir_url( __FILE__ ) . 'img/track-the-click-menu-icon-20.png',
	        31
	    );

	}

	/**
	 * Display the menu page
	 *
	 * @since 0.0.22
	 */
	function track_the_click_menu_page(){
	    // esc_html_e( 'Admin Page Test', 'track-the-click' );
	}

	/**
		 * Add a settings page under the Track The Click menu
		 *
		 * @since  0.0.1
		 */
	public function add_options_page() {

		$this->plugin_screen_hook_suffix = add_submenu_page(
			'track-the-click',
			__( 'Track The Click Settings', 'track-the-click' ),
			__( 'Settings', 'track-the-click' ),
			'manage_options',
			$this->plugin_name . '-settings',
			array( $this, 'display_options_page' )
		);

	}

	/**
	 * Render the options page for plugin
	 *
	 * @since  0.0.1
	 */
	public function display_options_page() {

		include_once 'partials/track-the-click-settings-display.php';

	}

	/**
		 * Add a click page under the Track The Click menu
		 *
		 * @since  0.0.5
		 */
	 public function add_tools_page() {

 		$this->plugin_screen_hook_suffix = add_submenu_page(
 			'track-the-click',
 			__( 'Clicks', 'track-the-click' ),
			__( 'Clicks', 'track-the-click' ),
 			'manage_options',
 			$this->plugin_name,
 			array( $this, 'display_tools_page' )
 		);

 	}

	/**
	 * Render the statistics page for plugin
	 *
	 * @since  0.0.5
	 */
	public function display_tools_page() {

		include_once 'partials/track-the-click-tools-display.php';

	}

	/**
	 * Register the settings
	 *
	 * @since  0.0.1
	 */
	public function register_setting() {

		// Add a Local section
		add_settings_section(
			$this->option_name . '_local',
			__( 'Local tracking', 'track-the-click' ),
			array( $this, $this->option_name . '_local_section_cb' ),
			$this->plugin_name
		);

		add_settings_field(
			$this->option_name . '_local',
			__( 'Local outbound link tracking', 'track-the-click' ),
			array( $this, $this->option_name . '_local_cb' ),
			$this->plugin_name,
			$this->option_name . '_local',
			array( 'label_for' => $this->option_name . '_local' )
		);

		register_setting( $this->plugin_name, $this->option_name . '_local' );

		add_settings_field(
			$this->option_name . '_retain_days',
			__( 'Data retention time', 'track-the-click' ),
			array( $this, $this->option_name . '_retain_days_cb' ),
			$this->plugin_name,
			$this->option_name . '_local',
			array( 'label_for' => $this->option_name . '_retain_days' )
		);

		register_setting( $this->plugin_name, $this->option_name . '_retain_days', array( $this, $this->option_name . '_sanitize_retain_days') );

		// Add an Other section
		add_settings_section(
			$this->option_name . '_other',
			__( 'Other settings', 'track-the-click' ),
			array( $this, $this->option_name . '_other_section_cb' ),
			$this->plugin_name . '-other'
		);

		add_settings_field(
			$this->option_name . '_exclude_addresses',
			__( 'Exclude URLs', 'track-the-click' ),
			array( $this, $this->option_name . '_exclude_addresses_cb' ),
			$this->plugin_name . '-other',
			$this->option_name . '_other',
			array( 'label_for' => $this->option_name . '_exclude_addresses' )
		);

		register_setting( $this->plugin_name, $this->option_name . '_exclude_addresses' );

		add_settings_field(
			$this->option_name . '_click_counts',
			__( 'Display click counts', 'track-the-click' ),
			array( $this, $this->option_name . '_click_counts_cb' ),
			$this->plugin_name . '-other',
			$this->option_name . '_other',
			array( 'label_for' => $this->option_name . '_click_counts' )
		);

		register_setting( $this->plugin_name, $this->option_name . '_click_counts' );

		// Add a Pro section
		add_settings_section(
			$this->option_name . '_pro',
			__( 'Pro settings', 'track-the-click' ),
			array( $this, $this->option_name . '_pro_section_cb' ),
			$this->plugin_name . '-pro'
		);

		add_settings_field(
			$this->option_name . '_remove_noreferrer',
			__( 'Remove noreferrer from link relationships', 'track-the-click' ),
			array( $this, $this->option_name . '_remove_noreferrer_cb' ),
			$this->plugin_name . '-pro',
			$this->option_name . '_pro',
			array( 'label_for' => $this->option_name . '_remove_noreferrer' )
		);

		register_setting( $this->plugin_name, $this->option_name . '_remove_noreferrer' );

		add_settings_field(
			$this->option_name . '_track_admins',
			__( 'Track clicks from admin users', 'track-the-click' ),
			array( $this, $this->option_name . '_track_admins_cb' ),
			$this->plugin_name . '-pro',
			$this->option_name . '_pro',
			array( 'label_for' => $this->option_name . '_track_admins' )
		);

		register_setting( $this->plugin_name, $this->option_name . '_track_admins' );

		add_settings_field(
			$this->option_name . '_track_users',
			__( 'Track clicks from logged in users', 'track-the-click' ),
			array( $this, $this->option_name . '_track_users_cb' ),
			$this->plugin_name . '-pro',
			$this->option_name . '_pro',
			array( 'label_for' => $this->option_name . '_track_users' )
		);

		register_setting( $this->plugin_name, $this->option_name . '_track_users' );

		// Add a license section under Pro
		add_settings_section(
			$this->option_name . '_license',
			__( 'Track The Click Pro License', 'track-the-click' ),
			array( $this, $this->option_name . '_license_section_cb' ),
			$this->plugin_name . '-pro'
		);

		add_settings_field(
			$this->option_name . '_license',
			__( 'License number', 'track-the-click' ),
			array( $this, $this->option_name . '_license_cb' ),
			$this->plugin_name . '-pro',
			$this->option_name . '_license',
			array( 'label_for' => $this->option_name . '_license' )
		);

		register_setting( $this->plugin_name, $this->option_name . '_license' );

		add_settings_field(
			$this->option_name . '_license_status',
			__( 'License status', 'track-the-click' ),
			array( $this, $this->option_name . '_license_status_cb' ),
			$this->plugin_name . '-pro',
			$this->option_name . '_license',
			array( 'label_for' => $this->option_name . '_license_status' )
		);

	}

	/**
	 * Render the text for the local section
	 *
	 * @since  0.0.1
	 */
	public function track_the_click_local_section_cb() {

		echo '<p>' . __( 'Local click tracking, data stored on this server.', 'track-the-click' ) . '</p>';

	}

	/**
	 * Render the checkbox input field for local tracking option
	 *
	 * @since  0.0.1
	 */
	public function track_the_click_local_cb() {

		$local = get_option( $this->option_name . '_local' );
		?>
			<label>
				<input type="hidden"  name="<?php echo esc_html( $this->option_name . '_local' ) ?>" id="<?php echo esc_html( $this->option_name . '_local' ) ?>" value="0">
				<input type="checkbox" name="<?php echo esc_html( $this->option_name . '_local' ) ?>" id="<?php echo esc_html( $this->option_name . '_local' ) ?>" value="1" <?php checked( $local, '1' ); ?>>
				<?php _e( 'Local tracking', 'track-the-click' ); ?>
			</label>
		<?php

	}

	/**
	 * Render the data retention field
	 *
	 * @since  0.3.6
	 */
	public function track_the_click_retain_days_cb() {

		$retain_days = get_option( $this->option_name . '_retain_days', '' );
		
		echo '<input type="text" name="' . esc_html( $this->option_name ) . '_retain_days' . '" id="' . esc_html( $this->option_name ) . '_retain_days' . '" placeholder="Do not delete data" value="' . esc_html( $retain_days ) . '"><br>';
		echo 'Keep click data on this server for this many days.<br>Leave blank to retain click data indefinitely.';

	}

	/**
	 * Sanitize the data retention setting
	 *
	 * @since  0.3.6
	 */

	public function track_the_click_sanitize_retain_days( $input ) {

		$output = $input;

		if ( !is_numeric( $input ) ) {
			if ( $input == '' ) {
				$timestamp = wp_next_scheduled( 'track_the_click_delete_old_data' );
				if ( $timestamp !== false ) {
					wp_unschedule_event( $timestamp, 'track_the_click_delete_old_data' );
				}
				delete_option( $this->option_name . '_retain_days' );
				$output = false;
			} else {
				add_settings_error(
					'Data retention time', 'track-the-click',
					'invalid-retain-days',
					'Please enter a number for data retention',
					'error'
				);
				$output = get_option( $this->option_name . '_retain_days', false );
			}
		} elseif ( $input == 0 ) {
			$timestamp = wp_next_scheduled( 'track_the_click_delete_old_data' );
			if ( $timestamp !== false ) {
				wp_unschedule_event( $timestamp, 'track_the_click_delete_old_data' );
			}
			delete_option( $this->option_name . '_retain_days' );
			$output = false;
		}

		if ( $output !== false ) {
			$timestamp = wp_next_scheduled( 'track_the_click_delete_old_data' );
			if ( $timestamp !== false ) {
				wp_unschedule_event( $timestamp, 'track_the_click_delete_old_data' );
			}
			wp_schedule_event( time(), 'daily', 'track_the_click_delete_old_data' );
		}

		return $output;

	}


	/**
	 * Render the text for the pro section
	 *
	 * @since  0.2.7
	 */
	public function track_the_click_pro_section_cb() {

		// echo '<p>' . __( 'Pro settings.', 'track-the-click' ) . '</p>';
		return;

	}

	/**
	 * Render the checkbox input field for noreferrer removeal option
	 *
	 * @since  0.1.2
	 */
	public function track_the_click_remove_noreferrer_cb() {

		$remove_noreferrer = get_option( $this->option_name . '_remove_noreferrer' );
		?>
			<label>
				<input type="hidden"  name="<?php echo esc_html( $this->option_name . '_remove_noreferrer' ) ?>" id="<?php echo esc_html( $this->option_name . '_remove_noreferrer' ) ?>" value="0">
				<input type="checkbox" name="<?php echo esc_html( $this->option_name . '_remove_noreferrer' ) ?>" id="<?php echo esc_html( $this->option_name . '_remove_noreferrer' ) ?>" value="1" <?php checked( $remove_noreferrer, '1' ); ?>>
				<?php _e( 'Remove noreferrer', 'track-the-click' ); ?>
			</label>
		<?php

	}

	/**
	 * Render the checkbox input field for admin tracking
	 *
	 * @since  0.3.9
	 */
	public function track_the_click_track_admins_cb() {

		$track_admins = get_option( $this->option_name . '_track_admins', true );
		?>
			<label>
				<input type="hidden"  name="<?php echo esc_html( $this->option_name . '_track_admins' ) ?>" id="<?php echo esc_html( $this->option_name . '_track_admins' ) ?>" value="0">
				<input type="checkbox" name="<?php echo esc_html( $this->option_name . '_track_admins' ) ?>" id="<?php echo esc_html( $this->option_name . '_track_admins' ) ?>" value="1" <?php checked( $track_admins, '1' ); ?>>
				<?php _e( 'Track admins', 'track-the-click' ); ?>
			</label>
		<?php

	}

	/**
	 * Render the checkbox input field for logged in user tracking
	 *
	 * @since  0.3.9
	 */
	public function track_the_click_track_users_cb() {

		$track_users = get_option( $this->option_name . '_track_users', true );
		?>
			<label>
				<input type="hidden"  name="<?php echo esc_html( $this->option_name . '_track_users' ) ?>" id="<?php echo esc_html( $this->option_name . '_track_users' ) ?>" value="0">
				<input type="checkbox" name="<?php echo esc_html( $this->option_name . '_track_users' ) ?>" id="<?php echo esc_html( $this->option_name . '_track_users' ) ?>" value="1" <?php checked( $track_users, '1' ); ?>>
				<?php _e( 'Track logged in users', 'track-the-click' ); ?><br>
				Note that this will not track admin level users unless the "Track admins" box is checked above.
			</label>
		<?php

	}

	/**
	 * Render the text for the license section
	 *
	 * @since  0.3.9
	 */
	public function track_the_click_license_section_cb() {

		// echo '<p>' . __( 'License settings.', 'track-the-click' ) . '</p>';
		return;

	}


	/**
	 * Render the license number field
	 *
	 * @since  0.3.9
	 */
	public function track_the_click_license_cb() {

		$license = get_option( $this->option_name . '_license', '' );
		
		echo '<input type="text" name="' . esc_html( $this->option_name ) . '_license' . '" id="' . esc_html( $this->option_name ) . '_license' . '" value="' . esc_html( $license ) . '"><br>';

	}

	/**
	 * Render the license status field
	 *
	 * @since  0.3.9
	 */
	public function track_the_click_license_status_cb() {

		$license_status = get_option( $this->option_name . '_license_status', false );
		
		//echo '<input type="text" name="' . esc_html( $this->option_name ) . '_license' . '" id="' . esc_html( $this->option_name ) . '_license' . '" value="' . esc_html( $license ) . '"><br>';
		if ($license_status == "valid") {
			echo "Valid";
		} else {
			echo "Unlicensed";
		}

	}

	/**
	 * Handle activating a license when license number updated
	 *
	 * @since  0.3.9
	 */

	public function track_the_click_license_updated( $old_value, $new_value ) {
		// Check for valid license
		$store_url = $this->license_url;
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $new_value,
			'item_id'    => 514,
			'url'        => home_url()
		);
		// Call the custom API.
		$response = wp_remote_post( $store_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$license_message = $response->get_error_message();
			} else {
				$license_message = __( 'An error occurred, please try again.', 'track-the-click' );
			}

		} else {
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( false === $license_data->success ) {
				delete_site_option( 'track_the_click_license_status' );
				switch( $license_data->error ) {

					case 'expired' :

						$license_message = sprintf(
							__( 'Your license key expired on %s.', 'track-the-click' ),
							date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;

					case 'disabled' :
					case 'revoked' :

						$license_message = __( 'Your license key has been disabled.', 'track-the-click' );
						break;

					case 'missing' :

						$license_message = __( 'Invalid license.', 'track-the-click' );
						break;

					case 'invalid' :
					case 'site_inactive' :

						$license_message = __( 'Your license is not active for this URL.', 'track-the-click' );
						break;

					case 'item_name_mismatch' :

						$license_message = sprintf( __( 'This appears to be an invalid license key for %s.', 'track-the-click' ), 'Track The Click' );
						break;

					case 'no_activations_left':

						$license_message = __( 'Your license key has reached its activation limit.', 'track-the-click' );
						break;

					default :

						$license_message = __( 'An error occurred, please try again.', 'track-the-click' );
						break;
				}
			}
			if ( empty( $license_message ) ) {
				update_site_option( 'track_the_click_license_status', $license_data->license );
			}
		}
	}

	/**
	 * Render the text for the other section
	 *
	 * @since  0.1.2
	 */
	public function track_the_click_other_section_cb() {

		// echo '<p>' . __( 'Other settings.', 'track-the-click' ) . '</p>';
		return;

	}

	/**
	 * Render the checkbox input field for local tracking option
	 *
	 * @since  0.1.3
	 */
	public function track_the_click_exclude_addresses_cb() {

		$exclude_addresses = get_option( $this->option_name . '_exclude_addresses' );
		?>
			<label>
				<p>As standard we try to exclude your own domain name from the click views.  We do this by adding your Site Address - currently <b><?php echo get_home_url() ?></b> - to the domain exclusion list.  This is set under Settings -> General -> Site Address.</p>
				<p>Sometimes you might have internal links that don’t match the Site Address.  For example if your Site Address is<br>
				https://domain.com<br>
				you might also have links in your site of the form<br>
				https://www.domain.com<br>
				In these instances you will need to add the extra variations of your site to the exclusion list.  The clicks are still recorded, but they will be hidden in the various click views to help you interpret your data better.<p>
				<p>Please add any domains you want to exclude to the list below.  Please add one domain per line only.</p>
				<p><textarea name="<?php echo esc_html( $this->option_name . '_exclude_addresses' ) ?>" id="<?php echo esc_html( $this->option_name . '_exclude_addresses' ) ?>" rows="10" cols="50"><?php echo esc_html( $exclude_addresses ) ?></textarea></p>
			</label>
		<?php

	}

	/**
	 * Render the checkbox input field for display of click counts
	 *
	 * @since  0.3.1
	 */
	public function track_the_click_click_counts_cb() {

		$click_counts = get_option( $this->option_name . '_click_counts', false );
		?>
			<label>
				<input type="hidden"  name="<?php echo esc_html( $this->option_name . '_click_counts' ) ?>" id="<?php echo esc_html( $this->option_name . '_click_counts' ) ?>" value="0">
				<input type="checkbox" name="<?php echo esc_html( $this->option_name . '_click_counts' ) ?>" id="<?php echo esc_html( $this->option_name . '_click_counts' ) ?>" value="1" <?php checked( $click_counts, '1' ); ?>>
				<?php _e( 'Display click counts next to links on the website', 'track-the-click' ); ?>
			</label>
		<?php

	}

	/**
	 * Register REST API route for stats data retrieval
	 *
	 * @since  0.0.7
	 */
	public function get_statistics_register_rest() {

		register_rest_route( 'track-the-click/v1', '/stats', array(
			'methods' => 'GET',
			'permission_callback' => array($this,'get_statistics_rest_permission_check'),
			'callback' => array($this, 'get_statistics')
		) );

	}

	/**
	 * Check permisssion for stats retrieval
	 *
	 * @since  0.0.7
	 */
	public function get_statistics_rest_permission_check() {

		return current_user_can( 'manage_options' );

	}

	/**
	 * Retrieve data for the statistics page
	 *
	 * @since  0.0.7
	 */
	public function get_statistics( $data ) {

		$parameters = $data->get_params();
		$start = $parameters['start'];
		$end = $parameters['end'];
		$timescale = $parameters['group_time'];
		if( in_array( 'link', array_keys( $parameters ) ) ) {
			$querylink = $parameters['link'];
		} else {
		 	$querylink = False;
		}

		$layout = null;

		$wptimezone = wp_timezone();
		$wptime = new DateTime("now", $wptimezone);
		$wptimediff = $wptime->getOffset();

		// Some hosts block use of exec()
		if ( function_exists( 'exec' ) ) {
			$output = null;
			$return = null;
			if ( exec( 'timedatectl | grep zone', $output, $return ) ) {
				if ( $return == 0 ) {
					$parts = preg_split( "/[\s]+/", trim( $output[0] ) );
					date_default_timezone_set( $parts[2] );
				}
			}
		}
		
		$servertimezone = new DateTimeZone(date_default_timezone_get());
		$servertime = new DateTime("now", $servertimezone);
		$servertimediff = $servertime->getOffset();

		$timediff = $wptimediff - $servertimediff;

		if ( $timescale == 'day' ) {
			$date_format = '%%Y-%%m-%%d';
			$interval = new DateInterval( 'P1D' );
			$format = 'd';
			$label = 'Date';
		} else if ( $timescale == 'hour' ) {
			$date_format = '%%H';
			$interval = new DateInterval( 'PT1H' );
			$format = 'h';
			$label = 'Hour';
		}

		$exclusions = array( get_home_url() );
		$exclusions_option = get_option( 'track_the_click_exclude_addresses', '' );
		if ( strlen( $exclusions_option ) > 0 ) {
			$exclusions = array_merge( $exclusions, explode( "\r\n", $exclusions_option ) ) ;
		}
		$exclusions_sql = "AND L.url NOT LIKE '" . implode ("%' AND L.url NOT LIKE '", $exclusions) . "%'";

		if ( strlen( $exclusions_option ) > 0 ) {
			$exclusions = explode( "\r\n", $exclusions_option );
			$exclusions_sql = "AND L.url NOT LIKE '" . implode ("%' AND L.url NOT LIKE '", $exclusions) . "%'";
		} else {
			$exclusions_sql = "";
		}

		global $wpdb;
		$link_table_name = $wpdb->prefix . 'track_the_click_link';
		$click_table_name = $wpdb->prefix . 'track_the_click_hit';
		$group_table_name = $wpdb->prefix . 'track_the_click_group';
		$link_group_table_name = $wpdb->prefix . 'track_the_click_link_group';

		if( in_array( 'linkgroup', array_keys( $parameters ) ) ) {
			$group = $parameters['linkgroup'];
			$group_sql = $wpdb->prepare("LEFT JOIN $link_group_table_name LG ON L.id=LG.link_id LEFT JOIN (SELECT id FROM $group_table_name WHERE id=%d) G ON LG.group_id=G.id", $group);
			$group_sql_where = $wpdb->prepare("AND G.id=%d", $group);
		} else {
		 	$group_sql='';
			$group_sql_where='';
		}

		if ( ! $querylink ) {
			$graphquery = $wpdb->prepare(
				"SELECT DATE_FORMAT(DATE_ADD(H.timestamp, INTERVAL $timediff SECOND), '$date_format') AS date,
					COUNT(H.id) AS hits
				FROM $click_table_name H
				JOIN $link_table_name L ON H.link_id=L.id
				$group_sql
				WHERE TO_DAYS(DATE_ADD(H.timestamp, INTERVAL $timediff SECOND)) >= TO_DAYS('%s')
					AND TO_DAYS(DATE_ADD(H.timestamp, INTERVAL $timediff SECOND)) <= TO_DAYS('%s')
					$exclusions_sql
					$group_sql_where
				GROUP BY DATE_FORMAT(DATE_ADD(H.timestamp, INTERVAL $timediff SECOND), '$date_format')",
				$start, $end);
		} else {
			// We're querying about a single link
			$graphquery = $wpdb->prepare(
				"SELECT DATE_FORMAT(DATE_ADD(H.timestamp, INTERVAL $timediff SECOND), '$date_format') AS date,
					COUNT(H.id) AS hits
				FROM $click_table_name H
				WHERE TO_DAYS(DATE_ADD(H.timestamp, INTERVAL $timediff SECOND)) >= TO_DAYS('%s')
					AND TO_DAYS(DATE_ADD(H.timestamp, INTERVAL $timediff SECOND)) <= TO_DAYS('%s')
					AND H.link_id = %d
				GROUP BY DATE_FORMAT(DATE_ADD(H.timestamp, INTERVAL $timediff SECOND), '$date_format')",
				$start, $end, $querylink);
		}

		$results = $wpdb->get_results($graphquery);

		if ( $timescale == 'day' ) {
			$daterange = new DatePeriod( new DateTime( $start ), $interval, ( new DateTime( $end ) )->modify( '+1 day' ) );
		} else if ( $timescale == 'hour' ) {
			$daterange = range( 0, 23, 1 );
		}
		$days = array();
		$hits = array();
		foreach ( $daterange as $period ) {
			$periodhits = 0;
			foreach ( $results as $result ) {
				if ( $timescale == 'day' ) {
					if ( $period == new DateTime( $result->date ) ) {
						$periodhits = $result->hits;
					}
				} else if ( $timescale == 'hour' ) {
					if ( $period == (int)$result->date ) {
						$periodhits = $result->hits;
					}
				}
			}
			if ( $timescale == 'day' ) {
				array_push( $days, $period->format( $format ) );
			} else if ( $timescale == 'hour' ) {
				array_push( $days, $period );
			}
			array_push( $hits, $periodhits );
		}

		$graphjson = array(
			'type' => 'line',
			'data' => array(
				'labels' => $days,
				'datasets' => array(
					array(
						'label' => 'Clicks',
						'backgroundColor' => 'rgb(54, 162, 235)',
						'borderColor' => 'rgb(54, 162, 235)',
						'data' => $hits,
						'fill' => false
					)
				)
			),
			'options' => array(
				'responsive' => true,
				'maintainAspectRatio' => false,
				'legend' => array(
					'display' => false
				),
				'tooltips' => array(
					'mode' => 'index',
					'intersect' => false
				),
				'hover' => array(
					'mode' => 'nearest',
					'intersect' => true
				),
				'plugins' => array(
					'legend' => array(
						'display' => false
					)
				),
				'scales' => array(
					'y' => array(
						'display' => true,
						'min' => 0,
						'ticks' => array(
							'precision' => 0
						)
					)
				)
			)
		);

		if ( ! $querylink ) {
			if ($parameters["group"] == 'link') {
				$layout = 'by-link';
				$tablequery = $wpdb->prepare(
					"SELECT L.post_id AS post_id,
					  L.page_url AS page_url,
						L.url AS url,
						L.anchor AS anchor,
						COUNT(H.id) AS hits,
						L.id AS link_id
					FROM $click_table_name H
					JOIN $link_table_name L ON H.link_id=L.id
					$group_sql
					WHERE TO_DAYS(DATE_ADD(H.timestamp, INTERVAL $timediff SECOND)) >= TO_DAYS('%s')
						AND TO_DAYS(DATE_ADD(H.timestamp, INTERVAL $timediff SECOND)) <= TO_DAYS('%s')
						$exclusions_sql
						$group_sql_where
					GROUP BY post_id, url
					ORDER BY hits DESC",
					$start, $end);
				$results = $wpdb->get_results($tablequery);

				$tablejson = array();
				foreach ($results as $result) {
					if ($result->post_id != 0) {
						$post = get_post($result->post_id);
						$title = $post->post_title;
						$link = get_permalink($post);
					} else {
						$title = $result->page_url;
						$link = $result->page_url;
					}
					array_push( $tablejson,
										  array(
												'link' => esc_url($link),
												'post_title' => esc_html($title),
												'url' => esc_url($result->url),
												'anchor' => esc_html($result->anchor),
												'hits' => $result->hits,
												'link_id' => $result->link_id,
												property_exists($result, "money") ? $result->money : 0
											)
					);
				}
			} else if ($parameters["group"] == 'domain') {
				$layout = 'by-domain';
				$tablequery = $wpdb->prepare(
				  "SELECT REGEXP_REPLACE(REGEXP_REPLACE(L.url, '^https?://(www\.)?', ''), '/.*', '') AS domain,
					  COUNT(H.id) AS hits
					FROM $click_table_name H
					JOIN $link_table_name L ON H.link_id=L.id
					$group_sql
					WHERE TO_DAYS(DATE_ADD(H.timestamp, INTERVAL $timediff SECOND)) >= TO_DAYS('%s')
						AND TO_DAYS(DATE_ADD(H.timestamp, INTERVAL $timediff SECOND)) <= TO_DAYS('%s')
						$exclusions_sql
						$group_sql_where
					GROUP BY domain
					ORDER BY hits DESC",
					$start, $end);
				$results = $wpdb->get_results($tablequery);

				$tablejson = array();
				foreach ($results as $result) {
					array_push( $tablejson,
										  array(
												'domain' => $result->domain,
												'hits' => $result->hits,
											)
					);
				}
			} else if ($parameters["group"] == 'page') {
				$layout = 'by-page';
				$tablequery = $wpdb->prepare(
					"SELECT L.post_id AS post_id,
					  L.page_url AS page_url,
						COUNT(H.id) AS hits
					FROM $click_table_name H
					JOIN $link_table_name L ON H.link_id=L.id
					$group_sql
					WHERE TO_DAYS(DATE_ADD(H.timestamp, INTERVAL $timediff SECOND)) >= TO_DAYS('%s')
						AND TO_DAYS(DATE_ADD(H.timestamp, INTERVAL $timediff SECOND)) <= TO_DAYS('%s')
						$exclusions_sql
						$group_sql_where
					GROUP BY post_id, page_url
					ORDER BY hits DESC", $start, $end);
				$results = $wpdb->get_results($tablequery);

				$tablejson = array();
				foreach ($results as $result) {
					if ($result->post_id != 0) {
						$post = get_post($result->post_id);
						$title = $post->post_title;
					} else {
						$title = $result->page_url;
					}
					$link = get_permalink($post);
					array_push( $tablejson,
											array(
												'link' => $link,
												'post_title' => esc_html($title),
												'page_url' => esc_url($result->page_url),
												'hits' => $result->hits
											)
					);
				}
			}
		} else {
			// We're querying about a single link
			$layout = 'single-link';
			$tablequery = $wpdb->prepare(
				"SELECT DATE_FORMAT(DATE_ADD(H.timestamp, INTERVAL $timediff SECOND), '%%Y-%%m-%%d %%H:%%i:%%s') AS time
				FROM $click_table_name H
				WHERE H.link_id = %d
				  AND TO_DAYS(DATE_ADD(H.timestamp, INTERVAL $timediff SECOND)) >= TO_DAYS('%s')
					AND TO_DAYS(DATE_ADD(H.timestamp, INTERVAL $timediff SECOND)) <= TO_DAYS('%s')
				ORDER BY H.timestamp ASC", $querylink, $start, $end);
			$results = $wpdb->get_results($tablequery);

			$tablejson = array();
			foreach ($results as $result) {
				array_push( $tablejson,
										array (
											'time' => $result->time
										)
				);
			}
		}

		$json = array( 'layout' => $layout, 'chart' => $graphjson, 'table' => $tablejson, 'text' => '' );
		return rest_ensure_response( $json );

	}

}
