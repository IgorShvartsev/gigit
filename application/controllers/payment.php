<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends MX_Controller_Public {

	public function index()
	{
        $this->_postdata();
        $this->load->view('payment');
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
        redirect('payment/thanks');
    } 
}

/* End of file payment.php */
/* Location: ./application/controllers/payment.php */