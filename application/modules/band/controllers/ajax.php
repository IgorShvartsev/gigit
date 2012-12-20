<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends MX_Controller {
    
    protected $modelPath = null;
    
    public function __construct()
    {
        $this->layout->disable_layout();
        $this->modelPath = APPPATH.'modules/band/models/';   
    }
    
	public function index()
	{
		redirect('band/browse');
	}
    
    public function getone($id = 0) 
    {
       $data = array(); 
       $model = $this->encrypt->decode($this->input->get('m'), $this->session->userdata('session_id'));
       if (!empty($model) && file_exists($this->modelPath.$model)) {
            $this->load->model($model);
            $data = $this->$model->get($id);
       } else {
           $data['error'] = 'Action failed';
       }
       $this->output
            ->set_content_type('application/json')  
            ->set_output(json_encode($data));
    }
    
    /**
    * Model save data action
    * 
    */
    public function save()
    {
       $data = array(); 
       $model = $this->encrypt->decode($this->input->post('m'), $this->session->userdata('session_id'));
       if (!empty($model) && file_exists($this->modelPath.$model)) {
            $id = $this->input->post('id',true);
            $data = $this->input->post('data');
            $action = $this->input->post('action');
            $this->load->model($model);
            $data = $this->$model->save($id, $data, $action);
       } else {
            $data['error'] = 'Action failed';
       }
       $this->output
             ->set_content_type('application/json')  
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
       if (!empty($model) && file_exists($this->modelPath.$model)) {
            $id = $this->input->post('id',true);
            $data = $this->input->post('data');
            $this->load->model($model);
            $data = $this->$model->delete($id, $data);
       } else {
            $data['error'] = 'Action failed';
       }
       $this->output
             ->set_content_type('application/json')  
             ->set_output(json_encode($data));
    }
    
    /**
    * Model sort action
    * 
    */
    public function sort()
    { 
       $model = $this->encrypt->decode($this->input->post('m'), $this->session->userdata('session_id'));
       if (!empty($model) && file_exists($this->modelPath.$model)) {
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
}

/* End of file ajax.php */
/* Location: ./application/modules/band/controllers/ajax.php */