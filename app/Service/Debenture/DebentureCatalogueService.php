<?php
/**
 *
 * 货币
 *
 * 外汇
 *
 */
class DebentureCatalogueService extends Service
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
	public function getCatalogueList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('DebentureCatalogue')->where($where)->count();
		if($count){
			$handle = $this->model('DebentureCatalogue')->where($where);
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
	public function checkCatalogueTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('DebentureCatalogue')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $catalogueId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getCatalogueInfo($catalogueId,$field = '*'){
		$catalogData = array();
		
		if(!is_array($catalogueId)){
			$catalogueId = array($catalogueId);
		}
		
		$catalogueId = array_filter(array_map('intval',$catalogueId));
		
		if(!empty($catalogueId)){
		
			$where = array(
				'identity'=>$catalogueId
			);
			
			$catalogueData = $this->model('DebentureCatalogue')->field($field)->where($where)->select();
		}
		return $catalogueData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $catalogueId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeCatalogueId($catalogueId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$catalogueId
		);
		
		$catalogueData = $this->model('DebentureCatalogue')->where($where)->find();
		if($catalogueData){
			
			$output = $this->model('DebentureCatalogue')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $catalogueId 模块ID
	 * @param $catalogueNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($catalogueNewData,$catalogueId){
		$where = array(
			'identity'=>$catalogueId
		);
		
		$catalogueData = $this->model('DebentureCatalogue')->where($where)->find();
		if($catalogueData){
			
			$catalogueNewData['lastupdate'] = $this->getTime();
			$this->model('DebentureCatalogue')->data($catalogueNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $catalogueNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($catalogueNewData){
		
		$catalogueNewData['subscriber_identity'] =$this->session('uid');
		$catalogueNewData['dateline'] = $this->getTime();
			
		$catalogueNewData['lastupdate'] = $catalogueNewData['dateline'];
		$this->model('DebentureCatalogue')->data($catalogueNewData)->add();
	}
}