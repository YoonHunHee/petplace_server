<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends Console_Controller {

	public $page_size;

  	function __construct()
  	{
		parent::__construct();
		$this->load->model('admin_model');

		$this->load->library('encrypt');
	  	$this->load->library('form_validation');

    	$this->page_size = $this->config->item('default_page_size');
	}

	public function lists()
	{
		$where = 'admin_id is not null';
		$total_count = $this->admin_model->total_count($where);
		$lists = $this->admin_model->lists($where, 0, $this->page_size);

		$this->data['list'] = $lists;

		$config['base_url'] = '/console/admin/lists';
		$config['total_rows'] = $total_count;
		$config['per_page'] = $this->page_size; 
		$this->pagination->initialize($config); 
		$this->data['paging'] = $this->pagination->create_links();

		$this->body = 'console/admin/lists';
		$this->layout();
	}

	public function form($id = '')
	{
		if(!empty($id)) {
			$row = $this->admin_model->get($id);
			$this->data['row'] = $row;
		}

		$this->body = 'console/admin/form';
		$this->layout();
	}

	public function action()
	{
		$mode = $this->input->post('mode');
		if($mode == 'regist') 
			$this->_regist();
		else
			$this->_edit();
	}

	public function _regist()
	{
		$this->form_validation->set_rules('admin_id', '아이디를 입력하세요', 'required|min_length[5]|max_length[20]');
	    $this->form_validation->set_rules('admin_name', '이름을 입력하세요', 'required');
	    $this->form_validation->set_rules('admin_pass', '비밀번호를 입력하세요', 'required|min_length[6]|max_length[20]|matches[admin_pass_re]');
	    $this->form_validation->set_rules('admin_pass_re', '비밀번호 확인', 'required');

	    if($this->form_validation->run() === false)
	    {
	    	echo 'form error : ' . validation_errors(); 
    	}
    	else
    	{
			$insert['admin_id'] = $this->input->post('admin_id');
			$insert['admin_name'] = $this->input->post('admin_name');
			$insert['admin_pass'] = $this->encrypt->encode($this->input->post('admin_pass'));
			$insert['create_at'] = date('Y-m-d H:i:s', time());
			$insert['update_at'] = date('Y-m-d H:i:s', time());
			$result = $this->admin_model->insert($insert);

    		if($result)
    			redirect('/console/admin/lists');
		}
	}

	public function _edit()
	{
		$this->form_validation->set_rules('admin_id', '아이디를 입력하세요', 'required|min_length[5]|max_length[20]');
		$this->form_validation->set_rules('admin_name', '이름 입력하세요', 'required');

		if($this->form_validation->run() === false)
	    {
	    	echo 'form error : ' . validation_errors(); 
    	}
    	else
    	{
    		$result = $this->admin_model->update();

    		if($result) {
    			redirect('/console/admin/form/'.$this->input->post('admin_id'));
    		}
    	}
	}

	public function del()
	{
		$this->form_validation->set_rules('admin_id', '아이디를 입력하세요', 'required|min_length[5]|max_length[20]');
		
		if($this->form_validation->run() === false)
	    {
	    	echo 'form error : ' . validation_errors(); 
    	}
    	else
    	{
    		$result = $this->admin_model->delete($this->input->post('admin_id'));

    		if($result) {
    			redirect('/console/admin/lists');
    		}
    	}
	}
}