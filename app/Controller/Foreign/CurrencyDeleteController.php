<?php
/**
 *
 * 删除货币
 *
 * 20180301
 *
 */
class CurrencyDeleteController extends Controller {
	
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
		
		$currencyInfo = $this->service('ForeignCurrency')->getCurrencyInfo($currencyId);
		
		if(!$currencyInfo){
			$this->info('货币不存在',4101);
		}
		
		if(!is_array($currencyId)){
			$currencyInfo = array($currencyInfo);
		}
		
		
		$removeCurrencyIds = array();
		foreach($currencyInfo as $key=>$currency){
			$removeCurrencyIds[] = $currency['identity'];
		}
		
		$this->service('ForeignCurrency')->removeCurrencyId($removeCurrencyIds);
		
		$sourceTotal = count($currencyId);
		$successNum = count($removeCurrencyIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>