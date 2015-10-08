<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Console_Controller {

    public $page_size;

    function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');

        $this->load->library('encrypt');
        $this->load->library('form_validation');

        $this->page_size = $this->config->item('default_page_size');
    }

    public function lists()
    {
        $where = 'id is not null';
        $total_count = $this->user_model->total_count($where);
        $lists = $this->user_model->lists($where, 0, $this->page_size);

        $this->data['list'] = $lists;

        $config['base_url'] = '/console/user/lists';
        $config['total_rows'] = $total_count;
        $config['per_page'] = $this->page_size; 
        $this->pagination->initialize($config); 
        $this->data['paging'] = $this->pagination->create_links();

        $this->body = 'console/user/lists';
        $this->layout();
    }

    public function form($id = '')
    {
        if(!empty($id)) {
            $row = $this->user_model->get($id);
            $this->data['row'] = $row;
        }

        $this->body = 'console/user/form';
        $this->layout();
    }


}