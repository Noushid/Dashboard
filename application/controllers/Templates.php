<?php
/**
 * Created by PhpStorm.
 * User: noushi
 * Date: 20/7/16
 * Time: 3:17 PM
 */
defined('BASEPATH') or exit('No direct Script Access Allowed');

class Templates extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function view($view)
    {
        $this->load->view('templates/' . $view);
    }
}