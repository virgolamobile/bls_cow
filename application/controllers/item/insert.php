<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Insert extends CI_Controller {

	/**
	 * Pagina di gestione dei prodotti. Quanti step?
	 */
	public function index()
	{
		$this->load->view('front');
	}
}