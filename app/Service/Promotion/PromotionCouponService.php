<?php
/**
 *
 * 优惠卷
 *
 * 促销
 *
 */
class  PromotionCouponService extends Service {
	
	
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
	public function getCouponList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('PromotionCoupon')->where($where)->count();
		if($count){
			$couponHandle = $this->model('PromotionCoupon')->where($where)->orderby($orderby);
			$start = intval($start);
			$perpage = intval($perpage);
			
			if($perpage > 0){
				$couponHandle = $couponHandle->limit($start,$perpage,$count);
			}
			$listdata = $couponHandle->select();
			
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	/**
	 *
	 * 活动信息
	 *
	 * @param $couponIds 活动ID
	 *
	 * @reutrn int;
	 */
	public function getCouponInfo($couponIds){
		$couponData = array();
		
		$where = array(
			'identity'=>$couponIds
		);
		
		$couponList = $this->model('PromotionCoupon')->where($where)->select();
		if($couponList){
			
			if(is_array($couponIds)){
				$couponData = $couponList;
			}else{
				$couponData = current($couponList);
			}
			
			
		}
		
		
		return $couponData;
	}
	
	
		
	/**
	 *
	 * 删除活动
	 *
	 * @param $couponId 活动ID
	 *
	 * @reutrn int;
	 */
	public function removeCouponId($couponId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$couponId
		);
		
		$couponData = $this->model('PromotionCoupon')->where($where)->count();
		if($couponData){
			
			$output = $this->model('PromotionCoupon')->where($where)->delete();
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
	public function checkCouponTitle($title){
		$couponId = array();		
		$where = array(
			'title'=>$title,
		);
		
		
		return $this->model('PromotionCoupon')->where($where)->count();
	}
	
	/**
	 *
	 * 活动修改
	 *
	 * @param $couponId 活动ID
	 * @param $couponNewData 活动数据
	 *
	 * @reutrn int;
	 */
	public function update($couponNewData,$couponId){
		$where = array(
			'identity'=>$couponId
		);
		
		$couponData = $this->model('PromotionCoupon')->where($where)->find();
		if($couponData){
			
			
			$couponNewData['lastupdate'] = $this->getTime();
			$this->model('PromotionCoupon')->data($couponNewData)->where($where)->save();
			
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
	public function insert($couponData){
		$dateline = $this->getTime();
		$couponData['subscriber_identity'] = $this->session('uid');
		$couponData['dateline'] = $dateline;
		$couponData['lastupdate'] = $dateline;
		$couponData['sn'] = $this->get_sn();
			
		$couponId = $this->model('PromotionCoupon')->data($couponData)->add();
		if($couponId){
		}
		return $couponId;
	}
}