<?php
/**
 * Created by PhpStorm.
 * User: psybo-03
 * Date: 19/12/16
 * Time: 4:44 PM
 */
defined('BASEPATH') or exit('No Direct script access allowed');

class Home extends CI_Controller
{
    protected $footer = 'templates/footer';
    protected $header = 'templates/header';

    function __construct()
    {
        parent::__construct();
        $this->load->model('Portfolio_Files_model', 'portfolio_file');
        $this->load->model('Employee_Model', 'team');
        $this->load->model('Testimonial_Model', 'testimonial');
        $this->load->model('Gallery_Files_Model', 'gallery_files');

        $this->load->library('session');
        $this->load->helper('download');

    }

    public function index($page = 'index')
    {
        $this->load->view($page);
        $this->load->view($this->footer);
    }

    public function login()
    {
        if ($this->session->userdata('logged_in') == TRUE) {
            redirect(base_url('admin'));
            exit;
        }
        $this->load->view('login');
    }
}