<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * MY_Loader Class
 *
 *
 * @package             CodeIgniter
 * @subpackage          Libraries
 * @author              Marco Monteiro
 * @category            Loader
 * @link                www.marcomonteiro.net / www.github.com/mpmont/CI-Developers-Toolbar
 */
 class MY_Loader extends CI_Loader
{
        /**
         * List of loaded files
         *
         * @var array
         * @access protected
         */
        var $_ci_loaded_files           = array();
        /**
         * List of loaded models
         *
         * @var array
         * @access protected
         */
        var $_ci_models                 = array();
        /**
         * List of loaded helpers
         *
         * @var array
         * @access protected
         */
        var $_ci_helpers                        = array();
}

/* End of file Loader.php */
/* Location: ./application/core/Loader.php */