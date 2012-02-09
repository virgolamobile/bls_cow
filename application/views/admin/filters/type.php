<a href="<?php echo base_url('admin/filter'); ?>">&laquo; Back</a>
<h2>Filters</h2>
<form method="post" action="<?php echo base_url('admin/filter/type_save/' . $type); ?>">
	<table cellpadding="2" cellspacing="0" border="1">
		<tr>
			<th>name</th>
			<th>format</th>
			<th>cast</th>
			<th>scope</th>
			<th>label</th>
			<?php foreach($langs as $lang_id) : ?>
				<th><?php echo $lang_id; ?></th>
			<?php endforeach; ?>
			<th>Opzioni</th>
		</tr>

		<?php foreach($filters as $name => $filter) : ?>
			<tr>
				<td>
					<input type="text" name="update[name][<?php echo $filter['id']; ?>]" value="<?php echo $filter['name']; ?>" />
				</td>
				<td>
					<input type="text" name="update[format][<?php echo $filter['id']; ?>]" value="<?php echo $filter['format']; ?>" />
				</td>
				<td>
					<input type="text" name="update[cast][<?php echo $filter['id']; ?>]" value="<?php echo $filter['cast']; ?>" />
				</td>
				<td>
					<input type="text" name="update[scope][<?php echo $filter['id']; ?>]" value="<?php echo $filter['scope']; ?>" />
				</td>
				<td>
					<input type="text" name="update[label][<?php echo $filter['id']; ?>]" value="<?php echo $filter['label']; ?>" />
				</td>
				<?php foreach($langs as $lang_id) : ?>
					<td>
						<input type="text" name="update[lang][<?php echo $filter['id']; ?>][<?php echo $lang_id; ?>]" value="<?php echo $filter_lang[ $filter['id'] ][$lang_id]; ?>" />
					</td>
				<?php endforeach; ?>
				<td>
					<a href="<?php echo base_url('admin/filter/options/' . $filter['id']); ?>">Edit</a>
				</td>
			</tr>
		<?php endforeach; ?>
		<tr>
			<td>
				<input type="text" name="insert[name][0]" value="" />
			</td>
			<td>
				<input type="text" name="insert[format][0]" value="" />
			</td>
			<td>
				<input type="text" name="insert[cast][0]" value="" />
			</td>
			<td>
				<input type="text" name="insert[scope][0]" value="" />
			</td>
			<td>
				<input type="text" name="insert[label][0]" value="" />
			</td>
			<?php foreach($langs as $lang_id) : ?>
				<td>
					<input type="text" name="insert[lang][0][<?php echo $lang_id; ?>]" value="" />
				</td>
			<?php endforeach; ?>
			<td>
				&nbsp;
			</td>
		</tr>
	</table>

	<input type="submit" value="save" />
</form>