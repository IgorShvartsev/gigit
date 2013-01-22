<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Band extends MX_Controller_Public {

    protected $banddata = null;
    
    public function __construct()
    {
        $this->sessionId = $this->session->userdata('session_id'); 
        $this->load->model('bands');
        $this->banddata = bandLoggedIn();
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
        if (!$this->banddata) {
            redirect('');
        }    
        $data['band'] = $this->banddata;
        $data['profile'] = 1;
        $jsParams = array(
            'id'      => $this->banddata['id'],
            'model'   => $this->encrypt->encode('calendar', $this->sessionId)
        );
        $data['jsparams']  = base64_encode($this->encrypt->encode(serialize($jsParams), $this->sessionId));
        $this->load->view('band', $data);
    }
    
    public function dashboard()
    {
        if (!$this->banddata) {
            redirect('');
        }    
        $this->load->model('bookings');
        $data['bookings'] = $this->bookings->getData(array('band_id' => $this->banddata['id']), null, null, 1, 10);
        $this->load->view('dashboard', $data);
    }
    
    public function gigs()
    {
        $this->load->view('gigs');
    }
    
    public function settings()
    {
        $this->load->view('settings');
    }
    
    public function soundcloud($action = null)
    {   
        $this->layout->disable_layout();
        $soundcloud = $this->session->userdata('soundcloud');
        $config = $this->config->item('soundcloud');
        if (!$soundcloud) {
            redirect();
        }
        switch ($action) {
            case 'quickform':
                print_r($soundcloud);
                break;
            default:
                redirect();
        }
    } 
}

/* End of file band.php */
/* Location: ./application/modules/band/controllers/band.php */