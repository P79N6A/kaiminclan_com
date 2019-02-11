<?php
/**
 *
 * 订单发货
 *
 *
 * 营销
 *
 */
class OrderdDeliveryController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'orderddId'=>array('type'=>'digital','tooltip'=>'订单ID'),
			'logistics_company'=>array('type'=>'digital','tooltip'=>'快递公司'),
			'logistics_code'=>array('type'=>'digital','tooltip'=>'快递单号'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		$orderddId = $this->argument('orderddId');
		$logistics_company = $this->argument('logistics_company');
		$logistics_code = $this->argument('logistics_code');
		
		$orderddData = $this->service('MarketOrderdd')->getOrderddBaseInfo($orderddId);
		if(!$orderddData){
			$this->info('订单不存在',40012);
		}
		if($orderddData['distribution'] != MarketOrderddModel::MARKET_ORDERDD_DISTRIBUTION_LOGISTICS){
			$this->info('非快递配送订单，禁止此操作',40013);
		}
		
		if($orderddData['status'] != MarketOrderddModel::MARKET_ORDERDD_STATUS_WAIT_DELIVERY){
			$this->info('此订单已完成发货',40015);
		}
		
		$deliveryData = array(
			'delivery_time'=>$this->getTime(),
			'logistics_code'=>$logistics_code,
			'logistics_identity'=>$logistics_company,
			'status'=>MarketOrderddModel::MARKET_ORDERDD_STATUS_WAIT_RECEIPT
		);
		
		$this->service('MarketOrderdd')->update($deliveryData,$orderddId);
	}
}
?>