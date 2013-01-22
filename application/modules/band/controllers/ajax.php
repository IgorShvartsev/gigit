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
    * Auto complete 
    * 
    */
    public function autocomplete($model = '')
    {
        $data = array();
        $listLimit = 12;
        $term = $this->input->get_post('term');
        $list = array();
        if (strlen($term) >= 2) {
            if ($model == 'tags') {
                $this->load->model('tags');
                $list = $this->tags->getData(null, null, null, 1, $listLimit, array('tag' => $term));
                foreach($list as $i => $row) {
                    $data[] = array(
                        'i' => $i,
                        'label' => $row['tag'],
                        'value' => $row['tag']
                    );
                } 
            }
        }
       $this->output->set_content_type('application/json')  
                    ->set_output(json_encode($data));
    }
    
    
    /**
    * Get datepicker widget
    * 
    */
    public function getdatepicker()
    {
        $id = (int) $this->input->get('id');
        $data = array(
            'band'          => array('id' => $id),
            'inputName'     => str_replace(array("'", '"'), "", strip_tags($this->input->get('iname'))),
            'modelCalendar' => $this->encrypt->encode('calendar', $this->sessionId)
        );
        $this->load->view('forms/datepicker', $data);
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
                if ($name == 'tracks' && $this->session->userdata('soundcloud')) {
                    $data['soundcloud'] = true;
                }
                $this->load->view('forms/' .$name, $data);
            } else if (file_exists(APPPATH.'modules/band/views/forms/names.php')) {
                $this->load->view('forms/names', $data);
            } 
            return;
        } 
        echo 'Failed';
    }
    
    /**
    * Submitting form
    * 
    */
    public function submitform()
    {
        $loggedBand = bandLoggedIn();
        $action = $this->input->post('action');
        if ($loggedBand) {
            if ($action) {
                $error = '';
                if ($this->_validateForm($error)) {
                    $data = $this->_saveFormData($loggedBand['id'], $action, $this->input->post('data'));
                } else {
                    $data['error'] = $error;
                }
            } else {
                $data['error'] = 'Action is not defined'; 
            }
        } else {
            $data['error'] = 'Forbidden';
        }
        $this->output->set_content_type('application/json')  
                     ->set_output(json_encode($data));
    }
    
    /**
    * Validate post data  of submitted form
    * 
    */
    protected function _validateForm(&$error)
    {
        $this->load->library('form_validation');
        
        $data = $this->input->post('data');
        !element('name', $data, NULL)        || $this->form_validation->set_rules('data[name]',  'Band name', 'trim|required|max_length[50]');
        !element('description', $data, NULL) || $this->form_validation->set_rules('data[description]', 'Band description', 'trim|max_length[500]');
        !element('zip', $data, NULL)         || $this->form_validation->set_rules('data[zip]', 'ZIP', 'trim|required|numeric|max_length[8]|');
        !element('distance', $data, NULL)    || $this->form_validation->set_rules('data[distance]', 'Distance', 'trim|numeric|max_length[5]|');
        !element('tags', $data, NULL)        || $this->form_validation->set_rules('data[tags]', 'Tags', 'trim');
        !element('genres', $data, NULL)      || $this->form_validation->set_rules('data[genres]', 'Genres', 'trim');
        
        if ($this->form_validation->run() == FALSE) {
            $error = strip_tags(validation_errors());
            return empty($error);
        }
        
        return true;
    }
    
    /**
    * Save form data 
    * 
    * @param numeric $id     - id of Band
    * @param string $action  - what action should be done to save form data
    * @param array $data     - form data
    * @return array
    */
    protected function _saveFormData($id, $action, $data) 
    {
        if (!is_array($data) || count($data) == 0 ) {
            return array('error' => 'Incorrect data');
        }
        $this->load->model('bands');
        switch($action) {
            case 'names':
                $res = $this->bands->save($id, $data);
                break;
            case 'address':
                $this->load->library('geocode');
                $data = $this->input->post('data');
                $res = $this->geocode->getByZip(trim($data['zip']));
                if (isset($res['error'])) {
                    return $res;
                }
                $res = $this->bands->save($id, array_merge($data, $res['result']));
                break;
            case 'footprint':
                $data = $this->input->post('data');
                if ($data) {
                    // save genres
                    $genreIds = isset($data['genres']) && is_array($data['genres']) ? $data['genres'] : array();
                    $this->load->model('genres');
                    $this->genres->save($id, $genreIds);
                    // save tags
                    $this->load->model('tags');
                    $this->tags->save($id, isset($data['tags']) ? $data['tags'] : '');
                    $res = true;
                } else {
                    $res = false;
                }
                break;
            case 'tracks':
                $data = $this->input->post('data');
                $this->load->model('tracks');
                if (isset($data['track']) && is_array($data['track'])) {
                    foreach($data['track'] as $track) {
                        $track = unserialize(base64_decode($track));
                        if(is_array($track)) {
                            $this->tracks->save($id, $track);
                        }
                    }
                } else {
                    $this->tracks->deleteSoundcloudTracks($id);
                } 
                $res = true;
                break;
        }
        return $res ? array('result' => 'Saved') : array('error' => 'Incorrect data');     
    }
}

/* End of file ajax.php */
/* Location: ./application/modules/band/controllers/ajax.php */