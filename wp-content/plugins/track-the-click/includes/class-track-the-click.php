<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://trackthe.click
 * @since      0.0.1
 *
 * @package    Track_The_Click
 * @subpackage Track_The_Click/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      0.0.1
 * @package    Track_The_Click
 * @subpackage Track_The_Click/includes
 * @author     Daniel Foster <daniel@34sp.com>
 */
class Track_The_Click {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    0.0.1
	 * @access   protected
	 * @var      Track_The_Click_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    0.0.1
	 * @access   protected
	 * @var      string    plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    0.0.1
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	* The plugin's pro features class.
	*
	* @since 		0.1.3
	* @access		protected
	* @var			Track_The_Click_Pro		$pro		Implements pro features
	*/
	protected $pro;

	/**
	 * The filename of the base plugin file, used for EDD
	 * 
	 * @since		0.3.10
	 * @access		protected
	 * @var			string	$basefile	The base filename of the plugin
	 */
	protected $basefile;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    0.0.1
	 */
	public function __construct( $basefile ) {
		if ( defined( 'TRACK_THE_CLICK_VERSION' ) ) {
			$this->version = TRACK_THE_CLICK_VERSION;
		} else {
			$this->version = '0.4.0';
		}
		$this->plugin_name = 'track-the-click';
		$this->basefile = $basefile;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_pro_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Track_The_Click_Loader. Orchestrates the hooks of the plugin.
	 * - Track_The_Click_i18n. Defines internationalization functionality.
	 * - Track_The_Click_Admin. Defines all hooks for the admin area.
	 * - Track_The_Click_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    0.0.1
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-track-the-click-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-track-the-click-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-track-the-click-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-track-the-click-public.php';

		/**
		 * The class for licensing functions
		 * Easy Digital Downloads with the Software Licensing plugin
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/EDD_SL_Plugin_Updater.php';

		/**
		 * The class responsible for defining pro features.
		 */
		$pro_include = plugin_dir_path( dirname( __FILE__ ) ) . 'pro/class-track-the-click-pro.php';
		if ( file_exists( $pro_include ) ) {
			include_once $pro_include;
		}

		$this->loader = new Track_The_Click_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Track_The_Click_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    0.0.1
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Track_The_Click_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Track_The_Click_Admin( $this->get_plugin_name(), $this->get_version(), $this->basefile );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'track_the_click_register_menu' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_tools_page' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_options_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_setting' );

		$this->loader->add_action( 'update_option_track_the_click_license', $plugin_admin, 'track_the_click_license_updated', 10, 2 );

		$this->loader->add_action( 'wp_dashboard_setup', $plugin_admin, 'add_track_the_click_dashboard_widget' );

		$this->loader->add_action( 'rest_api_init', $plugin_admin, 'get_statistics_register_rest' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Track_The_Click_Public( $this->get_plugin_name(), $this->get_version(), $this->basefile );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_filter( 'script_loader_tag', $plugin_public, 'add_noptimize_tag', 10, 2 );

		$this->loader->add_action( 'rest_api_init', $plugin_public, 'record_tracking_hit_v2' );
		$this->loader->add_action( 'rest_api_init', $plugin_public, 'get_page_link_clicks' );

		$this->loader->add_action( 'track_the_click_delete_old_data', $plugin_public, 'delete_old_data' );
	}

	/**
	 * Register all of the hooks related to the pro functionality
	 * of the plugin.
	 *
	 * @since    0.1.3
	 * @access   private
	 */
	private function define_pro_hooks() {

		if ( class_exists( 'Track_The_Click_Pro' ) ) {
			$plugin_pro = new Track_The_Click_Pro( $this->get_plugin_name(), $this->get_version(), $this->basefile );
			$this->pro = $plugin_pro;
			$this->pro->define_hooks( $this->loader );

		}

	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    0.0.1
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     0.0.1
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     0.0.1
	 * @return    Track_The_Click_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     0.0.1
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
