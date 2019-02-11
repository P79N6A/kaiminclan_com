<?php
/**
 *
 * 货币
 *
 * 外汇
 *
 */
class RecruitmentPersonnelService extends Service
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
	public function getPersonnelList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('RecruitmentPersonnel')->where($where)->count();
		if($count){
			$handle = $this->model('RecruitmentPersonnel')->where($where);
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
	public function checkPersonnelTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('RecruitmentPersonnel')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $personnelId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getPersonnelInfo($personnelId,$field = '*'){
		$personnelData = array();
		
		if(!is_array($personnelId)){
			$personnelId = array($personnelId);
		}
		
		$personnelId = array_filter(array_map('intval',$personnelId));
		
		if(!empty($personnelId)){
			
			$where = array(
				'identity'=>$personnelId
			);
			
			$personnelData = $this->model('RecruitmentPersonnel')->field($field)->where($where)->select();
		}
		return $personnelData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $personnelId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removePersonnelId($personnelId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$personnelId
		);
		
		$personnelData = $this->model('RecruitmentPersonnel')->where($where)->find();
		if($personnelData){
			
			$output = $this->model('RecruitmentPersonnel')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $personnelId 模块ID
	 * @param $personnelNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($personnelNewData,$personnelId){
		$where = array(
			'identity'=>$personnelId
		);
		
		$personnelData = $this->model('RecruitmentPersonnel')->where($where)->find();
		if($personnelData){
			
			$personnelNewData['lastupdate'] = $this->getTime();
			$this->model('RecruitmentPersonnel')->data($personnelNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $personnelNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($personnelNewData){
		
		$personnelNewData['subscriber_identity'] =$this->session('uid');
		$personnelNewData['dateline'] = $this->getTime();
			
		$personnelNewData['lastupdate'] = $personnelNewData['dateline'];
		$this->model('RecruitmentPersonnel')->data($personnelNewData)->add();
	}
}