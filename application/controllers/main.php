<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends MX_Controller_Public {

	public function index()
	{
        //$this->layout->disable_layout();
		$this->load->view('main');
	}
    
    public function static_page($page)
    {
        $this->load->model('pages');
        $staticPage = $this->pages->get($page, true);
        if ($staticPage) {
            $data = array(
                'page'  => $page,
                'title' => ''
            );
            $this->load->view('static_page', $data);
        } else {
            redirect('err/404');
        }
    }
}

/* End of file main.php */
/* Location: ./application/controllers/main.php */