<?php

/**
* Geocode class 
* Uses Google Geocoding API
*/
class Geocode
{
    public $request_url     = "http://maps.google.com/maps/api/geocode/json?";
    
    public $sensor          = "false";
    public $language        = "en";
    public $country         = 'United States';
    
    public function __construct($config = null)
    {
        
    }
    
    /**
    * Get geodata by ZIP
    * 
    * @param string $zip
    * @param string $state
    * @param string $country
    * @return array
    */
    public function getByZip($zip, $state = '', $country = '')
    {
        $querydata = array(
            'address'  => (empty($state) ? '' :( $state . ' ')). $zip . ',' . (empty($country) ? $this->country : $country),
            'language' => $this->language,
            'sensor'   => $this->sensor
        );
        
        $response = $this->_request($querydata);
        
        if($response->status == 'OK')
        {   
            $results = (array)$response->results; 
            $results = $results[0];
            if (!in_array('postal_code', (array)$results->types)) {
                return array('error' => 'Incorrect postal code');
            }
            $locality = '';
            $country  = '';
            foreach($results->address_components as $component) {
                $types = (array)$component->types;
                if ($types[0] == 'postal_code' && $component->long_name != $zip) {
                    return array('error' => 'Incorrect postal code');
                }
                if (in_array('locality', $types) || in_array('sublocality', $types)) {
                    $locality = $component->long_name;
                }
                if (in_array('country', $types)) {
                    $country = $component->long_name;
                }
            }
            return array('result' => array(
                'lng'      => $results->geometry->location->lng,
                'lat'      => $results->geometry->location->lat,
                'city'     => $locality,
                'country'  => $country 
            ));
        } 
        return array('error' => 'Connection failed'); 
    }
    
    /**
    * Get geodata by Address
    * 
    * @param string $address
    * @return array
    */
    public function getByAddress($address)
    {
        $querydata = array(
            'address'  => $address,
            'language' => $this->language,
            'sensor'   => $this->sensor
        );
        
        $response = $this->_request($querydata);
        
        if ($response->status == 'OK')
        {
            $results  = (array)$response->results; 
            $results  = $results[0]; 
            $locality = '';
            $country  = '';
            $zip      = '';
            $state    = '';
            foreach($results->address_components as $component) {
                $types = (array)$component->types;
                if ($types[0] == 'postal_code') {
                    $zip = $component->long_name;
                }
                if (in_array('locality', $types) || in_array('sublocality', $types)) {
                    $locality = $component->long_name;
                }
                if (in_array('country', $types)) {
                    $country = $component->long_name;
                }
                if (in_array('administrative_area_level_1', $types)) {
                    $state = $component->short_name;
                }
            }
            return array('result' => array(
                'lng'      => $results->geometry->location->lng,
                'lat'      => $results->geometry->location->lat,
                'city'     => $locality,
                'country'  => $country,
                'state'    => $state,
                'zip'      => $zip  
            )); 
        }
    }
    
    public function getByPoint($lat, $lng)
    {
        $querydata = array(
            'latlng'   => $lat . ',' . $lng,
            'language' => $this->language,
            'sensor'   => $this->sensor
        );
        $response = $this->_request($querydata);
        
        if ($response->status == 'OK')
        {
            $results = (array)$response->results; 
            $results = $results[0];
            $locality = '';
            $country  = '';
            $zip      = '';
            foreach($results->address_components as $component) {
                $types = (array)$component->types;
                if ($types[0] == 'postal_code') {
                    $zip = $component->long_name;
                }
                if (in_array('locality', $types) || in_array('sublocality', $types)) {
                    $locality = $component->long_name;
                }
                if (in_array('country', $types)) {
                    $country = $component->long_name;
                }
                if (in_array('administrative_area_level_1', $types)) {
                    $state = $component->short_name;
                }
            }
            return array('result' => array(
                'lng'      => $results->geometry->location->lng,
                'lat'      => $results->geometry->location->lat,
                'city'     => $locality,
                'country'  => $country,
                'state'    => $state,
                'zip'      => $zip  
            )); 
        }
        
    }
    
    /**
    * Request to Google service
    * 
    * @param array $querydata
    * @return object;
    */
    protected function _request($querydata)
    {
        $url = $this->request_url . http_build_query($querydata);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        //print_r($response);
        //die();
        $response = json_decode($response);
        curl_close($ch);
        return $response;
    }
     
}