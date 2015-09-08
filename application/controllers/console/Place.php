<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Place extends Console_Controller {

	public $page_size = 10;

	public $daumApi = 'https://apis.daum.net/local/geo/addr2coord';
	public $daumKey = 'd86641dfd0563f3fd48a194de27462e9';

	function __construct()
	{
  		parent::__construct();
  		$this->load->model('place_model');
  		$this->load->library('form_validation');
  	}

	public function lists($start = 0)
	{
		$where = 'lat is not null and lng is not null';
		$total_count = $this->place_model->total_count($where);

		$this->data['list'] = $this->place_model->lists($where, $start, $this->page_size);

		$config['base_url'] = '/console/place/lists';
		$config['total_rows'] = $total_count;
		$config['per_page'] = $this->page_size; 
		$this->pagination->initialize($config); 
		$this->data['paging'] = $this->pagination->create_links();
		
		$this->body = 'console/place/lists';
		$this->layout();
	}

	public function form($id = '')
	{
		$this->load->helper('form');

		if(!empty($id)) {
			$row = $this->place_model->get($id);
			$this->data['row'] = $row;
		}

		$this->body = 'console/place/form';
		$this->layout();
	}

	public function get_json()
	{
		$this->load->helper('download');

		$rows = $this->place_model->lists('lat is not null and lan is not null');
		$data['places'] = $rows->result_array();

		force_download('places.json', json_encode($data));
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
    		$result = $this->place_model->delete($this->input->post('id'));

    		if($result) {
    			redirect('/console/place/lists');
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
			$insert['update_at'] = date('Y-m-d H:i:s', time());
			$latlng = $this->_addressTolatlng($insert['addr']);

			if(!empty($latlng)) {
				$temp = explode(',', $latlng);
				$insert['lat'] = $temp[0];
				$insert['lng'] = $temp[1];
			}
				
			$result = $this->place_model->insert($insert);

    		if($result)
    			redirect('/console/place/lists');
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
			$latlng = $this->_addressTolatlng($update['addr']);

			if(!empty($latlng)) {
				$temp = explode(',', $latlng);
				$update['lat'] = $temp[0];
				$update['lng'] = $temp[1];
			}
			$where = array('id'=>$this->input->post('id'));
    		$result = $this->place_model->update($where, $update);

    		if($result) {
    			redirect('/console/place/form/'.$this->input->post('id'));
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
