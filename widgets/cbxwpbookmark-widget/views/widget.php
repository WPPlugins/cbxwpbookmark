<?php

	if (function_exists('cbxbookmarkmypost_html')):

		$readmore = isset($instance['readmore']) ? intval($instance['readmore']) : 0;

		echo '<ul class="cbxwpbookmark-mylist cbxwpbookmark-mylist-wd">';
		echo cbxbookmarkmypost_html($instance, false);

		//if readmore shown and logged in user then show his readmore link
		if ($readmore && get_current_user_id() > 0) {
			$cbxwpbookmark_basics = get_option('cbxwpbookmark_basics');
			$mybookmark_pageid    = isset($cbxwpbookmark_basics['mybookmark_pageid']) ? intval($cbxwpbookmark_basics['mybookmark_pageid']) : 0;

			echo ($mybookmark_pageid > 0) ? '<li> <a href="' . get_permalink($mybookmark_pageid) . '" class="cbxlbjs-item-widget" >' . __(' View All', 'cbxwpbookmark') . '</a></li>' : '';
		}

		echo '</ul>';

	endif;
?>