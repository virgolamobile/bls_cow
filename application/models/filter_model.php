<?php

class Filter_model extends CI_Model {
	
	protected $type = 0;
	
    function __construct()
    {
        parent::__construct();
    }
	
	/**
	 * Ottengo i types
	 *
	 * @return array
	 */
	public function get_types()
	{
		$query = $this->db->get('filter_type');
		$result = $query->result_array();
		
		foreach($result as $row)
		{
			$id = $row['id'];
			$name = $row['name'];

			$types[$id] = $name;
		}
		
		return $types;
	}
	
	/**
	 * Ottengo un singolo filtro
	 *
	 */
	public function get_filter($id)
	{
		// ottengo il filtro
		$this->db->where('id',$id);
		$query = $this->db->get('filter');
		$filter = $query->row_array();

		// aggiungo le options
		$filter['options'] = $this->get_raw_options($filter['id']);

		return $filter;
	}
	
	/**
	 * Ottengo la struttura di tutti i filtri di un type, oppure per uno, organizzati per type
	 * 
	 * @param string type. 0 default ovvero tutti.
	 * @return array filters by type
	 */
	public function get_filters($type = 0)
	{
		// setto il type
		$this->set_type($type);

		// ottengo filtri e opzioni
		$raw_filters = $this->get_raw_filters($this->type);
		$raw_options = $this->get_raw_options();

		// Normalizzo quanto ottenuto, ovvero ottengo una struttura dati coerente che possa essere utilizzata nel sistema.
		// In questa fase unisco filtri e opzioni.
		// ottengo tutti i filtri (di un type o tutti) con cache
		if ( ! $filters = $this->cache->get('filters-'.$this->type.'-'.LANG))
		{
			$filters = $this->normalize_filters($raw_filters,$raw_options);

			$this->cache->save('filters-'.$this->type.'-'.LANG, $filters, '60');
		}

		// setto i valori di default. Se ci sono filtri attivi uso quelli. Per farlo ciclo i filtri attivi e li popolo con i valori ottenuti in post, uri o get.
		if(is_array($filters))
		foreach($filters as $type => $_filters)
		{
			foreach($_filters as $name => $filter)
			{
				$value = $this->get_active_filter($name);
				if($value) $filters[$type][$name]['default'] = $value;
			}
		}

		return $filters;
	}

	/**
	 * Ottengo i filtri.
	 * Con cache.
	 * 
	 * @param int type
	 * @return array filters
	 */
	private function get_raw_filters($type)
	{
		// ottengo tutti i filtri (di un type o tutti) con cache
		if ( ! $raw_filters = $this->cache->get('raw_filters-'.$this->type.'-'.LANG))
		{
			$this->db->select('filter.*,filter_type.name as filter_type');
		
			if(0 < $this->type) $this->db->where_in('type',array(0,$this->type)); // se ho il type cerco solo i filtri che afferiscono a quel type o a 0 (filtri universali: ad esempio il prezzo).
			$this->db->join('filter_type','filter_type.id = filter.type');
			$this->db->join('filter_lang',"filter.id = filter_lang.filter AND filter_lang.lang = '".LANG."'",'left');
			$query = $this->db->get('filter');
			$raw_filters = $query->result_array();
			
			// salvo in cache. I filtri andranno salvati a long term. // TODO: in development i filtri sono settati short term.
			$this->cache->save('raw_filters-'.$this->type.'-'.LANG, $raw_filters, '60');
		}
		
		return $raw_filters;
	}

	/**
	 * Ottengo tutte le opzioni dei filtri. Forte utilizzo di cache.
	 * 
	 * @param int filter
	 * @return array options
	 * 
	 */
	private function get_raw_options($filter = 0)
	{
		$filter = (int) $filter;

		// ottengo tutti i filtri (di un type o tutti) con cache
		if ( ! $raw_options = $this->cache->get('raw_options-'.$filter.'-'.LANG))
		{
			if(0 < $filter) $this->db->where('filter',$filter);	// solo se il filter è settato come > 0
			$this->db->join('filter_option_lang','filter_option_lang.filter_option = filter_option.id');
			$this->db->where('lang',LANG);
			$this->db->order_by('order','asc');
			$query = $this->db->get('filter_option');
			$raw_options = $query->result_array();

			// salvo in cache. I filtri andranno salvati a long term. // TODO: in development i filtri sono settati short term.
			$this->cache->save('raw_options-'.$filter.'-'.LANG, $raw_options, '60');
		}

		return $raw_options;
	}

	/**
	 * Normalizzo le opzioni.
	 * 
	 * Per ogni filtro creo un sotto array id=>label
	 */
	private function normalize_options($raw_options)
	{
		foreach($raw_options as $option)
		{
			$filter = $option['filter'];
			$id = $option['id'];
			$label = $option['label'];
			
			$op['option_key'] = $id;
			$op['option_label'] = $label;

			$options[$filter][] = $op;
		}
		
		return $options;
	}
	

	/**
	 * Normalizzo i filtri.
	 * 
	 * Sarà generato un array di filtri diviso per type, dove ogni filtro sarà identificato dal suo name.
	 * All'interno saranno presenti i dati per rappresentarlo, default, eventuali options.
	 * 
	 * @param array raw filters, appena pescati da cache o db
	 * @return array filters normalizzati
	 */
	private function normalize_filters($raw_filters = array(),$raw_options = array())
	{
		$options = $this->normalize_options($raw_options);
		
		// organizzo in una struttura dati divisa per type
		foreach($raw_filters as $raw_filter)
		{
			// id del filtro
			$id = $raw_filter['id'];
			
			// rilevo il type del filtro
			$type = $raw_filter['type'];

			// rilevo il name del filtro
			$name = $raw_filter['name'];

			// rilevo e forzo label vuote. Saranno sostituite da {name}
			$raw_filter['label'] = empty($raw_filter['label']) ? '{'.$raw_filter['name'].'}' : $raw_filter['label'];
			
			// opzioni del filtro
			if(isset($options[$id])) $raw_filter['options'] = $options[$id];

			// creo un identificativo unico per ogni filtro. Runtime.
			$raw_filter['hash'] = hash('md5',$type . $name . time() . microtime());

			$filters[$type][$name] = $raw_filter;
		}

		return $filters;
	}

	/**
	 * Setto i filtri di default con gli eventuali filtri attivi.
	 */
	private function get_active_filter($name)
	{
		
		if(isset($this->search_library) && isset($this->search_library->filters[$name]))
			return $this->search_library->filters[$name];
		else
			return FALSE;
	}

	/**
	 * Setter type
	 */
	public function set_type($type = 0)
	{
		$this->type = (int) $type;
	}

}