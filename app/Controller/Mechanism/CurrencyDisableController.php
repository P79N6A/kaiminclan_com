<?php
/**
 *
 * 禁用货币
 *
 * 20180301
 *
 */
class CurrencyDisableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'currencyId'=>array('type'=>'digital','tooltip'=>'货币ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$currencyId = $this->argument('currencyId');
		
		$groupInfo = $this->service('MechanismCurrency')->getCurrencyInfo($currencyId);
		if(!$groupInfo){
			$this->info('货币不存在',4101);
		}
		
		if($groupInfo['status'] == MechanismCurrencyModel::PAGINATION_BLOCK_STATUS_ENABLE){
			$this->service('MechanismCurrency')->update(array('status'=>MechanismCurrencyModel::PAGINATION_BLOCK_STATUS_DISABLED),$currencyId);
		}
	}
}
?>