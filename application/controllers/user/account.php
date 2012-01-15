<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller {

	/**
	 * Pagina di gestione account
	 */
	public function index()
	{
		$this->load->view('front/user/test');
	}

	/**
	 * Signin
	 * post email
	 * post password
	 */
	public function signin()
	{
		// ricevo email e password e chiedo l'autenticazione
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$signin_status = $this->account_library->signin($email,$password);

		$this->load->view('front/user/test');
	}
	
	public function signout()
	{
		$this->account_library->signout();
		
		$this->load->view('front/user/test');		
	}
	
	public function activate($id,$activation)
	{
		$activation_status = $this->account_library->activation($id,$activation);
		
		$this->load->view('front/user/test');
	}

}