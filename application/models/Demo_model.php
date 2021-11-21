<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Demo_model extends MY_Model {

	public function __construct()
	{
		parent::__construct();
		$this->_collection = 'demo';
	}

}

/* End of file Demo_model.php */
/* Location: ./application/models/Demo_model.php */