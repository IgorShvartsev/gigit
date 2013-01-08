<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
*  Model Calendar
*/
class Calendar extends CI_Model {
    /**
    * Table allowed fields
    * 
    * @var array
    */
    protected $fields = array('busy_date', 'active');
    
    /**
    * Get calendar data by id and some data as `month` and `year` in array  
    * 
    * @param numeric $id  -  band Id
    * @param array $data
    * @return array
    */
    public function getbydata($id, $data)
    {
        if (!isset($data['month']) || !preg_match('/\d{1,2}/', $data['month'])) {
            $data['month'] = date('n');            
        }
        if (!isset($data['year']) || !preg_match('/\d{4}/', $data['year']) ) {
            $data['year'] = date('Y');            
        }
        $date = $data['year'] . '-' . (strlen($data['month']) < 2 ? ('0' . $data['month']) : $data['month']) . '-01';
        
        $datetime = new DateTime($date);
        $thisMonth = $datetime->format('F Y');
        
        $datetime = new DateTime($date);
        $datetime->sub(new DateInterval('P1M'));
        $prevDate = $datetime->format('Y-m-01');
        
        $datetime = new DateTime($date);
        $datetime->add(new DateInterval('P1M'));
        $nextDate = $datetime->format('Y-m-31');
        
        $this->db->select('busy_date')
                 ->from('band_calendars')
                 ->where('band_id', $id)
                 ->where('busy_date >=', $prevDate)
                 ->where('busy_date <=', $nextDate)
                 ->where('active', 1);
        $res = $this->db->get()->result_array();

        $busyData = array();
        foreach($res as $row) {
            $busyData[] = $row['busy_date'];
        }
        $this->load->library('busycalendar', $data);
        return array(
            'month'   => $thisMonth,
            'weeks'   => $this->busycalendar->weeks($busyData),
            'days'    => $this->busycalendar->days(),
            'previous'=> $this->busycalendar->prev_month_url(),
            'next'    => $this->busycalendar->next_month_url()
        );
    }
    
    /**
    * Save data
    * 
    * @param numeric $id
    * @param array $data
    */
    public function save($id, $data)
    {
        $this->_tidy($data);
        if (isset($data['busy_date'])) {
            $r = $this->db->where('band_id', $id)
                          ->where('busy_date', $data['busy_date'])
                          ->from('band_calendars')
                          ->count_all_results();
            if (!$r) {
                $data['band_id'] = $id;
                $this->db->insert('band_calendars', $data);
            } else {
                $this->db->where('band_id', $id)
                         ->where('busy_date', $data['busy_date'])
                         ->update('band_calendars', $data);
            }
            return array('result' => 'OK');
        } else {
            return array('error' => 'busy_date is not defined');
        }
    }
    
    /**
    * Tity input data
    * 
    * @param array $data - data reference
    */
    protected function _tidy(&$data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        foreach($data as $field => $value) {
            if (!in_array($field, $this->fields)) {
                unset($data[$field]);
            }
        }
    }
}

/* End of file calendar.php */
/* Location: ./application/modules/band/models/calendar.php */