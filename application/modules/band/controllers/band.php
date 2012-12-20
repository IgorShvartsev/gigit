<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Band extends MX_Controller_Public {

    public function __construct()
    {
        $this->load->model('bands');
        parent::__construct();    
    }
    
	public function index($seo = '')
	{
        if (empty($seo)) {
		    redirect('band/browse');
            exit();
        }
        $data['band'] = $this->bands->getBySEO($seo);
        if (!$data['band']) {
            redirect('err/404');
        }
        $this->load->view('band', $data);
	}
    
    public function registration()
    {
        $data = array();
        $data['band'] = $this->bands->getTempAccount();
        $data['registration'] = 1;
        $this->load->view('band', $data);
    }
}

/* End of file band.php */
/* Location: ./application/modules/band/controllers/band.php */