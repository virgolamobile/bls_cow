<?php

class Init {

	public function __construct()
	{
		$CI =& get_instance();
		
		// attivo la cache
		$CI->load->driver('cache', array('adapter' => 'file', 'backup' => 'file')); // adapter va modificato in APC. Ora non funziona il fallback a file. // TODO: Verificare
		
		// lingua
		define('LANG','it');
	}
}
