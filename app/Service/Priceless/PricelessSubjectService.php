<?php
/**
 *
 * 货币
 *
 * 外汇
 *
 */
class PricelessSubjectService extends Service
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
	public function getSubjectList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('PricelessSubject')->where($where)->count();
		if($count){
			$handle = $this->model('PricelessSubject')->where($where);
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
	public function checkSubjectTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('PricelessSubject')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $subjectId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getSubjectInfo($subjectId,$field = '*'){
		$subjectData = array();
		
		if(!is_array($subjectId)){
			$subjectId = array($subjectId);
		}
		
		$subjectId = array_filter(array_map('intval',$subjectId));
		
		if(!empty($subjectId)){
			
			$where = array(
				'identity'=>$subjectId
			);
			
			$subjectData = $this->model('PricelessSubject')->field($field)->where($where)->select();
		}
		return $subjectData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $subjectId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeSubjectId($subjectId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$subjectId
		);
		
		$subjectData = $this->model('PricelessSubject')->where($where)->find();
		if($subjectData){
			
			$output = $this->model('PricelessSubject')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $subjectId 模块ID
	 * @param $subjectNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($subjectNewData,$subjectId){
		$where = array(
			'identity'=>$subjectId
		);
		
		$subjectData = $this->model('PricelessSubject')->where($where)->find();
		if($subjectData){
			
			$subjectNewData['lastupdate'] = $this->getTime();
			$this->model('PricelessSubject')->data($subjectNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $subjectNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($subjectNewData){
		
		$subjectNewData['subscriber_identity'] =$this->session('uid');
		$subjectNewData['dateline'] = $this->getTime();
		$subjectNewData['sn'] = $this->get_sn();
			
		$subjectNewData['lastupdate'] = $subjectNewData['dateline'];
		$this->model('PricelessSubject')->data($subjectNewData)->add();
	}
}