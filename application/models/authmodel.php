<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Authmodel Class
*/
class Authmodel extends CI_Model
{
    /**
    * Authenticate
    * 
    * @param string $email     - email
    * @param string $password  - password
    * @param string $role      - role ('admin', 'user', 'band')
    * @param string $table     - database table (users or bands)
    * @return array or false
    */
    public function authenticate($email, $password, $role = "user", $table = "users")
    {
        $email    = trim($email);
        $password = trim($password);
        $query = $this->db->query("SHOW TABLES LIKE '" . $table . "'");
        if ($query->num_rows() == 0) {
            trigger_error('Table ' . $table . ' is not found in DB');
            exit();
        }
        $this->db->select()
                 ->from($table)
                 ->where('email = ' . $this->db->escape($email) . ' AND (password = ' . $this->db->escape(sha1(md5($password))) .' OR password = ' . $this->db->escape($password) . ')' . ( $role != 'band' ? (" AND role = " . $this->db->escape($role)) : '') );
        $res = $this->db->get()->result_array();
        return count($res) > 0 ? $res[0] : false;        
    }
    
    /**
    * Checks if account already exists and returns its data, otherwise false
    * 
    * @param string $email
    * @return array or false
    */
    public function checkAccount($email)
    {
        $res = $this->db->get_where('users', array('email' => $email))->result_array();
        if (count($res) > 0) {
            return $res[0];
        }
        $res = $this->db->get_where('bands', array('email' => $email))->result_array();
        if (count($res) > 0) {
            return $res[0];
        }
        return false;
    }
    
    /**
    * Set credentials into session
    * 
    * @param array $data
    */
    public function setCredentials($data)
    {
         if (!isset($data['id']) && !isset($data['email'])) return;
          
         $logindata = array(
            'id'    => $data['id'],
            'name'  => isset($data['first_name']) && isset($data['last_name']) ? ($data['first_name'] . ' ' . $data['last_name']) : (isset($data['name']) ? $data['name'] : ''),
            'email' => $data['email'],
            'role'  => isset($data['role']) ? $data['role'] : ''
         );
         $this->session->set_userdata('logindata', $logindata);
    }
    
    
}

/* End of file authmodel.php */
/* Location: ./application/models/authmodel.php */