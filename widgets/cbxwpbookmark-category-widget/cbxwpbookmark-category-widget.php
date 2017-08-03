<?php

// Prevent direct file access
	if (!defined('ABSPATH')) {
		exit;
	}

	class Cbxwpbookmark_Cagetory extends WP_Widget
	{

		/**
		 *
		 *
		 * Unique identifier for your widget.
		 *
		 *
		 * The variable name is used as the text domain when internationalizing strings
		 * of text. Its value should match the Text Domain file header in the main
		 * widget file.
		 *
		 * @since    1.0.0
		 *
		 * @var      string
		 */
		protected $cbxwpbookmarkcategory_slug = 'cbxwpbookmarkcategory';
		private $plugin_name;
		private $version;

		/**
		 * Constructor
		 *
		 * Specifies the classname and description, instantiates the widget,
		 * loads localization files, and includes necessary stylesheets and JavaScript.
		 */
		public function __construct()
		{

			// load plugin text domain
			//add_action( 'init', array( $this, 'cbxwpbookmark_textdomain' ) );

			parent::__construct(
				$this->cbxwpbookmarkcategory_slug(), esc_html__('My Bookmark Categories', 'cbxwpbookmark'), array(
					'classname'   => $this->cbxwpbookmarkcategory_slug() . '-class',
					'description' => esc_html__('This widget shows bookmark categories from a logged in user.', 'cbxwpbookmark')
				)
			);


		}

		// end constructor

		/**
		 * Return the widget slug.
		 *
		 * @since    1.0.0
		 *
		 * @return    Plugin slug variable.
		 */
		public function cbxwpbookmarkcategory_slug()
		{
			return $this->cbxwpbookmarkcategory_slug;
		}

		/* -------------------------------------------------- */
		/* Widget API Functions
		  /*-------------------------------------------------- */

		/**
		 * Outputs the content of the widget.
		 *
		 * @param array args  The array of form elements
		 * @param array instance The current instance of the widget
		 */
		public function widget($args, $instance)
		{


			// Check if there is a cached output
			//$cache = wp_cache_get($this->cbxwpbookmarkcategory_slug(), 'widget');

			/*if (!is_array($cache))
				$cache = array();*/

			if (!isset($args['widget_id']))
				$args['widget_id'] = $this->id;

			/* if (isset($cache[$args['widget_id']]))
				 return print $cache[$args['widget_id']];*/

			// go on with your widget logic, put everything into a string and …

			extract($args, EXTR_SKIP);

			$widget_string = $before_widget;

			$default_title = esc_html__('My Bookmark Categories', 'cbxwpbookmark');

			$instance['honorauthor'] = isset($instance['honorauthor']) ? intval($instance['honorauthor']) : 0;

			if (is_author() && $instance['honorauthor']) {
				$curauth            = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
				$instance['userid'] = $curauth->ID;
				$default_title      = __('Author\'s Bookmark Categories', 'cbxwpbookmark');
			}

			// Getting Title
			$title = apply_filters('widget_title', empty($instance['title']) ? $default_title : $instance['title'], $instance, $this->id_base);
			// Wrapping title
			if ($title) {
				$widget_string .= $args['before_title'] . $title . $args['after_title'];
			} else {
				$widget_string .= $args['before_title'] . $args['after_title'];
			}

			wp_enqueue_style('cbxwpbookmarkpublic-css');

			// Checking if the user is logged in

			ob_start();


			include(plugin_dir_path(__FILE__) . 'views/widget.php');
			//}

			$widget_string .= ob_get_clean();
			$widget_string .= $after_widget;

			//$cache[$args['widget_id']] = $widget_string;

			//wp_cache_set($this->cbxwpbookmarkcategory_slug, $cache, 'widget');

			print $widget_string;
		}

		// end widget

		/*public function flush_widget_cache() {
			wp_cache_delete($this->cbxwpbookmarkcategory_slug, 'widget');
		}*/

		/**
		 * Processes the widget's options to be saved.
		 *
		 * @param array new_instance The new instance of values to be generated via the update.
		 * @param array old_instance The previous instance of values before the update.
		 */
		public function update($new_instance, $old_instance)
		{

			$instance            = $old_instance;
			$instance['title']   = esc_attr($new_instance['title']);
			$instance['privacy'] = intval($new_instance['privacy']);

			$instance['orderby']     = esc_attr($new_instance['orderby']);
			$instance['order']       = esc_attr($new_instance['order']);
			$instance['show_count']  = intval($new_instance['show_count']);
			$instance['display']     = intval($new_instance['display']);
			$instance['honorauthor'] = intval($new_instance['honorauthor']);


			return $instance;
		}

		// end widget

		/**
		 * Generates the administration form for the widget.
		 *
		 * @param array instance The array of keys and values for the widget.
		 */
		public function form($instance)
		{


			$instance = wp_parse_args(
				(array)$instance,
				array(
					'title'      => __('My Bookmark Category', 'cbxwpbookmark'),
					'privacy'    => 1, // all  = 2, 0 = private 1 = public
					'orderby'    => 'cat_name',
					'order'      => 'asc',
					'show_count' => 0,
					'display'    => 0,
				)
			);


			$title   = strip_tags($instance['title']);
			$privacy = intval($instance['privacy']);

			$orderby     = esc_attr($instance['orderby']);
			$order       = esc_attr($instance['order']);
			$show_count  = intval($instance['show_count']);
			$display     = intval($instance['display']);
			$honorauthor = intval($instance['honorauthor']);


			// Display the admin form
			include(plugin_dir_path(__FILE__) . 'views/admin.php');
		}

		// end form


	}


