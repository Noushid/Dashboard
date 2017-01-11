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

        $this->load->library('upload');

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

    public function store()
    {
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 2800000;
        $config['file_name'] = 'T_' . rand();
        $this->upload->initialize($config);

        if ($this->upload->do_upload('photo')) {
            $upload_data = $this->upload->data();
            $data['file_name'] = $upload_data[0]['file_name'];
            $data['file_type'] = $upload_data[0]['file_type'];

            $file_id = $this->file->add($data);
            if ($file_id) {
                $_POST['files_id'] = $file_id;
                if ($this->testimonial->add($_POST)) {
                    $this->output->set_content_type('application/json')->set_output(json_encode($_POST));
                } else {
                    if ($this->file->remove($file_id)) {
                        unlink(getcwd() . '/uploads/' . $upload_data['file_name']);
                    }
                    $error['error'] = 'testimonial error';
                    $this->output->set_status_header(500, 'Server Down');
                    $this->output->set_content_type('application/json')->set_output(json_encode($error));
                }
            } else {
                unlink(getcwd() . '/uploads/' . $upload_data['file_name']);
                $error['error'] = 'file insert error';
                $this->output->set_status_header(500, 'Server Down');
                $this->output->set_content_type('application/json')->set_output(json_encode($error));
            }
        } else {
            $this->output->set_status_header(500, 'Server down');
            $this->output->set_content_type('application/json')->set_output(json_encode($this->upload->display_errors()));
        }
    }

    public function update()
    {
        $id = $_POST['id'];
        if ($_FILES != null) {
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = 2800000;
            $config['file_name'] = 'T_' . rand();
            $this->upload->initialize($config);

            if ($this->upload->do_upload('photo')) {
                $upload_data = $this->upload->data();
                $data['file_name'] = $upload_data[0]['file_name'];
                $data['file_type'] = $upload_data[0]['file_type'];

                $file_id = $this->file->add($data);
                if ($file_id) {
                    $old_file_id = $_POST['fileId'];
                    $old_file_name = $_POST['file_name'];

                    unset($_POST['fileId']);
                    unset($_POST['file_name']);
                    unset($_POST['file_type']);
                    $_POST['files_id'] = $file_id;

                    if ($this->testimonial->edit($_POST, $id)) {
                        /*remove file information from db*/
                        if ($this->file->remove($old_file_id)) {
                            /*delete image from folder*/
                            unlink(getcwd() . '/uploads/' . $old_file_name);
                        }
                        $this->output->set_content_type('application/json')->set_output(json_encode($_POST));
                    } else {
                        $error['error'] = 'testimonial edit error';

                        $this->output->set_status_header(500, 'Server Down.');
                        $this->output->set_content_type('application/json')->set_output(json_encode($error));
                    }
                } else {
                    $error['error'] = 'file insert error';
                    $this->output->set_status_header(500, 'Server down');
                    $this->output->set_content_type('application/json')->set_output(json_encode($error));
                }
            } else {
                $this->output->set_status_header(500, 'Server down');
                $this->output->set_content_type('application/json')->set_output(json_encode($this->upload->display_error()));
            }
        } else {
            unset($_POST['fileId']);
            unset($_POST['file_name']);
            unset($_POST['file_type']);

            if ($this->testimonial->edit($_POST, $id)) {
            $this->output->set_content_type('application/json')->set_output(json_encode($_POST));
            } else {
                $error['error'] = 'testimonial edit error';
                $this->output->set_status_header(500, 'Server down');
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

        if (!$this->testimonial->get(['id'=>$id])) {
            $data = 'record not found';
            $this->output->set_status_header(400, 'Record Not found.');
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            if ($this->testimonial->remove($id)) {
                if ($this->file->remove($file_id)) {
                    unlink(getcwd() . '/uploads/' . $data['file_name']);
                    $data = 'Record Deleted!';
                    $this->output->set_content_type('application/json')->set_output(json_encode($data));
                } else {
                    $data['error'] = 'files cant delete';
                    $this->output->set_status_header(400, 'Server down');
                    $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            } else {
                $data['error'] = 'testimonial cant delete';
                $this->output->set_status_header(400, 'Server down');
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        }
    }
}