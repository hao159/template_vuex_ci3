<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data['page_title'] = "Trang chủ";
		$data['temp'] = 'welcome_message';
		$this->load->view('template/layout', $data);
	}

}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */