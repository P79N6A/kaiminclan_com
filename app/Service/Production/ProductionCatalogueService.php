<?php
/**
 *
 * 模块
 *
 * 科技
 *
 */
class ProductionCatalogueService extends Service
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
		
		$count = $this->model('ProductionCatalogue')->where($where)->count();
		if($count){
			$handle = $this->model('ProductionCatalogue')->where($where);
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
			return $this->model('ProductionCatalogue')->where($where)->count();
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
		
		$where = array(
			'identity'=>$catalogueId
		);
		
		$catalogueData = $this->model('ProductionCatalogue')->field($field)->where($where)->find();
		
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
		
		$catalogueData = $this->model('ProductionCatalogue')->where($where)->find();
		if($catalogueData){
			
			$output = $this->model('ProductionCatalogue')->where($where)->delete();
			
			$this->service('PaginationItem')->removeCatalogueIdAllItem($catalogueId);
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
		
		$catalogueData = $this->model('ProductionCatalogue')->where($where)->find();
		if($catalogueData){
			
			$catalogueNewData['lastupdate'] = $this->getTime();
			$this->model('ProductionCatalogue')->data($catalogueNewData)->where($where)->save();
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
		$catalogueNewData['sn'] = $this->get_sn();
			
		$catalogueNewData['lastupdate'] = $catalogueNewData['dateline'];
		$catalogueId = $this->model('ProductionCatalogue')->data($catalogueNewData)->add();
		if($catalogueId){
		}
		return $catalogueId;
	}
}