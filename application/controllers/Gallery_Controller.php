<?php
/**
 * Created by PhpStorm.
 * User: Noushid
 * Date: 23/12/16
 * Time: 4:46 PM
 */
defined('BASEPATH') or exit('Direct script access not allowed');
require_once(APPPATH.'core/Check_Logged.php');

class Gallery_Controller extends Check_Logged
{
    function __construct()
    {
        parent::__construct();

        $this->load->model('Gallery_Files_Model', 'gallery_files');
        $this->load->model('Gallery_Model', 'gallery');
        $this->load->model('File_Model', 'files');
        $this->load->library('upload');
    }

    public function index()
    {
        $this->load->view('templates/gallery');
    }

    public function get($limit = null, $order = null)
    {
        $galleries = $this->gallery_files->select($limit, $order);
        $this->output->set_content_type('application/json')->set_output(json_encode($galleries));
    }

    public function store()
    {
        $success = false;
        $config['upload_path'] = getwdir() . '/uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 4000;
        $config['file_name'] = 'G_' . rand();
        $config['multi'] = 'ignore';
        $this->upload->initialize($config);

        $this->form_validation->set_rules('name', 'Name', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->output->set_status_header(400, 'validation error');
            $this->output->set_content_type('application/json')->set_output(json_encode(validation_errors()));
        } else {
            $post_data = $this->input->post();
            /*Upload files*/
            if ($this->upload->do_upload('files')) {
                /*Check upload error*/
                $upload_error = $this->upload->display_errors();
                $uploaded = $this->upload->data();
                /*Add gallery to db*/
                $gallery_id = $this->gallery->add($post_data);
                if (isset($uploaded[0])) {
                    if ($gallery_id != FALSE) {
                        foreach ($uploaded as $value) {
                            $file_data['file_name'] = $value['file_name'];
                            $file_data['file_type'] = $value['file_type'];
                            /*Add file data to DB*/
                            $file_id = $this->files->add($file_data);
                            if ($file_id != FALSE) {
                                $galleryfile_data['files_id'] = $file_id;
                                $galleryfile_data['galleries_id'] = $gallery_id;
                                if ($this->gallery_files->add($galleryfile_data)) {
                                    $success = true;
                                } else {
                                //TODO gallery files insert error .delete their image and remove file data form db
                                    if ($this->files->remove($file_id)) {
                                        if (file_exists(getwdir() . '/uploads/' . $value['file_name'])) {
                                            unlink(getwdir() . '/uploads/' . $value['file_name']);
                                        }
                                    }
                                }
                            } else {
                                //TODO image insert error.delete the image
                                if (file_exists(getwdir() . '/uploads/' . $value['file_name'])) {
                                    unlink(getwdir() . '/uploads/' . $value['file_name']);
                                }
                            }
                        }
                    } else {
                        //TODO delete uploaded image
                        foreach ($uploaded as $value) {
                            if (file_exists(getwdir() . '/uploads/' . $value['file_name'])) {
                                unlink(getwdir() . '/uploads/' . $value['file_name']);
                            }
                        }
                        $this->output->set_status_header(500, 'Server Down');
                        $this->output->set_content_type('application/json')->set_output(json_encode(['msg' => 'gallery add error']));
                    }
                }/*single file upload*/
                else {
                    $upload_data['file_name'] = $uploaded['file_name'];
                    $upload_data['file_type'] = $uploaded['file_type'];
                    $file_id = $this->files->add($upload_data);
                    if ($file_id != FALSE) {
                        $galleryfile_data['files_id'] = $file_id;
                        $galleryfile_data['galleries_id'] = $gallery_id;
                        if ($this->gallery_files->add($galleryfile_data)) {
                            $success = true;
                        } else {
                            if ($this->files->remove($file_id)) {
                                if (file_exists(getwdir() . '/uploads/' . $uploaded['file_name'])) {
                                    unlink(getwdir() . '/uploads/' . $uploaded['file_name']);
                                }
                                $this->output->set_status_header(500, 'Server Down');
                                $this->output->set_content_type('application/json')->set_output(json_encode(['msg'=>'gallery files insert error']));
                            }
                        }
                    } else {
                        if (file_exists(getwdir() . '/uploads/' . $uploaded['file_name'])) {
                            unlink(getwdir() . '/uploads/' . $uploaded['file_name']);
                        }
                        $this->output->set_status_header(500, 'Server Down');
                        $this->output->set_content_type('application/json')->set_output(json_encode(['msg'=>'files insert error']));
                    }
                }
            } else {
                //TODO display upload error
                $upload_error = $this->upload->display_errors();
                $this->output->set_status_header(500, 'Server Down');
                $this->output->set_content_type('application/json')->set_output(json_encode($upload_error));
            }
            if ($success == true) {
                foreach ($upload_error as $key => $value) {
                    $upload_error[$key] = substr($value, 0, strpos($value, ".", strpos($value, ".") + 1));
                }
                $data['msg'] = 'success';
                if ($upload_error != null) {
                    $data['error'] = $upload_error;
                }
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        }
    }

    public function update($id)
    {
        unset($_POST['files']);
        $success = FALSE;
        $config['upload_path'] = getwdir() . '/uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 4000;
        $config['file_name'] = 'G_' . rand();
        $config['multi'] = 'ignore';
        $this->upload->initialize($config);

        $this->form_validation->set_rules('name', 'Name', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->output->set_status_header(400, 'validation error');
            $this->output->set_content_type('application/json')->set_output(json_encode(validation_errors()));
        } else {
            if (!empty($_FILES)) {
                /*upload file*/
                if ($this->upload->do_upload('files')) {
                    /*Check upload error*/
                    $upload_error = $this->upload->display_errors();
                    $uploaded = $this->upload->data();
                    if (isset($uploaded[0])) {
                        foreach ($uploaded as $value) {
                            $file_data['file_name'] = $value['file_name'];
                            $file_data['file_type'] = $value['file_type'];
                            /*Add files data to DB*/
                            $file_id = $this->files->add($file_data);
                            if ($file_id != FALSE) {
                                $galleryfiles_data['files_id'] = $file_id;
                                $galleryfiles_data['galleries_id'] = $id;

                                if ($this->gallery_files->add($galleryfiles_data)) {
                                    $success = TRUE;
                                } else {
                                    //TODO gallery files insert error .delete their image and remove file data form db
                                    $success = FALSE;
                                    if ($this->files->remove($file_id)) {
                                        if (file_exists(getwdir() . '/uploads/' . $value['file_name'])) {
                                            unlink(getwdir() . '/uploads/' . $value['file_name']);
                                        }
                                    }
                                }
                            } else {
                                //TODO file insert error delete the uploded image
                                $success = FALSE;
                                if (file_exists(getwdir() . '/uploads/' . $value['file_name'])) {
                                    unlink(getwdir() . '/uploads/' . $value['file_name']);
                                }
                            }
                        }
                    } else {
                        /*Single Image upload*/
                        $upload_data['file_name'] = $uploaded['file_name'];
                        $upload_data['file_type'] = $uploaded['file_type'];
                        $file_id = $this->files->add($upload_data);
                        if ($file_id != FALSE) {
                            $galleryfile_data['files_id'] = $file_id;
                            $galleryfile_data['galleries_id'] = $id;
                            if ($this->gallery_files->add($galleryfile_data)) {
                                $success = TRUE;
                            } else {
                                $success = FALSE;
                                if ($this->files->remove($file_id)) {
                                    if (file_exists(getwdir() . '/uploads/' . $uploaded['file_name'])) {
                                        unlink(getwdir() . '/uploads/' . $uploaded['file_name']);
                                    }
                                    $this->output->set_status_header(500, 'Server Down');
                                    $this->output->set_content_type('application/json')->set_output(json_encode(['msg'=>'gallery files insert error']));
                                }
                            }
                        } else {
                            $success = FALSE;
                            if (file_exists(getwdir() . '/uploads/' . $uploaded['file_name'])) {
                                unlink(getwdir() . '/uploads/' . $uploaded['file_name']);
                            }
                            $this->output->set_status_header(500, 'Server Down');
                            $this->output->set_content_type('application/json')->set_output(json_encode(['msg'=>'files insert error']));
                        }
                    }
                } else {
                    //TODO display upload error
                    $upload_error = $this->upload->display_errors();
                    $this->output->set_status_header(500, 'Server Down');
                    $this->output->set_content_type('application/json')->set_output(json_encode($upload_error));
                }
            } else {
                $success = TRUE;
            }

            if ($success == TRUE) {
                if ($this->gallery->edit($_POST, $id)) {
                    $data['msg'] = 'success';
                    if (isset($upload_error) and $upload_error != null) {
                        $data['error'] = $upload_error;
                    }
                    $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        }
    }

    public function delete_image()
    {
        $data = json_decode(file_get_contents('php://input'), TRUE);
        if ($this->files->remove($data['files_id'])) {
            if (file_exists(getwdir() . '/uploads/' . $data['file_name'])) {
                unlink(getwdir() . '/uploads/' . $data['file_name']);
            }
            if ($this->gallery_files->remove($data['gallery_files_id'])) {
                $this->output->set_content_type('application/json')->set_output(json_encode(['msg' => 'image deleted']));
            } else {
                $this->output->set_status_header(500, 'Server error');
                $this->output->set_content_type('application/json')->set_output(json_encode(['error' => 'gallery files remove error']));
            }
        } else {
            $this->output->set_status_header(500, 'Server error');
            $this->output->set_content_type('application/json')->set_output(json_encode(['error' => 'files remove error']));
        }
    }

    public function delete()
    {
        $data = json_decode(file_get_contents('php://input'), TRUE);
        $success = FALSE;
        $message = [];
        $files = $data['files'];
        if (!empty($files)) {
            foreach ($files as $value) {
                if ($this->files->remove($value['files_id'])) {
                    if (file_exists(getwdir() . '/uploads/' . $value['file_name'])) {
                        unlink(getwdir() . '/uploads/' . $value['file_name']);
                    }
                    if ($this->gallery_files->remove($value['gallery_files_id'])) {
                        $success = TRUE;
                    } else {
                        $success =FALSE;
                        $message['msg'] = 'gallery files remove error';
                    }
                } else {
                    $success =FALSE;
                    $message['msg'] = 'files remove error';
                }
            }
        } else {
            $success = TRUE;
        }
        if ($success == TRUE) {
            if ($this->gallery->remove($data['id'])) {
                $data['msg'] = 'gallery deleted';
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $this->output->set_status_header(500, 'Server error');
                $this->output->set_content_type('application/json')->set_output(json_encode(['error' => 'gallery remove error']));
            }
        }
    }
}