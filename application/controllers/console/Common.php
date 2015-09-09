<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends CI_Controller {

	public $daumApi;
	public $daumKey;

	function __construct()
	{
		parent::__construct();

		$this->daumApi = $this->config->item('daum_api');
		$this->daumKey = $this->config->item('daum_key');
	}

	public function get_menu_status()
	{
		echo $this->session->userdata('toggle_is');
	}

	public function set_menu_status()
	{
		$this->session->set_userdata('toggle_is', !$this->session->userdata('toggle_is'));

		echo $this->session->userdata('toggle_is');
	}

	public function get_latlng()
	{
		$lat = '';
		$lng = '';
		$addr = $this->input->post('addr');

		if(!empty($addr))
		{
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
		 	
		 	if(isset($json_array['item'])) {
			 	if($json_array['result'] > 1) {
			 		$lat = $json_array['item'][0]['lat'];
			 		$lng = $json_array['item'][0]['lng'];
				} else {
					$lat = $json_array['item']['lat'];
			 		$lng = $json_array['item']['lng'];
				}
			}
		}

		echo json_encode(array('lat' => $lat, 'lng' => $lng));
	}
}