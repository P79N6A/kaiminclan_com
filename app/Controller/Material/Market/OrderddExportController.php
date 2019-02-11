<?php
/**
 *
 * 订单导出
 *
 *待付款的订单关闭
 * 营销
 *
 */
class OrderddExportController extends Controller {
	
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
		
		$this->assign('listdata',$this->service('MarketOrderdd')->getOrderddInfo($orderddId));
	}
}
?>