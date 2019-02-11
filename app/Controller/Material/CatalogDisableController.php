<?php
/**
 *
 * 禁用栏目
 *
 * 20180301
 *
 */
class CatalogDisableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'catalogId'=>array('type'=>'digital','tooltip'=>'栏目ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$catalogId = $this->argument('catalogId');
		
		$groupInfo = $this->service('MaterialCatalog')->getCatalogInfo($catalogId);
		if(!$groupInfo){
			$this->info('栏目不存在',4101);
		}
		
		if($groupInfo['status'] == MaterialCatalogModel::PAGINATION_BLOCK_STATUS_ENABLE){
			$this->service('MaterialCatalog')->update(array('status'=>MaterialCatalogModel::PAGINATION_BLOCK_STATUS_DISABLED),$catalogId);
		}
	}
}
?>