<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends MX_Controller{
 
  protected $model = null;
  
  protected $perpage = 25;
    
  public function __construct()
  {
      $this->layout->disable_layout();
      parent::__construct();
      $this->model   = $this->input->get_post('m');
      $this->section = $this->input->get_post('s');  
  }  
    
  public function index()
  {
      redirect('admin');
  }
  
  public function get()
  {
      if (!empty($this->model) && !empty($this->section)) {
          $model = $this->model;
          $this->load->model($model);
          $id = (int)$this->input->get('id');
          $result = $this->$model->get($id);
          if ($result || (!$result && !$id)) {
            $result['model']   = $this->model;
            $result['section'] = $this->section;  
            $data['content'] = $this->load->view($this->section.'/edit', $result, true);
          } else {
            $data['error'] = 'Not found';  
          }
      } else {
          $data['error'] = 'Model and section params are not defined';
      }
      echo json_encode($data);
  }
  
  public function getall()
  {  
     $total = 0; 
     if (!empty($this->model) && !empty($this->section)) {
        
        $model = $this->model;
        $this->load->model($model);
        
        $data = $this->input->get_post('data');
        $data = is_array($data) ? $data : array();
        $data['p'] = (int)$this->input->get_post('p') ? (int)$this->input->get_post('p') : 1;
        $data['perpage']  = $this->perpage;
  
        $result = $this->$model->getAll($data, $total);
        $content = '';
        foreach($result as $row) {
            $content .= $this->load->view($this->section.'/row', $row, true);
        }
        $data = array(
            'content'    => $content,
            'pagination' => $this->load->view('admin/pagination', array_merge($data, array('total' => $total)), true)
        );
     } else {
         $data['error'] = 'Model and section params are not defined';
     }
     echo json_encode($data);
  }
  
  /**
  * Save data
  * 
  */
  public function save()
  {
      if (!empty($this->model) && !empty($this->section)) {
          $model = $this->model;
          $this->load->model($model);
          $id = (int)$this->input->post('id');
          $saveData = $this->input->post('data');
          if (is_array($saveData)) {
              $method = $id ? 'save' : 'create';
              if (method_exists($this->model, $method)) {
                  $data = $method == 'save' ? $this->$model->save($id, $saveData) : $this->$model->create($saveData);
                  if (isset($data['result'])) {
                      $data['content'] = $this->load->view($this->section.'/row', $data['result'], true);
                  }
              } else {
                  $data['error'] = 'Method ' . $method . ' not exists in model '. $model; 
              }
          } else {
              $data['error'] = 'Data is not an array';
          }
      } else {
          $data['error'] = 'Model and section params are not defined';
      }
      echo json_encode($data);
  }
  
  public function send()
  {
      
  }
  
  public function activate()
  {
     $data = array(); 
     if (!empty($this->model) && !empty($this->section)) {
          $model = $this->model;
          $this->load->model($model);
          if (method_exists($this->model, 'activate')) {
                $chk = $this->input->post('chk');
                if (is_array($chk)) {
                    foreach($chk as $id) {
                        $this->$model->activate($id, 1);
                        $data['result'][] = $id;                    }
                } else {
                    $data['error'] = 'Checkbox data is not an array';
                }
          } else {
              $data['error'] = "Method `activate` not exists in model ". $this->model;
          }
     } else {
          $data['error'] = 'Model and section params are not defined';
     }
     echo json_encode($data);
  }
  
  public function deactivate()
  {
     $data = array(); 
     if (!empty($this->model) && !empty($this->section)) {
          $model = $this->model;
          $this->load->model($model);
          if (method_exists($this->model, 'activate')) {
                $chk = $this->input->post('chk');
                if (is_array($chk)) {
                    foreach($chk as $id) {
                        $this->$model->activate($id, 0);
                        $data['result'][] = $id; 
                    }
                } else {
                    $data['error'] = 'Checkbox data is not an array';
                }
          } else {
              $data['error'] = "Method `activate` not exists in model ". $this->model;
          }
     } else {
          $data['error'] = 'Model and section params are not defined';
     }
     echo json_encode($data);
  }
  
  public function delete()
  {
     if (!empty($this->model) && !empty($this->section)) {
        $model = $this->model;
          $this->load->model($model);
          if (method_exists($this->model, 'delete')) {
               $chk = $this->input->post('chk');
               if (is_array($chk)) {
                    foreach($chk as $id) {
                        $this->$model->delete($id);
                        $data['result'][] = $id; 
                    }
               } else {
                    $data['error'] = 'Checkbox data is not an array';
               }
          } else {
                $data['error'] ="Method `delete` not exists in model ". $this->model; 
          }
     } else {
          $data['error'] = 'Model and section params are not defined';
     }
     echo json_encode($data);
  }
}

/* End of file ajax.php */
/* Location: ./application/modules/controllers/admin/ajax.php */