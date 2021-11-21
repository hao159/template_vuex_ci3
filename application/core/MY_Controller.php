<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$returnurl = base_url(uri_string());
		$this->myauthen->checkLogin(true, $returnurl);
		$router = (array) $this->router;

		$this->config->set_item('active_page', empty(uri_string()) ? strtolower($router['default_controller']) : uri_string());
	}



}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */