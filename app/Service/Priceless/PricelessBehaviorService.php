<?php
/**
 *
 * 货币
 *
 * 外汇
 *
 */
class PricelessBehaviorService extends Service
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
	public function getBehaviorList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('PricelessBehavior')->where($where)->count();
		if($count){
			$handle = $this->model('PricelessBehavior')->where($where);
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
	public function checkBehaviorTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('PricelessBehavior')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $behaviorId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getBehaviorInfo($behaviorId,$field = '*'){
		$behaviorData = array();
		
		if(!is_array($behaviorId)){
			$behaviorId = array($behaviorId);
		}
		
		$behaviorId = array_filter(array_map('intval',$behaviorId));
		
		if(!empty($behaviorId)){
			
			$where = array(
				'identity'=>$behaviorId
			);
			
			$behaviorData = $this->model('PricelessBehavior')->field($field)->where($where)->select();
		}
		return $behaviorData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $behaviorId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeBehaviorId($behaviorId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$behaviorId
		);
		
		$behaviorData = $this->model('PricelessBehavior')->where($where)->find();
		if($behaviorData){
			
			$output = $this->model('PricelessBehavior')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $behaviorId 模块ID
	 * @param $behaviorNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($behaviorNewData,$behaviorId){
		$where = array(
			'identity'=>$behaviorId
		);
		
		$behaviorData = $this->model('PricelessBehavior')->where($where)->find();
		if($behaviorData){
			
			$behaviorNewData['lastupdate'] = $this->getTime();
			$this->model('PricelessBehavior')->data($behaviorNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $behaviorNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($behaviorNewData){
		
		$behaviorNewData['subscriber_identity'] =$this->session('uid');
		$behaviorNewData['dateline'] = $this->getTime();
		$behaviorNewData['sn'] = $this->get_sn();
			
		$behaviorNewData['lastupdate'] = $behaviorNewData['dateline'];
		$this->model('PricelessBehavior')->data($behaviorNewData)->add();
	}
}