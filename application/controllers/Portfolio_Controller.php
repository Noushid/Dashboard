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
        $this->load->model('File_Model', 'file');
        $this->load->model('Portfolio_Files_model', 'portfolio_file');
        $this->load->library('upload');

    }

    public function index()
    {
        $this->load->view('templates/portfolios');
    }


    public function get()
    {

        $data = $this->portfolio_file->select();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function upload_file()
    {

        $data = $_FILES;
        $name = $_POST['name'];
        $upload_data = [];

        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 2800000;

        foreach ($data as $value) {
//            random file name
            $config['file_name'] = 'p_'.rand();
//            get file extension
            $ext = substr(strrchr($value['name'],'.'),1);

//          check file size
            if ($ext == 'jpg' or $ext == 'png' or $ext == 'JPG' or $ext == 'jpeg') {
                if ($value['size'] < $config['max_size']) {
                    if (move_uploaded_file($value['tmp_name'],getcwd().'/uploads/'.$config['file_name'].'.'.$ext)) {
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
                }
            }else{
                $error = ['error' => "Unknown file type"];
                $this->output->set_status_header(500, 'Server Down.');
                $this->output->set_content_type('application/json')->set_output(json_encode($error));

            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($upload_data));
    }

    public function delete_file()
    {
        $data = json_decode(file_get_contents('php://input'), TRUE);
        $path = getcwd().'/uploads/';

        if (is_array($data)) {
            foreach ($data as $value) {
                if (!unlink($path . $value['file_name'])) {
                    $this->output->set_status_header(500, 'Server Down.');
                    $this->output->set_content_type('application/json')->set_output(json_encode('Unable to delete'));
                    return FALSE;
                } else {
                    $this->output->set_content_type('application/json')->set_output(json_encode('Deleted!'));
                }
            }
        } else {
            unlink($path . $data);
        }
    }

    public function store()
    {
        $_POST = json_decode(file_get_contents('php://input'), TRUE);

        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('type', 'Type', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->output->set_status_header(400,'Validation Error');
            $this->output->set_content_type('application/json')->set_output(json_encode(validation_errors()));
        } else {
            $portfolio_id = $this->portfolio->add($_POST);
            if ($portfolio_id) {
                $_POST['id'] = $portfolio_id;
                $this->output->set_content_type('application/json')->set_output(json_encode($_POST));
            } else {
                $error = ['error' => "Sorry, Can't Process your Request \n Try again later"];
                $this->output->set_status_header(500, 'Server Down.');
                $this->output->set_content_type('application/json')->set_output(json_encode($error));
            }
        }
    }

    public function add_file($id)
    {
        $data = json_decode(file_get_contents('php://input'), TRUE);


        $error = [];
        $success = [];
        if (empty($data)) {
            $this->output->set_status_header(500, 'Server error');
            $this->output->set_content_type('application/json')->set_output(json_encode(['error' => 'can\'t get file information']));
        } else {
            foreach ($data as $value) {
//            add data to files table
                $file['file_name'] = $value['file_name'];
                $file['file_type'] = $value['file_type'];
                $file_id = $this->file->add($file);

//            add data to portfolio_files table
                if ($file_id) {
                    $temp = [
                        'portfolios_id' => $id,
                        'files_id' => $file_id,
                        'image_type' => $value['image_cat']
                    ];
                    $portfolio_file = $this->portfolio_file->add($temp);
                    if ($portfolio_file) {
                        array_push($success, $portfolio_file);
                    }else{
                        $error['error'] = 'portfolio files error';
                    }
                } else {
                    $error['error'] = 'ca\'t add files information to db try again';
                }
            }
            if ($success) {
                $this->output->set_content_type('application/json')->set_output(json_encode($success));
                return true;
            }
            if ($error) {
                $this->output->set_status_header(500, 'Server error');
                $this->output->set_content_type('application/json')->set_output(json_encode($error));
                return false;
            }

        }
    }

    public function edit_record()
    {
        $_POST = json_decode(file_get_contents('php://input'), TRUE);

        if (array_key_exists('files',$_POST)) {
            unset($_POST['files']);
        }
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
            $this->output->set_status_header(400, 'Record Not found.');
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            if ($this->portfolio->remove($id)) {
                $data = 'Record Deleted!';
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        }
    }

    public function delete_image()
    {
        $data = json_decode(file_get_contents('php://input'), TRUE);

        $portfolio_file_id = $data['id'];
        $files_id = $data['files_id'];

        if ($this->portfolio_file->remove($portfolio_file_id)) {
            if ($this->file->remove($files_id)) {
                $data = 'file Deleted!';
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $data['error'] = 'files delete error';
                $this->output->set_status_header(400, 'Record Not found.');
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        } else {
            $data['error'] = 'portfolio files delete error';
            $this->output->set_status_header(400, 'Record Not found.');
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

}