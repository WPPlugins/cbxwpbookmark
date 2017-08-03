<?php

// Prevent direct file access
	if (!defined('ABSPATH')) {
		exit;
	}

	class Cbxbookmarkedmost_Widget extends WP_Widget
	{

		/**
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
		protected $widget_slug = 'cbxwpbookmarkedmost-widget';


		/**
		 * Constructor
		 * Specifies the classname and description, instantiates the widget,
		 * loads localization files, and includes necessary stylesheets and JavaScript.
		 */
		public function __construct()
		{


			parent::__construct(
				$this->get_widget_slug(), __('Most Bookmarked Posts', "cbxwpbookmark"), array(
					'classname'   => 'cbxbmmostlisting cbxbmmostlisting-wd ' . $this->get_widget_slug() . '-class',
					'description' => __('This widget shows bookmarks from all user within specific time limit.', "cbxwpbookmark")
				)
			);

			/* 	Register admin styles and scripts
			  add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
			  add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

			  Register site styles and scripts
			  add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );
			  add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_scripts' ) );

			*/
			//Refreshing the widget's cached output with each new post
			add_action('save_post', array($this, 'flush_widget_cache'));
			add_action('deleted_post', array($this, 'flush_widget_cache'));
			add_action('switch_theme', array($this, 'flush_widget_cache'));

		}

		// end constructor

		/**
		 * Return the widget slug.
		 *
		 * @since    1.0.0
		 *
		 * @return    Plugin slug variable.
		 */
		public function get_widget_slug()
		{
			return $this->widget_slug;
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
			$cache = wp_cache_get($this->get_widget_slug(), 'widget');

			if (!is_array($cache))
				$cache = array();

			if (!isset($args['widget_id']))
				$args['widget_id'] = $this->id;

			if (isset($cache[$args['widget_id']]))
				return print $cache[$args['widget_id']];

			// go on with your widget logic, put everything into a string and …

			extract($args, EXTR_SKIP);

			$widget_string = $before_widget;

			// Title
			$title = apply_filters('widget_title', empty($instance['title']) ? esc_html__('Most Bookmarked Post', 'cbxwpbookmark') : $instance['title'], $instance, $this->id_base);

			// Defining Display Limit

			//var_dump($instance);

			$limit = !empty($instance['limit']) ? intval($instance['limit']) : 10;
			//$timezone = isset($instance['timezone']) ? esc_attr($instance['timezone']) : 'Africa/Accra';
			$daytime    = isset($instance['daytime']) ? intval($instance['daytime']) : '0'; // all in days numeric value, 0  is for all time
			$orderby    = isset($instance['orderby']) ? esc_attr($instance['orderby']) : 'object_id';
			$order      = isset($instance['order']) ? esc_attr($instance['order']) : 'desc';
			$type       = isset($instance['type']) ? esc_attr($instance['type']) : '';
			$show_count = isset($instance['show_count']) ? intval($instance['show_count']) : 1;

			$show_thumb = isset($instance['show_thumb']) ? intval($instance['show_thumb']) : 1;


			// Defining Title of Widget
			if ($title) {
				$widget_string .= $args['before_title'] . $title . $args['after_title'];
			} else {
				$widget_string .= $args['before_title'] . $args['after_title'];
			}

			wp_enqueue_style('cbxwpbookmarkpublic-css');

			ob_start();

			include(plugin_dir_path(__FILE__) . 'views/widget.php');

			$widget_string .= ob_get_clean();
			$widget_string .= $after_widget;

			$cache[$args['widget_id']] = $widget_string;

			wp_cache_set($this->get_widget_slug(), $cache, 'widget');

			print $widget_string;
		}

		// end widget

		public function flush_widget_cache()
		{
			wp_cache_delete($this->get_widget_slug(), 'widget');
		}

		/**
		 * Processes the widget's options to be saved.
		 *
		 * @param array new_instance The new instance of values to be generated via the update.
		 * @param array old_instance The previous instance of values before the update.
		 */
		public function update($new_instance, $old_instance)
		{


			$instance['title']   = esc_attr($new_instance['title']);
			$instance['daytime'] = esc_attr($new_instance['daytime']);
			$instance['orderby'] = esc_attr($new_instance['orderby']); //id, object_id, object_type
			$instance['order']   = esc_attr($new_instance['order']);
			$instance['type']    = esc_attr($new_instance['type']);  //object type: post, page, custom any post type or custom object type  ->  can be introduced in future
			//$instance['timezone'] = esc_attr($new_instance['timezone']);
			$instance['limit']      = intval($new_instance['limit']);
			$instance['show_count'] = intval($new_instance['show_count']);
			$instance['show_thumb'] = intval($new_instance['show_thumb']);

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
					'title'      => __('Most Bookmarked Post', 'cbxwpbookmark'),
					'limit'      => 10,
					//'timezone' => 'Africa/Accra',
					'daytime'    => '0',
					'orderby'    => 'object_id', //possible id, object_id, type
					'order'      => 'desc',
					'type'       => '', // possible post, page, any custom post type or object type if we introduce later, have plan
					'show_count' => 1,
					'show_thumb' => 1
				)
			);

			$title = esc_attr($instance['title']);
			$limit = intval($instance['limit']);
			//$timezone = esc_attr($instance['timezone']);
			$daytime    = esc_attr($instance['daytime']);
			$orderby    = esc_attr($instance['orderby']); //possible id, object_id, type
			$order      = esc_attr($instance['order']); //desc, asc
			$type       = esc_attr($instance['type']); //post, page, custom post types or any custom object type
			$show_count = intval($instance['show_count']);
			$show_thumb = intval($instance['show_thumb']);

			// Display the admin form
			include(plugin_dir_path(__FILE__) . 'views/admin.php');
		}// end form

		public function getThumb($id, $size = 'thumbnail', $attr = array())
		{
			if (has_post_thumbnail($id)) {
				$image = get_the_post_thumbnail($id, $size, $attr);
			} elseif (($parent_id = wp_get_post_parent_id($id)) && has_post_thumbnail($parent_id)) {
				$image = get_the_post_thumbnail($parent_id, $size, $attr);
			}

			return $image;
		}


	}
	// end class


