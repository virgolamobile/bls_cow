<a href="<?php echo base_url('admin/filter'); ?>">&laquo; Back</a>

<form action="<?php echo base_url('admin/filter/options_save/' . $id); ?>" method="post">
	<h2>options</h2>
	<table cellpadding="2" cellspacing="0" border="1">
		<tr>
			<th>id</th>
			<td>&nbsp;</td>
			<?php foreach($langs as $lang) : ?>
				<th><?php echo $lang; ?></th>
			<?php endforeach; ?>
		</tr>
		<?php foreach($options as $option_id => $data) : ?>
				<tr>
					<td>
						<?php echo $option_id; ?>
					</td>
					<td><a href="<?php echo base_url('admin/filter/options_delete/' . $id . '/' . $option_id); ?>">X</a></td>
					<?php foreach($langs as $lang_id) : ?>
						<td>
							<input type="text" name="update[<?php echo $lang_id; ?>][<?php echo $option_id; ?>]" value="<?php echo $data[$lang_id]; ?>" />
						</td>
					<?php endforeach; ?>
				</tr>
		<?php endforeach; ?>
		
		<tr>
			<td colspan="2">
				new
			</td>
			<?php foreach($langs as $lang_id) : ?>
				<td>
					<input type="text" name="insert[<?php echo $lang_id; ?>][]" value="" />
				</td>
			<?php endforeach; ?>
		</tr>

	</table>
	
	<input type="submit" value="salva" />
	
</form>
