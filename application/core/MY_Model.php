<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class MY_Model extends CI_Model 
{
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
            $allowedFieds = isset($this->fields) ? $this->fields : array();
            
            // where clause
            if (is_array($where)) {
                foreach($where as $k => $v) {
                    if ( !in_array($k, $allowedFieds)) {
                        continue;
                    }    
                    if (is_array($v)) {
                        $where_in[$k] = $v;
                        continue; 
                    }
                    $whereAND[$k] = $v;
                }
                if (count($whereAND) > 0) {
                    $this->db->where($whereAND);
                }
            }
            
            // where clause with OR
            if (is_array($where_or)) {
                foreach($where_or as $k => $set) {
                    if ( !in_array($k, $allowedFieds)) {
                        continue;
                    }    
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