<!-- This file is used to markup the administration form of the widget. -->

<!-- Custom Title Field -->

<p>
	<label for="<?php echo $this->get_field_id('title'); ?>">
		<?php _e('Title:', 'cbxwpbookmark'); ?>
	</label>

	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
		   name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>"/>
</p>

<!-- display option -->

<!-- show count -->
<p>
	<label for="<?php echo $this->get_field_id('display'); ?>"><?php _e("Display", "cbxwpbookmark") ?>

		<select class="widefat" id="<?php echo $this->get_field_id('display'); ?>"
				name="<?php echo $this->get_field_name('display'); ?>">

			<option value="0" <?php echo ($display == "0") ? 'selected="selected"' : ''; ?>>
				<?php _e("List", "cbxwpbookmark") ?>
			</option>

			<option value="1" <?php echo ($display == "1") ? 'selected="selected"' : ''; ?>>
				<?php _e("Dropdown", "cbxwpbookmark") ?>
			</option>

		</select>

	</label>

</p>

<!-- Privacy Selection -->
<p>

	<label for="<?php echo $this->get_field_id('privacy'); ?>">
		<?php _e('Privacy:', "cbxwpbookmark"); ?>
	</label>

	<select class="widefat" id="<?php echo $this->get_field_id('privacy'); ?>"
			name="<?php echo $this->get_field_name('privacy'); ?>">
		<option value="2" <?php echo ($privacy == 2) ? 'selected="selected"' : ''; ?>>
			<?php _e("-- All --", "cbxwpbookmark") ?>
		</option>
		<option class="cbxwpbookmark-private" value="0" <?php echo ($privacy == 0) ? 'selected="selected"' : ''; ?>>
			<?php _e("Private", "cbxwpbookmark") ?>
		</option>

		<option class="cbxwpbookmark-public" value="1" <?php echo ($privacy == 1) ? 'selected="selected"' : ''; ?>>
			<?php _e("Public", "cbxwpbookmark") ?>
		</option>
	</select>

</p>
<p>
	<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e("Order By:", "cbxwpbookmark") ?>
		<select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>"
				name="<?php echo $this->get_field_name('orderby'); ?>">
			<option value="id" <?php echo ($orderby == "id") ? 'selected="selected"' : ''; ?>>
				<?php _e("ID", "cbxwpbookmark") ?>
			</option>
			<option value="cat_name" <?php echo ($orderby == "cat_name") ? 'selected="selected"' : ''; ?>>
				<?php _e("Category Title", "cbxwpbookmark") ?>
			</option>
			<option value="privacy" <?php echo ($orderby == "privacy") ? 'selected="selected"' : ''; ?>>
				<?php _e("Privacy", "cbxwpbookmark") ?>
			</option>
		</select>
	</label>

</p>
<!-- Selection of Asending or Desending -->

<p>
	<label for="<?php echo $this->get_field_id('order'); ?>"><?php _e("Order", "cbxwpbookmark") ?>

		<select class="widefat" id="<?php echo $this->get_field_id('order'); ?>"
				name="<?php echo $this->get_field_name('order'); ?>">

			<option value="asc" <?php echo ($order == "asc") ? 'selected="selected"' : ''; ?>>
				<?php _e("Asending", "cbxwpbookmark") ?>
			</option>

			<option value="desc" <?php echo ($order == "desc") ? 'selected="selected"' : ''; ?>>
				<?php _e("Desending", "cbxwpbookmark") ?>
			</option>

		</select>

	</label>

</p>

<!-- show count -->
<p>
	<label for="<?php echo $this->get_field_id('show_count'); ?>"><?php _e("Show Count", "cbxwpbookmark") ?>

		<select class="widefat" id="<?php echo $this->get_field_id('show_count'); ?>"
				name="<?php echo $this->get_field_name('show_count'); ?>">

			<option value="1" <?php echo ($show_count == "1") ? 'selected="selected"' : ''; ?>>
				<?php _e("Yes", "cbxwpbookmark") ?>
			</option>

			<option value="0" <?php echo ($show_count == "0") ? 'selected="selected"' : ''; ?>>
				<?php _e("No", "cbxwpbookmark") ?>
			</option>
		</select>
	</label>
</p>
<p>
	<label
		for="<?php echo $this->get_field_id('honorauthor'); ?>"><?php _e("In Author Archive Show for Author", "cbxwpbookmark") ?>

		<select class="widefat" id="<?php echo $this->get_field_id('honorauthor'); ?>"
				name="<?php echo $this->get_field_name('honorauthor'); ?>">

			<option value="1" <?php echo ($honorauthor == 1) ? 'selected="selected"' : ''; ?>>
				<?php _e("Yes", "cbxwpbookmark") ?>
			</option>

			<option value="0" <?php echo ($honorauthor == 0) ? 'selected="selected"' : ''; ?>>
				<?php _e("No", "cbxwpbookmark") ?>
			</option>

		</select>

	</label>

</p>
