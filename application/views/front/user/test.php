<?php
	_d($this->session->userdata('account'));
?>

<?php if(!is_signin()) : ?>

<form method="post" action="<?php _u('user/account/signin'); ?>">
	email <input type="text" name="email" value="test@localhost.com" />
	<br />
	password <input type="text" name="password" value="lollonz" />
	<br />
	<input type="submit" />
</form>

<?php else : ?>

<ul>
	<li><a href="<?php _u('user/account/signout'); ?>">sign out</a></li>
	<li><a href="<?php _u('user/account/activate/'.account('id').'/'.account('activation')); ?>">activate account</a></li>
</ul>

<?php endif; ?>