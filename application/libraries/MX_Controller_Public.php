<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MX_Controller_Public extends MX_Controller 
{
     public $data = array(
        'title'            => '',
        'meta_keywords'    => '',
        'meta_description' => ''
     );
     
     public function __construct()
     {
        parent::__construct(); 
        $this->layout->set_layout('main');
        $this->load->model('pages');
        $this->data['static_pages'] = $this->pages->getAll();
     }
}