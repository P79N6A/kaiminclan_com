<?php
/**
 *
 * 禁用账户
 *
 * 20180301
 *
 */
class TrusteeshipDisableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'trusteeshipId'=>array('type'=>'digital','tooltip'=>'账户ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$trusteeshipId = $this->argument('trusteeshipId');
		
		$groupInfo = $this->service('BankrollTrusteeship')->getCatalogInfo($trusteeshipId);
		if(!$groupInfo){
			$this->info('账户不存在',4101);
		}
		
		if($groupInfo['status'] == BankrollTrusteeshipModel::BANKROLL_TRUSTEESHIP_STATUS_ENABLE){
			$this->service('BankrollTrusteeship')->update(array('status'=>BankrollTrusteeshipModel::BANKROLL_TRUSTEESHIP_STATUS_DISABLED),$trusteeshipId);
		}
	}
}
?>