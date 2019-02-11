<?php
/**
 *
 * 转出启用
 *
 * 20180301
 *
 */
class ExpensesEnableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'expensesId'=>array('type'=>'digital','tooltip'=>'转出ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$expensesId = $this->argument('expensesId');
		
		$groupInfo = $this->service('BankrollExpenses')->getCatalogInfo($expensesId);
		if(!$groupInfo){
			$this->info('转出不存在',4101);
		}
		
		if($groupInfo['status'] == BankrollExpensesModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('BankrollExpenses')->update(array('status'=>BankrollExpensesModel::PAGINATION_BLOCK_STATUS_ENABLE),$expensesId);
		}
		
		
	}
}
?>