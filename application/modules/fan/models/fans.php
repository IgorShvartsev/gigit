<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
*  Model Fans
*/
class Fans extends MY_Model 
{
    
     protected $fields = array(); 
     /**
     * Constructor
     *    
     */
     public function __construct()
     {
         parent::__construct();
         $query = $this->db->query('SHOW COLUMNS FROM `users`');
         $r = $query->result();
         foreach($r as $row) {
             if ($row->Field == 'id' || $row->Field == 'role') continue;
             $this->fields[] = $row->Field;
         }
     }
     
     /**
     * Create account 
     * 
     * @param array $data
     * @return numeric  or  array or 0,  where 0 - error, array - data of already existed band, numeric - insert id
     */
     public function createAccount($data)
     {
        if (!isset($data['email'])) {
            return 0;
        }
        $res = $this->db->get_where('users', array('email' => $data['email']))->result_array();
        if (count($res) > 0) {
            return $res[0];
        }
        $res = $this->db->where('email', $data['email'])
                        ->from('bands')
                        ->count_all_results();
        if ($res) {
            return 0;
        }
        $data = $this->_validate($data);
        $data['create_date'] = $data['last_visit'] = date('Y-m-d H:i:s');
        $this->db->insert('users', $data);
        return $this->db->insert_id();
     }
     
     /**
     * Get fan account
     * 
     * @param numeruc $id
     * @return array or false
     */
     public function get($id)
     {
         $res = $this->db->get_where('users', array('id' => $id))->result_array();
         return count($res) > 0 ? $res[0] : false;
     }
     
     /**
     * Save data
     * 
     * @param numeric $id
     * @param array $data
     */
     public function save($id, $data)
     {
          $data = $this->_validate($data);
          if (count($data) > 0) {
              $this->db->where('id', $id)
                       ->update('users', $data); 
          }
     }
     
     /**
     * Validates data
     * 
     * @param array $data - var referece
     */
     protected function _validate($data)
     {
         $validDate = array();
         if (is_array($data)) {
             foreach($data as $field => $val) {
                 if (in_array($field, $this->fields)) {
                     $validDate[$field] = trim(strip_tags($val));
                 }
             }
         }
         return $validDate;
     }
} 
/* End of file fans.php */
/* Location: ./application/modules/fans/models/fans.php */    