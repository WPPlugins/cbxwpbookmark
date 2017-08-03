<?php

	/**
	 * Provide a admin area view for the plugin
	 *
	 * This file is used to markup the admin-facing aspects of the plugin.
	 *
	 * @link       codeboxr.com
	 * @since      1.0.0
	 *
	 * @package    Cbxwpbookmark
	 * @subpackage Cbxwpbookmark/admin/partials
	 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
	<div id="icon-options-general" class="icon32"></div>
	<h2><?php _e('CBX Wp Bookmark Setting', $this->cbxwpbookmark); ?></h2>

	<div id="poststuff">

		<div id="post-body" class="metabox-holder columns-2">

			<!-- main content -->
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<div class="postbox">
						<!--<h3><span><?php _e('Settings', $this->cbxwpbookmark); ?></span></h3>-->
						<div class="inside">
							<?php
								$this->settings_api->show_navigation();
								$this->settings_api->show_forms();
							?>
						</div> <!-- .inside -->
					</div> <!-- .postbox -->
				</div> <!-- .meta-box-sortables .ui-sortable -->
			</div> <!-- post-body-content -->
			<?php
				include('sidebar.php');
			?>


		</div> <!-- #post-body .metabox-holder .columns-2 -->

		<br class="clear">
	</div> <!-- #poststuff -->

</div> <!-- .wrap -->


<script type="text/javascript">

	jQuery(document).ready(function ($) {
		//if need any js code here
		//$('.chosen-select').chosen();
		$('.chosen-select').chosen({});

	});

</script>