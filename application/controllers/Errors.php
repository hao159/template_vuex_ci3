<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Errors extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data['page_title'] = '404 | Không tìm thấy trang';
		$this->load->view('template/page_404', $data);
	}

}

/* End of file Error.php */
/* Location: ./application/controllers/Error.php */