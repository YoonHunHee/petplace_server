<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Play extends API_Controller {

    public $limit = 20;

    function __construct()
    {
        parent::__construct();

        $this->load->model('play_model');
    }

    /**
     * [lists description]
     * @return [type] [description]
     */
    public function lists()
    {
        if($this->checkAccessToken())
        {
            $start = $this->input->get('start');

            $where = 'id is not null';
            $list = $this->play_model->lists($where, $start, $this->limit);
            $this->json(200, 'success', $list);
        }
    }

    /**
     * [info description]
     * @param  string $id [description]
     * @return [type]     [description]
     */
    public function info($id = '')
    {
        if($this->checkAccessToken())
        {
            if(empty($id))
            {
                $this->json(500, '정상적인 접근이 아닙니다.');
                return;
            }    

            $data = $this->play_model->get($id);
            if(is_null($data))
            {
                $this->json(901, '죄송합니다 데이터를 불러올 수 없습니다.');
                return;
            }
            
            $this->json(200, 'success', $data);
        }        
    }
}
