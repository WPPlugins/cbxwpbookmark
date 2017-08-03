<?php

	/**
	 * The admin-specific functionality of the plugin.
	 *
	 * @link       codeboxr.com
	 * @since      1.0.0
	 *
	 * @package    Cbxwpbookmark
	 * @subpackage Cbxwpbookmark/admin
	 */

	/**
	 * The admin-specific functionality of the plugin.
	 *
	 * Defines the plugin name, version, and two examples hooks for how to
	 * enqueue the admin-specific stylesheet and JavaScript.
	 *
	 * @package    Cbxwpbookmark
	 * @subpackage Cbxwpbookmark/admin
	 * @author     CBX Team  <info@codeboxr.com>
	 */
	class Cbxwpbookmark_Admin
	{

		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string $plugin_name The ID of this plugin.
		 */
		private $plugin_name;

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
		 * Slug of the plugin screen.
		 *
		 * @since    1.0.0
		 *
		 * @var      string
		 */
		protected $plugin_screen_hook_suffix = null;

		/**
		 * The version of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string $version The current version of this plugin.
		 */
		private $version;

		/**
		 * The settings api of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string $settings_api settings api of this plugin.
		 */
		private $settings_api;

		/**
		 * The plugin basename of the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string $plugin_basename The plugin basename of the plugin.
		 */
		protected $plugin_basename;

		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string $cbxwpbookmark The ID of this plugin.
		 */
		private $cbxwpbookmark;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 * @param      string $plugin_name The name of this plugin.
		 * @param      string $version The version of this plugin.
		 */
		public function __construct($plugin_name, $version)
		{

			$this->cbxwpbookmark = $plugin_name;
			$this->plugin_name   = $plugin_name;
			$this->version       = $version;

			$this->plugin_basename = plugin_basename(plugin_dir_path(__DIR__) . $this->cbxwpbookmark . '.php');

			$this->settings_api = new Cbxwpbookmark_Settings_API($plugin_name, $version);
		}

		public function setting_init()
		{
			//set the settings
			$this->settings_api->set_sections($this->get_settings_sections());
			$this->settings_api->set_fields($this->get_settings_fields());
			//initialize settings
			$this->settings_api->admin_init();
		}

		public function cbxwpbookmark_activation_error()
		{
			update_option('cbxwpbookmark_activation_error', ob_get_contents());
		}

		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles()
		{

			wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/chosen.min.css', array(), $this->version, 'all');
			wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/cbxwpbookmark-admin.css', array(), $this->version, 'all');
		}

		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts()
		{

			wp_register_script('cbxbookmarkchoosen', plugin_dir_url(__FILE__) . 'js/chosen.jquery.min.js', array('jquery'), $this->version, false);
			wp_enqueue_script('cbxbookmarkchoosen');

			wp_register_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/cbxwpbookmark-admin.js', array('jquery'), $this->version, false);
			wp_enqueue_script($this->plugin_name);
		}

		/**
		 * To access all loader class property
		 *
		 * @param Loader class object $loader
		 *
		 * @since 1.0.0
		 */
		public function set_loader($loader)
		{
			$this->loader = $loader;
		}

		/**
		 * Run all administrator program from here
		 *
		 * @since   1.0.0
		 */
		public function run()
		{
			//$this->loader->add_filter('plugin_action_links_' . $this->plugin_basename, $this, 'add_plugin_admin_page');
			//Add admin menu action hook
			$this->loader->add_action('admin_menu', $this, 'add_plugin_admin_menu');
			//Add ajax request action handler for add category
		}

		/**
		 * Tab Defination
		 *
		 * @return array
		 */
		public function get_settings_sections()
		{
			$sections = array(
				array(
					'id'    => 'cbxwpbookmark_basics',
					'title' => __('Basic Settings', 'cbxwpbookmark')
				)
			);

			return $sections;
		}

		public function post_types()
		{
			$post_type_args = array(
				'builtin' => array(
					'options' => array(
						'public'   => true,
						'_builtin' => true,
						'show_ui'  => true,
					),
					'label'   => __('Built in post types', 'cbxwpbookmark'),
				)

			);

			$post_type_args = apply_filters('cbxwpbookmark_post_types', $post_type_args);

			$output    = 'objects'; // names or objects, note names is the default
			$operator  = 'and'; // 'and' or 'or'
			$postTypes = array();

			foreach ($post_type_args as $postArgType => $postArgTypeArr) {
				$types = get_post_types($postArgTypeArr['options'], $output, $operator);

				if (!empty($types)) {
					foreach ($types as $type) {
						$postTypes[$postArgType]['label']              = $postArgTypeArr['label'];
						$postTypes[$postArgType]['types'][$type->name] = $type->labels->name;
					}
				}
			}

			return $postTypes;
		}

		/**
		 * Return the key value pair of posttypes
		 *
		 * @param $all_post_types array
		 */

		public function get_formatted_posttype_multicheckbox($all_post_types)
		{

			$posts_defination = array();


			foreach ($all_post_types as $key => $post_type_defination) {
				foreach ($post_type_defination as $post_type_type => $data) {
					if ($post_type_type == 'label') {
						$opt_grouplabel = __($data, 'cbxwpbookmark');
					}

					if ($post_type_type == 'types') {
						foreach ($data as $opt_key => $opt_val) {
							$posts_defination[$opt_grouplabel][$opt_key] = __($opt_val, 'cbxwpbookmark');
						}
					}
				}
			}

			return $posts_defination;
		}

		/**
		 * Returns all the settings fields
		 *
		 * @return array settings fields
		 */
		public function get_settings_fields()
		{
			//$all_post_types   = $this->post_types();
			$posts_defination = $this->get_formatted_posttype_multicheckbox($this->post_types());


			$pages         = get_pages();
			$pages_options = array();
			if ($pages) {
				foreach ($pages as $page) {
					$pages_options[$page->ID] = $page->post_title;
				}
			}

			$mybookmark_pageid      = '';
			$mybookmark_pageid_link = '#';
			$cbxwpbookmark_basics   = get_option('cbxwpbookmark_basics');
			if ($cbxwpbookmark_basics !== false && isset($cbxwpbookmark_basics['mybookmark_pageid']) && intval($cbxwpbookmark_basics['mybookmark_pageid']) > 0) {
				$mybookmark_pageid_link = get_permalink($cbxwpbookmark_basics['mybookmark_pageid']);
			}


			$settings_fields = array(
				'cbxwpbookmark_basics' => array(
					array(
						'name'    => 'cbxbookmarkpostion',
						'label'   => __('Position', 'cbxwpbookmark'),
						'desc'    => __('Adding position', 'cbxwpbookmark'),
						'type'    => 'select',
						'default' => 'after_content',
						'options' => array(
							'before_content' => 'Before Content',
							'after_content'  => 'After Content',
							'disable'        => 'Disable Auto Integration',
						)
					),
					
					array(
						'name'    => 'cbxbookmarkposttypes',
						'label'   => __('Post Type Selection', 'cbxwpbookmark'),
						'desc'    => __('Post Type Selection', 'cbxwpbookmark'),
						'type'    => 'multiselect',
						'default' => array('post', 'page'),
						'options' => $posts_defination
					),
					array(
						'name'    => 'mybookmark_pageid',
						'label'   => __('My Bookmark Page', 'cbxwpbookmark'),
						'desc'    => sprintf(__('User\'s private bookmark page. <a href="%s" target="_blank">Visit</a>', 'cbxwpbookmark'), $mybookmark_pageid_link),
						'type'    => 'select',
						'default' => 0,
						'options' => $pages_options
					)

				)
			);

			return $settings_fields;
		}

		/**
		 * Register the administration menu for this plugin into the WordPress Dashboard menu.
		 *
		 * @since    1.0.0
		 */
		public function add_plugin_admin_menu()
		{
			//overview
			$this->plugin_screen_hook_suffix = add_options_page('CBX WP Bookmark', 'CBX WP Bookmark', 'manage_options', 'cbxwpbookmark_settings', array($this, 'display_plugin_admin_settings'));
		}

		/**
		 * Admin page for settings of this plugin
		 *
		 * @since    1.0.0
		 */
		public function display_plugin_admin_settings()
		{
			global $wpdb;

			$plugin_data = get_plugin_data(plugin_dir_path(__DIR__) . '/../' . $this->plugin_basename);

			include('partials/cbxwpbookmark-admin-display.php');
		}

	}
