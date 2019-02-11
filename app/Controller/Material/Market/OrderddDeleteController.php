<?php
/**
 *
 * 订单删除
 *
 * 取消或者无效的订单，可以删除，
 *
 * 营销
 *
 */
class OrderddDeleteController extends Controller {
	
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
		if($orderddData['subscriber_identity'] != intval($this->session('uid'))){
			if(!in_array($orderddData['status'],array(MarketOrderddModel::MARKET_ORDERDD_STATUS_CANNEL,MarketOrderddModel::MARKET_ORDERDD_STATUS_INVALID))){
				$this->info('非取消或者无效的订单，禁止删除',40013);
			}
		}
		
		
		$orderddData = array(
			'status'=>MarketOrderddModel::MARKET_ORDERDD_STATUS_REMOVE
		);
		
		$this->service('MarketOrderdd')->update($orderddData,$orderddId);
		
		//商品解锁库存
		$this->service('Goods')->lockGoodsStorage($lockedGoodInventory);
	}
}
?>