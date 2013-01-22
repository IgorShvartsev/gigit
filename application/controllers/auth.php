<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends MX_Controller {
    
    protected $error = '';
    
    public function __construct()
    {
        $this->layout->disable_layout();
        parent::__construct();
        $this->load->model('authmodel');
    }
    
    public function index()
    {
        redirect('');
    }
    
    public function login()
    {
        // check if logged in with social account
        $this->_checkSocialResult();
        // check form post data
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() == TRUE) {
             $authdata = $this->authmodel->authenticate($this->input->post('email'), $this->input->post('password'), 'band', 'bands');
             if ($authdata) {
                /***** band authorization ******/
                $authdata['role'] = 'band';
                $this->authmodel->setCredentials($authdata);
                if (isXmlHttpRequest()) {
                    $data['result'] = $authdata;
                } else {
                    redirect('band/dashboard');
                    exit();
                    return;
                }   
             } 
             $authdata = $this->authmodel->authenticate($this->input->post('email'), $this->input->post('password'), 'user', 'users'); 
             if ($authdata) {
                $this->db->where('id', $authdata['id'])
                         ->update('users', array('last_visit' => date('Y-m-d')));
                /***** fan authorization ******/
                $this->authmodel->setCredentials($authdata);
                if (isXmlHttpRequest()) {
                    $data['result'] = $authdata;
                } else {
                    redirect('fan/dashboard');
                    exit(); 
                    return;
                }   
             } else { 
                $this->error = "Incorrect password or email";
             }
        }
        $data['error'] = $this->error;
        return json_encode($data);
    }
    
    public function logout()
    {
        $this->session->unset_userdata('logindata');
        $this->session->unset_userdata('facebook');
        $this->session->unset_userdata('soundcloud');
        redirect();
    }
    
    /**
    * Check social logging in procedure
    * 
    */
    protected function _checkSocialResult()
    {
        $facebook = $this->session->userdata('facebook');
        if ($facebook && count($facebook) > 0) {
            if (!isset($facebook['user']['email'])) {
                $this->error = "Please make application permission to your email in your Facebook account";
                return;
            }
            $account = $this->authmodel->checkAccount($facebook['user']['email']);
            if (is_array($account)) {
                !isset($account['role']) ? redirect('registration/band') : redirect('registration/fan');
            } else {
                redirect('registration/account');
            }
        }
    }
}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */