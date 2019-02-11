<?php
/**
 *
 * 货币
 *
 * 外汇
 *
 */
class RecruitmentMediumService extends Service
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
	public function getMediumList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('RecruitmentMedium')->where($where)->count();
		if($count){
			$handle = $this->model('RecruitmentMedium')->where($where);
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
	public function checkMediumTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('RecruitmentMedium')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $mediumId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getMediumInfo($mediumId,$field = '*'){
		$mediumData = array();
		
		if(!is_array($mediumId)){
			$mediumId = array($mediumId);
		}
		
		$mediumId = array_filter(array_map('intval',$mediumId));
		
		if(!empty($mediumId)){
			
			$where = array(
				'identity'=>$mediumId
			);
			
			$mediumData = $this->model('RecruitmentMedium')->field($field)->where($where)->select();
		}
		return $mediumData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $mediumId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeMediumId($mediumId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$mediumId
		);
		
		$mediumData = $this->model('RecruitmentMedium')->where($where)->find();
		if($mediumData){
			
			$output = $this->model('RecruitmentMedium')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $mediumId 模块ID
	 * @param $mediumNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($mediumNewData,$mediumId){
		$where = array(
			'identity'=>$mediumId
		);
		
		$mediumData = $this->model('RecruitmentMedium')->where($where)->find();
		if($mediumData){
			
			$mediumNewData['lastupdate'] = $this->getTime();
			$this->model('RecruitmentMedium')->data($mediumNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $mediumNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($mediumNewData){
		
		$mediumNewData['subscriber_identity'] =$this->session('uid');
		$mediumNewData['dateline'] = $this->getTime();
			
		$mediumNewData['lastupdate'] = $mediumNewData['dateline'];
		$this->model('RecruitmentMedium')->data($mediumNewData)->add();
	}
}