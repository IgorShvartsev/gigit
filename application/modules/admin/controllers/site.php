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
  
  public function dashboard()
  {
      $data = array();
      $this->load->view('dashboard', $data);
  }
  
}

/* End of file site.php */
/* Location: ./application/modules/controllers/admin/site.php */