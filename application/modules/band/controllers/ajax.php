<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Ajax class
*/
class Ajax extends MX_Controller {
    
    protected $modelPath = null;
    
    /**
    * Constructor
    * 
    */
    public function __construct()
    {
        $this->layout->disable_layout();
        $this->modelPath = APPPATH.'modules/band/models/'; 
        $this->sessionId = $this->session->userdata('session_id');  
    }
    
    /**
    * Index. Redirects to home page
    * 
    */
	public function index()
	{
		redirect('band/browse');
	}
    
    /**
    * Model get single record action by Id
    * 
    * @param numeric $id
    */
    public function getone($id = 0) 
    {
       $data = array(); 
       $model = $this->encrypt->decode($this->input->get('m'), $this->session->userdata('session_id'));
       if (!empty($model) && file_exists($this->modelPath.$model.'.php')) {
            $this->load->model($model);
            $data = $this->$model->get($id);
       } else {
           $data['error'] = 'Action failed';
       }
       $this->output->set_content_type('application/json')  
                    ->set_output(json_encode($data));
    }
    
    /**
    * Model get action by data 
    *  
    * Get params id - numeric,  data - array
    */
    public function getdata() 
    {
       $data = array();
       $id   = $this->input->get('id');
       $data = $this->input->get('data'); 
       $model = $this->encrypt->decode($this->input->get('m'), $this->session->userdata('session_id'));
       if (!empty($model) && file_exists($this->modelPath.$model.'.php')) {
            $this->load->model($model);
            $data = $this->$model->getbydata($id, $data);
       } else {
           $data['error'] = 'Action failed';
       }
       $this->output->set_content_type('application/json')  
                    ->set_output(json_encode($data));
    }
    
    /**
    * Model save data action
    * 
    * Get params  id - numeric, data - array
    */
    public function save()
    {
       $data = array();
       $id   = $this->input->post('id',true);
       $data = $this->input->post('data');
       $loggedBand = bandLoggedIn();
       $model = $this->encrypt->decode($this->input->post('m'), $this->session->userdata('session_id'));
       if ($loggedBand && $loggedBand['id'] == $id) {
            if (!empty($model) && file_exists($this->modelPath.$model.'.php')) {
                 $action = $this->input->post('action');
                 $this->load->model($model);
                 $data = $this->$model->save($id, $data, $action);
            } else {
                $data['error'] = 'Action failed';
            }
       } else {
           $data['error'] = 'Access denied';
       }
       $this->output->set_content_type('application/json')  
                    ->set_output(json_encode($data));
    }
    
    /**
    * Model delete action 
    * 
    */
    public function delete()
    {
       $data = array(); 
       $model = $this->encrypt->decode($this->input->post('m'), $this->session->userdata('session_id'));
       if (!empty($model) && file_exists($this->modelPath.$model.'.php')) {
            $id = $this->input->post('id',true);
            $data = $this->input->post('data');
            $this->load->model($model);
            $data = $this->$model->delete($id, $data);
       } else {
            $data['error'] = 'Action failed';
       }
       $this->output->set_content_type('application/json')  
                    ->set_output(json_encode($data));
    }
    
    /**
    * Model sort action
    * 
    */
    public function sort()
    { 
       $model = $this->encrypt->decode($this->input->post('m'), $this->session->userdata('session_id'));
       if (!empty($model) && file_exists($this->modelPath.$model.'.php')) {
            $sort_id = $this->input->post('s');
            $this->load->model($model);
            if (is_array($sort_id))
            {
                foreach($sort_id as $idx=>$id)
                {
                    $this->$model->savesort( $id, $idx + 100 );
                }
            }
       }
    }
    
    /**
    * Get form
    * 
    * @param string $name - name of the form
    */
    public function form($name = '')
    {
        $loggedBand = bandLoggedIn();
        if ($loggedBand) {
            $this->load->model('bands');
            $data = array(
                'band'   => $this->bands->get($loggedBand['id'], false),
                'genres' => $this->bands->getGenres(),
                'modelCalendar' => $this->encrypt->encode('calendar', $this->sessionId) 
            );
            if (file_exists(APPPATH.'modules/band/views/forms/'.$name.'.php')) {
                $this->load->view('forms/' .$name, $data);
            } else if (file_exists(APPPATH.'modules/band/views/forms/names.php')) {
                $this->load->view('forms/names', $data);
            } 
            return;
        } 
        echo 'Failed';
    } 
}

/* End of file ajax.php */
/* Location: ./application/modules/band/controllers/ajax.php */