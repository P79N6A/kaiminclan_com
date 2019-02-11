<?php

class FaultinessCatalogueService extends Service {
	
	
	/**
	 *
	 * 反馈信息
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 反馈列表;
	 */
	public function getCatalogueList($where = array(),$start = 1,$perpage = 10,$orderby = 'identity desc'){
		
		$count = $this->model('FaultinessCatalogue')->where($where)->count();
		if($count){
			$selectHandle = $this->model('FaultinessCatalogue')->where($where);
			if($perpage > 0){
				$selectHandle->limit($start,$perpage,$count);
			}
			if($orderby){
				$selectHandle ->order($orderby);
			}
			$listdata = $selectHandle->select();	
			
			$liabilitySubscriberIdentity = $subjectIds = $platformIds = array();
			foreach($listdata as $key=>$data){
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>FaultinessCatalogueModel::getStatusTitle($data['status'])
				);
				$liabilitySubscriberIdentity[] = $data['subscriber_identity'];
			}
			
			
			$subjectIds = $platformIds = array();
			foreach($listdata as $key=>$data){
				$listdata[$key]['subscriber'] = isset($subscriberData[$data['subscriber_identity']])?$subscriberData[$data['subscriber_identity']]:array();
			}
			
		}
		
		return array('total'=>$count,'list'=>$listdata);
	}
	
	/**
	 *
	 * 反馈信息
	 *
	 * @param $catalogueId 反馈ID
	 *
	 * @reutrn array;
	 */
	public function getCatalogueInfo($catalogueId){
		
		$catalogueData = array();
		
		$where = array(
			'identity'=>$catalogueId
		);
		
		$catalogueList = $this->model('FaultinessCatalogue')->where($where)->select();
		if($catalogueList){
			if(!is_array($catalogueId)){
				$catalogueData = current($catalogueList);
			}else{
				$catalogueData = $catalogueList;
			}
		
		}
		
		return $catalogueData;
	}
	
	/**
	 *
	 * 反馈信息
	 *
	 * @param $catalogueId 反馈ID
	 *
	 * @reutrn array;
	 */
	public function checkCatalogueTitle($title){
		
		
		$where = array(
			'title'=>$title
		);
		
		return $this->model('FaultinessCatalogue')->where($where)->count();
	}
	
	/**
	 *
	 * 删除反馈
	 *
	 * @param $catalogueId 反馈ID
	 *
	 * @reutrn int;
	 */
	public function removeCatalogueId($catalogueId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$catalogueId
		);
		
		$catalogueData = $this->model('FaultinessCatalogue')->where($where)->select();
		if($catalogueData){
			$output = $this->model('FaultinessCatalogue')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 反馈修改
	 *
	 * @param $catalogueId 反馈ID
	 * @param $catalogueNewData 反馈数据
	 *
	 * @reutrn int;
	 */
	public function update($catalogueNewData,$catalogueId){
		$where = array(
			'identity'=>$catalogueId
		);
		
		$catalogueData = $this->model('FaultinessCatalogue')->where($where)->find();
		if($catalogueData){
			
			$catalogueNewData['lastupdate'] = $this->getTime();
			$result = $this->model('FaultinessCatalogue')->data($catalogueNewData)->where($where)->save();
			
			
		}
		return $result;
	}
	
	public function getCode($content){
		return md5($content.$this->getClientIp().$this->getDeviceCode());
	}
	
	/**
	 *
	 * 检测消息码是否存在
	 *
	 * @param $code 识别码
	 *
	 * @reutrn int;
	 */
	public function checkCode($code){
		$where = array();
		$where['code'] = $code;
		return $this->model('FaultinessCatalogue')->where($where)->count();
	}
	
	/**
	 *
	 * 新反馈
	 *
	 * @param $catalogueNewData 反馈信息
	 *
	 * @reutrn int;
	 */
	public function insert($catalogueNewData){
		$catalogueNewData['subscriber_identity'] =$this->session('uid');		
		$catalogueNewData['dateline'] = $this->getTime();
			
		$catalogueNewData['lastupdate'] = $catalogueNewData['dateline'];
		
		$catalogueNewData['sn'] = date('Ymd').'-'.mt_rand(1,1000);
		
		$catalogueId = $this->model('FaultinessCatalogue')->data($catalogueNewData)->add();
		return $catalogueId;
	}
}