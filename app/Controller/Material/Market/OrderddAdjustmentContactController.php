<?php
/**
 *
 * 调整订单收货人
 *
 *
 * 营销
 *
 */
class OrderddAdjustmentContactController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'orderddId'=>array('type'=>'digital','tooltip'=>'订单ID'),
			'contactId'=>array('type'=>'digital','tooltip'=>'收货人'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		$orderddId = $this->argument('orderddId');
		$contact_identity = $this->argument('contactId');
		
		$orderddData = $this->service('MarketOrderdd')->getOrderddBaseInfo($orderddId);
		if(!$orderddData){
			$this->info('订单不存在',40012);
		}
		
		if(!in_array($orderddData['distribution'],array(MarketOrderddModel::MARKET_ORDERDD_DISTRIBUTION_LOGISTICS))){
			$this->info('非快递配送订单不允许执行此操作',40013);
		}
		
		if(!in_array($orderddData['status'],array(MarketOrderddModel::MARKET_ORDERDD_STATUS_WAIT_DELIVERY))){
			$this->info('谨待发货订单允许执行此操作',40014);
		}
		
		
		$orderddData = array(
			'contact_identity'=>$contact_identity
		);
		
		$this->service('MarketOrderdd')->update($orderddData,$orderddId);
	}
}
?>