<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
*  Model Pages
*/
class Pages extends CI_Model {
    
     const PAGES_VISIBLE_HIDDEN = -1;
     const PAGES_VISIBLE        = 0;
     const PAGES_HIDDEN         = 1; 
     
     /**
     * Constructor
     *    
     */
     public function __construct()
     {
         parent::__construct();
     }
     
     /**
     * Get single page
     * 
     * @param mixed $idseo    -   id or seo field  depending on $type
     * @param numeric $type   -   0 - id  or  1 - seo  is used 
     * @return array or false
     */
     public function get($idseo, $type = 0)
     {
         $where = $type ? array('seo' => $idseo) : array('id' => $idseo);
         $res = $this->db->get_where('pages', $where)->result_array();
         return count($res) > 0 ? $res[0] : false;
     }
     
     /**
     * Get all pages due to criteria what exactly is needed
     * 
     * @param numeric $what
     * @return array
     */
     public function getAll($what = self::PAGES_VISIBLE)
     {
         if ($what >= 0) {
             $this->db->where('hidden', $what == self::PAGES_VISIBLE ? self::PAGES_VISIBLE : self::PAGES_VISIBLE);
         }
         return $this->db->select()
                         ->from('pages')
                         ->order_by('order', 'ASC')
                         ->get()
                         ->result_array();
     }
     
}

/* End of file pages.php */
/* Location: ./application/models/pages.php */