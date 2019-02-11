<?php
/**
 *
 * 活动
 *
 * 促销
 *
 */
class  PromotionFlexibleService extends Service {
	
	
	/**
	 *
	 * 活动列表
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 订购列表;
	 */
	public function getFlexibleList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('PromotionFlexible')->where($where)->count();
		if($count){
			$flexibleHandle = $this->model('PromotionFlexible')->where($where)->orderby($orderby);
			$start = intval($start);
			$perpage = intval($perpage);
			
			if($perpage > 0){
				$flexibleHandle = $flexibleHandle->limit($start,$perpage,$count);
			}
			$listdata = $flexibleHandle->select();
			
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	/**
	 *
	 * 活动信息
	 *
	 * @param $flexibleIds 活动ID
	 *
	 * @reutrn int;
	 */
	public function getFlexibleInfo($flexibleIds){
		$flexibleData = array();
		
		$where = array(
			'identity'=>$flexibleIds
		);
		
		$flexibleList = $this->model('PromotionFlexible')->where($where)->select();
		if($flexibleList){
			
			if(is_array($flexibleIds)){
				$flexibleData = $flexibleList;
			}else{
				$flexibleData = current($flexibleList);
			}
			
			
		}
		
		
		return $flexibleData;
	}
	
	
		
	/**
	 *
	 * 删除活动
	 *
	 * @param $flexibleId 活动ID
	 *
	 * @reutrn int;
	 */
	public function removeFlexibleId($flexibleId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$flexibleId
		);
		
		$flexibleData = $this->model('PromotionFlexible')->where($where)->count();
		if($flexibleData){
			
			$output = $this->model('PromotionFlexible')->where($where)->delete();
		}
		
		return $output;
	}
		
	/**
	 *
	 * 检测活动
	 *
	 * @param $mobile 手机号码
	 *
	 * @reutrn int;
	 */
	public function checkFlexibleTitle($title){
		$flexibleId = array();		
		$where = array(
			'title'=>$title,
		);
		
		
		return $this->model('PromotionFlexible')->where($where)->count();
	}
	
	/**
	 *
	 * 活动修改
	 *
	 * @param $flexibleId 活动ID
	 * @param $flexibleNewData 活动数据
	 *
	 * @reutrn int;
	 */
	public function update($flexibleNewData,$flexibleId){
		$where = array(
			'identity'=>$flexibleId
		);
		
		$flexibleData = $this->model('PromotionFlexible')->where($where)->find();
		if($flexibleData){
			
			
			$flexibleNewData['lastupdate'] = $this->getTime();
			$this->model('PromotionFlexible')->data($flexibleNewData)->where($where)->save();
			
		}
	}
	
	/**
	 *
	 * 新活动
	 *
	 * @param $id 活动信息
	 * @param $idtype 活动信息
	 *
	 * @reutrn int;
	 */
	public function insert($flexibleData){
		$dateline = $this->getTime();
		$flexibleData['subscriber_identity'] = $this->session('uid');
		$flexibleData['dateline'] = $dateline;
		$flexibleData['lastupdate'] = $dateline;
		$flexibleData['sn'] = $this->get_sn();
			
		$flexibleId = $this->model('PromotionFlexible')->data($flexibleData)->add();
		if($flexibleId){
		}
		return $flexibleId;
	}
}