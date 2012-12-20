<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MX_Controller_Admin extends MX_Controller 
{
     public function __construct()
     {
        parent::__construct(); 
        $this->load->model('users');
        if (!$this->users->isAdmin()) {
            redirect('admin/auth/login');
        }
        $this->layout->set_layout('admin');
     }
}