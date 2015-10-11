<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gather extends CI_Controller {

	public $url = 'http://openapi.naver.com/search';
	public $mapUrl = 'http://openapi.map.naver.com/api/geocode';
	public $key = '41c3947d6a5d36f4451e2d9311d6eff5';
	public $mapKey = '29c5ee8706475ddafdb53c782403e193';

	public $daumApi = $this->config->item('daum_api');
	public $daumKey = $this->config->item('daum_key');

	public function getHospitalData()
	{
		
	}

	public function local_hostipal($query = '충청북도동물병원')
	{
		$count = 1;
		$total = 0;
		//while ($count != $total)
		//{
			$json_data = $this->_neverAPI($query, $count);
	        $total = $json_data['channel']['total'];
	        echo 'total : ' . $total;
	        
	        if(isset($json_data['channel']['item']))
	        {
		        $items = $json_data['channel']['item'];
		        foreach ($items as $val) {
		        	$title = strip_tags($val['title']);
		        	$description = '';
		        	$link = '';
		        	$telephone = '';
		        	$address = $val['address'];
		        	$latlag = '';
		        	$roadAddress = '';

		         	if(!is_array($val['description']))
		         		$description = strip_tags($val['description']);

		         	if(!is_array($val['link']))
		         		$link = $val['link'];

		         	if(!is_array($val['telephone']))
		         		$telephone = $val['telephone'];
		         	
		         	if(!is_array($val['roadAddress']))
		         		$roadAddress = $val['roadAddress'];

		         	$this->db->set('kind', '동물병원');
					$this->db->set('title', $title);
					$this->db->set('desc', $description);
					$this->db->set('link', $link);
					$this->db->set('addr', $address);
					$this->db->set('road_addr', $roadAddress);
					$this->db->set('tel', $telephone);
					$this->db->set('create_at', date('Y-m-d H:i:s', time()));
			        $this->db->set('update_at', date('Y-m-d H:i:s', time()));
			        $this->db->insert('tb_place'); 	

		         	$count++;
		        }

		        echo '<br/>next : ' . $count;
		    }
		//}
	}

	function _neverAPI($query = '', $start = 1) {
		$api_url = sprintf("%s?key=%s&query=%s&target=local&start=%d&display=100", $this->url, $this->key, $query, $start);
		echo '<br/>url : ' . $api_url;
	    $data =file_get_contents($api_url);
	    $xml = simplexml_load_string($data);
	    $json = json_encode($xml);
	    $json_array = json_decode($json, true);
	    return $json_array;
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

	 	print_r($json_array);

	 	if(isset($json_array['item'])) {
		 	if($json_array['result'] > 1) {
				$latlan = $json_array['item'][0]['lat'] . ',' . $json_array['item'][0]['lng'];
			} else
				$latlan = $json_array['item']['lat'] . ',' . $json_array['item']['lng'];
		}

		return $latlan;
	}

	public function local_latlng() {
		/*$query = $this->db->query('select id, addr from tb_place where lat = 0');
		foreach ($query->result_array() as $row)
		{
			echo $row['addr'] . '<br/>';
			$latlng = $this->_addressTolatlng($row['addr']);
			$temp = explode(',', $latlng);
			
			$lat = $temp[0];
			$lng = $temp[1];

			echo $lat . '//' . $lng . '<br/>';
			$this->db->set('lat', $lat);
			$this->db->set('lan', $lng);
			$this->db->where('id', $row['id']);
			$this->db->update('tb_place');
		}*/

		$addr = '충청남도 아산시 배방읍 모산로12번길 6 예림당서적';
		echo $this->_addressTolatlng($addr);
	}
}
