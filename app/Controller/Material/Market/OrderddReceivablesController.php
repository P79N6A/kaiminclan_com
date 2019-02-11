<?php
/**
 *
 * 订单收款
 *
 * 流程
 *
 * 检测商品
 *
 * 营销
 *
 */
class OrderddReceivablesController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'receivablesId'=>array('type'=>'digital','tooltip'=>'收款ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		$receivablesId = $this->argument('receivablesId');
		
		$receivablesData = $this->service('MarketReceivables')->getreceivablesInfo($receivablesId);

		if(!$receivablesData || $receivablesData['status'] != MarketReceivablesModel::MARKET_RECEIVABLES_STATUS_WAIT_COFNRIM){
			//$this->info('付款信息不存在',40012);
		}
		/*
		$paymentObject = $this->service('MarketPayment')->query($receivablesData['code']);
		if($paymentObject){
			$receivablesData = array(
				'status'=>MarketReceivablesModel::MARKET_RECEIVABLES_STATUS_FINISH
			);
			$this->service('MarketReceivables')->update($receivablesData,$receivablesId);
			
			//设定订单待发货
			$orderddIds = $this->service('MarketOrderdd')->getOrderddIdByReceivabledsId($receivablesId);
			if($orderddIds){
				$this->service('MarketOrderdd')->update(array('status'=>MarketOrderddModel::MARKET_ORDERDD_STATUS_WAIT_DELIVERY),$orderddIds);
			}
		}
		*/
		$this->assign('receivablesData',$receivablesData);
	}
}
?>