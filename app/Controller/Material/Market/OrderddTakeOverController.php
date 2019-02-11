<?php
/**
 *
 * 订单收货
 *
 *
 * 营销
 *
 */
class OrderddTakeOverController extends Controller {
	
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
		
		if($orderddData['status'] != MarketOrderddModel::MARKET_ORDERDD_STATUS_WAIT_RECEIPT){
			$this->info('此订单还未发货',40021);
		}
		
		$deliveryData = array(
			'takeover_time'=>$this->getTime(),
			'status'=>MarketOrderddModel::MARKET_ORDERDD_STATUS_FINISH
		);
		
		$this->service('MarketOrderdd')->update($deliveryData,$orderddId);
	}
}
?>