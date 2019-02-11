<?php
/**
 *
 * 收款回调
 *
 * 流程
 *
 * 检测商品
 *
 * 营销
 *
 */
class OrderddNotifyController extends Controller {
	
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
		
		$receivablesData = $this->service('MarketReceivables')->getReceivablesInfo($receivablesId);
		
		if(!$receivablesData || $receivablesData['status'] != MarketReceivablesModel::MARKET_RECEIVABLES_STATUS_WAIT_PAYMENT){
			$this->info('付款信息不存在',40012);
		}
		
		
		$cnt = 0;
		$max = 10;
		$isSuccess = 0;
		while(true){
			
			$paymentObject = $this->service('MarketPayment')->query($receivablesData['serial']);
			$this->service('FoundationIndicent')->newIndicent('支付查询'.$receivablesData['serial'],json_encode($paymentObject));
			if($paymentObject['return_code'] == 'SUCCESS' && $paymentObject['result_code'] == 'SUCCESS')
			{
				$isSuccess = 1;
				$receivablesData = array(
					'status'=>MarketReceivablesModel::MARKET_RECEIVABLES_STATUS_FINISH
				);
				$this->service('MarketReceivables')->update($receivablesData,$receivablesId);
				
				//设定订单待发货
				$orderddIds = $this->service('MarketOrderdd')->getOrderddIdByReceivabledsId($receivablesId);
				if($orderddIds){
					$this->service('MarketOrderdd')->update(array('status'=>MarketOrderddModel::MARKET_ORDERDD_STATUS_WAIT_DELIVERY),$orderddIds);
				}
				break;
			}
			$cnt++;
			if($cnt == $max)
			{
				break;
			}
			sleep(2);
		}
		$this->assign('success',$isSuccess);
		$this->assign('code',$receivablesData['serial']);
	}
}
?>