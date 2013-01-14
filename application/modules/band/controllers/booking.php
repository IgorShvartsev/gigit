<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*  Booking Controller
*/
class Booking extends MX_Controller_Public {
    /**
    * Logged In user data
    * 
    * @var array
    */
    protected $userdata = null;
    
    /**
    * Constructor
    * 
    */
    public function __construct()
    {
        $this->load->model('bands');
        $this->load->model('bookings');
        $this->userdata = userLoggedIn();
        parent::__construct();    
    }
    
    /**
    * Default action
    * 
    * @param string $seo
    */
	public function index($seo = '')
	{
        if (empty($seo) || !$this->userdata) {
		    redirect('band/browse');
            exit();
        }
        $data['band'] = $this->bands->getBySEO($seo);
        if (!$data['band']) {
            redirect('err/404');
        }
        
        $this->_postdata();
        
        $data['states'] = $this->config->item('states');
        
        $this->load->view('booking', $data);
	}
    
    /**
    * Confirm action
    * 
    */
    public function confirm()
    {
        if ($this->input->post('code')) {
            $data = $this->bookings->getByCode($this->input->post('code'));
            if(!$data) redirect();
            $this->_makeConfirmation($data);
            $this->load->view('booking/thanks');
        } else {
             $code = $this->input->get('code');
             if (!$code) {
                redirect();
                exit();
             }
            $data['booking'] = $this->bookings->getByCode($code);
            $this->load->view('booking/confirm', $data);
        } 
    }
    
    
    /**
    * Check form data
    * 
    */
    protected function _postdata()
    {
        $data = $this->input->post('data');
        if (!is_array($data)) {
            return;
        }
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<small class="error">', '</small>')
                              ->set_rules('data[gig_date]',   'Gig date', 'trim|required|xss_clean')
                              ->set_rules('data[start_time]', 'Start time', 'trim|required|max_length[10]|xss_clean')
                              ->set_rules('data[end_time]',   'End time', 'trim|required|max_length[10]|xss_clean')
                              ->set_rules('data[street1]',    'Street', 'trim|required|max_length[50]|xss_clean')
                              ->set_rules('data[city]',       'City', 'trim|required|max_length[30]|xss_clean')
                              ->set_rules('data[state]',      'State', 'trim|required|max_length[30]|xss_clean')
                              ->set_rules('data[zip]',        'ZIP', 'trim|required|max_length[10]|xss_clean')
                              ->set_rules('data[venue_type]', 'Venue Type', 'required')
                              ->set_rules('data[location]',   'Location', 'required')
                              ->set_rules('data[amp_request]', 'Amplification request', 'required')
                              ->set_rules('data[answer1]',    'Answer 1', 'trim|xss_clean')
                              ->set_rules('data[answer2]',    'Answer 2', 'trim|xss_clean')
                              ->set_rules('data[note]',       'Note', 'trim|xss_clean');
                              //->set_message('required', 'is required.');
        if ($this->form_validation->run() == FALSE) {
            return;
        }
        $code = md5(uniqid($this->userdata['id']));
        if (($id = $this->bookings->create(array_merge($data, array('user_id' => $this->userdata['id'], 'code' => $code)))) > 0) {
            $this->load->library('email');
            $band = $this->bands->getAccount($data['band_id']);
            if ($band) {
                 // SEND MAIL TO FAN
                $subject = "Gig Booking #".$id;
                $this->email->from($this->config->item('site_email'), $this->config->item('site_name'))
                            ->to($this->userdata['email'])
                            ->subject($subject)
                            ->message($this->load->view('mails/booking/booking_to_fan', 
                                                          array_merge($data, array(
                                                                'name'     => $band['name'], 
                                                                'id'       => $id
                                                                )
                                                          ), true))
                            ->send();
                // SEND MAIL TO BAND
                $subject = "Gig Booking #".$id;
                $this->email->from($this->config->item('site_email'), $this->config->item('site_name'))
                            ->to($band['email'])
                            ->subject($subject)
                            ->message($this->load->view('mails/booking/booking_to_band', 
                                                          array_merge($data, array(
                                                                'name'     => $band['name'], 
                                                                'fullname' => $this->userdata['first_name'] . ' '  . $this->userdata['lastname'],
                                                                'code'     => $code,
                                                                'id'       => $id
                                                                )
                                                          ), true))
                            ->send();
                 // SEND MAIL TO ADMIN
                 $subject = "New Gig booking #" . $id . ' has been made';
                 $this->email->from($this->config->item('site_email'), $this->config->item('site_name'))
                             ->to($this->config->item('admin_email'))
                             ->subject($subject)
                             ->message($this->load->view('mails/booking/booking_to_admin', 
                                                         array_merge($data, array(
                                                                'name'     => $band['name'], 
                                                                'fullname' => $this->userdata['first_name'] . ' '  . $this->userdata['lastname'],
                                                                'id'       => $id
                                                         )), true))
                             ->send();
            }
            redirect('payment');
        }
    }
    
    /**
    * Saves confirmation status and sends email
    * 
    * @param array $data
    */
    protected function _makeConfirmation($data)
    {
        $this->load->library('email');
        // SEND MAIL TO FAN
        $subject = "Confirmation of Gig booking from ". $data['name'];
        $this->email->from($this->config->item('site_email'), $this->config->item('site_name'))
                    ->to($data['user_email'])
                    ->subject($subject)
                    ->message($this->load->view('mails/booking/confirmation_to_fan', $data, true))
                    ->send();
        // SEND MAIL TO BAND
        $subject = "Booking confirmation";
        $this->email->from($this->config->item('site_email'), $this->config->item('site_name'))
                    ->to($data['band_email'])
                    ->subject($subject)
                    ->message($this->load->view('mails/booking/confirmation_to_band', $data, true))
                    ->send();
        // SEND MAIL TO ADMIN
        $subject = "Booking #" . $data['id'] . ' has been confirmed by ' . $data['name'];
        $this->email->from($this->config->item('site_email'), $this->config->item('site_name'))
                    ->to($this->config->item('admin_email'))
                    ->subject($subject)
                    ->message($this->load->view('mails/booking/confirmation_to_admin', $data, true))
                    ->send();
        // SAVE CONFIRMATION STATUS
        $saveData = array(
            'status'       => BOOKINGS::STATUS_CONFIRMED,
            'confirm_date' => date('Y-m-d H:i:s')
        );
        $this->bookings->save($data['id'], $saveData);            
    }
     
}
 
/* End of file booking.php */
/* Location: ./application/modules/band/controllers/booking.php */ 