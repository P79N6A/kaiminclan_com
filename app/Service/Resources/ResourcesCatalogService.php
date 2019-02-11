<?php
/**
 * 目录
 *
 * 资源库
 *
 */
class  ResourcesCatalogService extends Service {
	
	
	/**
	 *
	 * 目录信息
	 *
	 * @param $field 目录字段
	 * @param $status 目录状态
	 *
	 * @reutrn array;
	 */
	public function getCatalogList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('ResourcesCatalog')->where($where)->count();
		if($count){
			$listdata = $this->model('ResourcesCatalog')->where($where)->orderby($order)->limit($start,$perpage,$count)->select();
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	/**
	 *
	 * 目录信息
	 *
	 * @param $CatalogId 目录ID
	 *
	 * @reutrn array;
	 */
	public function getCatalogInfo($CatalogId,$field = '*'){
		
		$where = array(
			'identity'=>$CatalogId
		);
		
		$CatalogData = $this->model('ResourcesCatalog')->field($field)->where($where)->find();
		
		return $CatalogData;
	}
	/**
	 *
	 * 检测目录名称
	 *
	 * @param $CatalogName 目录名称
	 *
	 * @reutrn int;
	 */
	public function checkTitle($CatalogName){
		if($CatalogName){
			$where = array(
				'title'=>$CatalogName,
				'subscriber_identity'=>$this->session('uid')
			);
			return $this->model('ResourcesCatalog')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除目录
	 *
	 * @param $CatalogId 目录ID
	 *
	 * @reutrn int;
	 */
	public function removeCatalogId($CatalogId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$CatalogId
		);
		
		$CatalogData = $this->model('ResourcesCatalog')->where($where)->select();
		if($CatalogData){
			
			$output = $this->model('ResourcesCatalog')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 目录修改
	 *
	 * @param $CatalogId 目录ID
	 * @param $CatalogNewData 目录数据
	 *
	 * @reutrn int;
	 */
	public function update($CatalogNewData,$CatalogId){
		$where = array(
			'identity'=>$CatalogId
		);
		
		$CatalogData = $this->model('ResourcesCatalog')->where($where)->find();
		if($CatalogData){
			
			$CatalogNewData['lastupdate'] = $this->getTime();
			$this->model('ResourcesCatalog')->data($CatalogNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新目录
	 *
	 * @param $title 目录名称
	 * @param $summary 目录介绍
	 * @param $permission 目录权限
	 * @param $status 目录状态
	 *
	 * @reutrn int;
	 */
	public function insert($title,$catalog_identity,$remark,$status){
		$CatalogNewData = array(
			'title'=>$title,
			'catalog_identity'=>$catalog_identity,
			'remark'=>$remark,
			'business_identity'=>$this->session('business_identity'),
			'status'=>$status,
			'subscriber_identity'=>$this->session('uid'),
			'dateline'=>$this->getTime()
		);
			
		$CatalogNewData['lastupdate'] = $CatalogNewData['dateline'];
		$this->model('ResourcesCatalog')->data($CatalogNewData)->add();
	}
}