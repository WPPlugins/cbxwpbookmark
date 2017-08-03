<?php

	/**
	 * Fired during plugin activation
	 *
	 * @link       codeboxr.com
	 * @since      1.0.0
	 *
	 * @package    Cbxwpbookmark
	 * @subpackage Cbxwpbookmark/includes
	 */

	/**
	 * Fired during plugin activation.
	 *
	 * This class defines all code necessary to run during the plugin's activation.
	 *
	 * @since      1.0.0
	 * @package    Cbxwpbookmark
	 * @subpackage Cbxwpbookmark/includes
	 * @author     CBX Team  <info@codeboxr.com>
	 */
	class Cbxwpbookmark_Activator
	{

		/**
		 * Short Description. (use period)
		 *
		 * Long Description.
		 *
		 * @since    1.0.0
		 */
		public static function activate()
		{

			global $wpdb;
			$charset_collate = $wpdb->get_charset_collate();
			// charset_collate Defination

			$bookmark = $wpdb->prefix . 'cbxwpbookmark';
			$cattable = $wpdb->prefix . 'cbxwpbookmarkcat';


			// Table Names Defined

			$sql = "CREATE TABLE $bookmark (
          `id` mediumint(9) NOT NULL AUTO_INCREMENT,
          `object_id` int(11) NOT NULL,
          `object_type` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'post',
          `cat_id` int(11) NOT NULL,
          `user_id` int(11) NOT NULL,
          `created_date`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `modyfied_date` TIMESTAMP NOT NULL ,
          PRIMARY KEY (`id`)) $charset_collate;";

			//  cbx_bookmark Table Created


			$sql .= "CREATE TABLE $cattable (
          `id` mediumint(9) NOT NULL AUTO_INCREMENT,
           `cat_name` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
           `user_id` bigint(20) unsigned NOT NULL,
           `privacy` tinyint(2) NOT NULL DEFAULT '1',
           `created_date`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
           `modyfied_date` TIMESTAMP NOT NULL ,
           PRIMARY KEY (`id`))  $charset_collate;";

			//  category Table Created

			require_once(ABSPATH . "wp-admin/includes/upgrade.php");

			ob_start();
			dbDelta($sql);
			ob_clean();


		}

		/**
		 * Create pages that the plugin relies on, storing page id's in variables.
		 */
		public static function cbxbookmark_create_pages()
		{

			$pages = apply_filters('cbxwpbookmark_create_pages', array(
				'mybookmark' => array(
					'name'    => _x('cbxbookmark', 'Page slug', 'cbxwpbookmark'),
					'title'   => _x('My Bookmarks', 'Page title', 'cbxwpbookmark'),
					'content' => '[cbxwpbookmark]' . '[cbxwpbookmark-mycat]'
				)
			));

			foreach ($pages as $key => $page) {
				Cbxwpbookmark_Activator::cbxbookmark_create_page(esc_sql($page['name']), $key . '_page_id', $page['title'], $page['content'], '');
			}
		}

		/**
		 * Create a page and store the ID in an option.
		 *
		 * @param mixed $slug Slug for the new page
		 * @param string $option Option name to store the page's ID
		 * @param string $page_title (default: '') Title for the new page
		 * @param string $page_content (default: '') Content for the new page
		 * @param int $post_parent (default: 0) Parent for the new page
		 * @return int page ID
		 */
		public static function cbxbookmark_create_page($slug, $optionname = '', $page_title = '', $page_content = '', $post_parent = 0)
		{
			global $wpdb;

			$cbxwpbookmark_basics = get_option('cbxwpbookmark_basics');
			$option_value         = isset($cbxwpbookmark_basics['mybookmark_pageid']) ? intval($cbxwpbookmark_basics['mybookmark_pageid']) : 0;

			//if valid page id already exists
			if ($option_value > 0) {
				$page_object = get_post($option_value);

				if ('page' === $page_object->post_type && !in_array($page_object->post_status, array('pending', 'trash', 'future', 'auto-draft'))) {
					// Valid page is already in place
					return $page_object->ID;
				}
			}

			if (strlen($page_content) > 0) {
				// Search for an existing page with the specified page content (typically a shortcode)
				$valid_page_found = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' ) AND post_content LIKE %s LIMIT 1;", "%{$page_content}%"));
			} else {
				// Search for an existing page with the specified page slug
				$valid_page_found = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' )  AND post_name = %s LIMIT 1;", $slug));
			}

			$valid_page_found = apply_filters('cbxwpbookmark_create_page_id', $valid_page_found, $slug, $page_content);


			// Search for a matching valid trashed page
			if (strlen($page_content) > 0) {
				// Search for an existing page with the specified page content (typically a shortcode)
				$trashed_page_found = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_content LIKE %s LIMIT 1;", "%{$page_content}%"));
			} else {
				// Search for an existing page with the specified page slug
				$trashed_page_found = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_name = %s LIMIT 1;", $slug));
			}

			if ($trashed_page_found) {
				$page_id   = $trashed_page_found;
				$page_data = array(
					'ID'          => $page_id,
					'post_status' => 'publish',
				);
				wp_update_post($page_data);
			} else {
				$page_data = array(
					'post_status'    => 'publish',
					'post_type'      => 'page',
					//'post_author'    => 1,
					'post_name'      => $slug,
					'post_title'     => $page_title,
					'post_content'   => $page_content,
					'post_parent'    => $post_parent,
					'comment_status' => 'closed'
				);
				$page_id   = wp_insert_post($page_data);
			}

			//let's update the option
			$cbxwpbookmark_basics['mybookmark_pageid'] = $page_id;
			update_option('cbxwpbookmark_basics', $cbxwpbookmark_basics);

			return $page_id;
		}

	}
