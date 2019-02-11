<?php
/**
 *
 * 目录启用
 *
 * 20180301
 *
 */
class CatalogueEnableController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'catalogueId'=>array('type'=>'digital','tooltip'=>'目录ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$catalogueId = $this->argument('catalogueId');
		
		$groupInfo = $this->service('FoundationCatalogue')->getCatalogueInfo($catalogueId);
		if(!$groupInfo){
			$this->info('目录不存在',4101);
		}
		
		if($groupInfo['status'] == FoundationCatalogueModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('FoundationCatalogue')->update(array('status'=>FoundationCatalogueModel::PAGINATION_BLOCK_STATUS_ENABLE),$catalogueId);
		}
		
		
	}
}
?>