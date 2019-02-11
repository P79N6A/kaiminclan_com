<?php
/**
 *
 * 注册表
 *
 * 基础
 *
 */
class  FoundationRegistryService extends Service {
	
	
	public function getRegistryList($where){
		$count =  $this->model('FoundationRegistry')->where($where)->count();
		if($count){
			$listdata = $this->model('FoundationRegistry')->where($where)->select();
			
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	//医生计划
	public function getMedicinerDoctorPlan($code){
		$output = 0;
		$registryData = $this->get('mediciner_doctor_plan');
		if($registryData){
			if(isset($registryData[$code])){
				$output = $registryData[$code];
			}
		}
		return $output;
	}
	
	//商城规则
	public function getShop($code){
		$output = 0;
		$registryData = $this->get('shop');
		if($registryData){
			if(isset($registryData[$code])){
				$output = $registryData[$code];
			}
		}
		return $output;
	}
	
	//商城规则
	public function getShopAdmin($code){
		$output = 0;
		$registryData = $this->get('shopadmin');
		if($registryData){
			if(isset($registryData[$code])){
				$output = $registryData[$code];
			}
		}
		return $output;
	}
	
	//医分销
	public function getMedicinerDistribution($code){
		$output = 0;
		$registryData = $this->get('mediciner_distribution');
		if($registryData){
			if(isset($registryData[$code])){
				$output = $registryData[$code];
			}
		}
		return $output;
	}
	
	public function get($index){
		$where = array();
		$where['code'] = $index;
		$listdata = $this->getRegistryList($where);
		$listdata['list'] = current($listdata['list']);
		return json_decode($listdata['list']['valume'],true);
	}
	
	public function getRegistryByCode($code){
		
		$where = array(
			'code'=>$code
		);
		
		$registryData = $this->model('FoundationRegistry')->where($where)->find();
		if($registryData){
			$registryData['setting'] = json_decode($registryData['setting'],true);
		}
		return $registryData;
	}
	/**
	 *
	 * 地区信息
	 *
	 * @param $registryId 地区ID
	 *
	 * @reutrn array;
	 */
	public function getRegistryInfo($registryId){
		
		$registryData = array();
		
		$where = array(
			'identity'=>$registryId
		);
		
		$registryData = $this->model('FoundationRegistry')->where($where)->select();
		
		if(!is_array($registryId)){
			$registryData = current($registryData);
		}
		
		return $registryData;
	}
	
	/**
	 *
	 * 删除地区
	 *
	 * @param $registryId 地区ID
	 *
	 * @reutrn int;
	 */
	public function removeRegistryId($registryId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$registryId
		);
		
		$registryData = $this->model('FoundationRegistry')->where($where)->select();
		if($registryData){
			$output = $this->model('FoundationRegistry')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 地区修改
	 *
	 * @param $registryId 地区ID
	 * @param $registryNewData 地区数据
	 *
	 * @reutrn int;
	 */
	public function update($registryNewData,$registryId){
		$where = array(
			'identity'=>$registryId
		);
		
		$registryData = $this->model('FoundationRegistry')->where($where)->find();
		if($registryData){
			
			$registryNewData['lastupdate'] = $this->getTime();
			$result = $this->model('FoundationRegistry')->data($registryNewData)->where($where)->save();
			if($result){
			}
		}
		return $result;
	}
	
	/**
	 *
	 * 新地区
	 *
	 * @param $registryNewData 地区信息
	 *
	 * @reutrn int;
	 */
	public function insert($registryNewData){
		$registryNewData['subscriber_identity'] =$this->session('uid');		
		$registryNewData['dateline'] = $this->getTime();
			
		$registryNewData['lastupdate'] = $registryNewData['dateline'];
		$registryId = $this->model('FoundationRegistry')->data($registryNewData)->add();
	}
	
	private function initXml($bz){
		$xml = '<?xml version="1.0" encoding="utf-8"?>'.PHP_EOL;
		$xml .= '<config>'.PHP_EOL;
		$where = array();
		$where['bz'] = $bz;
		$listdata = $this->model('FoundationRegistry')->where($where)->select();
		
		if($listdata){
			foreach($listdata as $key=>$data){
				$xml .= '<route>'.PHP_EOL;
			}
		}
		$xml .= '</config>'.PHP_EOL;
		file_put_contents(__ROOT__.'/config/'.$bz.'.xml',$xml);
	}
}