<?php

	/**
	 * Created by PhpStorm.
	 * User: Codeboxr  One
	 * Date: 8/3/15
	 * Time: 4:04 PM
	 */
	class Cbxwpbookmark_FrontAdmin_Unused
	{

		/**
		 * Front End Admin Category table
		 *
		 * @return string
		 */
		public static function cbxbookmark_categories($attr)
		{

			global $wpdb;
			$userid  = intval($attr['userid']);
			$order   = $attr['order'];
			$orderby = $attr['orderby'];


			$user_loggedin = get_current_user_id();


			// Category Table in Database
			$wpbookmark_cat_table = $wpdb->prefix . 'cbxwpbookmarkcat';

			//todo: need to apply shortcode params userid, order and orderby ,
			// Executing The Query
			$sql  = $wpdb->prepare("SELECT * FROM $wpbookmark_cat_table WHERE user_id=%d ORDER BY $orderby $order", $userid);
			$cats = $wpdb->get_results($sql);


			$html = '<h3>' . esc_html__('My Bookmark Categories', 'cbxwpbookmark') . '</h3>';

			$html .= '<ul class="cbxbookmark-mycat-panel">';


			foreach ($cats as $cat) {
				$action_html = '<a href="#" class="cbxbookmark-edit-btn" ></a> <a class="cbxbookmark-delete-btn" href="#" data-id="' . $cat->id . '"><span></span></a>';

				$html .= '<li class="cbxbookmark-mycat-item" data-privacy="' . $cat->privacy . '"  data-id="' . $cat->id . '" data-name="' . $cat->cat_name . '">
                             <span class="my-catname-' . $cat->id . '">' . $cat->cat_name . '</span>
                             ' . (($user_loggedin == $userid && $user_loggedin > 0) ? $action_html : '') . '

                         </li>';
			}

			if (sizeof($cats) == 0) {
				$html .= '<li class="cbxbookmark-mypost-item">' . esc_html__('No matching category found', 'cbxwpbookmark') . '</li>';
			}

			$html .= '</ul>';

			return $html;
		}


		public static function cbxbookmarkpost($attr)
		{

			global $wpdb;

			$userid  = intval($attr['userid']);
			$order   = $attr['order'];
			$orderby = $attr['orderby'];
			$limit   = intval($attr['limit']);
			$type    = $attr['type'];


			$user_loggedin = get_current_user_id();

			$limit_sql = '';
			if ($limit != 0) {
				$limit_sql = " limit $limit ";
			}

			$type_sql = '';
			if ($type != '') {
				$type_sql = $wpdb->prepare(' AND object_type=%s ', $type);
			}

			// Category Table in Database
			$wpbookmark_table = $wpdb->prefix . 'cbxwpbookmark';


			$cat_sql = '';
			if (isset($_GET['cbxbmcatid']) && $_GET['cbxbmcatid'] != null) {
				$cat_sql = $wpdb->prepare(' AND cat_id = %d ', abs($_GET['cbxbmcatid']));
			}
			$bookmarkpost = $wpdb->get_results($wpdb->prepare("SELECT  object_id, object_type FROM  $wpbookmark_table WHERE user_id=%d $cat_sql $type_sql
                                            group by object_id order by $orderby $order $limit_sql", $userid)
			);


			//$html = '<div class="postmsg"></div>'; //todo: change class name to someting more specific to this plugin
			$html = '<h3>' . __('My Bookmarked Posts', 'cbxwpbookmark') . '</h3>';
			$html .= '<ul class="cbxbookmark-mypost-panel">';

			foreach ($bookmarkpost as $bookmark) {

				$action_html = '&nbsp <a class="cbxbookmark-delete-btn cbxbookmark-post-delete" href="#" data-userid="' . $userid . '" data-id="' . $bookmark->object_id . '"><span></span></a></li>';
				$html .= '<li class="cbxbookmark-mypost-item">';
				$html .= '<span class="my-postid-' . $bookmark->object_id . '"><a href="' . get_permalink($bookmark->object_id) . '">' . get_the_title($bookmark->object_id) . '</a></span>';
				//  '<a href="#" class="cbxwpbkmarkpost button-changeto"  data-object_id="'.$bookmark->object_id.'" >'.__( 'Change Category', 'cbxwpbookmark' ).'</a>';
				$html .= (($user_loggedin == $userid && $user_loggedin > 0) ? $action_html : '');
			}
			if (sizeof($bookmarkpost) == 0) {
				$html .= '<li class="cbxbookmark-mypost-item">' . __('No matching item found', 'cbxwpbookmark') . '</li>';
			}

			$html .= '</ul>';

			return $html;
		}


	}
