<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
*  Model Bookings
*/
class Bookings extends MY_Model {
    
     const STATUS_NEED_RESPONSE = 0;
     const STATUS_REQUEST_SENT  = 0;
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
     * Get booking by id 
     *  
     * @param numeric $id
     * @return array or false
     */
     public function get($id)
     {
            $res = $this->db->get_where('bookings', array('id' => $id))->result_array();
            if (!count($res)) {
                return false;
            }
            
            $this->_completedStatus($res[0]);
            
            $this->db->select('bookings.*, 
                               bands.name,
                               bands.email AS band_email, 
                               bands.seo , 
                               users.first_name, 
                               users.last_name,
                               CONCAT(users.first_name, " ", users.last_name) AS fullname,
                               users.email AS user_email, 
                               DATE_FORMAT(bookings.gig_date, "%m/%d/%Y") AS gig_date', false)
                     ->from('bookings')
                     ->join('users', 'users.id = bookings.user_id', 'left')
                     ->join('bands', 'bands.id = bookings.band_id', 'left')
                     ->where('bookings.id', $id);
            $res = $this->db->get()->result_array();
            $this->_addStatusText($res);
            return $res[0]; 
     }
     
     
     /**
     * Get booking by code 
     * Only record with status = STATUS_NEED_RESPONSE will be returned
     *  
     * @param string $code
     * @return array or false
     */
     public function getByCode($code)
     {
            $this->db->select('bookings.*, 
                               bands.name,
                               bands.email AS band_email, 
                               bands.seo , 
                               users.first_name, 
                               users.last_name,
                               CONCAT(users.first_name, " ", users.last_name) AS fullname,
                               users.email AS user_email, 
                               DATE_FORMAT(bookings.gig_date, "%m/%d/%Y") AS gig_date', false)
                     ->from('bookings')
                     ->join('users', 'users.id = bookings.user_id', 'left')
                     ->join('bands', 'bands.id = bookings.band_id', 'left')
                     ->where('bookings.code', $code)
                     ->where('status', BOOKINGS::STATUS_NEED_RESPONSE);
            $res = $this->db->get()->result_array();
            if (count($res) > 0) { 
                $this->_addStatusText($res);
                $res = $res[0];
                return $res; 
            }
            return false;
     }
     
     /**
     * Get all records due to criteria what exactly is needed
     * 
     * @param numeric $what
     * @return array
     */
     public function getData($where = array(), $where_or = array(), $order_by = array(), $page = 1, $perpage = 25, $search = '')
     {
            
            $this->_completedStatus($where);
         
            // SELECT
            $this->db->select('bookings.*, 
                               bands.name,
                               bands.seo, 
                               bands.email AS band_email, 
                               users.first_name, 
                               users.last_name,
                               users.email AS user_email,
                               DATE_FORMAT(bookings.gig_date, "%m/%d/%Y") AS gig_date',false)
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
     * Create  booking record
     * 
     * @param array $data  -  data must contain at least  next fields: band_id, user_id, gig_date
     * @return numeric or false;
     */
     public function create($data)
     {
         if (isset($data['band_id']) && isset($data['user_id']) ) {
             $data['band_id'] = (int)$data['band_id'];
             $data['user_id'] = (int)$data['user_id'];
             if (!$data['band_id'] || !$data['user_id']) {
                 return false;
             }
         } else {
             return false;;
         }
         if (!isset($data['gig_date']) || empty($data['gig_date'])) {
             return false;
         }
         $data = $this->_validate($data);
         if (count($data) > 0) {
            $data['create_date'] = date('Y-m-d H:i:s');
            $this->db->insert('bookings', $data);
            return $this->db->insert_id();
         }
         return false;
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
             return true;
         }
         return false;
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
                     if ($field == 'gig_date') {
                         if (!preg_match('/\d{4}-\d{1,2}-\d{1,2}/', $val)) {
                                if (preg_match('/\d{1,2}-\d{1,2}-\d{4}/', $val)) {
                                    $val = preg_replace('/(\d{1,2})-(\d{1,2})-(\d{4})/', $this->americanTime ? '$3-$1-$2' : '$3-$2-$1', $val);
                                } else {
                                    $val = '';
                                }   
                         }
                     }
                     $validDate[$field] = trim(strip_tags($val));
                 }
             }
         }
         return $validDate;
     }
     
     /**
     * Add status text to the data
     * 
     * @param array $data - reference to data
     */
     protected function _addStatusText(&$data)
     {
          foreach($data as $i=>$row) {
                switch($row['status']) {
                    case BOOKINGS::STATUS_NEED_RESPONSE:
                    case BOOKINGS::STATUS_REQUEST_SENT:
                        $data[$i]['status_text'] = array(0 => "Gig request sent", 1 => "Needs response");
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
     
     /**
     * Checks and makes complete status if needed
     * 
     * @param array $data - where should be set either $data['user_id'] or $data['band_id']
     */
     protected function _completedStatus($data)
     {
         if (!isset($data['user_id']) && !isset($data['band_id'])) return;
         $where = isset($data['user_id']) ? (' `user_id` = ' . (int) $data['user_id'] )  : (' `band_id` = ' . (int)$data['band_id']);
         $where .= " AND `status` != " . $this->db->escape(BOOKINGS::STATUS_COMPLETED);
         $where .= " AND `confirm_date` != '0000-00-00 00:00:00'";
         $where .= " AND DATEDIFF(NOW(), `confirm_date`) >= 1";
         $this->db->query("UPDATE `bookings` SET `status` = " . $this->db->escape(BOOKINGS::STATUS_COMPLETED) . " WHERE " . $where );
     }
}

/* End of file bookings.php */
/* Location: ./application/modules/band/models/bookings.php */