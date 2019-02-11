<?php
/**
 *
 * 类型
 *
 * 促销
 *
 */
class  PromotionStyleService extends Service {
	
	
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
	public function getStyleList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('PromotionStyle')->where($where)->count();
		if($count){
			$styleHandle = $this->model('PromotionStyle')->where($where)->orderby($orderby);
			$start = intval($start);
			$perpage = intval($perpage);
			
			if($perpage > 0){
				$styleHandle = $styleHandle->limit($start,$perpage,$count);
			}
			$listdata = $styleHandle->select();
			
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	/**
	 *
	 * 收藏信息
	 *
	 * @param $styleIds 收藏ID
	 *
	 * @reutrn int;
	 */
	public function getStyleInfo($styleIds){
		$styleData = array();
		
		$where = array(
			'identity'=>$styleIds
		);
		
		$styleList = $this->model('PromotionStyle')->where($where)->select();
		if($styleList){
			
			if(is_array($styleIds)){
				$styleData = $styleList;
			}else{
				$styleData = current($styleList);
			}
			
			
		}
		
		
		return $styleData;
	}
	
	
		
	/**
	 *
	 * 删除收藏
	 *
	 * @param $styleId 收藏ID
	 *
	 * @reutrn int;
	 */
	public function removeStyleId($styleId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$styleId
		);
		
		$styleData = $this->model('PromotionStyle')->where($where)->count();
		if($styleData){
			
			$output = $this->model('PromotionStyle')->where($where)->delete();
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
	public function checkStyleTitle($title){
		$styleId = array();		
		$where = array(
			'title'=>$title,
		);
		
		
		return $this->model('PromotionStyle')->where($where)->count();
	}
	
	/**
	 *
	 * 收藏修改
	 *
	 * @param $styleId 收藏ID
	 * @param $styleNewData 收藏数据
	 *
	 * @reutrn int;
	 */
	public function update($styleNewData,$styleId){
		$where = array(
			'identity'=>$styleId
		);
		
		$styleData = $this->model('PromotionStyle')->where($where)->find();
		if($styleData){
			
			
			$styleNewData['lastupdate'] = $this->getTime();
			$this->model('PromotionStyle')->data($styleNewData)->where($where)->save();
			
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
	public function insert($styleData){
		$dateline = $this->getTime();
		$styleData['subscriber_identity'] = $this->session('uid');
		$styleData['dateline'] = $dateline;
		$styleData['lastupdate'] = $dateline;
		$styleData['sn'] = $this->get_sn();
			
		$styleId = $this->model('PromotionStyle')->data($styleData)->add();
		if($styleId){
		}
		return $styleId;
	}
}