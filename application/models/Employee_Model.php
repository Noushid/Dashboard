<?php
/**
 * Created by PhpStorm.
 * User: noushi
 * Date: 20/7/16
 * Time: 12:35 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/My_Model.php';

class Employee_Model extends My_Model
{
    protected $table = 'employees';
    protected $fields = [
        'employees.*',
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
                'employees.files_id',
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

    public function remove($id)
    {
        return $this->drop($id);
    }

    public function trunc()
    {
        return $this->truncate();
    }
}
