<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends MX_Controller {

  public function __construct()
  {
      $this->layout->set_layout('admin');
      $this->load->model('users');
  }  
  
  public function index()
  {
         echo 'auth';
  }
  
  public function login()
  {
      $this->load->view('login',array('isLogin' => true));
  }
  
  public function logout()
  {
      
  }
  
}

/* End of file admin.php */
/* Location: ./application/modules/controllers/admin/auth.php */