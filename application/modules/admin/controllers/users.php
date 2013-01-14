<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends MX_Controller_Admin {

  public function __construct()
  {
      parent::__construct();
      $this->data['menu'] = 'users'; 
  } 
     
  public function index()
  {
      redirect('admin/users/band');
  }
  
  public function band($item = '')
  {
      if (empty($item) && $this->input->get('p') && isXmlHttpRequest()) {
          echo modules::run('admin/ajax/getall');
          return;
      }
      $data = array(
        'section' => 'band',
        'item'    => $item  
      );  
      $this->_view($data);
  }
  
  public function fan($item = '')
  {
      if (empty($item) && $this->input->get('p') && isXmlHttpRequest()) {
          echo modules::run('admin/ajax/getall');
          return;
      }
      $data = array(
        'section' => 'fan',
        'item'    => $item
      );  
      $this->_view($data);
      
  }
  
  protected function _view($data) 
  {
      $this->load->view('index', array_merge($data, $this->data));
  } 
  
}

/* End of file users.php */
/* Location: ./application/modules/controllers/admin/users.php */