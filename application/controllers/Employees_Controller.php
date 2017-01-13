<?php
/**
 * Created by PhpStorm.
 * User: noushi
 * Date: 20/7/16
 * Time: 3:20 PM
 */
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH.'core/Check_Logged.php');

class Employees_Controller extends Check_Logged
{
    function __construct()
    {
        parent::__construct();

        $this->load->model('Employee_Model', 'employee');
        $this->load->model('File_Model', 'file');
        $this->load->library('upload');

        if ( ! $this->logged)
        {
            redirect(base_url('login'));
        }
    }

    public function index()
    {
        $this->load->view('templates/employees');
    }

    public function get_employees()
    {
        $data = $this->employee->select();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }


    public function store()
    {
        $config['upload_path'] = getwdir() . '/uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 2800000;
        $config['file_name'] = 'E_' . rand();
        $this->upload->initialize($config);

        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('designation', 'Designation', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->output->set_status_header(400, 'validation error');
            $this->output->set_content_type('application/json')->set_output(json_encode(validation_errors()));
        } else {
            if ($this->upload->do_upload('photo')) {
                $upload_data = $this->upload->data();
                $file_data['file_name'] = $upload_data['file_name'];
                $file_data['file_type'] = $upload_data['file_type'];
                $file_id = $this->file->add($file_data);
                if ($file_id) {
                    $_POST['files_id'] = $file_id;

                    $post_data = $this->input->post();
                    if ($this->employee->add($post_data)) {
                        $this->output->set_content_type('application/json')->set_output(json_encode($post_data));
                    } else {
                        if ($this->file->remove($file_id)) {
                            if (file_exists(getwdir() . '/uploads' . $upload_data['file_name'])) {
                                unlink(getwdir() . '/uploads' . $upload_data['file_name']);
                            }
                        }

                        $this->output->set_status_header(500, 'Server Down');
                        $this->output->set_content_type('application/json')->set_output(json_encode(['error' => 'employee data add error']));
                    }
                } else {
                    if (file_exists(getwdir() . '/uploads' . $upload_data['file_name'])) {
                        unlink(getwdir() . '/uploads' . $upload_data['file_name']);
                    }

                    $this->output->set_status_header(500, 'Server Down');
                    $this->output->set_content_type('application/json')->set_output(json_encode(['error' => 'file data add error']));
                }
            } else {
                $error = $this->upload->display_errors();
                $this->output->set_status_header(409, 'File upload error');
                $this->output->set_content_type('application/json')->set_output(json_encode($error));
            }
        }

    }


    public function update($id)
    {
        $config['upload_path'] = getwdir() . '/uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 2800000;
        $config['file_name'] = 'E_' . rand();
        $this->upload->initialize($config);

        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('designation', 'Designation', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->output->set_status_header(400, 'validation error');
            $this->output->set_content_type('application/json')->set_output(json_encode(validation_errors()));
        } else {
            $post_data = $this->input->post();
            $old_file_name = $post_data['file_name'];
            $old_file_id = $post_data['fileId'];

            unset($post_data['fileId']);
            unset($post_data['file_name']);
            unset($post_data['file_type']);
            foreach ($post_data as $key => $vale) {
                if ($vale == 'null') {
                    $post_data[$key] = null;
                }
            }
            /*New photo*/
            if (isset($_FILES['photo'])) {
                if ($this->upload->do_upload('photo')) {
                    $upload_data = $this->upload->data();
                    $file_data['file_name'] = $upload_data['file_name'];
                    $file_data['file_type'] = $upload_data['file_type'];
                    $file_id = $this->file->add($file_data);
                    if ($file_id) {
                        $post_data['files_id'] = $file_id;
                        if ($this->employee->edit($post_data, $id)) {
                            $this->file->remove($old_file_id);
                            if (getwdir() . '/uploads/' . $old_file_name) {
                                unlink(getwdir() . '/uploads/' . $old_file_name);
                            }
                            $this->output->set_content_type('application/json')->set_output(json_encode(['msg' => 'Data updated']));
                        } else {
                            if ($this->file->remove($file_id)) {
                                if (file_exists(getwdir() . '/uploads/' . $upload_data['file_name'])) {
                                    unlink(getwdir() . '/uploads/' . $upload_data['file_name']);
                                }

                                $this->output->set_status_header(500, 'Server Down');
                                $this->output->set_content_type('application/json')->set_output(json_encode(['error' => 'employee update add error']));
                            }
                        }
                    } else {
                        if (file_exists(getwdir() . '/uploads' . $upload_data['file_name'])) {
                            unlink(getwdir() . '/uploads' . $upload_data['file_name']);
                        }

                        $this->output->set_status_header(500, 'Server Down');
                        $this->output->set_content_type('application/json')->set_output(json_encode(['error' => 'file data add error']));
                    }
                } else {
                    $error = $this->upload->display_errors();
                    $this->output->set_status_header(409, 'File upload error');
                    $this->output->set_content_type('application/json')->set_output(json_encode($error));
                }
            } else {
                /*Without new photo*/
                if ($this->employee->edit($post_data,$id)) {
                    $this->output->set_content_type('application/json')->set_output(json_encode(['msg' => 'Data updated']));
                } else {
                    $this->output->set_status_header(500, 'Server Down');
                    $this->output->set_content_type('application/json')->set_output(json_encode(['error' => 'employee update add error']));
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

        if (!$this->employee->get(['id' => $id])) {
            $data['message'] = 'Record not found';
            $this->output->set_status_header(400, 'Record Not found.');
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            if ($this->employee->remove($id)) {
                if ($this->file->remove($file_id)) {
                    if (file_exists(getwdir() . '/uploads/' . $data['file_name'])) {
                        unlink(getwdir() . '/uploads/' . $data['file_name']);
                    }
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