<?php
/**
 *
 * 分类启用
 *
 * 20180301
 *
 */
class CatalogueEnableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'catalogueId'=>array('type'=>'digital','tooltip'=>'分类ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$catalogueId = $this->argument('catalogueId');
		
		$groupInfo = $this->service('FundCatalogue')->getCatalogueInfo($catalogueId);
		if(!$groupInfo){
			$this->info('分类不存在',4101);
		}
		
		if($groupInfo['status'] == FundCatalogueModel::FUND_CATALOGUE_STATUS_DISABLED){
			$this->service('FundCatalogue')->update(array('status'=>FundCatalogueModel::FUND_CATALOGUE_STATUS_ENABLE),$catalogueId);
		}
		
		
	}
}
?>