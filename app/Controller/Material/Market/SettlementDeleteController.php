<?php
/**
 *
 * 结算删除
 *
 * 营销
 *
 */
class SettlementDeleteController extends Controller {
	
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
		
		$settlementData = $this->service('MarketSettlement')->getSettlementInfo($settlementId);
			
		if(!$settlementData){
			$this->info('结算不存在',40001);
		}
			
		
		
		$this->service('MarketSettlement')->removeSettlementId($settlementId);
		
		
	}
}
?>