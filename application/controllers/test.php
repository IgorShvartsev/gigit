<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends MX_Controller {

    public function __construct()
    {
         $this->layout->disable_layout();
    }
    
	public function index()
	{ 
		echo 'test';
        $this->load->module('band');
        $this->load->model('bands');
        //print_r($this->bands->getData(null, null, null, 1, 3, 'ja'));
        //print_r($this->bands->get(1));
        echo $this->bands->getTotal(null, null, 'nora');
	}
    
    public function my()
    {
       $this->load->library('geocode');
       //print_r($this->geocode->getByZip('98370'));
       /*
        SELECT id, ( 6371 * acos( cos( radians(28) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(-82) ) + sin( radians(28) ) * sin( radians( lat ) ) ) ) AS distance FROM bands HAVING distance < 100 ORDER BY distance
       */
       
       $query = $this->db->query(" SELECT id, round(glength(linestringfromwkb
(linestring(POINT(30.2966026, -97.9701846 ), loc)))) * 100 AS distance , astext(loc) as loc FROM bands HAVING distance <= 1000000 ORDER BY distance");
       if($query->num_rows()) {
           foreach ($query->result_array() as $row)
           {
               echo $row['id'] .' '. $row['distance'] . ' ' . $row['loc'] .'<br />'; 
           }
       }
       /*
       $this->db->where('id', 7);
       $this->db->update('bands', array(
            'lat' => '31,85852',
            'lng' => '-97,49082'
       ));
       */
    }
    
    public function calendar()
    {
        //$this->load->library('busycalendar');
        //print_r($this->busycalendar->weeks());
        $this->load->model('band/calendar','','calendar');
        $data = $this->calendar->getdata(1, array());
        print_r($data);
    }
}

/* End of file test.php */
/* Location: ./application/controllers/test.php */