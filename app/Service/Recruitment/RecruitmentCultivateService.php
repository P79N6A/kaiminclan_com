<?php
/**
 *
 * 货币
 *
 * 外汇
 *
 */
class RecruitmentCultivateService extends Service
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
	public function getCultivateList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('RecruitmentCultivate')->where($where)->count();
		if($count){
			$handle = $this->model('RecruitmentCultivate')->where($where);
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
	public function checkCultivateTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('RecruitmentCultivate')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $cultivateId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getCultivateInfo($cultivateId,$field = '*'){
		$cultivateData = array();
		
		if(!is_array($cultivateId)){
			$cultivateId = array($cultivateId);
		}
		
		$cultivateId = array_filter(array_map('intval',$cultivateId));
		
		if(!empty($cultivateId)){
			
			$where = array(
				'identity'=>$cultivateId
			);
			
			$cultivateData = $this->model('RecruitmentCultivate')->field($field)->where($where)->select();
		}
		return $cultivateData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $cultivateId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeCultivateId($cultivateId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$cultivateId
		);
		
		$cultivateData = $this->model('RecruitmentCultivate')->where($where)->find();
		if($cultivateData){
			
			$output = $this->model('RecruitmentCultivate')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $cultivateId 模块ID
	 * @param $cultivateNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($cultivateNewData,$cultivateId){
		$where = array(
			'identity'=>$cultivateId
		);
		
		$cultivateData = $this->model('RecruitmentCultivate')->where($where)->find();
		if($cultivateData){
			
			$cultivateNewData['lastupdate'] = $this->getTime();
			$this->model('RecruitmentCultivate')->data($cultivateNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $cultivateNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($cultivateNewData){
		
		$cultivateNewData['subscriber_identity'] =$this->session('uid');
		$cultivateNewData['dateline'] = $this->getTime();
			
		$cultivateNewData['lastupdate'] = $cultivateNewData['dateline'];
		$this->model('RecruitmentCultivate')->data($cultivateNewData)->add();
	}
}