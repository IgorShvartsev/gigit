<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Browse extends MX_Controller_Public {
    
    public function __construct()
    {
        $this->load->model('bands');
        parent::__construct();
    }
    
	public function index()
	{
        $data['total']  = 0;
        $data['perpage']= $this->config->item('perpage');
        $data['p']      = $this->input->get('p');
        $data['sort']   = $this->input->get('sort');
        $data['show']   = $this->input->get('show');
        $data['bands']  = $this->bands->getAll(
                $this->input->get(NULL, TRUE), 
                $data['total']
        );
		$this->load->view('browse', $data);
	}
}

/* End of file browse.php */
/* Location: ./application/controllers/modules/band/browse.php */