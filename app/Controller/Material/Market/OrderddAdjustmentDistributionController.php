<?php
/**
 *
 * 调整订单配送方式
 *
 *
 * 营销
 *
 */
class OrderddAdjustmentDistributionController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'orderddId'=>array('type'=>'digital','tooltip'=>'订单ID'),
			'distribution'=>array('type'=>'digital','tooltip'=>'送货方式'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		$orderddId = $this->argument('orderddId');
		$distribution = $this->argument('distribution');
		
		$orderddData = $this->service('MarketOrderdd')->getOrderddBaseInfo($orderddId);
		if(!$orderddData){
			$this->info('订单不存在',40012);
		}
		
		if(!in_array($distribution,array(MarketOrderddModel::MARKET_ORDERDD_DISTRIBUTION_SINCE,MarketOrderddModel::MARKET_ORDERDD_DISTRIBUTION_LOGISTICS))){
			$this->info('未定义的配送方式',40013);
		}
		
		
		
		if(!in_array($orderddData['status'],array(MarketOrderddModel::MARKET_ORDERDD_STATUS_WAIT_DELIVERY))){
			$this->info('谨待发货订单允许执行此操作',40014);
		}
		
		
		$orderddData = array(
			'distribution'=>$distribution
		);
		
		$this->service('MarketOrderdd')->update($orderddData,$orderddId);
	}
}
?>