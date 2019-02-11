<?php
/**
 *
 * 启用结算
 *
 * 营销
 *
 */
class SettlementEnabledController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'settlementId'=>array('type'=>'digital','tooltip'=>'结算ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
				
		$settlementId = $this->argument('settlementId');
		
		$settlementInfo = $this->service('MarketSettlement')->getSettlementInfo($settlementId);
			
		if(!$settlementInfo){
			$this->info('结算方式不存在',40012);
		}
		
		
		if($settlementInfo['status'] == MarketSettlementModel::MARKET_SETTLEMENT_STATUS_DISABLED){
			$this->service('MarketSettlement')->update(array('status'=>MarketSettlementModel::MARKET_SETTLEMENT_STATUS_ENABLED),$settlementId);
		}
		
	}
}
?>