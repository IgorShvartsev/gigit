<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*  Pre System class
*/
class Pre_System
{
    /**
    * Constructor
    * 
    */
    public function __construct()
    {
        
    }
    /**
    * Initialisation 
    * 
    */
    public function init()
    {
       $CI = &get_instance(); 
       defined('THEME') OR define('THEME', $CI->config->item('theme'));
       $session_id = $CI->session->userdata('session_id');
       if (!$session_id) {
           $CI->session->set_userdata('session_id', md5(uniqid()));
       }
    }
    
}

/* End of file pre_system.php */
/* Location: ./application/hooks/pre_system.php */