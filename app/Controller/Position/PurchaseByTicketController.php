<?php
/**
 *
 * 开仓编辑
 *
 * 20180301
 *
 */
class PurchaseByTicketController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	protected $accept = 'application/json';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'ticket'=>array('type'=>'digital','tooltip'=>'识别码'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$ticket = $this->argument('ticket');
		
		if(in_array($ticket,array('gold','silver'))){
			$symbolList = array(
				'gold'=>'xauusd','silver'=>'xagusd'
			);
			$symbol = $symbolList[$symbol];
		}
		
		$this->assign('purchaseData',$this->service('PositionPurchase')->getPurchaseByTicket($ticket));
	}
}
?>