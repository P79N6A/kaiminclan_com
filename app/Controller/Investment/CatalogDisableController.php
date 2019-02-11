<?php
/**
 *
 * 禁用行业
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
		
		if($groupInfo['status'] == InvestmentCatalogModel::PAGINATION_BLOCK_STATUS_ENABLE){
			$this->service('InvestmentCatalog')->update(array('status'=>InvestmentCatalogModel::PAGINATION_BLOCK_STATUS_DISABLED),$catalogId);
		}
	}
}
?>