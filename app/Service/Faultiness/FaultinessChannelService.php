<?php

class FaultinessChannelService extends Service {
	
	
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
	public function getChannelList($where = array(),$start = 1,$perpage = 10,$orderby = 'identity desc'){
		
		$count = $this->model('FaultinessChannel')->where($where)->count();
		if($count){
			$selectHandle = $this->model('FaultinessChannel')->where($where);
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
					'label'=>FaultinessChannelModel::getStatusTitle($data['status'])
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
	 * @param $channelId 反馈ID
	 *
	 * @reutrn array;
	 */
	public function getChannelInfo($channelId){
		
		$channelData = array();
		
		$where = array(
			'identity'=>$channelId
		);
		
		$channelList = $this->model('FaultinessChannel')->where($where)->select();
		if($channelList){
			if(!is_array($channelId)){
				$channelData = current($channelList);
			}else{
				$channelData = $channelList;
			}
		
		}
		
		return $channelData;
	}
	
	/**
	 *
	 * 反馈信息
	 *
	 * @param $channelId 反馈ID
	 *
	 * @reutrn array;
	 */
	public function checkChannelTitle($title){
		
		
		$where = array(
			'title'=>$title
		);
		
		return $this->model('FaultinessChannel')->where($where)->count();
	}
	
	/**
	 *
	 * 删除反馈
	 *
	 * @param $channelId 反馈ID
	 *
	 * @reutrn int;
	 */
	public function removeChannelId($channelId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$channelId
		);
		
		$channelData = $this->model('FaultinessChannel')->where($where)->select();
		if($channelData){
			$output = $this->model('FaultinessChannel')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 反馈修改
	 *
	 * @param $channelId 反馈ID
	 * @param $channelNewData 反馈数据
	 *
	 * @reutrn int;
	 */
	public function update($channelNewData,$channelId){
		$where = array(
			'identity'=>$channelId
		);
		
		$channelData = $this->model('FaultinessChannel')->where($where)->find();
		if($channelData){
			
			$channelNewData['lastupdate'] = $this->getTime();
			$result = $this->model('FaultinessChannel')->data($channelNewData)->where($where)->save();
			
			
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
		return $this->model('FaultinessChannel')->where($where)->count();
	}
	
	/**
	 *
	 * 新反馈
	 *
	 * @param $channelNewData 反馈信息
	 *
	 * @reutrn int;
	 */
	public function insert($channelNewData){
		$channelNewData['subscriber_identity'] =$this->session('uid');		
		$channelNewData['dateline'] = $this->getTime();
			
		$channelNewData['lastupdate'] = $channelNewData['dateline'];
		
		$channelNewData['sn'] = date('Ymd').'-'.mt_rand(1,1000);
		
		$channelId = $this->model('FaultinessChannel')->data($channelNewData)->add();
		return $channelId;
	}
}