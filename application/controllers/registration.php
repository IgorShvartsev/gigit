<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Registration  Controller
*/
class Registration extends MX_Controller_Public {
    /**
    * Type of registration (band or fan)
    * 
    * @var string
    */
    protected $type = null;
    
    /**
    * Registration error
    * 
    * @var string
    */
    protected $error = '';
    
    /**
    * Constructor
    * 
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('authmodel');
    }
    
    /**
    * Default action
    * 
    */
	public function index()
	{
        redirect('');    
	}
    
    /**
    * Band registration
    * 
    */
    public function band()
    {
        $this->type = 'band';
        $this->_checkSocialResult();
        if ($this->_validateForm() == TRUE) {
            $data = array(
                'email' => set_value('email'),
                'password' => sha1(md5(set_value('password')))
            );
            $this->load->model('band/bands');
            $id = $this->bands->createAccount($data);
            if ($id && !is_array($id)) {
                $data = $this->bands->getAccount($id);
                $data['role'] = 'band';
                $this->authmodel->setCredentials($data);
                redirect('band/profile');
                exit();
            }
        }
        $this->_view();
    }
    
    /**
    * Fan registration
    * 
    */
    public function fan()
    {
        $bandid = (int)$this->input->get('band');
        !$bandid OR $this->session->set_userdata('bandid', $bandid);
        
        $this->type  = 'fan';
        
        $this->_checkSocialResult();
        if ($this->_validateForm() == TRUE) {
            $data = array(
                'email' => set_value('email'),
                'password' => sha1(md5(set_value('password')))
            );
            
            $this->load->model('fan/fans');
            $id = $this->fans->createAccount($data);
            
            if ($id && !is_array($id)) {
                $data = $this->fans->get($id);
                $this->authmodel->setCredentials($data);
                $bandid = $this->session->userdata('bandid');
                if ($bandid) {
                    $this->session->unset_userdata('bandid');
                    $band = $this->db->get_where('bands', array('id' => $bandid))->result_array();
                    if (count($band) > 0) {
                        redirect('band/booking/' . $band[0]['seo'] .'.html');
                        exit();
                    }
                }
                redirect('fan/dashboard');
                exit();
            }
        }
        $this->_view();
    }
    
    /**
    * Choose type of new account (band or fan) through Facebook logging in
    * 
    */
    public function account()
    {
         $facebook = $this->session->userdata('facebook');
         if ($facebook && count($facebook) > 0) {
             $this->load->view('registration/choose_account');
         } else {
             redirect();
             exit();
         }
    }
    
    /**
    * Render view
    * 
    */
    protected function _view()
    {
        $data = array(
            'type'         => $this->type,
            'registration' => 1,
            'error'        => $this->error,
        );

        $bandid  = (int)$this->input->get('band');
        if ($bandid) {
            $data['bandid'] = $bandid;
        }

        $this->load->view('registration', $data);  
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
            switch($this->type) {
                
                case 'band':
                    $this->load->model('band/bands');
                    $res = $this->bands->createAccount($facebook['user']);
                    if ($res) {
                        $new = false;
                        if (!is_array($res)) {
                            $new = true;
                            $res = $this->bands->getAccount($res);
                        }
                        if ($res['uid'] == $facebook['user']['uid']) {
                            /***** band authorization ******/
                            $res['role'] = 'band';
                            $this->authmodel->setCredentials($res);
                            $new ? redirect('band/profile') : redirect('band/dashboard');
                            exit();
                        } 
                        $this->error = "Sorry you can't register by Facebook. Please try email and password.";
                        return; 
                    }
                    $this->error = "This account already exists as a Fan account. Please use Login to enter.";
                    break;
                
                case 'fan':
                    $this->load->model('fan/fans');
                    $res = $this->fans->createAccount($facebook['user']);
                    if ($res) {
                        if (!is_array($res)) {
                            $res = $this->fans->get($res);
                        }
                        if ($res['uid'] == $facebook['user']['uid']) {
                            /***** fan authorization ******/
                            $this->authmodel->setCredentials($res);
                            $bandid = $this->session->userdata('bandid');
                            if ($bandid) {
                                $this->session->unset_userdata('bandid');
                                $band = $this->db->get_where('bands', array('id' => $bandid))->result_array();
                                if (count($band) > 0) {
                                    redirect('band/booking/' . $band[0]['seo'] .'.html');
                                    exit();
                                }
                            }
                            redirect('fan/dashboard');
                            exit();
                        } 
                        $this->error = "Sorry you can't register by Facebook. Please try email and password.";
                        return; 
                    }
                    $this->error = "This account already exists as a Band account. Please use Login to enter.";
                    break;
            }
        }
    }
    
    /**
    * Form validation
    * 
    */
    protected function _validateForm()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>')
                              ->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]|is_unique[bands.email]')
                              ->set_rules('password', 'Password', 'trim|required|min_length[8]|max_length[16]|prep_for_form|xss_clean')
                              ->set_rules('confirm_password', 'Confirm Password', 'trim|required|xss_clean|matches[password]')
                              ->set_message('is_unique', 'email already exists. Try another one.')
                              ->set_message('matches', 'not mathes the Password.')
                              ->set_message('required', 'is required.');
        return $this->form_validation->run();
    }
}

/* End of file registration.php */
/* Location: ./application/controllers/registration.php */