<?php
/**
 * Created by PhpStorm.
 * User: noushi
 * Date: 20/7/16
 * Time: 12:55 PM
 */

defined('BASEPATH') or exit ('No direct script access allowed');
require_once APPPATH . 'core/My_Model.php';

class Testimonial_Model extends My_Model{

    protected $table = 'testimonials';
    protected $fields = [
        'testimonials.*',
        'files.id fileId',
        'files.file_name',
        'files.file_type'
    ];

    function __construct()
    {
        parent::__construct();
    }

    public function select()
    {
        $condition = [
            [
                'testimonials.files_id',
                'files.id'
            ]
        ];
        return $this->get_join(['files'], $this->fields, null, $condition, 'INNER');
}

    public function add($data)
    {
        return $this->insert($data);
    }

    public function edit($data, $id)
    {
        return $this->update($data, $id);
    }

    public function trunc()
    {
        return $this->truncate();
    }

}