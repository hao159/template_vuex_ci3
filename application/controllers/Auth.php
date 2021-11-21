<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!isset($_POST['g-recaptcha-response']) OR empty($_POST['g-recaptcha-response'])) {
			$this->session->set_flashdata('error-login', 'Vui lòng nhập recaptcha!!!');
		}
		$captcha = $this->input->post('g-recaptcha-response');
		$secretKey = get_env('APP_GOOGLE_SECRET_KEY');

		// post request to server
		$url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
		$response = file_get_contents($url);
		$responseKeys = json_decode($response,true);
		// print_r($responseKeys);
		// exit;

		if($responseKeys["success"]){
			if($this->input->post('user-name') && $this->input->post('password'))
	        {
	            $login = $this->myauthen->authenticate($this->input->post('user-name'), $this->input->post('password'));
	            if (!$login) {
					$this->session->set_flashdata('error-login', 'Tài khoản hoặc mật khẩu không khớp, vui lòng đăng nhập lại!');

	            }
	        }
	    }else{
	    	$this->session->set_flashdata('error-login', 'Vui lòng nhập recaptcha!!!');
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