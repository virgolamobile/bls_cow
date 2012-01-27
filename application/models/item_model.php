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
					 $this->db->where($where_min_max);
				}
				
				// salto al filtro successivo
				continue;
			}

			// caso autocomplete
			if('autocomplete' == $name)
			{
				if(!empty($filter) && is_array($filter))
				foreach($filter as $filter_name => $filter_table_search)
				{
					// TODO che fare qui della ricerca geografica?
				}
			}
			
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
				$this->db->where($where); 
			}
			else
			{
				// filtro come stringa
				$where = "(filter.name = '".$name."' AND item_filter.value = '".$filter."')";
				$this->db->where($where);	
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