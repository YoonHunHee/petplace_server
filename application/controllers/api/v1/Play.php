<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Play extends API_Controller {

    public $limit = 20;

    function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
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

    public function regist()
    {
        if($this->checkAccessToken())
        {
            // if($this->checkPost())
            // {
                $title = $this->input->post('title');
                $desc = $this->input->post('desc');
                $st_pt = $this->input->post('st_pt');
                $end_pt = $this->input->post('end_pt');
                $way_pt = $this->input->post('way_pt');
                $distance = $this->input->post('distance');
                $walk_time = $this->input->post('walk_time');

                // check parameter
                // if(!isset($title) || empty($title)) {
                //     $this->json(501, '제목을 입력해주세요.');
                //     return;
                // }

                // if(!isset($st_pt) || empty($st_pt)) {
                //     $this->json(501, '시작지점 입력해주세요.');
                //     return;
                // }

                $user = $this->user_model->get_email($this->session->userdata('user_email'));
                print_r($user);

                $insert['title'] = $title;
                $insert['desc'] = $desc;
                $insert['st_pt'] = $st_pt;
                $insert['end_pt'] = $end_pt;
                $insert['way_pt'] = 'GEOMETRYCOLLECTION('.$way_pt.')';
                $insert['distance'] = $distance;
                $insert['walk_time'] = $walk_time;


                $insert['create_at'] = date('Y-m-d H:i:s', time());
                $insert['create_id'] = $this->session->userdata('console_id');
                $insert['update_at'] = date('Y-m-d H:i:s', time());
                $insert['update_id'] = $this->session->userdata('console_id');


            // }
        }
    }
}
