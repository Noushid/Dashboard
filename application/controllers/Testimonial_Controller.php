<?php
/**
 * Created by PhpStorm.
 * User: psybo-03
 * Date: 19/12/16
 * Time: 1:33 PM
 */
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH.'core/Check_Logged.php');

class Testimonial_Controller extends Check_Logged
{
    function __construct()
    {
        parent::__construct();

        $this->load->model('Testimonial_Model', 'testimonial');
        $this->load->model('File_Model', 'file');

        $this->load->library('upload');

        if ( ! $this->logged)
        {
            redirect(base_url('login'));
        }

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
        $this->form_validation->set_rules('name', 'Name', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->output->set_status_header(400, 'Validation error');
            $this->output->set_content_type('application/json')->set_output(json_encode(validation_errors()));
        } else {
            $config['upload_path'] = getwdir() . '/uploads/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = 2800000;
            $config['file_name'] = 'T_' . rand();
            $this->upload->initialize($config);
            $post_data = $this->input->post();

            if ($this->upload->do_upload('photo')) {
                $upload_data = $this->upload->data();
                $data['file_name'] = $upload_data['file_name'];
                $data['file_type'] = $upload_data['file_type'];

                $file_id = $this->file->add($data);
                if ($file_id) {
                    $post_data['files_id'] = $file_id;
                    if ($this->testimonial->add($post_data)) {
                        $this->output->set_content_type('application/json')->set_output(json_encode(['msg' => 'Data added']));
                    } else {
                        if ($this->file->remove($file_id)) {
                            if (file_exists(getwdir() . '/uploads/' . $upload_data['file_name'])) {
                                unlink(getwdir() . '/uploads/' . $upload_data['file_name']);
                            }
                        }
                        $error['error'] = 'testimonial error';
                        $this->output->set_status_header(500, 'Server Down');
                        $this->output->set_content_type('application/json')->set_output(json_encode($error));
                    }
                } else {
                    if (file_exists(getwdir() . '/uploads/' . $upload_data['file_name'])) {
                        unlink(getwdir() . '/uploads/' . $upload_data['file_name']);
                    }
                    $error['error'] = 'file insert error';
                    $this->output->set_status_header(500, 'Server Down');
                    $this->output->set_content_type('application/json')->set_output(json_encode($error));
                }
            } else {
                $this->output->set_status_header(409, 'File upload error');
                $this->output->set_content_type('application/json')->set_output(json_encode($this->upload->display_errors()));
            }
        }
    }

    public function update($id)
    {
        $post_data = $this->input->post();


        $this->form_validation->set_rules('name', 'Name', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->output->set_status_header(400, 'Validation error');
            $this->output->set_content_type('application/json')->set_output(json_encode(validation_errors()));
        } else {
            if (isset($_FILES['photo'])) {
                $config['upload_path'] = getwdir() . '/uploads/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = 2800000;
                $config['file_name'] = 'T_' . rand();
                $this->upload->initialize($config);

                if ($this->upload->do_upload('photo')) {
                    $upload_data = $this->upload->data();
                    $data['file_name'] = $upload_data['file_name'];
                    $data['file_type'] = $upload_data['file_type'];

                    $file_id = $this->file->add($data);
                    if ($file_id) {
                        $old_file_name = $post_data['file_name'];
                        $old_file_id = $post_data['fileId'];

                        unset($post_data['fileId']);
                        unset($post_data['file_name']);
                        unset($post_data['file_type']);

                        $post_data['files_id'] = $file_id;

                        if ($this->testimonial->edit($post_data, $id)) {

                            /*Remove Old file and data from db*/
                            if ($this->file->remove($old_file_id)) {
                                if (file_exists(getwdir() . '/uploads/' . $old_file_name)){
                                    /*delete image from folder*/
                                    unlink(getcwd() . '/uploads/' . $old_file_name);
                                }
                            }
                            $this->output->set_content_type('application/json')->set_output(json_encode(['msg' => 'Data updated']));
                        } else {
                            if (file_exists(getwdir() . '/uploads/' . $data['file_name'])) {
                                unlink(getwdir() . '/uploads/' . $data['file_name']);
                            }
                            $this->file->remove($file_id);
                            $error['error'] = 'testimonial edit error';
                            $this->output->set_status_header(500, 'Server Down.');
                            $this->output->set_content_type('application/json')->set_output(json_encode($error));
                        }
                    } else {
                        /*delete uploaded file*/
                        if (file_exists(getwdir() . '/uploads/' . $data['file_name'])) {
                            unlink(getwdir() . '/uploads/' . $data['file_name']);
                        }

                        $error['error'] = 'file insert error';
                        $this->output->set_status_header(500, 'Server down');
                        $this->output->set_content_type('application/json')->set_output(json_encode($error));
                    }
                } else {
                    $this->output->set_status_header(409, 'File Upload error');
                    $this->output->set_content_type('application/json')->set_output(json_encode($this->upload->display_error()));
                }
            } else {
                unset($post_data['fileId']);
                unset($post_data['file_name']);
                unset($post_data['file_type']);

                if ($this->testimonial->edit($post_data, $id)) {
                    $this->output->set_content_type('application/json')->set_output(json_encode(['msg' => 'Data Updated']));
                } else {
                    $error['error'] = 'testimonial edit error';
                    $this->output->set_status_header(500, 'Server down');
                    $this->output->set_content_type('application/json')->set_output(json_encode($error));
                }
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