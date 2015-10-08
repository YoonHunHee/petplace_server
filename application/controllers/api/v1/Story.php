<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Story extends CI_Controller {

    public $result;
    public $limit = 20;

    function __construct()
    {
        parent::__construct();

        if(empty($this->session->userdata('access_token')))
        {
            redirect($this->config->item('API_URL') . '/error/message/300');
        }
    }
}
