<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
*  Model Bands
*/
class Bands extends MY_Model {
    
     protected $fields = array();
     
     protected $search_distance = 300; // km 
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
             $this->fields = array_merge($this->fields, array('genre'));
         }
     }
     
     /**
     * Get single band
     * 
     * @param numeruc $id
     * @return array or false
     */
     public function get($id, $onlyActive = true)
     {
         $where['id'] = $id;
         if ($onlyActive && !is_array(adminLoggedIn())) {
             $where['active'] = 1;
         }
         $res = $this->db->get_where('bands', $where)->result_array();
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
                $data['photo'] = base_url() . 'assets/images/' . THEME . '/nophoto.png';   
             } 
             return $data;
         }
         return false;
     }
     
     
     /**
     * Get account by id criteria
     * 
     * @param numeric $id
     * @return array or false
     */
     public function getAccount($id)
     {
         $res = $this->db->get_where('bands', array('id' => $id))->result_array();
         if (count($res) > 0) {
             return $res[0];
         }
         return false;
     }
     
     
     /**
     * Get band by seo criteria
     * 
     * @param string $seo
     * @return array or false
     */
     public function getBySEO($seo)
     {  
         $where['seo'] = $seo; 
         if (!is_array(adminLoggedIn())) {
            $where['active'] = 1;
         }
         $res = $this->db->get_where('bands', $where)->result_array();
         if (count($res) > 0) {
             return $this->get($res[0]['id']);
         }
         return false;
     }
     
     /**
     * Get band by email criteria
     * 
     * @param string $email
     * @return array
     */
     public function getByEmail($email)
     {
         $res = $this->db->get_where('bands', array('email' => $email))->result_array();
         if (count($res) > 0) {
             return $this->get($res[0]['id']);
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
         $where = array();
         $where_or = array();
         $order_by = array();
         $search = '';
         if (isset($data['sort'])) {
             if(is_array($data['sort'])) {
                 foreach($data['sort'] as $v) {
                     $order_by[$v] = 'asc';
                 }
             } else {
                 $by = 'asc';
                 if ($data['sort'] == 'fanbase' || $data['sort'] == 'create_date') {
                     $by = 'desc';
                 } 
                 $order_by[$data['sort']] = $by;
             }
         }
         if (isset($data['show'])) {
             if (!is_array($data['show'])) {
                $where[$data['show']] = 1; 
             }
         }
         $where = array_merge($where, array('active' => 1));
         $page = isset($data['p']) ? $data['p'] : 1;
         $perpage = $this->config->item('perpage');
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
            $data        = array();
            $images      = array();
            $genres      = array();
            $tracks      = array();
            $videos      = array();
            $tags        = array();
            $disnance    = '';
            
            $geo_position = $this->session->userdata('geo_position');
            if ($geo_position) {
                $distance = 'round(glength(linestringfromwkb(linestring(POINT(' . $geo_position['lat'] . ', ' . $geo_position['lng'] . '), loc)))) * 100 AS dist';
            }
            
            // SELECT
            $this->db->select('bands.* '.(empty($distance) ? '' : (',' . $distance)), FALSE)
                     ->from('bands')
                     ->join('band_tracks', 'band_tracks.band_id = bands.id', 'left')
                     ->join('band_images', 'band_images.band_id = bands.id', 'left')
                     ->join('band_videos', 'band_videos.band_id = bands.id', 'left')
                     ->join('band_genres', 'band_genres.band_id = bands.id', 'left')
                     ->join('band_tags',   'band_tags.band_id   = bands.id', 'left')
                     ->join('tags', 'tags.id = band_tags.tag_id', 'left');
            // WHERE
            $this->_makeWhere($where, $where_or, $search);
            
            $allowedFieds = array('genre', 'name', 'price', 'featured', 'fanbase', 'create_date');
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
            
            // HAVING
            if ($geo_position) {
                $this->db->having('dist <=', $this->search_distance);
            }
            
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
                $row['images'] = array();
                $row['tracks'] = array();
                $row['videos'] = array();
                $row['genres'] = array();
                $photo = APPPATH . '../uploads/bands/' . $row['id']. '/photo/photo_t.jpg';
                if (file_exists($photo)) {
                    $row['photo'] = base_url().'uploads/bands/' . $row['id'] . '/photo/photo_t.jpg';
                } else {
                    $row['photo'] = base_url() . 'assets/images/'.THEME.'/nophoto.png';   
                } 
            
                $data[$row['id']] = $row;
            }
            
            if (count($ids) > 0) {
                // images
                $images = $this->db->select()
                               ->from('band_images')
                               ->where_in('band_id', $ids)
                               ->get()->result_array();                      
                foreach($images as $image) {
                    $data[$image['band_id']]['images'][] = $image['image'];
                }
                // genres
                $genres = $this->db->select()
                               ->from('band_genres')
                               ->where_in('band_id', $ids)
                               ->get()->result_array();
                foreach($genres as $genre) {
                    $data[$genre['band_id']]['genres'][] = $genre['genre'];
                }
                // tracks
                $tracks = $this->db->select()
                               ->from('band_tracks')
                               ->where_in('band_id', $ids)
                               ->get()->result_array();
                foreach($tracks as $track) {
                    $data[$track['band_id']]['tracks'][] = array('title' => $track['title'], 'file' => $track['file']);
                }
                // videos
                $videos = $this->db->select()
                               ->from('band_videos')
                               ->where_in('band_id', $ids)
                               ->get()->result_array();
                foreach($videos as $video) {
                    $data[$video['band_id']]['videos'][] = array('title' => $video['title'], 'file' => $video['file']);
                }
            
                // tags
                $tags = $this->db->select('tags.tag, band_tags.band_id')
                               ->from('band_tags')
                               ->join('tags', 'tags.id = band_tags.tag_id', 'left')
                               ->where_in('band_tags.band_id', $ids)
                               ->get()->result_array();
                foreach($tags as $tag) {
                    $data[$tag['band_id']]['tags'][] = $tag['tag'];
                }
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
            $distance = '';
            $having   = '';
            $geo_position = $this->session->userdata('geo_position');
            if ($geo_position) {
                $distance = 'round(glength(linestringfromwkb(linestring(POINT(' . $geo_position['lat'] . ', ' . $geo_position['lng'] . '), loc)))) * 100 AS dist';
                $having   = "HAVING dist <= " .$this->search_distance;
            }
            $where = $this->_makeWhere($where, $where_or, $search, true); 
            $query = $this->db->query("SELECT COUNT(total) AS total FROM (
                                SELECT COUNT(bands.id) AS total " .(empty($distance) ? '' : (',' . $distance)) . " FROM bands
                                LEFT JOIN band_tracks ON band_tracks.band_id = bands.id
                                LEFT JOIN band_images ON band_images.band_id = bands.id
                                LEFT JOIN band_videos ON band_videos.band_id = bands.id
                                LEFT JOIN band_genres ON band_genres.band_id = bands.id " . $where . "  
                                GROUP BY bands.id
                                $having
                              ) AS temp");
            if ($query->num_rows() > 0) {
                $row = $query->row_array();
                return $row['total'];
            } else {
                return 0;
            }
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
        
        $res = $this->db->get_where('bands', array('email' => $data['email']))->result_array();
        if (count($res) > 0) {
            return $res[0];
        }
        $res = $this->db->where('email', $data['email'])
                        ->from('users')
                        ->count_all_results();
        if ($res) {
            return 0;
        }
        $data = $this->_validate($data);
        $data['active'] = 0;
        $data['create_date'] = date('Y-m-d H:i:s');
        $seoExists = false;
        if (isset($data['name'])) {
            $data['seo'] = toSeoString($data['name']);
            $seoExists = $this->db->where('seo', $data['seo'])
                                  ->from('bands')
                                  ->count_all_results();
        }
          
        if (isset($data['lat']) && isset($data['lng'])) {
            $this->db->set('loc', "POINT(" . $data['lat']. "," . $data['lng'] . ")", false);   
        } else {
            $this->db->set('loc', "POINT(0,0)", false);
        }
        
        $this->db->insert('bands', $data);
        if ($seoExists) {
            $id = $this->db->insert_id();
            $data['seo'] .= "-" . $id;
            $this->db->where('id', $id)
                     ->update('bands', array('seo' => $data['seo']));
        }
        return $this->db->insert_id();
     }
     
     /**
     * Activate account 
     * 
     * @param numeric $id
     */
     public function activateAccount($id)
     {
         $this->db->where('id', $id);
         $this->db->update('bands', array('active' => 1));
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
              if (isset($data['lat']) && isset($data['lng'])) {
                    $this->db->set('loc', "POINT(" . (float)$data['lat']. "," . (float)$data['lng'] . ")", false);   
              } else if (isset($data['lat']) || isset($data['lng'])){
                    $band = $this->getAccount($id);
                    if (!$band) {
                        return false;
                    }
                    isset($data['lat']) ?  $this->db->set('loc', "POINT(" . (float)$data['lat'] . "," . $band['lng'] . ")", false) : $this->db->set('loc', "POINT(" . $band['lat'] . "," . (float)$data['lng'] . ")", false);
              }
              
              $this->db->where('id', $id)
                       ->update('bands', $data);
              return true; 
          }
          return false;
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
         return $validDate;
     }
     
}


/* End of file bands.php */
/* Location: ./application/modules/band/models/bands.php */