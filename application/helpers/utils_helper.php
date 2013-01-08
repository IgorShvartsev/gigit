<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
* Checks if request is XmlHttp
* 
*/
if ( ! function_exists('isXmlHttpRequest')) 
{
    function isXmlHttpRequest()
    {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
    }
}

/**
* Get current theme
* 
* @return string
*/
if ( ! function_exists('get_theme')) 
{
    function get_theme()
    {
        $CI = &get_instance();
        return $CI->config->item('theme');
    }
}

/**
*  price_view 
*  Format price 
* 
* @param mixed $price
* @param string $delimiter
* @return string
*/
if ( ! function_exists('price_view')) 
{
    function price_view($price, $delimeter = ",")
    {
        $price = (string)$price;
        $arr = explode('.', $price);
        $l = str_split(strrev($arr[0]), 3);
        $arr[0] = strrev(implode($delimeter, $l));
        return implode('.', $arr);
    }
}

/**
*  toSeoString 
*  Convert string to seo-safe  string
* 
* @param string $string
* @return string 
*/
if ( ! function_exists('toSeoString')) 
{
    function toSeoString($string) 
    {
        $string = preg_replace('/\s\s+/', ' ', $string);
        $aWords = explode(' ',$string);
        $cleanWords = array();
        foreach($aWords as $word)
        {
            if( empty($word) )continue;
            if (function_exists('iconv'))
            {
                $word = iconv('utf-8', 'us-ascii//TRANSLIT', $word);
            }
            $cleanWords[] = strtolower( preg_replace('/[^.a-zA-Z0-9-]/i', '', $word) ); 
        }
        $string = ltrim(implode('-', $cleanWords), '.');
        return trim($string, '-');
    }
}

/**
* addQueryString
* Adds params from array into current  QUERY_STRING
*
* @param array $arr - array of params with key=>value
* @param string $queryString
* @return string
*/
if ( ! function_exists('addQueryString'))
{ 
    function addQueryString($arr, $queryString = false)
    {
        if (!is_array($arr)) return '';
        $queryString = $queryString === false ? $_SERVER['QUERY_STRING'] : $queryString;
        foreach($arr as $param=>$val)
        {
            $queryString = preg_replace(array('/&?'.$param.'\=(.*?)(&|$)/i', '/\?&/i', '/^&|&$/i', '/\?/'),array('&','?','',''), $queryString);
        }
        $qstr = http_build_query($arr, '', '&amp;');
        return empty($queryString) || strpos($queryString, '?') !== false ? ('?'.$qstr) : ('?'.$queryString.'&amp;'.$qstr);
    }
}


/**
 * removeQueryString
 * remove params listed in array from current  QUERY_STRING
 *
 * @param mixed $param - name of param or array of names in the query string to be removed
 * @param string $queryString - if empty current QUERY_STRING will be taken
 * @return string
 */
if ( ! function_exists('removeQueryString'))
{  
    function removeQueryString($param, $queryString = '')
    {
        $queryString = empty($queryString) ? $_SERVER['QUERY_STRING'] : $queryString;
        if (is_array($param))
        {
            foreach($param as $var)
            {
                $queryString = removeQueryString($var, $queryString);
            }
        }
        else
        {
            $queryString = preg_replace(array('/&?'.$param.'\=(.*?)(&|$)/i', '/\?&/i', '/^&|&$/i', '/\?/'),array('&','?','',''), $queryString);
        }
        return !empty($queryString) ? (strpos($queryString,'?') != false ? $queryString : ('?'.$queryString) ) : '';
    }
}


if ( ! function_exists('bandLoggedIn'))
{  
    function bandLoggedIn()
    {
        $CI = &get_instance();
        $logindata = $CI->session->userdata('logindata');
        return is_array($logindata) && $logindata['role'] == 'band' ?  $logindata : false;
    }
}


if ( ! function_exists('userLoggedIn'))
{  
    function userLoggedIn()
    {
        $CI = &get_instance();
        $logindata = $CI->session->userdata('logindata');
        return is_array($logindata) && ($logindata['role'] == 'user' || $logindata['role'] == 'admin' || $logindata['role'] == 'moderator' )?  $logindata : false;    
    }
}


if ( ! function_exists('adminLoggedIn'))
{  
    function adminLoggedIn()
    {
        $CI = &get_instance();
        $logindata = $CI->session->userdata('admindata');
        return is_array($logindata) && ($logindata['role'] == 'admin' || $logindata['role'] == 'moderator')?  $logindata : false;    
    }
}

if ( ! function_exists('moderatorLoggedIn'))
{  
    function moderatorLoggedIn()
    {
        $CI = &get_instance();
        $logindata = $CI->session->userdata('admindata');
        return is_array($logindata) && $logindata['role'] == 'moderator' ?  $logindata : false;    
    }
}


if ( ! function_exists('isLoggedIn'))
{  
    function isLoggedIn()
    {
        $CI = &get_instance();
        $logindata = $CI->session->userdata('logindata');
        return is_array($logindata) ?  $logindata : false;    
    }
}               