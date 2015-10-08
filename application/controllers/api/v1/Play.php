<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Play extends CI_Controller {

    public $result;
    public $limit = 20;

    function __construct()
    {
        parent::__construct();

        $this->load->model('play_model');

        if(empty($this->session->userdata('access_token')))
        {
            redirect($this->config->item('API_URL') . '/error/message/300');
        }
    }

    /*

    */
    public function lists()
    {
        $start = $this->input->get('start');

        $where = 'id is not null';
        $list = $this->play_model->lists($where, $start, $this->limit);
        $this->result = array('code' => 200, 'message' => 'success', 'lists' => $list);
        $this->output->set_content_type('application/json')->set_output(json_encode($this->result));
    }

    /*
    
    */
    public function info($id = '')
    {
        if(empty($id))
            redirect($this->config->item('API_URL') . '/error/message/500');

        $info = $this->play_model->get($id);
        if(is_null($info))
            redirect($this->config->item('API_URL') . '/error/message/500');
        
        
        $this->result = array('code' => 200, 'message' => 'success', 'info' => $info);
        $this->output->set_content_type('application/json')->set_output(json_encode($this->result));
    }
}
