<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends MX_Controller_Public {

    protected $userdata = null;
    
    public function __construct()
    {
        $this->userdata = userLoggedIn();
        if (!$this->userdata) {
            redirect();
        }
        $this->load->model('payments');
        parent::__construct();    
    }
    
	public function index()
	{
        $this->_postdata();
        $data['creditcard'] = $this->payments->getCreditCard($this->userdata['id']);
        $data['states'] = $this->config->item('states');
        $this->load->view('payment', $data);
	}
    
    public function thanks()
    {
        $this->load->view('payment/thanks');
    }
    
    protected function _postdata()
    {
        $data = $this->input->post('data');
        if (!is_array($data)) {
            return;
        }
        $this->payments->saveCreditCard($this->userdata['id'], $data);
        redirect('payment/thanks');
    }
     
}

/* End of file payments.php */
/* Location: ./application/controllers/payments.php */