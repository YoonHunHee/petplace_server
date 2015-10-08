<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Friends extends Console_Controller {

	public $page_size;
	public $daumApi;
	public $daumKey;

	function __construct()
	{
		parent::__construct();
		$this->load->model('friends_model');
		$this->load->library('form_validation');

		$this->page_size = $this->config->item('default_page_size');
		$this->daumApi = $this->config->item('daum_api');
		$this->daumKey = $this->config->item('daum_key');
	}

	public function lists($start = 0)
	{
		$where = 'lat is not null and lng is not null';
		$total_count = $this->friends_model->total_count($where);

		$this->data['list'] = $this->friends_model->lists($where, $start, $this->page_size);

		$config['base_url'] = '/console/friends/lists';
		$config['total_rows'] = $total_count;
		$config['per_page'] = $this->page_size; 
		$this->pagination->initialize($config); 
		$this->data['paging'] = $this->pagination->create_links();
		
		$this->body = 'console/friends/lists';
		$this->layout();
	}

	public function form($id = '')
	{
		$this->load->helper('form');

		if(!empty($id)) {
			$row = $this->friends_model->get($id);
			$this->data['row'] = $row;
		}

		$this->body = 'console/friends/form';
		$this->layout();
	}

	public function get_json()
	{
		$this->load->helper('download');

		$rows = $this->friends_model->lists('lat is not null and lan is not null');
		$data['friends'] = $rows->result_array();

		force_download('friends.json', json_encode($data));
		//$this->output->set_content_type('application/json')->set_output(json_encode($data));
	}

	public function action()
	{
		$mode = $this->input->post('mode');
		if($mode == 'regist') 
			$this->_regist();
		else
			$this->_edit();
	}

	public function del()
	{
		$this->form_validation->set_rules('id', '정상적인 접근이 아닙니다.', 'required');
		
		if($this->form_validation->run() === false)
    {
    	echo 'form error : ' . validation_errors(); 
  	}
  	else
  	{
  		$result = $this->friends_model->delete($this->input->post('id'));

  		if($result) {
  			redirect('/console/friends/lists');
  		}
  	}
	}

	function _regist()
	{
		$this->form_validation->set_rules('kind', '구분을 선택하세요', 'required');
    $this->form_validation->set_rules('title', '제목을 입력하세요', 'required');
    $this->form_validation->set_rules('addr', '주소를 입력하세요', 'required');

    if($this->form_validation->run() === false)
    {
    	echo 'form error : ' . validation_errors(); 
  	}
  	else
  	{
			$insert['kind'] = $this->input->post('kind');
			$insert['title'] = $this->input->post('title');
			$insert['tel'] = $this->input->post('tel');
			$insert['addr'] = $this->input->post('addr');
			$insert['road_addr'] = $this->input->post('road_addr');
			$insert['desc'] = $this->input->post('desc');
			$insert['create_at'] = date('Y-m-d H:i:s', time());
			$insert['create_id'] = $this->session->userdata('console_id');
			$insert['update_at'] = date('Y-m-d H:i:s', time());
			$insert['update_id'] = $this->session->userdata('console_id');
			$latlng = $this->_addressTolatlng($insert['addr']);

			if(!empty($latlng)) {
				$temp = explode(',', $latlng);
				$insert['lat'] = $temp[0];
				$insert['lng'] = $temp[1];
			}
				
			$result = $this->friends_model->insert($insert);

  		if($result)
  			redirect('/console/friends/lists');
		}
	}

	function _edit()
	{
		$this->form_validation->set_rules('id', '필수값이 존재하지 않습니다', 'required');
		$this->form_validation->set_rules('kind', '구분을 선택하세요', 'required');
	  $this->form_validation->set_rules('title', '제목을 입력하세요', 'required');
	  $this->form_validation->set_rules('addr', '주소를 입력하세요', 'required');

		if($this->form_validation->run() === false)
	  {
	  	echo 'form error : ' . validation_errors(); 
		}
		else
		{
	  	$update['kind'] = $this->input->post('kind');
			$update['title'] = $this->input->post('title');
			$update['tel'] = $this->input->post('tel');
			$update['addr'] = $this->input->post('addr');
			$update['road_addr'] = $this->input->post('road_addr');
			$update['desc'] = $this->input->post('desc');
			$update['update_at'] = date('Y-m-d H:i:s', time());
			$update['update_id'] = $this->session->userdata('console_id');
			$latlng = $this->_addressTolatlng($update['addr']);

			if(!empty($latlng)) {
				$temp = explode(',', $latlng);
				$update['lat'] = $temp[0];
				$update['lng'] = $temp[1];
			}
			$where = array('id'=>$this->input->post('id'));
			$result = $this->friends_model->update($where, $update);

			if($result) {
				redirect('/console/friends/form/'.$this->input->post('id'));
			}
  	}
	}

	function _addressTolatlng($addr = '') {

		$api_url = sprintf("%s?apikey=%s&q=%s&output=xml", $this->daumApi, $this->daumKey, urlencode($addr));
		$curl = curl_init($api_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$curl_response = curl_exec($curl);
		if ($curl_response === false) {
		    $info = curl_getinfo($curl);
		    curl_close($curl);
		}
		curl_close($curl);

		$xml = simplexml_load_string($curl_response);
		$json = json_encode($xml);

		$json_array = json_decode($json, true);
	 	$latlan = '';

	 	if(isset($json_array['item'])) {
		 	if($json_array['result'] > 1) {
				$latlan = $json_array['item'][0]['lat'] . ',' . $json_array['item'][0]['lng'];
			} else
				$latlan = $json_array['item']['lat'] . ',' . $json_array['item']['lng'];
		}

		return $latlan;
	}
}
