<?php
/**
 *
 * 账户启用
 *
 * 20180301
 *
 */
class AccountEnableController extends Controller {
	
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
		
		$accountId = $this->argument('accountId');
		
		$groupInfo = $this->service('BankrollAccount')->getCatalogInfo($accountId);
		if(!$groupInfo){
			$this->info('账户不存在',4101);
		}
		
		if($groupInfo['status'] == BankrollAccountModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('BankrollAccount')->update(array('status'=>BankrollAccountModel::PAGINATION_BLOCK_STATUS_ENABLE),$accountId);
		}
		
		
	}
}
?>