<?php
/**
 * Created by PhpStorm.
 * User: noushi
 * Date: 23/9/16
 * Time: 2:34 PM
 */
defined('BASEPATH') or exit('No direct script Access allowed');

class Portfolio_Controller extends CI_Controller
{
    function  __construct()
    {
        parent::__construct();

        $this->load->model('Portfolio_Model', 'portfolio');

    }

    public function index()
    {
        $this->load->view('templates/portfolios');
    }

    public function store()
    {
        $request= json_decode(file_get_contents('php://input'), TRUE);

        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('type', 'Type', 'required');

        if ($this->form_validation->run() == FALSE) {
            var_dump('error');
        } else {
            /*$name = $this->input->post('name');
            $type = $this->input->post('type');

            $data = [
                'name' => $name,
                'type' => $type
            ];*/
            if ($this->portfolio->add($request)) {
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $error = ['error' => 'Server Down'];
                $this->output->set_content_type('application/json')->set_output(json_encode($error));
            }
        }
    }

    public function get()
    {
        $data = $this->portfolio->select();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}