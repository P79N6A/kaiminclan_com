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
		
		$groupInfo = $this->service('MechanismCurrency')->getCurrencyInfo($currencyId);
		
		if(!$groupInfo){
			$this->info('货币不存在',4101);
		}
		if(!is_array($currencyueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('MechanismCurrency')->removeCurrencyId($removeGroupIds);
		
		$sourceTotal = count($currencyueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>