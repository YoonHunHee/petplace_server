<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends API_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model('version_model');
    }

    /**
     * application auth - access_token publication
     * @return json format
     * [code]       result code
     * [message]    result message
     */
    public function auth()
    {
        // if($this->checkPost())
        // {
            $this->load->helper('string');

            $secret_key = $this->input->post('secret_key');

            //todo : secret key checked

            //access token generated
            $access_token = random_string('alnum', 32);

            //access token session created
            $token = array('access_token' => $access_token);
            $this->session->set_userdata($token);    

            $this->json(200, 'suucess');
        // }
    }

    /**
     * application & data version
     * @return json format
     * [code]       result code
     * [message]    result message
     * [data]       result data
     */
    public function version()
    {
        if($this->checkAccessToken())
        {
            $data = $this->version_model->get();
            $json;
            if(sizeof($data) > 0) {
                $json['data_version'] = $data->data_version;
                $json['app_version'] = $data->app_version;
            }

            $this->json(200, 'success', $json);
        }
    }

    /**
     * [getSession description]
     * @return [type] [description]
     */
    public function session()
    {
        // if($this->checkPost())
        // {
            
            $secret_key = $this->input->post('secret_key');

            //todo : secret key checked

            $session = array(
                'access_token'  => $this->session->userdata('access_token'),
                'user_email'    => $this->session->userdata('user_email')
            );

            $this->json(200, 'sucess', $session);
        }
    }

    /**
     * [getData description]
     * @return [type] [description]
     */
    public function data()
    {
        if($this->checkAccessToken())
        {
            $this->load->helper('file');
            // $this->load->helper('download');

            //$version = $this->version_model->get();

            $file = $_SERVER['DOCUMENT_ROOT'] . '/application/data/test_data.json';

            $data = read_file($file);
            if(empty($data))
            {
                return;
            }

            $this->json(200, 'success', $data);
            //force_download('0.1.1.json', $data);
        }
    }

}