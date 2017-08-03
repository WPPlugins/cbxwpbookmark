<?php

	/**
	 * The file that defines the core plugin class
	 *
	 * A class definition that includes attributes and functions used across both the
	 * public-facing side of the site and the admin area.
	 *
	 * @link       codeboxr.com
	 * @since      1.0.0
	 *
	 * @package    Cbxwpbookmark
	 * @subpackage Cbxwpbookmark/includes
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
	 * @since      1.0.0
	 * @package    Cbxwpbookmark
	 * @subpackage Cbxwpbookmark/includes
	 * @author     CBX Team  <info@codeboxr.com>
	 */
	class Cbxwpbookmark
	{

		/**
		 * The loader that's responsible for maintaining and registering all hooks that power
		 * the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      Cbxwpbookmark_Loader $loader Maintains and registers all hooks for the plugin.
		 */
		protected $loader;

		/**
		 * The unique identifier of this plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string $plugin_name The string used to uniquely identify this plugin.
		 */
		protected $plugin_name;

		/**
		 * The current version of the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string $version The current version of the plugin.
		 */
		protected $version;

		/**
		 * Define the core functionality of the plugin.
		 *
		 * Set the plugin name and the plugin version that can be used throughout the plugin.
		 * Load the dependencies, define the locale, and set the hooks for the admin area and
		 * the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function __construct()
		{

			$this->plugin_name = CBXWPBOOKMARK_PLUGIN_NAME;
			$this->version     = CBXWPBOOKMARK_PLUGIN_VERSION;

			$this->load_dependencies();
			$this->set_locale(); //language
			$this->define_admin_hooks();
			$this->define_public_hooks();
		}

		/**
		 * Load the required dependencies for this plugin.
		 *
		 * Include the following files that make up the plugin:
		 *
		 * - Cbxwpbookmark_Loader. Orchestrates the hooks of the plugin.
		 * - Cbxwpbookmark_i18n. Defines internationalization functionality.
		 * - Cbxwpbookmark_Admin. Defines all hooks for the admin area.
		 * - Cbxwpbookmark_Public. Defines all hooks for the public side of the site.
		 *
		 * Create an instance of the loader which will be used to register the hooks
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function load_dependencies()
		{

			/**
			 * The class responsible for orchestrating the actions and filters of the
			 * core plugin.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-cbxwpbookmark-loader.php';

			/**
			 * The class responsible for defining internationalization functionality
			 * of the plugin.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-cbxwpbookmark-i18n.php';

			/**
			 * The class responsible for defining all actions that occur in the admin area.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-cbxwpbookmark-admin.php';

			//add widget class ( CBX BOOKMARK CATEGORY )
			require_once plugin_dir_path(dirname(__FILE__)) . 'widgets/cbxwpbookmark-category-widget/cbxwpbookmark-category-widget.php';

			// CBX WP BOOKMARK Widget
			require_once plugin_dir_path(dirname(__FILE__)) . 'widgets/cbxwpbookmark-widget/cbxbookmark-widget.php';

			// CBX WP Most BOOKMARKED Widget
			require_once plugin_dir_path(dirname(__FILE__)) . 'widgets/cbxwpbookmarkmost-widget/cbxbookmarkmost-widget.php';

			// Loading Settings Class
			require_once plugin_dir_path(dirname(__FILE__)) . 'settings/class-cbxwpbookmark-setting.php';

			/**
			 * The class responsible for defining all actions that occur in the public-facing
			 * side of the site.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-cbxwpbookmark-public.php';

			$this->loader = new Cbxwpbookmark_Loader();
		}

		/**
		 * Define the locale for this plugin for internationalization.
		 *
		 * Uses the Cbxwpbookmark_i18n class in order to set the domain and to register the hook
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function set_locale()
		{

			$plugin_i18n = new Cbxwpbookmark_i18n();
			$plugin_i18n->set_domain($this->get_plugin_name());

			$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
		}

		/**
		 * Register all of the hooks related to the admin area functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_admin_hooks()
		{

			$plugin_admin = new Cbxwpbookmark_Admin($this->get_plugin_name(), $this->get_version());

			$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
			$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

			//adding the setting action
			$this->loader->add_action('admin_init', $plugin_admin, 'setting_init');

			//for testing plugin error.Comment out after successful testing.
			//$this->loader->add_action('activated_plugin', $plugin_admin, 'cbxwpbookmark_activation_error', 10);//activate for testing only....search for 'cbxaccounting_activation_error' meta name in option table if error occurs.

			$plugin_admin->set_loader($this->loader);
			$plugin_admin->run();
		}

		/**
		 * Register all of the hooks related to the public-facing functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_public_hooks()
		{

			$plugin_public = new Cbxwpbookmark_Public($this->get_plugin_name(), $this->get_version());

			$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
			$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

			$this->loader->add_filter('the_content', $plugin_public, "cbx_bookmark_content_filter");


			$this->loader->add_action('wp_ajax_cbx_add_category', $plugin_public, 'add_category');
			//$this->loader->add_action('wp_ajax_nopriv_cbx_add_category', $plugin_public, 'add_category');

			// Delete Category from Front Admin
			$this->loader->add_action('wp_ajax_cbx_delete_bookmark_category', $plugin_public, 'delete_bookmark_category');
			$this->loader->add_action('wp_ajax_nopriv_cbx_delete_bookmark_category', $plugin_public, 'delete_bookmark_category');

			// Delete Category from Front Admin (delete_bookmark_post)
			$this->loader->add_action('wp_ajax_cbx_delete_bookmark_post', $plugin_public, 'delete_bookmark_post');
			//$this->loader->add_action('wp_ajax_nopriv_cbx_delete_bookmark_post', $plugin_public, 'delete_bookmark_post');

			// Update Category from Front Admin
			$this->loader->add_action('wp_ajax_cbx_update_bookmark_category', $plugin_public, 'update_bookmark_category');
			//$this->loader->add_action('wp_ajax_nopriv_cbx_update_bookmark_category', $plugin_public, 'update_bookmark_category');

			//find all boomkark category by loggedin user ajax hook
			$this->loader->add_action('wp_ajax_cbx_find_category', $plugin_public, 'find_category');
			//$this->loader->add_action('wp_ajax_nopriv_cbx_find_category', $plugin_public, 'find_category');

			//add bookmark for loggedin user ajax hook
			$this->loader->add_action('wp_ajax_cbx_add_bookmark', $plugin_public, 'add_bookmark');
			//$this->loader->add_action('wp_ajax_nopriv_cbx_add_bookmark', $plugin_public, 'add_bookmark');

			//loadmore bookmark ajax
			$this->loader->add_action('wp_ajax_cbx_bookmark_loadmore', $plugin_public, 'bookmark_loadmore');
			//widget
			$this->loader->add_action('widgets_init', $plugin_public, 'register_widget');
		}

		/**
		 * Run the loader to execute all of the hooks with WordPress.
		 *
		 * @since    1.0.0
		 */
		public function run()
		{
			$this->loader->run();
		}

		/**
		 * The name of the plugin used to uniquely identify it within the context of
		 * WordPress and to define internationalization functionality.
		 *
		 * @since     1.0.0
		 * @return    string    The name of the plugin.
		 */
		public function get_plugin_name()
		{
			return $this->plugin_name;
		}

		/**
		 * The reference to the class that orchestrates the hooks with the plugin.
		 *
		 * @since     1.0.0
		 * @return    Cbxwpbookmark_Loader    Orchestrates the hooks of the plugin.
		 */
		public function get_loader()
		{
			return $this->loader;
		}

		/**
		 * Retrieve the version number of the plugin.
		 *
		 * @since     1.0.0
		 * @return    string    The version number of the plugin.
		 */
		public function get_version()
		{
			return $this->version;
		}

	}
