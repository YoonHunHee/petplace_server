<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends API_Controller {

    function __construct()
    {
        parent::__construct();
    }

    public function auth()
    {
        if($this->checkPost())
        {
            $this->load->helper('string');

            $secret_key = $this->input->post('secret_key');

            //todo : secret key checked

            //access token generated
            $access_token = random_string('alnum', 32);

            //access token session created
            $token = array('access_token' => $access_token);
            $this->session->set_userdata($token);    

            $this->json(200, 'suucess');
        }
    }

    public function getVersion()
    {
        if($this->checkAccessToken())
        {
            $this->load->model('version_model');

            $data = $this->version_model->get();
            $json;
            if(sizeof($data) > 0) {
                $json['data_version'] = $data->data_version;
                $json['app_version'] = $data->app_version;
            }

            $this->json(200, 'success', $json);
        }
    }

    public function getCurrentData()
    {
        if($this->checkAccessToken())
        {
            // json file retun
        }
    }

}