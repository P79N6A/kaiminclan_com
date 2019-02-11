<?php
/**
 *
 * 货币启用
 *
 * 20180301
 *
 */
class CurrencyEnableController extends Controller {
	
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
		
		$groupInfo = $this->service('ForeignCurrency')->getCurrencyInfo($currencyId);
		if(!$groupInfo){
			$this->info('货币不存在',4101);
		}
		
		if($groupInfo['status'] == ForeignCurrencyModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('ForeignCurrency')->update(array('status'=>ForeignCurrencyModel::PAGINATION_BLOCK_STATUS_ENABLE),$currencyId);
		}
		
		
	}
}
?>