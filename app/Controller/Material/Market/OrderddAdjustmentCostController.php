<?php
/**
 *
 * 调整订单费用
 *
 *
 * 营销
 *
 */
class OrderddAdjustmentCostController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'orderddId'=>array('type'=>'digital','tooltip'=>'订单ID'),
			'freight'=>array('type'=>'money','tooltip'=>'运费'),
			'discount'=>array('type'=>'money','tooltip'=>'优惠金额'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		$orderddId = $this->argument('orderddId');
		$freight_amount = $this->argument('freight');
		$coupon_amount = $this->argument('discount');
		
		$orderddData = $this->service('MarketOrderdd')->getOrderddBaseInfo($orderddId);
		if(!$orderddData){
			$this->info('订单不存在',40012);
		}
		
		if(!in_array($orderddData['status'],array(MarketOrderddModel::MARKET_ORDERDD_STATUS_WAIT_PAYMENT))){
			$this->info('谨待付款订单允许执行此操作',40014);
		}
		
		
		$orderddData = array(
			'freight_amount'=>$freight_amount,
			'coupon_amount'=>$coupon_amount
		);
		
		$this->service('MarketOrderdd')->update($orderddData,$orderddId);
	}
}
?>