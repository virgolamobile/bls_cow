<form action="" method="post">
	<h2>filter: <?php _h($name); ?></h2>
	<table cellpadding="2" cellspacing="0" border="1">
		<tr>
			<th>min</th>
			<td>
				<input type="text" name="min" value="<?php _h($min); ?>" />
			</td>
		</tr>
		<tr>
			<th>min</th>
			<td>
				<input type="text" name="max" value="<?php _h($max); ?>" />
			</td>
		</tr>
		<tr>
			<th>default</th>
			<td>
				<input type="text" name="default" value="<?php _h($default); ?>" />
			</td>
		</tr>
		<tr>
			<th>format</th>
			<td>
				<input type="text" name="format" value="<?php _h($format); ?>" />
			</td>
		</tr>
		<tr>
			<th>cast</th>
			<td>
				<input type="text" name="cast" value="<?php _h($cast); ?>" />
			</td>
		</tr>
		<tr>
			<th>scope</th>
			<td>
				<input type="text" name="scope" value="<?php _h($scope); ?>" />
			</td>
		</tr>
	</table>

	<input type="submit" />
</form>

<a href="<?php echo base_url('admin/filter/options/' . $id); ?>">Show options</a>
