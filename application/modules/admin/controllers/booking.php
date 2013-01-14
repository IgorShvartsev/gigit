<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Booking extends MX_Controller_Admin {
   
  public function __construct()
  {
      parent::__construct();
  }  
  
  public function index()
  {
      redirect('bookings/bookings');
  }
  
  public function bookings($item = '')
  {
      if (empty($item) && $this->input->get('p') && isXmlHttpRequest()) {
          echo modules::run('admin/ajax/getall');
          return;
      }
      $data = array(
        'section' => 'bookings',
        'item'    => $item  
      );
      $this->_view($data);
  }
  
  
  protected function _view($data) 
  {
      $this->load->view('index', array_merge($data, $this->data));
  } 
  
}

/* End of file booking.php */
/* Location: ./application/modules/controllers/admin/booking.php */