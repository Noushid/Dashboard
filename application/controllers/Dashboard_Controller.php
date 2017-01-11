<?php
/**
 * Created by PhpStorm.
 * User: noushi
 * Date: 18/7/16
 * Time: 5:07 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'core/Check_Logged.php');

class Dashboard_Controller extends Check_Logged
{
    public function __construct()
    {
        parent::__construct();

        $this->load->helper(['path']);
        $this->load->helper(['form', 'url', 'captcha', 'string', 'html','menu']);
        $this->load->library(['form_validation', 'encryption', 'table', 'image_lib']);
        $this->load->model('User_Model', 'user');

        if ( ! $this->logged)
        {
            // Allow some methods?
            $allowed = array(
                'verify'
            );
            if ( ! in_array($this->router->fetch_method(), $allowed))
            {
                redirect(base_url('login'));
            }
        }
    }

/////////*test*////////////////////////////////////////////

    public function generate_key($str)
    {
//        $key = bin2hex($this->encryption->create_key(16));
//        var_dump($key);
        var_dump(hash('sha256', $str));

    }

///////////////////////////////////////////////////////////





    public function logout()
    {
        $this->session->unset_userdata('logged_in');
        session_destroy();
        redirect(base_url('login'), 'refresh');

    }

    public function verify()
    {
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        if ($this->form_validation->run()    == FALSE) {
            $this->output->set_status_header(400, 'Validation error');
            $this->output->set_content_type('application/json')->set_output(json_decode(validation_errors()));
        } else {
            $username = $this->input->post('username');
            $password = hash('sha256', $this->input->post('password'));
            $where = [
                'username' => $username,
                'password' => $password
            ];
            $result = $this->user->get($where);
            if ($result) {
                $login_data = [
                    'username' => $result[0]->username,
                    'logged' => true,
                ];
                $this->session->set_userdata('logged_in', $login_data);
                $this->output->set_content_type('application/json')->set_output(json_encode($login_data));
            } else {
                $this->output->set_status_header(400, 'Unauthorised access');
                $this->output->set_content_type('application/json')->set_output(json_encode(['error' => 'invalid username or password']));
            }
        }
    }

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

}