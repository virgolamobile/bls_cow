<?php

class Filter_library {
	
	var $filters = array(
		'item' => 0,			// id dell'elemento
		'type' => false		// categoria in cui cercare l'elemento
	);

	function __construct()
	{
		$CI =& get_instance();

		$CI->load->model('filter_model');
	}
	
	/**
	 * Restituisce l'elenco delle lingue disponibli
	 * // TODO hard code due lingue, it e en
	 */
	public function get_available_langs()
	{
		return array('it','en');
	}
	
	/**
	 * Ottengo i types di filtri.
	 * 
	 * @return array
	 */
	public function get_types()
	{
		$CI =& get_instance();
		
		return $CI->filter_model->get_types();
	}
	
	/**
	 * Ottengo un singolo filtro
	 *
	 * @param int id filtro 
	 * @return array
	 */
	public function get_filter($id = 0)
	{
		$CI =& get_instance();
		
		return $CI->filter_model->get_filter($id);
	}

	/**
	 * Ottengo i filtri.
	 * 
	 * @param int id type
	 * @return array
	 */
	public function get_filters($type = 0)
	{
		$CI =& get_instance();
		
		return $CI->filter_model->get_filters($type);
	}
	
	/**
	 * Ottengo i pacchetti lingua per un filtro o per più filtri, divisi per label e lang
	 * 
	 * @param int filter id
	 */
	public function get_filter_lang($id)
	{
		$CI =& get_instance();
		
		return $CI->filter_model->get_filter_lang($id);		
	}
	
	/**
	 * Ottengo le options, di tutte le lingue
	 * 
	 * @param int filter id 
	 * @return array
	 */
	public function get_options($filter = 0)
	{
		$CI =& get_instance();
		
		return $CI->filter_model->get_options($filter);
	}
	
	/**
	 * Salva un type
	 * 
	 * @param int id type
	 * @param post
	 */
	public function type_save($type)
	{
		$CI =& get_instance();
		
		// insert
		$_insert = $CI->input->post('insert');
		$CI->filter_model->type_save_insert($type,$_insert);
		
		// update
		$_update = $CI->input->post('update');
		$CI->filter_model->type_save_update($type,$_update);
	}
	
	/**
	 * Salva un pacchetto opzioni
	 * 
	 * @param int filtro
	 * @param post opzioni insert e update
	 */
	public function options_save($id)
	{
		$CI =& get_instance();
		
		// insert
		$_insert = $CI->input->post('insert');
		$CI->filter_model->options_save_insert($id,$_insert);
		
		// update
		$_update = $CI->input->post('update');
		$CI->filter_model->options_save_update($id,$_update);
	}
	
	/**
	 * Cancella una singola opzione
	 * 
	 * @param int id opzione da rimuovere
	 */
	public function option_delete($option_id)
	{
		$CI =& get_instance();

		$CI->filter_model->option_delete($option_id);
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