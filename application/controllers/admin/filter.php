<?php

class Filter extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	
		$this->load->library('parser');
		$this->load->library('filter_library');
	}

	/**
	 * Gestione filtri
	 *
	 *
	 */
	public function index()
	{
		$this->output->enable_profiler(TRUE);

		// ottengo filtri e types
		$_types = $this->filter_library->get_types();
		$_filters = $this->filter_library->get_filters();

		// giro tutti i types e appendo caratteristiche del type e del filtro
		foreach($_types as $type_id => $type)
		{
			// type
			$data[$type_id]['type_data']['name'] = $type;
			$data[$type_id]['filters_data'] = array();

			// filters, se ci sono per questo type
			if(isset($_filters[$type_id]))
			foreach($_filters[$type_id] as $filter_name => $filter)
			{
				$data[$type_id]['filters_data'][$filter_name] = $filter;
			}
		}

		$this->load->view('admin/filters/main',array('types' => $data));
	}

	/**
	 * Visualizza un filtro
	 *
	 *
	 */
	public function details($id)
	{
		$this->output->enable_profiler(TRUE); 
		
		$data = $this->filter_library->get_filter($id);
		
		$this->load->view('admin/filters/details',$data);
	}
	
	

}