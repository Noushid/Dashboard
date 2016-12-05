<?php
/**
 * Created by PhpStorm.
 * User: noushi
 * Date: 20/7/16
 * Time: 3:20 PM
 */
defined('BASEPATH') or exit('No direct script access allowed');

class Employees_Controller extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->model('Employee_Model', 'employee');
    }

    public function index()
    {
        $this->load->view('templates/employees');

    }

    public function get_employees()
    {
        $data = $this->employee->select();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

}