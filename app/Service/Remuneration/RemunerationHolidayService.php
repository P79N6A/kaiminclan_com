<?php
/**
 *
 * 货币
 *
 * 外汇
 *
 */
class RemunerationHolidayService extends Service
{
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $field 模块字段
	 * @param $status 模块状态
	 *
	 * @reutrn array;
	 */
	public function getHolidayList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('RemunerationHoliday')->where($where)->count();
		if($count){
			$handle = $this->model('RemunerationHoliday')->where($where);
			if($start > 0 && $perpage > 0){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	/**
	 *
	 * 检测岗位名称
	 *
	 * @param $subscriberName 账户名称
	 *
	 * @reutrn int;
	 */
	public function checkHolidayTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('RemunerationHoliday')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $holidayId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getHolidayInfo($holidayId,$field = '*'){
		$holidayData = array();
		
		if(!is_array($holidayId)){
			$holidayId = array($holidayId);
		}
		
		$holidayId = array_filter(array_map('intval',$holidayId));
		
		if(!empty($holidayId)){
			
			$where = array(
				'identity'=>$holidayId
			);
			
			$holidayData = $this->model('RemunerationHoliday')->field($field)->where($where)->select();
		}
		return $holidayData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $holidayId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeHolidayId($holidayId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$holidayId
		);
		
		$holidayData = $this->model('RemunerationHoliday')->where($where)->find();
		if($holidayData){
			
			$output = $this->model('RemunerationHoliday')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $holidayId 模块ID
	 * @param $holidayNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($holidayNewData,$holidayId){
		$where = array(
			'identity'=>$holidayId
		);
		
		$holidayData = $this->model('RemunerationHoliday')->where($where)->find();
		if($holidayData){
			
			$holidayNewData['lastupdate'] = $this->getTime();
			$this->model('RemunerationHoliday')->data($holidayNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $holidayNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($holidayNewData){
		
		$holidayNewData['subscriber_identity'] =$this->session('uid');
		$holidayNewData['dateline'] = $this->getTime();
			
		$holidayNewData['lastupdate'] = $holidayNewData['dateline'];
		$this->model('RemunerationHoliday')->data($holidayNewData)->add();
	}
}