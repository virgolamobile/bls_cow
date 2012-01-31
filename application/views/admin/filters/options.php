<form action="<?php echo base_url('admin/filter/options_save/' . $id); ?>" method="post">
	<h2>options</h2>
	<table cellpadding="2" cellspacing="0" border="1">
		<tr>
			<th>id</th>
			<?php foreach($langs as $lang) : ?>
				<th><?php echo $lang; ?></th>
			<?php endforeach; ?>
		</tr>
		<?php foreach($options as $id => $data) : ?>
				<tr>
					<td>
						<?php echo $id; ?>
					</td>
					<?php foreach($langs as $lang_id) : ?>
						<td>
							<input type="text" name="option[<?php echo $id; ?>]" value="<?php echo $data[$lang_id]; ?>" />
						</td>
					<?php endforeach; ?>
				</tr>
		<?php endforeach; ?>
	</table>
	
	<input type="submit" value="salva" />
	
</form>
