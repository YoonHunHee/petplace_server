<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('admin_model');

		$this->load->library('encrypt');
	    $this->load->library('form_validation');
	}

	public function index()
	{
		if($this->session->userdata('logged_in'))
  			redirect('/console/main');
  		
		$this->load->view('console/login');
	}

	public function login()
	{
		$this->form_validation->set_rules('console_id', '아이디를 입력하세요', 'required|min_length[5]|max_length[20]');
		$this->form_validation->set_rules('console_pass', '비밀번호를 입력하세요', 'required|min_length[6]|max_length[20]');

		if($this->form_validation->run() === false)
	    {
	    	redirect('/console/login');
    	}
    	else
    	{
    		$id = $this->input->post('console_id');
    		$pass = $this->input->post('console_pass');
    		$row = $this->admin_model->get($id);
    		if($pass == $this->encrypt->decode($row->admin_pass))
    		{
    			$admin = array(
				        'console_id'   => $row->admin_id,
				        'console_name' => $row->admin_name,
				        'logged_in' => TRUE,
				        'toggle_is' => FALSE
				);

				$this->session->set_userdata($admin);

				redirect('/console/main');

    		} else {
    			redirect('/console/login');
    		}
    	}
	}

	public function logout() 
	{
		$array_items = array('console_id', 'console_name', 'logged_in');
		$this->session->unset_userdata($array_items);

		redirect('/console/login');
	}
}
