<h2>Types</h2>
<table cellpadding="2" cellspacing="0" border="1">
<?php foreach($types as $id => $type) : ?>
	<tr>
		<th><?php echo $type; ?></th>
		<td><a href="<?php echo base_url('admin/filter/type/' . $id); ?>">Edit</a></td>
	</tr>
<?php endforeach; ?>
</table>