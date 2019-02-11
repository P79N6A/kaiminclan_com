<?php
/**
 *
 * 业务启用
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
			'catalogueId'=>array('type'=>'digital','tooltip'=>'业务ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$catalogueId = $this->argument('catalogueId');
		
		$groupInfo = $this->service('ProductionCatalogue')->getCatalogueInfo($catalogueId);
		if(!$groupInfo){
			$this->info('业务不存在',4101);
		}
		
		if($groupInfo['status'] == ProductionCatalogueModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('ProductionCatalogue')->update(array('status'=>ProductionCatalogueModel::PAGINATION_BLOCK_STATUS_ENABLE),$catalogueId);
		}
		
		
	}
}
?>