<?php
/**
 *
 * 客户
 *
 * 账户
 *
 */
class  PropertyTheaterService extends Service {
	
	
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
	public function getTheaterList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('PropertyTheater')->where($where)->count();
		if($count){
			$theaterHandle = $this->model('PropertyTheater')->where($where)->orderby($orderby);
			$start = intval($start);
			$perpage = intval($perpage);
			
			if($perpage > 0){
				$theaterHandle = $theaterHandle->limit($start,$perpage,$count);
			}
			$listdata = $theaterHandle->select();
			
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	/**
	 *
	 * 收藏信息
	 *
	 * @param $theaterIds 收藏ID
	 *
	 * @reutrn int;
	 */
	public function getTheaterInfo($theaterIds){
		$theaterData = array();
		
		$where = array(
			'identity'=>$theaterIds
		);
		
		$theaterList = $this->model('PropertyTheater')->where($where)->select();
		if($theaterList){
			
			if(is_array($theaterIds)){
				$theaterData = $theaterList;
			}else{
				$theaterData = current($theaterList);
			}
			
			
		}
		
		
		return $theaterData;
	}
	
	
		
	/**
	 *
	 * 删除收藏
	 *
	 * @param $theaterId 收藏ID
	 *
	 * @reutrn int;
	 */
	public function removeTheaterId($theaterId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$theaterId
		);
		
		$theaterData = $this->model('PropertyTheater')->where($where)->count();
		if($theaterData){
			
			$output = $this->model('PropertyTheater')->where($where)->delete();
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
	public function checkTheaterTitle($title){
		$theaterId = array();		
		$where = array(
			'title'=>$title,
		);
		
		
		return $this->model('PropertyTheater')->where($where)->count();
	}
	
	/**
	 *
	 * 收藏修改
	 *
	 * @param $theaterId 收藏ID
	 * @param $theaterNewData 收藏数据
	 *
	 * @reutrn int;
	 */
	public function update($theaterNewData,$theaterId){
		$where = array(
			'identity'=>$theaterId
		);
		
		$theaterData = $this->model('PropertyTheater')->where($where)->find();
		if($theaterData){
			
			
			$theaterNewData['lastupdate'] = $this->getTime();
			$this->model('PropertyTheater')->data($theaterNewData)->where($where)->save();
			
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
	public function insert($theaterData){
		$dateline = $this->getTime();
		$theaterData['subscriber_identity'] = $this->session('uid');
		$theaterData['dateline'] = $dateline;
		$theaterData['lastupdate'] = $dateline;
		$theaterData['sn'] = $this->get_sn();
			
		$theaterId = $this->model('PropertyTheater')->data($theaterData)->add();
		if($theaterId){
		}
		return $theaterId;
	}
}