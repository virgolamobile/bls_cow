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
		$types = $this->filter_library->get_types();

		$this->load->view('admin/filters/main',array('types' => $types));
	}

	/**
	 * Visualizzo un type
	 */
	public function type($type)
	{
		$this->output->enable_profiler(TRUE);

		// ottengo filtri e types (no cache)
		$filters = $this->filter_library->get_filters($type,TRUE);
		$filter_lang = $this->filter_library->get_filter_lang($type);
		$langs = $this->filter_library->get_available_langs();
		
		// init se il type non ha filtri
		if(!isset($filters[$type])) $filters[$type] = array();

		$this->load->view('admin/filters/type',array('type' => $type, 'langs' => $langs, 'filters' => $filters[$type], 'filter_lang' => $filter_lang));
	}

	/**
	 * Salva il contenuto di un type
	 * 
	 * @param int type id
	 */
	public function type_save($type)
	{
		$this->output->enable_profiler(TRUE);

		$this->filter_library->type_save($type);

		redirect(base_url('admin/filter/type/' . $type));
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
	
	/**
	 * Visualizza le opzioni di un filtro
	 *
	 *
	 */
	public function options($id)
	{
		$this->output->enable_profiler(TRUE);
		
		$options = $this->filter_library->get_options($id);
		$langs = $this->filter_library->get_available_langs();

		$this->load->view('admin/filters/options',array('langs'=>$langs,'id'=>$id,'options'=>$options));
	}
	
	/**
	 * Salva un filtro
	 *
	 * @param get id
	 * @param post i dati delle opzioni. Per ogni lingua elenco i dati organizzati per id. Le aggiunte saranno prive di id.
	 *
	 */
	public function details_save($filter_id)
	{
		// TODO
	}

	/**
	 * Salvo le options di un determinato filter
	 */
	public function options_save($id)
	{
		$this->output->enable_profiler(TRUE);

		// tipologie di query: insert elementi nuovi e update elementi vecchi.
		// la rimozione avviene con query chiamata secca su Napoli o ajax.
		$this->filter_library->options_save($id);
		
		redirect(base_url('admin/filter/options/' . $id));
	}

	/**
	 * Cancello un'opzione
	 * 
	 * @param filter id
	 * @param option id da cancellare
	 */
	public function options_delete($id,$option_id)
	{
		$this->filter_library->option_delete($options_id);

		redirect(base_url('admin/filter/options/' . $id));
	}
}