<?php
/**
 * Created by PhpStorm.
 * User: noushi
 * Date: 20/7/16
 * Time: 12:55 PM
 */

defined('BASEPATH') or exit ('No direct script access allowed');
require_once APPPATH . 'core/My_Model.php';
class Portfolio_Model extends My_Model{

    protected $table = 'portfolios';

    function __construct()
    {
        parent::__construct();
    }

    public function select()
    {
        return $this->get_all();
    }

    public function select_where($where)
    {
        return $this->get($where);
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

//    Truncate Table
    public function trunc()
    {
        return $this->truncate();
    }

}