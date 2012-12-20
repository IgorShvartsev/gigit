<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MX_Controller {

  public function index()
  {
      redirect('admin/site/dashboard');
  }
}

/* End of file admin.php */
/* Location: ./application/modules/controllers/admin/admin.php */