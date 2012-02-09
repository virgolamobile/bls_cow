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
	 * @param int id filtro
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
	 * Aggiorna il type
	 * 
	 * @param int type id
	 * @param array update
	 */
	public function type_save_update($type,$_update)
	{
		// normalizzo l'array per svuotare righe vuote, ovvero dove label non sia indicato in nessuna lingua
		$data = $this->options_normalize_post($_update);

		foreach($data as $id => $row)
		{
			// per ognuno, il rispettivo lang lo gestisco prima e poi lo unsetto
			foreach($row['lang'] as $lang => $label)
			{
				// seleziono. Esiste? Update. Non esiste? Insert. Pessimo approccio, ma per ora veloci così. Da sistemare.
				$this->db->where('filter',$id);
				$this->db->where('lang',$lang);
				$query = $this->db->get('filter_lang');
				$result = $query->row();
				
				// cosa popolo?
				$lang_data = array(
					'filter'	=> $id,
					'lang'		=> $lang,
					'label'		=> $label
				);
				
				if(empty($result))
				{
					$this->db->insert('filter_lang',$lang_data);
				}
				else
				{
					$this->db->where('filter',$id);
					$this->db->where('lang',$lang);
					$this->db->update('filter_lang',array('label'=>$label));
				}
				
			}

			// elimino lang e passo avanti
			unset($row['lang']);

			// così tutto il resto può essere inserito in un colpo.
			$this->db->where('id',$id);
			$this->db->update('filter',$row);
		}

	}

	/**
	 * Inserisce il type
	 * 
	 * @param int type id
	 * @param array update
	 */
	public function type_save_insert($type,$_insert)
	{
		// normalizzo l'array per svuotare righe vuote, ovvero dove label non sia indicato in nessuna lingua
		$data = $this->options_normalize_post($_insert);

		foreach($data as $row)
		{
			// TODO validazione: intanto se name è vuoto esco.
			if(empty($row['name'])) continue;
			
			// taglio lang e lo gestisco dopo
			$_langs = $row['lang'];
			unset($row['lang']);
			
			// setto il type
			$row['type'] = $type;
			
			$this->db->insert('filter',$row);
			$id = $this->db->insert_id();
			
			// carico le lingue
			foreach($_langs as $lang => $label)
			{
				// inserisco solo quelli non vuoti
				if(!empty($label))
				{
					$this->db->insert('filter_lang',array('filter' => $id, 'lang' => $lang, 'label' => $label));
				}
			}
		}
	}
	
	/**
	 * Ottengo un pacchetto opzioni, organizzato per lingua.
	 * Se manca la lingua chiedo tutte le lingue
	 * 
	 * @param filter id
	 * @param lang
	 * @return array organizzati per codice lingua
	 *
	 */
	public function get_options($filter,$lang = '')
	{
		// ottengo le opzioni di un filtro
		$this->db->where('filter',$filter);
		if(is_string($lang) && $lang != '')
		{
			$where = " AND lang = '" . $lang . "'";	
		}
		elseif(is_array($lang) && !empty($lang))
		{
			$where = " AND lang IN '" . explode(',',$lang) . "'";
		}
		else
		{
			$where = '';
		}

		$this->db->join('filter_option_lang','filter_option.id = filter_option_lang.filter_option' . $where,'left');
		$this->db->order_by('order','asc');
		$query = $this->db->get('filter_option');
		$_options = $query->result_array();
		
		unset($lang);
		
		// creo l'array
		$options = array();
		foreach($_options as $option)
		{
			$lang = $option['lang'];
			$id = $option['id'];
			$label = $option['label'];
			$options[$id][$lang] = $label;
		}
		
		// uso le lingue per determinare la normalizzazione
		$available_langs = $this->filter_library->get_available_langs();
		
		// lo normalizzo inserendo campi vuoti per le lingue senza traduzioni
		foreach($options as $id => $data)
		{
			foreach($available_langs as $lang)
			{
				if(!isset($options[$id][$lang])) $options[$id][$lang] = '';
				ksort($options[$id]);
			}
		}

		return $options;
	}

	/**
	 * Cancella un'opzione
	 * 
	 * @param int option id
	 */
	public function option_delete($option_id)
	{
		// rimuovo dalla tabella
		$this->db->delete('filter_option',array('id'=>$option_id));
		
		// rimuovo le lingue
		$this->db->delete('filter_option_lang',array('filter_option'=>$option_id));
	}
	
	/**
	 * Salvataggio options update
	 * 
	 * @param int filter id
	 * @param array update
	 */
	public function options_save_update($id,$_update)
	{
		// normalizzo l'array per svuotare righe vuote, ovvero dove label non sia indicato in nessuna lingua
		$data = $this->options_normalize_post($_update);
		
		foreach($data as $key => $el)
		{
			foreach($el as $lang => $label)
			{
				$update = array(
					'label'				=> $label
				);
				$where = array(
					'filter_option'		=> $key,
					'lang'				=> $lang

				);
				
				$this->db->where($where);
				$query = $this->db->get('filter_option_lang'); // verifico se esiste
				$row = $query->row_array();
				
				// se esisteva update, altrimenti insert
				if(!empty($row))
				{
					$this->db->set($update);
					$this->db->where($where);
					$this->db->update('filter_option_lang');	
				}
				else
				{
					$this->db->insert('filter_option_lang',array_merge($update,$where));	
				}
			}	
		}

	}
	
	/**
	 * Salvataggio options insert
	 * 
	 * @param int filter id
	 * @param array insert
	 */
	public function options_save_insert($id,$_insert)
	{
		// normalizzo l'array per svuotare righe vuote, ovvero dove label non sia indicato in nessuna lingua
		$data = $this->options_normalize_post($_insert);
		
		foreach($data as $key => $el)
		{
			// inserisco il nuovo filter option e ottengo l'id
			$this->db->insert('filter_option',array('filter'=>$id,'order'=>0));
			$filter_option[$key] = $this->db->insert_id();
			
			foreach($el as $lang => $label)
			{
				$insert_batch[] = array(
					'filter_option'		=> $filter_option[$key],
					'lang'				=> $lang,
					'label'				=> $label
				);	
			}	
		}

		if(isset($insert_batch) && !empty($insert_batch)) $this->db->insert_batch('filter_option_lang',$insert_batch);
	}
	
	/**
	 * Normalizzo l'array insert, update di options
	 * 
	 * @param array
	 */
	public function options_normalize_post($array)
	{
		$data = array();

		// normalizzo l'array per svuotare righe vuote, ovvero dove label non sia indicato in nessuna lingua
		foreach($array as $lang => $el)
		{
			foreach($el as $key => $label)
			{
				// se label non è vuoto non popolo. Se è vuoto in tutte le lingue non popolo affatto.
				if(!empty($label)) $data[$key][$lang] = $label;
			}
		}

		return $data;
	}
	
	/**
	 * Ottengo la struttura di tutti i filtri di un type, oppure per uno, organizzati per type
	 * 
	 * @param string type. 0 default ovvero tutti.
	 * @param bool nocache. Se attivo salto cache.
	 * @return array filters by type
	 */
	public function get_filters($type = 0, $nocache = FALSE)
	{
		// setto il type
		$this->set_type($type);

		// ottengo filtri e opzioni (nocache)
		$raw_filters = $this->get_raw_filters($this->type,$nocache);
		$raw_options = $this->get_raw_options(0,$nocache);

		// Normalizzo quanto ottenuto, ovvero ottengo una struttura dati coerente che possa essere utilizzata nel sistema.
		// In questa fase unisco filtri e opzioni.
		// ottengo tutti i filtri (di un type o tutti) con cache
		if ($nocache || !$filters = $this->cache->get('filters-'.$this->type.'-'.LANG))
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
	 * Ottengo i pacchetti lingua per uno o più filtri
	 * 
	 * @param int filter id
	 */
	public function get_filter_lang($id)
	{
		$this->db->select('filter.*,filter_lang.label as label, filter_lang.lang as lang');
		$this->db->join('filter','filter.id = filter_lang.filter','right');
		$query = $this->db->get('filter_lang');
		$result = $query->result_array();

		// normalizzo usando id e lingua
		foreach($result as $key => $filter)
		{
			$lang = $filter['lang'];
			$id = $filter['id'];
			$label = $filter['label'];

			$filters[$id][$lang] = $label;
		}

		// uso le lingue per determinare la normalizzazione
		$available_langs = $this->filter_library->get_available_langs();
		
		// lo normalizzo inserendo campi vuoti per le lingue senza traduzioni
		foreach($filters as $id => $data)
		{
			foreach($available_langs as $lang)
			{
				if(!isset($filters[$id][$lang])) $filters[$id][$lang] = '';
				ksort($filters[$id]);
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
	private function get_raw_filters($type, $nocache = FALSE)
	{
		// ottengo tutti i filtri (di un type o tutti) con cache
		if ($nocache || !$raw_filters = $this->cache->get('raw_filters-'.$this->type.'-'.LANG))
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
	private function get_raw_options($filter = 0, $nocache = FALSE)
	{
		$filter = (int) $filter;

		// ottengo tutti i filtri (di un type o tutti) con cache
		if ($nocache ||  !$raw_options = $this->cache->get('raw_options-'.$filter.'-'.LANG))
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
		
		// init
		$filters = array();
		
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