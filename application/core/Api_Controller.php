<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class API_Controller extends CI_Controller {

    public function json($code, $message = '', $data = '')
    {
        $output = array('code' => (int)$code, 'message' => $message);
        if(isset($data) && !is_null($data) && !empty($data))
            $output['data'] = $data;
        
        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    public function checkAccessToken() {
        if(empty($this->session->userdata('access_token'))) {
            $this->json(300, '발급된 access_token이 존재하지 않습니다.');
            return false;
        }

        return true;
    }

    public function checkPost() {
        if(!is_array($this->input->post()) || sizeof($this->input->post()) == 0) {
            $this->json(500, '정상적인 접근이 아닙니다.');
            return false;
        }

        return ture;
    }
	
}
