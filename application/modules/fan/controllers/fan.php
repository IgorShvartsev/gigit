<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fan extends MX_Controller_Public 
{

    public function __construct()
    {
        $this->sessionId = $this->session->userdata('session_id'); 
        $this->load->model('fans');
        parent::__construct();    
    }
    
    public function index()
    {
        redirect('fan/dashboard');
    }
    
    public function dashboard()
    {
        $this->load->model('band/bands', '', 'bands');
        $data['featuredBands'] = $this->bands->getData(array('featured' => 1,  'active' => 1), null, array('create_date' => 'DESC'), 1, 4);
        $data['newestBands']   = $this->bands->getData(array('featured' => 0,  'active' => 1), null, array('create_date' => 'DESC'), 1, 4);
        $this->load->view('dashboard', $data); 
    }
    
    public function profile()
    {
        $this->load->view('profile');
    }
    
    public function gigs()
    {
        $this->load->view('gigs');
    }
    
    public function settings($subname = '')
    {
        $this->load->view('settings');
    }
}