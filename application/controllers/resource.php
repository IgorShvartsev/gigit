<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
*  Resource Controller
*/
class Resource extends MX_Controller {
    
    protected $cssPath = 'assets/css/';
    
    protected $jsPath  = 'assets/js/';
    
    /**
    * Constructor
    * 
    */
    public function __construct()
    {
        parent::__construct();
        $this->layout->disable_layout(); 
    }
    
    /**
    * Default action
    * 
    */
    public function index()
    {
        redirect();
        break;
    }
    
    /**
    * Get javascript resources
    * 
    */
    public function js()
    {
        $type = $this->input->get('t');
        switch($type) {
            case 'component':
                break;
            default:
                header('Content-type: text/javascript;charset=utf-8');
                $resource = $this->config->item('resource');
                foreach($resource['js'] as $fname){
                    if (file_exists($this->jsPath.$fname)) {
                        echo file_get_contents(realpath($this->jsPath.$fname))."\n\n";
                    }
                }
        }
    }
    
    /**
    * Get CSS resources
    * 
    */
    public function css()
    {
         header('Content-type: text/css');
         $resource = $this->config->item('resource');
         foreach($resource['css'] as $fname){
            if (file_exists($this->cssPath.$fname)) {
                echo file_get_contents(realpath($this->cssPath.$fname))."\n\n";
            }
         }
    }
    
    /**
    * Builds javascript interface
    * 
    */
    public function interface_js()
    {
        header('Content-type: text/javascript;charset=utf-8');
        $type = $this->input->get('t');
        $module = $this->input->get('md');
        if (empty($type)) {
            echo '/*Interface type not defined*/';
            return;
        }
        $params = @unserialize($this->encrypt->decode(base64_decode($this->input->get('p')), $this->session->userdata('session_id')));
        if (!is_array($params)) {
            echo '/*Passed params damaged*/';
            return;
        }
        $jsResource = empty($module) ? (APPPATH. "views/js/$type-js.php") : (APPPATH. "modules/" . $module . "/views/js/$type-js.php") ;
        if (file_exists($jsResource)) {
            empty($module) ? $this->load->view("js/$type-js.php", $params) : $this->load->view($module . "/js/$type-js.php", $params);
        } else{
            echo '/*Unknown interface*/';  
        } 
    }
    
}

/* End of file resource.php */
/* Location: ./application/controllers/resource.php */
