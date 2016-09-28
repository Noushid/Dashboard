<?php
/**
 * Created by PhpStorm.
 * User: noushi
 * Date: 18/7/16
 * Time: 5:07 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'controllers/Check_Logged.php');

class Dashboard_Controller extends Check_Logged
{
    public function __construct()
    {
        parent::__construct();

        $this->load->helper(['path']);
        $this->load->helper(['form', 'url', 'captcha', 'string', 'html','menu']);
        $this->load->library(['form_validation', 'encryption', 'table', 'image_lib']);

//        $this->load->model('User_Model');
        $this->load->model('Employee_Model', 'team');
//        $this->load->model('Portfolio_files_Model', 'portfolio_files');
//        $this->load->model('Portfolio_Model', 'portfolio');
//        $this->load->model('Testimonial_model');
//        $this->load->model('Gallery_Model', 'gallery');
//        $this->load->model('Gallery_Files_Model', 'gallery_files');

    }

/////////*test*////////////////////////////////////////////

    public function generate_key()
    {
//        $key = bin2hex($this->encryption->create_key(16));
//        var_dump($key);
        var_dump(hash('sha256', 'admin'));

    }

    public function test()
    {
        $this->load->view('testroute');
    }

    public function test_key()
    {
        echo set_realpath('img.png');
//        $text = 'admin';
//        $en_text = $this->encryption->encrypt($text);
//
//        var_dump($en_text);
//        $d_text = $this->encryption->decrypt($en_text);
//        var_dump($d_text);
    }


///////////////////////////////////////////////////////////


    public function index()
    {
        /*if ($this->logged === TRUE) {
            redirect(base_url('dashboard'));
        } else {
            $data['currentPage'] = 'login';
            $data['image'] = $this->_create_captcha();
            $this->load->view('admin/login', $data);
        }*/


        $this->load->view('templates/dashboard');
    }

    public function get_employees()
    {
        $data = $this->team->select();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}