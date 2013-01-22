<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
*  Model Tracks
*/
class Tracks extends CI_Model {
     
     /**
     * Table fields
     * 
     * @var array
     */
     
     protected $fields = null;
     /**
     * Constructor
     *    
     */
     public function __construct()
     {
         parent::__construct();
         $query = $this->db->query('SHOW COLUMNS FROM `band_tracks`');
         $r = $query->result();
         foreach($r as $row) {
             if ($row->Field == 'id') continue;
             $this->fields[] = $row->Field;
         }
     }
     
     /**
     * Get soundcloud tracks of target band
     * 
     * @param numeric $bandId
     * @return array
     */
     public function getSoundcloudTracks($bandId)
     {
         $res = $this->db->select()
                         ->from('band_tracks')
                         ->where('band_id', $bandId)
                         ->where('soundcloud_id IS NOT NULL', NULL, FALSE)
                         ->get()->result_array();
         return $res;
     }
     
     /**
     * Save data
     * 
     * @param numeric $bandId
     * @param string $data
     */
     public function save($bandId, $data)
     {
         $data = $this->_validate($data);
         if (isset($data['soundcloud_id'])) {
             $total = $this->db->where('soundcloud_id', $data['soundcloud_id'])
                               ->from('band_tracks')
                               ->count_all_results();
             if ($total) {
                 $this->db->where('soundcloud_id', $data['soundcloud_id'])
                          ->update('band_tracks', $data);
             } else {
                 $data['band_id'] = $bandId;
                 $this->db->insert('band_tracks', $data);
             }                 
             return true;
         } else {
             $this->db->query("DELETE FROM `band_tracks` WHERE `band_id` = " . $this->db->escape($bandId) . " AND soundcloud_id IS NOT NULL");
         }
         return true;
     }
     
     /**
     * Delete Soundcloud tracks
     * 
     * @param numeric $bandId
     */
     public function deleteSoundcloudTracks($bandId)
     {
         $this->db->query("DELETE FROM `band_tracks` WHERE `band_id` = " . $this->db->escape($bandId) . " AND soundcloud_id IS NOT NULL");
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


/* End of file tracks.php */
/* Location: ./application/modules/band/models/tracks.php */