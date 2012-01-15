<?php

class Item_library {

	var $id;
	var $status;
	var $activation;
	var $user;
	var $creation;
	var $last_modify;
	var $last_view;

	public function __construct()
	{
		$CI =& get_instance();
	}
	
	/**
	 * Ottiene un prodotto dall'id	 
	 *
	 * @param int
	 */
	public function get_by_id($id=0)
	{
		$item = $this->item_model->get_by_id($id);
	}
}