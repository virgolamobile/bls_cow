<?php

class Template_library {
	
	var $css = array();
	var $js = array();
	
	function __construct()
	{
		$this->js_add('asd.js');
	}
	
	/**
	 * Aggiunge un css all'elenco dei css da caricare in headinclude.
	 * In autonomia filtra quelli già gestiti.
	 * 
	 * @param uri del css. Indicare tutto il percorso interno a assets/css/
	 * @param priority del css. Default 9. Saranno dunque ordinati per priorità e poi per FIFO.
	 * 
	 * Non previsto per ora la possibilità di caricare css esterni dalla cartella assets.
	 */
	public function css_add($uri,$priority = 9)
	{
		$css_path = 'css/';
		
		$hash = hash('md5',$uri);
		$url = assets_url() . $css_path . $uri;
		$priority = (int) $priority; // metti che...
		
		$this->css[$priority][$hash] = $url; 
		
		_d($url);
	}
	
	/**
	 * Aggiunge un js all'elenco dei js da caricare in headinclude.
	 * In autonomia filtra quelli già gestiti.
	 * 
	 * @param uri del js. Indicare tutto il percorso interno a assets/js/
	 * @param priority del js. Default 9. Saranno dunque ordinati per priorità e poi per FIFO.
	 * 
	 * Non previsto per ora la possibilità di caricare css esterni dalla cartella assets.
	 */
	public function js_add($uri,$priority = 9)
	{
		$js_path = 'js/';
		
		$hash = hash('md5',$uri);
		$url = assets_url() . $js_path . $uri;
		$priority = (int) $priority; // metti che...
		
		$this->js[$priority][$hash] = $url; 
		
		_d($url);
	}

	
}