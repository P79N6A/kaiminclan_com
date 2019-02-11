<?php
/**
 *
 * 删除证券
 *
 * 20180301
 *
 */
class StockDeleteController extends Controller {
	
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
		
		$stockList = $this->service('SecuritiesStock')->getStockInfo($stockId);
		
		if(!$stockList){
			$this->info('证券不存在',4101);
		}
		
		$this->service('SecuritiesStock')->removeStockId($stockId);
		
		
	}
}
?>