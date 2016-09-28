<?php
/**
 * Created by PhpStorm.
 * User: noushi
 * Date: 20/7/16
 * Time: 12:35 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/My_Model.php';

class Address_Model extends My_Model
{
    protected $table = 'address';

    function __construct()
    {
        parent::__construct();
    }

    public function select()
    {
        return $this->get_all();
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
