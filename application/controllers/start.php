<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Start extends CI_Controller {
	
	/**
	 * Home page del portale
	 */
	public function index()
	{
		$this->output->enable_profiler(TRUE);

		_t('front/start/main');
	}
}
