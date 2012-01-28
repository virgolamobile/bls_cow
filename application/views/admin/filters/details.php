<form>
	<h2><?php _h($name); ?>
	<table cellpadding="2" cellspacing="0" border="1">
		<tr>
			<th>min</th>
			<th>min</th>
			<th>default</th>
			<th>format</th>
			<th>cast</th>
			<th>scope</th>
		</tr>
		<tr>
			<td> <?php _h($min); ?></td>
			<td> <?php _h($max); ?></td>
			<td> <?php _h($default); ?></td>
			<td> <?php _h($format); ?></td>
			<td> <?php _h($cast); ?></td>
			<td> <?php _h($scope); ?></td>
		</tr>
	</table>


	<h2>Options</h2>
	<table cellpadding="2" cellspacing="0" border="1">
		<tr>
			<th>Label</th>
		</tr>
		<?php foreach($options as $option) : ?>
			<tr>
				<td><?php _h($option['label']); ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
	
	<input type="submit" />
</form>