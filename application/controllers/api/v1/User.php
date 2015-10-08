<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public $result;

    function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->library('encrypt');

        if(empty($this->session->userdata('access_token')))
        {
            redirect($this->config->item('API_URL') . '/error/message/300');
        }
    }

    public function regist()
    {
        if(!is_array($this->input->post()) || sizeof($this->input->post()) == 0) {
            redirect($this->config->item('API_URL') . '/error/message/500');
        }

        $email = $this->input->post('email');
        $nick_name = $this->input->post('nick_name');
        $password = $this->input->post('password');
        $password_confirm = $this->input->post('password_confirm');

        if(!isset($email) || empty($email)) {
            redirect($this->config->item('API_URL') . '/error/message/500');
        }

        if(!isset($nick_name) || empty($nick_name)) {
            redirect($this->config->item('API_URL') . '/error/message/500');
        }

        if(!isset($password) || empty($password)) {
            redirect($this->config->item('API_URL') . '/error/message/500');
        }

        if(!isset($password_confirm) || empty($password_confirm)) {
            redirect($this->config->item('API_URL') . '/error/message/500');
        }

        if($password != $password_confirm) {
            redirect($this->config->item('API_URL') . '/error/message/501');
        }

        if($this->user_model->is_check($email) > 0) {
            redirect($this->config->item('API_URL') . '/error/message/302');
        }

        $insert['email'] = $email;
        $insert['nick_name'] = $nick_name;
        $insert['password'] = $this->encrypt->encode($password);
        $insert['create_at'] = date('Y-m-d H:i:s', time());
        $insert['update_at'] = date('Y-m-d H:i:s', time());
        $result = $this->user_model->insert($insert);

        if($result) {

            //session create
            $user = array(
                'user_email'        => $email,
                'user_nick_name'    => $nick_name
            );

            $this->session->set_userdata($user);

            $this->result = array('code' => 200, 'message' => 'success');
            $this->output->set_content_type('application/json')->set_output(json_encode($this->result));
            return;
        } else {
            redirect($this->config->item('API_URL') . '/error/message/900');
        }
    }
  
}
