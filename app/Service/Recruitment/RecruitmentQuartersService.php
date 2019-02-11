<?php
/**
 *
 * 货币
 *
 * 外汇
 *
 */
class RecruitmentQuartersService extends Service
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
	public function getQuartersList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('RecruitmentQuarters')->where($where)->count();
		if($count){
			$handle = $this->model('RecruitmentQuarters')->where($where);
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
	public function checkQuartersTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('RecruitmentQuarters')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $quartersId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getQuartersInfo($quartersId,$field = '*'){
		$quartersData = array();
		
		if(!is_array($quartersId)){
			$quartersId = array($quartersId);
		}
		
		$quartersId = array_filter(array_map('intval',$quartersId));
		
		if(!empty($quartersId)){
			
			$where = array(
				'identity'=>$quartersId
			);
			
			$quartersData = $this->model('RecruitmentQuarters')->field($field)->where($where)->select();
		}
		return $quartersData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $quartersId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeQuartersId($quartersId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$quartersId
		);
		
		$quartersData = $this->model('RecruitmentQuarters')->where($where)->find();
		if($quartersData){
			
			$output = $this->model('RecruitmentQuarters')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $quartersId 模块ID
	 * @param $quartersNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($quartersNewData,$quartersId){
		$where = array(
			'identity'=>$quartersId
		);
		
		$quartersData = $this->model('RecruitmentQuarters')->where($where)->find();
		if($quartersData){
			
			$quartersNewData['lastupdate'] = $this->getTime();
			$this->model('RecruitmentQuarters')->data($quartersNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $quartersNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($quartersNewData){
		
		$quartersNewData['subscriber_identity'] =$this->session('uid');
		$quartersNewData['dateline'] = $this->getTime();
			
		$quartersNewData['lastupdate'] = $quartersNewData['dateline'];
		$this->model('RecruitmentQuarters')->data($quartersNewData)->add();
	}
}