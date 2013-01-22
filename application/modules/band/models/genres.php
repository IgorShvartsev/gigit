<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
*  Model Genres
*/
class Genres extends MY_Model {
    
     /**
     * Constructor
     *    
     */
     public function __construct()
     {
         parent::__construct();
     }
     
     /**
     * Save data
     * 
     * @param numeric $bandId
     * @param array $ids
     */
     public function save($bandId, $ids)
     {
          $genres = array();
          $res = $this->db->get('genres')->result_array();
          foreach($res as $row) {
              $genres[$row['id']] = $row['name'];
          }
          $this->db->delete('band_genres', array('band_id' => $bandId));
          foreach($ids as $id) {
                if (isset($genres[$id])) {
                    $this->db->insert('band_genres', array('band_id' => $bandId, 'genre' => $genres[$id]));
                }
          }
          return true;
     }
     
}


/* End of file genres.php */
/* Location: ./application/modules/band/models/genres.php */