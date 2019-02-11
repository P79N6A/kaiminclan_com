<?php
/**
 *
 * 证券启用
 *
 * 20180301
 *
 */
class StockEnableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'stockId'=>array('type'=>'digital','tooltip'=>'证券ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$stockId = $this->argument('stockId');
		
		$groupInfo = $this->service('SecuritiesStock')->getStockInfo($stockId);
		if(!$groupInfo){
			$this->info('证券不存在',4101);
		}
		
		if($groupInfo['status'] == SecuritiesStockModel::PAGINATION_ITEM_STATUS_DISABLED){
			$this->service('SecuritiesStock')->update(array('status'=>SecuritiesStockModel::PAGINATION_ITEM_STATUS_ENABLE),$stockId);
		}
		
		
	}
}
?>