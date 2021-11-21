<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Auth
 */
class Myauthen
{
	
	private $_MY;
	private $_table;

	public function __construct()
	{
		// code...
		$this->_MY = &get_instance();
		$this->_table = 'Auth';
	}

	/*
		auth and create session
	*/
	public function authenticate($user, $pwd)
	{
		$conditions = array(
			'UserName' => $user,
			'PassWord' => $pwd,
			'isActive' => TRUE
		);

		$tmp = $this->_MY->mongo_db->where($conditions)->getOne($this->_table);

		if ($tmp) {
			// ok
			unset($tmp['PassWord']);
			// set session
			$this->_MY->session->set_userdata($tmp);
			if ($_GET['return-url'] && !empty($this->_MY->input->get("return-url"))) {
                redirect($this->_MY->input->get("return-url"), 'location');
            } else {
                redirect(base_url(), 'location');
            }
		}else{
			return false;
		}

	}

	/*
		check user is logging
	*/

	public function checkLogin($redirect = false, $returnurl = "")
	{
		if ($this->_MY->router->class != 'auth') {

            if ($this->_MY->session->userdata("UserName")) {
                $this->logoutCheck();
            } else {

                /* User is not signed-in */
                if ($redirect === true) {
                    redirect(base_url('login') . '?return-url=' . urlencode($returnurl), 'location');
                } else {
                    return false;
                }
            }
        } else {
            if ($this->_MY->session->userdata("UserName")) {
                redirect(base_url(), 'location');
            }
        }
	}
	public function logoutKey()
    {
        $sessionid = $this->_MY->session->userdata("session_id");
        //echo hash("md5", $sessionid . "I-WANT-TO-LOGOUT");

        return hash("md5", $sessionid . "I-WANT-TO-LOGOUT");
    }

    private function logoutCheck()
    {
        if ($this->_MY->input->get('signout') === $this->logoutKey()) {

            $this->_MY->session->sess_destroy();
            redirect(base_url(), 'location');
        }
    }
}