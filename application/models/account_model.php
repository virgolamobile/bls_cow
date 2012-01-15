<?php

class Account_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
	
	/**
	 * Getter by email
	 */
	public function get_by_email($email)
	{
		$this->db->where('email',$email);
		$query = $this->db->get('user',1);

		return $query->row();
	}
	
	/**
	 * Getter by id
	 */
	public function get_by_id($id)
	{
		$this->db->where('id',$id);
		$query = $this->db->get('user',1);

		return $query->row();
	}

	/**
	 * Activate by id
	 */
	public function activate($id)
	{
		$this->db->where('id',$id);
		$query = $this->db->update('user',array('status'=>1,'activation'=>hash('md5',microtime())));
	}
	
	/**
	 * Un activate by id
	 */
	public function unactivate($id)
	{
		$this->db->where('id',$id);
		$query = $this->db->update('user',array('status'=>0,'activation'=>hash('md5',microtime())));
	}
}