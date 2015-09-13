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

    public function form($id = '')
    {
        $this->load->helper('form');

        if(!empty($id)) {
            $row = $this->course_model->get($id);
            $this->data['row'] = $row;
        }

        $this->body = 'console/course/form';
        $this->layout();
    }

    public function get_json()
    {
        $this->load->helper('download');

        $where = 'id is not null';
        $data['courses'] = $this->course_model->lists($where);

        force_download('courses.json', json_encode($data));
        //$this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function action()
    {
        $mode = $this->input->post('mode');
        if($mode == 'regist') 
            $this->_regist();
        else
            $this->_edit();
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
            $result = $this->course_model->delete($this->input->post('id'));

            if($result) {
                redirect('/console/course/lists');
            }
        }
    }

    function _regist()
    {
        $this->form_validation->set_rules('title', '제목을 입력하세요', 'required');
        $this->form_validation->set_rules('st_pt', '제목을 입력하세요', 'required');
        $this->form_validation->set_rules('end_pt', '제목을 입력하세요', 'required');
        $this->form_validation->set_rules('way_pt', '제목을 입력하세요', 'required');
        $this->form_validation->set_rules('distance', '제목을 입력하세요', 'required');
        $this->form_validation->set_rules('walk_time', '제목을 입력하세요', 'required');
        
        if($this->form_validation->run() === false)
        {
            echo 'form error : ' . validation_errors(); 
        }
        else
        {
            $insert['title'] = $this->input->post('title');
            $insert['desc'] = $this->input->post('desc');
            $insert['st_pt'] = $this->input->post('st_pt');
            $insert['end_pt'] = $this->input->post('end_pt');
            $insert['way_pt'] = 'GEOMETRYCOLLECTION('.$this->input->post('way_pt').')';
            $insert['distance'] = $this->input->post('distance');
            $insert['walk_time'] = $this->input->post('walk_time');
            $insert['create_at'] = date('Y-m-d H:i:s', time());
            $insert['create_id'] = $this->session->userdata('console_id');
            $insert['update_at'] = date('Y-m-d H:i:s', time());
            $insert['update_id'] = $this->session->userdata('console_id');

            $result = $this->course_model->insert($insert);

            if($result)
                redirect('/console/course/lists');
        }
    }

    function _edit() {

        $this->form_validation->set_rules('id', '필수갑을 입력하세', 'required');
        $this->form_validation->set_rules('title', '제목을 입력하세요', 'required');
        $this->form_validation->set_rules('st_pt', '제목을 입력하세요', 'required');
        $this->form_validation->set_rules('end_pt', '제목을 입력하세요', 'required');
        $this->form_validation->set_rules('way_pt', '제목을 입력하세요', 'required');
        $this->form_validation->set_rules('distance', '제목을 입력하세요', 'required');
        $this->form_validation->set_rules('walk_time', '제목을 입력하세요', 'required');
        
        if($this->form_validation->run() === false)
        {
            echo 'form error : ' . validation_errors(); 
        }
        else
        {
            $update['title'] = $this->input->post('title');
            $update['desc'] = $this->input->post('desc');
            $update['st_pt'] = $this->input->post('st_pt');
            $update['end_pt'] = $this->input->post('end_pt');
            $update['way_pt'] = 'GEOMETRYCOLLECTION('.$this->input->post('way_pt').')';
            $update['distance'] = $this->input->post('distance');
            $update['walk_time'] = $this->input->post('walk_time');
            $update['update_at'] = date('Y-m-d H:i:s', time());
            $update['update_id'] = $this->session->userdata('console_id');

            $where = array('id'=>$this->input->post('id'));
            $result = $this->course_model->update($where, $update);

            if($result)
                redirect('/console/course/form/' . $this->input->post('id'));
        }
    }

}