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
		
		$html_filters = $this->search_library->compile_filters(2);

		$this->load->view('front/item/test',array('filters'=>$html_filters));
	}
}