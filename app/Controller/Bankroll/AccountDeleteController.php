<?php
/**
 *
 * 删除账户
 *
 * 20180301
 *
 */
class AccountDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'accountId'=>array('type'=>'digital','tooltip'=>'账户ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$removeAccountIds = $this->argument('accountId');
		
		$groupInfo = $this->service('BankrollAccount')->getAccountInfo($removeAccountIds);
		
		if(!$groupInfo){
			$this->info('账户不存在',4101);
		}
		
		$this->service('BankrollAccount')->removeAccountId($removeAccountIds);
		
		$sourceTotal = count($accountId);
		$successNum = count($removeAccountIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>