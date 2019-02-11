<?php
/**
 *
 * 支出启用
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
		
		if($groupInfo['status'] == DealingsExpensesModel::PAGINATION_TEMPLATE_STATUS_DISABLED){
			$this->service('DealingsExpenses')->update(array('status'=>DealingsExpensesModel::PAGINATION_TEMPLATE_STATUS_ENABLE),$expensesId);
		}
		
		
	}
}
?>