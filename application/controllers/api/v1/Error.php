<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error extends CI_Controller {

    public $result;
    public $_message = array(
        '300' => array(
            'error' => 'access_token is empty',
            'message' => 'access_token이 발급되어있지 않습니다.'
        ),
        '301' => array(
            'error' => 'login faile',
            'message' => '사용자 정보가 일치하지 않습니다.'
        ),
        '302' => array(
            'error' => 'user already regist',
            'message' => '이미 등록된 사용자 정보입니다.'
        ),
        '500' => array(
            'error' => 'parameter is empty',
            'message' => '정상적인 접금이 아닙니다.'
        ),
        '501' => array(
            'error' => 'parameter is empty',
            'message' => '파라미터가 일치하지 않습니다.'
        ),
        '900' => array(
            'error' => 'database error',
            'message' => '데이터 처리에 실패하였습니다.'
        )
    );

    public function message($code = '') {
        if(!empty($code)) {
            if(isset($this->_message[$code]) && is_array($this->_message[$code])) {
                $error = $this->_message[$code]['error'];
                $message = $this->_message[$code]['message'];



                $this->result = array('code' => (int)$code, 'error' => $error, 'message' => $message);
                $this->output->set_content_type('application/json')->set_output(json_encode($this->result));
            }
        }
    }
}