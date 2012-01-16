<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Start extends CI_Controller {
	
	/**
	 * Home page del portale
	 */
	public function index()
	{
		$this->output->enable_profiler(TRUE);
		
		// css
		$this->template_library->css_add('reset',0);
		$this->template_library->less_add('style',9);
		
		// js
		$this->template_library->js_add('jquery/jquery-1.7.1');

		_t('front/start/main');
	}
}
