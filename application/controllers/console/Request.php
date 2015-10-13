<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request extends Console_Controller {

    public $page_size;

    function __construct()
    {
        parent::__construct();
        $this->load->model('request_model');

    }

    public function lists($start = 0)
    {
        $where = 'id is not null';
        $total_count = $this->request_model->total_count($where);

        $this->data['list'] = $this->request_model->lists($where, $start, $this->page_size);

        $config['base_url'] = '/console/request/lists';
        $config['total_rows'] = $total_count;
        $config['per_page'] = $this->page_size; 
        $this->pagination->initialize($config); 
        $this->data['paging'] = $this->pagination->create_links();
        
        $this->body = 'console/request/lists';
        $this->layout();
    }

    public function form($id = '')
    {
        $this->load->helper('form');

        if(!empty($id)) {
            $row = $this->request_model->get($id);
            $this->data['row'] = $row;
        }

        $this->body = 'console/request/form';
        $this->layout();
    }

    public function del()
    {
        $this->form_validation->set_rules('id', '정상적인 접근이 아닙니다.', 'required');
        
        if($this->form_validation->run() === false)
        {
            echo 'form error : ' . validation_errors(); 
        }
        else
        {
            $result = $this->request_model->delete($this->input->post('id'));

            if($result) {
                redirect('/console/request/lists');
            }
        }
    }
}
