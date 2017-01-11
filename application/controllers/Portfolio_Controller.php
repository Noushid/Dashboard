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



    public function store()
    {
        $success = false;
        $upload_error = [];
        $error = [];
        $i = 0;


        $this->form_validation->set_rules('name', 'Name', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->output->set_status_header(400, 'validation error');
            $this->output->set_content_type('application/json')->set_output(json_encode(validation_errors()));
        } else {
            $portfolio = $this->input->post();
            $portfolio_id = $this->portfolio->add($portfolio);
            if ($portfolio_id) {
                $desktop = $this->upload('desktop');
                if ($desktop != FALSE) {
                    $upload_error[] = $this->upload->display_errors();
                    /*Multiple file upload*/
                    if (isset($desktop[0])) {
                        foreach ($desktop as $value) {
                            $file['file_name'] = $value['file_name'];
                            $file['file_type'] = $value['file_type'];
                            $file_id = $this->file->add($file);
                            if ($file_id != FALSE) {
                                $portfolio_file['portfolios_id'] = $portfolio_id;
                                $portfolio_file['files_id'] = $file_id;
                                $portfolio_file['image_type'] = 'desktop';
                                if (!$this->portfolio_file->add($portfolio_file)) {
                                    $i++;
                                    $error[] = $i . 'files are not uploaded';
                                    if (file_exists(getwdir() . '/uploads/' . $value['file_name'])) {
                                        unlink(getwdir() . '/uploads/' . $value['file_name']);
                                        $this->file->remove($file_id);
                                    }
                                }
                            } else {
                                if (file_exists(getwdir() . '/uploads/' . $value['file_name'])) {
                                    unlink(getwdir() . '/uploads/' . $value['file_name']);
                                }
                            }
                        }
                    } else {
                        /*Single file upload*/
                        $file['file_name'] = $desktop['file_name'];
                        $file['file_type'] = $desktop['file_type'];
                        $file_id = $this->file->add($file);
                        if ($file_id != FALSE) {
                            $portfolio_file['portfolios_id'] = $portfolio_id;
                            $portfolio_file['files_id'] = $file_id;
                            $portfolio_file['image_type'] = 'desktop';
                            if (!$this->portfolio_file->add($portfolio_file)) {
                                $i++;
                                $error[] = $i . 'files are not uploaded';
                                if (file_exists(getwdir() . '/uploads/' . $desktop['file_name'])) {
                                    unlink(getwdir() . '/uploads/' . $desktop['file_name']);
                                    $this->file->remove($file_id);
                                }
                            }
                        } else {
                            if (file_exists(getwdir() . '/uploads/' . $desktop['file_name'])) {
                                unlink(getwdir() . '/uploads/' . $desktop['file_name']);
                            }
                        }
                    }
                } else {
                    $upload_error[] = $this->upload->display_errors();
                    $this->output->set_status_header(409, 'File upload error');
                }
                $mobile = $this->upload('mobile');
                if ($mobile != false) {
                    $upload_error[] = $this->upload->display_errors();
                    if (isset($mobile[0])) {
                        foreach ($mobile as $value) {
                            $file['file_name'] = $value['file_name'];
                            $file['file_type'] = $value['file_type'];
                            $file_id = $this->file->add($file);
                            if ($file_id != FALSE) {
                                $portfolio_file['portfolios_id'] = $portfolio_id;
                                $portfolio_file['files_id'] = $file_id;
                                $portfolio_file['image_type'] = 'mobile';
                                if (!$this->portfolio_file->add($portfolio_file)) {
                                    $i++;
                                    $error[] = $i . 'files are not uploaded';
                                    if (file_exists(getwdir() . '/uploads/' . $value['file_name'])) {
                                        unlink(getwdir() . '/uploads/' . $value['file_name']);
                                        $this->file->remove($file_id);
                                    }
                                }
                            } else {
                                if (file_exists(getwdir() . '/uploads/' . $value['file_name'])) {
                                    unlink(getwdir() . '/uploads/' . $value['file_name']);
                                }
                            }
                        }
                    } else {
                        /*Single file upload*/
                        $file['file_name'] = $mobile['file_name'];
                        $file['file_type'] = $mobile['file_type'];
                        $file_id = $this->file->add($file);
                        if ($file_id != FALSE) {
                            $portfolio_file['portfolios_id'] = $portfolio_id;
                            $portfolio_file['files_id'] = $file_id;
                            $portfolio_file['image_type'] = 'mobile';
                            if (!$this->portfolio_file->add($portfolio_file)) {
                                $i++;
                                $error[] = $i . 'files are not uploaded';
                                if (file_exists(getwdir() . '/uploads/' . $mobile['file_name'])) {
                                    unlink(getwdir() . '/uploads/' . $mobile['file_name']);
                                    $this->file->remove($file_id);
                                }
                            }
                        } else {
                            if (file_exists(getwdir() . '/uploads/' . $mobile['file_name'])) {
                                unlink(getwdir() . '/uploads/' . $mobile['file_name']);
                            }
                        }
                    }
                } else {
                    $upload_error[] = $this->upload->display_errors();
                    $this->output->set_status_header(409, 'File upload error');
                }


                if (!empty($error) and !empty($upload_error)) {
                    $error_merge['error'] = array_merge($error, $upload_error);
                } elseif (!empty($error)) {
                    $error_merge['error'] = $error;
                } else {
                    $error_merge['error'] = $upload_error;
                }
                $this->output->set_content_type('application/json')->set_output(json_encode($error_merge));
            } else {
                $this->output->set_status_header(500, 'Server error');
                $this->output->set_content_type('application/json')->set_output(json_encode(['error' => 'portfolio add error']));
            }
        }
    }

    public function update($id)

    {
        $success = false;
        $upload_error = [];
        $error = [];
        $i = 0;

        $this->form_validation->set_rules('name', 'Name', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->output->set_status_header(400, 'Validation error');
            $this->output->set_content_type('application/json')->set_output(json_encode(validation_errors()));
        } else {
            $data = $this->input->post();
            unset($data['files_id']);
            unset($data['files']);
            foreach ($data as $key => $vale) {
                if ($vale == 'null') {
                    $data[$key] = null;
                }
            }

            if ($this->portfolio->edit($data, $id)) {
                if (!empty($_FILES['desktop'])) {
                    $desktop = $this->upload('desktop');
                    if ($desktop != FALSE) {
                        $upload_error[] = $this->upload->display_errors();
                        /*Multiple file upload*/
                        if (isset($desktop[0])) {
                            foreach ($desktop as $value) {
                                $file['file_name'] = $value['file_name'];
                                $file['file_type'] = $value['file_type'];
                                $file_id = $this->file->add($file);
                                if ($file_id != FALSE) {
                                    $portfolio_file['portfolios_id'] = $id;
                                    $portfolio_file['files_id'] = $file_id;
                                    $portfolio_file['image_type'] = 'desktop';
                                    if (!$this->portfolio_file->add($portfolio_file)) {
                                        $i++;
                                        $error[] = $i . 'files are not uploaded';
                                        if (file_exists(getwdir() . '/uploads/' . $value['file_name'])) {
                                            unlink(getwdir() . '/uploads/' . $value['file_name']);
                                            $this->file->remove($file_id);
                                        }
                                    }
                                } else {
                                    if (file_exists(getwdir() . '/uploads/' . $value['file_name'])) {
                                        unlink(getwdir() . '/uploads/' . $value['file_name']);
                                    }
                                }
                            }
                        } else {
                            /*Single file upload*/
                            $file['file_name'] = $desktop['file_name'];
                            $file['file_type'] = $desktop['file_type'];
                            $file_id = $this->file->add($file);
                            if ($file_id != FALSE) {
                                $portfolio_file['portfolios_id'] = $id;
                                $portfolio_file['files_id'] = $file_id;
                                $portfolio_file['image_type'] = 'desktop';
                                if (!$this->portfolio_file->add($portfolio_file)) {
                                    $i++;
                                    $error[] = $i . 'files are not uploaded';
                                    if (file_exists(getwdir() . '/uploads/' . $desktop['file_name'])) {
                                        unlink(getwdir() . '/uploads/' . $desktop['file_name']);
                                        $this->file->remove($file_id);
                                    }
                                }
                            } else {
                                if (file_exists(getwdir() . '/uploads/' . $desktop['file_name'])) {
                                    unlink(getwdir() . '/uploads/' . $desktop['file_name']);
                                }
                            }
                        }
                    } else {
                        $upload_error[] = $this->upload->display_errors();
                        $this->output->set_status_header(409, 'File upload error');
                    }
                }
                if (!empty($_FILES['mobile'])) {
                    $mobile = $this->upload('mobile');
                    if ($mobile != false) {
                        $upload_error[] = $this->upload->display_errors();
                        if (isset($mobile[0])) {
                            foreach ($mobile as $value) {
                                $file['file_name'] = $value['file_name'];
                                $file['file_type'] = $value['file_type'];
                                $file_id = $this->file->add($file);
                                if ($file_id != FALSE) {
                                    $portfolio_file['portfolios_id'] = $id;
                                    $portfolio_file['files_id'] = $file_id;
                                    $portfolio_file['image_type'] = 'mobile';
                                    if (!$this->portfolio_file->add($portfolio_file)) {
                                        $i++;
                                        $error[] = $i . 'files are not uploaded';
                                        if (file_exists(getwdir() . '/uploads/' . $value['file_name'])) {
                                            unlink(getwdir() . '/uploads/' . $value['file_name']);
                                            $this->file->remove($file_id);
                                        }
                                    }
                                } else {
                                    if (file_exists(getwdir() . '/uploads/' . $value['file_name'])) {
                                        unlink(getwdir() . '/uploads/' . $value['file_name']);
                                    }
                                }
                            }
                        } else {
                            /*Single file upload*/
                            $file['file_name'] = $mobile['file_name'];
                            $file['file_type'] = $mobile['file_type'];
                            $file_id = $this->file->add($file);
                            if ($file_id != FALSE) {
                                $portfolio_file['portfolios_id'] = $id;
                                $portfolio_file['files_id'] = $file_id;
                                $portfolio_file['image_type'] = 'mobile';
                                if (!$this->portfolio_file->add($portfolio_file)) {
                                    $i++;
                                    $error[] = $i . 'files are not uploaded';
                                    if (file_exists(getwdir() . '/uploads/' . $mobile['file_name'])) {
                                        unlink(getwdir() . '/uploads/' . $mobile['file_name']);
                                        $this->file->remove($file_id);
                                    }
                                }
                            } else {
                                if (file_exists(getwdir() . '/uploads/' . $mobile['file_name'])) {
                                    unlink(getwdir() . '/uploads/' . $mobile['file_name']);
                                }
                            }
                        }
                    } else {
                        $upload_error[] = $this->upload->display_errors();
                        $this->output->set_status_header(409, 'File upload error');
                    }
                }

                if (!empty($error) and !empty($upload_error)) {
                    $error_merge['error'] = array_merge($error, $upload_error);
                } elseif (!empty($error)) {
                    $error_merge['error'] = $error;
                } else {
                    $error_merge['error'] = $upload_error;
                }
                $this->output->set_content_type('application/json')->set_output(json_encode($error_merge));
            } else {
                $this->output->set_status_header(500, 'Server error');
                $this->output->set_content_type('application/json')->set_output(json_encode(['error' => 'portfolio edit error']));
            }
        }
    }

    public function upload($field)
    {
        $config['upload_path'] = getwdir() . '/uploads';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 2800000;
        $config['file_name'] = 'P_' . rand();
        $config['multi'] = 'ignore';
        $this->upload->initialize($config);
        if ($this->upload->do_upload($field)) {
//            var_dump( $this->upload->data());
            return $this->upload->data();

        } else {
            return FALSE;
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
        $data = json_decode($this->input->raw_input_stream, false);
        if ($this->portfolio_file->remove($data->id)) {
            if ($this->file->remove($data->files_id)) {
                if (file_exists(getwdir() . '/uploads/' . $data->file_name)) {
                    unlink(getwdir() . '/uploads/' . $data->file_name);
                }
                $this->output->set_content_type('application/json')->set_output(json_encode(['msg' => 'Image deleted']));
            } else {
                $portfolio_file_data['id'] = $data->id;
                $portfolio_file_data['galleries_id'] = $data->portfolios_id;
                $portfolio_file_data['files_id'] = $data->files_id;
                if ($this->gallery_file->add(portfolio_file_data)) {
                    $result = [
                        'error' => 'Image Can\'t delete at this time.try again',
                        'code' => 001
                    ];
//                    $this->output->set_status_header(500, 'Server Error');
//                    $this->output->set_content_type('application/json')->set_output(json_encode($result));
                }
            }
        } else {
            $result = [
                'error' => 'Image Can\'t delete at this time.try again',
                'code' => 002
            ];
//            $this->output->set_status_header(500, 'Server Error');
//            $this->output->set_content_type('application/json')->set_output(json_encode($result));

        }
    }

}