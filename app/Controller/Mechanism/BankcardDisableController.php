<?php
/**
 *
 * 禁用银行卡
 *
 * 20180301
 *
 */
class BankcardDisableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'bankcardId'=>array('type'=>'digital','tooltip'=>'银行卡ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$bankcardId = $this->argument('bankcardId');
		
		$groupInfo = $this->service('MechanismBankcard')->getBankcardInfo($bankcardId);
		if(!$groupInfo){
			$this->info('银行卡不存在',4101);
		}
		
		if($groupInfo['status'] == MechanismBankcardModel::PAGINATION_BLOCK_STATUS_ENABLE){
			$this->service('MechanismBankcard')->update(array('status'=>MechanismBankcardModel::PAGINATION_BLOCK_STATUS_DISABLED),$bankcardId);
		}
	}
}
?>