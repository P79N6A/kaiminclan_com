<?php
/**
 *
 * 客户
 *
 * 账户
 *
 */
class  CustomerClienteteService extends Service {
	
	
	/**
	 *
	 * 收藏列表
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 订购列表;
	 */
	public function getClienteteList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('CustomerClientete')->where($where)->count();
		if($count){
			$clienteteHandle = $this->model('CustomerClientete')->where($where)->orderby($orderby);
			$start = intval($start);
			$perpage = intval($perpage);
			
			if($perpage > 0){
				$clienteteHandle = $clienteteHandle->limit($start,$perpage,$count);
			}
			$listdata = $clienteteHandle->select();
			$distinctionIds = array();
			foreach($listdata as $key=>$data){
				$distinctionIds[] = $data['distinction_identity'];
			}
			$distinctionData = $this->service('CustomerDistinction')->getDistinctionInfo($distinctionIds);
			
			foreach($listdata as $key=>$data){
				$listdata[$key]['distinction'] = isset($distinctionData[$data['distinction_identity']])?$distinctionData[$data['distinction_identity']]:array();
			}
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	/**
	 *
	 * 收藏信息
	 *
	 * @param $clienteteIds 收藏ID
	 *
	 * @reutrn int;
	 */
	public function getClienteteInfo($clienteteIds){
		$clienteteData = array();
		
		$where = array(
			'identity'=>$clienteteIds
		);
		
		$clienteteList = $this->model('CustomerClientete')->where($where)->select();
		if($clienteteList){
			
			if(is_array($clienteteIds)){
				$clienteteData = $clienteteList;
			}else{
				$clienteteData = current($clienteteList);
			}
			
			
		}
		
		
		return $clienteteData;
	}
	
	
		
	/**
	 *
	 * 删除收藏
	 *
	 * @param $clienteteId 收藏ID
	 *
	 * @reutrn int;
	 */
	public function removeClienteteId($clienteteId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$clienteteId
		);
		
		$clienteteData = $this->model('CustomerClientete')->where($where)->count();
		if($clienteteData){
			
			$output = $this->model('CustomerClientete')->where($where)->delete();
		}
		
		return $output;
	}
		
	/**
	 *
	 * 检测收藏
	 *
	 * @param $mobile 手机号码
	 *
	 * @reutrn int;
	 */
	public function checkClienteteMobile($mobile){
		$clienteteId = array();		
		$where = array(
			'mobile'=>$mobile,
		);
		
		
		return $this->model('CustomerClientete')->where($where)->count();
	}
	
	/**
	 *
	 * 收藏修改
	 *
	 * @param $clienteteId 收藏ID
	 * @param $clienteteNewData 收藏数据
	 *
	 * @reutrn int;
	 */
	public function update($clienteteNewData,$clienteteId){
		$where = array(
			'identity'=>$clienteteId
		);
		
		$clienteteData = $this->model('CustomerClientete')->where($where)->find();
		if($clienteteData){
			
			if($clienteteNewData['mobile'] != $clienteteData['mobile']){
				$isValid = $this->service('AuthoritySubscriber')->changeClienteteMobileByClientId($clienteteId,$clienteteNewData['mobile']);
				if(!$isValid){
					return -1;
				}
			}
			
			$clienteteNewData['lastupdate'] = $this->getTime();
			$this->model('CustomerClientete')->data($clienteteNewData)->where($where)->save();
			
		}
	}
	
	/**
	 *
	 * 新收藏
	 *
	 * @param $id 收藏信息
	 * @param $idtype 收藏信息
	 *
	 * @reutrn int;
	 */
	public function insert($clienteteData){
		$dateline = $this->getTime();
		$clienteteData['subscriber_identity'] = $this->session('uid');
		$clienteteData['dateline'] = $dateline;
		$clienteteData['lastupdate'] = $dateline;
		$clienteteData['sn'] = $this->get_sn();
			
		$clienteteId = $this->model('CustomerClientete')->data($clienteteData)->add();
		if($clienteteId){
			$this->service('AuthoritySubscriber')->newClienteteUser($clienteteId,$clienteteData['mobile'],$clienteteData['fullname']);
		}
		return $clienteteId;
	}
}