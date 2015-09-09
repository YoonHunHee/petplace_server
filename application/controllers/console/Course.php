<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course extends Console_Controller {

  public $page_size;

  function __construct()
  {
    parent::__construct();
    //$this->load->model('admin_model');
    $this->load->library('form_validation');

    $this->page_size = $this->config->item('default_page_size');
  }

  public function lists()
  {
    $where = '';
    
  }

}