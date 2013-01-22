<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
*  Soundcloud controller
*/
class Soundcloud extends MX_Controller
{
     protected $client = null;
     
     protected $expired = false;
     
     public function __construct($redirect = '')
     {
        $this->layout->disable_layout();         
        parent::__construct(); 
        // check session for authorization
        $session_soundcloud = $this->session->userdata('soundcloud');
        
        if (!$session_soundcloud) {
            die('You are not authorized');
        }
        // check expiration of access token . If expired clean soundcloud session data
        $this->expired = $session_soundcloud['expires'] <= time();
        if ($this->expired) {
            $this->session->unset_userdata('soundcloud');
        }
        // load api and init client
        $credentials = $this->config->item('soundcloud'); 
        $this->load->library('api_soundcloud', array(
            'clientId'     => $credentials['id'],
            'clientSecret' => $credentials['secret']
        ));
        $this->api_soundcloud->setAccessToken($session_soundcloud['access_token']);
        $this->client = $this->api_soundcloud;
     }
        
     public function index()
     {
         redirect();
     }
     
     public function gettracks()
     {
         $data = array();
         $this->load->model('band/tracks');
         // get band soundcloud tracks from db
         $dbTracks = array();
         $band = bandLoggedIn();
         if ($band) {
            $res = $this->tracks->getSoundcloudTracks($band['id']);
            if (count($res) > 0) {
                foreach($res as $row) {
                    $dbTracks[] = $row['soundcloud_id'];
                }
            }
         }
         // get tracks from soundcloud service
         if (!$this->expired) {
            $tracks = json_decode($this->client->get('me/tracks'));
            foreach($tracks as $track) {
                $trackData = array(
                    'soundcloud_id' => $track->id,  
                    'title'         => $track->title, 
                    'size'          => $track->original_content_size,
                    'file'          => $track->uri,
                    'stream_url'    => $track->stream_url
                );
                $data['result'][] = array(
                    'id'      => $track->id,
                    'title'   => $track->title,
                    'value'   => base64_encode(serialize($trackData)),
                    'checked' => intVal(in_array($track->id, $dbTracks))  
                );
            }
         } else {
             $data['error'] = 'Expired';
         }
         $this->output->set_content_type('application/json')  
                      ->set_output(json_encode($data));
     } 
}