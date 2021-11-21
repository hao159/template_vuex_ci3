<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		if($this->input->post('user-name') && $this->input->post('password'))
        {
            $login = $this->myauthen->authenticate($this->input->post('user-name'), $this->input->post('password'));
            if (!$login) {
				$this->session->set_flashdata('error-login', 'Tài khoản hoặc mật khẩu không khớp, vui lòng đăng nhập lại!');

            }
        }
	}

	public function index()
	{
		$data = [];
		$this->load->view('template/login', $data);
	}
}

/* End of file Auth.php */
/* Location: ./application/controllers/Auth.php */