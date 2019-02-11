<?php
/**
 *
 * 禁用支出
 *
 * 20180301
 *
 */
class ExpensesDisableController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'expensesId'=>array('type'=>'digital','tooltip'=>'支出ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$expensesId = $this->argument('expensesId');
		
		$groupInfo = $this->service('DealingsExpenses')->getTemplateInfo($expensesId);
		if(!$groupInfo){
			$this->info('支出不存在',4101);
		}
		
		if($groupInfo['status'] == DealingsExpensesModel::PAGINATION_TEMPLATE_STATUS_ENABLE){
			$this->service('DealingsExpenses')->update(array('status'=>DealingsExpensesModel::PAGINATION_TEMPLATE_STATUS_DISABLED),$expensesId);
		}
	}
}
?>