<?php
/**
 *
 * 订单关闭
 *
 *待付款的订单关闭
 * 营销
 *
 */
class OrderddClosedController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'orderddId'=>array('type'=>'digital','tooltip'=>'订单ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){				
		$orderddId = $this->argument('orderddId');
		
		$orderddData = $this->service('MarketOrderdd')->getOrderddBaseInfo($orderddId);
		if(!$orderddData){
			$this->info('订单不存在',40012);
		}
		
		
		
		if(!in_array($orderddData['status'],array(MarketOrderddModel::MARKET_ORDERDD_STATUS_WAIT_PAYMENT))){
			$this->info('谨待发货订单允许执行此操作',40014);
		}
		
		
		$orderddData = array(
			'status'=>MarketOrderddModel::MARKET_ORDERDD_STATUS_CLOSED
		);
		
		$this->service('MarketOrderdd')->update($orderddData,$orderddId);
		
		//商品解锁库存
		$this->service('Goods')->lockGoodsStorage($lockedGoodInventory);
	}
}
?>