<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends API_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->library('encrypt');
    }

    /**
     * [authorize description]
     * @return [type] [description]
     */
    public function login()
    {
        if($this->checkAccessToken())
        {
            if($this->checkPost())
            {
                // set parameters
                $email = $this->input->post('email');
                $password = $this->input->post('password');

                // check parameter
                if(!isset($email) || empty($email)) {
                    $this->json(501, '이메일을 입력해주세요.');
                    $this->_userSessionReset();
                    return;
                }

                if(!isset($password) || empty($password)) {
                    $this->json(501, '비밀번호를 입력해주세요.');
                    $this->_userSessionReset();
                    return;
                }

                if($this->user_model->is_check($email) != 1) {
                    $this->json(301, '이미 등록된 이메일주소입니다.');
                    $this->_userSessionReset();
                    return;
                }

                // get user info
                $user = $this->user_model->get_email($email);
                if($password != $this->encrypt->decode($user->password)) {
                    $this->json(302, '일치하는 정보가 존재하지 않습니다.');
                    $this->_userSessionReset();
                    return;
                }

                $user_session = array(
                    'user_email'        => $row->email,
                    'user_nick_name'    => $row->nick_name
                );

                $this->session->set_userdata($user_session);

                $this->json(200, 'success');
            }
        }
    }

    /**
     * [logout description]
     * @return [type] [description]
     */
    public function logout()
    {
        $array_items = array('user_email', 'user_nick_name');
        $this->session->unset_userdata($array_items);
        $this->json(200, 'success');
    }

    /**
     * [_userSessionReset description]
     * @return [type] [description]
     */
    function _userSessionReset()
    {
        $array_items = array('user_email', 'user_nick_name');
        $this->session->unset_userdata($array_items);
    }

    /**
     * [regist description]
     * @return [type] [description]
     */
    public function regist()
    {
        if($this->checkAccessToken())
        {
            if($this->checkPost())
            {
                $email = $this->input->post('email');
                $nick_name = $this->input->post('nick_name');
                $password = $this->input->post('password');
                $password_confirm = $this->input->post('password_confirm');

                // check parameter
                if(!isset($email) || empty($email)) {
                    $this->json(501, '이메일을 입력해주세요.');
                    return;
                }

                if(!isset($nick_name) || empty($nick_name)) {
                    $this->json(501, '닉네임을 입력해주세요.');
                    return;
                }

                if(!isset($password) || empty($password)) {
                    $this->json(501, '비밀번호를 입력해주세요.');
                    return;
                }

                if(!isset($password_confirm) || empty($password_confirm)) {
                    $this->json(501, '비밀번호확인을 입력해주세요.');
                    return;
                }

                if($password != $password_confirm) {
                    $this->json(501, '비밀번호가 일치하지 않습니다.');
                    return;
                }

                if($this->user_model->is_check($email) > 0) {
                    $this->json(301, '이미 등록된 이메일주소입니다.');
                    return;
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

                    $this->json(200, 'success');
                    return;
                } else {
                    $this->json(900, '죄송합니다. 데이터를 처리할 수 없습니다.');
                }
            }
        }
    }

}
