<?php
/**
 *
 * 订单支付
 *
 * 流程
 *
 * 检测商品
 *
 * 营销
 *
 */
class OrderddPaymentController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'receivablesId'=>array('type'=>'digital','tooltip'=>'付款ID')
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$receivablesId = $this->argument('receivablesId');
		
		$receivablesData = $this->service('MarketReceivables')->getReceivablesInfo($receivablesId);
		
		if(!$receivablesData || $receivablesData['status'] != MarketReceivablesModel::MARKET_RECEIVABLES_STATUS_WAIT_PAYMENT){
			//$this->info('付款信息不存在',40012);
		}
		
		$paymentObject = $this->service('MarketPayment')->payment($receivablesData['code'],$receivablesData['amount'],$receivablesData['mode']);
		if($paymentObject){
			$newReceivablesData = array(
				'serial'=>$paymentObject['code']
			);
			$this->service('MarketReceivables')->update($newReceivablesData,$receivablesId);
		}
		
		if($receivablesData['mode'] == MarketPaymentService::MARKET_PAYMENT_MODE_SCAN_CODE){
			$this->service('ResourcesQrcode')->sendCode($paymentObject['data']);
		}else{
			$this->assign('payment',$paymentObject['data']);
		}
	}
}
?>