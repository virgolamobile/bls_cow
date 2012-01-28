<?php

class Item_model extends CI_Model {
	
	var $lang = 'it';

    function __construct()
    {
        parent::__construct();
    }
	
	/**
	 * Restituisce i risultati di ricerca
	 * 
	 * @param array filters
	 * @return array results
	 * 
	 */
	public function search($filters)
	{
		_d($filters);
		
		$this->db->select('count(item.id) AS relevance, item.id, item_lang.title');
		$this->db->join('item_lang','item_lang.item = item.id','left');
		$this->db->join('item_filter','item.id = item_filter.item','left');
		$this->db->join('filter','filter.id = item_filter.filter','left');
		$this->db->join('filter_lang',"filter_lang.filter = filter.id AND filter_lang.lang = '".LANG."'",'left');
		
		foreach($filters as $name => $filter)
		{
			// caso range (min max)
			if('range' == $name)
			{
				// il filtro range è fatto da array nome-filtro => range min-max filtro. Per ognuno di questi genero il where.
				if(!empty($filter) && is_array($filter))
				foreach($filter as $filter_name => $_range_values)
				{
					$range_values = explode('-',$_range_values);
					$min = (int) trim($range_values[0]);
					$max = (int) trim($range_values[1]);
					
					 $where_min_max = "(filter.name = '".$filter_name."' AND item_filter.value >= '".$min."' AND item_filter.value <= '".$max."')";
					 $this->db->or_where($where_min_max);
				}
				
				// salto al filtro successivo
				continue;
			}

			if('autocomplete' == $name) continue;
			/*
			// caso autocomplete multiplo
			if('autocomplete' == $name)
			{
				if(!empty($filter) && is_array($filter))
				foreach($filter as $filter_name => $filter_table_search)
				{
					// TODO che fare qui della ricerca geografica?
					_d($filter_table_search);
					foreach($filter_table_search as $_tables_joins => $comma_separated_needles)
					{	
						// ottengo i needles dall'elenco comma separated, pulisco anche gli spazi
						$needles = array_map('trim',explode(",",$comma_separated_needles));
						foreach($needles as $k => $needle) if(empty($needle)) unset($needles[$k]); // elimino elementi vuoti dai needle
						
						// ottengo l'accoppiata loc e tabella di join
						$tables_joins = explode(',',$_tables_joins);
						
						// eseguo solo se ho dei needles
						if(!empty($needles))
						foreach($tables_joins as $_joins)
						{
							$joins = explode(':',$_joins);
							$table = $joins[0];
							$key = $joins[1];
							
							$this->db->join($table,$table . '.' . $key . ' = item.' . $key,'left');
							$this->db->join($key,$table . '.' . $key . ' = ' . $key . '.id','left');
							$this->db->where_in($table,$needles);
						}
					}
				}

				// salto al filtro successivo
				continue;
			}
			*/
			
			if(!empty($filter))
			if(is_array($filter))
			{
				// filtro come array
				$where = "( ";
				foreach($filter as $parameter)
				{
					$where_or[] = "(filter.name = '".$name."' AND item_filter.value = '".$parameter."')";
				}
				$where.=implode(' OR ', $where_or) . " )";
				$this->db->or_where($where); 
			}
			else
			{
				// filtro come stringa
				$where = "(filter.name = '".$name."' AND item_filter.value = '".$filter."')";
				$this->db->or_where($where);	
			}
		}

		$this->db->group_by('item.id');
		$this->db->order_by('relevance','desc');

		$query = $this->db->get('item');
		$result = $query->result_array();

		_d($result);
	}

	/**
	 * Restituisce un item
	 * 
	 * @param int
	 */
	private function get_by_id($id)
	{
		$this->db->where('id',$id);
		$this->db->join('item_lang',"item.id = item_lang.item AND item.lang = '".$this->lang."'");
		$query = $this->db->get('item',1);

		return $query->row();
	}
}