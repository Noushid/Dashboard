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


    public function get()
    {
        $data = $this->portfolio->select();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function store()
    {
        $_POST = json_decode(file_get_contents('php://input'), TRUE);

        var_dump($_POST);
        if (move_uploaded_file($_POST['desktop'][0]['url'],$_POST['desktop'][0]['name'])) {
            var_dump('success');
        }else{
            var_dump('errror');
        }



        /*$this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('type', 'Type', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->output->set_status_header(400,'Validation Error');
            $this->output->set_content_type('application/json')->set_output(json_encode(validation_errors()));
        } else {
            if ($this->portfolio->add($_POST)) {
                $this->output->set_content_type('application/json')->set_output(json_encode($_POST));
            } else {
                $error = ['error' => "Sorry, Can't Process your Request \n Try again later"];
                $this->output->set_status_header(500, 'Server Down.');
                $this->output->set_content_type('application/json')->set_output(json_encode($error));
            }
        }*/
    }

    public function edit_record()
    {
        $_POST = json_decode(file_get_contents('php://input'), TRUE);

        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('type', 'Type', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->output->set_status_header(400, 'Validation Error');
            $this->output->set_content_type('application/json')->set_output(json_encode(validation_errors()));
        } else {

            if ($this->portfolio->edit($_POST,$_POST['id'])) {
                $this->output->set_content_type('application/json')->set_output(json_encode($_POST));
            } else {
                $error = ['error' => "Sorry, Can't Process your Request \n Try again later"];
                $this->output->set_status_header(500, 'Server Down.');
                $this->output->set_content_type('application/json')->set_output(json_encode($error));
            }
        }
    }

    public function delete($id)
    {
        if(!$this->portfolio->get(['id' => $id])){
            $data = 'Record not found!';
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            if ($this->portfolio->remove($id)) {
                $data = 'Record Deleted!';
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        }
    }
}