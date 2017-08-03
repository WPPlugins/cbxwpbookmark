<?php
	if (function_exists('cbxbookmarkmycat_html')):
		$display    = isset($instance['display']) ? intval($instance['display']) : 0;
		//var_dump($display);

		if($display == 0) echo '<ul class="cbxbookmark-category-list cbxbookmark-category-list-wd">';

		echo cbxbookmarkmycat_html($instance, false);

		if($display == 0) echo '</ul>';
	endif;

?>