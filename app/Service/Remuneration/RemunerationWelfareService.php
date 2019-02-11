<?php
/**
 *
 * 货币
 *
 * 外汇
 *
 */
class RemunerationWelfareService extends Service
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
	public function getWelfareList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('RemunerationWelfare')->where($where)->count();
		if($count){
			$handle = $this->model('RemunerationWelfare')->where($where);
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
	public function checkWelfareTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('RemunerationWelfare')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $welfareId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getWelfareInfo($welfareId,$field = '*'){
		$welfareData = array();
		
		if(!is_array($welfareId)){
			$welfareId = array($welfareId);
		}
		
		$welfareId = array_filter(array_map('intval',$welfareId));
		
		if(!empty($welfareId)){
			
			$where = array(
				'identity'=>$welfareId
			);
			
			$welfareData = $this->model('RemunerationWelfare')->field($field)->where($where)->select();
		}
		return $welfareData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $welfareId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeWelfareId($welfareId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$welfareId
		);
		
		$welfareData = $this->model('RemunerationWelfare')->where($where)->find();
		if($welfareData){
			
			$output = $this->model('RemunerationWelfare')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $welfareId 模块ID
	 * @param $welfareNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($welfareNewData,$welfareId){
		$where = array(
			'identity'=>$welfareId
		);
		
		$welfareData = $this->model('RemunerationWelfare')->where($where)->find();
		if($welfareData){
			
			$welfareNewData['lastupdate'] = $this->getTime();
			$this->model('RemunerationWelfare')->data($welfareNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $welfareNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($welfareNewData){
		
		$welfareNewData['subscriber_identity'] =$this->session('uid');
		$welfareNewData['dateline'] = $this->getTime();
			
		$welfareNewData['lastupdate'] = $welfareNewData['dateline'];
		$this->model('RemunerationWelfare')->data($welfareNewData)->add();
	}
}