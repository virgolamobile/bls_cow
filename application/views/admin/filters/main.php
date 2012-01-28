<?php foreach($types as $type) : ?>
<h2><?php _h($type['type_data']['name']); ?></h2>
<table cellpadding="2" cellspacing="0" border="1">
	
	<?php foreach($type['filters_data'] as $k => $filters) : ?>

		<tr>
			<td><a href="<?php echo base_url('admin/filter/details/' . $filters['id']); ?>">Edit</a></td>
			<td><?php _h($filters['id']); ?></td>
			<td><?php _h($filters['type']); ?></td>
			<td><?php _h($filters['name']); ?></td>
			<td><?php _h($filters['label']); ?></td>
		</tr>
		
	<?php endforeach; ?>
	
</table>
<?php endforeach; ?>