<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MX_Controller {

	public function index()
	{
		echo 'user';
	}
    
    public function login()
    {
        echo 'login';
    }
    
    public function logout()
    {
        echo 'logout';
    }
    
    public function signup()
    {
        echo 'register';
    }
}

/* End of file user.php */
/* Location: ./application/modules/auth/controllers/user.php */