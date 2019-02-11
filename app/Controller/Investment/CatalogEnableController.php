<?php
/**
 *
 * 行业启用
 *
 * 20180301
 *
 */
class CatalogEnableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'catalogId'=>array('type'=>'digital','tooltip'=>'行业ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$catalogId = $this->argument('catalogId');
		
		$groupInfo = $this->service('InvestmentCatalog')->getCatalogInfo($catalogId);
		if(!$groupInfo){
			$this->info('行业不存在',4101);
		}
		
		if($groupInfo['status'] == InvestmentCatalogModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('InvestmentCatalog')->update(array('status'=>InvestmentCatalogModel::PAGINATION_BLOCK_STATUS_ENABLE),$catalogId);
		}
		
		
	}
}
?>