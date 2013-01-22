<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
*  Model Tags
*/
class Tags extends MY_Model {
     /**
     * Max characters in tag name
     *     
     * @var numeric
     */
     protected $tagLength = 50;
     
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
         $this->fields = array('tag');
     
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
            $this->db->select()
                     ->from('tags');
            
            // WHERE
            $this->_makeWhere($where, $where_or, $search);
            
            $allowedFieds = array('tag', 'id');
            // ORDER BY
            if (is_array($order_by)) {
                foreach($order_by as $k => $v) {
                    if (in_array($k, $allowedFieds)) {
                        $this->db->order_by($k, preg_match('/^desc$/i', $v) ? $v : 'ASC');
                    }
                }
            } else {
                $this->db->order_by('tag', 'ASC');
            }
            
            // LIMIT  (pagination) 
            $page    = (int)$page ? (int)$page : 1;
            $perpage = (int)$perpage;     
            if ($perpage) {
                $this->db->limit($perpage, $page * $perpage - $perpage);
            } 
           
            // FETCH
            $res = $this->db->get()->result_array(); 
            return $res;                                
     }
     
     /**
     * Save data
     * 
     * @param numeric $bandId
     * @param string $tagsString
     */
     public function save($bandId, $tagsString)
     {
         $saveTags = array();
         
         $this->db->delete('band_tags', array('band_id' => $bandId));
         
         // split string into tags
         $tags = explode(',', $tagsString);
         foreach($tags as $i => $tag) {
             $tag = trim( strip_tags($tag) );
             if (empty($tag) || strlen($tag) > $this->tagLength) continue;
             $saveTags[] = $tag;
         }
         
         // save tags
         foreach($saveTags as $tag) {
              $tagId = $this->getIdByTag($tag);
              if ($tagId) {
                  $this->db->insert('band_tags', array(
                        'band_id' => $bandId, 
                        'tag_id'  => $tagId)
                  );
              } else {
                  $this->db->insert('tags', array('tag' => $tag));
                  $this->db->insert('band_tags', array(
                        'band_id' => $bandId, 
                        'tag_id'  => $this->db->insert_id())
                  );
              }   
         }
         return true;
     }
     
     /**
     * Get tag Id by tag name
     * 
     * @param string $tag
     * @return numeric or false 
     */
     public function getIdByTag($tag)
     {
         $res = $this->db->get_where('tags', array('tag' => $tag))->result_array();
         return count($res) > 0 ? $res[0]['id'] : false; 
     }
     
}


/* End of file tags.php */
/* Location: ./application/modules/band/models/tags.php */