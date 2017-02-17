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

        $this->load->library(['image_lib']);
        $this->load->model('User_Model', 'user');

        /*
         * Check loin and logout
         * */

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

    public function version()
    {
        phpinfo();
    }


///////////////////////////////////////////////////////////



    public function index()
    {
        $this->load->view('templates/dashboard');
    }

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

    public function get_user()
    {
        $user = $this->session->logged_in;
        $this->output->set_content_type('application/json')->set_output(json_encode($user));
    }
    public function profile()
    {
        $data['username'] = $this->session->logged_in['username'];
        $this->load->view('templates/change_profile',$data);
    }
    public function edit_profile()
    {
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('curpassword', 'Current password', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('confirmpassword', 'Password', 'required|matches[password]');
        if ($this->form_validation->run() === FALSE) {
            $this->output->set_status_header(400, 'Validation error');
            $this->output->set_content_type('application/json')->set_output(json_encode(validation_errors()));
        } else {
            $cur_user = $this->session->logged_in['username'];
            $cur_pass = hash('sha256', $this->input->post('curpassword'));

            $data['username'] = $this->input->post('username');
            $data['password'] = hash('sha256', $this->input->post('password'));
            $where = [
                'username' => $cur_user,
                'password' => $cur_pass,
            ];
            if ($result = $this->user->get($where)) {
                $id = $result[0]->id;
                if ($edit = $this->user->edit($data, $id)) {
                    $login_data = [
                        'username' => $data['username'],
                        'logged' => TRUE
                    ];
                    $this->session->set_userdata('logged_in', $login_data);
                    $this->output->set_content_type('application/json')->set_output(json_encode(['msg' => 'Username and password changed']));
                }
            }else{
                $this->output->set_status_header(400, 'Validation error');
                $error['msg'] = 'Current username and password did not match';
                $this->output->set_content_type('application/json')->set_output(json_encode($error));
            }
        }
    }

}