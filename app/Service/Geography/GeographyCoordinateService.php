<?php
/**
 *
 * 区域
 *
 * 地理
 *
 */
class  GeographyCoordinateService extends Service {
	
	private $appkey = '';
	
	/**
	 * 根据地址查询经纬度
	 *
	 * @param $address 地址
	 *
	 */
	public function getCoordinate($address){
		
		$output = array('latitude'=>0,'longitude'=>0);
		
		
		$url = 'http://apis.map.qq.com/ws/geocoder/v1/';
		
		$param = array('address'=>$address,'key'=>$this->config('map'));
				
		$map = $this->helper('curl')->init($url)->data($param)->fetch();
		if($map){
			$map = json_decode($map);
			if($map->status < 1){
				$output = array('latitude'=>$map->result->location->lat,'longitude'=>$map->result->location->lng);
			}
		}
		return $output;
	}
	/**
	 * 根据IP查询客户地址
	 *
	 * @param $cilentip IP地址
	 *
	 */
	public function getCity($ip){
		if($ip == '127.0.0.1'){
			$ip = '183.67.61.214';
		}
		$output = array();
		
		
		$url = 'http://apis.map.qq.com/ws/location/v1/ip';
		
		$param = array('ip'=>$ip,'key'=>$this->config('map'));
				
		$map = $this->helper('curl')->init($url)->data($param)->fetch();
		if($map){
			$map = json_decode($map);
			
			if($map->status < 1){
				$output = array('nation'=>$map->result->ad_info->nation,'city'=>$map->result->ad_info->city,'province'=>$map->result->ad_info->province,'latitude'=>$map->result->location->lat,'longitude'=>$map->result->location->lng);
			}
		}
		return $output;
	}
}