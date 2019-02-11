<?php
/**
 *
 * 目录
 *
 * 统计分析
 *
 */
class QuotationFrameworkService extends Service
{
	
	/**
	 *
	 * 目录信息
	 *
	 * @param $field 目录字段
	 * @param $status 目录状态
	 *
	 * @reutrn array;
	 */
	public function getFrameworkList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('QuotationFramework')->where($where)->count();
		if($count){
			$handle = $this->model('QuotationFramework')->where($where);
			if($order){
				$handle->orderby($order);
			}
			
			if($perpage > 0){
				$handle = $handle->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	public function fetchFrameworkIdByPrincipalId($principalId){
		
		$where = array(
			'principal_identity'=>$principalId
		);
		$listdata = $this->model('QuotationFramework')->field('identity,code')->where($where)->select();
		
		return $listdata;
	}
	/**
	 *
	 * 检测岗位名称
	 *
	 * @param $subscriberName 账户名称
	 *
	 * @reutrn int;
	 */
	public function checkFrameworkTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('QuotationFramework')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 目录信息
	 *
	 * @param $frameworkId 目录ID
	 *
	 * @reutrn array;
	 */
	public function getFrameworkInfo($frameworkId,$field = '*'){
		
		$where = array(
			'identity'=>$frameworkId
		);
		
		$frameworkData = $this->model('QuotationFramework')->field($field)->where($where)->find();
		
		return $frameworkData;
	}
	
	/**
	 *
	 * 删除目录
	 *
	 * @param $frameworkId 目录ID
	 *
	 * @reutrn int;
	 */
	public function removeFrameworkId($frameworkId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$frameworkId
		);
		
		$frameworkData = $this->model('QuotationFramework')->where($where)->find();
		if($frameworkData){
			
			$output = $this->model('QuotationFramework')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 目录修改
	 *
	 * @param $frameworkId 目录ID
	 * @param $frameworkNewData 目录数据
	 *
	 * @reutrn int;
	 */
	public function update($frameworkNewData,$frameworkId){
		$where = array(
			'identity'=>$frameworkId
		);
		
		$frameworkData = $this->model('QuotationFramework')->where($where)->find();
		if($frameworkData){
			
			$frameworkNewData['lastupdate'] = $this->getTime();
			$this->model('QuotationFramework')->data($frameworkNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新目录
	 *
	 * @param $frameworkNewData 目录数据
	 *
	 * @reutrn int;
	 */
	public function insert($frameworkNewData){
		
		$frameworkNewData['subscriber_identity'] =$this->session('uid');
		$frameworkNewData['dateline'] = $this->getTime();
			
		$frameworkNewData['lastupdate'] = $frameworkNewData['dateline'];
		$this->model('QuotationFramework')->data($frameworkNewData)->add();
	}
}