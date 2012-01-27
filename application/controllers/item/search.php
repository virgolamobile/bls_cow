<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		// avvio la ricerca sulla base di eventuali get, post. Se i dati mancano la ricerca restituirà i risultati più probabili.
		$this->load->library('search_library');
	}

	/**
	 * SERP, pagina di ricerca.
	 */
	public function index()
	{
		$this->output->enable_profiler(TRUE);
		
		// css
		$this->template_library->css_add('reset',0);
		$this->template_library->css_add('ui-lightness/jquery-ui-1.8.17.custom',5);
		$this->template_library->less_add('style',9);

		// js
		// $this->template_library->js_add('jquery/jquery-1.7.1');
		$this->template_library->js_add('jquery/jquery-ui-1.8.17.custom.min');

		$html_filters = $this->search_library->compile_filters(2);

		$this->load->view('front/item/test',array('filters'=>$html_filters));
	}
}