<?php

/**
 * Base URL
 * 
 * Create a local URL based on your basepath.
 * Segments can be passed in as a string or an array, same as site_url
 * or a URL to a file can be passed in, e.g. to an image file.
 *
 * @access	public
 * @param string
 * @return	string
 */
if ( ! function_exists('assets_url'))
{
	function assets_url($uri = '')
	{
		$CI =& get_instance();
		return $CI->config->slash_item('assets_url');
	}
}
