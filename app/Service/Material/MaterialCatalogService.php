<?php
/**
 *
 * 合作伙伴
 *
 * 新闻
 *
 */
class MaterialCatalogService extends Service
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
	public function getCatalogList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('MaterialCatalog')->where($where)->count();
		if($count){
			$handle = $this->model('MaterialCatalog')->where($where);
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
	public function checkCatalogTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('MaterialCatalog')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $catalogId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getCatalogInfo($catalogId,$field = '*'){
		$catalogData = array();
		
		if(!is_array($catalogId)){
			$catalogId = array($catalogId);
		}
		
		$catalogId = array_filter(array_map('intval',$catalogId));
		
		if(!empty($catalogId)){
			$where = array(
				'identity'=>$catalogId
			);
			
			$catalogData = $this->model('MaterialCatalog')->field($field)->where($where)->select();
		}
		return $catalogData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $catalogId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeCatalogId($catalogId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$catalogId
		);
		
		$catalogData = $this->model('MaterialCatalog')->where($where)->find();
		if($catalogData){
			
			$output = $this->model('MaterialCatalog')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $catalogId 模块ID
	 * @param $catalogNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($catalogNewData,$catalogId){
		$where = array(
			'identity'=>$catalogId
		);
		
		$catalogData = $this->model('MaterialCatalog')->where($where)->find();
		if($catalogData){
			
			$catalogNewData['lastupdate'] = $this->getTime();
			$this->model('MaterialCatalog')->data($catalogNewData)->where($where)->save();
            $this->service('PropertyCapital')->pushCatalogueCapital($catalogId,$catalogNewData['title']);
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $catalogNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($catalogNewData){

		$catalogNewData['subscriber_identity'] =$this->session('uid');
		$catalogNewData['dateline'] = $this->getTime();

		$catalogNewData['lastupdate'] = $catalogNewData['dateline'];
		$catalogueId = $this->model('MaterialCatalog')->data($catalogNewData)->add();
		if($catalogueId){
            $this->service('PropertyCapital')->pushCatalogueCapital($catalogueId,$catalogNewData['title']);
        }
		return $catalogueId;
	}
}