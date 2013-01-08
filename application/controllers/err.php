<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Err extends MX_Controller {

    public function __construct()
    {
        $this->layout->disable_layout();
    }
	public function index( $status = '404' )
	{
	    $this->output->set_status_header((int)$status);
        $data = array( 
            'description' => 'Page not found',
            'type'        =>  (int)$status
        );
        $this->load->view('error', $data);
	}
}

/* End of file test.php */
/* Location: ./application/controllers/test.php */