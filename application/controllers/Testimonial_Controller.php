<?php
/**
 * Created by PhpStorm.
 * User: psybo-03
 * Date: 19/12/16
 * Time: 1:33 PM
 */
defined('BASEPATH') or exit('No direct script access allowed');

class Testimonial_Controller extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->model('Testimonial_Model', 'testimonial');
        $this->load->model('File_Model', 'file');

    }

    public function index()
    {
        $this->load->view('templates/testimonial');
    }

    public function get_all()
    {
        $data = $this->testimonial->select();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));

    }
}