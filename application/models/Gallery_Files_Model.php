<?php
/**
 * Created by PhpStorm.
 * User: noushi
 * Date: 20/7/16
 * Time: 12:55 PM
 */

defined('BASEPATH') or exit ('No direct script access allowed');
require_once APPPATH . 'core/My_Model.php';
class Gallery_Files_Model extends My_Model{

    protected $table = 'gallery_files';

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Return all galleries with their files ie,album model
     *
     */

    public function select($limit = null, $order = null)
    {
        $this->db->from('galleries');
        if ($limit != null) {
            $this->db->limit($limit);
        }
        if ($order != null) {
            $this->db->order_by('id', 'DESC');
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $galleries = $query->result();
            foreach ($galleries as $value) {
                $this->db->from('gallery_files');
                $this->db->where('galleries_id', $value->id);
                $glr_fls_query = $this->db->get();
                $gallery_files = $glr_fls_query->result();
                foreach ($gallery_files as $val) {
                    $this->db->from('files');
                    $this->db->where('id', $val->files_id);
                    $files_query = $this->db->get();
                    $files = $files_query->result();
                    foreach ($files as $file) {
                        $val->file_name = $file->file_name;
                        $val->file_type = $file->file_type;
                        $val->gallery_files_id = $val->id;
                    }
                }
                $value->files = $gallery_files;
            }
            return $galleries;
        }else
            return FALSE;

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


    /**
     *return all gallery files
     *
     */
    public function select_all($limit = null, $order = null)
    {
        $this->db->from('galleries');
        if ($limit != null) {
            $this->db->limit($limit);
        }
        if ($order != null) {
            $this->db->order_by($order, 'DESC');
        }
        $all_files = [];
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $galleries = $query->result();
            foreach ($galleries as $value) {
                $this->db->from('gallery_files');
                $this->db->where('galleries_id', $value->id);
                $glr_fls_query = $this->db->get();
                $gallery_files = $glr_fls_query->result();
                foreach ($gallery_files as $val) {
                    $this->db->from('files');
                    $this->db->where('id', $val->files_id);
                    $files_query = $this->db->get();
                    $files = $files_query->result();
                    foreach ($files as $file) {
                        $val->thumbUrl = public_url() . 'uploads/' . $file->file_name;
                        $val->url = public_url() . 'uploads/' . $file->file_name;
                        $val->alt = 'gallery';
                        array_push($all_files, $val);
                    }
                }
            }
            return $all_files;
        }else
            return FALSE;
    }

}