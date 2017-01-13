<?php

/**
 * Created by PhpStorm.
 * User: noushi
 * Date: 20/7/16
 * Time: 12:55 PM
 */

defined('BASEPATH') or exit ('No direct script access allowed');
require_once APPPATH . 'core/My_Model.php';

class Portfolio_Files_Model extends My_Model{

    protected $table = 'portfolio_files';
    protected $fields = [
        'portfolios.id as portfoliosId',
        'portfolios.name',
        'portfolios.type',
        'portfolios.description',
        'portfolio_files.id as portfoliofilesId',
        'portfolio_files.files_id',
        'portfolio_files.portfolios_id',
        'portfolio_files.image_type',
        'files.id as filesId',
        'files.file_name',
        'files.file_type'
    ];

    function __construct()
    {
        parent::__construct();
    }

    public function select()
    {
        $this->db->from('portfolios');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $portfolio_files = $query->result();
            foreach ($portfolio_files as $value) {
                $this->db->from('portfolio_files');
                $this->db->where('portfolios_id', $value->id);
                $temp = $this->db->get();

                foreach ($temp->result() as $val) {
                    $this->db->from('files');
                    $this->db->where('id', $val->files_id);
                    $files = $this->db->get();

                    foreach ($files->result() as $file) {
                        $val->file_name = $file->file_name;
                        $val->file_type = $file->file_type;
                    }
                }
                $value->files = $temp->result();
            }
            return $portfolio_files;
        } else {
            return FALSE;
        }

    }


    public function add($data)
    {
        return $this->insert($data);
    }

    public function edit($data, $id)
    {
        return $this->update($data, $id);
    }

    public function remove($id)
    {
        return $this->drop($id);
    }

    public function trunc()
    {
        return $this->truncate();
    }

}