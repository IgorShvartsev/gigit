<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Yahoo OAuth2 Provider
 *
 * @package    CodeIgniter/OAuth2
 * @category   Provider
 * @author     Toxa Bes
 * @copyright  (c) 2012 Softreactor LLC
 * @license    BSD
 */
require_once dirname(__FILE__).'/../OAuth/Yahoo.inc';
class OAuth2_Provider_Yahoo extends OAuth2_Provider
{
        public $method = 'POST';       

	public function url_authorize()
	{
		return '';
	}

	public function url_access_token()
	{
		return '';
	}
        
        public function authorize($options = array())
	{
                $state = md5(uniqid(rand(), TRUE));
		get_instance()->session->set_userdata('state', $state);

                $yahoo_auth = new YahooAuthorization;              
                $request_token = $yahoo_auth->getRequestToken($this->client_id, $this->client_secret, $this->redirect_uri);                    
                get_instance()->session->set_userdata('request_token', $request_token);              
                $auth_url = $yahoo_auth->createAuthorizationUrl($request_token); 

                redirect($auth_url);
	}
        
        public function access($code, $options = array())
	{
                $verifier = $_GET["oauth_verifier"];
                $request_token = get_instance()->session->userdata('request_token');
                $yahoo_auth = new YahooAuthorization;                  
                $access_token = $yahoo_auth->getAccessToken($this->client_id, $this->client_secret, $request_token, $verifier);

                return $access_token;		
	}
            
	public function get_user_info($token)
	{
                $consumer = new OAuthConsumer($this->client_id, $this->client_secret);               
                $yahoo_session = new YahooSession($consumer, $token, $this->app_id);

                $user = $yahoo_session->getSessionedUser();
                $profile = $user->getProfile();
                
                $mail = null;
                foreach ($profile->emails as $email)
                {
                    if(isset($email->primary)){
                        if($email->primary == true){
                            $mail = $email->handle;
                        }
                    }
                }
	
		// Create a response from the request
		return array(
			'uid' => $profile->guid,
			'nickname' => $profile->nickname,
			'name' => $profile->nickname,
			'first_name' => $profile->givenName,
			'last_name' => $profile->familyName,
			'email' => $mail,
			'location' => null,
			'description' => null,
			'image' => $profile->image->imageUrl,
			'urls' => array(
			  'Yahoo' => $profile->profileUrl,
			),
		);
	}
}
