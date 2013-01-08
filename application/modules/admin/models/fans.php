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
             if ($row->Field == 'id') continue;
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
            return -1;
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
     * Get all fans
     * 
     * @param array $data
     * @param numeric $total
     * @return array
     */
     public function getAll($data, &$total = 0 )
     {
         $order_by = array();
    
         $order_by['id'] = 'desc';
         $where   = isset($data['where']) ? $data['where'] : array();
         $where_or= isset($data['where_or']) ? $data['where_or'] : array();
         $page    = isset($data['p']) ? $data['p'] : 1;
         $perpage = isset($data['perpage']) ? $data['perpage'] : 25;
         $search  = isset($data['search']) ? $data['search'] : '';
         
         $where['role'] = 'user';
         
         $total = $this->getTotal($where, $where_or, $search);

         return $this->getData($where, $where_or, $order_by, $page, $perpage, $search);
     }
     
     /**
     * Get all pages due to criteria what exactly is needed
     * 
     * @param numeric $what
     * @return array
     */
     public function getData($where = array(), $where_or = array(), $order_by = array(), $page = 1, $perpage = 25, $search = '')
     {
            $data        = array();
            $images      = array();
            $genres      = array();
            $tracks      = array();
            $videos      = array();
            $tags        = array();
            
            // SELECT
            $this->db->select('users.*')
                     ->from('users');
            // WHERE
            $this->_makeWhere($where, $where_or, $search);
            
            $allowedFieds = array('id', 'first_name', 'last_name');
            // ORDER BY
            if (is_array($order_by)) {
                foreach($order_by as $k => $v) {
                    if (in_array($k, $allowedFieds)) {
                        $this->db->order_by($k, preg_match('/^desc$/i', $v) ? $v : 'ASC');
                    }
                }
            } else {
                $this->db->order_by('users.id','DESC');
            }         
            
            // GROUP BY 
            $this->db->group_by('users.id');
            
            // LIMIT  (pagination) 
            $page    = (int)$page ? (int)$page : 1;
            $perpage = (int)$perpage;     
            if ($perpage) {
                $this->db->limit($perpage, $page * $perpage - $perpage);
            } 

           
            // FETCH
            $res = $this->db->get()->result_array();
            $ids = array();
            foreach($res as $row) {
                $ids[] = $row['id'];
                $photo = APPPATH . '../uploads/fans/' . $row['id']. '/photo/photo_t.jpg';
                if (file_exists($photo)) {
                    $row['photo'] = base_url().'uploads/bands/' . $row['id'] . '/photo/photo_t.jpg';
                }
            
                $data[$row['id']] = $row;
            }
            return $data;                                
     }
     
     /**
     * Get total records due to search/where criteria
     * 
     * @param array $where
     * @param array $where_or
     * @param mixed $serach
     */
     public function getTotal($where = array(), $where_or = array(), $search = '')
     {
            $where = $this->_makeWhere($where, $where_or, $search, true);
            $this->db->from('(SELECT * FROM users ' . $where . '  
                              GROUP BY users.id) AS users', false);
            return $this->db->count_all_results();
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
             if (isset($data['email'])) {
                 $this->load->library('form_validation');
                 if ($this->form_validation->valid_email($data['email'])) {
                     $res = $this->db->where('email', $data['email'])
                                     ->where('id !=', $id)
                                     ->from('users')
                                     ->count_all_results();
                     if ($res) {
                         return array('error' => 'Account already exists. Try another email');
                     }
                     $res = $this->db->where('email', $data['email'])
                                     ->from('bands')
                                     ->count_all_results();
                     if ($res) {
                         return array('error' => 'Account already exists. Try another email');
                     }
                 } else {
                     return array('error' => 'Invalid email');
                 }  
             }
             $this->db->where('id', $id)
                      ->update('users', $data);
             return array('result' => $this->get($id));  
         } else {
             return array('error' => 'Wrong data');
         }
     }
     
     /**
     * Create account (for ajax)
     * 
     * @param array $data
     * @return array
     */
     public function create($data)
     {
         $res = $this->createAccount($data);
         if (!$res) {
             return array('error' => 'Email is not defined');
         } elseif (is_array($res) || $res == -1) {
             return array('error' => 'Account already exists. Try another email');
         } else {
             return array('result' => $this->get($res));
         }
     }
     
      /**
     * Activate
     * 
     * @param numeric $id
     */
     public function activate($id, $value)
     {
         $this->db->where('id', $id);
         $this->db->update('users', array('active' => (int)$value));
     }
     
     /**
     * Delete account
     * 
     * @param numeric $id
     */
     public function delete($id)
     {
         $this->db->delete('users', array('id' => $id));
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