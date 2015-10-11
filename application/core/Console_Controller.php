<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Console_Controller extends CI_Controller {

	var $template = array();
	var $data = array();
	var $body = '';

	function __construct()
	{
  		parent::__construct();
  		if(!$this->session->userdata('logged_in'))
  			redirect('/console/login');
  	}

	public function layout() {
		$this->data['cur_uri'] = uri_string();

		$this->template['header'] = $this->load->view('console/layout/header', $this->data, true);
		$this->template['left'] = $this->load->view('console/layout/left', $this->data, true);
		$this->template['top'] = $this->load->view('console/layout/top', $this->data, true);
		if(!empty($this->body))
			$this->template['body'] = $this->load->view($this->body, $this->data, true);
		$this->load->view('console/layout/index', $this->template);
	}

    public function back($message) {

        $data['message'] = $message;
        $this->load->view('console/error', $data);

    }
}
