<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Band extends MX_Controller_Public {

    public function __construct()
    {
        $this->sessionId = $this->session->userdata('session_id'); 
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
        $jsParams = array(
                'id'      => is_array($data['band']) ? $data['band']['id'] : 0,
                'model'   => $this->encrypt->encode('calendar', $this->sessionId)
        );
        $data['jsparams']  = base64_encode($this->encrypt->encode(serialize($jsParams), $this->sessionId));
        $this->load->view('band', $data);
	}
    
    public function profile()
    {
        $loggedBand = bandLoggedIn();
        if (!$loggedBand) {
            redirect('');
        }    
        $data['band'] = $loggedBand;
        $data['profile'] = 1;
        $jsParams = array(
            'id'      => $loggedBand['id'],
            'model'   => $this->encrypt->encode('calendar', $this->sessionId)
        );
        $data['jsparams']  = base64_encode($this->encrypt->encode(serialize($jsParams), $this->sessionId));
        $this->load->view('band', $data);
    }
    
    public function dashboard()
    {
        $this->load->view('dashboard');
    }
    
    public function gigs()
    {
        $this->load->view('gigs');
    }
    
    public function settings()
    {
        $this->load->view('settings');
    }
}

/* End of file band.php */
/* Location: ./application/modules/band/controllers/band.php */