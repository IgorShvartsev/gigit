<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends MX_Controller {

  public function __construct()
  {
      $this->layout->set_layout('admin');
      $this->load->model('authmodel');
  }  
  
  public function index()
  {
      redirect();
  }
  
  public function login()
  {
      $this->load->library('form_validation');
      $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|xss_clean');
      $this->form_validation->set_rules('password', 'Password', 'required|trim|xss_clean');        
      if ($this->form_validation->run() != false) {
        $email    = trim($this->input->post('email'));
        $password = trim($this->input->post('password'));   
        $res = $this->authmodel->authenticate($email, $password, 'admin');
        if (is_array($res)) {
            $logindata = array(
                'id'    => $res['id'],
                'name'  => $res['first_name'] . ' ' .$res['last_name'],
                'email' => $res['email'],
                'role'  => 'admin'
            );
            $this->session->set_userdata('admindata', $logindata);
            redirect('admin');
        }
      }
      $this->load->view('login', array('isLogin' => true));
  }
  
  public function logout()
  {
      $this->session->unset_userdata('admindata');
      redirect('admin');
  }
  
}

/* End of file admin.php */
/* Location: ./application/modules/controllers/admin/auth.php */