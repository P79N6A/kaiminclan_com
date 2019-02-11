<?php
/**
 *
 * 删除账户
 *
 * 20180301
 *
 */
class TrusteeshipDeleteController extends Controller {
	
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
		
		$removeTrusteeshipIds = $this->argument('trusteeshipId');
		
		$groupInfo = $this->service('BankrollTrusteeship')->getTrusteeshipInfo($removeTrusteeshipIds);
		
		if(!$groupInfo){
			$this->info('账户不存在',4101);
		}
		
		$this->service('BankrollTrusteeship')->removeTrusteeshipId($removeTrusteeshipIds);
		
		$sourceTotal = count($trusteeshipId);
		$successNum = count($removeTrusteeshipIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>