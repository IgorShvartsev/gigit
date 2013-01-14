<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
*  Model Bookings
*/
class Bookings extends MY_Model {
    
     const STATUS_NEED_RESPONSE = 0;
     const STATUS_CONFIRMED     = 1;
     const STATUS_COMPLETED     = 2;
     const STATUS_CANCELED      = 3;
     const STATUS_REJECTED      = 4;
    
     protected $fields = array(); 
     
     protected $americanTime = true;
     /**
     * Constructor
     *    
     */
     public function __construct()
     {
         parent::__construct();
         $query = $this->db->query('SHOW COLUMNS FROM `bookings`');
         $r = $query->result();
         foreach($r as $row) {
             if ($row->Field == 'id') continue;
             $this->fields[] = $row->Field;
         }
     }
     
     /**
     * Get 
     * 
     * @param numeric $id
     * @return array or false
     */
     public function get($id)
     {
            $this->db->select('bookings.*, 
                               bands.name, 
                               bands.seo , 
                               users.first_name, 
                               users.last_name, 
                               DATE_FORMAT(bookings.gig_date, "%m-%d-%Y") AS gig_date, 
                               DATE_FORMAT(bookings.create_date, "%m-%d-%Y %H:%i") AS create_date', false)
                     ->from('bookings')
                     ->join('users', 'users.id = bookings.user_id', 'left')
                     ->join('bands', 'bands.id = bookings.band_id', 'left')
                     ->where('bookings.id', $id);
            $res = $this->db->get()->result_array();
            if (count($res) > 0) { 
                $this->_addStatusText($res);
                $res = $res[0];
                return $res; 
            }
            return false;
     }
     
     /**
     * Get all bookings
     * 
     * @param array $data
     * @param numeric $total
     * @return array
     */
     public function getAll($data, &$total = 0 )
     {
         $order_by = array();
         $where    = isset($data['where']) ? $data['where'] : array();
         $where_or = isset($data['where_or']) ? $data['where_or'] : array();
         $page     = isset($data['p']) ? $data['p'] : 1;
         $perpage  = isset($data['perpage']) ? $data['perpage'] : 25;
         $search   = isset($data['search']) ? $data['search'] : '';
         if (!empty($search)) {
            foreach($this->fields as $i=>$field) {
                $this->fields[$i] = 'bookings.' . $field;
            }
         }
         $this->fields = array_merge( $this->fields, array('first_name', 'last_name', 'name') ); 
         
         $total = $this->getTotal($where, $where_or, $search);
         return $this->getData($where, $where_or, $order_by, $page, $perpage, $search);
     }
     
     /**
     * Get all records due to criteria what exactly is needed
     * 
     * @param numeric $what
     * @return array
     */
     public function getData($where = array(), $where_or = array(), $order_by = array(), $page = 1, $perpage = 25, $search = '')
     {
            // SELECT
            $this->db->select('bookings.*, 
                               bands.name, 
                               bands.seo , 
                               users.first_name, 
                               users.last_name, 
                               DATE_FORMAT(bookings.gig_date, "%m-%d-%Y") AS gig_date, 
                               DATE_FORMAT(bookings.create_date, "%m-%d-%Y %H:%i") AS create_date', false)
                     ->from('bookings')
                     ->join('users', 'users.id = bookings.user_id', 'left')
                     ->join('bands', 'bands.id = bookings.band_id', 'left');
                             
            // WHERE
            $this->_makeWhere($where, $where_or, $search);
            
            $allowedFieds = array('user_id', 'band_id', 'create_date');
            // ORDER BY
            if (is_array($order_by)) {
                foreach($order_by as $k => $v) {
                    if (in_array($k, $allowedFieds)) {
                        $this->db->order_by($k, preg_match('/^desc$/i', $v) ? $v : 'ASC');
                    }
                }
            } else {
                $this->db->order_by('bookings.id','DESC')
                         ->order_by('bookings.create_date', 'DESC');   
            }
            
            // LIMIT  (pagination) 
            $page    = (int)$page ? (int)$page : 1;
            $perpage = (int)$perpage;     
            if ($perpage) {
                $this->db->limit($perpage, $page * $perpage - $perpage);
            } 
           
            // FETCH
            $res = $this->db->get()->result_array();
           
            $this->_addStatusText($res);
            
            return $res; 
     }
     
     
     /**
     * Save data
     * 
     * @param numeric $id
     * @param array $data
     * @return boolean
     */
     public function save($id, $data) 
     {
         $data = $this->_validate($data);
         if (count($data) > 0) {
             $this->db->where('id', $id)
                      ->update('bookings', $data);
             return array('result' => $this->get($id));
         }
         return array('error' => 'Data empty');
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
            $this->db->from('bookings')
                     ->join('users', 'users.id = bookings.user_id', 'left')
                     ->join('bands', 'bands.id = bookings.band_id', 'left');
            return $this->db->count_all_results();
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
             foreach($data as $field => $val) {
                 if (in_array($field, $this->fields)) {
                     $validDate[$field] = trim(strip_tags($val));
                 }
             }
         }
         return $validDate;
     }
     
     
     protected function _addStatusText(&$data)
     {
          foreach($data as $i=>$row) {
                switch($row['status']) {
                    case BOOKINGS::STATUS_NEED_RESPONSE:
                        $data[$i]['status_text'] = "Needs response";
                        break;
                    case BOOKINGS::STATUS_CONFIRMED :
                        $data[$i]['status_text'] = "Confirmed";
                        break;
                    case BOOKINGS::STATUS_COMPLETED :
                        $data[$i]['status_text'] = "Completed";
                        break; 
                    case BOOKINGS::STATUS_CANCELED :
                        $data[$i]['status_text'] = "Canceled";
                        break;     
                    case BOOKINGS::STATUS_REJECTED :
                        $data[$i]['status_text'] = "Rejected";
                        break;
                    default:
                        $data[$i]['status_text'] = '';
                }
          }
     }
}

/* End of file bookings.php */
/* Location: ./application/modules/admin/models/bookings.php */