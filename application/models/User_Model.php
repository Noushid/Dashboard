<?php
/**
 * Created by PhpStorm.
 * User: noushi
 * Date: 20/7/16
 * Time: 11:52 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'core/My_Model.php');

class User_Model extends My_Model
{
    protected $table = 'users';

    public function __construct()
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
