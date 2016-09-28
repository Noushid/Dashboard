<?php
/**
 * Created by PhpStorm.
 * User: noushi
 * Date: 18/7/16
 * Time: 5:10 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Verify extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->helper(['form', 'url', 'security']);
        $this->load->library(['form_validation', 'encryption','session']);
        $this->load->model('User_Model');

    }

    public function index()
    {
        $this->form_validation->set_rules('email','Email','required|trim|xss_clean');
        $this->form_validation->set_rules('password','Password','required|trim|xss_clean|callback_check_password');

        if ($this->form_validation->run() === FALSE) {
//            $data['error'] = 'incorrect Email or Password';
            $this->load->view('admin/login');
            // var_dump('error');
        } else {
            redirect(base_url('dashboard/dash'));
//            var_dump('success');
//            $this->load->view('admin/dashboard');
        }
    }

    /*login process*/

    public function check_password($password)
    {
        $email = $this->input->input_stream('email', TRUE);
        $password = hash('sha256', $password);

        $result = $this->User_Model->login($email,$password);
        if($result != null){
            foreach ($result as $row) {
                $sess_data = [
                    'username' => $row->username,
                    'user_id' => $row->id
                ];
                $this->session->set_userdata('logged_in', $sess_data);
            }
            return TRUE;
        } else {
            $this->form_validation->set_message('check_password', 'invalid username and password');
            return FALSE;
        }
    }


    public function logout($page = 'login')
    {
        $this->session->unset_userdata('logged_in');
        session_destroy();
        redirect(base_url('login'),'refresh');
    }
}
