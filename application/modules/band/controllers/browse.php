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
        $data['zip']    = (int)$this->input->get_post('zip');
        if ($data['zip']) {
            // get geo data from ZIP and save into session
            $geo_position = $this->session->userdata('geo_position');
            if (!$geo_position || ($geo_position && $geo_position['zip'] != $data['zip'])) {
                $this->load->library('geocode');
                $res = $this->geocode->getByZip($data['zip']);
                if (isset($res['result'])) {
                    $this->session->set_userdata('geo_position', array(
                        'zip'    => $data['zip'],
                        'lat'    => $res['result']['lat'],
                        'lng'    => $res['result']['lng']
                    ));
                } 
            }
        } else {
            $this->session->unset_userdata('geo_position');
        }
        $data['bands']  = $this->bands->getAll(
                $this->input->get_post(NULL, TRUE), 
                $data['total']
        );
		$this->load->view('browse', $data);
	}
}

/* End of file browse.php */
/* Location: ./application/controllers/modules/band/browse.php */