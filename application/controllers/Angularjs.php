<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Angularjs extends CI_Controller {


    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url'));
    }
    public function index()
    {
        $this->load->view('angularjs_view');
    }

    public function get_list() {
        $this->load->model(array('User_Model'));
        $data = $this->User_Model->angular_get();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));

    }
}

/* End of file Angularjs.php */
/* Location: ./application/controllers/Angularjs.php */
