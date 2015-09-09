<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course extends Console_Controller {

  public $page_size;

  function __construct()
  {
    parent::__construct();
    $this->load->model('course_model');
    $this->load->library('form_validation');

    $this->page_size = $this->config->item('default_page_size');
  }

  public function lists($start = 0)
  {
    $where = 'id is not null';
    $total_count = $this->course_model->total_count($where);

    $this->data['list'] = $this->course_model->lists($where, $start, $this->page_size);

    $config['base_url'] = '/console/course/lists';
    $config['total_rows'] = $total_count;
    $config['per_page'] = $this->page_size; 
    $this->pagination->initialize($config); 
    $this->data['paging'] = $this->pagination->create_links();

    $this->body = 'console/course/lists';
    $this->layout();
  }

}