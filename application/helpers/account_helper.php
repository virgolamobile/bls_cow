<?php

/**
 * Check signin
 * 
 * @return bool
 */
function is_signin()
{
	$CI =& get_instance();
	
	return $CI->account_library->is_signin();
}

/**
 * Return account parameter 
 * 
 * @param bool print the value or return it
 */
function account($param,$print=false)
{
	$CI =& get_instance();

	if(is_signin() && isset($CI->account_library->$param))
		$value = $CI->account_library->$param; 
	else $value = '';
	
	if($print) echo $value;
	else return $value;
}
