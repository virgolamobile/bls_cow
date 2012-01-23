<html>
	<head>
		<?php _t('front/standard/headinclude'); ?>
	</head>
	<body>

		<form method="post" action="<?php _u('item/search'); ?>">
			<?php _h($filters); ?>
			<input type="submit" />
		</form>

		<?php _t('front/standard/footinclude'); ?>
	</body>
</html>
