<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public $result;

    function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->library('encrypt');
        $this->load->helper('string');
    }

    /*
        Access Token 발급
    */
    public function authorize()
    {
        /*if(!is_array($this->input->post()) || sizeof($this->input->post()) == 0) {
            redirect($this->config->item('API_URL') . '/error/message/500');
        }*/

        $secret_key = $this->input->post('secret_key');
        $access_token = random_string('alnum', 32);

        $token = array('access_token' => $access_token);

        $this->session->set_userdata($token);

        $this->result = array('code' => 200, 'message' => 'access_token이 발급되었습니다.');
        $this->output->set_content_type('application/json')->set_output(json_encode($this->result));

    }

    /*
        Login 처리
    */
    public function login()
    {
        if(!is_array($this->input->post()) || sizeof($this->input->post()) == 0) {
            redirect($this->config->item('API_URL') . '/error/message/500');
        }


        $email = $this->input->post('email');
        $password = $this->input->post('password');

        if(!isset($email) || empty($email)) {
            $this->_userSessionReset();
            redirect($this->config->item('API_URL') . '/error/message/500');
        }

        if(!isset($password) || empty($password)) {
            $this->_userSessionReset();
            redirect($this->config->item('API_URL') . '/error/message/500');
        }

        if($this->user_model->is_check($email) != 1) {
            $this->_userSessionReset();
            redirect($this->config->item('API_URL') . '/error/message/500');
        }

        $row = $this->user_model->get_email($email);
        if($password != $this->encrypt->decode($row->password)) {
            $this->_userSessionReset();
            redirect($this->config->item('API_URL') . '/error/message/301');
        }

        if(empty($this->session->userdata('access_token')))
            $access_token = random_string('alnum', 32);
        else
            $access_token = $this->session->userdata('access_token');

        $user = array(
            'user_email'        => $row->email,
            'user_nick_name'    => $row->nick_name,
            'access_token'      => $access_token  
        );

        $this->session->set_userdata($user);

        $this->result = array('code' => 200, 'message' => 'success');
        $this->output->set_content_type('application/json')->set_output(json_encode($this->result));
    }

    /*
        로그아웃 처리
    */
    public function logout()
    {
        $array_items = array('user_email', 'user_nick_name', 'access_token');
        $this->session->unset_userdata($array_items);

        $this->result = array('code' => 200, 'message' => 'success');
        $this->output->set_content_type('application/json')->set_output(json_encode($this->result));
    }

    /*
        access token 발급여부
    */
    public function getAuthorized()
    {
        if($this->session->userdata('access_token') && $this->session->userdata('user_email'))
        { 
            $this->result = array('code' => 200, 'message' => '서버 인증이 되어있습니다.');
            $this->output->set_content_type('application/json')->set_output(json_encode($this->result));
        } 
        else
        {
            redirect($this->config->item('API_URL') . '/error/message/300');
            return;
        }
    }

    /*
        user session 생성 여부
    */
    public function getLogined()
    {
        if($this->session->userdata('user_email'))
        {
            $this->result = array('code' => 200, 'message' => '로그인 되어있습니다.');
            $this->output->set_content_type('application/json')->set_output(json_encode($this->result));
        } 
        else
        {
            $this->result = array('code' => 300, 'message' => '로그인이 필요합니다. ');
            $this->output->set_content_type('application/json')->set_output(json_encode($this->result));
        }
    }

    /*
        user session 리셋
    */
    function _userSessionReset()
    {
        $array_items = array('user_email', 'user_nick_name');
        $this->session->unset_userdata($array_items);
    }

}
