<!-- This file is used to markup the public-facing widget. -->
<?php

	$attr = array(
		'ul_class' => 'product_list_widget',
		'li_class' => '',
	);



	if (function_exists('cbxbookmarkmost_html')):
		cbxbookmarkmost_html($instance, $attr, true);
	endif;
?>