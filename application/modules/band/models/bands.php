<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
*  Model Bands
*/
class Bands extends CI_Model {
    
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
             if ($row->Field == 'id' || $row->Field == 'description' || $row->Field == 'password' || $row->Field == 'email'  || $row->Field == 'session' || $row->Field == 'active' || $row->Field == 'temp') continue;
             $this->fields[] = $row->Field;
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
         $res = $this->db->get_where('bands', array('id' => $id, 'active' => 1, 'temp' => 0))->result_array();
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
             return $data;
         }
         return false;
     }
     
     
     /**
     * Get band by seo criteria
     * 
     * @param string $seo
     * @return array
     */
     public function getBySEO($seo)
     {
         $res = $this->db->get_where('bands', array('seo' => $seo, 'active' => 1, 'temp' => 0))->result_array();
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
                 if ($data['sort'] == 'fanbase') {
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
         $where = array_merge($where, array('active' => 1, 'temp' => 0));
         $page = isset($data['p']) ? $data['p'] : 1;
         $perpage = $this->config->item('perpage');
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
                     ->from('bands')
                     ->join('band_tracks', 'band_tracks.band_id = bands.id', 'left')
                     ->join('band_images', 'band_images.band_id = bands.id', 'left')
                     ->join('band_videos', 'band_videos.band_id = bands.id', 'left')
                     ->join('band_genres', 'band_genres.band_id = bands.id', 'left')
                     ->join('band_tags',   'band_tags.band_id   = bands.id', 'left')
                     ->join('tags', 'tags.id = band_tags.tag_id', 'left');
            // WHERE
            $this->_makeWhere($where, $where_or, $search);
            
            $allowedFieds = array_merge($this->fields, array('genre'));
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
                $row['images'] = array();
                $row['tracks'] = array();
                $row['videos'] = array();
                $row['genres'] = array();
                $photo = APPPATH . '../uploads/bands/' . $row['id']. '/photo/photo_t.jpg';
                if (file_exists($photo)) {
                    $row['photo'] = base_url().'uploads/bands/' . $row['id'] . '/photo/photo_t.jpg';
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
            $where = $this->_makeWhere($where, $where_or, $search, true);
            $this->db->from('(SELECT bands.*, genre FROM bands
                              LEFT JOIN band_tracks ON band_tracks.band_id = bands.id
                              LEFT JOIN band_images ON band_images.band_id = bands.id
                              LEFT JOIN band_videos ON band_videos.band_id = bands.id
                              LEFT JOIN band_genres ON band_genres.band_id = bands.id ' . $where . '  
                              GROUP BY bands.id) AS bands', false);
            return $this->db->count_all_results();
     }
     
     /**
     * Get temporary account using current session_id
     * 
     * @return array or false
     */
     public function getTempAccount()
     {
         $session_id = $this->session->userdata('session_id');
         if ($session_id) {
             $res = $this->db->get_where('bands', array('session' => $session_id))->result_array();
             if ($res) {
                 return $this->get($res[0]['id']);
             }
         }
         return false;
     }
     
     /**
     * Create temporary account useing session Id
     * 
     * @return array;
     */
     public function createTempAccount()
     {
         $session_id = $this->session->userdata('session_id');
         if ($session_id) {
             $res = $this->db->get_where('bands', array('session' => $session_id))->result_array();
             if ($res) {
                 return $this->get($res[0]['id']);
             }
             $this->db->insert('bands', array('session' => $session_id));
             return $this->get($this->db->insert_id());
         } else {
             trigger_error('session_id is not exists as session var');
         } 
         return false;
     }
     
     /**
     * Activate temporary account (this is final step in the registration process)
     * 
     * @param numeric $id
     */
     public function activateTempAccount($id)
     {
         $this->db->where('id', $id);
         $this->db->update('bands', array('temp' => 0));
     }
     
     
     
     /**
     * Make where clause for Active Records or build sql where string
     * 
     * @param array $where       -  clause with AND , if value is array then values of this array treated as where_in clause
     * @param array $where_or    -  clause with OR
     * @param mixed $search      -  search pattern , can be either string or array 
     */
     protected function _makeWhere($where = array(), $where_or = array(), $search = '', $output = false)
     {
            $whereAND    = array();
            $whereOR     = array();
            $where_in    = array();
            $where_like  = array();
            $allowedFieds = array_merge($this->fields, array('genre'));
            
            // where clause
            if (is_array($where)) {
                foreach($where as $k => $v) {
                    if (in_array($k, $allowedFieds)) {
                        if (is_array($v)) {
                            $where_in[$k] = $v;
                            continue; 
                        }
                        $whereAND[$k] = $v;
                    }
                }
                if (count($whereAND) > 0) {
                    $this->db->where($whereAND);
                }
            }
            
            // where clause with OR
            if (is_array($where_or)) {
                foreach($where_or as $k => $set) {
                    if (is_array($set) && count($set) > 0) {
                        $temp = array();
                        foreach($set as $v ) {
                            $temp[] = $k . ' = ' . $this->db->escape($v);   
                        }
                        $whereOR[] = "(" . implode(' OR ', $temp) . ")";
                    }
                }
                if (count($whereOR) > 0) {
                    foreach($whereOR as $w) {
                        $this->db->where($w, NULL, false);
                    }
                }
            }
            
            // search
            if (!empty($search)) {
                if (is_array($search) && count($search) > 0) {
                    foreach($search as $field=>$val) {
                        if (in_array($field, $allowedFieds) && !empty($val)) {
                            $where_like[] = $field . ' LIKE ' . $this->db->escape('%'.$val.'%') ; 
                        }
                    }
                } else {
                    foreach($allowedFieds as $field) {
                        $where_like[] = $field . " LIKE '%" . $this->db->escape_like_str($search) . "%'" ; 
                    }
                }
                if (count($where_like) > 0 ) {
                    $this->db->where('(' . implode(' OR ', $where_like) . ')', NULL, false);
                }            
            }
            
            $result = '';
            if ($output) {
                // generate where string from ar cache
                if (count($this->db->ar_where) > 0 OR count($this->db->ar_like) > 0){
                    $result .= "\nWHERE ";
                }
                
                $result .= implode("\n", $this->db->ar_where);
                
                if (count($this->db->ar_like) > 0)
                {
                    if (count($this->db->ar_where) > 0)
                    {
                        $result .= "\nAND ";
                    }

                    $result .= implode("\n", $this->dbar_like);
                }
                // empty ar cache
                $this->db->flush_cache();
            }
            return $result;
     }
     
}


/* End of file bands.php */
/* Location: ./application/modules/band/models/bands.php */