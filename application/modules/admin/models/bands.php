<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
*  Model Bands
*/
class Bands extends MY_Model {
    
     protected $fields = array(); 
     /**
     * Constructor
     *    
     */
     public function __construct()
     {
         parent::__construct();
         $query = $this->db->query('SHOW COLUMNS FROM `bands`');
         $r = $query->result();
         foreach($r as $row) {
             if ($row->Field == 'id') continue;
             $this->fields[] = $row->Field;
             $this->fields = array_merge($this->fields);
         }
     }
     
     /**
     * Get single band
     * 
     * @param numeruc $id
     * @return array or false
     */
     public function get($id)
     {
         $res = $this->db->get_where('bands', array('id' => $id))->result_array();
         if (count($res) > 0) {
             $data = $res[0];
             $data['genres'] = array();
             $r = $this->db->get_where('band_genres', array('band_id' => $id))->result_array();
             foreach($r as $row) {
                 $data['genres'][] = $row['genre'];
             } 
             $data['images'] = array();
             $r = $this->db->get_where('band_images', array('band_id' => $id))->result_array();
             foreach($r as $row) {
                 $data['images'][] = $row['image'];
             } 
             $data['videos'] = array();
             $r = $this->db->get_where('band_videos', array('band_id' => $id))->result_array();
             foreach($r as $row) {
                 $data['videos'][] = array('title' => $row['title'], 'file' => $row['file']);
             }
             $data['tracks'] = array();
             $r = $this->db->get_where('band_tracks', array('band_id' => $id))->result_array();
             foreach($r as $row) {
                 $data['tracks'][] = array('title' => $row['title'], 'file' => $row['file']);
             }
             $data['tags'] = array();
             $r = $this->db->select('tags.tag')
                               ->from('band_tags')
                               ->join('tags', 'tags.id = band_tags.tag_id', 'left')
                               ->where('band_id', $id)
                               ->get()->result_array();
             foreach($r as $row) {
                $data['tags'][] = $row['tag'];
             }
             $photo = APPPATH . '../uploads/bands/' . $data['id']. '/photo/photo_t.jpg';
             if (file_exists($photo)) {
                $data['photo'] = base_url().'uploads/bands/' . $data['id'] . '/photo/photo_t.jpg';
             } else {
                $data['photo'] = base_url() . 'assets/admin/images/nophoto.png';   
             } 
             return $data;
         }
         return false;
     }
     
     /**
     * Get all bands
     * 
     * @param array $data
     * @param numeric $total
     * @return array
     */
     public function getAll($data, &$total = 0 )
     {
         $order_by = array();
         if (isset($data['sort'])) {
             if(is_array($data['sort'])) {
                 foreach($data['sort'] as $v) {
                     $order_by[$v] = 'asc';
                 }
             } else {
                 $by = 'asc';
                 if ($data['sort'] == 'fanbase') {
                     $by = 'desc';
                 }
                 $order_by[$data['sort']] = $by;
             }
         } else {
             $order_by['id'] = 'desc';
         }
         $where   = isset($data['where']) ? $data['where'] : array();
         $where_or= isset($data['where_or']) ? $data['where_or'] : array();
         $page    = isset($data['p']) ? $data['p'] : 1;
         $perpage = isset($data['perpage']) ? $data['perpage'] : 25;
         $search  = isset($data['search']) ? $data['search'] : '';

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
            $this->db->select('bands.*')
                     ->from('bands');
            // WHERE
            $this->_makeWhere($where, $where_or, $search);
            
            $allowedFieds = array('id', 'name', 'price', 'featured', 'fanbase');
            // ORDER BY
            if (is_array($order_by)) {
                foreach($order_by as $k => $v) {
                    if (in_array($k, $allowedFieds)) {
                        $this->db->order_by($k, preg_match('/^desc$/i', $v) ? $v : 'ASC');
                    }
                }
            } else {
                $this->db->order_by('bands.id','DESC');
            }         
            
            // GROUP BY 
            $this->db->group_by('bands.id');
            
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
                $photo = APPPATH . '../uploads/bands/' . $row['id']. '/photo/photo_t.jpg';
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
            $this->db->from('(SELECT * FROM bands ' . $where . '  
                              GROUP BY bands.id) AS bands', false);
            return $this->db->count_all_results();
     }
     
     /**
     * Get genre list
     * 
     * @return array
     */
     public function getGenres()
     {
         return $this->db->get('genres')->result_array();
     }
     
     /**
     * Save data
     * 
     * @param numeric $id
     * @param array $data
     * @return array
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
                                     ->from('bands')
                                     ->count_all_results();
                     if ($res) {
                         return array('error' => 'Account already exists. Try another email');
                     }
                     $res = $this->db->where('email', $data['email'])
                                     ->from('fans')
                                     ->count_all_results();
                     if ($res) {
                         return array('error' => 'Account already exists. Try another email');
                     }
                 } else {
                     return array('error' => 'Invalid email');
                 }  
             }
             if(isset($data['seo'])) {
                 $data['seo'] = empty($data['seo']) ? ('band-' . $id) : toSeoString($data['seo']);
                 $seoExists = $this->db->where('seo', $data['seo'])
                                       ->where('id !=', $id) 
                                       ->from('bands')
                                       ->count_all_results();
                 if ($seoExists) {
                     $data['seo'] .= '-' .$id;
                 }
             }
             $this->db->where('id', $id)
                      ->update('bands', $data);
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
     * Create account 
     * 
     * @return array;
     */
     public function createAccount($data)
     {
        if (!isset($data['email'])) {
            return 0;
        }
        $res = $this->db->get_where('bands', array('email' => $data['email']))->result_array();
        if (count($res) > 0) {
            return $res[0];
        }
        $res = $this->db->where('email', $data['email'])
                        ->from('users')
                        ->count_all_results();
                
        if ($res) {
            return -1;
        }
        
        $data = $this->_validate($data);
        $data['active'] = 0;
        $data['create_date'] = date('Y-m-d H:i:s');
        $seoExists = false;
        if (isset($data['name']) && !isset($data['seo'])) {
            $data['seo'] = toSeoString($data['name']);
            $seoExists = $this->db->where('seo', $data['seo'])
                                  ->from('bands')
                                  ->count_all_results();
        }
        $this->db->insert('bands', $data);
        $id = $this->db->insert_id();
        if ($seoExists) {
            $data['seo'] .= "-" . $id;
            $this->db->where('id', $id)
                     ->update('bands', array('seo' => $data['seo']));
        }
        return $id;
     }
     
     /**
     * Activate
     * 
     * @param numeric $id
     */
     public function activate($id, $value)
     {
         $this->db->where('id', $id);
         $this->db->update('bands', array('active' => (int)$value));
     }
     
     /**
     * Delete account
     * 
     * @param numeric $id
     */
     public function delete($id)
     {
         $this->db->delete('bands', array('id' => $id));
         $this->db->delete('band_genres', array('band_id' => $id));
         $this->db->delete('band_tags', array('band_id' => $id));
         $this->db->delete('band_images', array('band_id' => $id));
         $this->db->delete('band_videos', array('band_id' => $id));
         $this->db->delete('band_tracks', array('band_id' => $id));
     }
     
     
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
         if (isset($validDate['password'])) {
             if (!empty($validDate['password'])) {
                $validDate['password'] = sha1(md5($validDate['password']));
             } else {
                 unset($validDate['password']);
             }
         }
         if (isset($validDate['description'])) {
            $validDate['description'] = str_replace("\n", "<br />", $validDate['description']);
         }
         return $validDate;
     }
     
}


/* End of file bands.php */
/* Location: ./application/modules/admin/models/bands.php */