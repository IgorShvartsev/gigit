<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends MX_Controller {

	public function index()
	{
        $this->layout->disable_layout();
		echo 'test';
        $this->load->module('band');
        $this->load->model('bands');
        //print_r($this->bands->getData(null, null, null, 1, 3, 'ja'));
        //print_r($this->bands->get(1));
        echo $this->bands->getTotal(null, null, 'nora');
	}
    
    public function my()
    {
       $this->layout->disable_layout();
       echo 'te';
    }
}

/* End of file test.php */
/* Location: ./application/controllers/test.php */