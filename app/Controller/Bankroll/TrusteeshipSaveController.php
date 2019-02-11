<?php
/**
 *
 * 账户编辑
 *
 * 20180301
 *
 */
class TrusteeshipSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'trusteeshipId'=>array('type'=>'digital','tooltip'=>'账户ID','default'=>0),
			'account_identity'=>array('type'=>'digital','tooltip'=>'经纪账户'),
			'finance_account_identity'=>array('type'=>'digital','tooltip'=>'银行账户'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>'')
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$trusteeshipId = $this->argument('trusteeshipId');
		
		$setarr = array(
			'account_identity' => $this->argument('account_identity'),
			'finance_account_identity' => $this->argument('finance_account_identity'),
			'remark' => $this->argument('remark')
		);
		
		if($trusteeshipId){
			$this->service('BankrollTrusteeship')->update($setarr,$trusteeshipId);
		}else{
			
			$this->service('BankrollTrusteeship')->insert($setarr);
		}
	}
}
?>