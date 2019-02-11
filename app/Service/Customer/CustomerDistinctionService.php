<?php
/**
 *
 * 客户
 *
 * 账户
 *
 */
class  CustomerDistinctionService extends Service {
	
	
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
	public function getDistinctionList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('CustomerDistinction')->where($where)->count();
		if($count){
			$distinctionHandle = $this->model('CustomerDistinction')->where($where)->orderby($orderby);
			$start = intval($start);
			$perpage = intval($perpage);
			
			if($perpage > 0){
				$distinctionHandle = $distinctionHandle->limit($start,$perpage,$count);
			}
			$listdata = $distinctionHandle->select();
			
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	/**
	 *
	 * 收藏信息
	 *
	 * @param $distinctionIds 收藏ID
	 *
	 * @reutrn int;
	 */
	public function getDistinctionInfo($distinctionIds){
		$distinctionData = array();
		
		$where = array(
			'identity'=>$distinctionIds
		);
		
		$distinctionList = $this->model('CustomerDistinction')->where($where)->select();
		if($distinctionList){
			
			if(is_array($distinctionIds)){
				$distinctionData = $distinctionList;
			}else{
				$distinctionData = current($distinctionList);
			}
			
			
		}
		
		
		return $distinctionData;
	}
	
	
		
	/**
	 *
	 * 删除收藏
	 *
	 * @param $distinctionId 收藏ID
	 *
	 * @reutrn int;
	 */
	public function removeDistinctionId($distinctionId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$distinctionId
		);
		
		$distinctionData = $this->model('CustomerDistinction')->where($where)->count();
		if($distinctionData){
			
			$output = $this->model('CustomerDistinction')->where($where)->delete();
		}
		
		return $output;
	}
		
	/**
	 *
	 * 检测收藏
	 *
	 * @param $title 数据类型
	 *
	 * @reutrn int;
	 */
	public function checkDistinctionTitle($title){
		$where = array(
			'title'=>$title
		);
		
		
		return $this->model('CustomerDistinction')->where($where)->count();
	}
	
	/**
	 *
	 * 收藏修改
	 *
	 * @param $distinctionId 收藏ID
	 * @param $distinctionNewData 收藏数据
	 *
	 * @reutrn int;
	 */
	public function update($distinctionNewData,$distinctionId){
		$where = array(
			'identity'=>$distinctionId
		);
		
		$distinctionData = $this->model('CustomerDistinction')->where($where)->find();
		if($distinctionData){
			
			
			$distinctionNewData['lastupdate'] = $this->getTime();
			$this->model('CustomerDistinction')->data($distinctionNewData)->where($where)->save();
			
			
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
	public function insert($distinctionData){
		$dateline = $this->getTime();
		$distinctionData['subscriber_identity'] = $this->session('uid');
		$distinctionData['dateline'] = $dateline;
		$distinctionData['lastupdate'] = $dateline;
		$distinctionData['sn'] = $this->get_sn();
			
		
		return $this->model('CustomerDistinction')->data($distinctionData)->add();
		
	}
}