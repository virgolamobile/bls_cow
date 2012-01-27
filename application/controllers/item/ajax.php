<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
	}

	public function autocomplete($scope,$column)
	{
		$needle = $_GET['term'];
	
		$this->db->like($column,$needle,'both');
		$query = $this->db->get($scope);
		$result = $query->result_array();
		
		$data = array();
		foreach($result as $row)
		{
			$data[] = array(
				'id'	=> $row['id'],
				'label'	=> $row['label'],
				'value'	=> $row['label']
			);
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
	}
}