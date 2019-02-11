<?php
/**
 *
 * 货币
 *
 * 外汇
 *
 */
class PricelessReorganizeService extends Service
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
	public function getReorganizeList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('PricelessReorganize')->where($where)->count();
		if($count){
			$handle = $this->model('PricelessReorganize')->where($where);
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
	public function checkReorganizeTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('PricelessReorganize')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $reorganizeId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getReorganizeInfo($reorganizeId,$field = '*'){
		$reorganizeData = array();
		
		if(!is_array($reorganizeId)){
			$reorganizeId = array($reorganizeId);
		}
		
		$reorganizeId = array_filter(array_map('intval',$reorganizeId));
		
		if(!empty($reorganizeId)){
			
			$where = array(
				'identity'=>$reorganizeId
			);
			
			$reorganizeData = $this->model('PricelessReorganize')->field($field)->where($where)->select();
		}
		return $reorganizeData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $reorganizeId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeReorganizeId($reorganizeId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$reorganizeId
		);
		
		$reorganizeData = $this->model('PricelessReorganize')->where($where)->find();
		if($reorganizeData){
			
			$output = $this->model('PricelessReorganize')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $reorganizeId 模块ID
	 * @param $reorganizeNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($reorganizeNewData,$reorganizeId){
		$where = array(
			'identity'=>$reorganizeId
		);
		
		$reorganizeData = $this->model('PricelessReorganize')->where($where)->find();
		if($reorganizeData){
			
			$reorganizeNewData['lastupdate'] = $this->getTime();
			$this->model('PricelessReorganize')->data($reorganizeNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $reorganizeNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($reorganizeNewData){
		
		$reorganizeNewData['subscriber_identity'] =$this->session('uid');
		$reorganizeNewData['dateline'] = $this->getTime();
		$reorganizeNewData['sn'] = $this->get_sn();
			
		$reorganizeNewData['lastupdate'] = $reorganizeNewData['dateline'];
		$this->model('PricelessReorganize')->data($reorganizeNewData)->add();
	}
}