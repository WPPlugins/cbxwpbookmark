<!-- This file is used to markup the administration form of the widget. -->

<!-- Custom  Title Field -->
<p>
	<label for="<?php echo $this->get_field_id('title'); ?>">
		<?php _e('Title:', "cbxwpbookmark"); ?>
	</label>

	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
		   name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>"/>
</p>

<?php
	$object_types = cbxwpbookmark_object_types();
?>

<p>
	<label for="<?php echo $this->get_field_id('type'); ?>"> <?php _e('Object Type :', "cbxwpbookmark"); ?>

		<select class="widefat" id="<?php echo $this->get_field_id('type'); ?>"
				name="<?php echo $this->get_field_name('type'); ?>">

			<option value="" <?php echo ($type == "") ? 'selected="selected"' : ''; ?>>
				<?php _e("-- All --", "cbxwpbookmark") ?>
			</option>
			<?php
				if (isset($object_types['builtin']['types'])) {
					echo '<optgroup label="' . __('Built-in Post Types', 'cbxwpbookmark') . '">';
					foreach ($object_types['builtin']['types'] as $key => $name) {
						$selected = ($key == $type) ? ' selected="selected" ' : '';
						echo '<option value="' . $key . '" ' . $selected . ' >' . $name . '</option>';
					}
					echo '</optgroup>';
				}

				if (isset($object_types['custom']['types'])) {
					echo '<optgroup label="' . __('Built-in Post Types', 'cbxwpbookmark') . '">';
					foreach ($object_types['custom']['types'] as $key => $name) {
						$selected = ($key == $type) ? ' selected="selected" ' : '';
						echo '<option value="' . $key . '" ' . $selected . ' >' . $name . '</option>';
					}
					echo '</optgroup>';
				}
			?>

		</select>

	</label>

</p>
<!-- Display Limit -->

<p>
	<label for="<?php echo $this->get_field_id('limit'); ?>">
		<?php _e('Display Limit:', "cbxwpbookmark"); ?>
	</label>

	<input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>"
		   name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo $limit; ?>"/>
</p>

<p>
	<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e("Order By:", "cbxwpbookmark") ?>
		<select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>"
				name="<?php echo $this->get_field_name('orderby'); ?>">
			<option value="id" <?php echo ($orderby == "id") ? 'selected="selected"' : ''; ?>>
				<?php _e("ID", "cbxwpbookmark") ?>
			</option>
			<option value="object_type" <?php echo ($orderby == "object_type") ? 'selected="selected"' : ''; ?>>
				<?php _e("Object Type", "cbxwpbookmark") ?>
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

<!--read more link -->

<p>
	<label for="<?php echo $this->get_field_id('readmore'); ?>"><?php _e("Read More", "cbxwpbookmark") ?>

		<select class="widefat" id="<?php echo $this->get_field_id('readmore'); ?>"
				name="<?php echo $this->get_field_name('readmore'); ?>">

			<option value="1" <?php echo ($readmore == 1) ? 'selected="selected"' : ''; ?>>
				<?php _e("Yes", "cbxwpbookmark") ?>
			</option>

			<option value="0" <?php echo ($readmore == 0) ? 'selected="selected"' : ''; ?>>
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