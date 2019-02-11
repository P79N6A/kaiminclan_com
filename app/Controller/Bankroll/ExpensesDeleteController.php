<?php
/**
 *
 * 删除转出
 *
 * 20180301
 *
 */
class ExpensesDeleteController extends Controller {
	
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
		
		$removeExpensesIds = $this->argument('expensesId');
		
		$groupInfo = $this->service('BankrollExpenses')->getExpensesInfo($removeExpensesIds);
		
		if(!$groupInfo){
			$this->info('转出不存在',4101);
		}
		
		$this->service('BankrollExpenses')->removeExpensesId($removeExpensesIds);
		
		$sourceTotal = count($expensesId);
		$successNum = count($removeExpensesIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>