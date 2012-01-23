<?php

/**
 * Stampa il percorso di assets ed il file associato
 * 
 * @param string
 */
function _a($string)
{
	$CI =& get_instance();
	
	echo $CI->config->item('assets_url') . $string;
}

/**
 * Stampa dell'html passato come parametro
 * 
 * @param html
 */
function _h($html)
{
	echo $html;
}

/**
 * Stampa un template
 * 
 * string template name
 * array data
 */
function _t($template,$data=array())
{
	$CI =& get_instance();
	echo $template;
	$CI->load->view($template,$data);
}

/**
 * Stampa un url partendo da base url
 * 
 * string path 
 */
function _u($path)
{
	echo base_url() . $path;
}

/**
 * Debug a variable
 */
function _d($var)
{
	echo '<pre class="debug">';
	var_dump($var);
	echo '</pre>';
}

/**
 * Stampa i js
 */
function _js()
{
	$CI =& get_instance();
	
	echo $CI->template_library->js_compile();
}

/**
 * Stampa i css
 */
function _css()
{
	$CI =& get_instance();
	
	echo $CI->template_library->css_compile();
}