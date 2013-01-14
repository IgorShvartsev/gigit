<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Socials extends MX_Controller {
    
    protected $scope = array(
        'facebook' => array('user_photos','user_birthday','email','user_location')
    );
    
    protected $socials = array('facebook');
    
    public function __construct()
    {
        $this->load->spark('oauth2/0.3.1');
        $this->layout->disable_layout();
        parent::__construct();
    }
	public function index()
	{
        redirect();
	}
    
    public function provider($social)
    {
         $social =  strtolower($social);
         if (!in_array($social, $this->socials)) {
             redirect();
             exit();
         }
         $redirect  = $this->input->get('redirect');
         $scope     = $this->input->get('scope');
         if (!empty($redirect)) {
            $this->session->set_userdata('redirect', $redirect); 
            redirect('socials/provider/' . $social);
            exit();
         }
         $config = $this->config->item($social);

         if (!empty($scope)) {
             $config['scope'] = $scope;
         } else if (isset($this->scope[$social])) {
             $config['scope'] = $this->scope[$social];
         }
         $params = array();
         if ($social == 'facebook') {
             $params['display']     = 'popup';
         } 
         
         $provider = $this->oauth2->provider($social, $config);
         if (!$this->input->get('code') && !$this->input->get('oauth_token'))
         {
            if ($this->input->get('error')) {
                // Cancel operation
                echo '<script type="text/javascript"> window.close(); </script>';
                exit();
                return;
            } 
            // authorization
            $url = $provider->authorize(array(), $params);
            redirect($url);
         }
         else
         { 
            // accept operation 
            try
            {
                $code    = $this->input->get('code') ? $this->input->get('code') :  $this->input->get('oauth_token');                            
                $token   = $provider->access($code);
                $user    = $provider->get_user_info($token);
                // check geo data
                $geodata = array();
                if (isset($user['location'])) {
                    $this->load->library('geocode');
                    $point = $this->geocode->getByAddress($user['location']);
                    if (isset($point['result'])) {
                        $geodata = $this->geocode->getByPoint($point['result']['lat'], $point['result']['lng']);
                        $geodata = !isset($geodata['error']) ? $geodata['result'] : array(); 
                    }
                }
                $this->session->set_userdata($social, array(
                    'access_token' => $token->access_token, 
                    'expires'      => $token->expires,
                    'user'         => array_merge((array)$user, $geodata) 
                ));
                

                //print_r($token);
                //die();
                
               // redirect and close 
               echo '<script type="text/javascript">
                            opener.location.href="' . base_url($this->session->userdata('redirect')) . '";
                            window.close();
                       </script>';
            }
            catch (OAuth2_Exception $e)
            {
                show_error('Something wrong: ' . $e);
            }
         }
    }
}

/* End of file socials.php */
/* Location: ./application/controllers/socials.php */