<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Booking extends MX_Controller_Public {

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
        
        $this->_postdata();
        
        $this->load->view('booking', $data);
	}
    
    
    public function confirm($userid = 0)
    {
        $userid = (int) $userid;
        $this->load->view('booking_confirm'); 
    }
    
    protected function _postdata()
    {
        $data = $this->input->post('data');
        if (!is_array($data)) {
            return;
        }
        redirect('payment');
    }
     
}
 
/* End of file booking.php */
/* Location: ./application/modules/band/controllers/booking.php */ 