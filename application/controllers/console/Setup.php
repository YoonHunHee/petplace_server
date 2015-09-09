<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setup extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->model('admin_model');
    $this->load->library('encrypt');
  }

  // public function index() {
  //     $insert['admin_id'] = 'master';
  //     $insert['admin_name'] = 'master';
  //     $insert['admin_pass'] = $this->encrypt->encode('master!@#');
  //     $insert['create_at'] = date('Y-m-d H:i:s', time());
  //     $insert['update_at'] = date('Y-m-d H:i:s', time());
  //     $result = $this->admin_model->insert($insert);
      
  // }

}
