<?php

class Search_library {
	
	var $filters = array(
		'item' => 0,			// id dell'elemento
		'type' => false		// categoria in cui cercare l'elemento
	);
	
	function __construct()
	{
		$CI =& get_instance();

		$CI->load->model('filter_model');

		// setto i filtri in automatico con priorità POST, URI-TO-ASS, GET, quindi saranno letti al contrario. Possono essere sovrascritti in fasi successive.
		// esempio
		//
		// in assenza di post
		// ../item/search/item/3/?item=2
		// restituisce item=3
		//
		// in presenza di post (item=1)
		// ../item/search/item/3/?item=2
		// restituisce item=1
		
		$this->set_filter($CI->input->get());
		$this->set_filter($CI->uri->uri_to_assoc(3));	// è stata settata una rotta item/search in routes.php
		$this->set_filter($CI->input->post());
		
		// lancia la ricerca
		$this->run();
	}

	/**
	 * Lancia la ricerca sulla base dei filtri correnti
	 * e carica i risultati nello stack dei risultati
	 */
	public function run()
	{
		$CI =& get_instance();
		
		$CI->item_model->search($this->filters);	// invio alla ricerca i filtri attualmente attivi
	}
	

	/**
	 * Setta i filtri di ricerca
	 */
	public function set_filter($filters)
	{
		if(is_array($filters))
		foreach($filters as $key => $value)
		{
			$this->filters[$key] = $value;
		}
	}

	/**
	 * Ottengo i filtri.
	 * 
	 * Da definire.
	 */
	public function get_filters($type)
	{
		$CI =& get_instance();
		
		return $CI->filter_model->get_filters($type);
	}
	
	/**
	 * Compila i filtri secondo i template.
	 * 
	 * @param integer type
	 * @return html
	 * 
	 */
	public function compile_filters($type=0)
	{
		$filters = $this->prepare_filters($type);
		$compiled['filter'] = '';

		foreach($filters as $type => $_filters)
		{
			foreach($_filters as $name => $filter)
			{
				$compiled['filter'] .= $filter['view'];
			}
		}
		
		return $compiled['filter'];
	}

	/**
	 * Prepara i filtri associandoli ai giusti template.
	 */
	private function prepare_filters($type)
	{
		$CI =& get_instance();
		
		$filters_by_type = $this->get_filters($type);

		// ciclo i filtri organizzati per type, li esplodo e preparo le view per ogni filtro
		foreach($filters_by_type as $type => $filters)
		{
			foreach($filters as $name => $filter)
			{
				$filters_by_type[$type][$name]['view'] = $this->prepare_filter($type,$name);
			}
		}
		
		return $filters_by_type;
	}
	
	/**
	 * Prepara un singolo filtro
	 * 
	 * @param integer type del filtro
	 * @param string name del filtro
	 */
	private function prepare_filter($type,$name)
	{
		$CI =& get_instance();
		$CI->load->library('parser');
		
		$filters_by_type = $this->get_filters($type);

		return $CI->parser->parse('front/filters/' . $filters_by_type[$type][$name]['format'],$filters_by_type[$type][$name],TRUE);
	}
	
}