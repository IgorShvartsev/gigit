<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Facebook extends MX_Controller {
    public function __construct()
    {
        /*
                $base_url   = rtrim(BASE_URL,'/');
    $script_url = $base_url . '/facebook.php?action=next';
    $cancel_url = $base_url . '/facebook.php?action=cancel';
    $error = '';
    $facebook = new FacebookApi(array(
        'appId'  => $config['facebook']['appId'],
        'secret' => $config['facebook']['consumer']['secret'],
        'cookie' => true
    ));
    
    $session = $facebook->getSession();

    if ($session) {
        try{
            $uid = $facebook->getUser();
            $me = $facebook->api('/me?fields=id,first_name,last_name,gender,birthday,email,location');
        }
        catch(FacebookApiException $e){
            $error = $e->getMessage();
        }
    }
    
    $tpl   = $_REQUEST['tpl'] ? $_REQUEST['tpl'] : 'register';
    $perms = $_REQUEST['perms'] ? $_REQUEST['perms'] : 'user_photos,user_birthday,email,user_location';
        */
        $this->layout->disable_layout();
        parent::__construct();
    }
	public function index()
	{
        redirect();
	}
    
    public function auth()
    {
        $redirect  = $this->input->get('redirect');
        $perms = $this->input->get('perms');
        if (!$redirect) {
            redirect('');
            exit();
        }
        $this->session->set_userdata('redirect', $redirect);
        $config = $this->config->item('facebook');
        $this->load->library('facebook', $config);
        $session = $this->facebook->getSession();

        if ($session) {
             echo '<script type="text/javascript"> 
                        opener.location.href = "' . base_url($next) . '" ;
                        window.close();
                      </script>';
              
        } else {
             $this->session->set_userdata('access_token', $session['access_token']);
             $perms = $perms ? $perms : 'user_photos,user_birthday,email,user_location';
             $loginUrl  = $this->facebook->getLoginUrl(
                array(  'next'      => base_url('facebook/next'), 
                        'fbconnect' => 0, 
                        'display'   =>'popup', 
                        'req_perms' => $perms, 
                        'cancel_url'=> base_url('facebook/cancel')
                )
             ); 
             //echo '<script type="text/javascript"> window.location.href = "'. $link.'"; </script>';
             header('Location: '.$loginUrl);
             exit();
        }
        /*
            if (!$me) {  
                echo '<script type="text/javascript"> window.location.href = "'. $facebook->getLoginUrl(array('next'=>$script_url, 'fbconnect'=>0, 'display'=>'popup', 'req_perms'=>$perms, 'cancel_url'=>$cancel_url)).'"; </script>';
            }else{
                echo '<script type="text/javascript"> 
                        opener.location.href = "'.$base_url.'/?tpl='.$tpl.'&mydata=facebook" ;
                        window.close();
                      </script>'; 
            }
            exit();
        */
    }
    
    public function cancel()
    {
        echo '<script type="text/javascript"> window.close(); </script>';
        
    }
    
    public function next()
    {
        echo '<script type="text/javascript">
                    opener.location.href="' . base_url($this->session->userdata('redirect')) . '";
                    window.close();
              </script>';
    }
}

/* End of file facebook.php */
/* Location: ./application/controllers/facebook.php */