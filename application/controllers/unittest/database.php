<?php

class Database extends CI_Controller {
	
	// insert restituisce un affected corretto
	// update restituisce 0 affected anche quando l'update è corretto
	// insert se lavora su chiavi esistenti restituisce errore grave, al momento non intendo gestirlo con eccezioni.
	public function insert_and_affected()
	{
		$this->db->where(array('key'=>'2','value'=>'lol'));
		$this->db->update('test',array('key'=>'2','value'=>'lol'));
		
		echo $this->db->affected_rows();
	}
	
}
