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
        $this->load->model('File_Model', 'file');
    }

    public function index()
    {
        $this->load->view('admin/templates/employees');

    }

    public function get_employees()
    {
        $data = $this->employee->select();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function upload_file()
    {
        $data = $_FILES;

        $name = $_POST['name'];
        $upload_data = [];

        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 2800000;

        foreach ($data as $value) {
//            random file name
            $config['file_name'] = 'e_'.rand();
//            get file extension
            $ext = substr(strrchr($value['name'],'.'),1);

//          check file size
            if ($ext == 'jpg' or $ext == 'png' or $ext == 'JPG' or $ext == 'jpeg') {
                if ($value['size'] < $config['max_size']) {
                    if (move_uploaded_file($value['tmp_name'],getcwd().'/public/uploads/'.$config['file_name'].'.'.$ext)) {
                        $temp['file_name'] = $config['file_name'] . '.' . $ext;
                        $temp['file_type'] = $ext;
                        $temp['image_cat'] = $name;

//                Add uploaded file information.
                        array_push($upload_data, $temp);
                    }
                }else{
                    $error = ['error' => "File large"];
                    $this->output->set_status_header(500, 'Server Down.');
                    $this->output->set_content_type('application/json')->set_output(json_encode($error));
                    return false;
                }
            }else{
                $error = ['error' => "Unknown file type"];
                $this->output->set_status_header(500, 'Server Down.');
                $this->output->set_content_type('application/json')->set_output(json_encode($error));
                return false;
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($upload_data));
        return true;
    }

    public function store()
    {
        $_POST = json_decode(file_get_contents('php://input'), TRUE);

        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('designation', 'Designation', 'required');
        $this->form_validation->set_rules('gender', 'Gender', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->output->set_status_header(400,'Validation Error');
            $this->output->set_content_type('application/json')->set_output(json_encode(validation_errors()));
        } else {
            $employee_id = $this->employee->add($_POST);
            if ($employee_id) {
                $_POST['id'] = $employee_id;
                $this->output->set_content_type('application/json')->set_output(json_encode($_POST));
            } else {
                $error = ['error' => "Sorry, Can't Process your Request \n Try again later"];
                $this->output->set_status_header(500, 'Server Down.');
                $this->output->set_content_type('application/json')->set_output(json_encode($error));
            }
        }
    }

    public function add_file()
    {
        $data = json_decode(file_get_contents('php://input'), TRUE);

        $error = [];
        $success = [];
        if (empty($data)) {
            $this->output->set_status_header(500, 'Server error');
            $this->output->set_content_type('application/json')->set_output(json_encode(['error' => 'can\'t get files information']));
        } else {

            foreach ($data as $value) {
//            add data to files table
                $file['file_name'] = $value['file_name'];
                $file['file_type'] = $value['file_type'];

                $file_id = $this->file->add($file);

                if ($file_id) {
                    $this->output->set_content_type('application/json')->set_output(json_encode(['files_id' => $file_id]));
                } else {
                    $this->output->set_status_header(500, 'Server error');
                    $this->output->set_content_type('application/json')->set_output(json_encode(['error' => 'Files data add error']));
                }
            }
        }
    }

    public function update()
    {
        $_POST = json_decode(file_get_contents('php://input'), TRUE);
        if ($_POST['files_id'] == $_POST['fileId']) {
            unset($_POST['fileId']);
            unset($_POST['file_name']);
            unset($_POST['file_type']);
        }else{
            if ($this->file->remove($_POST['fileId'])) {
                unlink(getcwd() . '/public/uploads/' . $_POST['file_name']);

                unset($_POST['fileId']);
                unset($_POST['file_name']);
                unset($_POST['file_type']);
            }
        }

        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('designation', 'Designation', 'required');
        $this->form_validation->set_rules('gender', 'Gender', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->output->set_status_header(400,'Validation Error');
            $this->output->set_content_type('application/json')->set_output(json_encode(validation_errors()));
        } else {
            if ($this->employee->edit($_POST,$_POST['id'])) {
                $this->output->set_content_type('application/json')->set_output(json_encode($_POST));
            }else {
                $error = ['error' => "Sorry, Can't Process your Request \n Try again later"];
                $this->output->set_status_header(500, 'Server Down.');
                $this->output->set_content_type('application/json')->set_output(json_encode($error));
            }
        }

    }
    public function delete($id)
    {
        $data = json_decode(file_get_contents('php://input'), TRUE);
        if ($data['files_id']) {
            $file_id = $data['files_id'];
        }

        if (!$this->employee->get(['id' => $id])) {
            $data['message'] = 'Record not found';
            $this->output->set_status_header(400, 'Record Not found.');
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            if ($this->employee->remove($id)) {
                if ($this->file->remove($file_id)) {
                    unlink(getcwd() . '/public/uploads/' . $data['file_name']);
                    $data = 'Record Deleted!';
                    $this->output->set_content_type('application/json')->set_output(json_encode($data));
                } else {
                    $data['error'] = 'files cant delete';
                    $this->output->set_status_header(400, 'Server down');
                    $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        }

    }
}