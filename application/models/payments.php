<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
*  Model Payments
*/
class Payments extends CI_Model {

     protected $fields = null;
     /**
     * Constructor
     *    
     */
     public function __construct()
     {
         parent::__construct();
     }
     
     public function getCreditCard($userId)
     {
         $res =$this->db->get_where('cards', array('user_id' => $userId))->result_array();
         if (count($res) > 0) { 
             $res = $res[0];
             list($res['expire_year'], $res['expire_month']) = explode('-', $res['expiration']); 
             return $res; 
         }
         return false;
     }
     
     public function saveCreditCard($userId, $data)
     {
         $userId = (int)$userId;
         $query = $this->db->query('SHOW COLUMNS FROM `cards`');
         $r = $query->result();
         $this->fields = array();
         foreach($r as $row) {
             if ($row->Field == 'id' || $row->Field == 'user_id') continue;
             $this->fields[] = $row->Field;
         }
          
         $data = $this->_validate($data);
         
         if (count($data) == 0 || !$userId) {
             return false;
         }
         $user = $this->db->where('user_id', $userId)
                          ->from('cards')
                          ->count_all_results();              
         if ($user) {
             // update
             $this->db->where('user_id', $userId)
                      ->update('cards', $data);
             return $this->db->affected_rows();
         } else {
             // create
             $data['user_id'] = $userId;
             $this->db->insert('cards', $data);
             return $this->db->insert_id();
         }
     }
     
     /**
     * Validate data
     *     
     * @param array $data
     * @return array
     */
     protected function _validate($data)
     {
         $validDate = array();
         if (is_array($data)) {
             if (isset($data['expire_month']) && isset($data['expire_year'])) {
                 $m = (int)$data['expire_month'];
                 $y = (int)$data['expire_year'];
                 if (preg_match('/\d{1,2}/', $m) && preg_match('/\d{4}/', $y)) {
                     $data['expiration'] = $y . '-' . ($m > 9 ? $m : ('0' . $m)) . '-01'; 
                 }
             }   
             foreach($data as $field => $val) {
                 if (in_array($field, $this->fields)) {
                     $validDate[$field] = trim(strip_tags($val));
                 }
             }
         }
         return $validDate;
     }
     
}

/* End of file payments.php */
/* Location: ./application/models/payments.php */