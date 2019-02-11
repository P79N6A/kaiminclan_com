<?php
/**
 *
 * 货币
 *
 * 外汇
 *
 */
class QuotationIndicatrixService extends Service
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
	public function getIndicatrixList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('QuotationIndicatrix')->where($where)->count();
		if($count){
			$handle = $this->model('QuotationIndicatrix')->where($where);
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
	/**
	 *
	 * 检测岗位名称
	 *
	 * @param $subscriberName 账户名称
	 *
	 * @reutrn int;
	 */
	public function checkIndicatrixTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('QuotationIndicatrix')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $indicatrixId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getIndicatrixInfo($indicatrixId,$field = '*'){
		
		$where = array(
			'identity'=>$indicatrixId
		);
		
		$indicatrixData = $this->model('QuotationIndicatrix')->field($field)->where($where)->find();
		
		return $indicatrixData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $indicatrixId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeIndicatrixId($indicatrixId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$indicatrixId
		);
		
		$indicatrixData = $this->model('QuotationIndicatrix')->where($where)->find();
		if($indicatrixData){
			
			$output = $this->model('QuotationIndicatrix')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $indicatrixId 模块ID
	 * @param $indicatrixNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($indicatrixNewData,$indicatrixId){
		$where = array(
			'identity'=>$indicatrixId
		);
		
		$indicatrixData = $this->model('QuotationIndicatrix')->where($where)->find();
		if($indicatrixData){
			
			$indicatrixNewData['lastupdate'] = $this->getTime();
			$this->model('QuotationIndicatrix')->data($indicatrixNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $indicatrixNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($indicatrixNewData){
		
		$indicatrixNewData['subscriber_identity'] =$this->session('uid');
		$indicatrixNewData['dateline'] = $this->getTime();
			
		$indicatrixNewData['lastupdate'] = $indicatrixNewData['dateline'];
		$this->model('QuotationIndicatrix')->data($indicatrixNewData)->add();
	}
}