<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'/libraries/services/Soundcloud.php';

/**
 * Wrapper for Soundcloud
 */
class Api_Soundcloud extends Services_Soundcloud        
{
    public function __construct($config)
    {
        $config['redirectUri']  = isset($config['redirectUri']) ? $config['redirectUri'] : null;
        $config['development']  = isset($config['development']) ? $config['development'] : false;
        parent::__construct($config['clientId'], $config['clientSecret'], $config['redirectUri'], $config['development'] );
    }
}

?>