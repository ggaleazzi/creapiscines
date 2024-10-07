<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://trackthe.click
 * @since      0.0.1
 *
 * @package    Track_The_Click
 * @subpackage Track_The_Click/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Track_The_Click
 * @subpackage Track_The_Click/public
 * @author     Daniel Foster <daniel@34sp.com>
 */
class Track_The_Click_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 * @var      string    $track_the_click    The ID of this plugin.
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
	* @since 		0.2.18
	* @access		protected
	* @var			Track_The_Click_Pro		$pro		Implements pro features
	*/
	private $pro;

	/**
	* The base filename of the plugin
	*
	* @since 		0.3.10
	* @access		private
	* @var			string		$basefile	The base filename of the plugin
	*/
	private $basefile;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.0.1
	 * @param    string    $plugin_name       The name of the plugin.
	 * @param    string    $version    The version of this plugin.
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
	 * @since    0.2.18
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
			$plugin_pro = new Track_The_Click_Pro( $this->plugin_name, $this->version, $this->basefile );
			$this->pro = $plugin_pro;
		}
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    0.0.1
	 */
	public function enqueue_styles() {

		if ( get_option( 'track_the_click_click_counts', false ) ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/track-the-click-public.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    0.0.1
	 */
	public function enqueue_scripts() {

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/track-the-click-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'ajax_var', array(
			//'url' => rest_url(),
			'nonce' => wp_create_nonce('wp_rest')
		));
		wp_localize_script( $this->plugin_name, 'ttc_data', array(
			'proBeacon' => $this->pro !== false
		));
		wp_enqueue_script( $this->plugin_name );
		wp_script_add_data( $this->plugin_name, 'data-noptimize', 'true' );

		$script = "function getHomeURL() {return \"" . get_home_url() . "\";}\n";
		$script .= "function getPostID() {return " . (is_singular() ? get_the_ID() : 0) . ";}\n";
		$script .= "function showClickCounts() {return " . (get_option( 'track_the_click_click_counts', false ) ? "true" : "false") . ";}\n";

		wp_add_inline_script( $this->plugin_name, $script, 'after' );

	}

	/**
	 * Track a click on a link with JS generated onClick event.
	 *
	 * @since    0.2.0
	 */
 public function track_click_v2( $data ) {

	 global $wpdb;
	 $link_table_name = $wpdb->prefix . 'track_the_click_link';
	 $click_table_name = $wpdb->prefix . 'track_the_click_hit';
	 if ( $data['postID'] != 0 ) {
		 // We're in a single post
		 $link_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM $link_table_name WHERE post_id = %d AND url = %s", $data['postID'], $data['target']));
	 } else {
		 // We're on some page that isn't a single post
		 $link_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM $link_table_name WHERE post_id = 0 AND page_url = %s AND url = %s", $data['location'], $data['target']));
	 }
	 if ( $link_id === null ) {
		 $wpdb->insert(
			 $link_table_name,
			 array('post_id' => $data['postID'], 'url' => $data['target'], 'anchor' => $data['anchor'], 'page_url' => $data['location']),
			 array('%d', '%s', '%s', '%s')
		 );
		 $link_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM $link_table_name WHERE post_id = %d AND url = %s", $data['postID'], $data['target']));
		 if ($this->pro !== false) {
			 $this->pro->new_link_group_add($link_id, $data['target']);
		 }
	 }
	 $insert_result = $wpdb->insert(
									$click_table_name,
									array('link_id' => $link_id)
								);
   return "Tracked";

 }

	public function record_tracking_hit_v2() {

		register_rest_route( 'track-the-click/v3', '/click/(?P<cachebreak>\d+)', array(
			'methods' => ['POST'],
			'permission_callback' => '__return_true',
			'callback' => array($this, 'track_click_v2')
		) );

	}

	/**
	 * Get number of clicks on all links on a page
	 *
	 * @since    0.3.2
	 */

	public function get_page_link_clicks_cb( $data ) {

		if (get_option( 'track_the_click_click_counts', false )) {
			global $wpdb;
			$link_table_name = $wpdb->prefix . 'track_the_click_link';
			$click_table_name = $wpdb->prefix . 'track_the_click_hit';

			$clicks = $wpdb->get_results($wpdb->prepare("
				SELECT L.url AS target, L.anchor AS anchor, COUNT(H.id) as clicks
				FROM $click_table_name H
				JOIN $link_table_name L ON H.link_id=L.id
				WHERE L.post_id = %d
					AND page_url = %s
				GROUP BY L.url, L.anchor
			", $data['postID'], $data['location']), ARRAY_A);

			return $clicks;
		} else {
			return False;
		}
	}


	public function get_page_link_clicks() {

		register_rest_route( 'track-the-click/v3', '/getpagelinkclicks/(?P<cachebreak>\d+)', array(
			'methods' => ['POST'],
			'permission_callback' => '__return_true',
			'callback' => array($this, 'get_page_link_clicks_cb')
		) );

	}


	/**
	 * Add data-noptimize='true' to script tags for compatibility with the widely used plugin Autoptimize
	 * Also add data-no-optimize='1' for compatilbility with litespeed-cache
	 *
	 * @since    0.3.13
	 */

	public function add_noptimize_tag( $tag, $handle ) {
		$noptimize = wp_scripts()->get_data( $handle, 'data-noptimize' );

		if ( $noptimize ) {
			$tag = preg_replace('/<script (.*?)>(.*)/i', "<script $1 data-noptimize=\"" . esc_attr( $noptimize ) . "\" data-no-optimize=\"1\">$2", $tag);
		}

		return $tag;
	}


	public function delete_old_data() {
		$retain_days = get_option( 'track_the_click_retain_days', false );

		if ($retain_days) {
			global $wpdb;
			$click_table_name = $wpdb->prefix . 'track_the_click_hit';
	   
			$wpdb->query($wpdb->prepare("DELETE FROM $click_table_name WHERE timestamp < DATE_SUB(NOW(), INTERVAL $retain_days DAY)"));
		}
	}

}

