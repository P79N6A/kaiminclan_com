<?php
/**
 *
 * 资源目录锁定
 *
 * 20180301
 *
 */
class CatalogLockedController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'catalogId'=>array('type'=>'digital','tooltip'=>'目录ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$catalogId = $this->argument('catalogId');
		
		$groupInfo = $this->service('ResourcesCatalog')->getCatalogInfo($catalogId);
		if(!$groupInfo){
			$this->info('目录不存在',4101);
		}
		
		if($groupInfo['status'] == ResourcesCatalogModel::RESOURCES_CATALOG_STATUS_ENABLE){
			$this->service('ResourcesCatalog')->update(array('status'=>ResourcesCatalogModel::RESOURCES_CATALOG_STATUS_LOCKED),$catalogId);
		}
	}
}
?>