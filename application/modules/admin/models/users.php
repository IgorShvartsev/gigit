<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Users
{
    public function __construct()
    {
        
    }
    
    public function isAdmin()
    {
        return true;
    }
}