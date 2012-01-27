<?php

class Filter extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	
		$this->load->library('parser');
		$this->load->library('filter_library');
	}

	public function index()
	{
		$this->output->enable_profiler(TRUE);

	
		$types = $this->filter_library->get_types();

		$_filters = $this->filter_library->get_filters();
		
		// ciclo per organizzare i filtri
		foreach($types as $type_id => $type_name)
		{
			$data['type']['type_id'] = $type_id;
			$data['type']['type_name'] = $type_name;
			$data['rows'] = '';
		
			// ciclo tutti i filtri del tipo corrente
			$filters = array();
			if(isset($_filters[$type_id]))
			$filters = $_filters[$type_id];
			
			foreach($filters as $filter)
			{
				$data['rows'] .= $this->load->view('admin/filters/row',$filter,true);
			}
			
			$this->load->view('admin/filters/manage',$data);
		}
	}
	
	

}