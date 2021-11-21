<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test1 extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data['page_title'] = "Trang test menu lv2";
		$data['temp'] = 'welcome_message';
		$this->load->view('template/layout', $data);
	}

	// public function genDemoData()
	// {
	// 	$this->load->model('Demo_model');
	// 	$batch = [];
	// 	for ($i=0; $i < 100000; $i++) { 
	// 		$batch[] = array(
	// 			'col1' => 'Demo data '. $i,
	// 			'col2' => $i*4 + $i -3,
	// 			'col3' => boolval(rand(0,1)),
	// 			'col4' => 'Note demo ' . rand(10000, 99999)
	// 		);
	// 	}

	// 	$this->Demo_model->addNew_many($batch);
	// 	echo "Xong";
	// }
	
}

/* End of file Test1.php */
/* Location: ./application/controllers/Test1.php */