<?php
/**
 *
 * 订单确认
 *
 *
 * 营销
 *
 */
class OrderddConfirmController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'orderddId'=>array('type'=>'digital','tooltip'=>'订单ID'),
			'status'=>array('type'=>'digital','tooltip'=>'订单状态'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		$orderddId = $this->argument('orderddId');
		$status = $this->argument('status');
		
		$orderddData = $this->service('MarketOrderdd')->getOrderddBaseInfo($orderddId);
		if(!$orderddData){
			$this->info('订单不存在',40012);
		}
		
		if(!in_array($status,array(MarketOrderddModel::MARKET_ORDERDD_STATUS_REFUSE))){
			$this->info('状态未定义',40012);
		}
		
		$orderddData = array(
			'status'=>$status
		);
		
		$this->service('MarketOrderdd')->update($orderddData,$orderddId);
	}
}
?>