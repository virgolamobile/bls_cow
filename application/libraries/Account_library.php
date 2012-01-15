<?php

class Account_library {
	
	var $id = 0;
	var $email = '';
	var $status = 0;
	var $activation = '';
	
	public function __construct()
	{
		$CI =& get_instance();
	}
	
	/**
	 * Initialize account
	 * 
	 * Qui determino tutti i valori presenti all'interno dell'oggetto utente e della sessione.
	 */
	private function init($data=array())
	{
		$CI =& get_instance();

		// default data e 
		$default = get_class_vars(get_class());
		$session = (object) $CI->session->userdata('account');

		// se non ho sessione
		if(!isset($session->id) || 0 < $session->id) $session = get_class_vars(get_class());

		// se vengono passati valori...
		foreach($data as $key => $value)
		{
			// ...sovrascrivono i parametri dell'oggetto
			$this->$key = $value;
			
			// ...e i dati di sessione
			if(isset($session->$key)) $session->$key = $value;
		}

		// salvo nella sessione
		$CI->session->set_userdata('account',$session);
	}
	
	/**
	 * Check signin
	 * 
	 * string email
	 * string password
	 */
	public function signin($email,$password)
	{
		$CI =& get_instance();
		$account_to_check = $CI->account_model->get_by_email($email);
		// check
		if(!empty($account_to_check) && hash('md5',$password.$account_to_check->salt) == $account_to_check->password)
		{
			$this->signin_ok($account_to_check);
			return true;
		}
		else
		{
			$this->signin_fail();
			return false;
		}
	}
	
	/**
	 * Conferma signin
	 * 
	 * object account
	 */
	private function signin_ok($account)
	{
		$CI =& get_instance();
		
		// rimuovo password
		unset($account->salt);
		unset($account->password);
		
		$this->init($account);
	}
	
	/**
	 * Signin che fallisce.
	 * 
	 * Redirect con messaggio?
	 * Check reiterato tentativo e blocco?
	 */
	private function signin_fail()
	{
		// todo
	}
	
	/**
	 * Check login
	 * 
	 * @access public
	 * @return bool
	 */
	public function is_signin()
	{
		$CI =& get_instance();
		
		// ottengo userdata e analizzo
		$account = $CI->session->userdata('account');
			
		if(isset($account->id) && 0 < $account->id) return true;
		else return false;
	}

	/**
	 * Signout
	 * 
	 * @access public
	 */
	public function signout()
	{
		$CI =& get_instance();
		
		// distruggo tutto
		$CI->session->unset_userdata('account');
		$this->init();
	}
	
	/**
	 * Check activation
	 * 
	 * string activation
	 */
	public function activation($id,$activation)
	{
		$CI =& get_instance();
		$account_to_check = $CI->account_model->get_by_id($id);
		// check
		if(!empty($account_to_check) && $activation == $account_to_check->activation)
		{
			$this->activation_ok($account_to_check);
			return true;
		}
		else
		{
			$this->activation_fail();
			return false;
		}
	}
	
	/**
	 * Conferma activation
	 * 
	 * object account
	 */
	private function activation_ok($account)
	{
		$CI =& get_instance();
		
		// rimuovo password
		unset($account->salt);
		unset($account->password);
		
		$CI->account_model->activate($account->id);
		
		$account->status = 1;
		$this->init($account);
	}
	
	/**
	 * Activation che fallisce.
	 * 
	 * Redirect con messaggio?
	 * Check reiterato tentativo e blocco?
	 */
	private function activation_fail()
	{
		// todo
	}
	
	
}
