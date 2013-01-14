<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends MX_Controller {

    public function __construct()
    {
         $this->layout->disable_layout();
    }
    
	public function index()
	{ 
		echo 'test';
        $this->load->module('band');
        $this->load->model('bands');
        //print_r($this->bands->getData(null, null, null, 1, 3, 'ja'));
        //print_r($this->bands->get(1));
        echo $this->bands->getTotal(null, null, 'nora');
	}
    
    public function my()
    {
       $this->load->library('geocode');
       print_r($this->geocode->getByZip('98370'));
    }
    
    public function calendar()
    {
        //$this->load->library('busycalendar');
        //print_r($this->busycalendar->weeks());
        $this->load->model('band/calendar','','calendar');
        $data = $this->calendar->getdata(1, array());
        print_r($data);
    }
}

/* End of file test.php */
/* Location: ./application/controllers/test.php */