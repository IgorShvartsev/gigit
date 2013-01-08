<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends MX_Controller_Admin {
   
  public function __construct()
  {
      parent::__construct();
  }  
  
  public function index()
  {
      redirect('site/dashboard');
  }
  
  public function dashboard($item = '')
  {
      $data = array(
        'section' => 'dashboard',
        'item'    => $item  
      );
      $this->load->model('bands');
      $this->load->model('fans');
      $data['bands']  = $this->bands->getData(null, null, array('id'=>'desc','create_date'=>'desc'), 1, 5);
      $data['fans']   = $this->fans->getData(array('role' => 'user'), null, array('id'=>'desc','create_date'=>'desc'), 1, 5);   
      $this->_view($data);
  }
  
  public function settings($item = '')
  {
      $data = array(
        'section' => 'settings',
        'item'    => $item  
      );  
      $this->_view($data);
  }
  
  public function pages($item = '')
  {
      $data = array(
        'section' => 'pages',
        'item'    => $item  
      );  
      $this->_view($data);
  }
  
  protected function _view($data) 
  {
      $this->load->view('index', array_merge($data, $this->data));
  } 
  
}

/* End of file site.php */
/* Location: ./application/modules/controllers/admin/site.php */