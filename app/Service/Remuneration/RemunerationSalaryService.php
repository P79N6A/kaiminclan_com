<?php
/**
 *
 * 货币
 *
 * 外汇
 *
 */
class RemunerationSalaryService extends Service
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
	public function getSalaryList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('RemunerationSalary')->where($where)->count();
		if($count){
			$handle = $this->model('RemunerationSalary')->where($where);
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
	public function checkSalaryTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('RemunerationSalary')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $salaryId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getSalaryInfo($salaryId,$field = '*'){
		$salaryData = array();
		
		if(!is_array($salaryId)){
			$salaryId = array($salaryId);
		}
		
		$salaryId = array_filter(array_map('intval',$salaryId));
		
		if(!empty($salaryId)){
			
			$where = array(
				'identity'=>$salaryId
			);
			
			$salaryData = $this->model('RemunerationSalary')->field($field)->where($where)->select();
		}
		return $salaryData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $salaryId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeSalaryId($salaryId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$salaryId
		);
		
		$salaryData = $this->model('RemunerationSalary')->where($where)->find();
		if($salaryData){
			
			$output = $this->model('RemunerationSalary')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $salaryId 模块ID
	 * @param $salaryNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($salaryNewData,$salaryId){
		$where = array(
			'identity'=>$salaryId
		);
		
		$salaryData = $this->model('RemunerationSalary')->where($where)->find();
		if($salaryData){
			
			$salaryNewData['lastupdate'] = $this->getTime();
			$this->model('RemunerationSalary')->data($salaryNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $salaryNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($salaryNewData){
		
		$salaryNewData['subscriber_identity'] =$this->session('uid');
		$salaryNewData['dateline'] = $this->getTime();
			
		$salaryNewData['lastupdate'] = $salaryNewData['dateline'];
		$this->model('RemunerationSalary')->data($salaryNewData)->add();
	}
}