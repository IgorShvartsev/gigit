<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Socials extends MX_Controller {
    
    protected $scope = array(
        'facebook' => array('user_photos','user_birthday','email','user_location')
    );
    
    protected $providers = array('facebook');
    
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
    
    public function provider($provider)
    {
         $provider =  strtolower($provider);
         if (!in_array($provider, $this->providers)) {
             redirect();
             exit();
         }
         $redirect  = $this->input->get('redirect');
         $scope     = $this->input->get('scope');
         if (!empty($redirect)) {
            $this->session->set_userdata('redirect', $redirect); 
            redirect('socials/provider/' . $provider);
            exit();
         }
         $config = $this->config->item($provider);

         if (!empty($scope)) {
             $config['scope'] = $scope;
         } else if (isset($this->scope[$provider])) {
             $config['scope'] = $this->scope[$provider];
         }
         $params = array();
         if ($provider == 'facebook') {
             $params['display']     = 'popup';
         } 
         
         $provider = $this->oauth2->provider($provider, $config);
         if (!$this->input->get('code') && !$this->input->get('oauth_token'))
         {
            if ($this->input->get('error')) {
                // Cancel operation
                echo '<script type="text/javascript"> window.close(); </script>';
                exit();
                return;
            } 
            $url = $provider->authorize(array(), $params);
            redirect($url);
         }
         else
         { 
            try
            {
                $code = $this->input->get('code') ? $this->input->get('code') :  $this->input->get('oauth_token');                            
                $token = $provider->access($code);
                $this->session->set_userdata('facebook', array('access_token' => $token->access_token, 'expires_in' => $token->expires_in));
                $user = $provider->get_user_info($token);

                echo "<pre>Tokens: ";
                var_dump($token);

                echo "\n\nUser Info: ";
                var_dump($user);
                
                echo '<script type="text/javascript">
                            opener.location.href="' . base_url($this->session->userdata('redirect')) . '";
                            window.close();
                       </script>';
            }

            catch (OAuth2_Exception $e)
            {
                show_error('That didnt work: '.$e);
            }
         }
    }
}

/* End of file socials.php */
/* Location: ./application/controllers/socials.php */