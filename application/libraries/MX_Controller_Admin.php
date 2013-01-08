<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MX_Controller_Admin extends MX_Controller 
{
     public $data = array(
        'menu' => ''
     );
     
     public function __construct()
     {
        $this->data['menu'] = strtolower(get_class($this)); 
        parent::__construct(); 
        if (!adminLoggedIn()) {
            redirect('admin/auth/login');
        }
        $this->layout->set_layout('admin');
     }
}