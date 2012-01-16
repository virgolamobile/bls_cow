<?php

class Template_library {
	
	var $css = array();
	var $js = array();
	
	function __construct()
	{

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
	public function less_add($uri,$priority = 9)
	{
		$CI =& get_instance();

		$less_path = 'less/';

		$hash = hash('md5',$uri);
		$url = assets_url() . $less_path . $uri;
		$priority = (int) $priority; // metti che...

		// lo carico, lo elaboro lato server, lo salvo nei css e d'ora in poi lo gestisco come css
		// Con cache. Il file css generato viene salvato nel suo spazio finale e gestito come file css regolare, apparentemente senza gestione di cache.
		// In cache/ viene salvato lo stesso file. Se manca in cache (vita 7 giorni) viene ricreato anche in css/less.
		// Così se svuoto la cache rigenero anche i css.
		if ( ! $filters = $CI->cache->get('less-'.$hash))
		{
			$less_path = APPPATH . '../assets/less/' . $uri . '.less';
			$css_path = APPPATH . '../assets/css/less/' . $uri . '.css';

			// conversione less -> css
			$CI->lesscode->parseFile($less_path);
			$css_string = $CI->lesscode->output();

			// salvo il file in css/less
			$CI->load->helper('file');
			write_file($css_path,$css_string);
			// salvo in cache
			$CI->cache->save('less-'.$hash, array($hash => $css_string), '604800'); // 1 week
		}

		$this->css_add('less/' . $uri,$priority);
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
		$url = $css_path . $uri . '.css';
		$priority = (int) $priority; // metti che...
		
		$this->css[$priority][$hash] = $url;
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
		$url = $js_path . $uri . '.js';
		$priority = (int) $priority; // metti che...

		$this->js[$priority][$hash] = $url;
	}

	/**
	 * Compila i css utilizzando il booster.
	 */
	public function css_compile()
	{
		$CI = get_instance();
		
		// ordino per priorità
		ksort($this->css);
		
		foreach($this->css as $key => $priorities)
		{
			foreach($priorities as $hash => $css)
			{
				$css_ordered_by_priority[] = '../../../../' . 'assets/' . $css;
			}
		}

		$CI->booster->debug = FALSE;
		$CI->booster->css_source = $css_ordered_by_priority;
		return $CI->booster->css_markup();
	}
	
	/**
	 * Compila i js utilizzando il booster.
	 */
	public function js_compile()
	{
		$CI = get_instance();
		
		// ordino per priorità
		ksort($this->js);
		
		foreach($this->js as $key => $priorities)
		{
			foreach($priorities as $hash => $js)
			{
				$js_ordered_by_priority[] = '../../../../' . 'assets/' . $js;
			}
		}

		$CI->booster->js_source = $js_ordered_by_priority;
		return $CI->booster->js_markup();
	}
	
}